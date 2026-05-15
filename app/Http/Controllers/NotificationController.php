<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
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