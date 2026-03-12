<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tag_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_group_id')->constrained('tag_groups')->cascadeOnDelete();
            $table->string('slug');
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['tag_group_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_options');
    }
};

