<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArticleCoverHelper
{
    public static function isDirectImageUrl(?string $url): bool
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $path = strtolower(parse_url($url, PHP_URL_PATH) ?? '');

        $blockedHosts = ['gemini.google.com', 'chat.google.com', 'bard.google.com'];
        foreach ($blockedHosts as $blocked) {
            if (str_contains($host, $blocked)) {
                return false;
            }
        }

        if (preg_match('/\.(jpe?g|png|gif|webp|avif|svg)$/i', $path)) {
            return true;
        }

        $allowedHosts = ['images.unsplash.com', 'i.imgur.com', 'res.cloudinary.com', 'cdn.shopify.com'];
        foreach ($allowedHosts as $allowed) {
            if (str_contains($host, $allowed)) {
                return true;
            }
        }

        return false;
    }

    public static function resolveUrl(?string $coverImage): ?string
    {
        if (!$coverImage) {
            return null;
        }

        if (str_starts_with($coverImage, 'http')) {
            return self::isDirectImageUrl($coverImage) ? $coverImage : null;
        }

        if (str_starts_with($coverImage, '/storage/')) {
            $relative = ltrim($coverImage, '/');
            return Storage::disk('public')->exists(str_replace('storage/', '', $relative))
                ? asset($relative)
                : (file_exists(public_path($relative)) ? asset($relative) : null);
        }

        return Storage::disk('public')->exists($coverImage)
            ? asset('storage/' . $coverImage)
            : null;
    }

    public static function storeUpload(UploadedFile $file): string
    {
        $path = $file->store('articles', 'public');
        self::optimizeImage(Storage::disk('public')->path($path));

        return $path;
    }

    public static function optimizeImage(string $fullPath): void
    {
        if (!file_exists($fullPath) || !extension_loaded('gd')) {
            return;
        }

        $info = @getimagesize($fullPath);
        if (!$info) {
            return;
        }

        [$width, $height, $type] = $info;
        $maxSize = 1600;

        $needsResize = $width > $maxSize || $height > $maxSize;
        $needsCompress = filesize($fullPath) > 1_500_000;

        if (!$needsResize && !$needsCompress) {
            return;
        }

        $ratio = min($maxSize / max($width, 1), $maxSize / max($height, 1), 1);
        $newW = max(1, (int) round($width * $ratio));
        $newH = max(1, (int) round($height * $ratio));

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($fullPath),
            IMAGETYPE_PNG  => @imagecreatefrompng($fullPath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($fullPath) : false,
            IMAGETYPE_GIF  => @imagecreatefromgif($fullPath),
            default        => false,
        };

        if (!$src) {
            return;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

        match ($type) {
            IMAGETYPE_JPEG => imagejpeg($dst, $fullPath, 82),
            IMAGETYPE_PNG  => imagepng($dst, $fullPath, 7),
            IMAGETYPE_WEBP => imagewebp($dst, $fullPath, 82),
            IMAGETYPE_GIF  => imagegif($dst, $fullPath),
            default        => null,
        };

        imagedestroy($src);
        imagedestroy($dst);
    }
}
