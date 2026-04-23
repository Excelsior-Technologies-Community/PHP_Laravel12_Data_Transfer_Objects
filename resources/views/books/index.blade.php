<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-6xl mx-auto">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📚 Books Management</h1>
            <p class="text-gray-500 text-sm">Manage and track all books</p>
        </div>

        <a href="/books/create"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
            + Add Book
        </a>
    </div>

    <!-- Success -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="w-full text-left">

            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 text-gray-600">Title</th>
                    <th class="p-4 text-gray-600">Quantity</th>
                    <th class="p-4 text-gray-600 text-center">Status</th>
                    <th class="p-4 text-gray-600 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($books as $book)
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="p-4 font-medium text-gray-800">
                        {{ $book->title }}
                    </td>

                    <td class="p-4">
                        {{ $book->quantity }}
                    </td>

                    <!-- Status -->
                    <td class="p-4 text-center">
                        @if($book->quantity > 0)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Available
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Out of Stock
                            </span>
                        @endif
                    </td>

                    <!-- Action -->
                    <td class="p-4 text-center">
                        <form method="POST" action="/books/delete/{{ $book->id }}">
                            @csrf
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg text-sm">
                                Delete
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-6 text-gray-500">
                        No books found. Add your first book 🚀
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

</body>
</html>