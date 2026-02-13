<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserOtp;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request - Step 1: Collect info and send OTP
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Store registration data in session
        session([
            'registration_data' => [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        ]);

        // Check rate limiting
        if ($this->otpService->hasExceededLimit($request->email, UserOtp::TYPE_REGISTRATION)) {
            return back()->withErrors(['email' => 'لقد تجاوزت الحد الأقصى لطلبات التسجيل. حاول مرة أخرى بعد ساعة.']);
        }

        // Send OTP
        if ($this->otpService->sendRegistrationOtp($request->email, $request->name)) {
            return redirect()->route('register.verify')->with('email', $request->email);
        } else {
            return back()->withErrors(['email' => 'فشل في إرسال رمز التحقق. حاول مرة أخرى.']);
        }
    }

    /**
     * Show OTP verification page
     */
    public function showVerifyOtp()
    {
        if (!session('registration_data')) {
            return redirect()->route('register')->withErrors(['email' => 'انتهت صلاحية الجلسة. يرجى المحاولة مرة أخرى.']);
        }

        return view('auth.verify-otp', [
            'email' => session('email'),
            'type' => 'registration'
        ]);
    }

    /**
     * Verify OTP and complete registration - Step 2
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $registrationData = session('registration_data');
        if (!$registrationData) {
            return redirect()->route('register')->withErrors(['otp' => 'انتهت صلاحية الجلسة. يرجى المحاولة مرة أخرى.']);
        }

        $email = session('email');
        
        // Verify OTP
        if ($this->otpService->verifyOtp($email, $request->otp, UserOtp::TYPE_REGISTRATION)) {
            // Create user
            $user = User::create($registrationData);

            // Mark email as verified
            $user->markEmailAsVerified();

            // إنشاء إشعار للتسجيل الجديد
            AdminNotification::createRegistrationNotification($user);

            // Clear session data
            session()->forget(['registration_data', 'email']);

            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'تم إنشاء حسابك بنجاح!');
        } else {
            return back()->withErrors(['otp' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('email');
        $registrationData = session('registration_data');

        if (!$email || !$registrationData) {
            return redirect()->route('register')->withErrors(['email' => 'انتهت صلاحية الجلسة.']);
        }

        // Check cooldown period
        $cooldownSeconds = $this->otpService->getTimeUntilNextRequest($email, UserOtp::TYPE_REGISTRATION);
        if ($cooldownSeconds > 0) {
            return back()->withErrors(['otp' => "يمكنك طلب رمز جديد بعد {$cooldownSeconds} ثانية."]);
        }

        // Check rate limiting
        if ($this->otpService->hasExceededLimit($email, UserOtp::TYPE_REGISTRATION)) {
            return back()->withErrors(['otp' => 'تجاوزت الحد الأقصى للمحاولات. حاول بعد ساعة.']);
        }

        if ($this->otpService->sendRegistrationOtp($email, $registrationData['name'])) {
            return back()->with('success', 'تم إرسال رمز تحقق جديد!');
        } else {
            return back()->withErrors(['otp' => 'فشل في إرسال الرمز. حاول مرة أخرى.']);
        }
    }
}
