<?php

namespace App\Http\Controllers;

use App\DTOs\BookReservationDTO;
use App\Models\Book;
use App\Services\BookReservationService;
use Illuminate\Http\Request;

class BookReservationController extends Controller
{
    public function create()
    {
        return view('reserve', [
            'books' => Book::all()
        ]);
    }

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
}
