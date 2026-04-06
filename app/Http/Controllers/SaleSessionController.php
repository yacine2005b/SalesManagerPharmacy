<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleSession;
use App\Models\ActivityLog;

class SaleSessionController extends Controller
{
    public function startSession()
    {
        // Check if a session is already active
        $activeSession = SaleSession::where('user_id', auth()->id())->whereNull('end_time')->first();
        if ($activeSession) {
            return redirect()->route('pos.normal')->with('error', 'A sale session is already active.');
        }

        // Create a new sale session
        $session = SaleSession::create([
            'user_id' => auth()->id(),
            'start_time' => now(),
        ]);

        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Started a sale session',
            'details' => 'Session ID: ' . $session->id,
        ]);

        return redirect()->route('pos.normal')->with('success', 'Sale session started successfully.');
    }
    public function endSession(SaleSession $session)
{
    // Ensure the session belongs to the loggedin user
    if ($session->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // End the session
    $session->update([
        'end_time' => now(),
    ]);

    // Log the activity
    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'Ended a sale session',
        'details' => 'Session ID: ' . $session->id,
    ]);

    return redirect()->route('pos.normal')->with('success', 'Sale session ended successfully.');
}
}
