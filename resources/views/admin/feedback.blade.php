@extends('layouts.admin')
@section('title', 'Feedback')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Feedback</div>
        <div class="page-subtitle">User-submitted feedback and bug reports</div>
    </div>
</div>

{{-- Status Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;">

    <a href="{{ route('admin.feedback', ['status' => 'new'] + (request('type') ? ['type' => request('type')] : [])) }}" style="text-decoration:none;">
        <div class="stat-card" style="{{ request('status') === 'new' ? 'border-color:rgba(239,68,68,0.4);' : '' }}">
            <div>
                <div class="stat-card-value text-red">{{ $counts['new'] }}</div>
                <div class="stat-card-label">New</div>
                <div class="stat-card-delta text-dim">Awaiting review</div>
            </div>
            <div class="stat-card-icon" style="background:var(--red-dim);">
                <i data-lucide="inbox" style="color:var(--red);width:18px;height:18px;"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.feedback', ['status' => 'reviewed'] + (request('type') ? ['type' => request('type')] : [])) }}" style="text-decoration:none;">
        <div class="stat-card" style="{{ request('status') === 'reviewed' ? 'border-color:rgba(245,158,11,0.4);' : '' }}">
            <div>
                <div class="stat-card-value text-yellow">{{ $counts['reviewed'] }}</div>
                <div class="stat-card-label">Reviewed</div>
                <div class="stat-card-delta text-dim">In progress</div>
            </div>
            <div class="stat-card-icon" style="background:var(--yellow-dim);">
                <i data-lucide="eye" style="color:var(--yellow);width:18px;height:18px;"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.feedback', ['status' => 'resolved'] + (request('type') ? ['type' => request('type')] : [])) }}" style="text-decoration:none;">
        <div class="stat-card" style="{{ request('status') === 'resolved' ? 'border-color:var(--green-border);' : '' }}">
            <div>
                <div class="stat-card-value text-green">{{ $counts['resolved'] }}</div>
                <div class="stat-card-label">Resolved</div>
                <div class="stat-card-delta text-dim">Completed</div>
            </div>
            <div class="stat-card-icon" style="background:var(--green-dim);">
                <i data-lucide="check-circle" style="color:var(--green);width:18px;height:18px;"></i>
            </div>
        </div>
    </a>

</div>

<div class="card">

    {{-- Toolbar --}}
    <div class="toolbar">
        <div class="toolbar-filters">
            <a href="{{ route('admin.feedback', request('status') ? ['status' => request('status')] : []) }}"
               class="filter-chip {{ !request('type') ? 'active' : '' }}">All Types</a>
            <a href="{{ route('admin.feedback', array_merge(request()->only('status'), ['type' => 'bug'])) }}"
               class="filter-chip {{ request('type') === 'bug' ? 'active' : '' }}">
                <i data-lucide="bug" style="width:11px;height:11px;"></i> Bug
            </a>
            <a href="{{ route('admin.feedback', array_merge(request()->only('status'), ['type' => 'feature'])) }}"
               class="filter-chip {{ request('type') === 'feature' ? 'active' : '' }}">
                <i data-lucide="lightbulb" style="width:11px;height:11px;"></i> Feature
            </a>
            <a href="{{ route('admin.feedback', array_merge(request()->only('status'), ['type' => 'suggestion'])) }}"
               class="filter-chip {{ request('type') === 'suggestion' ? 'active' : '' }}">
                <i data-lucide="message-circle" style="width:11px;height:11px;"></i> Suggestion
            </a>
            <a href="{{ route('admin.feedback', array_merge(request()->only('status'), ['type' => 'general'])) }}"
               class="filter-chip {{ request('type') === 'general' ? 'active' : '' }}">General</a>
            <a href="{{ route('admin.feedback', array_merge(request()->only('status'), ['type' => 'other'])) }}"
               class="filter-chip {{ request('type') === 'other' ? 'active' : '' }}">Other</a>
        </div>

        @if(request('status') || request('type'))
            <a href="{{ route('admin.feedback') }}" class="btn btn-ghost btn-sm" style="margin-left:auto;">
                <i data-lucide="x"></i> Clear filters
            </a>
        @endif
    </div>

    {{-- Table --}}
    @if($feedback->isEmpty())
        <div class="empty-state">
            <div class="empty-icon"><i data-lucide="message-square"></i></div>
            <div class="empty-title">No feedback found</div>
            <div class="empty-desc">{{ request('status') || request('type') ? 'Try changing the filters above.' : 'Feedback submitted by users will appear here.' }}</div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Type</th>
                        <th>Rating</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedback as $item)
                        <tr style="{{ $item->status === 'resolved' ? 'opacity:0.6;' : '' }}">
                            <td>
                                <div style="font-weight:600;font-size:13px;">{{ $item->user?->name ?? 'Guest' }}</div>
                                @if($item->user)
                                    <div style="font-size:11.5px;color:var(--text-2);">{{ $item->user->email }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $typeMap = [
                                        'bug'        => ['label' => 'Bug',        'class' => 'badge-red'],
                                        'feature'    => ['label' => 'Feature',    'class' => 'badge-blue'],
                                        'suggestion' => ['label' => 'Suggestion', 'class' => 'badge-yellow'],
                                        'general'    => ['label' => 'General',    'class' => 'badge-gray'],
                                        'other'      => ['label' => 'Other',      'class' => 'badge-gray'],
                                    ];
                                    $typeInfo = $typeMap[$item->type] ?? ['label' => ucfirst($item->type), 'class' => 'badge-gray'];
                                @endphp
                                <span class="badge {{ $typeInfo['class'] }}">{{ $typeInfo['label'] }}</span>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:2px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span style="font-size:12px;color:{{ $i <= ($item->rating ?? 0) ? 'var(--yellow)' : 'var(--surface3)' }};">★</span>
                                    @endfor
                                </div>
                            </td>
                            <td style="max-width:260px;">
                                <div style="font-size:13px;line-height:1.5;color:var(--text-2);">{{ Str::limit($item->message, 60) }}</div>
                                @if(strlen($item->message) > 60)
                                    <button type="button" class="btn btn-ghost btn-sm"
                                        style="padding:2px 0;font-size:11.5px;color:var(--green);margin-top:3px;"
                                        onclick="openMsgModal(this)"
                                        data-message="{{ e($item->message) }}"
                                        data-user="{{ $item->user?->name ?? 'Guest' }}"
                                        data-type="{{ $typeInfo['label'] }}"
                                        data-rating="{{ $item->rating ?? 0 }}"
                                        data-date="{{ $item->created_at->format('d M Y, H:i') }}">
                                        Read full message →
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'new')
                                    <span class="badge badge-red">New</span>
                                @elseif($item->status === 'reviewed')
                                    <span class="badge badge-yellow">Reviewed</span>
                                @else
                                    <span class="badge badge-green">Resolved</span>
                                @endif
                            </td>
                            <td style="color:var(--text-2);font-size:12px;white-space:nowrap;">{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    @if($item->status !== 'reviewed')
                                        <form method="POST" action="{{ route('admin.feedback.status', $item->id) }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="reviewed">
                                            <button type="submit" class="btn btn-warning-soft btn-sm">Review</button>
                                        </form>
                                    @endif

                                    @if($item->status !== 'resolved')
                                        <form method="POST" action="{{ route('admin.feedback.status', $item->id) }}" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="btn btn-secondary btn-sm">Resolve</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.feedback.destroy', $item->id) }}" id="del-fb-{{ $item->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger-soft btn-sm"
                                            data-form="del-fb-{{ $item->id }}"
                                            data-title="Delete feedback?"
                                            data-desc="This feedback entry will be permanently removed."
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
        @if($feedback->hasPages())
            <div class="pagination">
                <span>Showing {{ $feedback->firstItem() }}–{{ $feedback->lastItem() }} of {{ $feedback->total() }}</span>
                <div class="pagination-pages">
                    @if(!$feedback->onFirstPage())
                        <a href="{{ $feedback->previousPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-left" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                    @foreach($feedback->getUrlRange(max(1,$feedback->currentPage()-2), min($feedback->lastPage(),$feedback->currentPage()+2)) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $feedback->currentPage() ? 'current' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($feedback->hasMorePages())
                        <a href="{{ $feedback->nextPageUrl() }}" class="page-btn">
                            <i data-lucide="chevron-right" style="width:14px;height:14px;"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @endif

</div>

{{-- Message Viewer Modal --}}
<div class="modal-overlay" id="msgModal" onclick="if(event.target===this)closeMsgModal()">
    <div class="modal" style="max-width:520px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px;">
            <div>
                <div style="font-size:11px;color:var(--text-2);margin-bottom:4px;" id="msgMeta"></div>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;" id="msgBadges"></div>
            </div>
            <button type="button" class="btn btn-ghost btn-sm" onclick="closeMsgModal()" style="flex-shrink:0;padding:4px 6px;">
                <i data-lucide="x" style="width:15px;height:15px;"></i>
            </button>
        </div>

        <div id="msgBody"
             style="font-size:14px;line-height:1.7;color:var(--text);background:var(--surface2);border-radius:8px;padding:16px;white-space:pre-wrap;word-break:break-word;max-height:360px;overflow-y:auto;"></div>

        <div style="margin-top:16px;display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeMsgModal()">Close</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openMsgModal(btn) {
    const message = btn.dataset.message;
    const user    = btn.dataset.user;
    const type    = btn.dataset.type;
    const rating  = parseInt(btn.dataset.rating) || 0;
    const date    = btn.dataset.date;

    document.getElementById('msgMeta').textContent = user + ' · ' + date;

    const stars = '★'.repeat(rating) + '☆'.repeat(5 - rating);
    document.getElementById('msgBadges').innerHTML =
        `<span class="badge badge-gray">${type}</span>` +
        (rating ? `<span style="font-size:13px;color:var(--yellow);letter-spacing:1px;">${stars}</span>` : '');

    document.getElementById('msgBody').textContent = message;
    document.getElementById('msgModal').classList.add('show');
    lucide.createIcons();
}

function closeMsgModal() {
    document.getElementById('msgModal').classList.remove('show');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMsgModal();
});
</script>
@endpush

@endsection
