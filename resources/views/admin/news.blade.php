@extends('layouts.admin')
@section('title', 'News Feed')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">News Feed</div>
        <div class="page-subtitle">{{ $articles->total() }} articles in the database</div>
    </div>
    <div class="page-actions">
        <form method="POST" action="{{ route('admin.news.refresh') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i data-lucide="refresh-cw"></i> Refresh Feed
            </button>
        </form>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ $articles->total() }}</div>
            <div class="stat-card-label">Total Articles</div>
        </div>
        <div class="stat-card-icon" style="background:rgba(99,102,241,0.1);">
            <i data-lucide="newspaper" style="color:var(--blue);width:18px;height:18px;"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ \App\Models\NewsArticle::whereDate('created_at', today())->count() }}</div>
            <div class="stat-card-label">Fetched Today</div>
        </div>
        <div class="stat-card-icon" style="background:var(--green-dim);">
            <i data-lucide="calendar" style="color:var(--green);width:18px;height:18px;"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ $articles->lastPage() }}</div>
            <div class="stat-card-label">Pages</div>
        </div>
        <div class="stat-card-icon" style="background:var(--surface3);">
            <i data-lucide="layers" style="color:var(--text-2);width:18px;height:18px;"></i>
        </div>
    </div>
</div>

<div class="card">

    @if($articles->isEmpty())
        <div class="empty-state" style="padding:64px 20px;">
            <div class="empty-icon" style="width:56px;height:56px;">
                <i data-lucide="newspaper" style="width:26px;height:26px;"></i>
            </div>
            <div class="empty-title">No articles yet</div>
            <div class="empty-desc" style="margin-bottom:24px;">Click "Refresh Feed" above to fetch the latest currency news.</div>
            <form method="POST" action="{{ route('admin.news.refresh') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="refresh-cw"></i> Fetch Now
                </button>
            </form>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Source</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                        <tr>
                            <td style="max-width:380px;">
                                <div style="font-weight:600;font-size:13.5px;line-height:1.4;margin-bottom:4px;">
                                    {{ Str::limit($article->title, 80) }}
                                </div>
                                @if($article->description)
                                    <div style="font-size:12px;color:var(--text-2);line-height:1.4;">
                                        {{ Str::limit($article->description, 100) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($article->source)
                                    <span class="badge badge-gray">{{ Str::limit(strtoupper($article->source), 18) }}</span>
                                @else
                                    <span style="color:var(--text-3);font-size:12px;">—</span>
                                @endif
                            </td>
                            <td style="color:var(--text-2);font-size:12px;white-space:nowrap;">
                                {{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}
                                @if($article->published_at)
                                    <div style="color:var(--text-3);font-size:11px;">{{ $article->published_at->format('H:i') }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    @if($article->url)
                                        <a href="{{ $article->url }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">
                                            <i data-lucide="external-link"></i> Open
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.news.destroy', $article->id) }}" id="del-news-{{ $article->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger-soft btn-sm"
                                            data-form="del-news-{{ $article->id }}"
                                            data-title="Delete article?"
                                            data-desc="This article will be permanently removed from the feed."
                                            onclick="confirmAction(this.dataset.title, this.dataset.desc, this.dataset.form)">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($articles->hasPages())
            <div class="pagination">
                <span>Showing {{ $articles->firstItem() }}–{{ $articles->lastItem() }} of {{ $articles->total() }}</span>
                <div class="pagination-pages">
                    @if(!$articles->onFirstPage())
                        <a href="{{ $articles->previousPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-left" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                    @foreach($articles->getUrlRange(max(1,$articles->currentPage()-2), min($articles->lastPage(),$articles->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $articles->currentPage() ? 'current' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @endif

</div>

@endsection
