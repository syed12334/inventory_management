<?php

namespace App\Service;

use App\Repositories\ColorRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ColorService
{
    protected ColorRepository $colorRepo;

    public function __construct(ColorRepository $colorRepo)
    {
        $this->colorRepo = $colorRepo;
    }

    public function getColors(array $filters = [])
    {
        return $this->colorRepo->getColors($filters);
    }

    public function getColorById(int $id)
    {
        return $this->colorRepo->getColorById($id);
    }

    public function create(array $payload)
    {
        $validation = $this->validateColor($payload, 'create');
        if (! $validation['status']) {
            return ['status' => false, 'errors' => $validation['errors'], 'code' => 422];
        }

        $data  = $this->prepareColorData($payload, 'create');
        $color = $this->colorRepo->store($data);

        return $color
            ? ['status' => true,  'data' => $color]
            : ['status' => false, 'message' => 'Failed to create color.', 'code' => 500];
    }

    public function updateColor(array $payload)
    {
        $validation = $this->validateColor($payload, 'update');
        if (! $validation['status']) {
            return ['status' => false, 'errors' => $validation['errors'], 'code' => 422];
        }

        $colorId  = $payload['co_id'] ?? null;
        $existing = $this->colorRepo->getColorById($colorId);

        if (! $existing) {
            return ['status' => false, 'message' => 'Color not found.', 'code' => 404];
        }

        $data    = $this->prepareColorData($payload, 'update', $existing);
        $updated = $this->colorRepo->update($colorId, $data);

        return $updated
            ? ['status' => true,  'data' => $updated]
            : ['status' => false, 'message' => 'Failed to update color.', 'code' => 500];
    }

    protected function validateColor(array $data, string $mode): array
    {
        $colorId = $data['co_id'] ?? null;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('colors', 'name')->ignore($colorId, 'co_id'),
            ],
            'ccode' => [
                'required',
                'string',
                'max:10',
                'regex:/^#?[0-9A-F]{3,8}$/i',
                Rule::unique('colors', 'ccode')->ignore($colorId, 'co_id'),
            ],
            'status' => ['nullable', 'integer'],
        ];

        $validator = Validator::make($data, $rules, [
            'name.required'   => 'Color name is required.',
            'name.unique'     => 'This color name already exists.',
            'ccode.required'  => 'Color code is required.',
            'ccode.unique'    => 'This color code already exists.',
            'ccode.regex'     => 'Color code must be a valid hex value.',
        ]);

        return $validator->fails()
            ? ['status' => false, 'errors' => $validator->errors()]
            : ['status' => true];
    }

    protected function prepareColorData(array $data, string $mode, $existing = null): array
    {
        return [
            'name'       => $data['name'],
            'ccode'      => strtoupper($data['ccode']),
            'status'     => $data['status'] ?? ($existing->status ?? 1),
            'updated_by' => Auth::id() ?? null,
        ];
    }
}
