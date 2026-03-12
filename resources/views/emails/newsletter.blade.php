<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #003399 0%, #0055cc 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 20px;
            line-height: 1.8;
            color: #333;
        }
        .email-footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #666;
        }
        .email-footer a {
            color: #003399;
            text-decoration: none;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #FFD700;
            color: #003399;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 5px;
            color: #666;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        @php
            use App\Models\Setting;
            $emailSiteName = Setting::getValue('site_name', 'مكتبة الصديق');
            $emailSiteNameEn = Setting::getValue('site_name_en', 'El-Sedeek Store');
            $emailAddress = Setting::getValue('site_address', 'شارع الجمهورية، بجوار الوطنية مول، أسيوط، مصر');
            $emailPhone = Setting::getValue('site_phone', '01223694848');
            $emailFrom = Setting::getValue('site_email', 'info@seddik-library.com');
            $emailFacebook = Setting::getValue('facebook_url', 'https://www.facebook.com/seddik.library');
            $emailInstagram = Setting::getValue('instagram_url', 'https://www.instagram.com/seddik.library');
            $emailWhatsapp = 'https://wa.me/'.Setting::getValue('whatsapp_number', '201223694848');
        @endphp
        <div class="email-header">
            <h1>{{ $emailSiteName }}</h1>
            <p>{{ $emailSiteNameEn }}</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            {!! $content !!}
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ $emailSiteName }} - {{ $emailSiteNameEn }}</strong></p>
            <p>{{ $emailAddress }}</p>
            <p>
                <a href="tel:{{ $emailPhone }}">{{ $emailPhone }}</a> | 
                <a href="mailto:{{ $emailFrom }}">{{ $emailFrom }}</a>
            </p>
            
            <div class="social-links">
                <a href="{{ $emailFacebook }}" target="_blank">Facebook</a> |
                <a href="{{ $emailInstagram }}" target="_blank">Instagram</a> |
                <a href="{{ $emailWhatsapp }}" target="_blank">WhatsApp</a>
            </div>

            <p style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                @if(isset($subscriber))
                <a href="{{ route('newsletter.unsubscribe', $subscriber->verification_token) }}" style="color: #999;">
                    إلغاء الاشتراك من النشرة البريدية
                </a>
                @endif
            </p>
        </div>
    </div>
</body>
</html>
