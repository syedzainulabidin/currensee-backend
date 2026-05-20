@extends('layouts.admin')
@section('title', 'Users')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Users</div>
        <div class="page-subtitle">{{ $users->total() }} registered {{ Str::plural('user', $users->total()) }}</div>
    </div>
</div>

<div class="card">

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.users') }}">
        <div class="toolbar">
            <div class="input-group has-icon-left toolbar-search">
                <div class="input-icon"><i data-lucide="search"></i></div>
                <input type="search" name="search" placeholder="Search by name or email…" value="{{ request('search') }}">
            </div>
            <div class="toolbar-filters">
                @if(request('search'))
                    <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">
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
    @if($users->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i data-lucide="users"></i></div>
            <div class="empty-title">No users found</div>
            <div class="empty-desc">{{ request('search') ? 'No results for "'.request('search').'". Try a different search.' : 'Users will appear here once they register.' }}</div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="avatar" style="background:var(--green-dim);color:var(--green);font-size:13px;font-weight:700;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;">{{ $user->name }}</div>
                                        <div style="font-size:12px;color:var(--text-2);">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-green">{{ $user->default_currency }}</span></td>
                            <td>
                                @if($user->preferences['suspended'] ?? false)
                                    <span class="badge badge-red">Suspended</span>
                                @else
                                    <span class="badge badge-green">Active</span>
                                @endif
                            </td>
                            <td style="color:var(--text-2);font-size:12.5px;white-space:nowrap;">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary btn-sm">
                                        <i data-lucide="eye"></i> View
                                    </a>

                                    @if($user->preferences['suspended'] ?? false)
                                        <form method="POST" action="{{ route('admin.users.unsuspend', $user->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning-soft btn-sm">
                                                <i data-lucide="rotate-ccw"></i> Restore
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--yellow);">
                                                <i data-lucide="ban"></i> Suspend
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" id="del-user-{{ $user->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger-soft btn-sm"
                                            data-form="del-user-{{ $user->id }}"
                                            data-title="Delete user?"
                                            data-desc="This will permanently delete {{ addslashes($user->name) }} and all their data. This cannot be undone."
                                            onclick="confirmAction(this.dataset.title, this.dataset.desc, this.dataset.form)">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="pagination">
                <span>Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</span>
                <div class="pagination-pages">
                    @if(!$users->onFirstPage())
                        <a href="{{ $users->previousPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-left" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                    @foreach($users->getUrlRange(max(1,$users->currentPage()-2), min($users->lastPage(),$users->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $users->currentPage() ? 'current' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @endif

</div>

@endsection
