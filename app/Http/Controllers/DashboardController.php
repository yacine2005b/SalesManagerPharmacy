<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleSession;

class DashboardController extends Controller
{
    public function index(){
        $activeSession = SaleSession::where('user_id', auth()->id())
        ->whereNull('end_time') // Ensure the session is not ended
        ->latest()
        ->first();

    
        return view('welcome', compact('activeSession'));
    }
}
