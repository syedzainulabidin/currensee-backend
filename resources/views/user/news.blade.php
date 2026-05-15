@extends('layouts.app')

@section('title', 'Market News')

@section('content')

<div class="animate-in stagger-1">
    <div class="section-title">MARKET NEWS</div>

    <!-- Currency filter -->
    <div class="cyber-card animate-in stagger-2" style="padding:12px 14px;">
        <label class="cyber-label">// FILTER BY CURRENCY</label>
        <div style="display:flex;gap:8px;">
            <select id="currency-filter" class="cyber-input" style="flex:1;">
                <option value="">ALL CURRENCIES</option>
            </select>
            <button onclick="loadNews()" style="background:var(--green);color:#000;border:none;padding:0 16px;font-family:'Syne',sans-serif;font-weight:700;font-size:.7rem;letter-spacing:2px;cursor:pointer;clip-path:polygon(0 0,calc(100% - 6px) 0,100% 6px,100% 100%,6px 100%,0 calc(100% - 6px));">FILTER</button>
        </div>
    </div>

    <!-- News list -->
    <div id="news-list"><div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// LOADING...</div></div>
</div>

@endsection

@push('scripts')
<script>
const API = '/api';

async function loadCurrencies() {
    const res = await fetch(`${API}/currencies`, { headers:{'Accept':'application/json'} });
    const data = await res.json();
    const currencies = data.currencies || data;
    const sel = document.getElementById('currency-filter');
    currencies.slice(0, 20).forEach(c => sel.appendChild(new Option(`${c.code} — ${c.name}`, c.code)));
}

async function loadNews() {
    const filter = document.getElementById('currency-filter').value;
    const el = document.getElementById('news-list');
    el.innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// LOADING...</div>';
    try {
        const url = filter ? `${API}/news/${filter}` : `${API}/news`;
        const res = await fetch(url, { headers:{'Accept':'application/json'} });
        const data = await res.json();
        const articles = data.articles || data.news || data || [];
        if (!articles.length) { el.innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// NO NEWS FOUND</div>'; return; }
        el.innerHTML = articles.map((a,i) => `
            <div class="cyber-card animate-in stagger-${Math.min(i+1,5)}" style="padding:14px;">
                ${a.currency ? `<span style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:var(--green);border:1px solid var(--border);padding:2px 8px;letter-spacing:1px;margin-bottom:8px;display:inline-block;">${a.currency}</span>` : ''}
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:.85rem;color:var(--text);margin:8px 0 6px;line-height:1.3;">${a.title}</div>
                ${a.summary || a.description ? `<div style="font-family:'DM Sans',sans-serif;font-size:.75rem;color:var(--text-dim);line-height:1.5;margin-bottom:8px;">${(a.summary || a.description || '').substring(0,160)}${(a.summary||a.description||'').length>160?'...':''}</div>` : ''}
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:var(--text-dim);">${a.published_at ? new Date(a.published_at).toLocaleDateString('en-GB',{day:'numeric',month:'short',year:'numeric'}) : ''}</span>
                    ${a.url ? `<a href="${a.url}" target="_blank" rel="noopener" style="font-family:'Share Tech Mono',monospace;font-size:.55rem;color:var(--green);text-decoration:none;letter-spacing:1px;">READ →</a>` : ''}
                </div>
            </div>`).join('');
    } catch(e) { el.innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:#ff4444;text-align:center;padding:40px;">// FAILED TO LOAD NEWS</div>'; }
}

loadCurrencies();
loadNews();
</script>
@endpush
