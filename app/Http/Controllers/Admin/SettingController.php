<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Theme;

class SettingController extends Controller
{
    public function index()
    {
        $settings   = DB::table('settings')->pluck('value', 'key');
        $categories = Category::all();

        return view('admin.settings.index', compact('settings', 'categories'));
    }

    public function update(Request $request)
    {
        // 1. Update Category styles
        if ($request->has('category_style')) {
            foreach ($request->category_style as $catId => $style) {
                DB::table('settings')->updateOrInsert(
                    ['key' => 'category_style_' . $catId],
                    ['value' => $style]
                );
            }
        }

        // 2. Shipping Settings
        if ($request->has('shipping_type')) {
            DB::table('settings')->updateOrInsert(['key' => 'shipping_type'], ['value' => $request->shipping_type]);
            DB::table('settings')->updateOrInsert(['key' => 'shipping_fee'], ['value' => $request->shipping_fee ?? 0]);

            if ($request->shipping_type === 'region' && $request->has('shipping_regions')) {
                $regions = collect($request->shipping_regions)
                    ->filter(fn($r) => !empty($r['name']))
                    ->map(fn($r) => [
                        'name' => trim($r['name']),
                        'fee'  => (float) ($r['fee'] ?? 0),
                    ])
                    ->values()
                    ->toArray();

                DB::table('settings')->updateOrInsert(
                    ['key' => 'shipping_regions'],
                    ['value' => json_encode($regions, JSON_UNESCAPED_UNICODE)]
                );
            }
        }

        // 3. WhatsApp Numbers
        if ($request->has('new_whatsapp_number')) {
            $data = DB::table('settings')->where('key', 'whatsapp_numbers')->value('value');
            $numbers = $data ? json_decode($data, true) : [];
            $numbers[] = $request->new_whatsapp_number;
            DB::table('settings')->updateOrInsert(['key' => 'whatsapp_numbers'], ['value' => json_encode($numbers)]);
            return back()->with('success', __('messages.settings_updated') ?? 'تم إضافة رقم الواتساب!');
        }

        // 4. Logo Settings
        $logoSettings = [
            'store_name',
            'text_color',
            'font_family',
            'logo_size',
            'logo_shape',
            'text_size',
        ];

        foreach ($logoSettings as $key) {
            if ($request->has($key)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    [
                        'value' => $request->$key,
                        'updated_at' => now()
                    ]
                );
            }
        }

        // 5. Logo File Upload
        if ($request->hasFile('logo_image')) {
            $disk = config('filesystems.default', 'public');
            $path = $request->file('logo_image')->store('logos', $disk);

            DB::table('settings')->updateOrInsert(
                ['key' => 'logo_path'],
                [
                    'value' => $path,
                    'updated_at' => now()
                ]
            );
        }

        // 6. Related Products Settings (Layout, Animation, Glow Color)
        $relatedSettings = [
            'related_products_layout',
            'title_animation_style',
            'title_anim_color',
        ];

        foreach ($relatedSettings as $key) {
            if ($request->has($key)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $request->$key, 'updated_at' => now()]
                );
            }
        }

        \Illuminate\Support\Facades\Cache::forget('global_app_settings');

        return back()->with('success', __('messages.settings_updated') ?? 'تم تحديث كافة الإعدادات بنجاح!');
    }

    /**
     * Delete a WhatsApp number by its index in the JSON array.
     */
    public function deleteNumber(Request $request)
    {
        $request->validate(['number_index' => 'required|integer|min:0']);

        $data    = DB::table('settings')->where('key', 'whatsapp_numbers')->value('value');
        $numbers = $data ? json_decode($data, true) : [];

        if (isset($numbers[$request->number_index])) {
            array_splice($numbers, $request->number_index, 1);
            DB::table('settings')->updateOrInsert(
                ['key' => 'whatsapp_numbers'],
                ['value' => json_encode(array_values($numbers))]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('global_app_settings');

        return back()->with('success', __('messages.settings_updated') ?? 'تم حذف الرقم بنجاح!');
    }
}