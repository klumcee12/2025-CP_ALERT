<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Child;
use App\Models\LocationLog;
use App\Models\PresenceCall;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            ['name' => 'Parent User']
        );

        if (!Child::where('user_id', $user->id)->exists()) {
            $children = [
                ['name' => 'Aira', 'device_id' => 'ALP-001', 'sim_number' => '+63•••1234', 'signal_strength' => 4, 'battery_percent' => 87, 'last_seen_at' => now()->subMinutes(5), 'last_lat' => 6.9103, 'last_lng' => 122.061],
                ['name' => 'Liam', 'device_id' => 'ALP-002', 'sim_number' => '+63•••5678', 'signal_strength' => 3, 'battery_percent' => 62, 'last_seen_at' => now()->subMinutes(10), 'last_lat' => 6.9140, 'last_lng' => 122.059],
                ['name' => 'Mika', 'device_id' => 'ALP-003', 'sim_number' => '+63•••9101', 'signal_strength' => 2, 'battery_percent' => 44, 'last_seen_at' => now()->subMinutes(20), 'last_lat' => 6.9085, 'last_lng' => 122.065],
            ];

            foreach ($children as $c) {
                $child = Child::create(array_merge($c, ['user_id' => $user->id]));

                for ($i = 0; $i < 5; $i++) {
                    LocationLog::create([
                        'child_id' => $child->id,
                        'timestamp' => now()->subHours($i),
                        'address' => 'Near WMSU, Zamboanga City',
                        'source' => 'Parent Ping',
                        'status' => 'success',
                        'lat' => $c['last_lat'],
                        'lng' => $c['last_lng'],
                    ]);
                }

                for ($i = 0; $i < 3; $i++) {
                    PresenceCall::create([
                        'child_id' => $child->id,
                        'timestamp' => now()->subDays($i),
                        'sequence' => 'standard',
                        'strength' => 3,
                        'duration_seconds' => 12,
                        'dnd_mode' => 'respect',
                        'status' => 'sent',
                    ]);
                }
            }
        }
    }
}
