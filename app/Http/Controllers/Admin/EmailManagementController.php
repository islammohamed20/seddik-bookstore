<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailManagementController extends Controller
{
    /**
     * Display newsletter subscribers
     */
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->latest()->paginate(50);
        
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::active()->count(),
            'verified' => NewsletterSubscriber::verified()->count(),
            'unsubscribed' => NewsletterSubscriber::whereNotNull('unsubscribed_at')->count(),
        ];

        return view('admin.email-management.index', compact('subscribers', 'stats'));
    }

    /**
     * Show bulk email form
     */
    public function compose()
    {
        $subscribersCount = NewsletterSubscriber::active()->verified()->count();
        return view('admin.email-management.compose', compact('subscribersCount'));
    }

    /**
     * Send bulk email
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:all,active,test',
        ]);

        $query = NewsletterSubscriber::query();

        if ($request->recipient_type === 'active') {
            $query->active()->verified();
        } elseif ($request->recipient_type === 'test') {
            // Send to admin only for testing
            $query->where('email', auth()->user()->email);
        }

        $subscribers = $query->get();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'لا يوجد مشتركين لإرسال البريد إليهم');
        }

        $sent = 0;
        $failed = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::send('emails.newsletter', [
                    'content' => $request->message,
                    'subscriber' => $subscriber,
                ], function ($mail) use ($subscriber, $request) {
                    $mail->to($subscriber->email)
                         ->subject($request->subject);
                });
                $sent++;
            } catch (\Exception $e) {
                $failed++;
                \Log::error("Failed to send email to {$subscriber->email}: " . $e->getMessage());
            }
        }

        return back()->with('success', "تم إرسال {$sent} رسالة بنجاح" . ($failed > 0 ? " وفشل {$failed}" : ''));
    }

    /**
     * Send a test email
     */
    public function testSend(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255',
            'test_subject' => 'required|string|max:255',
            'test_message' => 'nullable|string',
        ]);

        try {
            Mail::raw($request->test_message ?: 'هذه رسالة اختبار من متجر الصديق.', function ($mail) use ($request) {
                $mail->to($request->test_email)
                    ->subject($request->test_subject);
            });

            return back()->with('success', 'تم إرسال رسالة الاختبار بنجاح');
        } catch (\Exception $e) {
            \Log::error('Test email failed: ' . $e->getMessage());
            return back()->with('error', 'فشل إرسال رسالة الاختبار. تحقق من إعدادات البريد.');
        }
    }

    /**
     * Export subscribers
     */
    public function export()
    {
        $subscribers = NewsletterSubscriber::active()->get();
        
        $filename = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Email', 'Name', 'Subscribed At', 'Verified', 'Source']);
            
            // Data
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->name,
                    $subscriber->created_at->format('Y-m-d H:i'),
                    $subscriber->isVerified() ? 'Yes' : 'No',
                    $subscriber->source,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete subscriber
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'تم حذف المشترك بنجاح');
    }
}
