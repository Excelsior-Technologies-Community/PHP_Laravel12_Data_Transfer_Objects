<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DTOs\BookReservationDTO;
use App\Models\Book;
use App\Models\Reservation;
use App\Services\BookReservationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookApiController extends Controller
{
    public function getAvailableBooks(): JsonResponse
    {
        $books = Book::where('quantity', '>', 0)->get();
        return response()->json(['success' => true, 'data' => $books]);
    }
    
    public function reserveBook(Request $request, BookReservationService $service): JsonResponse
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_name' => 'required|string',
            'issue_date' => 'required|date'
        ]);
        
        try {
            $dto = new BookReservationDTO($request);
            $service->reserve($dto);
            
            return response()->json([
                'success' => true,
                'message' => 'Book reserved successfully',
                'data' => $dto->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    public function getReservations(): JsonResponse
    {
        $reservations = Reservation::with('book')->get();
        return response()->json(['success' => true, 'data' => $reservations]);
    }
}