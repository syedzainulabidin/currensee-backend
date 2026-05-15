@extends('layouts.admin')
@section('title', 'User — ' . $user->name)

@section('content')

    <!-- Back -->
    <div class="animate-in" style="margin-bottom:16px;">
        <a href="{{ route('admin.users') }}"
            style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);text-decoration:none;letter-spacing:1px;">
            &larr; BACK_TO_USERS
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="cyber-card animate-in stagger-1" style="margin-bottom:16px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap;">

            <div style="flex:1;min-width:0;">
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:6px;">
                    USER_ID::{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                </div>
                <h2
                    style="font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;color:var(--text);margin-bottom:4px;">
                    {{ $user->name }}
                </h2>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--green);margin-bottom:12px;">
                    {{ $user->email }}
                </div>

                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @if ($user->preferences['suspended'] ?? false)
                        <span class="badge badge-red">SUSPENDED</span>
                    @else
                        <span class="badge badge-green">ACTIVE</span>
                    @endif
                    <span class="badge badge-yellow">{{ $user->default_currency }}</span>
                    <span class="badge" style="color:var(--text-dim);border:1px solid rgba(255,255,255,0.1);">
                        JOINED {{ $user->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div style="display:flex;flex-direction:column;gap:6px;">
                @if ($user->preferences['suspended'] ?? false)
                    <form method="POST" action="{{ route('admin.users.unsuspend', $user->id) }}">
                        @csrf
                        <button type="submit" class="btn-outline"
                            style="font-size:0.65rem;padding:8px 14px;color:#ffcc00;border-color:rgba(255,204,0,0.4);">
                            RESTORE
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}">
                        @csrf
                        <button type="submit" class="btn-danger" style="font-size:0.65rem;padding:8px 14px;">
                            SUSPEND
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                    onsubmit="return confirm('PERMANENTLY DELETE {{ strtoupper($user->name) }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger" style="font-size:0.65rem;padding:8px 14px;width:100%;">
                        DELETE
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Preferences -->
    <div class="section-title animate-in stagger-2">PREFERENCES</div>
    <div class="cyber-card animate-in stagger-2" style="margin-bottom:16px;">
        @php $prefs = $user->preferences ?? []; @endphp
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    DEFAULT CCY</div>
                <div style="font-family:'Black Ops One',cursive;font-size:1.1rem;color:var(--green);">
                    {{ $user->default_currency }}</div>
            </div>
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    PUSH ALERTS</div>
                <div>
                    @if ($prefs['notifications']['push_enabled'] ?? true)
                        <span class="badge badge-green">ENABLED</span>
                    @else
                        <span class="badge badge-red">DISABLED</span>
                    @endif
                </div>
            </div>
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    EMAIL ALERTS</div>
                <div>
                    @if ($prefs['notifications']['rate_alerts_email'] ?? false)
                        <span class="badge badge-green">ENABLED</span>
                    @else
                        <span class="badge badge-gray">DISABLED</span>
                    @endif
                </div>
            </div>
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    NEWS DIGEST</div>
                <div>
                    @if ($prefs['notifications']['news_digest'] ?? false)
                        <span class="badge badge-green">ENABLED</span>
                    @else
                        <span class="badge badge-gray">DISABLED</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Conversions -->
    <div class="section-title animate-in stagger-3">RECENT CONVERSIONS</div>
    <div class="cyber-card animate-in stagger-3" style="padding:0;overflow:hidden;margin-bottom:16px;">
        @if ($user->conversionHistories->isEmpty())
            <div
                style="padding:20px;text-align:center;font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);">
                NO_CONVERSIONS_YET
            </div>
        @else
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>PAIR</th>
                        <th>AMOUNT</th>
                        <th>RESULT</th>
                        <th>DATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->conversionHistories as $c)
                        <tr>
                            <td>
                                <span style="font-family:'Black Ops One',cursive;font-size:0.8rem;color:var(--green);">
                                    {{ $c->from_currency }}/{{ $c->to_currency }}
                                </span>
                            </td>
                            <td style="font-family:'Black Ops One',cursive;font-size:0.8rem;">
                                {{ number_format($c->amount, 2) }}
                            </td>
                            <td style="font-family:'Black Ops One',cursive;font-size:0.8rem;color:var(--green);">
                                {{ number_format($c->converted_amount, 2) }}
                            </td>
                            <td style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                                {{ $c->created_at->format('d/m H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Rate Alerts -->
    <div class="section-title animate-in stagger-4">RATE ALERTS</div>
    <div class="cyber-card animate-in stagger-4" style="padding:0;overflow:hidden;margin-bottom:20px;">
        @if ($user->rateAlerts->isEmpty())
            <div
                style="padding:20px;text-align:center;font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);">
                NO_ALERTS_SET
            </div>
        @else
            <table class="cyber-table">
                <thead>
                    <tr>
                        <th>PAIR</th>
                        <th>TARGET</th>
                        <th>DIR</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->rateAlerts as $alert)
                        <tr>
                            <td>
                                <span style="font-family:'Black Ops One',cursive;font-size:0.8rem;color:var(--green);">
                                    {{ $alert->from_currency }}/{{ $alert->to_currency }}
                                </span>
                            </td>
                            <td style="font-family:'Black Ops One',cursive;font-size:0.8rem;">
                                {{ number_format($alert->target_rate, 4) }}
                            </td>
                            <td>
                                <span class="badge {{ $alert->direction === 'above' ? 'badge-green' : 'badge-red' }}">
                                    {{ strtoupper($alert->direction) }}
                                </span>
                            </td>
                            <td>
                                @if ($alert->triggered_at)
                                    <span class="badge badge-yellow">TRIGGERED</span>
                                @elseif($alert->is_active)
                                    <span class="badge badge-green">ACTIVE</span>
                                @else
                                    <span class="badge badge-gray">OFF</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection
