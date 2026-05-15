<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-6">

<div class="max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📖 Reservation History</h1>
            <p class="text-gray-500 text-sm font-medium">Track student borrowings and penalty status</p>
        </div>
        <div class="flex gap-3">
            <a href="/dashboard" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg font-semibold transition">
                Dashboard
            </a>
            <a href="/books" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg font-semibold transition">
                Books
            </a>
            <a href="/reserve-book" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                + New Reservation
            </a>
        </div>
    </div>

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
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider">Book & Category</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider">Student Name</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider">Issue Date</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Due Date</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Penalty</th>
                    <th class="p-5 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($reservations as $res)
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="p-5">
                        <div class="font-bold text-gray-800 text-lg">{{ $res->book->title ?? 'N/A' }}</div>
                        <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded text-[10px] font-black uppercase">
                            {{ $res->book->category->name ?? 'General' }}
                        </span>
                    </td>

                    <td class="p-5">
                        <div class="text-gray-700 font-medium">{{ $res->student_name }}</div>
                    </td>

                    <td class="p-5 text-gray-500 font-mono text-sm">
                        {{ $res->issue_date }}
                    </td>

                    <td class="p-5 text-center text-gray-500 font-mono text-sm">
                        {{ $res->return_date }}
                    </td>

                    <td class="p-5 text-center">
                        @if($res->penalty > 0)
                            <div class="text-red-600 font-black text-lg">₹{{ $res->penalty }}</div>
                            <span class="text-[10px] text-red-400 font-bold uppercase tracking-tighter">Overdue</span>
                        @else
                            <div class="text-green-600 font-black text-lg">₹0</div>
                            <span class="text-[10px] text-green-400 font-bold uppercase tracking-tighter">On Time</span>
                        @endif
                    </td>

                    <td class="p-5 text-center">
                        <form action="/return-book/{{ $res->id }}" method="POST" onsubmit="return confirm('Confirm book return?')">
                            @csrf
                            <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg shadow-green-100 transition">
                                Return Book
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-20">
                        <div class="text-5xl mb-4">📭</div>
                        <div class="text-gray-400 font-bold">No active reservations found.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>