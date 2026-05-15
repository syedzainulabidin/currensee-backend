@extends('layouts.admin')
@section('title', 'News')

@section('content')

    <!-- Header -->
    <div class="animate-in" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:4px;">
            &gt; NEWS_FEED_MANAGER
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text);">
            News Feed
        </h1>
    </div>

    <!-- Refresh Button -->
    <div class="animate-in stagger-1" style="margin-bottom:16px;">
        <form method="POST" action="{{ route('admin.news.refresh') }}">
            @csrf
            <button type="submit" class="btn-green"
                style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <polyline points="23 4 23 10 17 10" />
                    <polyline points="1 20 1 14 7 14" />
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
                </svg>
                REFRESH NEWS FEED
            </button>
        </form>
    </div>

    <!-- Stats -->
    <div class="animate-in stagger-2" style="display:flex;gap:8px;margin-bottom:16px;">
        <div style="flex:1;background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div
                style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:var(--green);text-shadow:0 0 10px var(--green);">
                {{ $articles->total() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                TOTAL ARTICLES</div>
        </div>
        <div style="flex:1;background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:#fff;">
                {{ \App\Models\NewsArticle::whereDate('created_at', today())->count() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                TODAY</div>
        </div>
    </div>

    <!-- Articles -->
    <div class="section-title animate-in stagger-2">ARTICLES</div>

    @forelse($articles as $index => $article)
        <div class="cyber-card animate-in" style="animation-delay:{{ $index * 0.04 }}s;opacity:0;padding:12px 14px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">

                <div style="flex:1;min-width:0;">
                    <!-- Title -->
                    <div
                        style="font-family:'Syne',sans-serif;font-weight:600;font-size:0.85rem;color:var(--text);margin-bottom:6px;line-height:1.3;">
                        {{ Str::limit($article->title, 80) }}
                    </div>

                    <!-- Description -->
                    @if ($article->description)
                        <div style="font-size:0.75rem;color:var(--text-dim);margin-bottom:8px;line-height:1.4;">
                            {{ Str::limit($article->description, 100) }}
                        </div>
                    @endif

                    <!-- Meta -->
                    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                        @if ($article->source)
                            <span class="badge badge-green">{{ strtoupper($article->source) }}</span>
                        @endif
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                            {{ $article->published_at ? $article->published_at->format('d M Y H:i') : 'N/A' }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
                    <a href="{{ $article->url }}" target="_blank" class="btn-outline"
                        style="padding:5px 10px;font-size:0.6rem;text-align:center;">
                        OPEN
                    </a>
                    <form method="POST" action="{{ route('admin.news.destroy', $article->id) }}"
                        onsubmit="return confirm('DELETE THIS ARTICLE?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="padding:5px 10px;font-size:0.6rem;width:100%;">
                            DELETE
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="cyber-card" style="text-align:center;padding:40px;">
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:16px;">
                NO_ARTICLES_IN_DB
            </div>
            <form method="POST" action="{{ route('admin.news.refresh') }}">
                @csrf
                <button type="submit" class="btn-green" style="display:inline-block;width:auto;padding:10px 24px;">
                    FETCH NOW
                </button>
            </form>
        </div>
    @endforelse

    <!-- Pagination -->
    @if ($articles->hasPages())
        <div class="pagination animate-in">
            @if ($articles->onFirstPage())
                <span style="opacity:0.3;">&laquo; PREV</span>
            @else
                <a href="{{ $articles->previousPageUrl() }}">&laquo; PREV</a>
            @endif

            @foreach ($articles->getUrlRange(max(1, $articles->currentPage() - 2), min($articles->lastPage(), $articles->currentPage() + 2)) as $page => $url)
                @if ($page == $articles->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($articles->hasMorePages())
                <a href="{{ $articles->nextPageUrl() }}">NEXT &raquo;</a>
            @else
                <span style="opacity:0.3;">NEXT &raquo;</span>
            @endif
        </div>
    @endif

@endsection
