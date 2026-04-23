<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    // Show all books
    public function index()
    {
        $books = Book::latest()->get();
        return view('books.index', compact('books'));
    }

    // Show create form
    public function create()
    {
        return view('books.create');
    }

    // Store book
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        Book::create($request->all());

        return redirect('/books')->with('success', 'Book added successfully');
    }

    // Delete book
    public function delete($id)
    {
        Book::findOrFail($id)->delete();

        return back()->with('success', 'Book deleted');
    }
}