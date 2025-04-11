<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;





class RegisterController extends Controller
{
    // @desc Show register form
    // @route GET /register
    public function register(): view
    {
        return view('auth.register');
    }

    // @desc Create a new user
    // @route POST /register
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Hash password
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Create user
        User::create($validatedData);

        return redirect()->route('login')->with('success', 'Registration successful!');
    }

}
