<?php

namespace App\DTOs;

use App\Models\Book;

class BookSearchDTO
{
    public ?string $search_term;
    public ?string $category;
    public ?string $availability;
    public array $results;
    public int $total_count;
    
    public function __construct($request)
    {
        $this->search_term = $request->search;
        $this->category = $request->category;
        $this->availability = $request->availability;
        
        $this->performSearch();
    }
    
    private function performSearch(): void
    {
        $query = Book::query();
        
        if ($this->search_term) {
            $query->where('title', 'like', '%' . $this->search_term . '%');
        }
        
        if ($this->availability === 'available') {
            $query->where('quantity', '>', 0);
        } elseif ($this->availability === 'unavailable') {
            $query->where('quantity', 0);
        }
        
        $this->results = $query->get()->toArray();
        $this->total_count = count($this->results);
    }
}