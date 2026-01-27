# PHP_Laravel12_Data_Transfer_Objects

## Project Overview

This is a Laravel 12 project demonstrating the use of Data Transfer Objects (DTOs) to handle smart data processing and business logic for a Book Reservation System.

The project is designed to show how DTOs can encapsulate data and business rules outside of controllers, making your Laravel applications more clean, maintainable, and scalable.


## Technical Stack

Backend: PHP 8+, Laravel 12

Database: MySQL

Frontend: Blade Templates, Tailwind CSS

Design Pattern: MVC + DTO + Service Layer


## Benefits of Using DTOs in This Project

Separation of Concerns: Business logic is outside the controller.

Reusability: DTO can be reused across multiple services or controllers.

Maintainability: Easier to update rules like penalty calculation or return period.

Clarity: Clear structure for handling complex data and rules.


---



# Project Setup & Step-by-Step Explanation

---

## STEP 1: Create New Laravel 12 Project

### Run Command :

```
composer create-project laravel/laravel PHP_Laravel12_Data_Transfer_Objects "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Data_Transfer_Objects

```

Make sure Laravel 12 is installed successfully.



## STEP 2: Database Configuration

### Open .env file and update database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dto_transfer_library
DB_USERNAME=root
DB_PASSWORD=

```

### Create database:

```
dto_transfer_library

```



## STEP 3: Create Models + Migrations 

### Run Command:

```
php artisan make:model Book -m

php artisan make:model Reservation -m

```

Explanation:

Book and Reservation models represent tables.

-m also generates migration files for database schema




## STEP 4: Edit Migrations and Models

### database/migrations/xxxx_create_books_table.php

```

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->integer('quantity');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};


```


### database/migrations/xxxx_create_reservations_table.php

```

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('book_id');
    $table->string('student_name');
    $table->date('issue_date');
    $table->date('return_date');
    $table->integer('penalty');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};


```


### app/Models/Book.php

```

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'quantity'
    ];
}


```

### app/Models/Reservation.php

```

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'book_id',
        'student_name',
        'issue_date',
        'return_date',
        'penalty'
    ];
}


```

### Then run migrations:

```
php artisan migrate

```

Explanation:

$fillable allows mass assignment when using create().




## STEP 5: Add Sample Books

### Insert some books into the database so dropdown works:

```
INSERT INTO books (title, quantity, created_at, updated_at) VALUES
('Mathematics', 5, NOW(), NOW()),
('Science', 3, NOW(), NOW()),
('History', 2, NOW(), NOW());


```

Explanation:

Adds sample data for dropdown in the reservation form.




## STEP 6: Create DTO Folder

```
app/DTOs

```


## STEP 7: Create SMART DTO

### app/DTOs/BookReservationDTO.php

```

<?php

namespace App\DTOs;

use App\Models\Book;
use Carbon\Carbon;

class BookReservationDTO
{
    public int $book_id;
    public string $student_name;
    public string $issue_date;

    public string $return_date;
    public int $penalty;
    public bool $can_issue;

    public function __construct($request)
    {
        $this->book_id = $request->book_id;
        $this->student_name = $request->student_name;
        $this->issue_date = $request->issue_date;

        $this->applyBusinessRules();
    }

    private function applyBusinessRules(): void
    {
        $this->calculateReturnDate();
        $this->calculatePenalty();
        $this->checkAvailability();
    }

    private function calculateReturnDate(): void
    {
        $this->return_date = Carbon::parse($this->issue_date)
            ->addDays(7)
            ->toDateString();
    }

    private function calculatePenalty(): void
    {
        $today = Carbon::today();
        $returnDate = Carbon::parse($this->return_date);

        $this->penalty = $today->greaterThan($returnDate)
            ? $today->diffInDays($returnDate) * 10
            : 0;
    }

    private function checkAvailability(): void
    {
        $book = Book::find($this->book_id);
        $this->can_issue = $book && $book->quantity > 0;
    }

    public function toArray(): array
    {
        return [
            'book_id' => $this->book_id,
            'student_name' => $this->student_name,
            'issue_date' => $this->issue_date,
            'return_date' => $this->return_date,
            'penalty' => $this->penalty,
        ];
    }
}

```

Explanation:

Handles return date, penalty, and availability automatically.

Keeps controller clean and separates business logic.




## STEP 8: Create Service 

### Run Command:

```
php artisan make:class Services/BookReservationService

```


### app/Services/BookReservationService.php

```

