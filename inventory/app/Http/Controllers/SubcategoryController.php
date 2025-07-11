<?php
namespace App\Http\Controllers;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Service\CategoryService;
class SubCategoryController extends Controller
{
    public $categoryService;
    public function __construct(CategoryService $categoryService) {
        $this->categoryService =  $categoryService;
    }

    
    public function index(Request $request)
    {
        $subcategories = $this->categoryService->getSubCategory($request->all());
        
        $categories = $this->categoryService->getCategoryAll();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('subcategory.partials.table', compact('subcategories'))->render(),
            ]);
        }
        return view('subcategory.list', compact('subcategories', 'categories'));
    }

    public function edit(Request $request) 
    {
        $subcategoryId = $request->input('sub_category_id');

        $subcategory = $this->categoryService->getSubCategoryById($subcategoryId);

        if ($subcategory) {
            return response()->json([
                'status' => true,
                'msg'    => 'Sub Category data found.',
                'data'   => $subcategory
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'No SUb category data found.'
        ], 404);
    }

    public function store(Request $request)
    {
        $result = $this->categoryService->createSubCategory($request->all());

        if ($result) {
            return response()->json([
                'status' => true,
                'msg'    => 'Category created successfully',
                'data'   => $result,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg'    => 'Failed to create sub category',
            ]);
        }
    }

    public function update(Request $request)
    {
        $result = $this->categoryService->updateSubCategory($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status'  => true,
                'msg' => 'Sub Category updated successfully.',
                'data'    => $result['data'] ?? null,
            ], 200);
        }
        
        return response()->json([
            'status'  => false,
            'msg' => $result['message'] ?? 'Failed to update sub category.',
            'errors'  => $result['errors'] ?? null,
        ], 422);
    }

}
