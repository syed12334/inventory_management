<?php

namespace App\Service;

use App\Repository\BrandRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BrandService
{
    protected $brandRepo;

    public function __construct(BrandRepository $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    /**
     * Get all brands with filters (e.g. pagination, search)
     */
    public function getBrands(array $request)
    {
        return $this->brandRepo->getBrands($request);
    }

    /**
     * Get single brand by ID
     */
    public function getBrandById($id)
    {
        return $this->brandRepo->getBrandById($id);
    }

    /**
     * Create a new brand
     */
    public function create(array $request)
    {
        $validation = $this->validateBrand($request, 'create');

        if (!$validation['status']) {
            return [
                'status' => false,
                'errors' => $validation['errors'],
                'code'   => 422,
            ];
        }

        $data = $this->prepareBrandData($request, 'create');

        $brand = $this->brandRepo->store($data);

        if ($brand) {
            return [
                'status' => true,
                'data'   => $brand,
            ];
        }

        return [
            'status' => false,
            'message' => 'Failed to create brand.',
            'code'    => 500,
        ];
    }

    /**
     * Update existing brand
     */
    public function updateBrand(array $request)
    {
        $validation = $this->validateBrand($request, 'update');

        if (!$validation['status']) {
            return [
                'status' => false,
                'errors' => $validation['errors'],
                'code'   => 422,
            ];
        }

        $brandId = $request['brand_id'] ?? null;

        $existing = $this->brandRepo->getBrandById($brandId);

        if (!$existing) {
            return [
                'status'  => false,
                'message' => 'Brand not found.',
                'code'    => 404,
            ];
        }

        $data = $this->prepareBrandData($request, 'update', $existing);

        $updated = $this->brandRepo->update($brandId, $data);

        if ($updated) {
            return [
                'status' => true,
                'data'   => $updated,
            ];
        }

        return [
            'status'  => false,
            'message' => 'Failed to update brand.',
            'code'    => 500,
        ];
    }

    /**
     * Validation logic for brand creation/update
     */
    protected function validateBrand(array $data, string $type)
    {
        $brandId = $data['brand_id'] ?? null;

        $rules = [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'title'),
            ],
            'brand_img' => [
                'nullable',
                'string', // or file upload rule if needed
            ]
        ];

        if ($type === 'update' && $brandId) {
            $rules['title'][2] = Rule::unique('brands', 'title')->ignore($brandId, 'brand_id');
        }

        $validator = Validator::make($data, $rules, [
            'title.required' => 'Brand title is required.',
            'title.unique'   => 'This brand already exists.',
        ]);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        return ['status' => true];
    }

    /**
     * Prepare data for storing/updating brand
     */
    protected function prepareBrandData(array $data, string $type, $existing = null)
    {
        return [
            'title'     => $data['title'],
            'brand_img' => $data['brand_img'] ?? ($existing->brand_img ?? null),
            'user_id'   => Auth::id(),
            'status'    => $data['status'] ?? 0,
        ];
    }
}
