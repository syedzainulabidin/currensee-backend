@extends('layouts.admin')
@section('title', 'Rate Alerts')

@section('content')

    <!-- Header -->
    <div class="animate-in" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:4px;">
            &gt; ALERT_MONITOR
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text);">
            Rate Alerts
        </h1>
    </div>

    <!-- Stats -->
    <div class="animate-in stagger-1" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:16px;">
        <div style="background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div
                style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:var(--green);text-shadow:0 0 10px var(--green);">
                {{ $alerts->where('is_active', true)->whereNull('triggered_at')->count() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                ACTIVE</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:#ffcc00;">
                {{ $alerts->whereNotNull('triggered_at')->count() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                TRIGGERED</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div style="font-family:'Black Ops One',cursive;font-size:1.3rem;color:var(--text-dim);">
                {{ $alerts->where('is_active', false)->count() }}
            </div>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);letter-spacing:1px;">
                INACTIVE</div>
        </div>
    </div>

    <!-- Alerts List -->
    <div class="section-title animate-in stagger-2">ALL ALERTS</div>

    @forelse($alerts as $index => $alert)
        <div class="cyber-card animate-in"
            style="animation-delay:{{ $index * 0.04 }}s;opacity:0;padding:12px 14px;
    {{ $alert->triggered_at ? 'border-color:rgba(255,204,0,0.3);' : '' }}
    {{ !$alert->is_active && !$alert->triggered_at ? 'opacity:0.5;' : '' }}
">

            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">

                <div style="flex:1;min-width:0;">
                    <!-- Pair + Direction -->
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap;">
                        <span
                            style="font-family:'Black Ops One',cursive;font-size:1.1rem;color:var(--green);text-shadow:0 0 8px var(--green);">
                            {{ $alert->from_currency }}/{{ $alert->to_currency }}
                        </span>
                        <span class="badge {{ $alert->direction === 'above' ? 'badge-green' : 'badge-red' }}">
                            {{ $alert->direction === 'above' ? '▲ ABOVE' : '▼ BELOW' }}
                        </span>
                    </div>

                    <!-- Target Rate -->
                    <div style="margin-bottom:6px;">
                        <span style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);">TARGET
                            RATE </span>
                        <span style="font-family:'Black Ops One',cursive;font-size:1rem;color:var(--text);">
                            {{ number_format($alert->target_rate, 6) }}
                        </span>
                    </div>

                    <!-- User -->
                    <div style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                        USER <span style="color:var(--text);">{{ $alert->user?->name ?? 'N/A' }}</span>
                        &nbsp;·&nbsp;
                        SET {{ $alert->created_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Status -->
                <div style="text-align:right;flex-shrink:0;">
                    @if ($alert->triggered_at)
                        <span class="badge badge-yellow">TRIGGERED</span>
                        <div
                            style="font-family:'Share Tech Mono',monospace;font-size:0.5rem;color:var(--text-dim);margin-top:4px;">
                            {{ $alert->triggered_at->format('d M H:i') }}
                        </div>
                    @elseif($alert->is_active)
                        <span class="badge badge-green"
                            style="animation: pulse-glow 2s ease-in-out infinite;">WATCHING</span>
                    @else
                        <span class="badge badge-gray">OFF</span>
                    @endif
                </div>

            </div>
        </div>
    @empty
        <div class="cyber-card" style="text-align:center;padding:40px;">
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:2px;">
                NO_ALERTS_CONFIGURED
            </div>
        </div>
    @endforelse

    <!-- Pagination -->
    @if ($alerts->hasPages())
        <div class="pagination animate-in">
            @if ($alerts->onFirstPage())
                <span style="opacity:0.3;">&laquo; PREV</span>
            @else
                <a href="{{ $alerts->previousPageUrl() }}">&laquo; PREV</a>
            @endif

            @foreach ($alerts->getUrlRange(max(1, $alerts->currentPage() - 2), min($alerts->lastPage(), $alerts->currentPage() + 2)) as $page => $url)
                @if ($page == $alerts->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($alerts->hasMorePages())
                <a href="{{ $alerts->nextPageUrl() }}">NEXT &raquo;</a>
            @else
                <span style="opacity:0.3;">NEXT &raquo;</span>
            @endif
        </div>
    @endif

@endsection
