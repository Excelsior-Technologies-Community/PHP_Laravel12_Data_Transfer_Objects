<?php

namespace App\DTOs;

use App\Models\Book;
use Carbon\Carbon;

class BookReservationDTO
{
    public int $book_id;
    public string $student_name;
    public string $issue_date;

    public string $return_date;
    public int $penalty;
    public bool $can_issue;

    public function __construct($request)
    {
        $this->book_id = $request->book_id;
        $this->student_name = $request->student_name;
        $this->issue_date = $request->issue_date;

        $this->applyBusinessRules();
    }

    private function applyBusinessRules(): void
    {
        $this->calculateReturnDate();
        $this->calculatePenalty();
        $this->checkAvailability();
    }

    private function calculateReturnDate(): void
    {
        $this->return_date = Carbon::parse($this->issue_date)
            ->addDays(7)
            ->toDateString();
    }

    private function calculatePenalty(): void
    {
        $today = Carbon::today();
        $returnDate = Carbon::parse($this->return_date);

        $this->penalty = $today->greaterThan($returnDate)
            ? $today->diffInDays($returnDate) * 10
            : 0;
    }

    private function checkAvailability(): void
    {
        $book = Book::find($this->book_id);
        $this->can_issue = $book && $book->quantity > 0;
    }

    public function toArray(): array
    {
        return [
            'book_id' => $this->book_id,
            'student_name' => $this->student_name,
            'issue_date' => $this->issue_date,
            'return_date' => $this->return_date,
            'penalty' => $this->penalty,
        ];
    }
}
