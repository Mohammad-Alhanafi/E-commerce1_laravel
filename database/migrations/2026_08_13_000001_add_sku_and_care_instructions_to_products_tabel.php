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
            if (!Schema::hasColumn('products_tabel', 'sku')) {
                $table->string('sku', 100)->nullable()->unique()->after('name');
            }

            if (!Schema::hasColumn('products_tabel', 'care_instructions')) {
                $table->text('care_instructions')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_tabel', function (Blueprint $table) {
            if (Schema::hasColumn('products_tabel', 'sku')) {
                $table->dropColumn('sku');
            }

            if (Schema::hasColumn('products_tabel', 'care_instructions')) {
                $table->dropColumn('care_instructions');
            }
        });
    }
};