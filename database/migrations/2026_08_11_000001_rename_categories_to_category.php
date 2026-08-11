<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إعادة تسمية الجدول من categories إلى category (إذا موجود)
        if (Schema::hasTable('categories') && !Schema::hasTable('category')) {
            Schema::rename('categories', 'category');
        }

        // إضافة الأعمدة الناقصة إذا مش موجودة
        Schema::table('category', function (Blueprint $table) {
            if (!Schema::hasColumn('category', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('category', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('category', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (!Schema::hasColumn('category', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
            if (!Schema::hasColumn('category', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('category')) {
            Schema::rename('category', 'categories');
        }
    }
};