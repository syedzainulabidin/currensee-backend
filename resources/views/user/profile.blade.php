@extends('layouts.app')

@section('title', 'Account')

@section('content')

<!-- Not logged in state -->
<div id="guest-view" style="display:none;" class="animate-in stagger-1">
    <div class="section-title">ACCOUNT</div>
    <div class="cyber-card" style="text-align:center;padding:40px 24px;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
        <div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;color:var(--text-dim);margin-bottom:20px;letter-spacing:2px;">// NOT AUTHENTICATED</div>
        <a href="/app/login" class="btn-green" style="display:inline-block;padding:12px 32px;text-decoration:none;font-family:'Syne',sans-serif;font-weight:700;font-size:.75rem;letter-spacing:3px;background:var(--green);color:#000;clip-path:polygon(0 0,calc(100% - 8px) 0,100% 8px,100% 100%,8px 100%,0 calc(100% - 8px));">LOGIN</a>
        <div style="margin-top:12px;">
            <a href="/app/register" style="font-family:'Share Tech Mono',monospace;font-size:.55rem;color:var(--text-dim);letter-spacing:2px;text-decoration:none;">CREATE ACCOUNT →</a>
        </div>
    </div>
</div>

<!-- Logged in state -->
<div id="user-view" style="display:none;" class="animate-in stagger-1">

    <!-- User card -->
    <div class="section-title">ACCOUNT</div>
    <div class="cyber-card animate-in stagger-2" style="padding:16px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:48px;height:48px;border:1px solid var(--border-hot);display:flex;align-items:center;justify-content:center;clip-path:polygon(0 0,calc(100% - 8px) 0,100% 8px,100% 100%,8px 100%,0 calc(100% - 8px));background:var(--surface2);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </div>
            <div style="flex:1;">
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:.95rem;" id="profile-name">—</div>
                <div style="font-family:'Share Tech Mono',monospace;font-size:.55rem;color:var(--text-dim);margin-top:2px;" id="profile-email">—</div>
            </div>
        </div>
    </div>

    <!-- Preferences -->
    <div class="section-title">PREFERENCES</div>
    <div class="cyber-card animate-in stagger-3" style="padding:16px;">
        <div style="margin-bottom:14px;">
            <label class="cyber-label">// DEFAULT BASE CURRENCY</label>
            <select id="pref-currency" class="cyber-input"><option value="">Loading...</option></select>
        </div>
        <div id="pref-success" style="font-family:'Share Tech Mono',monospace;font-size:.6rem;color:var(--green);margin-bottom:10px;display:none;">// PREFERENCES SAVED</div>
        <button class="btn-green" onclick="savePreferences()">SAVE PREFERENCES</button>
    </div>

    <!-- Quick links -->
    <div class="section-title">MORE</div>
    <div style="display:grid;gap:8px;" class="animate-in stagger-4">
        <a href="/app/history" class="cyber-card" style="display:flex;align-items:center;justify-content:space-between;padding:14px;text-decoration:none;color:var(--text);">
            <div style="display:flex;align-items:center;gap:12px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span style="font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:2px;">CONVERSION HISTORY</span>
            </div>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="/app/feedback" class="cyber-card" style="display:flex;align-items:center;justify-content:space-between;padding:14px;text-decoration:none;color:var(--text);">
            <div style="display:flex;align-items:center;gap:12px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span style="font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:2px;">SUBMIT FEEDBACK</span>
            </div>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
        <a href="/app/help" class="cyber-card" style="display:flex;align-items:center;justify-content:space-between;padding:14px;text-decoration:none;color:var(--text);">
            <div style="display:flex;align-items:center;gap:12px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span style="font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:2px;">HELP CENTER / FAQ</span>
            </div>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </div>

    <!-- Notification preferences -->
    <div class="section-title" style="margin-top:4px;">NOTIFICATIONS</div>
    <div class="cyber-card animate-in stagger-5" style="padding:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <div>
                <div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:1px;">RATE ALERTS</div>
                <div style="font-family:'DM Sans',sans-serif;font-size:.7rem;color:var(--text-dim);margin-top:2px;">Notify when alert triggers</div>
            </div>
            <label style="position:relative;display:inline-block;width:40px;height:22px;">
                <input type="checkbox" id="notif-alerts" style="opacity:0;width:0;height:0;" onchange="saveNotifPrefs()">
                <span id="toggle-alerts" onclick="document.getElementById('notif-alerts').click()" style="position:absolute;cursor:pointer;inset:0;background:var(--surface2);border:1px solid var(--border);transition:.3s;"></span>
            </label>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:1px;">APP UPDATES</div>
                <div style="font-family:'DM Sans',sans-serif;font-size:.7rem;color:var(--text-dim);margin-top:2px;">News and app announcements</div>
            </div>
            <label style="position:relative;display:inline-block;width:40px;height:22px;">
                <input type="checkbox" id="notif-updates" style="opacity:0;width:0;height:0;" onchange="saveNotifPrefs()">
                <span id="toggle-updates" onclick="document.getElementById('notif-updates').click()" style="position:absolute;cursor:pointer;inset:0;background:var(--surface2);border:1px solid var(--border);transition:.3s;"></span>
            </label>
        </div>
    </div>

    <!-- Logout -->
    <div style="margin-top:8px;">
        <button onclick="doLogout()" style="width:100%;background:none;border:1px solid rgba(255,68,68,.4);color:#ff4444;font-family:'Syne',sans-serif;font-weight:700;font-size:.75rem;letter-spacing:3px;text-transform:uppercase;padding:13px;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='rgba(255,68,68,.05)'" onmouseout="this.style.background='none'">LOGOUT</button>
    </div>
