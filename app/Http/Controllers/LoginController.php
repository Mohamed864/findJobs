<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class LoginController extends Controller
{
     // @desc Show login form
    // @route GET /login
    public function login(): view
    {
        return view('auth.login');
    }

    // @desc Authenticate user
    // @route POST /login
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate user
        if(Auth::attempt($credentials)){
            // Regenerate the session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Login successful!');


        }
        // If auth fail, redirect back to login page

        return back()->withErrors([
            'email' => 'The provided credentials are invalid.',
        ])->onlyInput('email');

    }

    // @desc Logout user
    // @route POST /logout
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();



        return redirect()->route('home');
    }


}
