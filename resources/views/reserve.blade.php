<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Book Reservation</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <div class="w-full bg-white shadow-md py-4 px-8 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-700">📚 Library System</h1>

        <div class="flex gap-6">
            <a href="/books" class="text-gray-600 hover:text-blue-600 font-medium">Books</a>
            <a href="/reserve-book" class="text-blue-600 font-semibold">Reserve</a>
            <a href="/reservations" class="text-gray-600 hover:text-blue-600 font-medium">History</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex items-center justify-center mt-10 px-4">

        <div class="w-full max-w-lg p-8 bg-white rounded-xl shadow-lg">

            <h1 class="text-3xl font-bold text-center mb-6 text-blue-700">
                📚 Book Reservation
            </h1>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4 shadow">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4 shadow">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Reservation Form -->
            <form method="POST" action="/reserve-book" class="space-y-5">
                @csrf

                <!-- Book Dropdown -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Select Book</label>
                    <select name="book_id"
                        class="w-full border-gray-300 rounded p-3 focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                        <option value="">-- Choose Book --</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ $book->quantity == 0 ? 'disabled' : '' }}>
                                {{ $book->title }} (Available: {{ $book->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Student Name -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Student Name</label>
                    <input type="text" name="student_name"
                           placeholder="Enter student name"
                           class="w-full border-gray-300 rounded p-3 focus:ring-2 focus:ring-blue-400 outline-none"
                           required>
                </div>

                <!-- Issue Date -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Issue Date</label>
                    <input type="date" name="issue_date"
                           class="w-full border-gray-300 rounded p-3 focus:ring-2 focus:ring-blue-400 outline-none"
                           required>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all duration-200 shadow">
                    Reserve Book
                </button>
            </form>

            <!-- Optional Info -->
            @isset($dto)
                <div class="mt-6 p-4 bg-gray-50 rounded shadow-inner border">
                    <p class="text-gray-800 font-medium">
                        Return Date:
                        <span class="font-bold text-blue-600">{{ $dto->return_date }}</span>
                    </p>
                    <p class="text-gray-800 font-medium">
                        Penalty:
                        <span class="font-bold text-red-600">₹{{ $dto->penalty }}</span>
                    </p>
                </div>
            @endisset

        </div>
    </div>

</body>
</html>