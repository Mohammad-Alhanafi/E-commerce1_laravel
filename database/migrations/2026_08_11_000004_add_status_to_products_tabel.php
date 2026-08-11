<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products_tabel', function (Blueprint $table) {
            if (!Schema::hasColumn('products_tabel', 'status')) {
                $table->string('status')->default('active')->after('image');
            }
            if (!Schema::hasColumn('products_tabel', 'care_instructions')) {
                $table->text('care_instructions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products_tabel', 'sku')) {
                $table->string('sku')->nullable()->after('status');
            }
            if (!Schema::hasColumn('products_tabel', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('sku');
            }
            if (!Schema::hasColumn('products_tabel', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products_tabel', function (Blueprint $table) {
            $table->dropColumn(['status', 'care_instructions', 'sku', 'is_featured', 'is_active']);
        });
    }
};