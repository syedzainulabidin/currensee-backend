@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

    <!-- Page Header -->
    <div class="animate-in" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:4px;">
            &gt; SYSTEM_OVERVIEW
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text);">
            Dashboard
        </h1>
    </div>

    <!-- Stats Grid -->
    <div class="section-title animate-in stagger-1">LIVE METRICS</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">

        <div class="cyber-card animate-in stagger-1">
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">TOTAL USERS</div>
        </div>

        <div class="cyber-card animate-in stagger-2">
            <div class="stat-value" style="color:#fff;text-shadow:0 0 10px rgba(255,255,255,0.3);">
                +{{ $stats['new_users_today'] }}
            </div>
            <div class="stat-label">NEW TODAY</div>
        </div>

        <div class="cyber-card animate-in stagger-3">
            <div class="stat-value">{{ number_format($stats['conversions_today']) }}</div>
            <div class="stat-label">CONVERSIONS TODAY</div>
        </div>

        <div class="cyber-card animate-in stagger-4">
            <div class="stat-value" style="font-size:1.5rem;">
                {{ number_format($stats['total_conversions']) }}
            </div>
            <div class="stat-label">TOTAL CONVERSIONS</div>
        </div>

        <div class="cyber-card animate-in stagger-5">
            <div class="stat-value" style="font-size:1.5rem;color:#ffcc00;text-shadow:0 0 15px rgba(255,204,0,0.4);">
                {{ $stats['active_alerts'] }}
            </div>
            <div class="stat-label">ACTIVE ALERTS</div>
        </div>

        <div class="cyber-card animate-in stagger-6"
            style="border-color:{{ $stats['new_feedback'] > 0 ? 'rgba(255,68,68,0.4)' : 'var(--border)' }}">
            <div class="stat-value"
                style="font-size:1.5rem;color:{{ $stats['new_feedback'] > 0 ? '#ff4444' : 'var(--green)' }};text-shadow:0 0 15px {{ $stats['new_feedback'] > 0 ? 'rgba(255,68,68,0.4)' : 'var(--green-glow)' }};">
                {{ $stats['new_feedback'] }}
            </div>
            <div class="stat-label">NEW FEEDBACK</div>
        </div>

    </div>

    <!-- Recent Users -->
    <div class="section-title animate-in">RECENT USERS</div>

    <div class="cyber-card animate-in" style="padding:0;overflow:hidden;">
        @if ($recentUsers->isEmpty())
            <div
                style="padding:20px;text-align:center;font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);">
                NO_DATA_FOUND
            </div>
        @else
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>CURRENCY</th>
                        <th>JOINED</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentUsers as $user)
                        <tr>
                            <td>
                                <div style="font-weight:500;color:var(--text);font-size:0.8rem;">
                                    {{ Str::limit($user->name, 12) }}</div>
                                <div
                                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                                    {{ Str::limit($user->email, 16) }}</div>
                            </td>
                            <td>
                                <span class="badge badge-green">{{ $user->default_currency }}</span>
                            </td>
                            <td style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);">
                                {{ $user->created_at->diffForHumans(null, true) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <a href="{{ route('admin.users') }}" class="btn-outline"
        style="width:100%;text-align:center;margin-bottom:20px;display:block;">
        VIEW ALL USERS →
    </a>

    <!-- Recent Conversions -->
    <div class="section-title animate-in">RECENT CONVERSIONS</div>

    <div class="cyber-card animate-in" style="padding:0;overflow:hidden;">
        @if ($recentConversions->isEmpty())
            <div
                style="padding:20px;text-align:center;font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);">
                NO_DATA_FOUND
            </div>
        @else
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>PAIR</th>
                        <th>AMOUNT</th>
                        <th>TIME</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentConversions as $c)
                        <tr>
                            <td>
                                <span style="font-family:'Black Ops One',cursive;font-size:0.8rem;color:var(--green);">
                                    {{ $c->from_currency }}/{{ $c->to_currency }}
                                </span>
                                <div
                                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                                    {{ $c->user?->name ?? 'GUEST' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-family:'Black Ops One',cursive;font-size:0.85rem;color:var(--text);">
                                    {{ number_format($c->amount, 2) }}
                                </div>
                                <div style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--green);">
                                    = {{ number_format($c->converted_amount, 2) }}
                                </div>
                            </td>
                            <td style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);">
                                {{ $c->created_at->diffForHumans(null, true) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <a href="{{ route('admin.conversions') }}" class="btn-outline"
        style="width:100%;text-align:center;margin-bottom:20px;display:block;">
        VIEW ALL LOGS →
    </a>

@endsection
