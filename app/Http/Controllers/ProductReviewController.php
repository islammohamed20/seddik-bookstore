<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // التحقق من أن المستخدم يمكنه المراجعة (مثلاً هل اشترى المنتج)
        // هنا يمكنك إضافة منطق التحقق من الشراء إذا لزم الأمر

        $review = ProductReview::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_approved' => false, // تحتاج لموافقة الأدمن
            ]
        );

        return redirect()->back()->with('success', 'تم إرسال مراجعتك بنجاح وستظهر بعد الموافقة');
    }
}
