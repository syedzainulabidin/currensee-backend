<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(protected NewsService $newsService) {}

    /**
     * GET /api/news
     * Returns latest currency/forex news.
     */
    public function index()
    {
        try {
            $articles = $this->newsService->getLatestNews(20);

            return response()->json(['articles' => $articles]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }

    /**
     * GET /api/news/{currency}
     * Returns news specific to a currency (e.g. USD, EUR).
     */
    public function byCurrency(string $currency)
    {
        try {
            $articles = $this->newsService->getNewsByCurrency($currency, 10);

            return response()->json(['articles' => $articles]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }
}