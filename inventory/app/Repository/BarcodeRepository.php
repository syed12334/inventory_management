<?php
namespace App\Repositories;

use Milon\Barcode\DNS1D;

class BarcodeRepository
{
    protected $barcode;

    public function __construct()
    {
        $this->barcode = new DNS1D();
        $this->barcode->setStorPath(public_path('barcodes/'));
    }

    public function generateBarcode(string $text, string $type = 'C128', int $scale = 3, int $height = 100): string
    {
        $filename = $this->barcode->getBarcodePNGPath($text, $type, $scale, $height);
        return asset($filename);
    }
}
