<?php

namespace App\Services;

use App\Models\NewsArticle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsService
{
    protected string $baseUrl = 'https://newsapi.org/v2';
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    /**
     * Fetch latest currency/forex news.
     * Saves to DB and returns collection.
     * Cached for 1 hour to respect free tier (100 req/day).
     */
    public function getLatestNews(int $limit = 20): array
    {
        return Cache::remember('currency_news', now()->addHour(), function () use ($limit) {
            $response = Http::withHeaders([
                'X-Api-Key' => $this->apiKey,
            ])->get("{$this->baseUrl}/everything", [
                'q'        => 'currency exchange forex market',
                'language' => 'en',
                'sortBy'   => 'publishedAt',
                'pageSize' => $limit,
            ]);

            if ($response->failed()) {
                // Fall back to DB if API fails
                return $this->getFromDb($limit);
            }

            $articles = $response->json('articles') ?? [];

            $this->saveToDb($articles);

            return $this->formatArticles($articles);
        });
    }

    /**
     * Fetch news for a specific currency (e.g. "USD", "EUR").
     */
    public function getNewsByCurrency(string $currency, int $limit = 10): array
    {
        $currency = strtoupper($currency);

        return Cache::remember("news_{$currency}", now()->addHour(), function () use ($currency, $limit) {
            $response = Http::withHeaders([
                'X-Api-Key' => $this->apiKey,
            ])->get("{$this->baseUrl}/everything", [
                'q'        => "{$currency} currency exchange rate",
                'language' => 'en',
                'sortBy'   => 'publishedAt',
                'pageSize' => $limit,
            ]);

            if ($response->failed()) {
                return [];
            }

            $articles = $response->json('articles') ?? [];

            return $this->formatArticles($articles);
        });
    }

    /**
     * Save fetched articles to DB (upsert by URL to avoid duplicates).
     */
    protected function saveToDb(array $articles): void
    {
        foreach ($articles as $article) {
            if (empty($article['url']) || empty($article['title'])) {
                continue;
            }

            NewsArticle::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title'        => $article['title'],
                    'description'  => $article['description'] ?? null,
                    'source'       => $article['source']['name'] ?? null,
                    'image_url'    => $article['urlToImage'] ?? null,
                    'published_at' => isset($article['publishedAt'])
                        ? \Carbon\Carbon::parse($article['publishedAt'])
                        : null,
                ]
            );
        }
    }

    /**
     * Fallback: get news from DB if API is unavailable.
     */
    protected function getFromDb(int $limit): array
    {
        return NewsArticle::orderByDesc('published_at')
            ->limit($limit)
            ->get()
            ->map(fn($a) => [
                'title'        => $a->title,
                'description'  => $a->description,
                'url'          => $a->url,
                'source'       => $a->source,
                'image_url'    => $a->image_url,
                'published_at' => $a->published_at?->toDateTimeString(),
            ])
            ->toArray();
    }

    /**
     * Normalize API response into a consistent format.
     */
    protected function formatArticles(array $articles): array
    {
        return collect($articles)
            ->filter(fn($a) => !empty($a['title']) && !empty($a['url']))
            ->map(fn($a) => [
                'title'        => $a['title'],
                'description'  => $a['description'] ?? null,
                'url'          => $a['url'],
                'source'       => $a['source']['name'] ?? null,
                'image_url'    => $a['urlToImage'] ?? null,
                'published_at' => $a['publishedAt'] ?? null,
            ])
            ->values()
            ->toArray();
    }
}