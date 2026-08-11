<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('attribute_values')) {
            Schema::create('attribute_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attributes_id')->constrained('attributes')->cascadeOnDelete();
                $table->string('value');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};