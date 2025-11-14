<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\LocationLog;
use App\Models\PresenceCall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function exportJson()
    {
        $user = Auth::user();
        $children = Child::where('user_id', $user->id)->get();
        
        $data = [
            'export_date' => now()->toIso8601String(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->toIso8601String(),
            ],
            'children' => $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'category' => $child->category,
                    'device_id' => $child->device_id,
                    'sim_number' => $child->sim_number,
                    'signal_strength' => $child->signal_strength,
                    'battery_percent' => $child->battery_percent,
                    'last_seen_at' => $child->last_seen_at?->toIso8601String(),
                    'last_lat' => $child->last_lat,
                    'last_lng' => $child->last_lng,
                    'created_at' => $child->created_at->toIso8601String(),
                    'location_logs' => $child->locationLogs()->orderBy('timestamp', 'desc')->get()->map(function ($log) {
                        return [
                            'timestamp' => $log->timestamp->toIso8601String(),
                            'address' => $log->address,
                            'source' => $log->source,
                            'status' => $log->status,
                            'lat' => $log->lat,
                            'lng' => $log->lng,
                        ];
                    }),
                    'presence_calls' => $child->presenceCalls()->orderBy('timestamp', 'desc')->get()->map(function ($call) {
                        return [
                            'timestamp' => $call->timestamp->toIso8601String(),
                            'sequence' => $call->sequence,
                            'strength' => $call->strength,
                            'duration_seconds' => $call->duration_seconds,
                            'dnd_mode' => $call->dnd_mode,
                            'status' => $call->status,
                        ];
                    }),
                ];
            }),
        ];

        $filename = 'alert_export_' . now()->format('Y-m-d_His') . '.json';
        
        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportCsv()
    {
        $user = Auth::user();
        $children = Child::where('user_id', $user->id)->get();
        
        $csv = "Export Date," . now()->toDateTimeString() . "\n";
        $csv .= "User," . $user->name . " (" . $user->email . ")\n\n";
        
        foreach ($children as $child) {
            $csv .= "Child: {$child->name}\n";
            $csv .= "Device ID,Category,SIM Number,Signal,Battery,Last Seen,Last Lat,Last Lng\n";
            $csv .= "{$child->device_id},{$child->category}," . ($child->sim_number ?? '') . ",{$child->signal_strength},{$child->battery_percent}," . ($child->last_seen_at?->toDateTimeString() ?? '') . ",{$child->last_lat},{$child->last_lng}\n\n";
            
            $csv .= "Location Logs\n";
            $csv .= "Timestamp,Address,Source,Status,Lat,Lng\n";
            foreach ($child->locationLogs()->orderBy('timestamp', 'desc')->get() as $log) {
                $csv .= "{$log->timestamp},{$log->address},{$log->source},{$log->status},{$log->lat},{$log->lng}\n";
            }
            
            $csv .= "\nPresence Calls\n";
            $csv .= "Timestamp,Sequence,Strength,Duration (s),DND Mode,Status\n";
            foreach ($child->presenceCalls()->orderBy('timestamp', 'desc')->get() as $call) {
                $csv .= "{$call->timestamp},{$call->sequence},{$call->strength},{$call->duration_seconds},{$call->dnd_mode},{$call->status}\n";
            }
            
            $csv .= "\n\n";
        }
        
        $filename = 'alert_export_' . now()->format('Y-m-d_His') . '.csv';
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function backup()
    {
        $user = Auth::user();
        $children = Child::where('user_id', $user->id)->get();
        
        $backup = [
            'version' => '1.0',
            'backup_date' => now()->toIso8601String(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'middle_name' => $user->middle_name,
                'email' => $user->email,
            ],
            'children' => $children->map(function ($child) {
                return [
                    'name' => $child->name,
                    'category' => $child->category,
                    'device_id' => $child->device_id,
                    'sim_number' => $child->sim_number,
                ];
            }),
            'settings' => [
                // Add any settings here in the future
            ],
        ];

        $filename = 'alert_backup_' . now()->format('Y-m-d_His') . '.json';
        
        return response()->json($backup, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'backup_file' => ['required', 'file', 'mimes:json', 'max:10240'], // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => 'Invalid backup file. Please upload a valid JSON file.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('backup_file');
            $content = file_get_contents($file->getRealPath());
            $backup = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format');
            }

            if (!isset($backup['version']) || !isset($backup['children'])) {
                throw new \Exception('Invalid backup format');
            }

            $user = Auth::user();
            $restored = 0;
            $errors = [];

            foreach ($backup['children'] as $childData) {
                try {
                    // Check if device_id already exists
                    $existing = Child::where('device_id', $childData['device_id'])
                        ->where('user_id', $user->id)
                        ->first();

                    if ($existing) {
                        $errors[] = "Device {$childData['device_id']} already exists. Skipping.";
                        continue;
                    }

                    Child::create([
                        'user_id' => $user->id,
                        'name' => $childData['name'],
                        'category' => $childData['category'] ?? 'regular',
                        'device_id' => $childData['device_id'],
                        'sim_number' => $childData['sim_number'] ?? null,
                        'signal_strength' => 0,
                        'battery_percent' => 0,
                    ]);
                    $restored++;
                } catch (\Exception $e) {
                    $errors[] = "Error restoring {$childData['name']}: " . $e->getMessage();
                }
            }

            return response()->json([
                'ok' => true,
                'message' => "Restored {$restored} device(s) successfully.",
                'restored' => $restored,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Failed to restore backup: ' . $e->getMessage(),
            ], 422);
        }
    }
}


