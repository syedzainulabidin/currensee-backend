@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <div class="page-title">Dashboard</div>
        <div class="page-subtitle">Welcome back — here's what's happening</div>
    </div>
    <div class="page-actions">
        <span style="font-size:12px;color:var(--text-2);">{{ now()->format('l, d M Y') }}</span>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">

    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-card-label">Total Users</div>
            <div class="stat-card-delta text-green">+{{ $stats['new_users_today'] }} today</div>
        </div>
        <div class="stat-card-icon" style="background:var(--green-dim);">
            <i data-lucide="users" style="color:var(--green);width:18px;height:18px;"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ number_format($stats['conversions_today']) }}</div>
            <div class="stat-card-label">Conversions Today</div>
            <div class="stat-card-delta text-dim">{{ number_format($stats['total_conversions']) }} all time</div>
        </div>
        <div class="stat-card-icon" style="background:rgba(99,102,241,0.1);">
            <i data-lucide="arrow-left-right" style="color:var(--blue);width:18px;height:18px;"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ $stats['active_alerts'] }}</div>
            <div class="stat-card-label">Active Rate Alerts</div>
            <div class="stat-card-delta text-dim">Watching live rates</div>
        </div>
        <div class="stat-card-icon" style="background:var(--yellow-dim);">
            <i data-lucide="bell" style="color:var(--yellow);width:18px;height:18px;"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-card-value {{ $stats['new_feedback'] > 0 ? 'text-red' : '' }}">{{ $stats['new_feedback'] }}</div>
            <div class="stat-card-label">Unread Feedback</div>
            <div class="stat-card-delta {{ $stats['new_feedback'] > 0 ? 'text-red' : 'text-dim' }}">
                {{ $stats['new_feedback'] > 0 ? 'Needs attention' : 'All cleared' }}
            </div>
        </div>
        <div class="stat-card-icon" style="background:{{ $stats['new_feedback'] > 0 ? 'var(--red-dim)' : 'var(--surface3)' }};">
            <i data-lucide="message-square" style="color:{{ $stats['new_feedback'] > 0 ? 'var(--red)' : 'var(--text-2)' }};width:18px;height:18px;"></i>
        </div>
    </div>

</div>

{{-- Recent Tables Side-by-Side --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    {{-- Recent Users --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Recent Registrations</div>
                <div class="card-subtitle">Last 5 new users</div>
            </div>
            <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">
                View all <i data-lucide="arrow-right" style="width:13px;height:13px;"></i>
            </a>
        </div>

        @if($recentUsers->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i data-lucide="users"></i></div>
                <div class="empty-title">No users yet</div>
                <div class="empty-desc">Users will appear here once they register.</div>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Currency</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="avatar" style="background:var(--green-dim);color:var(--green);">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:13px;">{{ $user->name }}</div>
                                            <div style="font-size:11.5px;color:var(--text-2);" class="truncate">{{ Str::limit($user->email, 24) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-green">{{ $user->default_currency }}</span></td>
                                <td style="color:var(--text-2);font-size:12px;white-space:nowrap;">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Recent Conversions --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Recent Conversions</div>
                <div class="card-subtitle">Last 5 currency conversions</div>
            </div>
            <a href="{{ route('admin.conversions') }}" class="btn btn-ghost btn-sm">
                View all <i data-lucide="arrow-right" style="width:13px;height:13px;"></i>
            </a>
        </div>

        @if($recentConversions->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i data-lucide="arrow-left-right"></i></div>
                <div class="empty-title">No conversions yet</div>
                <div class="empty-desc">Conversions will appear here when users start converting.</div>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Pair</th>
                            <th>Amount → Result</th>
                            <th>When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentConversions as $c)
                            <tr>
                                <td>
                                    <span class="font-mono text-green">{{ $c->from_currency }}/{{ $c->to_currency }}</span>
                                    <div style="font-size:11.5px;color:var(--text-2);margin-top:2px;">{{ $c->user?->name ?? 'Guest' }}</div>
                                </td>
                                <td>
                                    <span style="font-weight:600;">{{ number_format($c->amount, 2) }}</span>
                                    <span style="color:var(--text-3);margin:0 3px;">→</span>
                                    <span class="text-green font-semibold">{{ number_format($c->converted_amount, 2) }}</span>
                                </td>
                                <td style="color:var(--text-2);font-size:12px;white-space:nowrap;">{{ $c->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@endsection
