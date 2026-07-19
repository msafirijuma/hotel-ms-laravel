<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Redirect based on user role
            if ($user->hasAnyRole(['admin', 'receptionist', 'housekeeper', 'manager'])) {
                return redirect()->route('dashboard')->with('success', 'Welcome back!');
            } 
            else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have access to the dashboard.',
                ])->withInput();
            }
        }

        return back()->withErrors([
            'email' => 'Email or password is incorrect.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}