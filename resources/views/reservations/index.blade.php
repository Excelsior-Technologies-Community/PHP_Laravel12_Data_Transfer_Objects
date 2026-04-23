<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-6xl mx-auto">

    <!-- Navbar -->
    <div class="mb-6 flex gap-6">
        <a href="/books" class="text-gray-600 hover:text-blue-600">Books</a>
        <a href="/reserve-book" class="text-gray-600 hover:text-blue-600">Reserve</a>
        <a href="/reservations" class="text-blue-600 font-semibold">History</a>
    </div>

    <h1 class="text-3xl font-bold mb-6">📖 Reservation History</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Book</th>
                    <th class="p-3">Student</th>
                    <th class="p-3">Issue</th>
                    <th class="p-3">Return</th>
                    <th class="p-3">Penalty</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($reservations as $res)
                <tr class="border-t text-center">

                    <td class="p-3">{{ $res->book->title ?? 'N/A' }}</td>
                    <td class="p-3">{{ $res->student_name }}</td>
                    <td class="p-3">{{ $res->issue_date }}</td>
                    <td class="p-3">{{ $res->return_date }}</td>

                    <td class="p-3">
                        @if($res->penalty > 0)
                            <span class="text-red-600 font-bold">₹{{ $res->penalty }}</span>
                        @else
                            <span class="text-green-600 font-bold">₹0</span>
                        @endif
                    </td>

                    <td class="p-3">
                        <form action="/return-book/{{ $res->id }}" method="POST">
                            @csrf
                            <button class="bg-green-500 text-white px-3 py-1 rounded">
                                Return
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-5 text-gray-500">
                        No reservations found
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

</body>
</html>