<?php

namespace App\Http\Controllers;

use App\Service\SizeService;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    protected $sizeService;

    public function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }

    public function index(Request $request)
    {
        $sizes = $this->sizeService->getSizes($request->all());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'html'   => view('size.partials.table', compact('sizes'))->render(),
            ]);
        }

        return view('size.list', compact('sizes'));
    }

    public function edit(Request $request)
    {
        
        $sizeId = $request->input('si_id');

        $size = $this->sizeService->getSizeById($sizeId);

        if ($size) {
            return response()->json([
                'status' => true,
                'msg'    => 'Size data found.',
                'data'   => $size,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'No size data found.',
        ], 404);
    }

    public function store(Request $request)
    {
        $result = $this->sizeService->create($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => 'Size created successfully.',
                'data'   => $result['data'] ?? null,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => $result['message'] ?? 'Failed to create size.',
            'errors' => $result['errors'] ?? null,
        ], $result['code'] ?? 422);
    }

    public function update(Request $request)
    {
        $result = $this->sizeService->updateSize($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => 'Size updated successfully.',
                'data'   => $result['data'] ?? null,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => $result['message'] ?? 'Failed to update size.',
            'errors' => $result['errors'] ?? null,
        ], $result['code'] ?? 422);
    }
}
