<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * POST /api/feedback
     * Submits feedback. Works for both guests and authenticated users.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|min:10|max:2000',
            'type'    => 'sometimes|in:bug,suggestion,general,feature,other',
            'rating'  => 'sometimes|integer|min:1|max:5',
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->user()?->id,
            'message' => $data['message'],
            'type'    => $data['type'] ?? 'general',
            'rating'  => $data['rating'] ?? null,
            'status'  => 'new',
        ]);

        return response()->json([
            'message'  => 'Feedback submitted. Thank you!',
            'feedback' => $feedback,
        ], 201);
    }

    /**
     * GET /api/feedback
     * Returns feedback submitted by the authenticated user.
     */
    public function myFeedback(Request $request)
    {
        $feedback = Feedback::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['feedback' => $feedback]);
    }
}