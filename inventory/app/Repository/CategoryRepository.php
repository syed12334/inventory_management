<?php

namespace App\Repository;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Log;

class CategoryRepository
{
    protected $categoryModel;
    protected $subcategoryModel;
    public function __construct(Category $category, SubCategory  $subcategory)
    {
        $this->categoryModel = $category;
        $this->subcategoryModel = $subcategory;
    }

    public function getCategory(array $filters = [])
    {
        $paginate = 10;

        if (!empty($filters['paging'])) {
            $paginate = (int) $filters['paging'];
        }

        $query = $this->categoryModel->query();

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

    public function getCategoryAll()
    {
        return $this->categoryModel
                    ->where('status', '!=', 2)
                    ->orderBy('title', 'asc')
                    ->get();
    }

    public function getSubCategory(array $filters = [])
    {
        $paginate = 10;

        if (!empty($filters['paging'])) {
            $paginate = (int) $filters['paging'];
        }

        $query = $this->subcategoryModel->query();

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

    public function getCategoryById($id)
    {
        return $this->categoryModel->where('category_id', $id)->first();
    }
    
    /**
     * Store a new category
     */
    public function store(array $data)
    {
        try {
            return $this->categoryModel->create($data);
        } catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }

    public function checkDuplicateName(string $name, $id)
    {
        return $this->categoryModel
            ->where('title', $name)
            ->where('category_id', '!=', $id)
            ->exists();
    }

    /**
     * Update a category by ID
     */
    public function update(int $id, array $data)
    {
        try {
            $category = $this->categoryModel->findOrFail($id);
            $category->update($data);
            return $category;
        } catch (\Exception $e) {
            Log::error('Category update failed: ' . $e->getMessage(), [
                'category_id' => $id,
                'data' => $data,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }

    /**
     * Change status of a category by ID
     */
    public function statusChange(int $id, int $status)
    {
        try {
            $category = $this->categoryModel->findOrFail($id);
            $category->status = $status;
            $category->save();
            return $category;
        } catch (\Exception $e) {
            Log::error('Category status change failed: ' . $e->getMessage(), [
                'category_id' => $id,
                'status' => $status,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }
    }
}
