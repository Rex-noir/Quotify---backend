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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->messages();

            if (isset($errors['email'])) {
                $message = '"A man must enter email!" - Anon!';
                return response(['message' => $message], 422);
            }
            if (isset($errors['password'])) {
                $message = '"Forget not thou password!" - A';
                return response(['message' => $message], 422);
            }
            return response($validator->errors(), 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return response()->noContent();
        }
        return response(['message' => 'Invalid credentials.'], 401);
    }
}
