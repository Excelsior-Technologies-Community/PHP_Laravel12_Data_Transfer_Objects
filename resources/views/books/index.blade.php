<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📚 Books Management</h1>
            <p class="text-gray-500 text-sm font-medium">Manage library inventory, categories, and QR tracking</p>
        </div>

        <div class="flex gap-3">
            <a href="/dashboard" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg font-semibold transition">
                Dashboard
            </a>
            <a href="/categories" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                Categories
            </a>
            <a href="/books/create" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                + Add Book
            </a>
        </div>
    </div>

    <form action="/books" method="GET" class="bg-white p-4 rounded-xl shadow-sm mb-6 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Search Title</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search book title..." 
                   class="w-full border-gray-200 border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
        </div>

        <div class="w-full md:w-64">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
            <select name="category_id" class="w-full border-gray-200 border p-2.5 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition">
            Filter
        </button>
        
        @if(request('search') || request('category_id'))
            <a href="/books" class="text-red-500 font-bold py-2.5 px-2 hover:underline">Clear</a>
        @endif
    </form>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 shadow-sm">
            <p class="font-bold">Success!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider">Book Details</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider">Category</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider">Quantity</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">QR Code</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($books as $book)
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="p-5">
                        <div class="font-bold text-gray-800 text-lg">{{ $book->title }}</div>
                        <div class="text-xs text-gray-400">ID: #{{ str_pad($book->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </td>

                    <td class="p-5">
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-lg text-xs font-bold">
                            {{ $book->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>

                    <td class="p-5 font-mono font-bold text-gray-700 text-center">
                        {{ $book->quantity }}
                    </td>

                    <td class="p-5 text-center">
                        <div class="inline-block p-1 bg-white border rounded-lg shadow-sm">
                            {!! QrCode::size(50)->generate('Book: ' . $book->title . ' | ID: ' . $book->id) !!}
                        </div>
                    </td>

                    <td class="p-5 text-center">
                        @if($book->quantity > 0)
                            <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-xs font-black uppercase">
                                Available
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-xs font-black uppercase">
                                Out of Stock
                            </span>
                        @endif
                    </td>

                    <td class="p-5 text-center">
                        <form method="POST" action="/books/delete/{{ $book->id }}" onsubmit="return confirm('Delete this book?')">
                            @csrf
                            <button class="text-red-500 hover:text-red-700 font-bold p-2 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-20">
                        <div class="text-5xl mb-4">Empty</div>
                        <div class="text-gray-400 font-bold">No books found matching your criteria.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>