<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'البريد الإلكتروني غير صالح');
        }

        // Check if already subscribed
        $existing = NewsletterSubscriber::where('email', $request->email)->first();

        if ($existing) {
            if ($existing->isActive()) {
                return back()->with('info', 'أنت مشترك بالفعل في النشرة البريدية');
            }
            
            // Reactivate if previously unsubscribed
            $existing->update([
                'is_active' => true,
                'unsubscribed_at' => null,
                'name' => $request->name ?? $existing->name,
            ]);

            return back()->with('success', 'تم تفعيل اشتراكك في النشرة البريدية بنجاح');
        }

        // Create new subscriber
        NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'source' => $request->source ?? 'website',
            'verified_at' => now(), // Auto-verify for simplicity
        ]);

        return back()->with('success', 'شكراً لك! تم الاشتراك في النشرة البريدية بنجاح');
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, $token)
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)->firstOrFail();
        $subscriber->unsubscribe();

        return view('storefront.newsletter.unsubscribed', compact('subscriber'));
    }

    /**
     * Verify email (optional - for double opt-in)
     */
    public function verify(Request $request, $token)
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)
            ->whereNull('verified_at')
            ->firstOrFail();

        $subscriber->verify();

        return redirect()->route('home')->with('success', 'تم تأكيد بريدك الإلكتروني بنجاح!');
    }
}
