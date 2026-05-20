@extends('layouts.admin')
@section('title', $user->name)

@section('content')

{{-- Back --}}
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm" style="padding-left:0;">
        <i data-lucide="arrow-left"></i> Back to Users
    </a>
</div>

{{-- Profile Header --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap;">

            <div style="display:flex;align-items:center;gap:16px;">
                <div class="avatar" style="width:56px;height:56px;font-size:22px;background:var(--green-dim);color:var(--green);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:20px;font-weight:800;letter-spacing:-0.5px;margin-bottom:4px;">{{ $user->name }}</div>
                    <div style="font-size:13px;color:var(--text-2);margin-bottom:10px;">{{ $user->email }}</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                        @if($user->preferences['suspended'] ?? false)
                            <span class="badge badge-red">Suspended</span>
                        @else
                            <span class="badge badge-green">Active</span>
                        @endif
                        <span class="badge badge-blue">{{ $user->default_currency }}</span>
                        <span class="badge badge-gray">Joined {{ $user->created_at->format('d M Y') }}</span>
                        <span class="badge badge-gray">ID #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:8px;align-items:center;">
                @if($user->preferences['suspended'] ?? false)
                    <form method="POST" action="{{ route('admin.users.unsuspend', $user->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning-soft">
                            <i data-lucide="rotate-ccw"></i> Restore Access
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-ghost" style="color:var(--yellow);">
                            <i data-lucide="ban"></i> Suspend
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" id="del-user-detail" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger-soft"
                        data-form="del-user-detail"
                        data-title="Delete user account?"
                        data-desc="This will permanently delete {{ addslashes($user->name) }} and all their data including conversions, alerts and feedback."
                        onclick="confirmAction(this.dataset.title, this.dataset.desc, this.dataset.form)">
                        <i data-lucide="trash-2"></i> Delete User
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Preferences + Stats --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    {{-- Preferences --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Preferences</div>
        </div>
        <div class="card-body">
            @php $prefs = $user->preferences ?? []; @endphp
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">Default Currency</span>
                    <span class="badge badge-green">{{ $user->default_currency }}</span>
                </div>
                <div style="border-top:1px solid var(--border);"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">Push Notifications</span>
                    @if($prefs['notifications']['push_enabled'] ?? true)
                        <span class="badge badge-green">Enabled</span>
                    @else
                        <span class="badge badge-gray">Disabled</span>
                    @endif
                </div>
                <div style="border-top:1px solid var(--border);"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">Email Rate Alerts</span>
                    @if($prefs['notifications']['rate_alerts_email'] ?? false)
                        <span class="badge badge-green">Enabled</span>
                    @else
                        <span class="badge badge-gray">Disabled</span>
                    @endif
                </div>
                <div style="border-top:1px solid var(--border);"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">News Digest</span>
                    @if($prefs['notifications']['news_digest'] ?? false)
                        <span class="badge badge-green">Enabled</span>
                    @else
                        <span class="badge badge-gray">Disabled</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Activity</div>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">Total Conversions</span>
                    <span style="font-size:18px;font-weight:800;color:var(--text);">{{ $user->conversionHistories->count() }}</span>
                </div>
                <div style="border-top:1px solid var(--border);"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">Rate Alerts</span>
                    <span style="font-size:18px;font-weight:800;color:var(--text);">{{ $user->rateAlerts->count() }}</span>
                </div>
                <div style="border-top:1px solid var(--border);"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">Member Since</span>
                    <span style="font-size:13px;font-weight:600;color:var(--text);">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div style="border-top:1px solid var(--border);"></div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:13px;color:var(--text-2);">Last Active</span>
                    <span style="font-size:13px;font-weight:600;color:var(--text);">{{ $user->updated_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Recent Conversions --}}
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title">Recent Conversions</div>
        <div class="card-subtitle">Last 10 conversions by this user</div>
    </div>
    @if($user->conversionHistories->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i data-lucide="arrow-left-right"></i></div>
            <div class="empty-title">No conversions yet</div>
            <div class="empty-desc">This user hasn't performed any currency conversions.</div>
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
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->conversionHistories as $c)
                        <tr>
                            <td><span class="font-mono text-green">{{ $c->from_currency }} → {{ $c->to_currency }}</span></td>
                            <td style="font-weight:600;">{{ number_format($c->amount, 4) }}</td>
                            <td class="text-green font-semibold">{{ number_format($c->converted_amount, 4) }}</td>
                            <td style="color:var(--text-2);font-size:12px;" class="font-mono">{{ number_format($c->rate, 6) }}</td>
                            <td style="color:var(--text-2);font-size:12px;white-space:nowrap;">{{ $c->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Rate Alerts --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">Rate Alerts</div>
        <div class="card-subtitle">Alerts configured by this user</div>
    </div>
    @if($user->rateAlerts->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i data-lucide="bell-off"></i></div>
            <div class="empty-title">No alerts configured</div>
            <div class="empty-desc">This user hasn't set up any rate alerts.</div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pair</th>
                        <th>Target Rate</th>
                        <th>Direction</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->rateAlerts as $alert)
                        <tr>
                            <td><span class="font-mono text-green">{{ $alert->from_currency }}/{{ $alert->to_currency }}</span></td>
                            <td style="font-weight:600;" class="font-mono">{{ number_format($alert->target_rate, 6) }}</td>
                            <td>
                                @if($alert->direction === 'above')
                                    <span class="badge badge-green">▲ Above</span>
                                @else
                                    <span class="badge badge-red">▼ Below</span>
                                @endif
                            </td>
                            <td>
                                @if($alert->triggered_at)
                                    <span class="badge badge-yellow">Triggered</span>
                                @elseif($alert->is_active)
                                    <span class="badge badge-green">Watching</span>
                                @else
                                    <span class="badge badge-gray">Inactive</span>
                                @endif
                            </td>
                            <td style="color:var(--text-2);font-size:12px;white-space:nowrap;">{{ $alert->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
