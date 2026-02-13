<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الطلب</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .email-container {
            max-width: 650px;
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
        }
        .success-badge {
            background-color: #FFD700;
            color: #003399;
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            margin-top: 15px;
            font-weight: bold;
        }
        .email-body {
            padding: 30px 20px;
        }
        .order-details {
            background-color: #f9f9f9;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .order-details h2 {
            color: #003399;
            margin-top: 0;
            font-size: 20px;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .products-table th {
            background-color: #003399;
            color: white;
            padding: 12px;
            text-align: right;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .total-row {
            background-color: #f0f8ff;
            font-weight: bold;
            font-size: 18px;
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
            text-align: center;
        }
        .email-footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #d1ecf1; color: #0c5460; }
        .status-shipped { background-color: #d4edda; color: #155724; }
        .status-delivered { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>✓ تم استلام طلبك بنجاح</h1>
            <div class="success-badge">طلب رقم #{{ $order->id }}</div>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p style="font-size: 16px; line-height: 1.8;">
                مرحباً <strong>{{ $order->customer_name }}</strong>،
            </p>
            <p style="font-size: 16px; line-height: 1.8;">
                شكراً لك على طلبك من <strong>مكتبة الصديق</strong>! تم استلام طلبك بنجاح وجاري معالجته الآن.
            </p>

            <!-- Order Details -->
            <div class="order-details">
                <h2>📋 تفاصيل الطلب</h2>
                <div class="detail-row">
                    <span class="detail-label">رقم الطلب:</span>
                    <span class="detail-value">#{{ $order->id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">تاريخ الطلب:</span>
                    <span class="detail-value">{{ $order->created_at->format('Y-m-d h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">الحالة:</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ $order->status }}">
                            {{ __('orders.status.' . $order->status) }}
                        </span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">طريقة الدفع:</span>
                    <span class="detail-value">{{ $order->payment_method === 'cash' ? 'الدفع عند الاستلام' : $order->payment_method }}</span>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="order-details">
                <h2>📍 عنوان التوصيل</h2>
                <p style="margin: 0; line-height: 1.8;">
                    <strong>{{ $order->customer_name }}</strong><br>
                    {{ $order->customer_phone }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->city }}, {{ $order->governorate }}
                </p>
            </div>

            <!-- Products -->
            <h2 style="color: #003399; margin-top: 30px;">🛒 المنتجات المطلوبة</h2>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th style="text-align: center;">الكمية</th>
                        <th style="text-align: left;">السعر</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: left;">{{ number_format($item->price * $item->quantity, 2) }} جنيه</td>
                    </tr>
                    @endforeach
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="2" style="text-align: left; color: #28a745;">خصم الكوبون:</td>
                        <td style="text-align: left; color: #28a745;">-{{ number_format($order->discount_amount, 2) }} جنيه</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" style="text-align: left;">رسوم التوصيل:</td>
                        <td style="text-align: left;">{{ number_format($order->shipping_cost, 2) }} جنيه</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2" style="text-align: left;">الإجمالي:</td>
                        <td style="text-align: left; color: #003399;">{{ number_format($order->total_amount, 2) }} جنيه</td>
                    </tr>
                </tbody>
            </table>

            <!-- Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('orders.show', $order) }}" class="button">
                    تتبع طلبك
                </a>
            </div>

            <!-- Support Info -->
            <div style="background-color: #f0f8ff; border-right: 4px solid #003399; padding: 15px; border-radius: 4px; margin-top: 20px;">
                <p style="margin: 0; line-height: 1.8;">
                    <strong>💬 هل لديك استفسار؟</strong><br>
                    تواصل معنا عبر الواتساب: <a href="https://wa.me/201223694848" style="color: #003399; text-decoration: none; font-weight: bold;">01223694848</a><br>
                    أو عبر البريد: <a href="mailto:info@seddik-library.com" style="color: #003399; text-decoration: none;">info@seddik-library.com</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>متجر الصديق - El-Sedeek Store</strong></p>
            <p>شارع الجمهورية، بجوار الوطنية مول، أسيوط، مصر</p>
            <p>© {{ date('Y') }} جميع الحقوق محفوظة</p>
        </div>
    </div>
</body>
</html>
