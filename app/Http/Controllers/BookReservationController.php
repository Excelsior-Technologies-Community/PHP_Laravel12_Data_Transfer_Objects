<?php

namespace App\Http\Controllers;

use App\DTOs\BookReservationDTO;
use App\Models\Book;
use App\Models\Reservation;
use App\Services\BookReservationService;
use Illuminate\Http\Request;

class BookReservationController extends Controller
{
    // Show Reserve Page
    public function create()
    {
        return view('reserve', [
            'books' => Book::all()
        ]);
    }

    // Store Reservation
    public function store(Request $request, BookReservationService $service)
    {
        $request->validate([
            'book_id' => 'required',
            'student_name' => 'required',
            'issue_date' => 'required|date',
        ]);

        $dto = new BookReservationDTO($request);

        $service->reserve($dto);

        return back()->with('success', 'Book Reserved Successfully');
    }

    // NEW: Reservation History
    public function index()
    {
        $reservations = Reservation::with('book')->latest()->get();
        return view('reservations.index', compact('reservations'));
    }

    // NEW: Return Book
    public function returnBook($id)
    {
        $reservation = Reservation::findOrFail($id);

        // increase quantity
        Book::where('id', $reservation->book_id)->increment('quantity');

        // delete reservation
        $reservation->delete();

        return back()->with('success', 'Book Returned Successfully');
    }
}