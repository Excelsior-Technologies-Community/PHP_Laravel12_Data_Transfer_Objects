<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'book_id',
        'student_name',
        'issue_date',
        'return_date',
        'actual_return_date',
        'penalty',
        'penalty_paid',
        'status'
    ];
    
    protected $casts = [
        'issue_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
        'penalty_paid' => 'boolean'
    ];
    
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    
    public function isOverdue()
    {
        if ($this->actual_return_date) {
            return $this->actual_return_date->gt($this->return_date);
        }
        return now()->gt($this->return_date);
    }
    
    public function calculatePenalty()
    {
        $returnDate = $this->actual_return_date ?? now();
        if ($returnDate->gt($this->return_date)) {
            return $returnDate->diffInDays($this->return_date) * 10;
        }
        return 0;
    }
}