<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Max width/height in pixels after resize.
     * Photo will be resized to fit within this dimension while keeping aspect ratio.
     */
    const MAX_DIMENSION = 1200;

    /**
     * JPEG/WebP quality (0-100). 80 is a good balance of size and quality.
     */
    const QUALITY = 80;

    /**
     * Optimize and store an uploaded image.
     * Converts to JPEG, resizes to max 1200px, compresses to ~80% quality.
     *
     * @param  UploadedFile $file
     * @param  string       $folder  Storage folder (e.g. 'products')
     * @return string                Relative path stored in DB (e.g. 'products/abc123.jpg')
     */
    public static function store(UploadedFile $file, string $folder = 'products'): string
    {
        // Read original image using GD based on MIME type
        $mime = $file->getMimeType();
        $sourcePath = $file->getRealPath();

        $sourceImage = match (true) {
            str_contains($mime, 'jpeg') => imagecreatefromjpeg($sourcePath),
            str_contains($mime, 'png')  => imagecreatefrompng($sourcePath),
            str_contains($mime, 'webp') => imagecreatefromwebp($sourcePath),
            str_contains($mime, 'gif')  => imagecreatefromgif($sourcePath),
            default                     => imagecreatefromjpeg($sourcePath),
        };

        if (!$sourceImage) {
            // Fallback: store original file if GD fails
            return $file->store($folder, 'public');
        }

        // Get original dimensions
        $origWidth  = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Calculate new dimensions while preserving aspect ratio
        [$newWidth, $newHeight] = self::calculateDimensions($origWidth, $origHeight);

        // Create resized true-color canvas
        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if (str_contains($mime, 'png')) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // High quality resize
        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Generate unique filename (.jpg)
        $filename = Str::random(40) . '.jpg';
        $relativePath = $folder . '/' . $filename;
        $absolutePath = Storage::disk('public')->path($relativePath);

        // Ensure directory exists
        $dir = dirname($absolutePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        // Save as JPEG with desired quality
        imagejpeg($resized, $absolutePath, self::QUALITY);

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($resized);

        return $relativePath;
    }

    /**
     * Calculate new width and height keeping aspect ratio within MAX_DIMENSION.
     */
    private static function calculateDimensions(int $origWidth, int $origHeight): array
    {
        $maxDim = self::MAX_DIMENSION;

        if ($origWidth <= $maxDim && $origHeight <= $maxDim) {
            return [$origWidth, $origHeight]; // Already small enough
        }

        if ($origWidth > $origHeight) {
            $newWidth  = $maxDim;
            $newHeight = (int) round($origHeight * $maxDim / $origWidth);
        } else {
            $newHeight = $maxDim;
            $newWidth  = (int) round($origWidth * $maxDim / $origHeight);
        }

        return [$newWidth, $newHeight];
    }
}
