<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products_tabel', function (Blueprint $table) {
            if (Schema::hasColumn('products_tabel', 'status') && Schema::hasColumn('products_tabel', 'created_at')) {
                $table->index(['status', 'created_at'], 'idx_products_status_created');
            }
            if (Schema::hasColumn('products_tabel', 'category_id') && Schema::hasColumn('products_tabel', 'status')) {
                $table->index(['category_id', 'status'], 'idx_products_category_status');
            }
            if (Schema::hasColumn('products_tabel', 'sku')) {
                $table->index('sku', 'idx_products_sku');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'is_active') && Schema::hasColumn('categories', 'sort_order')) {
                $table->index(['is_active', 'sort_order'], 'idx_categories_active_sort');
            }
        });

        Schema::table('sliders', function (Blueprint $table) {
            if (Schema::hasColumn('sliders', 'status') && Schema::hasColumn('sliders', 'order')) {
                $table->index(['status', 'order'], 'idx_sliders_status_order');
            }
        });

        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                if (Schema::hasColumn('comments', 'parent_id') && Schema::hasColumn('comments', 'created_at')) {
                    $table->index(['parent_id', 'created_at'], 'idx_comments_parent_created');
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'user_id') && Schema::hasColumn('orders', 'created_at')) {
                    $table->index(['user_id', 'created_at'], 'idx_orders_user_created');
                }
                if (Schema::hasColumn('orders', 'status')) {
                    $table->index('status', 'idx_orders_status');
                }
            });
        }

        if (Schema::hasTable('variants')) {
            Schema::table('variants', function (Blueprint $table) {
                if (Schema::hasColumn('variants', 'product_id')) {
                    $table->index('product_id', 'idx_variants_product_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_tabel', function (Blueprint $table) {
            $table->dropIndex('idx_products_status_created');
            $table->dropIndex('idx_products_category_status');
            $table->dropIndex('idx_products_sku');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_active_sort');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropIndex('idx_sliders_status_order');
        });

        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropIndex('idx_comments_parent_created');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex('idx_orders_user_created');
                $table->dropIndex('idx_orders_status');
            });
        }

        if (Schema::hasTable('variants')) {
            Schema::table('variants', function (Blueprint $table) {
                $table->dropIndex('idx_variants_product_id');
            });
        }
    }
};
