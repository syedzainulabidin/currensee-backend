@extends('layouts.admin')
@section('title', 'Conversions')

@section('content')

@php $todayCount = \App\Models\ConversionHistory::whereDate('created_at', today())->count(); @endphp

<div class="page-header">
    <div>
        <div class="page-title">Conversion Logs</div>
        <div class="page-subtitle">{{ number_format($conversions->total()) }} total conversions recorded</div>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ number_format($conversions->total()) }}</div>
            <div class="stat-card-label">All Time</div>
        </div>
        <div class="stat-card-icon" style="background:rgba(99,102,241,0.1);">
            <i data-lucide="arrow-left-right" style="color:var(--blue);width:18px;height:18px;"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-card-value text-green">{{ number_format($todayCount) }}</div>
            <div class="stat-card-label">Today</div>
        </div>
        <div class="stat-card-icon" style="background:var(--green-dim);">
            <i data-lucide="trending-up" style="color:var(--green);width:18px;height:18px;"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ $conversions->lastPage() }}</div>
            <div class="stat-card-label">Pages</div>
            <div class="stat-card-delta text-dim">25 per page</div>
        </div>
        <div class="stat-card-icon" style="background:var(--surface3);">
            <i data-lucide="database" style="color:var(--text-2);width:18px;height:18px;"></i>
        </div>
    </div>
</div>

<div class="card">

    {{-- Toolbar --}}
    <form method="GET">
        <div class="toolbar">
            <div class="input-group has-icon-left toolbar-search">
                <div class="input-icon"><i data-lucide="search"></i></div>
                <input type="search" name="search" placeholder="Filter by currency code e.g. USD, EUR…" value="{{ request('search') }}">
            </div>
            <div class="toolbar-filters">
                @if(request('search'))
                    <a href="{{ url()->current() }}" class="btn btn-ghost btn-sm">
                        <i data-lucide="x"></i> Clear
                    </a>
                @endif
                <button type="submit" class="btn btn-primary btn-sm">
                    <i data-lucide="search"></i> Search
                </button>
            </div>
        </div>
    </form>

    {{-- Table --}}
    @if($conversions->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i data-lucide="arrow-left-right"></i></div>
            <div class="empty-title">No conversions found</div>
            <div class="empty-desc">{{ request('search') ? 'No results for "'.request('search').'". Try a different currency code.' : 'Currency conversions will appear here.' }}</div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Amount</th>
                        <th>Result</th>
                        <th>Rate</th>
                        <th>User</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conversions as $c)
                        <tr>
                            <td>
                                <span class="font-mono text-green" style="font-size:14px;font-weight:700;">
                                    {{ $c->from_currency }}
                                </span>
                                <span style="color:var(--text-3);margin:0 4px;font-size:12px;">→</span>
                                <span class="font-mono text-green" style="font-size:14px;font-weight:700;">
                                    {{ $c->to_currency }}
                                </span>
                            </td>
                            <td style="font-weight:600;">{{ number_format($c->amount, 4) }}</td>
                            <td class="text-green font-semibold">{{ number_format($c->converted_amount, 4) }}</td>
                            <td class="font-mono" style="color:var(--text-2);font-size:12px;">{{ number_format($c->rate, 6) }}</td>
                            <td>
                                @if($c->user)
                                    <div style="font-size:13px;font-weight:500;">{{ $c->user->name }}</div>
                                @else
                                    <span style="color:var(--text-3);font-size:12px;">Guest</span>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">
                                <div style="font-size:12.5px;color:var(--text-2);">{{ $c->created_at->format('d M Y') }}</div>
                                <div class="font-mono" style="font-size:11px;color:var(--text-3);">{{ $c->created_at->format('H:i:s') }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($conversions->hasPages())
            <div class="pagination">
                <span>Showing {{ $conversions->firstItem() }}–{{ $conversions->lastItem() }} of {{ number_format($conversions->total()) }}</span>
                <div class="pagination-pages">
                    @if(!$conversions->onFirstPage())
                        <a href="{{ $conversions->previousPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-left" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                    @foreach($conversions->getUrlRange(max(1,$conversions->currentPage()-2), min($conversions->lastPage(),$conversions->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $conversions->currentPage() ? 'current' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($conversions->hasMorePages())
                        <a href="{{ $conversions->nextPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @endif

</div>

@endsection
