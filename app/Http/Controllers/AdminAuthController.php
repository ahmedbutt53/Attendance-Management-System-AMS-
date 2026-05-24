<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->roles()->where('name', 'Admin')->exists()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * Handle the admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        // Check if user is active first
        $user = User::where('email', $credentials['email'])->first();

        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Your account is deactivated. Please contact the administrator.',
            ]);
        }

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if user is an Admin
            if ($user->roles()->where('name', 'Admin')->exists()) {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Welcome back to the Admin Panel, ' . $user->name . '!');
            }

            // If not admin, log them out immediately and reject login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Access denied. Only administrators are allowed to log in here.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
