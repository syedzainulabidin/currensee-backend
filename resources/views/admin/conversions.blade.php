@extends('layouts.admin')
@section('title', 'Conversion Logs')

@section('content')

    <!-- Header -->
    <div class="animate-in" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:4px;">
            &gt; CONVERSION_LOGS
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text);">
            Conversion Logs
        </h1>
    </div>

    <!-- Stats Strip -->
    <div class="animate-in stagger-1" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:16px;">
        <div style="background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div
                style="font-family:'Black Ops One',cursive;font-size:1.2rem;color:var(--green);text-shadow:0 0 10px var(--green);">
                {{ $conversions->total() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                TOTAL</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div style="font-family:'Black Ops One',cursive;font-size:1.2rem;color:#fff;">
                {{ \App\Models\ConversionHistory::whereDate('created_at', today())->count() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                TODAY</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div style="font-family:'Black Ops One',cursive;font-size:1.2rem;color:#ffcc00;">
                {{ $conversions->currentPage() }}/{{ $conversions->lastPage() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                PAGE</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="animate-in stagger-2" style="margin-bottom:16px;">
        <form method="GET">
            <div style="display:flex;gap:8px;">
                <input type="text" name="search" class="cyber-input" placeholder="FILTER BY CURRENCY PAIR... e.g. USD"
                    value="{{ request('search') }}" style="flex:1;">
                <button type="submit" class="btn-green" style="width:auto;padding:10px 14px;">SCAN</button>
            </div>
        </form>
    </div>

    <!-- Logs -->
    <div class="section-title animate-in stagger-2">TRANSACTION LOG</div>

    @forelse($conversions as $index => $c)
        <div class="cyber-card animate-in" style="animation-delay:{{ $index * 0.03 }}s;opacity:0;padding:12px 14px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">

                <div style="flex:1;min-width:0;">
                    <!-- Currency Pair -->
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                        <span
                            style="font-family:'Black Ops One',cursive;font-size:1.1rem;color:var(--green);text-shadow:0 0 8px var(--green);">
                            {{ $c->from_currency }}
                        </span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)"
                            stroke-width="2">
                            <polyline points="17 1 21 5 17 9" />
                            <path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4" />
                            <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                        </svg>
                        <span
                            style="font-family:'Black Ops One',cursive;font-size:1.1rem;color:var(--green);text-shadow:0 0 8px var(--green);">
                            {{ $c->to_currency }}
                        </span>
                    </div>

                    <!-- Amounts -->
                    <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;flex-wrap:wrap;">
                        <span style="font-family:'Black Ops One',cursive;font-size:0.9rem;color:var(--text);">
                            {{ number_format($c->amount, 4) }}
                        </span>
                        <span
                            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);">=</span>
                        <span style="font-family:'Black Ops One',cursive;font-size:0.9rem;color:var(--green);">
                            {{ number_format($c->converted_amount, 4) }}
                        </span>
                    </div>

                    <!-- Rate + User -->
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                            RATE <span style="color:var(--green);">{{ number_format($c->rate, 6) }}</span>
                        </span>
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                            BY <span style="color:var(--text);">{{ $c->user?->name ?? 'GUEST' }}</span>
                        </span>
                    </div>
                </div>

                <!-- Time -->
                <div style="text-align:right;flex-shrink:0;">
                    <div
                        style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;">
                        {{ $c->created_at->format('d M Y') }}
                    </div>
                    <div style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--green);">
                        {{ $c->created_at->format('H:i:s') }}
                    </div>
                </div>

            </div>
        </div>
    @empty
        <div class="cyber-card" style="text-align:center;padding:40px;">
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:2px;">
                NO_CONVERSIONS_FOUND
            </div>
        </div>
    @endforelse

    <!-- Pagination -->
    @if ($conversions->hasPages())
        <div class="pagination animate-in">
            @if ($conversions->onFirstPage())
                <span style="opacity:0.3;">&laquo; PREV</span>
            @else
                <a href="{{ $conversions->previousPageUrl() }}">&laquo; PREV</a>
            @endif

            @foreach ($conversions->getUrlRange(max(1, $conversions->currentPage() - 2), min($conversions->lastPage(), $conversions->currentPage() + 2)) as $page => $url)
                @if ($page == $conversions->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($conversions->hasMorePages())
                <a href="{{ $conversions->nextPageUrl() }}">NEXT &raquo;</a>
            @else
                <span style="opacity:0.3;">NEXT &raquo;</span>
            @endif
        </div>
    @endif

@endsection
