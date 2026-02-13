<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'name',
        'is_active',
        'verification_token',
        'verified_at',
        'unsubscribed_at',
        'source',
        'preferences',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'preferences' => 'array',
    ];

    /**
     * Generate verification token
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->verification_token)) {
                $subscriber->verification_token = Str::random(64);
            }
        });
    }

    /**
     * Check if subscriber is verified
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Check if subscriber is active
     */
    public function isActive(): bool
    {
        return $this->is_active && is_null($this->unsubscribed_at);
    }

    /**
     * Verify the subscriber
     */
    public function verify(): void
    {
        $this->update([
            'verified_at' => now(),
            'is_active' => true,
        ]);
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe(): void
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    /**
     * Scope for active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->whereNull('unsubscribed_at');
    }

    /**
     * Scope for verified subscribers
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }
}
