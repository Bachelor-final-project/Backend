<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
        ], 201);
    }

    public function create()
    {
        return Inertia::render('Auth/login');
        // return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Logged in successfully!'], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out successfully!'], 200);
    }

    public function profile()
    {
        return response()->json(Auth::user());
    }
}