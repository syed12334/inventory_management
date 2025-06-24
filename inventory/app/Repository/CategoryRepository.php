<?php

namespace App\Repository;
use App\Models\Categories;
use Illuminate\Validation\ValidationException;

class CategoryRepository
{
    protected $category;
    public function __construct(Categories $category)
    {
        $this->category = $category;
    }
    /* To fetch category list */
    public function index() {
        return $this->category->select(['category_id','title','status','created_at'])->where('status','!=',2)->get();
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
