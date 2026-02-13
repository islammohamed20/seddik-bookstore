<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create Shops Table (if not exists)
        if (! Schema::hasTable('shops')) {
            Schema::create('shops', function (Blueprint $table) {
                $table->id();
                $table->string('name_ar');
                $table->string('name_en')->nullable();
                $table->string('slug')->unique();
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->string('logo')->nullable();
                $table->string('cover_image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Update Categories (Ensure columns exist)
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'name_ar')) {
                $table->string('name_ar')->nullable();
            }
            if (! Schema::hasColumn('categories', 'name_en')) {
                $table->string('name_en')->nullable();
            }
            if (! Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable();
            }
            if (! Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
            if (! Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->index();
            }
        });

        // 3. Update Products (Ensure columns exist & add shop_id)
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'shop_id')) {
                $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            }
            if (! Schema::hasColumn('products', 'name_ar')) {
                $table->string('name_ar')->nullable();
            }
            if (! Schema::hasColumn('products', 'name_en')) {
                $table->string('name_en')->nullable();
            }
            if (! Schema::hasColumn('products', 'description_ar')) {
                $table->text('description_ar')->nullable();
            }
            if (! Schema::hasColumn('products', 'description_en')) {
                $table->text('description_en')->nullable();
            }
            if (! Schema::hasColumn('products', 'old_price')) {
                $table->decimal('old_price', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('products', 'currency')) {
                $table->string('currency')->default('EGP');
            }
            if (! Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (! Schema::hasColumn('products', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
        });

        // 4. Product Attributes Tables (New)
        if (! Schema::hasTable('product_attributes')) {
            Schema::create('product_attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name_ar');
                $table->string('name_en')->nullable();
                $table->string('slug')->unique();
                $table->string('input_type')->default('text'); // text, select, textarea, etc.
                $table->json('options')->nullable(); // For select/multi_select
                $table->string('validation_rules')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('category_product_attribute')) {
            Schema::create('category_product_attribute', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
                $table->boolean('is_required')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('product_attribute_values')) {
            Schema::create('product_attribute_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('product_attribute_id')->constrained('product_attributes')->cascadeOnDelete();
                $table->text('value'); // Can be JSON if needed for multi-select
                $table->timestamps();
            });
        }

        // 5. Update Sliders
        if (Schema::hasTable('sliders')) {
            Schema::table('sliders', function (Blueprint $table) {
                if (! Schema::hasColumn('sliders', 'title_ar')) {
                    $table->string('title_ar')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'title_en')) {
                    $table->string('title_en')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'subtitle_ar')) {
                    $table->string('subtitle_ar')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'subtitle_en')) {
                    $table->string('subtitle_en')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'cta_text_ar')) {
                    $table->string('cta_text_ar')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'cta_text_en')) {
                    $table->string('cta_text_en')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'cta_link')) {
                    $table->string('cta_link')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'image_mobile')) {
                    $table->string('image_mobile')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'start_date')) {
                    $table->dateTime('start_date')->nullable();
                }
                if (! Schema::hasColumn('sliders', 'end_date')) {
                    $table->dateTime('end_date')->nullable();
                }
            });
        }

        // 6. Settings Table
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->string('group')->default('general');
                $table->text('value_ar')->nullable();
                $table->text('value_en')->nullable();
                $table->string('type')->default('text');
                $table->timestamps();
            });
        }

        // 7. Contact Messages
        if (! Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->text('message');
                $table->string('status')->default('new'); // new, read, archived
                $table->timestamps();
            });
        }

        // 8. Facebook Posts
        if (! Schema::hasTable('facebook_posts')) {
            Schema::create('facebook_posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
                $table->string('fb_post_id')->unique();
                $table->text('message')->nullable();
                $table->string('permalink_url')->nullable();
                $table->string('image_url')->nullable();
                $table->dateTime('posted_at')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->json('raw_payload')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Drop tables in reverse order of creation
        Schema::dropIfExists('facebook_posts');
        Schema::dropIfExists('product_attribute_values');
        Schema::dropIfExists('category_product_attribute');
        Schema::dropIfExists('product_attributes');
        // We don't drop columns from existing tables to prevent data loss in dev
    }
};
