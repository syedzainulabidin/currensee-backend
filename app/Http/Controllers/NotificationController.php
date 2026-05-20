<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * POST /api/notifications/device-token
     * Saves or updates the FCM device token for the authenticated user.
     */
    public function saveDeviceToken(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
        ]);

        // updateOrCreate prevents duplicate rows for the same token
        DeviceToken::updateOrCreate(
            ['token'   => $data['token']],
            ['user_id' => $request->user()->id],
        );

        return response()->json(['message' => 'Device token saved.']);
    }

    /**
     * GET /api/notifications/preferences
     * Returns current notification preferences.
     */
    public function getPreferences(Request $request)
    {
        $user = $request->user();

        $defaults = [
            'push_enabled'      => true,
            'rate_alerts_email' => false,
            'news_digest'       => false,
        ];

        $preferences = array_merge($defaults, $user->preferences['notifications'] ?? []);

        return response()->json(['preferences' => $preferences]);
    }

    /**
     * PUT /api/notifications/preferences
     * Updates notification preferences.
     */
    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'push_enabled'      => 'sometimes|boolean',
            'rate_alerts_email' => 'sometimes|boolean',
            'news_digest'       => 'sometimes|boolean',
        ]);

        $user                              = $request->user();
        $current                           = $user->preferences ?? [];
        $current['notifications']          = array_merge($current['notifications'] ?? [], $data);
        $user->preferences                 = $current;
        $user->save();

        return response()->json([
            'message'     => 'Preferences updated.',
            'preferences' => $current['notifications'],
        ]);
    }
}