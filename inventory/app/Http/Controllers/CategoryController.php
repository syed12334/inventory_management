<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Repository\CategoryRepository;

class CategoryController extends Controller
{
    public $category;
    public function __construct(CategoryRepository $categoryRepository) {
        $this->category =  $categoryRepository;
    }
    /* Fetch category list */
    public function index() {
        return $this->category->index();
    }
    /* Fake data using factory */
    public function categoryFac() {
        Categories::factory()->count(40)->create();
    }
}
