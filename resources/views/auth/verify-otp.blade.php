@extends('layouts.storefront')

@section('title', __('تحقق من البريد الإلكتروني') . ' - ' . __('مكتبة الصديق'))

@section('content')
<div class="min-h-screen bg-gray-100 py-12 flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-bold">
                    <span class="text-primary-yellow">مكتبة</span>
                    <span class="text-primary-blue">الصديق</span>
                </span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-primary-blue">تحقق من بريدك الإلكتروني</h2>
                <p class="text-gray-600 mt-2">
                    أرسلنا رمز تحقق مكون من 6 أرقام إلى
                </p>
                <p class="text-primary-blue font-semibold">{{ $email }}</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-6 flex items-center">
                    <i class="fas fa-check-circle text-green-500 ml-2"></i>
                    <span class="text-green-700">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register.verify') }}" class="space-y-6" id="otpForm">
                @csrf

                <!-- OTP Input -->
                <div>
                    <label for="otp" class="block text-gray-700 font-semibold mb-3 text-center">أدخل رمز التحقق</label>
                    <div class="flex gap-2 justify-center">
                        <input type="text" 
                               id="otp-1" 
                               class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-yellow focus:border-transparent @error('otp') border-red-500 @enderror otp-input"
                               maxlength="1" 
                               pattern="[0-9]"
                               inputmode="numeric">
                        <input type="text" 
                               id="otp-2" 
                               class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-yellow focus:border-transparent @error('otp') border-red-500 @enderror otp-input"
                               maxlength="1" 
                               pattern="[0-9]"
                               inputmode="numeric">
                        <input type="text" 
                               id="otp-3" 
                               class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-yellow focus:border-transparent @error('otp') border-red-500 @enderror otp-input"
                               maxlength="1" 
                               pattern="[0-9]"
                               inputmode="numeric">
                        <input type="text" 
                               id="otp-4" 
                               class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-yellow focus:border-transparent @error('otp') border-red-500 @enderror otp-input"
                               maxlength="1" 
                               pattern="[0-9]"
                               inputmode="numeric">
                        <input type="text" 
                               id="otp-5" 
                               class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-yellow focus:border-transparent @error('otp') border-red-500 @enderror otp-input"
                               maxlength="1" 
                               pattern="[0-9]"
                               inputmode="numeric">
                        <input type="text" 
                               id="otp-6" 
                               class="w-12 h-12 text-center text-xl font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-yellow focus:border-transparent @error('otp') border-red-500 @enderror otp-input"
                               maxlength="1" 
                               pattern="[0-9]"
                               inputmode="numeric">
                    </div>
                    
                    <!-- Hidden input to store complete OTP -->
                    <input type="hidden" name="otp" id="otp" value="{{ old('otp') }}">
                    
                    @error('otp')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timer -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        الرمز صالح لمدة <span id="timer" class="font-bold text-primary-blue">15:00</span>
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-primary-blue hover:bg-primary-blue/90 text-white font-bold py-4 rounded-lg transition transform hover:scale-[1.02]"
                        id="verifyButton">
                    تحقق من الرمز
                </button>
            </form>

            <!-- Resend OTP -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm mb-2">لم تستلم الرمز؟</p>
                <form method="POST" action="{{ route('register.resend-otp') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-primary-blue font-semibold hover:underline disabled:opacity-50 disabled:cursor-not-allowed"
                            id="resendButton">
                        إرسال رمز جديد
                    </button>
                </form>
            </div>

            <!-- Change Email -->
            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-gray-500 text-sm hover:text-primary-blue transition">
                    تغيير البريد الإلكتروني
                </a>
            </div>
        </div>

        <!-- Back to home -->
        <p class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-blue transition">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للرئيسية
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const hiddenOtp = document.getElementById('otp');
    const form = document.getElementById('otpForm');
    const resendButton = document.getElementById('resendButton');
    
    // Handle OTP input
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow digits
            if (!/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Move to next input
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            
            updateHiddenOtp();
        });
        
        input.addEventListener('keydown', function(e) {
            // Handle backspace
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
        
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text');
            const digits = pastedData.replace(/\D/g, '').slice(0, 6);
            
            digits.split('').forEach((digit, i) => {
                if (otpInputs[i]) {
                    otpInputs[i].value = digit;
                }
            });
            
            updateHiddenOtp();
            
            // Focus the next empty input or the last one
            const nextEmpty = Array.from(otpInputs).findIndex(input => !input.value);
            if (nextEmpty !== -1) {
                otpInputs[nextEmpty].focus();
            } else {
                otpInputs[otpInputs.length - 1].focus();
            }
        });
    });
    
    function updateHiddenOtp() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        hiddenOtp.value = otp;
    }
    
    // Auto-submit when all 6 digits are entered
    function checkAutoSubmit() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        if (otp.length === 6) {
            form.submit();
        }
    }
    
    otpInputs.forEach(input => {
        input.addEventListener('input', checkAutoSubmit);
    });
    
    // Timer countdown (15 minutes)
    let timeLeft = 15 * 60; // 15 minutes in seconds
    const timerElement = document.getElementById('timer');
    
    const countdown = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerElement.textContent = '00:00';
            timerElement.parentElement.innerHTML = '<span class="text-red-500">انتهت صلاحية الرمز</span>';
        }
        
        timeLeft--;
    }, 1000);
    
    // Focus first input on page load
    otpInputs[0].focus();
});
</script>
@endsection