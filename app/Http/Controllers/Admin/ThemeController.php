<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Theme;
use App\Services\Theme\ColorGeneratorService;

class ThemeController extends Controller
{
    /**
     * Display a listing of the themes.
     */
    public function index()
    {
        $themes = Theme::orderBy('is_active', 'desc')->latest()->get();
        return view('admin.themes.index', compact('themes'));
    }

    /**
     * Show the form for creating a new theme.
     */
    public function create()
    {
        $theme = new Theme();
        $theme->colors = config('theme.defaults', []);
        return view('admin.themes.builder', compact('theme'));
    }

    /**
     * Store a newly created theme in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'colors' => 'required|array',
        ]);

        $isDraft = $request->has('save_draft');

        if (! $isDraft) {
            Theme::where('is_active', true)->update(['is_active' => false]);
        }

        $theme = Theme::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $isDraft ? 'draft' : 'published',
            'colors' => $request->colors,
            'is_active' => ! $isDraft,
        ]);

        return redirect()->route('admin.themes.index')->with('success', $isDraft ? 'تم حفظ القالب كمسودة.' : 'تم إنشاء القالب وتفعيله بنجاح.');
    }

    /**
     * Show the form for editing the specified theme.
     */
    public function show(Theme $theme)
    {
        return view('admin.themes.builder', compact('theme'));
    }

    /**
     * Update the specified theme in storage.
     */
    public function update(Request $request, Theme $theme)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'colors' => 'required|array',
        ]);

        $isDraft = $request->has('save_draft');

        if (! $isDraft) {
            Theme::where('is_active', true)
                ->where('id', '!=', $theme->id)
                ->update(['is_active' => false]);
        }

        $theme->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $isDraft ? 'draft' : 'published',
            'colors' => $request->colors,
            'is_active' => $isDraft ? $theme->is_active : true,
        ]);

        return redirect()->back()->with('success', $isDraft ? 'تم تحديث القالب كمسودة.' : 'تم تحديث القالب وتفعيله على الموقع بنجاح.');
    }

    /**
     * Remove the specified theme from storage.
     */
    public function destroy(Theme $theme)
    {
        if ($theme->is_active) {
            return redirect()->back()->with('error', 'لا يمكن حذف القالب النشط.');
        }

        $theme->delete();
        return redirect()->back()->with('success', 'تم حذف القالب بنجاح.');
    }

    /**
     * Activate the specified theme.
     */
    public function activate(Theme $theme)
    {
        Theme::where('is_active', true)->update(['is_active' => false]);
        $theme->update(['is_active' => true]);

        return redirect()->back()->with('success', 'تم تفعيل القالب بنجاح.');
    }

    /**
     * Duplicate the specified theme.
     */
    public function duplicate(Theme $theme)
    {
        $newTheme = $theme->replicate();
        $newTheme->name = $newTheme->name . ' (نسخة)';
        $newTheme->is_active = false;
        $newTheme->is_default = false;
        $newTheme->save();

        return redirect()->route('admin.themes.index')->with('success', 'تم تكرار القالب بنجاح.');
    }

    /**
     * Export the specified theme as JSON.
     */
    public function export(Theme $theme)
    {
        $data = [
            'name' => $theme->name,
            'description' => $theme->description,
            'colors' => $theme->colors,
            'overrides' => $theme->overrides,
            'mode' => $theme->mode,
        ];

        $fileName = 'theme-' . \Illuminate\Support\Str::slug($theme->name) . '-' . date('Y-m-d') . '.json';

        return response()->json($data)->setEncodingOptions(JSON_UNESCAPED_UNICODE)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Import a theme from a JSON file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'theme_file' => 'required|file|mimes:json'
        ]);

        $file = $request->file('theme_file');
        $json = file_get_contents($file->getRealPath());
        $data = json_decode($json, true);

        if (!$data || !isset($data['colors'])) {
            return redirect()->back()->with('error', 'ملف JSON غير صالح.');
        }

        Theme::create([
            'name' => ($data['name'] ?? 'Imported Theme') . ' (مستورد)',
            'description' => $data['description'] ?? null,
            'colors' => $data['colors'],
            'overrides' => $data['overrides'] ?? [],
            'mode' => $data['mode'] ?? 'both',
            'status' => 'draft',
            'is_active' => false,
            'is_default' => false,
        ]);

        return redirect()->route('admin.themes.index')->with('success', 'تم استيراد القالب بنجاح.');
    }

    /**
     * Auto-generate a color palette based on a primary color.
     */
    public function generateColors(Request $request, ColorGeneratorService $generator)
    {
        $request->validate([
            'primary' => 'required|string'
        ]);

        $mode = $request->input('mode', 'dark'); // default dark as per original ColorGeneratorService signature
        $colors = $generator->generateFromPrimary($request->primary, $mode);

        return response()->json($colors);
    }

    /**
     * Auto-generate a full color palette derived from a background color.
     * When the admin changes the background, the entire palette is recalculated
     * to look great against that background — primary is auto-picked as a
     * complementary hue with guaranteed contrast.
     */
    public function generateFromBackground(Request $request, ColorGeneratorService $generator)
    {
        $request->validate([
            'background' => 'required|string|regex:/^#[0-9A-Fa-f]{3,6}$/'
        ]);

        $colors = $generator->generateFromBackground($request->background);

        return response()->json($colors);
    }
}
