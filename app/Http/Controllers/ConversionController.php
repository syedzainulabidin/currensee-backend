<?php

namespace App\Http\Controllers;

use App\Models\ConversionHistory;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class ConversionController extends Controller
{
    public function __construct(protected ExchangeRateService $exchangeRateService) {}

    /**
     * POST /api/convert
     * Converts an amount and saves to history.
     */
    public function convert(Request $request)
    {
        $data = $request->validate([
            'from'   => 'required|string|size:3',
            'to'     => 'required|string|size:3',
            'amount' => 'required|numeric|min:0.000001',
        ]);

        try {
            $result = $this->exchangeRateService->convert(
                $data['from'],
                $data['to'],
                (float) $data['amount']
            );

            // Save to history for authenticated users
            $authUser = $request->user() ?? auth('sanctum')->user();
            if ($authUser) {
                ConversionHistory::create([
                    'user_id'          => $authUser->id,
                    'from_currency'    => $result['from'],
                    'to_currency'      => $result['to'],
                    'amount'           => $result['amount'],
                    'converted_amount' => $result['converted_amount'],
                    'rate'             => $result['rate'],
                ]);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }

    /**
     * GET /api/history
     * Returns authenticated user's conversion history (paginated).
     */
    public function history(Request $request)
    {
        $history = ConversionHistory::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($history);
    }

    /**
     * DELETE /api/history/{id}
     * Deletes a single history entry belonging to the user.
     */
    public function deleteHistory(Request $request, int $id)
    {
        $entry = ConversionHistory::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $entry->delete();

        return response()->json(['message' => 'Entry deleted.']);
    }

    /**
     * DELETE /api/history
     * Clears all history for the authenticated user.
     */
    public function clearHistory(Request $request)
    {
        ConversionHistory::where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'History cleared.']);
    }
}