<?php

namespace App\DTOs;

use App\Models\Reservation;
use Carbon\Carbon;

class BookReturnDTO
{
    public int $reservation_id;
    public int $penalty;
    public bool $is_overdue;
    public int $days_overdue;
    public string $actual_return_date;
    
    public function __construct(Reservation $reservation, ?string $actual_return_date = null)
    {
        $this->reservation_id = $reservation->id;
        $this->actual_return_date = $actual_return_date ?? Carbon::today()->toDateString();
        
        $this->calculateReturnDetails($reservation);
    }
    
    private function calculateReturnDetails(Reservation $reservation): void
    {
        $returnDate = Carbon::parse($this->actual_return_date);
        $dueDate = Carbon::parse($reservation->return_date);
        
        $this->days_overdue = $returnDate->gt($dueDate) 
            ? $returnDate->diffInDays($dueDate) 
            : 0;
        
        $this->is_overdue = $this->days_overdue > 0;
        $this->penalty = $this->days_overdue * 10;
    }
    
    public function toArray(): array
    {
        return [
            'actual_return_date' => $this->actual_return_date,
            'penalty' => $this->penalty,
            'penalty_paid' => false,
            'status' => $this->is_overdue ? 'overdue' : 'returned'
        ];
    }
}