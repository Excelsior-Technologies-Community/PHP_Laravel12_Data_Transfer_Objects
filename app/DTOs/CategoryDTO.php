<?php

namespace App\DTOs;

class CategoryDTO
{
    public string $name;
    public string $slug;
    public ?string $description;
    public int $book_count;
    
    public function __construct($request)
    {
        $this->name = $request->name;
        $this->slug = \Illuminate\Support\Str::slug($request->name);
        $this->description = $request->description;
        $this->book_count = 0;
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
        ];
    }
}