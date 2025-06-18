<?php

namespace App\Repository;
use App\Service\CategoryService;
use Illuminate\Validation\ValidationException;
class CategoryRepository
{
    protected $category;
    public function __construct(CategoryService $category)
    {
        $this->category = $category;
    }
    /* To fetch category list */
    public function index() {
        $category = $this->category->where('status','!=',2)->get();
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
