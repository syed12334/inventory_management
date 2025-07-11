<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\BrandService;

class BrandController extends Controller
{
    private BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(Request $request)
    {
        $brands = $this->brandService->getBrands($request->all());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'html'   => view('brand.partials.table', compact('brands'))->render(),
            ]);
        }

        return view('brand.list', compact('brands'));
    }

    public function edit(Request $request)
    {
        $brandId = $request->input('brand_id');

        $brand = $this->brandService->getBrandById($brandId);

        if ($brand) {
            return response()->json([
                'status' => true,
                'msg'    => 'Brand data found.',
                'data'   => $brand,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'No brand data found.',
        ], 404);
    }

    public function store(Request $request)
    {
        $result = $this->brandService->create($request->all());

        if ($result) {
            return response()->json([
                'status' => true,
                'msg'    => 'Brand created successfully.',
                'data'   => $result,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'Failed to create brand.',
        ], 422);
    }

    public function update(Request $request)
    {
        $result = $this->brandService->updateBrand($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => 'Brand updated successfully.',
                'data'   => $result['data'] ?? null,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'msg'    => $result['message'] ?? 'Failed to update brand.',
            'errors' => $result['errors'] ?? null,
        ], 422);
    }
}
