@extends('layouts.admin')
@section('title', 'Feedback')

@section('content')

    <!-- Header -->
    <div class="animate-in" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:4px;">
            &gt; FEEDBACK_INBOX
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text);">
            Feedback
        </h1>
    </div>

    <!-- Status Counts -->
    <div class="animate-in stagger-1" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:16px;">
        <a href="{{ route('admin.feedback', ['status' => 'new']) }}" style="text-decoration:none;">
            <div
                style="background:var(--surface);border:1px solid {{ request('status') === 'new' ? 'var(--border-hot)' : 'var(--border)' }};padding:10px;text-align:center;transition:all 0.2s;">
                <div
                    style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:#ff4444;text-shadow:0 0 10px rgba(255,68,68,0.4);">
                    {{ $counts['new'] }}
                </div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                    NEW</div>
            </div>
        </a>
        <a href="{{ route('admin.feedback', ['status' => 'reviewed']) }}" style="text-decoration:none;">
            <div
                style="background:var(--surface);border:1px solid {{ request('status') === 'reviewed' ? 'var(--border-hot)' : 'var(--border)' }};padding:10px;text-align:center;transition:all 0.2s;">
                <div style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:#ffcc00;">
                    {{ $counts['reviewed'] }}
                </div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                    REVIEWED</div>
            </div>
        </a>
        <a href="{{ route('admin.feedback', ['status' => 'resolved']) }}" style="text-decoration:none;">
            <div
                style="background:var(--surface);border:1px solid {{ request('status') === 'resolved' ? 'var(--border-hot)' : 'var(--border)' }};padding:10px;text-align:center;transition:all 0.2s;">
                <div
                    style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:var(--green);text-shadow:0 0 10px var(--green);">
                    {{ $counts['resolved'] }}
                </div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                    RESOLVED</div>
            </div>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="animate-in stagger-2" style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
        <a href="{{ route('admin.feedback') }}"
            class="{{ !request('status') && !request('type') ? 'btn-green' : 'btn-outline' }}"
            style="font-size:0.6rem;padding:7px 12px;">ALL</a>
        <a href="{{ route('admin.feedback', ['type' => 'bug']) }}"
            class="{{ request('type') === 'bug' ? 'btn-green' : 'btn-outline' }}"
            style="font-size:0.6rem;padding:7px 12px;">BUGS</a>
        <a href="{{ route('admin.feedback', ['type' => 'suggestion']) }}"
            class="{{ request('type') === 'suggestion' ? 'btn-green' : 'btn-outline' }}"
            style="font-size:0.6rem;padding:7px 12px;">SUGGESTIONS</a>
        <a href="{{ route('admin.feedback', ['type' => 'general']) }}"
            class="{{ request('type') === 'general' ? 'btn-green' : 'btn-outline' }}"
            style="font-size:0.6rem;padding:7px 12px;">GENERAL</a>
    </div>

    <!-- Feedback List -->
    <div class="section-title animate-in stagger-2">SUBMISSIONS</div>

    @forelse($feedback as $index => $item)
        <div class="cyber-card animate-in"
            style="animation-delay:{{ $index * 0.04 }}s;opacity:0;padding:14px;
    {{ $item->status === 'new' ? 'border-color:rgba(255,68,68,0.35);' : '' }}
    {{ $item->status === 'resolved' ? 'opacity:0.7;' : '' }}
">

            <!-- Top Row -->
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:10px;">
                <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
                    <!-- Type Badge -->
                    @if ($item->type === 'bug')
                        <span class="badge badge-red">🐛 BUG</span>
                    @elseif($item->type === 'suggestion')
                        <span class="badge badge-yellow">💡 SUGGESTION</span>
                    @else
                        <span class="badge badge-gray">GENERAL</span>
                    @endif

                    <!-- Status Badge -->
                    @if ($item->status === 'new')
                        <span class="badge badge-red">NEW</span>
                    @elseif($item->status === 'reviewed')
                        <span class="badge badge-yellow">REVIEWED</span>
                    @else
                        <span class="badge badge-green">RESOLVED</span>
                    @endif
                </div>

                <!-- Date -->
                <div style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);flex-shrink:0;">
                    {{ $item->created_at->format('d M Y') }}
                </div>
            </div>

            <!-- Message -->
            <div
                style="font-size:0.82rem;color:var(--text);line-height:1.5;margin-bottom:10px;padding:10px;background:var(--surface2);border-left:2px solid var(--border);">
                {{ $item->message }}
            </div>

            <!-- User -->
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);margin-bottom:12px;">
                FROM <span style="color:var(--text);">{{ $item->user?->name ?? 'GUEST' }}</span>
                @if ($item->user)
                    &nbsp;·&nbsp; {{ $item->user->email }}
                @endif
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                @if ($item->status !== 'reviewed')
                    <form method="POST" action="{{ route('admin.feedback.status', $item->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="reviewed">
                        <button type="submit" class="btn-outline"
                            style="padding:5px 10px;font-size:0.6rem;color:#ffcc00;border-color:rgba(255,204,0,0.4);">
                            MARK REVIEWED
                        </button>
                    </form>
                @endif

                @if ($item->status !== 'resolved')
                    <form method="POST" action="{{ route('admin.feedback.status', $item->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="btn-outline" style="padding:5px 10px;font-size:0.6rem;">
                            RESOLVE
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.feedback.destroy', $item->id) }}"
                    onsubmit="return confirm('DELETE THIS FEEDBACK?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="padding:5px 10px;font-size:0.6rem;">
                        DELETE
                    </button>
                </form>
            </div>

        </div>
    @empty
        <div class="cyber-card" style="text-align:center;padding:40px;">
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:2px;">
                NO_FEEDBACK_FOUND
            </div>
            @if (request('status') || request('type'))
                <a href="{{ route('admin.feedback') }}" class="btn-outline" style="margin-top:16px;display:inline-block;">
                    CLEAR FILTER
                </a>
            @endif
        </div>
    @endforelse

    <!-- Pagination -->
    @if ($feedback->hasPages())
        <div class="pagination animate-in">
            @if ($feedback->onFirstPage())
                <span style="opacity:0.3;">&laquo; PREV</span>
            @else
                <a href="{{ $feedback->previousPageUrl() }}">&laquo; PREV</a>
            @endif

            @foreach ($feedback->getUrlRange(max(1, $feedback->currentPage() - 2), min($feedback->lastPage(), $feedback->currentPage() + 2)) as $page => $url)
                @if ($page == $feedback->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($feedback->hasMorePages())
                <a href="{{ $feedback->nextPageUrl() }}">NEXT &raquo;</a>
            @else
                <span style="opacity:0.3;">NEXT &raquo;</span>
            @endif
        </div>
    @endif

@endsection
