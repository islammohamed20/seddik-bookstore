<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailManagementController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::get('products/import', [ProductController::class, 'importPage'])->name('products.import-page');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('products/template', [ProductController::class, 'template'])->name('products.template');
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::delete('products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete-image');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::post('categories/{category}/toggle-featured', [CategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured');

    // Brands
    Route::resource('brands', BrandController::class);
    Route::post('brands/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('brands.toggle-status');

    // Orders
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Coupons
    Route::resource('coupons', CouponController::class);
    Route::post('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('coupons.toggle-status');

    // Sliders
    Route::resource('sliders', SliderController::class);
    Route::post('sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggle-status');

    // Offers
    Route::resource('offers', OfferController::class);
    Route::post('offers/{offer}/toggle-status', [OfferController::class, 'toggleStatus'])->name('offers.toggle-status');

    // Pages
    Route::resource('pages', PageController::class);
    Route::post('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // Visit Reports
    Route::get('visit-reports', [VisitReportController::class, 'index'])->name('visit-reports.index');

    // Shipping Zones
    Route::resource('shipping-zones', ShippingZoneController::class);
    Route::post('shipping-zones/{shippingZone}/toggle-status', [ShippingZoneController::class, 'toggleStatus'])->name('shipping-zones.toggle-status');

    // Shipping Methods
    Route::resource('shipping-methods', ShippingMethodController::class);
    Route::post('shipping-methods/{shippingMethod}/toggle-status', [ShippingMethodController::class, 'toggleStatus'])->name('shipping-methods.toggle-status');

    // Contact Messages
    Route::resource('contact-messages', ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::post('contact-messages/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::post('contact-messages/{contactMessage}/mark-read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.mark-read');

    // Email Management & Newsletter
    Route::get('email-management', [EmailManagementController::class, 'index'])->name('email-management.index');
    Route::get('email-management/compose', [EmailManagementController::class, 'compose'])->name('email-management.compose');
    Route::post('email-management/send', [EmailManagementController::class, 'send'])->name('email-management.send');
    Route::post('email-management/test-send', [EmailManagementController::class, 'testSend'])->name('email-management.test-send');
    Route::get('email-management/export', [EmailManagementController::class, 'export'])->name('email-management.export');
    Route::delete('email-management/{subscriber}', [EmailManagementController::class, 'destroy'])->name('email-management.destroy');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/clear-read', [NotificationController::class, 'clearRead'])->name('clear-read');
    });
});
