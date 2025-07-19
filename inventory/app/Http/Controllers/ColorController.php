<?php

namespace App\Http\Controllers;

use App\Services\ColorService;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    public function index(Request $request)
    {
        $colors = $this->colorService->getColors($request->all());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'html'   => view('color.partials.table', compact('colors'))->render(),
            ]);
        }

        return view('color.list', compact('colors'));
    }

    public function edit(Request $request)
    {
        $colorId = $request->input('co_id');

        $color = $this->colorService->getColorById($colorId);

        if ($color) {
            return response()->json([
                'status' => true,
                'msg'    => 'Color data found.',
                'data'   => $color,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'No color data found.',
        ], 404);
    }

    public function store(Request $request)
    {
        $result = $this->colorService->create($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => 'Color created successfully.',
                'data'   => $result['data'] ?? null,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => $result['message'] ?? 'Failed to create color.',
            'errors' => $result['errors'] ?? null,
        ], $result['code'] ?? 422);
    }

    public function update(Request $request)
    {
        $result = $this->colorService->updateColor($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => 'Color updated successfully.',
                'data'   => $result['data'] ?? null,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => $result['message'] ?? 'Failed to update color.',
            'errors' => $result['errors'] ?? null,
        ], $result['code'] ?? 422);
    }
}
