<?php

namespace App\Services;

use App\Repositories\SizeRepositories;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SizeService
{
    protected SizeRepository $sizeRepo;

    public function __construct(SizeRepository $sizeRepo)
    {
        $this->sizeRepo = $sizeRepo;
    }

    public function getSizes(array $filters = [])
    {
        return $this->sizeRepo->getSizes($filters);
    }

    public function getSizeById(int $id)
    {
        return $this->sizeRepo->getSizeById($id);
    }

    public function create(array $payload)
    {
        $validation = $this->validateSize($payload, 'create');
        if (!$validation['status']) {
            return ['status' => false, 'errors' => $validation['errors'], 'code' => 422];
        }

        $data  = $this->prepareSizeData($payload, 'create');
        $size  = $this->sizeRepo->store($data);

        return $size
            ? ['status' => true,  'data' => $size]
            : ['status' => false, 'message' => 'Failed to create size.', 'code' => 500];
    }

    public function updateSize(array $payload)
    {
    
        $validation = $this->validateSize($payload, 'update');
        if (!$validation['status']) {
            return ['status' => false, 'errors' => $validation['errors'], 'code' => 422];
        }

        $sizeId  = $payload['si_id'] ?? null;
        $existing = $this->sizeRepo->getSizeById($sizeId);

        if (!$existing) {
            return ['status' => false, 'message' => 'Size not found.', 'code' => 404];
        }

        $data    = $this->prepareSizeData($payload, 'update', $existing);
        $updated = $this->sizeRepo->update($sizeId, $data);

        return $updated
            ? ['status' => true,  'data' => $updated]
            : ['status' => false, 'message' => 'Failed to update size.', 'code' => 500];
    }

    protected function validateSize(array $data, string $mode): array
    {
        $sizeId = $data['si_id'] ?? null;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('sizes', 'sname')->ignore($sizeId, 's_id'),
            ],
            'status' => ['nullable', 'integer'],
        ];

        $validator = Validator::make($data, $rules, [
            'name.required' => 'Size name is required.',
            'name.unique'   => 'This size already exists.',
        ]);

        return $validator->fails()
            ? ['status' => false, 'errors' => $validator->errors()]
            : ['status' => true];
    }

    protected function prepareSizeData(array $data, string $mode, $existing = null): array
    {
        return [
            'sname'       => $data['name'],
            'status'     => $data['status'] ?? ($existing->status ?? 1),
            'updated_by' => Auth::id() ?? null,
        ];
    }
}
