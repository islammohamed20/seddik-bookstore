<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        $statuses = [
            ContactMessage::STATUS_UNREAD => 'غير مقروء',
            ContactMessage::STATUS_READ => 'مقروء',
            ContactMessage::STATUS_REPLIED => 'تم الرد',
        ];

        return view('admin.contact-messages.index', compact('messages', 'statuses'));
    }

    public function show(ContactMessage $contactMessage)
    {
        // Mark as read if unread
        if ($contactMessage->status === ContactMessage::STATUS_UNREAD) {
            $contactMessage->markAsRead();
        }

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function markAsRead(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();

        return back()->with('success', 'تم تحديد الرسالة كمقروءة');
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'reply_message' => 'required|string|max:5000',
        ]);

        // Here you would typically send an email
        // Mail::to($contactMessage->email)->send(new ContactReplyMail($validated['reply_message']));

        $contactMessage->update([
            'status' => ContactMessage::STATUS_REPLIED,
            'replied_at' => now(),
            'reply_message' => $validated['reply_message'],
        ]);

        return back()->with('success', 'تم إرسال الرد بنجاح');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'تم حذف الرسالة بنجاح');
    }
}
