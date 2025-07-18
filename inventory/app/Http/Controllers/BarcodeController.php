<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;

class BarcodeController extends Controller
{
    // Dummy product list
    private function getDummyProducts(): array
    {
        return [
            1 => [
                'id' => 1,
                'name' => 'Demo Product A',
                'description' => 'This is a test product.',
                'sku' => 'SKU001',
                'price' => 100,
                'discount' => 10
            ],
            2 => [
                'id' => 2,
                'name' => 'Demo Product B',
                'description' => 'Another demo item.',
                'sku' => 'SKU002',
                'price' => 200,
                'discount' => 15
            ],
        ];
    }

    // Generate barcode for a given product ID
   public function generate($id)
{
    $products = $this->getDummyProducts();

    if (!array_key_exists($id, $products)) {
        abort(404, 'Product not found');
    }

    $product = $products[$id];

    $barcode = new \Milon\Barcode\DNS1D();
    $barcode->setStorPath(public_path('barcodes/'));

    // Save barcode image to public/barcodes/
    $filePath = $barcode->getBarcodePNGPath($product['sku'], 'C128', 3, 100);

    // Get just the filename from the full path
    $filename = basename($filePath);

    // Create public URL using asset()
    $barcodeUrl = asset('barcodes/' . $filename);


    return view('barcode.generate', [
        'product' => $product,
        'barcodePath' => $barcodeUrl,
    ]);
}


    // Show the scanner page
    public function scanPage()
    {
        return view('barcode.scan');
    }

    // Handle the scanned barcode
    public function processScan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = $request->input('barcode');
        $products = $this->getDummyProducts();

        // Search for product by SKU (barcode)
        $product = collect($products)->firstWhere('sku', $barcode);

        if (!$product) {
            return back()->with('error', 'Product not found!');
        }

        return back()->with('success', 'Product found: ' . $product['name']);
    }
}
