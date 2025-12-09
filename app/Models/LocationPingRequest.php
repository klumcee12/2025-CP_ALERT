<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationPingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'requested_at',
        'fulfilled_at',
        'status',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}

