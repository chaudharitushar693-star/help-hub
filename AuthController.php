<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('user.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Support both bcrypt and SHA256 (for cross-environment testing)
        $passwordMatches = false;
        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                $passwordMatches = true;
            } elseif ($user->password === hash('sha256', $credentials['password'])) {
                $passwordMatches = true;
                // Upgrade to bcrypt
                $user->password = Hash::make($credentials['password']);
                $user->save();
            }
        }

        if ($passwordMatches && $user->is_active) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            AuditLog::log('USER_LOGIN', $user->id, $user->email, 'User', $user->id, 'User signed in successfully.');

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', "Welcome back, Admin {$user->name}!");
            }
            return redirect()->intended(route('user.dashboard'))->with('success', "Welcome to Yatri Sahayata, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or account is inactive.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Public registration NEVER assigns Admin role
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(6)],
            'preferred_language' => ['nullable', 'string', 'in:en,hi'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'role_id' => 2, // Strictly normal User / Pilgrim
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'preferred_language' => $validated['preferred_language'] ?? 'en',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Auto-create initial profile
        Profile::create([
            'user_id' => $user->id,
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact_phone' => $request->input('emergency_contact_phone'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
        ]);

        Auth::login($user);
        AuditLog::log('USER_REGISTRATION', $user->id, $user->email, 'User', $user->id, 'New pilgrim account registered.');

        return redirect()->route('user.dashboard')->with('success', 'Account registered successfully! Welcome to Yatri Sahayata.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::log('USER_LOGOUT', Auth::id(), Auth::user()->email, 'User', Auth::id(), 'User logged out.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'You have been logged out securely.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        return back()->with('status', 'If an account exists with this email, reset instructions have been dispatched.');
    }
}
