<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'icon',
        'url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Notification types
    const TYPE_ORDER = 'order';
    const TYPE_LOGIN = 'login';
    const TYPE_REGISTRATION = 'registration';
    const TYPE_EMAIL = 'email';
    const TYPE_MESSAGE = 'message';

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    // Static helpers
    public static function createOrderNotification($order, $message = null)
    {
        return self::create([
            'type' => self::TYPE_ORDER,
            'title' => 'طلب جديد',
            'message' => $message ?? "تم استلام طلب جديد رقم #{$order->id}",
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number ?? $order->id,
                'total' => $order->total,
            ],
            'icon' => 'fas fa-shopping-cart',
            'url' => route('admin.orders.show', $order->id),
        ]);
    }

    public static function createLoginNotification($user)
    {
        return self::create([
            'type' => self::TYPE_LOGIN,
            'title' => 'تسجيل دخول جديد',
            'message' => "قام {$user->name} بتسجيل الدخول",
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'ip' => request()->ip(),
            ],
            'icon' => 'fas fa-sign-in-alt',
            'url' => route('admin.users.show', $user->id),
        ]);
    }

    public static function createRegistrationNotification($user)
    {
        return self::create([
            'type' => self::TYPE_REGISTRATION,
            'title' => 'عميل جديد',
            'message' => "قام {$user->name} بالتسجيل في الموقع",
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ],
            'icon' => 'fas fa-user-plus',
            'url' => route('admin.users.show', $user->id),
        ]);
    }

    public static function createEmailNotification($subject, $recipient)
    {
        return self::create([
            'type' => self::TYPE_EMAIL,
            'title' => 'بريد جديد',
            'message' => "تم إرسال بريد: {$subject}",
            'data' => [
                'subject' => $subject,
                'recipient' => $recipient,
            ],
            'icon' => 'fas fa-envelope',
            'url' => route('admin.email-management.index'),
        ]);
    }

    public static function createMessageNotification($message)
    {
        return self::create([
            'type' => self::TYPE_MESSAGE,
            'title' => 'رسالة جديدة',
            'message' => "رسالة من {$message->name}: {$message->subject}",
            'data' => [
                'message_id' => $message->id,
                'from_name' => $message->name,
                'subject' => $message->subject,
            ],
            'icon' => 'fas fa-comment-dots',
            'url' => route('admin.contact-messages.show', $message->id),
        ]);
    }
}
