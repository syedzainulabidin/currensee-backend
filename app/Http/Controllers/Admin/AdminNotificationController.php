<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function __construct(protected PushNotificationService $push) {}

    public function index()
    {
        $totalTokens = DeviceToken::count();
        $usersWithTokens = DeviceToken::distinct('user_id')->count('user_id');

        return view('admin.notifications', compact('totalTokens', 'usersWithTokens'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'target'  => 'required|in:all,user',
            'user_id' => 'required_if:target,user|nullable|exists:users,id',
            'title'   => 'required|string|max:100',
            'body'    => 'required|string|max:500',
            'type'    => 'sometimes|in:announcement,alert,promo,update',
        ]);

        $extra = ['type' => $data['type'] ?? 'announcement'];

        if ($data['target'] === 'all') {
            $this->push->sendToAll($data['title'], $data['body'], $extra);
            $msg = 'Notification sent to all users.';
        } else {
            $this->push->sendToUser((int) $data['user_id'], $data['title'], $data['body'], $extra);
            $user = User::find($data['user_id']);
            $msg = "Notification sent to {$user->name}.";
        }

        return back()->with('success', $msg);
    }
}
