<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversionHistory;
use App\Models\Feedback;
use App\Models\RateAlert;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::where('role', 'user')->count(),
            'new_users_today'   => User::where('role', 'user')
                ->whereDate('created_at', today())
                ->count(),
            'conversions_today' => ConversionHistory::whereDate('created_at', today())->count(),
            'total_conversions' => ConversionHistory::count(),
            'active_alerts'     => RateAlert::where('is_active', true)->count(),
            'new_feedback'      => Feedback::where('status', 'new')->count(),
        ];

        $recentUsers = User::where('role', 'user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'name', 'email', 'default_currency', 'created_at']);

        $recentConversions = ConversionHistory::with('user:id,name,email')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentConversions'));
    }

    public function conversions()
    {
        $query = ConversionHistory::with('user:id,name,email')
            ->orderByDesc('created_at');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('from_currency', 'like', "%{$search}%")
                    ->orWhere('to_currency', 'like', "%{$search}%");
            });
        }

        $conversions = $query->paginate(25)->withQueryString();

        return view('admin.conversions', compact('conversions'));
    }
}
