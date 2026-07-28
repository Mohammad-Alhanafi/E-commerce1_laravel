<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('themes')) {
            Schema::create('themes', function (Blueprint $table) {
                $table->id();
                $table->string('name')->default('Default Theme');
                $table->string('primary_color', 10)->default('#D4AF37');
                $table->string('secondary_color', 10)->default('#B8941E');
                $table->string('hover_color', 10)->default('#C89B2C');
                $table->string('success_color', 10)->default('#28A745');
                $table->string('danger_color', 10)->default('#DC3545');
                $table->string('warning_color', 10)->default('#FFC107');
                $table->string('info_color', 10)->default('#17A2B8');
                $table->string('dark_bg', 10)->default('#1A1A1A');
                $table->string('light_bg', 10)->default('#F8F9FA');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Insert default luxury gold theme row
            DB::table('themes')->insert([
                'name'            => 'Luxury Gold',
                'primary_color'   => '#D4AF37',
                'secondary_color' => '#B8941E',
                'hover_color'     => '#C89B2C',
                'success_color'   => '#28A745',
                'danger_color'    => '#DC3545',
                'warning_color'   => '#FFC107',
                'info_color'      => '#17A2B8',
                'dark_bg'         => '#1A1A1A',
                'light_bg'        => '#F8F9FA',
                'is_active'       => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
