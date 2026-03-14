<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return view('storefront.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        // التحقق من أن الطلب ينتمي للمستخدم الحالي أو تم إنشاؤه في نفس الجلسة
        $canView = $this->canViewOrder($request, $order);

        if (! $canView) {
            abort(404);
        }

        $order->load(['items', 'items.product', 'payments']);

        return view('storefront.orders.show', [
            'order' => $order,
        ]);
    }

    public function cancel(Request $request, Order $order)
    {
        if (! $this->canViewOrder($request, $order)) {
            abort(404);
        }

        if (! $order->is_cancellable) {
            return back()->with('error', 'لا يمكن إلغاء هذا الطلب في حالته الحالية.');
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $reason = $validated['reason'] ?? 'تم الإلغاء بواسطة العميل';
        $order->cancel($reason);

        return back()->with('success', 'تم إلغاء الطلب بنجاح.');
    }

    /**
     * التحقق من صلاحية عرض الطلب
     */
    protected function canViewOrder(Request $request, Order $order): bool
    {
        // إذا كان المستخدم مسجل دخول
        if ($request->user()) {
            // المسؤول يمكنه رؤية كل الطلبات
            if ($request->user()->hasRole('admin')) {
                return true;
            }

            // المستخدم يمكنه رؤية طلباته فقط
            return $order->user_id === $request->user()->id;
        }

        // للزائرين: السماح فقط إذا كان الطلب تم إنشاؤه في نفس الجلسة
        $lastOrderNumber = $request->session()->get('last_order_number');

        return $lastOrderNumber === $order->order_number;
    }
}
