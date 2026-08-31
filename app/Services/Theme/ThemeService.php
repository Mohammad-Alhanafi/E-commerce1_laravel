<?php

namespace App\Services\Theme;

use App\Models\Theme;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ThemeService
{
    public function __construct(
        protected ColorGeneratorService $generator,
        protected CssVariableBuilder $cssBuilder,
    ) {}

    public function resolveColors(Theme $theme, string $previewMode = 'dark'): array
    {
        $defaults = config('theme.defaults', []);
        $stored = $theme->getMergedColors();

        return array_merge($defaults, $stored);
    }

    public function resolveBothModes(Theme $theme): array
    {
        return [
            'dark'  => $this->resolveColors($theme, 'dark'),
            'light' => $this->mergeLightOverrides($this->resolveColors($theme, 'light')),
        ];
    }

    protected function mergeLightOverrides(array $colors): array
    {
        $overrides = config('theme.light_overrides', []);

        foreach ($overrides as $key => $value) {
            if (! isset($colors[$key]) || in_array($key, config('theme.generated_from_primary', []))) {
                // light-specific structural colors
                if (in_array($key, ['background', 'surface', 'navbar_bg', 'sidebar_bg', 'footer_bg', 'input_bg'])) {
                    $colors[$key] = $value;
                }
            }
        }

        return array_merge($colors, $overrides);
    }

    public function create(array $data): Theme
    {
        return DB::transaction(function () use ($data) {
            $colors = $this->buildColorsFromInput($data);
            $overrides = $data['overrides'] ?? [];

            if (! empty($data['auto_generate']) && ! empty($colors['primary'])) {
                $generated = $this->generator->generateFromPrimary($colors['primary'], $data['preview_mode'] ?? 'dark');
                $colors = $this->applyManualOverrides($generated, $colors, $overrides);
            }

            return Theme::create([
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'status'      => $data['status'] ?? 'draft',
                'mode'        => $data['mode'] ?? 'both',
                'colors'      => $colors,
                'overrides'   => $overrides,
                'is_active'   => false,
                'is_default'  => false,
            ]);
        });
    }

    public function update(Theme $theme, array $data): Theme
    {
        return DB::transaction(function () use ($theme, $data) {
            $colors = $this->buildColorsFromInput($data, $theme);
            $overrides = $data['overrides'] ?? $theme->overrides ?? [];

            if (! empty($data['auto_generate']) && ! empty($colors['primary'])) {
                $generated = $this->generator->generateFromPrimary($colors['primary'], $data['preview_mode'] ?? 'dark');
                $colors = $this->applyManualOverrides($generated, $colors, $overrides);
            }

            $theme->update([
                'name'        => $data['name'] ?? $theme->name,
                'description' => $data['description'] ?? $theme->description,
                'status'      => $data['status'] ?? $theme->status,
                'mode'        => $data['mode'] ?? $theme->mode,
                'colors'      => $colors,
                'overrides'   => $overrides,
            ]);

            return $theme->fresh();
        });
    }

    protected function buildColorsFromInput(array $data, ?Theme $existing = null): array
    {
        $defaults = $existing?->getMergedColors() ?? config('theme.defaults', []);
        $inputColors = $data['colors'] ?? [];

        foreach ($inputColors as $key => $value) {
            if ($value) {
                $defaults[$key] = ColorUtility::normalizeHex($value);
            }
        }

        return $defaults;
    }

    protected function applyManualOverrides(array $generated, array $manual, array $overrides): array
    {
        foreach ($manual as $key => $value) {
            if (! empty($overrides[$key]) && $value) {
                $generated[$key] = ColorUtility::normalizeHex($value);
            }
        }

        return $generated;
    }

    public function activate(Theme $theme): void
    {
        DB::transaction(function () use ($theme) {
            Theme::where('is_active', true)->update(['is_active' => false]);
            $theme->update(['is_active' => true, 'status' => 'published']);
        });
    }

    public function duplicate(Theme $theme): Theme
    {
        $copy = $theme->replicate();
        $copy->name = $theme->name . ' (Copy)';
        $copy->is_active = false;
        $copy->is_default = false;
        $copy->status = 'draft';
        $copy->save();

        return $copy;
    }

    public function resetToDefault(Theme $theme): Theme
    {
        $defaults = config('theme.defaults', []);
        $primary = $defaults['primary'];
        $generated = $this->generator->generateFromPrimary($primary, 'dark');

        $theme->update([
            'colors'    => $generated,
            'overrides' => [],
        ]);

        return $theme->fresh();
    }

    public function export(Theme $theme): array
    {
        return [
            'version'     => 1,
            'exported_at' => now()->toIso8601String(),
            'theme'       => [
                'name'        => $theme->name,
                'description' => $theme->description,
                'mode'        => $theme->mode,
                'colors'      => $theme->getMergedColors(),
                'overrides'   => $theme->overrides ?? [],
            ],
        ];
    }

    public function import(array $payload, ?string $name = null): Theme
    {
        $themeData = $payload['theme'] ?? $payload;

        return $this->create([
            'name'        => $name ?? ($themeData['name'] ?? 'Imported Theme') . ' ' . Str::random(4),
            'description' => $themeData['description'] ?? 'Imported theme',
            'mode'        => $themeData['mode'] ?? 'both',
            'colors'      => $themeData['colors'] ?? [],
            'overrides'   => $themeData['overrides'] ?? [],
            'status'      => 'draft',
        ]);
    }

    public function importFromFile(UploadedFile $file): Theme
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'zip') {
            return $this->importFromZip($file);
        }

        // Default: treat as JSON
        $content = json_decode($file->get(), true);

        if (! is_array($content)) {
            throw new \InvalidArgumentException('ملف JSON غير صالح أو تالف.');
        }

        return $this->import($content);
    }

    public function importFromZip(UploadedFile $file): Theme
    {
        if (! class_exists(\ZipArchive::class)) {
            throw new \RuntimeException('امتداد ZipArchive غير مثبّت على الخادم.');
        }

        $zip = new \ZipArchive();
        $result = $zip->open($file->getRealPath());

        if ($result !== true) {
            throw new \InvalidArgumentException('تعذّر فتح ملف ZIP (كود الخطأ: ' . $result . ').');
        }

        $jsonContent = null;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);

            // Skip directories and hidden/system files
            if (str_ends_with($entry, '/') || str_starts_with(basename($entry), '.')) {
                continue;
            }

            if (str_ends_with(strtolower($entry), '.json')) {
                $jsonContent = $zip->getFromIndex($i);
                break;
            }
        }

        $zip->close();

        if ($jsonContent === null) {
            throw new \InvalidArgumentException('لا يوجد ملف JSON داخل ملف ZIP.');
        }

        $data = json_decode($jsonContent, true);

        if (! is_array($data)) {
            throw new \InvalidArgumentException('ملف JSON الموجود داخل ZIP غير صالح أو تالف.');
        }

        return $this->import($data);
    }

    public function publish(Theme $theme): Theme
    {
        $theme->update(['status' => 'published']);

        return $theme;
    }

    public function saveDraft(Theme $theme, array $data): Theme
    {
        $data['status'] = 'draft';

        return $this->update($theme, $data);
    }

    public function previewCss(Theme $theme): string
    {
        $modes = $this->resolveBothModes($theme);

        return $this->cssBuilder->buildStylesheet($modes['dark'], $modes['light']);
    }
}
