<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users.index_adminlte', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create_adminlte');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:client,shop,admin'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'type' => $validated['type'],
            'is_admin' => $validated['type'] === 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', 'User created successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')->with('status', "User {$status} successfully.");
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}
