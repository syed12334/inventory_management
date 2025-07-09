<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryService
{   
    protected $categoryrepo;

    public function __construct(CategoryRepository $categories)
    {
        $this->categoryrepo = $categories;
    }

    /**
     * Fetch all categories
     */
    public function getCategory($request)
    {
        return $this->categoryrepo->getCategory($request);
    }

    public function getCategoryById($id)
    {
        return $this->categoryrepo->getCategoryById($id);
    }

    /**
     * Create a new category
     */
    public function create(array $request)
    {
        $validation = $this->validateCategory($request, 'create');

        if (!$validation['status']) {
            return response()->json([
                'status' => false,
                'code'   => 422,
                'errors' => $validation['errors'],
            ], 422);
        }

        $data = $this->prepareBuildData($request, 'create');

        $category = $this->categoryrepo->store($data);
        
        if ($category) {
            return response()->json([
                'status' => true,
                'msg'    => 'Category created successfully.',
                'data'   => $category,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'Unable to create category.',
            'data'   => null,
        ], 500);
    }

    /**
     * Validate category input
     */
    public function validateCategory(array $request, string $type)
    {
        $categoryId = $request['category_id'] ?? null;

        $rules = [
            'name' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
        ];

        if ($type === 'update' && $categoryId) {
            $rules['name'][] = Rule::unique('categories', 'title')->ignore($categoryId, 'category_id');

        } else {
            $rules['name'][] = Rule::unique('categories', 'title');
        }

        $messages = [
            'name.required' => 'Category name is required.',
            'name.regex'    => 'Category name may only contain letters, numbers, and spaces.',
            'name.unique'   => 'This category name already exists.',
        ];

        $validator = Validator::make($request, $rules, $messages);

        if ($validator->fails()) {
            return [
                'status' => false,
                'errors' => $validator->errors(),
            ];
        }

        return ['status' => true];
    }

    
     /**
     * Prepare data for create or update
     */
    protected function prepareBuildData(array $request, string $type, $existing = null)
    {
        $slug = Str::slug($request['name']);

        if ($type === 'update' && $existing && $existing->name === $request['name']) {
            $slug = $existing->slug;
        }

        return [
            'title' => $request['name'],
            'slug' => $slug,
            'status' => $request['status'] ?? 1,
        ];
    }

    public function updateCategory(array $request)
    {
        try {
            $categoryId = $request['category_id'];
            $category = $this->categoryrepo->getCategoryById($categoryId);

            if (!$category) {
                return [
                    'status' => false,
                    'msg'    => 'Category not found.',
                    'code'   => 404,
                ];
            }

            $validation = $this->validateCategory($request, 'update');

            if (!$validation['status']) {
                return [
                    'status' => false,
                    'msg'    => 'Validation failed.',
                    'errors' => $validation['errors'],
                    'code'   => 422,
                ];
            }

            $updatedData = $this->prepareBuildData($request, 'update', $category);
            $updated = $this->categoryrepo->update($categoryId, $updatedData);

            return [
                'status' => true,
                'data'   => $updated,
                'msg'    => 'Category updated successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'msg'    => 'An unexpected error occurred: ' . $e->getMessage(),
                'code'   => 500,
            ];
        }
    }



}
