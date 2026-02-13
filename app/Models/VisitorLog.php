<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VisitorLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'path',
        'referrer',
        'latitude',
        'longitude',
        'city',
        'region',
        'country',
        'source',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
