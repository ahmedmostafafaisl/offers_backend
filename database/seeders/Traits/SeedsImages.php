<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\Storage;

trait SeedsImages
{
    protected function putImage(string $relativePublicPath, string $label = 'Image', int $w = 800, int $h = 600): string
    {
        $dir = dirname($relativePublicPath);
        if ($dir !== '.') {
            Storage::disk('public')->makeDirectory($dir);
        }

        $im = imagecreatetruecolor($w, $h);

        $bg = imagecolorallocate($im, 245, 245, 245);
        imagefilledrectangle($im, 0, 0, $w, $h, $bg);

        $border = imagecolorallocate($im, 200, 200, 200);
        imagerectangle($im, 0, 0, $w - 1, $h - 1, $border);

        $text = imagecolorallocate($im, 60, 60, 60);

        $lines = [
            $label,
            $relativePublicPath,
            date('Y-m-d H:i:s'),
        ];

        $y = 30;
        foreach ($lines as $line) {
            imagestring($im, 5, 20, $y, $line, $text);
            $y += 25;
        }

        ob_start();
        imagepng($im);
        $png = ob_get_clean();

        imagedestroy($im);

        Storage::disk('public')->put($relativePublicPath, $png);

        return $relativePublicPath;
    }
}
