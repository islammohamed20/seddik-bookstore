<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $purpose }} - مكتبة الصديق</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .header-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .content {
            padding: 2rem;
        }
        
        .greeting {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: #374151;
        }
        
        .purpose-text {
            font-size: 1rem;
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        
        .otp-section {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            margin: 2rem 0;
            border-right: 4px solid #3b82f6;
        }
        
        .otp-label {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .otp-code {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e40af;
            font-family: 'Courier New', monospace;
            letter-spacing: 8px;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 10px rgba(30, 64, 175, 0.1);
        }
        
        .expiry-info {
            background: #fef3c7;
            border-right: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        
        .expiry-info .icon {
            color: #f59e0b;
            font-weight: bold;
        }
        
        .security-notice {
            background: #fce7f3;
            border-right: 4px solid #ec4899;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }
        
        .security-notice .icon {
            color: #ec4899;
            font-weight: bold;
        }
        
        .footer {
            background: #f9fafb;
            padding: 1.5rem;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-text {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }
        
        .company-info {
            color: #9ca3af;
            font-size: 0.8rem;
        }
        
        .social-links {
            margin: 1rem 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 0.5rem;
            color: #6b7280;
            text-decoration: none;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .content {
                padding: 1.5rem;
            }
            
            .header {
                padding: 1.5rem;
            }
            
            .otp-code {
                font-size: 2rem;
                letter-spacing: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🏪 مكتبة الصديق</div>
            <div class="header-subtitle">El-Sedeek Store</div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                مرحباً {{ $userName }}،
            </div>
            
            @if($type === 'registration')
                <div class="purpose-text">
                    نشكرك على تسجيلك في مكتبة الصديق! لإتمام تفعيل حسابك، يرجى استخدام رمز التفعيل التالي:
                </div>
            @elseif($type === 'password_reset')
                <div class="purpose-text">
                    تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك. استخدم الرمز التالي لإعادة تعيين كلمة مرورك:
                </div>
            @elseif($type === 'email_verification')
                <div class="purpose-text">
                    لتأكيد عنوان بريدك الإلكتروني وضمان أمان حسابك، يرجى استخدام رمز التأكيد التالي:
                </div>
            @endif
            
            <!-- OTP Section -->
            <div class="otp-section">
                <div class="otp-label">رمز التحقق</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <!-- Expiry Notice -->
            <div class="expiry-info">
                <span class="icon">⏰</span>
                <strong>مهم:</strong> هذا الرمز صالح لمدة 15 دقيقة فقط من وقت الإرسال.
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <span class="icon">🔒</span>
                <strong>تنبيه أمني:</strong> لا تشارك هذا الرمز مع أي شخص آخر. فريق مكتبة الصديق لن يطلب منك أبداً مشاركة رمز التحقق.
            </div>
            
            @if($type === 'registration')
                <p>إذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذه الرسالة.</p>
            @elseif($type === 'password_reset')
                <p>إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة أو التواصل معنا فوراً.</p>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                شكراً لاختيارك مكتبة الصديق
            </div>
            
            <div class="social-links">
                <a href="tel:+201223694848">📞 01223694848</a>
                <a href="mailto:info@al-seddik.com">✉️ info@al-seddik.com</a>
            </div>
            
            <div class="company-info">
                مكتبة الصديق - شارع الجمهورية، أسيوط<br>
                متخصصون في الأدوات المدرسية والألعاب التعليمية منذ 1987
            </div>
        </div>
    </div>
</body>
</html>