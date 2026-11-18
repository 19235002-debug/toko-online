<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressor
{
    /**
     * Kompres dan ubah ukuran gambar agar ringan menggunakan GD, lalu simpan ke disk public.
     * Jika GD tidak tersedia atau file bukan gambar yang didukung, simpan langsung tanpa kompresi.
     */
    public static function compressAndStore(UploadedFile $file, string $folder = 'uploads', int $maxDim = 1200, int $quality = 80): string
    {
        if (!function_exists('imagecreatefromstring') || !function_exists('imagejpeg')) {
            return $file->store($folder, 'public');
        }

        $raw = @file_get_contents($file->getRealPath());
        if ($raw === false) {
            return $file->store($folder, 'public');
        }

        $src = @imagecreatefromstring($raw);
        if (!$src) {
            return $file->store($folder, 'public');
        }

        $width = imagesx($src);
        $height = imagesy($src);

        if ($width > $maxDim || $height > $maxDim) {
            $ratio = min($maxDim / $width, $maxDim / $height);
            $newW = max(1, (int) round($width * $ratio));
            $newH = max(1, (int) round($height * $ratio));
        } else {
            $newW = $width;
            $newH = $height;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

        ob_start();
        imagejpeg($dst, null, $quality);
        $compressedData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        if (!$compressedData) {
            return $file->store($folder, 'public');
        }

        $filename = trim($folder, '/') . '/' . Str::random(32) . '.jpg';
        Storage::disk('public')->put($filename, $compressedData);

        return $filename;
    }
}

