<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('provider');
            $table->string('payment_method')->nullable();
            $table->string('currency', 3)->default('EGP');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending')->index();
            $table->string('transaction_id')->nullable()->index();
            $table->string('reference')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('payment_token')->nullable();
            $table->json('payload')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
