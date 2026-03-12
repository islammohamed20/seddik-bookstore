<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_tag_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('tag_option_id')->constrained('tag_options')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['product_id', 'tag_option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_tag_option');
    }
};

