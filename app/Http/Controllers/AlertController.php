<?php

namespace App\Http\Controllers;

use App\Models\RateAlert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * GET /api/alerts
     * Returns all alerts for the authenticated user.
     */
    public function index(Request $request)
    {
        $alerts = RateAlert::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['alerts' => $alerts]);
    }

    /**
     * POST /api/alerts
     * Creates a new rate alert.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'from_currency' => 'required|string|size:3',
            'to_currency'   => 'required|string|size:3',
            'target_rate'   => 'required|numeric|min:0.000001',
            'direction'     => 'required|in:above,below',
        ]);

        $alert = RateAlert::create([
            'user_id'       => $request->user()->id,
            'from_currency' => strtoupper($data['from_currency']),
            'to_currency'   => strtoupper($data['to_currency']),
            'target_rate'   => $data['target_rate'],
            'direction'     => $data['direction'],
            'is_active'     => true,
        ]);

        return response()->json(['alert' => $alert], 201);
    }

    /**
     * PUT /api/alerts/{id}
     * Updates an existing alert.
     */
    public function update(Request $request, int $id)
    {
        $alert = RateAlert::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $data = $request->validate([
            'target_rate' => 'sometimes|numeric|min:0.000001',
            'direction'   => 'sometimes|in:above,below',
            'is_active'   => 'sometimes|boolean',
        ]);

        $alert->update($data);

        return response()->json(['alert' => $alert]);
    }

    /**
     * DELETE /api/alerts/{id}
     * Deletes an alert belonging to the user.
     */
    public function destroy(Request $request, int $id)
    {
        $alert = RateAlert::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $alert->delete();

        return response()->json(['message' => 'Alert deleted.']);
    }
}