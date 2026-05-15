<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(protected ExchangeRateService $exchangeRateService) {}

    /**
     * GET /api/currencies
     * Returns all supported currencies with code + name.
     */
    public function index()
    {
        try {
            $currencies = $this->exchangeRateService->getCurrencies();

            return response()->json(['currencies' => $currencies]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }

    /**
     * GET /api/currencies/{base}/rates
     * Returns all exchange rates for a base currency.
     */
    public function rates(string $base)
    {
        try {
            $data = $this->exchangeRateService->getRates(strtoupper($base));

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }

    /**
     * GET /api/currencies/{base}/rate/{target}
     * Returns a single rate between two currencies.
     */
    public function singleRate(string $base, string $target)
    {
        try {
            $rate = $this->exchangeRateService->getRate($base, $target);

            return response()->json([
                'from' => strtoupper($base),
                'to'   => strtoupper($target),
                'rate' => $rate,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }

    /**
     * GET /api/currencies/{base}/historical/{target}?days=7
     * Returns historical rates for a currency pair.
     */
    public function historical(Request $request, string $base, string $target)
    {
        $days = (int) $request->query('days', 7);
        $days = min(max($days, 1), 30); // clamp between 1 and 30

        try {
            $data = $this->exchangeRateService->getHistorical($base, $target, $days);

            return response()->json([
                'from'    => strtoupper($base),
                'to'      => strtoupper($target),
                'days'    => $days,
                'history' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }
}