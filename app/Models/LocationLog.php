<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'timestamp',
        'address',
        'source',
        'status',
        'lat',
        'lng',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}


