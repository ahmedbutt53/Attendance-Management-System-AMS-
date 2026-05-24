<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
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

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->roles()->where('name', 'Admin')->exists()) {
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Admin Dashboard loaded successfully!');
            }

            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['nullable', 'string', 'max:100'],
            'employee_id' => ['nullable', 'string', 'max:50', 'unique:users'],
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'is_active' => true,
        ]);

        // Assign default Student role
        $studentRole = Role::where('name', 'Student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole->id);
        }

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please sign in to continue.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'You have been logged out successfully.');
    }
}
