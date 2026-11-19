<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    public function storeResized(UploadedFile $file, string $disk, string $directory, int $width, int $height): string
    {
        $manager = ImageManager::usingDriver(Driver::class);
        $path = trim($directory, '/').'/'.now()->timestamp.'-'.uniqid().'.jpg';

        $encoded = $manager->decode($file)->cover($width, $height)->encodeUsingFileExtension('jpg', quality: 85);
        Storage::disk($disk)->put($path, (string) $encoded);

        return $path;
    }
}
