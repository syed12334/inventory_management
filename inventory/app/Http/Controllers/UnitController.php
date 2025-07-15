<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\UnitService;

class UnitController extends Controller
{
    private UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    public function index(Request $request)
    {
        $units = $this->unitService->getUnits($request->all());

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'html'   => view('unit.partials.table', compact('units'))->render(),
            ]);
        }

        return view('unit.list', compact('units'));
    }

    public function edit(Request $request)
    {
        $unitId = $request->input('unit_id');

        $unit = $this->unitService->getUnitById($unitId);

        if ($unit) {
            return response()->json([
                'status' => true,
                'msg'    => 'Unit data found.',
                'data'   => $unit,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'No unit data found.',
        ], 404);
    }

    public function store(Request $request)
    {
        $result = $this->unitService->create($request->all());

        if ($result) {
            return response()->json([
                'status' => true,
                'msg'    => 'Unit created successfully.',
                'data'   => $result,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'Failed to create unit.',
        ], 422);
    }

    public function update(Request $request)
    {
        $result = $this->unitService->updateUnit($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status' => true,
                'msg'    => 'Unit updated successfully.',
                'data'   => $result['data'] ?? null,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'msg'    => $result['message'] ?? 'Failed to update unit.',
            'errors' => $result['errors'] ?? null,
        ], 422);
    }
}
