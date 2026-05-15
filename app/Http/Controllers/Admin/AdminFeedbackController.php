<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('user:id,name,email');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $feedback = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $counts = [
            'new'      => Feedback::where('status', 'new')->count(),
            'reviewed' => Feedback::where('status', 'reviewed')->count(),
            'resolved' => Feedback::where('status', 'resolved')->count(),
        ];

        return view('admin.feedback', compact('feedback', 'counts'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|in:new,reviewed,resolved',
        ]);

        Feedback::findOrFail($id)->update(['status' => $data['status']]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(int $id)
    {
        Feedback::findOrFail($id)->delete();

        return back()->with('success', 'Feedback deleted.');
    }
}