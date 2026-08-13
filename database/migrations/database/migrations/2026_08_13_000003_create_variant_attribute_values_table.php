<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variants_id')->constrained('variants')->cascadeOnDelete();
            $table->foreignId('attribute_values_id')->constrained('attribute_values')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_attribute_values');
    }
};