<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\LocationLog;
use App\Models\PresenceCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function children()
    {
        $children = Child::where('user_id', Auth::id())
            ->orderBy('name')
            ->get([ 'id','name','category','device_id','sim_number','signal_strength','battery_percent','last_seen_at','last_lat','last_lng' ]);

        return response()->json([
            'data' => $children->map(function ($c) {
                return [
                    'id' => (string) $c->id,
                    'name' => $c->name,
                    'deviceId' => $c->device_id,
                    'category' => $c->category,
                    'sim' => $c->sim_number,
                    'signal' => (int) $c->signal_strength,
                    'battery' => (int) $c->battery_percent,
                    'lastSeen' => optional($c->last_seen_at)->diffForHumans() ?? null,
                    'coords' => $c->last_lat !== null && $c->last_lng !== null ? [ 'lat' => (float) $c->last_lat, 'lng' => (float) $c->last_lng ] : null,
                ];
            })
        ]);
    }

    public function locationLogs(Request $request)
    {
        $childId = $request->query('child_id');
        $query = LocationLog::whereHas('child', fn($q) => $q->where('user_id', Auth::id()))
            ->orderByDesc('timestamp')
            ->limit(200);

        if ($childId) {
            $query->where('child_id', $childId);
        }

        $rows = $query->get();

        return response()->json([
            'data' => $rows->map(function ($r) {
                return [
                    'time' => $r->timestamp->toIso8601String(),
                    'user' => $r->child->name,
                    'address' => $r->address,
                    'source' => $r->source,
                    'status' => $r->status,
                ];
            })
        ]);
    }

    public function presenceCalls(Request $request)
    {
        $childId = $request->query('child_id');
        $query = PresenceCall::whereHas('child', fn($q) => $q->where('user_id', Auth::id()))
            ->orderByDesc('timestamp')
            ->limit(200);

        if ($childId) {
            $query->where('child_id', $childId);
        }

        $rows = $query->get();

        return response()->json([
            'data' => $rows->map(function ($r) {
                return [
                    'time' => $r->timestamp->toIso8601String(),
                    'user' => $r->child->name,
                    'seq' => $r->sequence,
                    'status' => $r->status,
                ];
            })
        ]);
    }

    public function sendPresenceCall(Request $request)
    {
        $validated = $request->validate([
            'child_id' => ['required','integer','exists:children,id'],
            'sequence' => ['required','string'],
            'strength' => ['required','integer','min:1','max:5'],
            'duration' => ['required','integer','min:5','max:300'],
            'dnd' => ['required','in:respect,ignore'],
        ]);

        $child = Child::where('user_id', Auth::id())->findOrFail($validated['child_id']);

        $ok = true; // For now always succeed; integrate with device later
        $log = PresenceCall::create([
            'child_id' => $child->id,
            'timestamp' => now(),
            'sequence' => $validated['sequence'],
            'strength' => $validated['strength'],
            'duration_seconds' => $validated['duration'],
            'dnd_mode' => $validated['dnd'],
            'status' => $ok ? 'sent' : 'failed',
        ]);

        return response()->json(['ok' => $ok, 'id' => $log->id]);
    }

    public function createChild(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'category' => ['required','in:regular,normal,child_with_disability,bed_ridden'],
            'device_id' => ['required','string','max:255','unique:children,device_id'],
            'sim_number' => ['nullable','string','max:255'],
        ]);

        // Normalize UI alias "normal" -> stored as "regular"
        $normalizedCategory = $validated['category'] === 'normal' ? 'regular' : $validated['category'];

        $child = Child::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'category' => $normalizedCategory,
            'device_id' => $validated['device_id'],
            'sim_number' => $validated['sim_number'] ?? null,
            'signal_strength' => 0,
            'battery_percent' => 0,
        ]);

        return response()->json([
            'ok' => true,
            'id' => (string)$child->id,
            'name' => $child->name,
            'category' => $child->category,
        ]);
    }
}