<?php

namespace App\Services;

use App\DTOs\BookReservationDTO;
use App\Models\Book;
use App\Models\Reservation;

class BookReservationService
{
    public function reserve(BookReservationDTO $dto)
    {
        if (!$dto->can_issue) {
            throw new \Exception('Book not available');
        }

        Reservation::create($dto->toArray());

        Book::where('id', $dto->book_id)->decrement('quantity');
    }
}

```

Explanation:

Service handles reservation logic outside the controller.

Throws exception if book is unavailable.




## STEP 9: Create Controller 

### Run Command:

```
php artisan make:controller BookReservationController

```

### app/Http/Controllers/BookReservationController.php

```

<?php

namespace App\Http\Controllers;

use App\DTOs\BookReservationDTO;
use App\Models\Book;
use App\Services\BookReservationService;
use Illuminate\Http\Request;

class BookReservationController extends Controller
{
    public function create()
    {
        return view('reserve', [
            'books' => Book::all()
        ]);
    }

    public function store(Request $request, BookReservationService $service)
    {
        $request->validate([
            'book_id' => 'required',
            'student_name' => 'required',
            'issue_date' => 'required|date',
        ]);

        $dto = new BookReservationDTO($request);

        $service->reserve($dto);

        return back()->with('success', 'Book Reserved Successfully');
    }
}


```

Explanation:

Controller is thin: only validates request, creates DTO, and calls service.




## STEP 10: Routes

### routes/web.php

```

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookReservationController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/reserve-book', [BookReservationController::class, 'create']);
Route::post('/reserve-book', [BookReservationController::class, 'store']);


```



## STEP 11: Blade View

### resources/views/reserve.blade.php

```

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Book Reservation</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-lg p-8 bg-white rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-center mb-6 text-blue-700">📚 Book Reservation</h1>

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
        <form method="POST" class="space-y-5">
            @csrf

            <!-- Book Dropdown -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Select Book</label>
                <select name="book_id" class="w-full border-gray-300 rounded p-3 focus:ring-2 focus:ring-blue-400" required>
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
                <input type="text" name="student_name" placeholder="Enter student name"
                       class="w-full border-gray-300 rounded p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <!-- Issue Date -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Issue Date</label>
                <input type="date" name="issue_date"
                       class="w-full border-gray-300 rounded p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all duration-200">
                Reserve Book
            </button>
        </form>

        <!-- Optional Info -->
        @isset($dto)
            <div class="mt-6 p-4 bg-gray-50 rounded shadow-inner">
                <p class="text-gray-800 font-medium">Return Date: <span class="font-bold text-blue-600">{{ $dto->return_date }}</span></p>
                <p class="text-gray-800 font-medium">Penalty: <span class="font-bold text-red-600">{{ $dto->penalty }}</span></p>
            </div>
        @endisset
    </div>

</body>
</html>

```

Explanation:

Tailwind CSS makes the form beautiful.

Shows success/error messages.

Optional info shows return date & penalty from the DTO.





## STEP 12: Start Server

### Run:

```
php artisan serve

```

### Open:

```
http://127.0.0.1:8000/reserve-book

```



## So you can see this type Output:


### Reserve-book Page:


<img width="1919" height="956" alt="Screenshot 2026-01-27 123343" src="https://github.com/user-attachments/assets/93356b52-0881-46ff-acd7-06c75bbc030b" />


### Reserve-book Page (after click on reserve book button then Success Message show):


<img width="1919" height="964" alt="Screenshot 2026-01-27 123356" src="https://github.com/user-attachments/assets/12cbbead-a69d-4164-b602-73228dd9c9dc" />


---


# Project Folder Structure:

```

PHP_Laravel12_Data_Transfer_Objects/
├── app/
│   ├── Console/
│   ├── DTOs/
│   │   └── BookReservationDTO.php
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── BookReservationController.php
│   │   ├── Middleware/
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── Book.php
│   │   └── Reservation.php
│   ├── Providers/
│   └── Services/
│       └── BookReservationService.php
├── bootstrap/
│   └── app.php
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── xxxx_create_books_table.php
│   │   └── xxxx_create_reservations_table.php
│   └── seeders/
├── public/
│   └── index.php
├── resources/
│   ├── views/
│   │   └── reserve.blade.php
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── storage/
├── tests/
│   ├── Feature/
│   └── Unit/
├── vendor/
├── .env
├── composer.json
└── artisan
```
