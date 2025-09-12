<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresenceCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'timestamp',
        'sequence',
        'strength',
        'duration_seconds',
        'dnd_mode',
        'status',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'strength' => 'integer',
        'duration_seconds' => 'integer',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}


