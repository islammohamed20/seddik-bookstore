<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('order_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->timestamp('used_at')->index();
            $table->timestamps();

            $table->index(['coupon_id', 'user_id'], 'coupons_usage_coupon_user_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons_usage');
    }
};
