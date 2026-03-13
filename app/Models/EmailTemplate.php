<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'subject',
        'body_html',
        'body_text',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public static function ensureDefaults(): void
    {
        $legacy = [
            'otp' => '<p>مرحباً {{user_name}}،</p><p>رمز التحقق الخاص بك هو: <strong>{{otp}}</strong></p><p>صلاحية الرمز: {{expires_minutes}} دقيقة.</p>',
            'cart_reminder' => '<p>مرحباً {{user_name}}،</p><p>لديك منتجات في سلة التسوق. يمكنك إكمال الطلب من هنا: <a href="{{cart_url}}">{{cart_url}}</a></p>',
            'payment_success' => '<p>مرحباً {{user_name}}،</p><p>تمت عملية الدفع بنجاح للطلب رقم <strong>{{order_number}}</strong>.</p><p>إجمالي المبلغ: {{amount}}.</p>',
            'payment_failed' => '<p>مرحباً {{user_name}}،</p><p>للأسف فشلت عملية الدفع للطلب رقم <strong>{{order_number}}</strong>.</p><p>السبب: {{reason}}</p><p>يمكنك المحاولة مرة أخرى من هنا: <a href="{{retry_url}}">{{retry_url}}</a></p>',
        ];

        $defaults = [
            [
                'key' => 'otp',
                'name' => 'OTP (رمز التحقق)',
                'description' => 'يرسل عند التسجيل/إعادة تعيين كلمة المرور',
                'subject' => 'رمز التحقق - {{app_name}}',
                'body_html' => self::defaultOtpHtml(),
                'body_text' => 'مرحباً {{user_name}}، رمز التحقق: {{otp}}. صلاحية الرمز: {{expires_minutes}} دقيقة.',
                'variables' => ['app_name', 'user_name', 'otp', 'expires_minutes', 'purpose', 'support_phone', 'support_email'],
                'is_active' => true,
            ],
            [
                'key' => 'cart_reminder',
                'name' => 'تذكير السلة',
                'description' => 'يرسل كتذكير للعميل بوجود منتجات في السلة',
                'subject' => 'تذكير بالسلة - {{app_name}}',
                'body_html' => self::defaultCartReminderHtml(),
                'body_text' => 'مرحباً {{user_name}}، لديك منتجات في السلة. أكمل الطلب: {{cart_url}}',
                'variables' => ['app_name', 'user_name', 'cart_url', 'items_rows', 'support_phone', 'support_email'],
                'is_active' => true,
            ],
            [
                'key' => 'payment_success',
                'name' => 'نجاح الدفع',
                'description' => 'يرسل بعد الدفع الناجح',
                'subject' => 'تم تأكيد الدفع - الطلب {{order_number}}',
                'body_html' => self::defaultPaymentSuccessHtml(),
                'body_text' => 'مرحباً {{user_name}}، تم الدفع بنجاح للطلب {{order_number}}. الإجمالي: {{grand_total}}.',
                'variables' => ['app_name', 'user_name', 'order_number', 'order_date', 'payment_method', 'items_rows', 'subtotal', 'discount_total', 'shipping_total', 'grand_total', 'order_url', 'support_phone', 'support_email', 'store_address'],
                'is_active' => true,
            ],
            [
                'key' => 'payment_failed',
                'name' => 'فشل الدفع',
                'description' => 'يرسل عند فشل الدفع',
                'subject' => 'فشل الدفع - الطلب {{order_number}}',
                'body_html' => self::defaultPaymentFailedHtml(),
                'body_text' => 'مرحباً {{user_name}}، فشل الدفع للطلب {{order_number}}. السبب: {{reason}}. أعد المحاولة: {{retry_url}}',
                'variables' => ['app_name', 'user_name', 'order_number', 'reason', 'retry_url', 'support_phone', 'support_email'],
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $data) {
            $template = self::query()->firstOrCreate(
                ['key' => $data['key']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'subject' => $data['subject'],
                    'body_html' => $data['body_html'],
                    'body_text' => $data['body_text'],
                    'variables' => $data['variables'],
                    'is_active' => $data['is_active'],
                ]
            );

            $key = $data['key'];
            if (! $template->wasRecentlyCreated && isset($legacy[$key]) && $template->body_html === $legacy[$key]) {
                $template->update([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'subject' => $data['subject'],
                    'body_html' => $data['body_html'],
                    'body_text' => $data['body_text'],
                    'variables' => $data['variables'],
                ]);
            }
        }
    }

    private static function emailLayout(string $title, string $badge, string $contentHtml, ?string $ctaLabel = null, ?string $ctaUrl = null): string
    {
        $cta = '';
        if (is_string($ctaLabel) && $ctaLabel !== '' && is_string($ctaUrl) && $ctaUrl !== '') {
            $cta = <<<HTML
                <tr>
                    <td align="center" style="padding: 18px 28px 6px 28px;">
                        <a href="{$ctaUrl}" style="background:#FFD700;color:#003399;text-decoration:none;display:inline-block;padding:14px 26px;border-radius:10px;font-weight:700;font-size:14px;">
                            {$ctaLabel}
                        </a>
                    </td>
                </tr>
            HTML;
        }

        return <<<HTML
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:24px 0;">
        <tr>
            <td align="center" style="padding:0 12px;">
                <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#003399 0%,#0055cc 100%);padding:26px 28px;color:#ffffff;">
                            <div style="font-size:22px;font-weight:800;line-height:1.2;">{{app_name}}</div>
                            <div style="opacity:0.9;margin-top:6px;font-size:14px;line-height:1.6;">{$title}</div>
                            <div style="margin-top:14px;display:inline-block;background:#FFD700;color:#003399;padding:8px 16px;border-radius:999px;font-size:13px;font-weight:800;">{$badge}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:26px 28px;color:#111827;font-size:15px;line-height:1.9;">
                            {$contentHtml}
                        </td>
                    </tr>
                    {$cta}
                    <tr>
                        <td style="padding:18px 28px 24px 28px;background:#f9fafb;border-top:1px solid #e5e7eb;color:#6b7280;font-size:12px;line-height:1.8;">
                            <div style="font-weight:700;color:#374151;margin-bottom:6px;">للمساعدة تواصل معنا</div>
                            <div>هاتف: {{support_phone}} | بريد: {{support_email}}</div>
                            <div style="margin-top:6px;">{{store_address}}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    private static function defaultOtpHtml(): string
    {
        $content = <<<HTML
<div style="font-size:15px;color:#111827;">مرحباً <strong>{{user_name}}</strong>،</div>
<div style="margin-top:8px;color:#374151;">{{purpose}}</div>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:18px;background:#f3f4f6;border-radius:14px;">
    <tr>
        <td style="padding:18px 16px;border-right:4px solid #0055cc;text-align:center;">
            <div style="color:#6b7280;font-size:12px;font-weight:800;letter-spacing:1px;margin-bottom:10px;">رمز التحقق</div>
            <div style="display:inline-block;background:#ffffff;border-radius:12px;padding:14px 18px;font-family:ui-monospace,Menlo,Monaco,Consolas,monospace;font-size:30px;font-weight:900;letter-spacing:8px;color:#003399;">
                {{otp}}
            </div>
        </td>
    </tr>
</table>

<div style="margin-top:16px;background:#fff7ed;border-right:4px solid #f59e0b;border-radius:12px;padding:12px 14px;color:#92400e;">
    هذا الرمز صالح لمدة <strong>{{expires_minutes}}</strong> دقيقة فقط.
</div>
<div style="margin-top:12px;background:#fdf2f8;border-right:4px solid #db2777;border-radius:12px;padding:12px 14px;color:#9d174d;">
    لا تشارك هذا الرمز مع أي شخص.
</div>
HTML;

        return self::emailLayout('رمز التحقق', 'OTP', $content);
    }

    private static function defaultCartReminderHtml(): string
    {
        $content = <<<HTML
<div style="font-size:15px;color:#111827;">مرحباً <strong>{{user_name}}</strong>،</div>
<div style="margin-top:8px;color:#374151;">لديك منتجات في سلة التسوق ويمكنك إكمال الطلب بسهولة.</div>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
    <tr>
        <td style="background:#003399;color:#ffffff;padding:10px 12px;font-weight:800;font-size:13px;">المنتج</td>
        <td style="background:#003399;color:#ffffff;padding:10px 12px;font-weight:800;font-size:13px;text-align:center;">الكمية</td>
        <td style="background:#003399;color:#ffffff;padding:10px 12px;font-weight:800;font-size:13px;text-align:left;">الإجمالي</td>
    </tr>
    {{items_rows}}
</table>
HTML;

        return self::emailLayout('تذكير بالسلة', 'Cart', $content, 'إكمال الطلب', '{{cart_url}}');
    }

    private static function defaultPaymentSuccessHtml(): string
    {
        $content = <<<HTML
<div style="font-size:15px;color:#111827;">مرحباً <strong>{{user_name}}</strong>،</div>
<div style="margin-top:8px;color:#374151;">تم تأكيد الدفع بنجاح لطلبك. نجهز طلبك الآن للشحن.</div>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:14px;">
    <tr>
        <td style="padding:14px 16px;">
            <div style="font-weight:800;color:#003399;margin-bottom:8px;">تفاصيل الطلب</div>
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="font-size:13px;color:#374151;line-height:1.8;">
                <tr>
                    <td style="padding:6px 0;color:#6b7280;font-weight:700;">رقم الطلب</td>
                    <td style="padding:6px 0;text-align:left;font-weight:800;color:#111827;">{{order_number}}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7280;font-weight:700;">تاريخ الطلب</td>
                    <td style="padding:6px 0;text-align:left;">{{order_date}}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7280;font-weight:700;">طريقة الدفع</td>
                    <td style="padding:6px 0;text-align:left;">{{payment_method}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:18px;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;">
    <tr>
        <td style="background:#003399;color:#ffffff;padding:10px 12px;font-weight:800;font-size:13px;">المنتج</td>
        <td style="background:#003399;color:#ffffff;padding:10px 12px;font-weight:800;font-size:13px;text-align:center;">الكمية</td>
        <td style="background:#003399;color:#ffffff;padding:10px 12px;font-weight:800;font-size:13px;text-align:left;">سعر الوحدة</td>
        <td style="background:#003399;color:#ffffff;padding:10px 12px;font-weight:800;font-size:13px;text-align:left;">الإجمالي</td>
    </tr>
    {{items_rows}}
</table>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:14px;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;">
    <tr>
        <td style="padding:14px 16px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="font-size:13px;color:#374151;line-height:1.8;">
                <tr>
                    <td style="padding:6px 0;color:#6b7280;font-weight:700;">الإجمالي الفرعي</td>
                    <td style="padding:6px 0;text-align:left;font-weight:800;">{{subtotal}}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7280;font-weight:700;">خصم</td>
                    <td style="padding:6px 0;text-align:left;font-weight:800;color:#16a34a;">-{{discount_total}}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;color:#6b7280;font-weight:700;">الشحن</td>
                    <td style="padding:6px 0;text-align:left;font-weight:800;">{{shipping_total}}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#003399;font-weight:900;font-size:15px;border-top:1px solid #e5e7eb;">الإجمالي</td>
                    <td style="padding:10px 0;text-align:left;font-weight:900;font-size:15px;color:#003399;border-top:1px solid #e5e7eb;">{{grand_total}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
HTML;

        return self::emailLayout('نجاح الدفع', 'Paid', $content, 'عرض الطلب', '{{order_url}}');
    }

    private static function defaultPaymentFailedHtml(): string
    {
        $content = <<<HTML
<div style="font-size:15px;color:#111827;">مرحباً <strong>{{user_name}}</strong>،</div>
<div style="margin-top:8px;color:#374151;">للأسف لم تكتمل عملية الدفع للطلب <strong>{{order_number}}</strong>.</div>
<div style="margin-top:14px;background:#fef2f2;border-right:4px solid #ef4444;border-radius:12px;padding:12px 14px;color:#991b1b;">
    السبب: {{reason}}
</div>
<div style="margin-top:10px;color:#374151;">يمكنك إعادة المحاولة من خلال الزر التالي.</div>
HTML;

        return self::emailLayout('فشل الدفع', 'Failed', $content, 'إعادة المحاولة', '{{retry_url}}');
    }
}
