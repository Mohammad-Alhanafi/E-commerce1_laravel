<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('themes')) {
            Schema::create('themes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('status', 20)->default('published');
                $table->string('mode', 10)->default('both');
                $table->json('colors')->nullable();
                $table->json('overrides')->nullable();
                $table->boolean('is_active')->default(false);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });

            $defaults = config('theme.defaults', []);
            DB::table('themes')->insert([
                'name'        => 'Luxury Gold',
                'description' => 'Default luxury gold theme for Honey Abayah',
                'status'      => 'published',
                'mode'        => 'both',
                'colors'      => json_encode($defaults),
                'overrides'   => json_encode([]),
                'is_active'   => true,
                'is_default'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return;
        }

        Schema::table('themes', function (Blueprint $table) {
            if (! Schema::hasColumn('themes', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (! Schema::hasColumn('themes', 'status')) {
                $table->string('status', 20)->default('published')->after('description');
            }
            if (! Schema::hasColumn('themes', 'mode')) {
                $table->string('mode', 10)->default('both')->after('status');
            }
            if (! Schema::hasColumn('themes', 'colors')) {
                $table->json('colors')->nullable()->after('mode');
            }
            if (! Schema::hasColumn('themes', 'overrides')) {
                $table->json('overrides')->nullable()->after('colors');
            }
            if (! Schema::hasColumn('themes', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('is_active');
            }
        });

        $this->migrateLegacyColumns();
    }

    protected function migrateLegacyColumns(): void
    {
        if (! Schema::hasColumn('themes', 'primary_color')) {
            return;
        }

        $legacyMap = [
            'primary_color'   => 'primary',
            'secondary_color' => 'secondary',
            'hover_color'     => 'btn_hover',
            'success_color'   => 'success',
            'danger_color'    => 'danger',
            'warning_color'   => 'warning',
            'info_color'      => 'info',
            'dark_bg'         => 'background',
            'light_bg'        => null,
        ];

        $defaults = config('theme.defaults', []);

        foreach (DB::table('themes')->get() as $theme) {
            $colors = json_decode($theme->colors ?? 'null', true) ?? $defaults;

            foreach ($legacyMap as $column => $key) {
                if ($key && isset($theme->{$column}) && $theme->{$column}) {
                    $colors[$key] = $theme->{$column};
                }
            }

            if ($theme->light_bg ?? null) {
                // preserve light bg hint in overrides metadata if needed
            }

            DB::table('themes')->where('id', $theme->id)->update([
                'colors'    => json_encode($colors),
                'overrides' => $theme->overrides ?? json_encode([]),
                'status'    => $theme->status ?? 'published',
                'mode'      => $theme->mode ?? 'both',
            ]);
        }

        Schema::table('themes', function (Blueprint $table) {
            $legacy = ['primary_color', 'secondary_color', 'hover_color', 'success_color',
                'danger_color', 'warning_color', 'info_color', 'dark_bg', 'light_bg'];

            foreach ($legacy as $col) {
                if (Schema::hasColumn('themes', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('themes')) {
            return;
        }

        Schema::table('themes', function (Blueprint $table) {
            foreach (['description', 'status', 'mode', 'colors', 'overrides', 'is_default'] as $col) {
                if (Schema::hasColumn('themes', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
