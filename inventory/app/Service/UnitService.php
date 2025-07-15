<?php

namespace App\Service;

use App\Repository\UnitRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UnitService
{
    protected $unitRepo;

    public function __construct(UnitRepository $unitRepo)
    {
        $this->unitRepo = $unitRepo;
    }

    public function getUnits(array $request)
    {
        return $this->unitRepo->getUnits($request);
    }

    public function getUnitById($id)
    {
        return $this->unitRepo->getUnitById($id);
    }

    public function create(array $request)
    {
        $validation = $this->validateUnit($request, 'create');

        if (!$validation['status']) {
            return [
                'status' => false,
                'errors' => $validation['errors'],
                'code'   => 422,
            ];
        }

        $data = $this->prepareUnitData($request, 'create');

        $unit = $this->unitRepo->store($data);

        if ($unit) {
            return [
                'status' => true,
                'data'   => $unit,
            ];
        }

        return [
            'status'  => false,
            'message' => 'Failed to create unit.',
            'code'    => 500,
        ];
    }

    public function updateUnit(array $request)
    {
        $validation = $this->validateUnit($request, 'update');

        if (!$validation['status']) {
            return [
                'status' => false,
                'errors' => $validation['errors'],
                'code'   => 422,
            ];
        }

        $unitId = $request['unit_id'] ?? null;

        $existing = $this->unitRepo->getUnitById($unitId);

        if (!$existing) {
            return [
                'status'  => false,
                'message' => 'Unit not found.',
                'code'    => 404,
            ];
        }

        $data = $this->prepareUnitData($request, 'update', $existing);

        $updated = $this->unitRepo->update($unitId, $data);

        if ($updated) {
            return [
                'status' => true,
                'data'   => $updated,
            ];
        }

        return [
            'status'  => false,
            'message' => 'Failed to update unit.',
            'code'    => 500,
        ];
    }

    protected function validateUnit(array $data, string $type)
    {
        $unitId = $data['unit_id'] ?? null;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name'),
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'code'),
            ],
            'conversion_rate' => ['required', 'numeric', 'min:0'],
            'is_base' => ['boolean'],
        ];

        if ($type === 'update' && $unitId) {
            $rules['name'] = array_merge(
                ['required', 'string', 'max:255'],
                [Rule::unique('units', 'name')->ignore($unitId, 'id')]
            );

            $rules['code'] = array_merge(
                ['required', 'string', 'max:50'],
                [Rule::unique('units', 'code')->ignore($unitId, 'id')]
            );
        }

        $validator = Validator::make($data, $rules, [
            'name.required' => 'Unit name is required.',
            'code.required' => 'Unit code is required.',
            'name.unique'   => 'This unit name already exists.',
            'code.unique'   => 'This unit code already exists.',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        return ['status' => true];
    }

    protected function prepareUnitData(array $data, string $type, $existing = null)
    {
        return [
            'name'            => $data['name'],
            'code'            => $data['code'],
            'conversion_rate' => $data['conversion_rate'] ?? 1,
            'is_base'         => $data['is_base'] ?? false,
            'updated_by'      => Auth::id(),
        ];
    }
}
