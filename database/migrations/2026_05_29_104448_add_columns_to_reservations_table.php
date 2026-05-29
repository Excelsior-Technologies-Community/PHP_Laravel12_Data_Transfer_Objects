<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->date('actual_return_date')->nullable()->after('return_date');
            $table->boolean('penalty_paid')->default(false)->after('penalty');
            $table->enum('status', ['issued', 'returned', 'overdue'])->default('issued')->after('penalty_paid');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['actual_return_date', 'penalty_paid', 'status']);
        });
    }
};