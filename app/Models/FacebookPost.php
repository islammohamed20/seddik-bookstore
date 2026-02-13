<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'posted_at' => 'datetime',
        'raw_payload' => 'array',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
