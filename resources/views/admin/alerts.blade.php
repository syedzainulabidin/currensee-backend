@extends('layouts.admin')
@section('title', 'Rate Alerts')

@section('content')

@php
    $activeCount    = \App\Models\RateAlert::where('is_active', true)->whereNull('triggered_at')->count();
    $triggeredCount = \App\Models\RateAlert::whereNotNull('triggered_at')->count();
    $inactiveCount  = \App\Models\RateAlert::where('is_active', false)->whereNull('triggered_at')->count();
@endphp

<div class="page-header">
    <div>
        <div class="page-title">Rate Alerts</div>
        <div class="page-subtitle">{{ $alerts->total() }} alerts configured by users</div>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="stat-card-value text-green">{{ $activeCount }}</div>
            <div class="stat-card-label">Watching</div>
            <div class="stat-card-delta text-dim">Active &amp; waiting</div>
        </div>
        <div class="stat-card-icon" style="background:var(--green-dim);">
            <i data-lucide="bell" style="color:var(--green);width:18px;height:18px;"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-card-value text-yellow">{{ $triggeredCount }}</div>
            <div class="stat-card-label">Triggered</div>
            <div class="stat-card-delta text-dim">Target rate hit</div>
        </div>
        <div class="stat-card-icon" style="background:var(--yellow-dim);">
            <i data-lucide="zap" style="color:var(--yellow);width:18px;height:18px;"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-card-value text-dim">{{ $inactiveCount }}</div>
            <div class="stat-card-label">Inactive</div>
            <div class="stat-card-delta text-dim">Turned off</div>
        </div>
        <div class="stat-card-icon" style="background:var(--surface3);">
            <i data-lucide="bell-off" style="color:var(--text-2);width:18px;height:18px;"></i>
        </div>
    </div>
</div>

<div class="card">

    @if($alerts->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i data-lucide="bell"></i></div>
            <div class="empty-title">No rate alerts</div>
            <div class="empty-desc">Users haven't configured any rate alerts yet.</div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Target Rate</th>
                        <th>Direction</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alerts as $alert)
                        <tr style="{{ !$alert->is_active && !$alert->triggered_at ? 'opacity:0.5;' : '' }}">
                            <td>
                                <span class="font-mono text-green" style="font-size:15px;font-weight:700;">
                                    {{ $alert->from_currency }}/{{ $alert->to_currency }}
                                </span>
                            </td>
                            <td>
                                <span class="font-mono" style="font-size:14px;font-weight:700;">
                                    {{ number_format($alert->target_rate, 6) }}
                                </span>
                            </td>
                            <td>
                                @if($alert->direction === 'above')
                                    <span class="badge badge-green">▲ Above</span>
                                @else
                                    <span class="badge badge-red">▼ Below</span>
                                @endif
                            </td>
                            <td>
                                @if($alert->user)
                                    <div style="font-weight:600;font-size:13px;">{{ $alert->user->name }}</div>
                                @else
                                    <span style="color:var(--text-3);font-size:12px;">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($alert->triggered_at)
                                    <span class="badge badge-yellow">Triggered</span>
                                    <div style="font-size:11px;color:var(--text-3);margin-top:3px;">{{ $alert->triggered_at->format('d M H:i') }}</div>
                                @elseif($alert->is_active)
                                    <span class="badge badge-green">Watching</span>
                                @else
                                    <span class="badge badge-gray">Off</span>
                                @endif
                            </td>
                            <td style="color:var(--text-2);font-size:12px;white-space:nowrap;">{{ $alert->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($alerts->hasPages())
            <div class="pagination">
                <span>Showing {{ $alerts->firstItem() }}–{{ $alerts->lastItem() }} of {{ $alerts->total() }}</span>
                <div class="pagination-pages">
                    @if(!$alerts->onFirstPage())
                        <a href="{{ $alerts->previousPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-left" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                    @foreach($alerts->getUrlRange(max(1,$alerts->currentPage()-2), min($alerts->lastPage(),$alerts->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $alerts->currentPage() ? 'current' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($alerts->hasMorePages())
                        <a href="{{ $alerts->nextPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @endif

</div>

@endsection
