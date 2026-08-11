<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('get_image_url')) {
    /**
     * Get accessible URL for any image path stored locally, on S3, external HTTP, or fallback default.
     *
     * @param string|null $path
     * @param string|null $default
     * @return string|null
     */
    function get_image_url(?string $path, ?string $default = 'assets/images/default.png'): ?string
    {
        if (empty($path)) {
            return $default ? asset($default) : null;
        }

        // If path is already a full URL (http:// or https://)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // If path is a public asset starting with 'assets/'
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        $defaultDisk = config('filesystems.default', 'public');

        // Clean leading slashes
        $cleanPath = ltrim($path, '/');

        // If default disk is S3 or explicitly targeting cloud storage
        if ($defaultDisk === 's3') {
            return Storage::disk('s3')->url($cleanPath);
        }

        // For local public disk:
        // Handle paths legacy-saved with 'storage/' prefix
        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        // Handle legacy category paths saved directly into public/categories or public/uploads
        if (str_starts_with($cleanPath, 'categories/') || str_starts_with($cleanPath, 'uploads/')) {
            if (file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }
        }

        // Standard Laravel Storage public URL
        return Storage::disk('public')->url($cleanPath);
    }
}
