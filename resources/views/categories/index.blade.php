<script src="https://cdn.tailwindcss.com"></script>
<body class="bg-gray-100 p-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-bold">Manage Categories</h1>
            <a href="/books" class="text-blue-600">Back to Books</a>
        </div>

        <form action="/categories" method="POST" class="mb-8 flex gap-2">
            @csrf
            <input type="text" name="name" placeholder="New Category (e.g. Sci-Fi)" class="border p-2 flex-1 rounded" required>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
        </form>

        <table class="w-full text-left">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-3">Name</th>
                    <th class="p-3">Books Count</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr class="border-b">
                    <td class="p-3">{{ $cat->name }}</td>
                    <td class="p-3">{{ $cat->books_count }}</td>
                    <td class="p-3">
                        <form action="/categories/delete/{{ $cat->id }}" method="POST">
                            @csrf
                            <button class="text-red-600 font-bold">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>