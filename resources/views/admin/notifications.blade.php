@extends('layouts.admin')
@section('title', 'Send Notification')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Push Notifications</div>
        <div class="page-subtitle">Send a notification to one user or everyone</div>
    </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px;max-width:480px;">
    <div class="stat-card">
        <div>
            <div class="stat-card-value text-green">{{ $usersWithTokens }}</div>
            <div class="stat-card-label">Reachable Users</div>
            <div class="stat-card-delta text-dim">Have a device registered</div>
        </div>
        <div class="stat-card-icon" style="background:var(--green-dim);">
            <i data-lucide="users" style="color:var(--green);width:18px;height:18px;"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-card-value">{{ $totalTokens }}</div>
            <div class="stat-card-label">Registered Devices</div>
            <div class="stat-card-delta text-dim">Across all users</div>
        </div>
        <div class="stat-card-icon" style="background:rgba(99,102,241,0.1);">
            <i data-lucide="smartphone" style="color:var(--blue);width:18px;height:18px;"></i>
        </div>
    </div>
</div>

{{-- Send Form --}}
<div class="card" style="max-width:640px;">
    <div class="card-header">
        <div>
            <div class="card-title">Compose Notification</div>
            <div class="card-subtitle">Fill in the details and choose who receives it</div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.notifications.send') }}">
            @csrf

            {{-- Target --}}
            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12.5px;font-weight:600;color:var(--text-2);margin-bottom:10px;">Send To</label>
                <div style="display:flex;gap:8px;">
                    <label style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:8px;border:1px solid var(--border2);background:var(--surface2);cursor:pointer;flex:1;">
                        <input type="radio" name="target" value="all" checked
                               onchange="toggleTarget('all')"
                               style="accent-color:var(--green);width:15px;height:15px;">
                        <div>
                            <div style="font-weight:600;font-size:13px;">All Users</div>
                            <div style="font-size:11.5px;color:var(--text-2);">{{ $usersWithTokens }} reachable</div>
                        </div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:8px;border:1px solid var(--border2);background:var(--surface2);cursor:pointer;flex:1;">
                        <input type="radio" name="target" value="user"
                               onchange="toggleTarget('user')"
                               style="accent-color:var(--green);width:15px;height:15px;">
                        <div>
                            <div style="font-weight:600;font-size:13px;">Specific User</div>
                            <div style="font-size:11.5px;color:var(--text-2);">Search by name or email</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- User picker (shown only when target = user) --}}
            <div id="user-picker" style="margin-bottom:20px;display:none;">
                <label style="display:block;font-size:12.5px;font-weight:600;color:var(--text-2);margin-bottom:7px;">User</label>
                <div style="position:relative;">
                    <div class="input-group has-icon-left">
                        <div class="input-icon"><i data-lucide="search"></i></div>
                        <input type="text" id="user-search" placeholder="Type name or email…" autocomplete="off">
                    </div>
                    <div id="user-results"
                         style="display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;background:var(--surface2);border:1px solid var(--border2);border-radius:8px;z-index:50;max-height:200px;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,0.4);"></div>
                </div>
                <input type="hidden" name="user_id" id="user-id-input">
                <div id="selected-user" style="display:none;margin-top:8px;padding:8px 12px;background:var(--green-dim);border:1px solid var(--green-border);border-radius:8px;font-size:13px;color:var(--green);"></div>
            </div>

            {{-- Type --}}
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12.5px;font-weight:600;color:var(--text-2);margin-bottom:7px;">Type</label>
                <select name="type">
                    <option value="announcement">📢 Announcement</option>
                    <option value="alert">🔔 Alert</option>
                    <option value="update">✨ Update</option>
                    <option value="promo">🎁 Promo</option>
                </select>
            </div>

            {{-- Title --}}
            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:12.5px;font-weight:600;color:var(--text-2);margin-bottom:7px;">
                    Title <span style="color:var(--text-3);font-weight:400;">(max 100 chars)</span>
                </label>
                <input type="text" name="title" placeholder="e.g. Market Alert 🔔"
                       maxlength="100" value="{{ old('title') }}" required>
                @error('title')
                    <div style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Body --}}
            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12.5px;font-weight:600;color:var(--text-2);margin-bottom:7px;">
                    Message <span style="color:var(--text-3);font-weight:400;">(max 500 chars)</span>
                </label>
                <textarea name="body" rows="4" maxlength="500" required
                          placeholder="e.g. USD/PKR just crossed 280. Tap to check your alerts."
                          style="resize:vertical;">{{ old('body') }}</textarea>
                <div style="font-size:11.5px;color:var(--text-3);margin-top:5px;" id="char-counter">0 / 500</div>
                @error('body')
                    <div style="color:var(--red);font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="send"></i> Send Notification
                </button>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
// Target toggle
function toggleTarget(val) {
    document.getElementById('user-picker').style.display = val === 'user' ? 'block' : 'none';
    if (val !== 'user') {
        document.getElementById('user-id-input').value = '';
        document.getElementById('selected-user').style.display = 'none';
        document.getElementById('user-search').value = '';
    }
    lucide.createIcons();
}

// Character counter
const bodyTA = document.querySelector('textarea[name="body"]');
const counter = document.getElementById('char-counter');
if (bodyTA) {
    bodyTA.addEventListener('input', () => { counter.textContent = bodyTA.value.length + ' / 500'; });
}

// User search
const searchInput = document.getElementById('user-search');
const resultsBox  = document.getElementById('user-results');
const hiddenInput = document.getElementById('user-id-input');
const selectedBox = document.getElementById('selected-user');

let timer;
if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { resultsBox.style.display = 'none'; return; }
        timer = setTimeout(async () => {
            try {
                const res  = await fetch(`{{ route('admin.users') }}?search=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                // Users page returns HTML — so we use a separate JSON search endpoint
                // For now, we show a note to use the dedicated endpoint
                renderFallback();
            } catch { resultsBox.style.display = 'none'; }
        }, 300);
    });
}

function renderFallback() {
    // Inline user search — calls a small JSON endpoint we'll add
    const q = searchInput.value.trim();
    fetch(`/admin/users/json-search?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => renderResults(data.users || []))
        .catch(() => resultsBox.style.display = 'none');
}

function renderResults(users) {
    if (!users.length) {
        resultsBox.innerHTML = '<div style="padding:12px 16px;color:var(--text-2);font-size:13px;">No users found</div>';
    } else {
        resultsBox.innerHTML = users.slice(0,6).map(u => `
            <div onclick="selectUser(${u.id},'${esc(u.name)}','${esc(u.email)}')"
                 style="padding:10px 16px;cursor:pointer;border-bottom:1px solid var(--border);transition:background 0.1s;"
                 onmouseover="this.style.background='var(--surface3)'"
                 onmouseout="this.style.background=''">
                <div style="font-weight:600;font-size:13px;">${esc(u.name)}</div>
                <div style="font-size:11.5px;color:var(--text-2);">${esc(u.email)}</div>
            </div>`).join('');
    }
    resultsBox.style.display = 'block';
}

function selectUser(id, name, email) {
    hiddenInput.value = id;
    searchInput.value = name;
    selectedBox.textContent  = `✓ Sending to ${name} (${email})`;
    selectedBox.style.display = 'block';
    resultsBox.style.display  = 'none';
}

function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

document.addEventListener('click', e => {
    if (!e.target.closest('#user-picker')) resultsBox.style.display = 'none';
});
</script>
@endpush

@endsection
