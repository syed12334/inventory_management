<?php
namespace App\Http\Controllers;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Services\CategoryService;
class CategoryController extends Controller
{
    public $categoryService;
    public function __construct(CategoryService $categoryService) {
        $this->categoryService =  $categoryService;
    }

    
    public function index(Request $request)
    {
        $categories = $this->categoryService->getCategory($request->all());

        if ($request->ajax()) {
            return response()->json([
                'html' => view('category.partials.table', compact('categories'))->render(),
            ]);
        }
        return view('category.list', compact('categories'));
    }

    public function edit(Request $request) 
    {
        $categoryId = $request->input('category_id');

        $category = $this->categoryService->getCategoryById($categoryId);

        if ($category) {
            return response()->json([
                'status' => true,
                'msg'    => 'Category data found.',
                'data'   => $category
            ]);
        }

        return response()->json([
            'status' => false,
            'msg'    => 'No category data found.'
        ], 404);
    }

    public function store(Request $request)
    {
        $result = $this->categoryService->create($request->all());

        if ($result) {
            return response()->json([
                'status' => true,
                'msg'    => 'Category created successfully',
                'data'   => $result,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg'    => 'Failed to create category',
            ]);
        }
    }

    public function update(Request $request)
    {
        $result = $this->categoryService->updateCategory($request->all());

        if ($result['status'] === true) {
            return response()->json([
                'status'  => true,
                'msg' => 'Category updated successfully.',
                'data'    => $result['data'] ?? null,
            ], 200);
        }
        
        return response()->json([
            'status'  => false,
            'msg' => $result['message'] ?? 'Failed to update category.',
            'errors'  => $result['errors'] ?? null,
        ], 422);
    }

}
