<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
