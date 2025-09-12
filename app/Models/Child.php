<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'device_id',
        'sim_number',
        'signal_strength',
        'battery_percent',
        'last_seen_at',
        'last_lat',
        'last_lng',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'last_lat' => 'float',
        'last_lng' => 'float',
        'signal_strength' => 'integer',
        'battery_percent' => 'integer',
    ];

    public function parentUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function locationLogs(): HasMany
    {
        return $this->hasMany(LocationLog::class);
    }

    public function presenceCalls(): HasMany
    {
        return $this->hasMany(PresenceCall::class);
    }
}


