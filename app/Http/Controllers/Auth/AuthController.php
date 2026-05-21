<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        // Send welcome email (never block registration if mail fails)
        error_log('[MAIL] Attempting to send welcome email to: ' . $user->email . ' via mailer: ' . config('mail.default'));
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
            error_log('[MAIL] Welcome email sent successfully to: ' . $user->email);
        } catch (\Throwable $e) {
            error_log('[MAIL] Welcome email FAILED: ' . $e->getMessage());
        }

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

    public function googleAuth(Request $request)
    {
        $data = $request->validate(['access_token' => 'required|string']);

        // Fetch user info from Google using the access token
        $response = Http::withToken($data['access_token'])
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (!$response->ok()) {
            throw ValidationException::withMessages(['access_token' => 'Invalid Google token.']);
        }

        $payload = $response->json();

        // Find or create the user
        $isNew = false;
        $user  = User::where('google_id', $payload['sub'])
                     ->orWhere('email', $payload['email'])
                     ->first();

        if (!$user) {
            $isNew = true;
            $user  = User::create([
                'name'             => $payload['name'] ?? $payload['email'],
                'email'            => $payload['email'],
                'google_id'        => $payload['sub'],
                'password'         => Hash::make(Str::random(32)),
                'default_currency' => 'USD',
                'role'             => 'user',
            ]);
        } else {
            // Link Google ID if signing in via Google for the first time
            if (!$user->google_id) {
                $user->update(['google_id' => $payload['sub']]);
            }
        }

        $token = $user->createToken('app_token')->plainTextToken;

        // Send welcome email only to new users
        if ($isNew) {
            try { Mail::to($user->email)->send(new WelcomeMail($user)); } catch (\Throwable $e) { Log::error('Welcome email failed: ' . $e->getMessage()); }
        }

        return response()->json(['user' => $user, 'token' => $token]);
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