<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeController extends Controller
{
    public function index() {
         $text = 'https://example.com'; // Text/URL to encode
        $fileName = 'qrcode_' . time() . '.png'; // Dynamic filename
        $filePath = 'public/qrcodes/' . $fileName; // Save path in storage

        // Generate and store QR code as PNG
        Storage::put($filePath, QrCode::format('png')->size(300)->generate($text));
        $publicUrl = asset('storage/qrcodes/' . $fileName);
        return view('qrcode',compact('publicUrl'));
    }
}
