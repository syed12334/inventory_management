<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessProductImage;

class Helpers
{
    public static function uploadFile($file, $path = 'uploads', $disk = 'public')
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $filename, $disk);
    }
}
