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
        'penalty'
    ];

    public function book()
    {
        return $this->belongsTo(\App\Models\Book::class);
    }
}
