<?php

namespace App\Service;

use App\Models\Categories;

class CategoryService
{   
    protected $category;
    public function __construct(Categories $categories)
    {
        $this->category = $categories;
    }
    public function getCategory() {
        $category =  $this->category->get();
         
    }
}
