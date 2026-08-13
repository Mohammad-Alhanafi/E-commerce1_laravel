<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products_tabel')->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('color')->nullable();
            $table->decimal('additional_price', 8, 2)->nullable()->default(0);
            $table->decimal('variant_price', 8, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('status')->nullable()->default('active');
            $table->text('notes')->nullable();
            $table->string('variant_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};