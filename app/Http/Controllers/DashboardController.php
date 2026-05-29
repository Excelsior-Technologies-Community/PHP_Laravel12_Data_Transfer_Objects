<?php

namespace App\Http\Controllers;

use App\DTOs\DashboardStatsDTO;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = new DashboardStatsDTO();
        $overdueReservations = Reservation::with('book')
            ->where('status', 'issued')
            ->where('return_date', '<', now())
            ->get();
            
        return view('dashboard', compact('stats', 'overdueReservations'));
    }
}