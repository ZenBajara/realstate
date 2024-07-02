<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($user->role)->plainTextToken;

            return response()->json([
                'token' => $token,
                'role' => $user->role,
            ]);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }
}
