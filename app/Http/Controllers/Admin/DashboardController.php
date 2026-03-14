<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::pending()->count(),
            'total_users' => User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'customer');
            })->count(),
            'total_customers' => User::role('customer')->count(),
            'total_categories' => Category::count(),
            'unread_messages' => ContactMessage::unread()->count(),
        ];

        // Revenue stats
        $stats['total_revenue'] = Order::completed()->sum('grand_total');
        $stats['today_revenue'] = Order::completed()
            ->whereDate('created_at', today())
            ->sum('grand_total');
        $stats['month_revenue'] = Order::completed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total');

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Orders chart data (last 7 days)
        $ordersChart = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(grand_total) as revenue')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top selling products
        $topProducts = Product::query()
            ->with('images')
            ->select('products.*')
            ->selectSub(function ($query) {
                $query->from('order_items')
                    ->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('order_items.product_id', 'products.id');
            }, 'sold_quantity')
            ->whereExists(function ($query) {
                $query->from('order_items')
                    ->selectRaw('1')
                    ->whereColumn('order_items.product_id', 'products.id')
                    ->where('quantity', '>', 0);
            })
            ->orderByDesc('sold_quantity')
            ->take(5)
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'ordersChart',
            'topProducts',
            'lowStockProducts'
        ));
    }
}
