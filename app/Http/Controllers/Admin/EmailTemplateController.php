<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    public function index()
    {
        EmailTemplate::ensureDefaults();

        $templates = EmailTemplate::query()
            ->orderBy('name')
            ->get();

        return view('admin.email-management.templates.index', compact('templates'));
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-management.templates.edit', [
            'template' => $emailTemplate,
        ]);
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'subject' => ['nullable', 'string', 'max:255'],
            'body_html' => ['required', 'string'],
            'body_text' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);

        $emailTemplate->update($validated);

        return redirect()
            ->route('admin.email-templates.edit', $emailTemplate)
            ->with('success', 'تم حفظ قالب البريد بنجاح');
    }

    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        $data = $this->sampleDataForTemplate($emailTemplate);
        $rendered = $this->renderTemplate($emailTemplate, $data);

        $format = $request->query('format', 'html');
        if ($format === 'text') {
            return response($rendered['text'] ?? '', 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
        }

        return response($rendered['html'] ?? '', 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    public function testSend(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ]);

        $data = $this->sampleDataForTemplate($emailTemplate);
        $rendered = $this->renderTemplate($emailTemplate, $data);

        try {
            Mail::send([], [], function ($message) use ($validated, $rendered) {
                $message->to($validated['test_email'])
                    ->subject($rendered['subject'] ?? 'Test Email')
                    ->html($rendered['html'] ?? '');

                if (is_string($rendered['text'] ?? null) && $rendered['text'] !== '') {
                    $message->text($rendered['text']);
                }
            });

            return back()->with('success', 'تم إرسال رسالة اختبار للقالب بنجاح');
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            $username = config('mail.mailers.smtp.username');
            $password = config('mail.mailers.smtp.password');

            if (is_string($username) && $username !== '') {
                $message = str_replace($username, '[MAIL_USERNAME]', $message);
            }
            if (is_string($password) && $password !== '') {
                $message = str_replace($password, '[MAIL_PASSWORD]', $message);
            }

            return back()->with('error', 'فشل إرسال رسالة الاختبار. '.$message);
        }
    }

    private function sampleDataForTemplate(EmailTemplate $template): array
    {
        $appName = Setting::getValue('site_name', config('app.name', 'El Sedeek Store'));
        $supportPhone = Setting::getValue('site_phone', '01223694848');
        $supportEmail = Setting::getValue('site_email', 'notifications@elsedeek-store.com');
        $storeAddress = Setting::getValue('site_address', 'أسيوط، مصر');

        $base = [
            'app_name' => $appName,
            'support_phone' => $supportPhone,
            'support_email' => $supportEmail,
            'store_address' => $storeAddress,
            'user_name' => 'عميل تجريبي',
        ];

        if ($template->key === 'otp') {
            return array_merge($base, [
                'purpose' => 'هذه رسالة اختبار لرمز التحقق (OTP).',
                'otp' => '123456',
                'expires_minutes' => '15',
            ]);
        }

        if ($template->key === 'cart_reminder') {
            return array_merge($base, [
                'cart_url' => url('/cart'),
                'items_rows' => $this->sampleCartRows(),
            ]);
        }

        if ($template->key === 'payment_success') {
            return array_merge($base, [
                'order_number' => 'TEST-1001',
                'order_date' => now()->format('Y-m-d H:i'),
                'payment_method' => 'بطاقة',
                'items_rows' => $this->sampleOrderRows(),
                'subtotal' => '150.00 ج.م',
                'discount_total' => '0.00 ج.م',
                'shipping_total' => '20.00 ج.م',
                'grand_total' => '170.00 ج.م',
                'order_url' => url('/orders/1'),
            ]);
        }

        if ($template->key === 'payment_failed') {
            return array_merge($base, [
                'order_number' => 'TEST-1001',
                'reason' => 'فشل اختبار بوابة الدفع',
                'retry_url' => url('/checkout'),
            ]);
        }

        return $base;
    }

    private function renderTemplate(EmailTemplate $template, array $data): array
    {
        $rawKeys = ['items_rows'];

        $replace = function (?string $text) use ($data, $rawKeys): string {
            if (! is_string($text)) {
                return '';
            }

            return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', function ($matches) use ($data, $rawKeys) {
                $key = $matches[1];
                $value = $data[$key] ?? '';
                $value = is_scalar($value) ? (string) $value : '';

                if (in_array($key, $rawKeys, true)) {
                    return $value;
                }

                return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }, $text) ?? $text;
        };

        return [
            'subject' => $replace($template->subject ?: $template->name),
            'html' => $replace($template->body_html),
            'text' => $replace($template->body_text),
        ];
    }

    private function sampleCartRows(): string
    {
        $rows = [];

        $rows[] = $this->row([
            'كتاب تعليم الحروف',
            '1',
            '75.00 ج.م',
        ], [null, 'text-align:center;', 'text-align:left;']);

        $rows[] = $this->row([
            'لعبة تركيب (Puzzle)',
            '2',
            '120.00 ج.م',
        ], [null, 'text-align:center;', 'text-align:left;']);

        return implode('', $rows);
    }

    private function sampleOrderRows(): string
    {
        $rows = [];

        $rows[] = $this->row([
            'كتاب تعليم الحروف',
            '1',
            '75.00 ج.م',
            '75.00 ج.م',
        ], [null, 'text-align:center;', 'text-align:left;', 'text-align:left;font-weight:700;']);

        $rows[] = $this->row([
            'لعبة تركيب (Puzzle)',
            '2',
            '60.00 ج.م',
            '120.00 ج.م',
        ], [null, 'text-align:center;', 'text-align:left;', 'text-align:left;font-weight:700;']);

        return implode('', $rows);
    }

    private function row(array $cells, array $cellStyles): string
    {
        $tds = [];
        foreach ($cells as $i => $cell) {
            $style = 'padding:10px 12px;border-bottom:1px solid #e5e7eb;color:#111827;font-size:13px;'.($cellStyles[$i] ?? '');
            $tds[] = '<td style="'.$style.'">'.htmlspecialchars((string) $cell, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'</td>';
        }

        return '<tr>'.implode('', $tds).'</tr>';
    }
}
