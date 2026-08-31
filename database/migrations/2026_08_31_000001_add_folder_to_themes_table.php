<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('themes') && ! Schema::hasColumn('themes', 'folder')) {
            Schema::table('themes', function (Blueprint $table) {
                $table->string('folder')->nullable()->after('mode');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('themes') && Schema::hasColumn('themes', 'folder')) {
            Schema::table('themes', function (Blueprint $table) {
                $table->dropColumn('folder');
            });
        }
    }
};
