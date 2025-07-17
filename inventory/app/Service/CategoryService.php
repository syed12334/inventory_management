<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class CategoryService
{   
    protected $categoryrepo;

    public function __construct(CategoryRepository $categories)
    {
        $this->categoryrepo = $categories;
    }
    
    /**
     * Fetch all Sub categories
    */
    public function getSubCategory($request) 
    {
        return $this->categoryrepo->getSubCategory($request);
    }

    /**
     * Fetch all categories
    */
    public function getCategory($request)
    {
        return $this->categoryrepo->getCategory($request);
    }

    public function getCategoryAll()
    {
        return $this->categoryrepo->getCategoryAll();
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

    public function createSubCategory(array $request)
    {
        $validation = $this->validateSubCategory($request, 'create');
         
        if (!$validation['status']) {
            return response()->json([
                'status' => false,
                'code'   => 422,
                'errors' => $validation['errors'],
            ], 422);
        }

        $data = $this->prepareBuildDataSubCategory($request, 'create');

        $subcategory = $this->categoryrepo->storesubcategory($data);
        
        if ($subcategory) {
            return response()->json([
                'status' => true,
                'msg'    => 'Sub Category created successfully.',
                'data'   => $subcategory,
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'Unable to create sub category.',
            'data'   => null,
        ], 500);

    }

    public function getSubCategoryById($id) {
        return $this->categoryrepo->getSubCategoryById($id);
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
     * Validate category input
     */
    public function validateSubCategory(array $data, string $type)
    {
        $subCategoryId = $data['sub_category_id'] ?? null;

        $rules = [
            'name' => [
                'required',
                'regex:/^[a-zA-Z0-9\s]+$/',
                Rule::unique('subcategories', 'subcategory_name'),
            ],
            'category_id' => [
                'required',
                'exists:categories,category_id',
            ],
        ];

        if ($type === 'update' && $subCategoryId) {
            $rules['name'][2] = Rule::unique('subcategories', 'subcategory_name')
                ->ignore($subCategoryId, 'subcategory_id');
        }

        $messages = [
            'name.required'        => 'Sub-category name is required.',
            'name.regex'           => 'Sub-category name may only contain letters, numbers, and spaces.',
            'name.unique'          => 'This sub-category name already exists.',
            'category_id.required' => 'Parent category is required.',
            'category_id.exists'   => 'The selected parent category does not exist.',
        ];

        $validator = Validator::make($data, $rules, $messages);

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
            'updated_by' => Auth::user()->id ?? null,
        ];
    }
   
    /**
     * Prepare data for create or update
    */
    protected function prepareBuildDataSubCategory(array $request, string $type, $existing = null)
    {
        $slug = Str::slug($request['name']);

        if ($type === 'update' && $existing && $existing->name === $request['name']) {
            $slug = $existing->slug;
        }

        return [
            'category_id' => $request['category_id'],
            'subcategory_name' => $request['name'],
            'slug' => $slug,
            'status' => $request['status'] ?? 1,
            'updated_by' => Auth::user()->id ?? null,
        ];
    }

    public function updateSubCategory(array $request)
    {
        try {
            $subcategoryId = $request['subcategory_id'];
            $subcategory   = $this->categoryrepo->getSubCategoryById($subcategoryId);

            if (!$subcategory) {
                return [
                    'status' => false,
                    'msg'    => 'Sub-category not found.',
                    'code'   => 404,
                ];
            }

            $validation = $this->validateSubCategory($request, 'update');

            if (!$validation['status']) {
                return [
                    'status' => false,
                    'msg'    => 'Validation failed.',
                    'errors' => $validation['errors'],
                    'code'   => 422,
                ];
            }

            $updatedData = $this->prepareBuildDataSubCategory($request, 'update', $subcategory);

            $updated     = $this->categoryrepo->updateSubCategory($subcategoryId, $updatedData);

            return [
                'status' => true,
                'data'   => $updated,
                'msg'    => 'Sub-category updated successfully.',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'msg'    => 'An unexpected error occurred: ' . $e->getMessage(),
                'code'   => 500,
            ];
        }
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
