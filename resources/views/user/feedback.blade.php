@extends('layouts.app')

@section('title', 'Feedback')

@section('content')

<div class="animate-in stagger-1">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <a href="/app/profile" style="color:var(--text-dim);text-decoration:none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <div class="section-title" style="margin:0;">SUBMIT FEEDBACK</div>
    </div>

    <!-- Submit form -->
    <div class="cyber-card animate-in stagger-2" style="padding:18px;">
        <div style="display:grid;gap:14px;">
            <div>
                <label class="cyber-label">// CATEGORY</label>
                <select id="category" class="cyber-input">
                    <option value="bug">BUG REPORT</option>
                    <option value="feature">FEATURE REQUEST</option>
                    <option value="ui">UI / DESIGN</option>
                    <option value="rates">EXCHANGE RATES</option>
                    <option value="other">OTHER</option>
                </select>
            </div>
            <div>
                <label class="cyber-label">// SUBJECT</label>
                <input type="text" id="subject" class="cyber-input" placeholder="Brief summary...">
            </div>
            <div>
                <label class="cyber-label">// MESSAGE</label>
                <textarea id="message" class="cyber-input" rows="5" placeholder="Describe your issue or suggestion in detail..." style="resize:vertical;font-family:'DM Sans',sans-serif;"></textarea>
            </div>
            <div id="fb-err" style="border:1px solid #ff4444;background:rgba(255,68,68,.05);color:#ff4444;font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:1px;padding:10px 14px;display:none;"></div>
            <div id="fb-success" style="border:1px solid var(--green);background:var(--green-glow2);color:var(--green);font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:1px;padding:10px 14px;display:none;">// FEEDBACK SUBMITTED — THANK YOU</div>
            <button class="btn-green" id="fb-btn" onclick="submitFeedback()">SUBMIT FEEDBACK</button>
        </div>
    </div>

    <!-- Past feedback -->
    <div id="past-section" style="display:none;">
        <div class="section-title">MY PAST FEEDBACK</div>
        <div id="past-list"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const API = '/api';
const token = () => localStorage.getItem('currensee_token');
const authHeaders = (json=true) => ({ ...(json?{'Content-Type':'application/json'}:{}), 'Accept':'application/json', ...(token()?{'Authorization':'Bearer '+token()}:{}) });

async function submitFeedback() {
    const btn = document.getElementById('fb-btn');
    const err = document.getElementById('fb-err');
    const ok = document.getElementById('fb-success');
    const category = document.getElementById('category').value;
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();
    err.style.display='none'; ok.style.display='none';
    if (!subject || !message) { err.textContent='> SUBJECT AND MESSAGE ARE REQUIRED'; err.style.display='block'; return; }
    btn.disabled=true; btn.textContent='SUBMITTING...';
    const typeMap = { bug:'bug', feature:'suggestion', ui:'suggestion', rates:'general', other:'general' };
    const fullMessage = `[${subject}]\n${message}`;
    try {
        const res = await fetch(`${API}/feedback`, { method:'POST', headers: authHeaders(), body: JSON.stringify({ type: typeMap[category] || 'general', message: fullMessage }) });
        const data = await res.json();
        if (!res.ok) { err.textContent='> '+(data.message||'SUBMISSION FAILED'); err.style.display='block'; btn.disabled=false; btn.textContent='SUBMIT FEEDBACK'; return; }
        ok.style.display='block';
        document.getElementById('subject').value='';
        document.getElementById('message').value='';
        btn.disabled=false; btn.textContent='SUBMIT FEEDBACK';
        loadPast();
    } catch(e) { err.textContent='> CONNECTION ERROR'; err.style.display='block'; btn.disabled=false; btn.textContent='SUBMIT FEEDBACK'; }
}

async function loadPast() {
    if (!token()) return;
    try {
        const res = await fetch(`${API}/feedback`, { headers: authHeaders(false) });
        const data = await res.json();
        const items = data.feedback || data.data || data || [];
        if (!items.length) return;
        document.getElementById('past-section').style.display='block';
        const statusColor = { open:'var(--green)', resolved:'#ffcc00', closed:'#555' };
        document.getElementById('past-list').innerHTML = items.map(f => `
            <div class="cyber-card" style="padding:12px 14px;margin-bottom:8px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-family:'Share Tech Mono',monospace;font-size:.65rem;color:var(--green);">${(f.category||'').toUpperCase()}</span>
                    <span style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:${statusColor[f.status]||'#555'};border:1px solid ${statusColor[f.status]||'#555'};padding:2px 6px;">${(f.status||'OPEN').toUpperCase()}</span>
                </div>
                <div style="font-family:'Syne',sans-serif;font-weight:600;font-size:.8rem;">${f.subject||''}</div>
                <div style="font-family:'DM Sans',sans-serif;font-size:.72rem;color:var(--text-dim);margin-top:4px;">${(f.message||'').substring(0,100)}${(f.message||'').length>100?'...':''}</div>
                <div style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:var(--text-dim);margin-top:6px;">${new Date(f.created_at).toLocaleString()}</div>
            </div>`).join('');
    } catch(e) {}
}

loadPast();
</script>
@endpush
