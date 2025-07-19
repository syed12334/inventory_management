<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Support\Facades\Log;

class BrandRepository
{
    protected $brandModel;

    public function __construct(Brand $brand)
    {
        $this->brandModel = $brand;
    }

    /**
     * Get all brands with optional filters
     */
    public function getBrands(array $filters = [])
    {
        $paginate = $filters['paging'] ?? 10;

        $query = $this->brandModel->query();

        if (isset($filters['status'])) {
            if ($filters['status'] == -1) {
                $query->whereIn('status', [0, 1]);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        return $query->where('status', '!=', 2)
                     ->orderByDesc('created_at')
                     ->paginate($paginate);
    }

    /**
     * Get a brand by ID
     */
    public function getBrandById($id)
    {
        return $this->brandModel
                    ->where('brand_id', $id)
                    ->where('status', '!=', 2)
                    ->first();
    }

    /**
     * Store a new brand
     */
    public function store(array $data)
    {
        try {
            return $this->brandModel->create($data);
        } catch (\Exception $e) {
            Log::error('Brand creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }

    /**
     * Update a brand by ID
     */
    public function update(int $id, array $data)
    {
        try {
            $brand = $this->brandModel->findOrFail($id);
            $brand->update($data);
            return $brand;
        } catch (\Exception $e) {
            Log::error('Brand update failed: ' . $e->getMessage(), [
                'brand_id' => $id,
                'data'     => $data,
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]);

            return false;
        }
    }

    /**
     * Change the status of a brand
     */
    public function statusChange(int $id, int $status)
    {
        try {
            $brand = $this->brandModel->findOrFail($id);
            $brand->status = $status;
            $brand->save();
            return $brand;
        } catch (\Exception $e) {
            Log::error('Brand status change failed: ' . $e->getMessage(), [
                'brand_id' => $id,
                'status'   => $status,
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
            ]);

            return false;
        }
    }
}
