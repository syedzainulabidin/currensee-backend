<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8|confirmed',
            'default_currency'  => 'sometimes|string|size:3',
        ]);

        $user = User::create([
            'name'             => $data['name'],
            'email'            => $data['email'],
            'password'         => Hash::make($data['password']),
            'default_currency' => strtoupper($data['default_currency'] ?? 'USD'),
            'role'             => 'user',
        ]);

        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user  = Auth::user();
        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'default_currency'              => 'sometimes|string|size:3',
            'preferences'                   => 'sometimes|array',
            'preferences.notifications'     => 'sometimes|boolean',
            'preferences.rate_alerts_email' => 'sometimes|boolean',
        ]);

        $user = $request->user();

        if (isset($data['default_currency'])) {
            $user->default_currency = strtoupper($data['default_currency']);
        }

        if (isset($data['preferences'])) {
            $current             = $user->preferences ?? [];
            $user->preferences   = array_merge($current, $data['preferences']);
        }

        $user->save();

        return response()->json($user);
    }
}