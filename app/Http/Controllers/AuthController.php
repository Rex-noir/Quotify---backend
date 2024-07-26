<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function verify(Request $request)
    {
        if (Auth::user()) {
            return response(['message' => 'Authenticated'], 200);
        }
        return response(['message' => 'Not authenticated'], 206);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'username', 'password');

        // Determine if we're authenticating using an email or username
        $fieldType = filter_var($credentials['email'] ?? '', FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $request->validate([
            $fieldType => 'required|string|exists:users,' . $fieldType,
            'password' => 'required|string',
        ]);

        if (Auth::attempt([$fieldType => $credentials[$fieldType], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return response()->json(['message' => 'Login successful.']);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }
}
