<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleSession;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index(){
        $activeSession = SaleSession::whereNull('end_time')->first();
        $totalSalesToday = Sale::whereDate('created_at', today())->sum('total_amount');
        $totalTransactions = Sale::count();
        $activeSessionsCount = SaleSession::whereNull('end_time')->count();
        $recentTransactions = Sale::latest()->take(5)->get();

        return view('welcome', compact(
            'activeSession', 
            'totalSalesToday', 
            'totalTransactions', 
            'activeSessionsCount', 
            'recentTransactions'
        ));
    }
}
