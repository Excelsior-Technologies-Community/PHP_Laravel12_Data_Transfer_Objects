<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <nav class="bg-white shadow-sm border-b mb-10">
        <div class="max-w-7xl mx-auto px-8 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-black text-blue-700 uppercase tracking-tight">📚 Library Admin</h1>
            <div class="flex gap-8">
                <a href="/dashboard" class="text-blue-600 font-bold border-b-2 border-blue-600">Dashboard</a>
                <a href="/books" class="text-gray-500 hover:text-blue-600 font-bold transition">Books</a>
                <a href="/categories" class="text-gray-500 hover:text-blue-600 font-bold transition">Categories</a>
                <a href="/reservations" class="text-gray-500 hover:text-blue-600 font-bold transition">History</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-8 pb-20">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-blue-100 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Books in Stock</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ number_format($stats['total_books']) }}</h3>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-green-100 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Active Reservations</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ number_format($stats['total_reservations']) }}</h3>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center gap-5">
                <div class="bg-purple-100 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Total Categories</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ number_format($stats['total_categories']) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-2xl font-black text-gray-800">Inventory Analysis</h2>
                    <p class="text-gray-400 font-medium">Distribution of book quantities by category</p>
                </div>
                <a href="/books/create" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">Add New Book</a>
            </div>

            <div class="relative h-[400px]">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

    </main>

    <script>
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total Books',
                    data: {!! json_encode($data) !!},
                    backgroundColor: '#3b82f6',
                    hoverBackgroundColor: '#2563eb',
                    borderRadius: 12,
                    borderSkipped: false,
                    barThickness: 45,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { 
                            color: '#f1f5f9',
                            drawBorder: false 
                        },
                        ticks: { 
                            font: { weight: 'bold' },
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    }
                }
            }
        });
    </script>

</body>
</html>