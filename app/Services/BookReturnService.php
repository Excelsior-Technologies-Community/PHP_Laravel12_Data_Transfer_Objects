<?php

namespace App\Services;

use App\DTOs\BookReturnDTO;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class BookReturnService
{
    public function returnBook(Reservation $reservation, BookReturnDTO $dto)
    {
        DB::beginTransaction();
        
        try {
            // Update reservation
            $reservation->update($dto->toArray());
            
            // Increment book quantity back
            Book::where('id', $reservation->book_id)->increment('quantity');
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Book returned successfully',
                'penalty' => $dto->penalty,
                'is_overdue' => $dto->is_overdue
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to return book: ' . $e->getMessage());
        }
    }
}