<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث حالة الطلب</title>
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
            font-size: 26px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .status-update {
            background-color: #f0f8ff;
            border: 2px solid #003399;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #d1ecf1; color: #0c5460; }
        .status-processing { background-color: #cce5ff; color: #004085; }
        .status-shipped { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .timeline {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .timeline-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-right: 3px solid #e0e0e0;
            padding-right: 20px;
            position: relative;
        }
        .timeline-item.active {
            border-right-color: #FFD700;
        }
        .timeline-item.active::before {
            content: '';
            position: absolute;
            right: -8px;
            top: 20px;
            width: 14px;
            height: 14px;
            background-color: #FFD700;
            border-radius: 50%;
        }
        .timeline-icon {
            flex-shrink: 0;
        }
        .button {
            display: inline-block;
            padding: 14px 35px;
            background-color: #FFD700;
            color: #003399;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .email-footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>📦 تحديث حالة طلبك</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">طلب رقم #{{ $order->id }}</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p style="font-size: 16px; line-height: 1.8;">
                مرحباً <strong>{{ $order->customer_name }}</strong>،
            </p>

            <!-- Status Update Box -->
            <div class="status-update">
                <p style="margin: 0 0 10px 0; font-size: 18px; color: #003399;">
                    <strong>تم تحديث حالة طلبك!</strong>
                </p>
                <div class="status-badge status-{{ $order->status }}">
                    @switch($order->status)
                        @case('pending')
                            ⏳ قيد الانتظار
                            @break
                        @case('confirmed')
                            ✅ تم التأكيد
                            @break
                        @case('processing')
                            📦 قيد التحضير
                            @break
                        @case('shipped')
                            🚚 تم الشحن
                            @break
                        @case('delivered')
                            ✓ تم التوصيل
                            @break
                        @case('cancelled')
                            ❌ تم الإلغاء
                            @break
                        @default
                            {{ $order->status }}
                    @endswitch
                </div>
                
                @if($order->status === 'shipped')
                    <p style="margin: 15px 0 0 0; font-size: 14px; color: #666;">
                        طلبك في الطريق إليك! متوقع الوصول خلال 2-3 أيام عمل
                    </p>
                @elseif($order->status === 'delivered')
                    <p style="margin: 15px 0 0 0; font-size: 14px; color: #155724;">
                        تم توصيل طلبك بنجاح! نتمنى أن تكون راضياً عن مشترياتك
                    </p>
                @elseif($order->status === 'cancelled')
                    <p style="margin: 15px 0 0 0; font-size: 14px; color: #721c24;">
                        للأسف تم إلغاء طلبك. يمكنك التواصل معنا لمعرفة السبب
                    </p>
                @endif
            </div>

            <!-- Order Timeline -->
            @if($order->status !== 'cancelled')
            <div class="timeline">
                <h3 style="margin-top: 0; color: #003399;">مراحل الطلب:</h3>
                
                <div class="timeline-item {{ in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                    <div class="timeline-icon">⏳</div>
                    <div>
                        <strong>تم استلام الطلب</strong><br>
                        <small style="color: #666;">{{ $order->created_at->format('Y-m-d h:i A') }}</small>
                    </div>
                </div>

                <div class="timeline-item {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                    <div class="timeline-icon">✅</div>
                    <div>
                        <strong>تأكيد الطلب</strong><br>
                        <small style="color: #666;">{{ $order->status === 'pending' ? 'قيد المراجعة' : 'تم التأكيد' }}</small>
                    </div>
                </div>

                <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                    <div class="timeline-icon">📦</div>
                    <div>
                        <strong>تحضير الطلب</strong><br>
                        <small style="color: #666;">{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'جاري التحضير' : 'في الانتظار' }}</small>
                    </div>
                </div>

                <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                    <div class="timeline-icon">🚚</div>
                    <div>
                        <strong>الشحن</strong><br>
                        <small style="color: #666;">{{ in_array($order->status, ['shipped', 'delivered']) ? 'تم الشحن' : 'في الانتظار' }}</small>
                    </div>
                </div>

                <div class="timeline-item {{ $order->status === 'delivered' ? 'active' : '' }}">
                    <div class="timeline-icon">✓</div>
                    <div>
                        <strong>التوصيل</strong><br>
                        <small style="color: #666;">{{ $order->status === 'delivered' ? 'تم التوصيل' : 'في الانتظار' }}</small>
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Summary -->
            <div class="order-details">
                <h3 style="margin-top: 0; color: #003399;">ملخص الطلب:</h3>
                <p style="margin: 5px 0;"><strong>رقم الطلب:</strong> #{{ $order->id }}</p>
                <p style="margin: 5px 0;"><strong>الإجمالي:</strong> {{ number_format($order->total_amount, 2) }} جنيه</p>
                <p style="margin: 5px 0;"><strong>طريقة الدفع:</strong> {{ $order->payment_method === 'cash' ? 'الدفع عند الاستلام' : $order->payment_method }}</p>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('orders.show', $order) }}" class="button">
                    عرض تفاصيل الطلب
                </a>
            </div>

            <!-- Support Info -->
            @php
                use App\Models\Setting;
                $statusEmailPhone = Setting::getValue('site_phone', '01223694848');
                $statusWhatsapp = 'https://wa.me/'.Setting::getValue('whatsapp_number', '201223694848');
                $statusSupportEmail = Setting::getValue('site_email', 'info@seddik-library.com');
                $statusSiteName = Setting::getValue('site_name', 'متجر الصديق');
                $statusSiteNameEn = Setting::getValue('site_name_en', 'El-Sedeek Store');
                $statusAddress = Setting::getValue('site_address', 'شارع الجمهورية، بجوار الوطنية مول، أسيوط، مصر');
            @endphp
            <div style="background-color: #f0f8ff; border-right: 4px solid #003399; padding: 15px; border-radius: 4px; margin-top: 20px;">
                <p style="margin: 0; line-height: 1.8;">
                    <strong>💬 أسئلة أو استفسارات؟</strong><br>
                    نحن هنا لمساعدتك! تواصل معنا:<br>
                    واتساب: <a href="{{ $statusWhatsapp }}" style="color: #003399; text-decoration: none; font-weight: bold;">{{ $statusEmailPhone }}</a><br>
                    بريد: <a href="mailto:{{ $statusSupportEmail }}" style="color: #003399; text-decoration: none;">{{ $statusSupportEmail }}</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ $statusSiteName }} - {{ $statusSiteNameEn }}</strong></p>
            <p>{{ $statusAddress }}</p>
            <p>© {{ date('Y') }} جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
