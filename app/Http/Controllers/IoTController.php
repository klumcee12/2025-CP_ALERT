<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\PresenceCall;
use App\Models\LocationLog;
use App\Models\LocationPingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IoTController extends Controller
{
    /**
     * Optional: receive alerts or events sent from the device to the server.
     * Currently saves a simple row into an `iot_alerts` table if you create one.
     */
    public function receiveAlert(Request $request)
    {
        // Optional shared-secret protection for physical devices
        $expectedKey = config('app.iot_shared_secret');
        if (! empty($expectedKey) && $request->header('X-IOT-KEY') !== $expectedKey) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $deviceId = $request->input('device_id');
        $alertType = $request->input('alert_type'); // e.g. "sound"
        $timestamp = now();

        DB::table('iot_alerts')->insert([
            'device_id'  => $deviceId,
            'alert_type' => $alertType,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Device polls this endpoint to check if there is a new "quick ping"
     * (presence call) that it should play as sound.
     *
     * Request JSON:
     * {
     *   "device_id": "ABC123",
     *   "last_id": 10   // optional: last presence_call id the device handled
     * }
     *
     * Response JSON:
     * { "call": null }  // nothing new
     * or
     * {
     *   "call": {
     *     "id": 15,
     *     "sequence": "...",
     *     "strength": 3,
     *     "duration": 30,
     *     "dnd": "respect"
     *   }
     * }
     */
    public function pollPing(Request $request)
    {
        // Optional shared-secret protection for physical devices
        $expectedKey = config('app.iot_shared_secret');
        if (! empty($expectedKey) && $request->header('X-IOT-KEY') !== $expectedKey) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $validated = $request->validate([
            'device_id' => ['required', 'string'],
            'last_id'   => ['nullable', 'integer'],
        ]);

        // Find the child record that corresponds to this device
        $child = Child::where('device_id', $validated['device_id'])->first();

        if (! $child) {
            return response()->json([
                'call' => null,
                'error' => 'unknown_device',
            ], 404);
        }

        $query = PresenceCall::where('child_id', $child->id)
            ->orderByDesc('id');

        if (! empty($validated['last_id'])) {
            $query->where('id', '>', $validated['last_id']);
        }

        $call = $query->first();

        if (! $call) {
            return response()->json(['call' => null]);
        }

        return response()->json([
            'call' => [
                'id'       => $call->id,
                'sequence' => $call->sequence,
                'strength' => $call->strength,
                'duration' => $call->duration_seconds,
                'dnd'      => $call->dnd_mode,
            ],
        ]);
    }

    /**
     * Device sends telemetry (status + optional GPS).
     *
     * Request JSON example:
     * {
     *   "device_id": "ABC123",
     *   "battery": 80,
     *   "signal": 3,
     *   "lat": 14.5995,
     *   "lng": 120.9842,
     *   "source": "gps"   // optional, defaults to "device"
     * }
     */
    public function telemetry(Request $request)
    {
        // Optional shared-secret protection for physical devices
        $expectedKey = config('app.iot_shared_secret');
        if (! empty($expectedKey) && $request->header('X-IOT-KEY') !== $expectedKey) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $validated = $request->validate([
            'device_id' => ['required', 'string'],
            'battery'   => ['nullable', 'integer', 'min:0', 'max:100'],
            'signal'    => ['nullable', 'integer', 'min:0', 'max:5'],
            'lat'       => ['nullable', 'numeric'],
            'lng'       => ['nullable', 'numeric'],
            'source'    => ['nullable', 'string', 'max:50'],
        ]);

        $child = Child::where('device_id', $validated['device_id'])->first();

        if (! $child) {
            return response()->json([
                'ok' => false,
                'error' => 'unknown_device',
            ], 404);
        }

        $now = now();

        // Update the "live" status on the child record
        $child->last_seen_at = $now;
        if (array_key_exists('battery', $validated) && $validated['battery'] !== null) {
            $child->battery_percent = (int) $validated['battery'];
        }
        if (array_key_exists('signal', $validated) && $validated['signal'] !== null) {
            $child->signal_strength = (int) $validated['signal'];
        }
        if (array_key_exists('lat', $validated) && $validated['lat'] !== null &&
            array_key_exists('lng', $validated) && $validated['lng'] !== null) {
            $child->last_lat = (float) $validated['lat'];
            $child->last_lng = (float) $validated['lng'];
        }
        $child->save();

        // Optionally create a location log when GPS data is present
        if (! empty($validated['lat']) && ! empty($validated['lng'])) {
            LocationLog::create([
                'child_id'  => $child->id,
                'timestamp' => $now,
                'address'   => null,
                'source'    => $validated['source'] ?? 'device',
                'status'    => 'success',
                'lat'       => $validated['lat'],
                'lng'       => $validated['lng'],
            ]);
        }

        // Check if there's a pending location ping request
        $pingRequest = LocationPingRequest::where('child_id', $child->id)
            ->where('status', 'pending')
            ->where('requested_at', '>', now()->subMinutes(5)) // Only recent requests (within 5 minutes)
            ->first();
        
        if ($pingRequest && !empty($validated['lat']) && !empty($validated['lng'])) {
            // Mark request as fulfilled
            $pingRequest->update([
                'fulfilled_at' => $now,
                'status' => 'fulfilled',
            ]);
        }

        return response()->json([
            'ok' => true,
            'time' => $now->toIso8601String(),
        ]);
    }

    /**
     * Device polls this endpoint to check if there is a location ping request.
     * When a request is found, device should immediately send telemetry with GPS data.
     *
     * Request JSON:
     * {
     *   "device_id": "ABC123",
     *   "last_id": 10   // optional: last location_ping_request id the device handled
     * }
     *
     * Response JSON:
     * { "request": null }  // nothing new
     * or
     * {
     *   "request": {
     *     "id": 15,
     *     "requested_at": "2025-12-09T10:30:00+00:00"
     *   }
     * }
     */
    public function pollLocationRequest(Request $request)
    {
        // Optional shared-secret protection for physical devices
        $expectedKey = config('app.iot_shared_secret');
        if (! empty($expectedKey) && $request->header('X-IOT-KEY') !== $expectedKey) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $validated = $request->validate([
            'device_id' => ['required', 'string'],
            'last_id'   => ['nullable', 'integer'],
        ]);

        // Find the child record that corresponds to this device
        $child = Child::where('device_id', $validated['device_id'])->first();

        if (! $child) {
            return response()->json([
                'request' => null,
                'error' => 'unknown_device',
            ], 404);
        }

        $query = LocationPingRequest::where('child_id', $child->id)
            ->where('status', 'pending')
            ->where('requested_at', '>', now()->subMinutes(5)) // Only recent requests (within 5 minutes)
            ->orderByDesc('id');

        if (! empty($validated['last_id'])) {
            $query->where('id', '>', $validated['last_id']);
        }

        $pingRequest = $query->first();

        if (! $pingRequest) {
            return response()->json(['request' => null]);
        }

        return response()->json([
            'request' => [
                'id' => $pingRequest->id,
                'requested_at' => $pingRequest->requested_at->toIso8601String(),
            ],
        ]);
    }
}


