<?php
namespace App\Http\Controllers;
use App\Models\Categories;
use Illuminate\Http\Request;
use App\Service\CategoryService;
class CategoryController extends Controller
{
    public $category;
    public function __construct(CategoryService $categoryService) {
        $this->category =  $categoryService;
    }
    /* Fetch category list */
    public function index() {
        $category =  $this->category->getCategory();
        return view('Category.index',compact('category')); 
    }
}
