<?php

namespace App\Services;

use App\DTOs\BookReservationDTO;
use App\Models\Book;
use App\Models\Reservation;

class BookReservationService
{
    public function reserve(BookReservationDTO $dto)
    {
        if ($dto->is_limit_reached) {
            throw new \Exception('Limit reached! A student can only reserve up to 3 books.');
        }

        if (!$dto->can_issue) {
            throw new \Exception('Book not available');
        }

        Reservation::create($dto->toArray());

        Book::where('id', $dto->book_id)->decrement('quantity');
    }
}