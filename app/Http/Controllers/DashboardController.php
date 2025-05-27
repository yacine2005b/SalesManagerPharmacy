<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleSession;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index(){
        // Only get the active session for the current user
        $activeSession = SaleSession::where('user_id', auth()->id())
            ->whereNull('end_time')
            ->first();

        $totalSalesToday = Sale::whereDate('created_at', today())->sum('total_amount');
        $activeSessionsCount = SaleSession::whereNull('end_time')->count();
        $recentTransactions = Sale::latest()->take(5)->get();

        // Total transactions = total sales for the active session of this user
        $totalTransactions = 0;
        if ($activeSession) {
            $totalTransactions = Sale::where('sale_session_id', $activeSession->id)->count();
        }

        return view('welcome', compact(
            'activeSession', 
            'totalSalesToday', 
            'totalTransactions', 
            'activeSessionsCount', 
            'recentTransactions'
        ));
    }
}
