<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center">

<div class="w-full max-w-xl bg-white shadow-xl rounded-2xl p-8">

    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📚 Add New Book</h1>
        <p class="text-gray-500 text-sm">Manage your library inventory easily</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Errors -->
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" action="/books/store" class="space-y-5">
        @csrf

        <!-- Title -->
        <div>
            <label class="block text-gray-700 font-medium mb-2">Book Title</label>
            <input type="text" name="title" placeholder="Enter book name"
                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>

        <!-- Quantity -->
        <div>
            <label class="block text-gray-700 font-medium mb-2">Quantity</label>
            <input type="number" name="quantity" placeholder="Enter quantity"
                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>

        <!-- Buttons -->
        <div class="flex justify-between items-center pt-3">
            <a href="/books" class="text-gray-500 hover:text-gray-700">← Back</a>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md transition">
                Add Book
            </button>
        </div>

    </form>
</div>

</body>
</html>