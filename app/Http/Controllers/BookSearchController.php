<?php

namespace App\Http\Controllers;

use App\DTOs\BookSearchDTO;
use Illuminate\Http\Request;

class BookSearchController extends Controller
{
    public function search(Request $request)
    {
        $searchDTO = new BookSearchDTO($request);
        
        return view('books.search', [
            'results' => $searchDTO->results,
            'total_count' => $searchDTO->total_count,
            'search_term' => $searchDTO->search_term
        ]);
    }
}