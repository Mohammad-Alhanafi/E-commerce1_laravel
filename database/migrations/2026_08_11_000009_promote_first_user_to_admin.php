<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->orderBy('id')
            ->limit(1)
            ->update(['role' => 'admin']);
    }

    public function down(): void
    {
        //
    }
};