</div>

@endsection

@push('scripts')
<script>
const API = '/api';
const token = () => localStorage.getItem('currensee_token');
const authHeaders = () => ({ 'Content-Type':'application/json','Accept':'application/json','Authorization':'Bearer '+token() });

async function init() {
    const t = token();
    const user = JSON.parse(localStorage.getItem('currensee_user') || 'null');
    if (!t || !user) { document.getElementById('guest-view').style.display='block'; return; }
    document.getElementById('user-view').style.display='block';
    document.getElementById('profile-name').textContent = user.name;
    document.getElementById('profile-email').textContent = user.email;
    loadCurrencies(user);
    loadNotifPrefs();
}

async function loadCurrencies(user) {
    const res = await fetch(`${API}/currencies`, { headers:{'Accept':'application/json'} });
    const data = await res.json();
    const currencies = data.currencies || data;
    const sel = document.getElementById('pref-currency');
    sel.innerHTML = '';
    currencies.forEach(c => sel.appendChild(new Option(`${c.code} — ${c.name}`, c.code)));
    const saved = user.default_currency || localStorage.getItem('currensee_default_currency') || 'USD';
    sel.value = saved;
}

async function savePreferences() {
    const default_currency = document.getElementById('pref-currency').value;
    localStorage.setItem('currensee_default_currency', default_currency);
    try {
        await fetch(`${API}/me`, { method:'PUT', headers: authHeaders(), body: JSON.stringify({ default_currency }) });
        const user = JSON.parse(localStorage.getItem('currensee_user') || '{}');
        user.default_currency = default_currency;
        localStorage.setItem('currensee_user', JSON.stringify(user));
    } catch(e) {}
    const s = document.getElementById('pref-success');
    s.style.display = 'block';
    setTimeout(() => s.style.display='none', 3000);
}

async function loadNotifPrefs() {
    try {
        const res = await fetch(`${API}/notifications/preferences`, { headers: authHeaders() });
        const data = await res.json();
        if (data.rate_alerts !== undefined) document.getElementById('notif-alerts').checked = data.rate_alerts;
        if (data.app_updates !== undefined) document.getElementById('notif-updates').checked = data.app_updates;
        updateToggles();
    } catch(e) {}
}

async function saveNotifPrefs() {
    updateToggles();
    try {
        await fetch(`${API}/notifications/preferences`, {
            method:'PUT', headers: authHeaders(),
            body: JSON.stringify({ rate_alerts: document.getElementById('notif-alerts').checked, app_updates: document.getElementById('notif-updates').checked })
        });
    } catch(e) {}
}

function updateToggles() {
    ['alerts','updates'].forEach(k => {
        const checked = document.getElementById(`notif-${k}`).checked;
        const span = document.getElementById(`toggle-${k}`);
        span.style.background = checked ? 'var(--green)' : 'var(--surface2)';
        span.style.borderColor = checked ? 'var(--green)' : 'var(--border)';
    });
}

async function doLogout() {
    try { await fetch(`${API}/logout`, { method:'POST', headers: authHeaders() }); } catch(e) {}
    localStorage.removeItem('currensee_token');
    localStorage.removeItem('currensee_user');
    window.location.href = '/app/login';
}

init();
</script>
@endpush
