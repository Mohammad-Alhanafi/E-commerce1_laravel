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

        $defaults = config('theme.defaults', []);
        $colors   = is_array($themeData['colors'] ?? null) ? $themeData['colors'] : [];
        $mergedColors = array_merge($defaults, $colors);

        $importedName = $name ?? ($themeData['name'] ?? 'قالب مستورد') . ' (مستورد)';

        return $this->create([
            'name'        => $importedName,
            'description' => $themeData['description'] ?? 'قالب تم استيراده من ملف',
            'mode'        => $themeData['mode'] ?? 'both',
            'colors'      => $mergedColors,
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
            // Fallback: create theme named after file if JSON parsing fails
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            return $this->create([
                'name'        => ucwords(str_replace(['_', '-'], ' ', $filename)) . ' (مستورد)',
                'description' => 'قالب مستورد من ملف النص',
                'mode'        => 'both',
                'colors'      => config('theme.defaults', []),
                'status'      => 'draft',
            ]);
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

        $jsonContent  = null;
        $cssContents  = '';

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);

            // Skip directories and hidden/system files
            if (str_ends_with($entry, '/') || str_starts_with(basename($entry), '.')) {
                continue;
            }

            $lower = strtolower($entry);
            if (str_ends_with($lower, '.json') && $jsonContent === null) {
                $jsonContent = $zip->getFromIndex($i);
            } elseif (str_ends_with($lower, '.css') || str_ends_with($lower, '.txt')) {
                $cssContents .= ' ' . $zip->getFromIndex($i);
            }
        }

        $zip->close();

        // 1. If JSON file is present in ZIP, use it
        if ($jsonContent !== null) {
            $data = json_decode($jsonContent, true);
            if (is_array($data)) {
                return $this->import($data);
            }
        }

        // 2. If NO JSON file is present (ZIP without JSON), build theme from ZIP metadata & CSS
        $zipBaseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanName   = ucwords(str_replace(['_', '-'], ' ', $zipBaseName));

        $extractedColors = [];
        if (! empty($cssContents)) {
            preg_match_all('/#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})\b/', $cssContents, $matches);
            if (! empty($matches[0])) {
                $uniqueColors = array_values(array_unique($matches[0]));
                $defaults     = config('theme.defaults', []);
                $colorKeys    = array_keys($defaults);

                foreach ($uniqueColors as $index => $hex) {
                    if (isset($colorKeys[$index])) {
                        $extractedColors[$colorKeys[$index]] = $hex;
                    }
                }
            }
        }

        $finalColors = array_merge(config('theme.defaults', []), $extractedColors);

        return $this->create([
            'name'        => $cleanName . ' (مستورد من ZIP)',
            'description' => 'قالب تم إنشاؤه واستيراده من أرشيف ZIP (' . $file->getClientOriginalName() . ')',
            'mode'        => 'both',
            'colors'      => $finalColors,
            'status'      => 'draft',
        ]);
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
