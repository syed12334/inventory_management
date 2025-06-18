<?php

namespace App\Repository;

use App\Models\Categories;
use Illuminate\Validation\ValidationException;

class CategoryRepository
{
    /**
     * Create a new class instance.
     */
    public $category;
    public function __construct(Categories $category)
    {
        $this->category = $category;
    }
    /* To fetch category list */
    public function index() {
        $category = $this->category->where('status','!=',2)->get();
        return view('Category.index',compact('category')); 
    }
    /* To add category*/
    public function create() {

    }
    /* To store category */
    public function store() {

    }
     /* To update category */
    public function update() {

    }
     /* To status change category */
    public function statusChange() {

    }
}
