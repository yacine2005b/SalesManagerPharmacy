<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivitylogController extends Controller
{
    public function index()
    {
        // Fetch all activity logs
        $activityLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(10); // 10 logs per page

        return view('admin.activityLog', compact('activityLogs'));
    }
}
