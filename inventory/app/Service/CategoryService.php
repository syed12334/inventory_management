<?php

namespace App\Service;
use App\Repository\CategoryRepository;
class CategoryService
{   
    protected $category;
    public function __construct(CategoryRepository $categories)
    {
        $this->category = $categories;
    }
    /* Fetch all categories */
    public function getCategory() {
       return $this->category->index();
    }
}
