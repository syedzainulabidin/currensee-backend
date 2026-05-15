@extends('layouts.admin')
@section('title', 'Users')

@section('content')

    <!-- Header -->
    <div class="animate-in" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:4px;">
            &gt; USER_MANAGEMENT
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text);">
            Users
        </h1>
    </div>

    <!-- Search -->
    <div class="animate-in stagger-1" style="margin-bottom:16px;">
        <form method="GET" action="{{ route('admin.users') }}">
            <div style="display:flex;gap:8px;">
                <input type="text" name="search" class="cyber-input" placeholder="SEARCH BY NAME OR EMAIL..."
                    value="{{ request('search') }}" style="flex:1;">
                <button type="submit" class="btn-green" style="width:auto;padding:10px 16px;white-space:nowrap;">
                    SCAN
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Strip -->
    <div class="animate-in stagger-2" style="display:flex;gap:8px;margin-bottom:16px;">
        <div style="flex:1;background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div
                style="font-family:'Black Ops One',cursive;font-size:1.4rem;color:var(--green);text-shadow:0 0 10px var(--green);">
                {{ $users->total() }}
            </div>
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;">
                TOTAL</div>
        </div>
        <div style="flex:1;background:var(--surface);border:1px solid var(--border);padding:10px;text-align:center;">
            <div style="font-family:'Black Ops One',cursive;font-size:1.4rem;color:#fff;">
                {{ $users->currentPage() }}/{{ $users->lastPage() }}
            </div>
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;">
                PAGE</div>
        </div>
    </div>

    <!-- Users List -->
    <div class="section-title animate-in stagger-2">REGISTERED USERS</div>

    @forelse($users as $index => $user)
        <div class="cyber-card animate-in" style="animation-delay:{{ $index * 0.04 }}s;opacity:0;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;">

                <!-- User Info -->
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;">
                        <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:0.9rem;color:var(--text);">
                            {{ $user->name }}
                        </span>
                        @if ($user->preferences['suspended'] ?? false)
                            <span class="badge badge-red">SUSPENDED</span>
                        @else
                            <span class="badge badge-green">ACTIVE</span>
                        @endif
                    </div>

                    <div
                        style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);margin-bottom:6px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $user->email }}
                    </div>

                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <div style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                            <span style="color:var(--green);">CCY</span> {{ $user->default_currency }}
                        </div>
                        <div style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);">
                            <span style="color:var(--green);">REG</span> {{ $user->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-outline"
                        style="padding:5px 10px;font-size:0.6rem;">
                        VIEW
                    </a>

                    @if ($user->preferences['suspended'] ?? false)
                        <form method="POST" action="{{ route('admin.users.unsuspend', $user->id) }}">
                            @csrf
                            <button type="submit" class="btn-outline"
                                style="padding:5px 10px;font-size:0.6rem;color:#ffcc00;border-color:rgba(255,204,0,0.4);width:100%;">
                                RESTORE
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}">
                            @csrf
                            <button type="submit" class="btn-danger" style="padding:5px 10px;font-size:0.6rem;">
                                SUSPEND
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                        onsubmit="return confirm('DELETE USER {{ strtoupper($user->name) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="padding:5px 10px;font-size:0.6rem;width:100%;">
                            DELETE
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="cyber-card" style="text-align:center;padding:32px;">
            <div
                style="font-family:'Share Tech Mono',monospace;font-size:0.65rem;color:var(--text-dim);letter-spacing:2px;">
                NO_USERS_FOUND
            </div>
            @if (request('search'))
                <a href="{{ route('admin.users') }}" class="btn-outline" style="margin-top:16px;display:inline-block;">
                    CLEAR FILTER
                </a>
            @endif
        </div>
    @endforelse

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="pagination animate-in">
            {{-- Previous --}}
            @if ($users->onFirstPage())
                <span style="opacity:0.3;">&laquo; PREV</span>
            @else
                <a href="{{ $users->previousPageUrl() }}">&laquo; PREV</a>
            @endif

            {{-- Pages --}}
            @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                @if ($page == $users->currentPage())
                    <span class="active"><span>{{ $page }}</span></span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}">NEXT &raquo;</a>
            @else
                <span style="opacity:0.3;">NEXT &raquo;</span>
            @endif
        </div>
    @endif

@endsection
