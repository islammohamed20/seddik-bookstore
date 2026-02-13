<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'type',
        'expires_at',
        'is_used',
        'verified_at',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    // OTP Types
    const TYPE_REGISTRATION = 'registration';
    const TYPE_PASSWORD_RESET = 'password_reset';
    const TYPE_EMAIL_VERIFICATION = 'email_verification';

    /**
     * Generate a new OTP for the given email and type
     */
    public static function generateOtp($email, $type, $expirationMinutes = 10)
    {
        // Invalidate any existing OTPs for this email and type
        self::where('email', $email)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Generate 6-digit OTP
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Create new OTP record
        return self::create([
            'email' => $email,
            'otp' => $otp,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes($expirationMinutes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Verify OTP
     */
    public static function verifyOtp($email, $otp, $type)
    {
        $otpRecord = self::where('email', $email)
            ->where('otp', $otp)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otpRecord) {
            $otpRecord->update([
                'is_used' => true,
                'verified_at' => Carbon::now()
            ]);
            return true;
        }

        return false;
    }

    /**
     * Check if OTP is valid
     */
    public function isValid()
    {
        return !$this->is_used && $this->expires_at > Carbon::now();
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired()
    {
        return $this->expires_at < Carbon::now();
    }

    /**
     * Get remaining time in minutes
     */
    public function getRemainingTimeAttribute()
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return $this->expires_at->diffInMinutes(Carbon::now());
    }

    /**
     * Scope for valid OTPs
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope for expired OTPs
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', Carbon::now());
    }

    /**
     * Clean up expired OTPs (can be used in scheduled command)
     */
    public static function cleanupExpired()
    {
        return self::expired()->delete();
    }
}
