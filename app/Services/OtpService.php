<?php

namespace App\Services;

use App\Models\UserOtp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Send OTP for registration
     */
    public function sendRegistrationOtp($email, $userName = null)
    {
        try {
            // Generate OTP
            $otpRecord = UserOtp::generateOtp($email, UserOtp::TYPE_REGISTRATION, 15);
            
            // Send email
            Mail::to($email)->send(new OtpMail(
                $otpRecord->otp,
                $userName ?? $email,
                'تفعيل الحساب',
                'registration'
            ));

            Log::info("Registration OTP sent", ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send registration OTP", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send OTP for password reset
     */
    public function sendPasswordResetOtp($email, $userName = null)
    {
        try {
            // Generate OTP
            $otpRecord = UserOtp::generateOtp($email, UserOtp::TYPE_PASSWORD_RESET, 15);
            
            // Send email
            Mail::to($email)->send(new OtpMail(
                $otpRecord->otp,
                $userName ?? $email,
                'إعادة تعيين كلمة المرور',
                'password_reset'
            ));

            Log::info("Password reset OTP sent", ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send password reset OTP", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send OTP for email verification
     */
    public function sendEmailVerificationOtp($email, $userName = null)
    {
        try {
            // Generate OTP
            $otpRecord = UserOtp::generateOtp($email, UserOtp::TYPE_EMAIL_VERIFICATION, 15);
            
            // Send email
            Mail::to($email)->send(new OtpMail(
                $otpRecord->otp,
                $userName ?? $email,
                'تأكيد البريد الإلكتروني',
                'email_verification'
            ));

            Log::info("Email verification OTP sent", ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email verification OTP", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($email, $otp, $type)
    {
        return UserOtp::verifyOtp($email, $otp, $type);
    }

    /**
     * Get OTP attempts count for rate limiting
     */
    public function getOtpAttemptsCount($email, $type, $withinMinutes = 60)
    {
        return UserOtp::where('email', $email)
            ->where('type', $type)
            ->where('created_at', '>', now()->subMinutes($withinMinutes))
            ->count();
    }

    /**
     * Check if user has exceeded OTP request limit
     */
    public function hasExceededLimit($email, $type, $maxAttempts = 5, $withinMinutes = 60)
    {
        return $this->getOtpAttemptsCount($email, $type, $withinMinutes) >= $maxAttempts;
    }

    /**
     * Get time until next OTP can be requested
     */
    public function getTimeUntilNextRequest($email, $type, $cooldownMinutes = 2)
    {
        $lastOtp = UserOtp::where('email', $email)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastOtp) {
            return 0;
        }

        $nextAllowedTime = $lastOtp->created_at->addMinutes($cooldownMinutes);
        
        if ($nextAllowedTime > now()) {
            return $nextAllowedTime->diffInSeconds(now());
        }

        return 0;
    }

    /**
     * Clean up expired OTPs
     */
    public function cleanupExpired()
    {
        return UserOtp::cleanupExpired();
    }
}