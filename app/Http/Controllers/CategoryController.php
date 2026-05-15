<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        return view('categories.index', ['categories' => Category::withCount('books')->get()]);
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:categories']);
        Category::create($request->all());
        return back()->with('success', 'Category added!');
    }

    public function delete($id) {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Category removed!');
    }
}