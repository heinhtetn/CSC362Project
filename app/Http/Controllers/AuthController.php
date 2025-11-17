<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user account is active
            if (!$user->active) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ])->withInput($request->only('email'));
            }
            
            $request->session()->regenerate();

            // Check profile
            if (!$user->jobSeeker) {
                return redirect()->route('jobseeker.create')
                    ->with('warning', 'Please complete your profile.');
            }

            return redirect()->intended(route('jobs.list'));
        }

        // If login fails
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->withInput($request->only('email'));
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }


    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create($request->all());

        return redirect()->route('login')->with('success', 'Account created successfully.');
    }
}
