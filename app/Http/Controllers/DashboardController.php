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

        // =========================
        // Chart Data for This Month
        // =========================
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        // Sessions per day
        $sessions = SaleSession::whereBetween('start_time', [$start, $end])
            ->selectRaw('DATE(start_time) as date, COUNT(*) as session_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Sales per day
        $sales = Sale::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_money')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Build labels and datasets for all days in the month
        $labels = [];
        $sessionCounts = [];
        $moneyTotals = [];
        $period = \Carbon\CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $labels[] = $d;
            $sessionCounts[] = isset($sessions[$d]) ? $sessions[$d]->session_count : 0;
            $moneyTotals[] = isset($sales[$d]) ? (float)$sales[$d]->total_money : 0;
        }

        return view('welcome', compact(
            'activeSession', 
            'totalSalesToday', 
            'totalTransactions', 
            'activeSessionsCount', 
            'recentTransactions',
            'labels',
            'sessionCounts',
            'moneyTotals'
        ));
    }
}
