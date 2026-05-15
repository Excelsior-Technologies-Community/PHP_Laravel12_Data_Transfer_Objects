<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->get();

        $labels = $categories->pluck('name');
        
        $data = $categories->pluck('books_count');

        $stats = [
            'total_books' => Book::count(),
            'total_reservations' => Reservation::count(),
            'total_categories' => Category::count(),
        ];

        return view('dashboard', compact('labels', 'data', 'stats'));
    }
}