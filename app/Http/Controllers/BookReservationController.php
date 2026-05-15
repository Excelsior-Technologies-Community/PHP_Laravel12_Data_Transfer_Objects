<?php

namespace App\Http\Controllers;

use App\DTOs\BookReservationDTO;
use App\Models\Book;
use App\Models\Category;
use App\Models\Reservation;
use App\Services\BookReservationService;
use Illuminate\Http\Request;
use Exception;

class BookReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('book.category')->latest()->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        return view('reserve', [
            'books' => Book::with('category')->get(),
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request, BookReservationService $service)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_name' => 'required|string|max:255',
            'issue_date' => 'required|date',
        ]);

        try {
            $dto = new BookReservationDTO($request);
            $service->reserve($dto);

            return redirect('/reservations')->with('success', 'Book Reserved Successfully');
        } catch (Exception $e) {
            return back()->withErrors([$e->getMessage()])->withInput();
        }
    }

    public function returnBook($id)
    {
        $reservation = Reservation::findOrFail($id);

        Book::where('id', $reservation->book_id)->increment('quantity');

        $reservation->delete();

        return back()->with('success', 'Book Returned Successfully');
    }
}