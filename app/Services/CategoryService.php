<?php

namespace App\Services;

use App\DTOs\CategoryDTO;
use App\Models\Category;

class CategoryService
{
    public function create(CategoryDTO $dto)
    {
        return Category::create($dto->toArray());
    }
    
    public function delete(Category $category)
    {
        if ($category->books()->count() > 0) {
            throw new \Exception('Cannot delete category with associated books');
        }
        return $category->delete();
    }
    
    public function getAllWithBookCount()
    {
        return Category::withCount('books')->get();
    }
}