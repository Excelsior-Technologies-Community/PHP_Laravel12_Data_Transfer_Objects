<?php

namespace App\DTOs;

use App\Models\Book;
use App\Models\Reservation;

class DashboardStatsDTO
{
    public int $total_books;
    public int $available_books;
    public int $issued_books;
    public int $total_reservations;
    public int $active_reservations;
    public int $overdue_reservations;
    public int $total_penalty_collected;
    public array $recent_reservations;
    public array $popular_books;
    
    public function __construct()
    {
        $this->calculateStats();
    }
    
    private function calculateStats(): void
    {
        $this->total_books = Book::sum('quantity');
        $this->available_books = Book::sum('quantity');
        $this->issued_books = Reservation::where('status', 'issued')->count();
        $this->total_reservations = Reservation::count();
        $this->active_reservations = Reservation::where('status', 'issued')->count();
        $this->overdue_reservations = Reservation::where('status', 'issued')
            ->where('return_date', '<', now())
            ->count();
        $this->total_penalty_collected = Reservation::where('penalty_paid', true)->sum('penalty');
        
        $this->recent_reservations = Reservation::with('book')
            ->latest()
            ->take(5)
            ->get()
            ->toArray();
            
        $this->popular_books = Reservation::select('book_id')
            ->with('book')
            ->selectRaw('count(*) as total')
            ->groupBy('book_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get()
            ->toArray();
    }
}