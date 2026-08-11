<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('get_image_url')) {
    /**
     * Resolve any stored image path to a publicly accessible URL.
     *
     * Handles all cases:
     *  - Already a full URL  (http / https)  → return as-is
     *  - assets/ prefix                       → asset() helper
     *  - disk = s3                            → S3 URL
     *  - disk = public or local               → Storage::disk('public')->url()
     *  - Legacy paths in public/categories,
     *    public/uploads, public/logos         → asset() helper
     *
     * @param string|null $path
     * @param string|null $default  Pass null to get null when empty instead of a placeholder image
     * @return string|null
     */
    function get_image_url(?string $path, ?string $default = 'assets/images/default.png'): ?string
    {
        // ── 1. Empty path ──────────────────────────────────────────────────────
        if (empty($path)) {
            return $default ? asset($default) : null;
        }

        // ── 2. Already a full URL ──────────────────────────────────────────────
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // ── 3. Public asset (e.g. assets/images/logo.png) ────────────────────
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        $disk      = config('filesystems.default', 'public');
        $cleanPath = ltrim($path, '/');

        // ── 4. S3 / cloud storage ─────────────────────────────────────────────
        if ($disk === 's3') {
            try {
                return Storage::disk('s3')->url($cleanPath);
            } catch (\Throwable $e) {
                // fall through to public
            }
        }

        // ── 5. Path already has 'storage/' prefix (old absolute-ish paths) ───
        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        // ── 6. Legacy direct-public paths (categories/, uploads/, logos/) ─────
        //      These were saved by old code that used move(public_path(...))
        $legacyPrefixes = ['categories/', 'uploads/', 'logos/', 'images/'];
        foreach ($legacyPrefixes as $prefix) {
            if (str_starts_with($cleanPath, $prefix) && file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }
        }

        // ── 7. Standard Laravel public disk  ─────────────────────────────────
        //      Works for disk = 'public' and disk = 'local' (fallback)
        //      Requires `php artisan storage:link` to be run (done in entrypoint.sh)
        try {
            return Storage::disk('public')->url($cleanPath);
        } catch (\Throwable $e) {
            return asset('storage/' . $cleanPath);
        }
    }
}
