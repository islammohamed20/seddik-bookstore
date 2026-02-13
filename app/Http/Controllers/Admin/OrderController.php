<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Search by order number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $statuses = [
            Order::STATUS_PENDING => 'قيد الانتظار',
            Order::STATUS_PROCESSING => 'قيد المعالجة',
            Order::STATUS_SHIPPED => 'تم الشحن',
            Order::STATUS_DELIVERED => 'تم التوصيل',
            Order::STATUS_CANCELLED => 'ملغي',
        ];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payments', 'coupon']);

        $statuses = [
            Order::STATUS_PENDING => 'قيد الانتظار',
            Order::STATUS_PROCESSING => 'قيد المعالجة',
            Order::STATUS_SHIPPED => 'تم الشحن',
            Order::STATUS_DELIVERED => 'تم التوصيل',
            Order::STATUS_CANCELLED => 'ملغي',
        ];

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ]),
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'تم تحديث الطلب بنجاح');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ]),
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // إنشاء إشعار للتحديث
        if ($oldStatus !== $validated['status']) {
            $statusNames = [
                Order::STATUS_PENDING => 'قيد الانتظار',
                Order::STATUS_PROCESSING => 'قيد المعالجة',
                Order::STATUS_SHIPPED => 'تم الشحن',
                Order::STATUS_DELIVERED => 'تم التوصيل',
                Order::STATUS_CANCELLED => 'ملغي',
            ];
            $newStatusName = $statusNames[$validated['status']] ?? $validated['status'];
            AdminNotification::createOrderNotification(
                $order, 
                "تم تحديث الطلب #{$order->id} إلى: {$newStatusName}"
            );
        }

        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->status !== Order::STATUS_CANCELLED) {
            return back()->with('error', 'لا يمكن حذف الطلب إلا إذا كان ملغياً');
        }

        $order->items()->delete();
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }
}
