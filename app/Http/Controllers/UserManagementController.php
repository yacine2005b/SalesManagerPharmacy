<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::all(); // Fetch all users
        return view('admin.users', compact('users'));
    }

    /**
     * Show the form to create a new user.
     */
    public function create()
    {
        return view('admin.createUser');
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:admin,pharmacist,cashier'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' created a new user: ' . $request->name,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

   
    /**
     * Show the form to edit the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update the specified user in the database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,pharmacist,cashier'],
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

   
    public function destroy(User $user)
    {
        $userName = $user->name;
        $user->delete();
        // Log the activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => auth()->user()->name . ' Deleted a user: ' . $userName,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
