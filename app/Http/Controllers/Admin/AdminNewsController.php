<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Services\NewsService;
use Illuminate\Http\Request;

class AdminNewsController extends Controller
{
    public function __construct(protected NewsService $newsService) {}

    public function index()
    {
        $articles = NewsArticle::orderByDesc('published_at')->paginate(20);

        return view('admin.news', compact('articles'));
    }

    /**
     * Manually trigger a news fetch and cache refresh.
     */
    public function refresh()
    {
        \Cache::forget('currency_news');

        try {
            $this->newsService->getLatestNews(20);
            return back()->with('success', 'News refreshed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to refresh news: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        NewsArticle::findOrFail($id)->delete();

        return back()->with('success', 'Article removed.');
    }
}