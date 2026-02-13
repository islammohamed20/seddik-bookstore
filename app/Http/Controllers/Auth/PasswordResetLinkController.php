<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request - Send OTP instead of email link
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'لا يوجد حساب مسجل بهذا البريد الإلكتروني.']);
        }

        // Check rate limiting
        if ($this->otpService->hasExceededLimit($request->email, UserOtp::TYPE_PASSWORD_RESET)) {
            return back()->withErrors(['email' => 'لقد تجاوزت الحد الأقصى للمحاولات. حاول مرة أخرى بعد ساعة.']);
        }

        // Send OTP
        if ($this->otpService->sendPasswordResetOtp($request->email, $user->name)) {
            session(['reset_email' => $request->email]);
            return redirect()->route('password.verify-otp')->with('status', 'تم إرسال رمز إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
        } else {
            return back()->withErrors(['email' => 'فشل في إرسال رمز التحقق. حاول مرة أخرى.']);
        }
    }

    /**
     * Show OTP verification page for password reset
     */
    public function showVerifyOtp()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'انتهت صلاحية الجلسة. يرجى المحاولة مرة أخرى.']);
        }

        return view('auth.verify-otp', [
            'email' => session('reset_email'),
            'type' => 'password_reset'
        ]);
    }

    /**
     * Verify OTP for password reset
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['otp' => 'انتهت صلاحية الجلسة. يرجى المحاولة مرة أخرى.']);
        }

        // Verify OTP
        if ($this->otpService->verifyOtp($email, $request->otp, UserOtp::TYPE_PASSWORD_RESET)) {
            // Generate secure token for password reset form
            $token = bin2hex(random_bytes(32));
            session(['reset_token' => $token, 'reset_email_verified' => true]);
            
            return redirect()->route('password.reset', ['token' => $token])->with('success', 'تم التحقق بنجاح. يمكنك الآن تعيين كلمة مرور جديدة.');
        } else {
            return back()->withErrors(['otp' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }
    }

    /**
     * Resend OTP for password reset
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'انتهت صلاحية الجلسة.']);
        }

        // Get user for name
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'حدث خطأ. يرجى المحاولة مرة أخرى.']);
        }

        // Check cooldown period
        $cooldownSeconds = $this->otpService->getTimeUntilNextRequest($email, UserOtp::TYPE_PASSWORD_RESET);
        if ($cooldownSeconds > 0) {
            return back()->withErrors(['otp' => "يمكنك طلب رمز جديد بعد {$cooldownSeconds} ثانية."]);
        }

        // Check rate limiting
        if ($this->otpService->hasExceededLimit($email, UserOtp::TYPE_PASSWORD_RESET)) {
            return back()->withErrors(['otp' => 'تجاوزت الحد الأقصى للمحاولات. حاول بعد ساعة.']);
        }

        if ($this->otpService->sendPasswordResetOtp($email, $user->name)) {
            return back()->with('success', 'تم إرسال رمز تحقق جديد!');
        } else {
            return back()->withErrors(['otp' => 'فشل في إرسال الرمز. حاول مرة أخرى.']);
        }
    }
}
