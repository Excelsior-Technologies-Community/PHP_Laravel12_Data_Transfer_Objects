<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8"> Library Dashboard</h1>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-blue-600 text-2xl mb-2"></div>
                <div class="text-2xl font-bold">{{ $stats->total_books }}</div>
                <div class="text-gray-600">Total Books</div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-green-600 text-2xl mb-2"></div>
                <div class="text-2xl font-bold">{{ $stats->available_books - $stats->issued_books }}</div>
                <div class="text-gray-600">Available Books</div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-orange-600 text-2xl mb-2"></div>
                <div class="text-2xl font-bold">{{ $stats->issued_books }}</div>
                <div class="text-gray-600">Issued Books</div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-red-600 text-2xl mb-2"></div>
                <div class="text-2xl font-bold">{{ $stats->overdue_reservations }}</div>
                <div class="text-gray-600">Overdue Returns</div>
            </div>
        </div>
        
        <!-- Overdue Books Alert -->
        @if($overdueReservations->count() > 0)
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8">
                <h2 class="font-bold"> Overdue Books Alert</h2>
                <p>{{ $overdueReservations->count() }} book(s) are overdue for return.</p>
            </div>
        @endif
    </div>
</body>
</html>