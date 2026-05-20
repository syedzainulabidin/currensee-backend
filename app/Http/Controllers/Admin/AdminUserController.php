<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function jsonSearch(Request $request)
    {
        $users = User::where('role', 'user')
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                  ->orWhere('email', 'like', '%'.$request->q.'%');
            })
            ->limit(8)
            ->get(['id', 'name', 'email']);

        return response()->json(['users' => $users]);
    }

    public function show(int $id)
    {
        $user = User::where('role', 'user')->with([
            'conversionHistories' => fn($q) => $q->orderByDesc('created_at')->limit(10),
            'rateAlerts'          => fn($q) => $q->orderByDesc('created_at')->limit(10),
        ])->findOrFail($id);

        return view('admin.user-detail', compact('user'));
    }

    public function suspend(int $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        // Store suspended state in preferences JSON
        $prefs               = $user->preferences ?? [];
        $prefs['suspended']  = true;
        $user->preferences   = $prefs;
        $user->save();

        // Revoke all tokens
        $user->tokens()->delete();

        return back()->with('success', "User {$user->name} suspended.");
    }

    public function unsuspend(int $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $prefs              = $user->preferences ?? [];
        $prefs['suspended'] = false;
        $user->preferences  = $prefs;
        $user->save();

        return back()->with('success', "User {$user->name} unsuspended.");
    }

    public function destroy(int $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }
}