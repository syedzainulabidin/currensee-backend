@extends('layouts.app')

@section('title', 'Rate Alerts')

@section('content')

<div class="animate-in stagger-1">
    <div class="section-title">RATE ALERTS</div>

    <!-- Create alert -->
    <div class="cyber-card animate-in stagger-2">
        <div class="card-title" style="font-family:'Syne',sans-serif;font-weight:700;font-size:.65rem;color:var(--green);letter-spacing:4px;text-transform:uppercase;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
            NEW ALERT <span style="flex:1;height:1px;background:linear-gradient(90deg,var(--border),transparent);display:block;"></span>
        </div>
        <div style="display:grid;gap:10px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div>
                    <label class="cyber-label">// FROM</label>
                    <select id="alert-from" class="cyber-input"><option value="">Loading...</option></select>
                </div>
                <div>
                    <label class="cyber-label">// TO</label>
                    <select id="alert-to" class="cyber-input"><option value="">Loading...</option></select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <div>
                    <label class="cyber-label">// CONDITION</label>
                    <select id="alert-condition" class="cyber-input">
                        <option value="above">ABOVE</option>
                        <option value="below">BELOW</option>
                    </select>
                </div>

                <div>
                    <label class="cyber-label">// TARGET RATE</label>
                    <input type="number" id="alert-rate" class="cyber-input" placeholder="0.00" step="any" min="0">
                </div>
            </div>
            <div class="err" id="alert-err" style="border:1px solid #ff4444;background:rgba(255,68,68,.05);color:#ff4444;font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:1px;padding:10px 14px;display:none;"></div>
            <button class="btn-green" onclick="createAlert()">SET ALERT</button>
        </div>
    </div>

    <!-- Alerts summary donut -->
    <div class="cyber-card animate-in stagger-3">
        <div class="cyber-label" style="margin-bottom:12px;">ALERTS BY STATUS</div>
        <div id="status-chart" style="min-height:200px;"></div>
    </div>

    <!-- Alerts list -->
    <div class="section-title">MY ALERTS</div>
    <div id="alerts-list"><div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// LOADING...</div></div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const API = '/api';
const token = () => localStorage.getItem('currensee_token');
const authHeaders = () => ({ 'Content-Type':'application/json','Accept':'application/json','Authorization':'Bearer '+token() });

let alerts = [];
let statusChart = null;

async function loadCurrencies() {
    const res = await fetch(`${API}/currencies`, { headers:{'Accept':'application/json'} });
    const data = await res.json();
    const currencies = data.currencies || data;
    [document.getElementById('alert-from'), document.getElementById('alert-to')].forEach((sel, i) => {
        sel.innerHTML = '';
        currencies.forEach(c => sel.appendChild(new Option(`${c.code} — ${c.name}`, c.code)));
        sel.value = i === 0 ? 'USD' : 'PKR';
    });
}

async function loadAlerts() {
    if (!token()) { document.getElementById('alerts-list').innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// LOGIN TO VIEW ALERTS</div>'; return; }
    const res = await fetch(`${API}/alerts`, { headers: authHeaders() });
    const data = await res.json();
    alerts = data.alerts || data.data || data || [];
    renderAlerts();
    renderChart();
}

function renderAlerts() {
    const el = document.getElementById('alerts-list');
    if (!alerts.length) { el.innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// NO ALERTS SET</div>'; return; }
    el.innerHTML = alerts.map((a,i) => {
        const active = a.is_active || a.status === 'active';
        const triggered = a.is_triggered || a.status === 'triggered';
        const badgeColor = triggered ? '#ffcc00' : (active ? 'var(--green)' : '#555');
        const badgeText = triggered ? 'TRIGGERED' : (active ? 'ACTIVE' : 'INACTIVE');
        return `
        <div class="cyber-card animate-in stagger-${Math.min(i+1,5)}" style="padding:12px 14px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                        <span style="font-family:'Share Tech Mono',monospace;font-size:.75rem;color:var(--green);">${a.from_currency} / ${a.to_currency}</span>
                        <span style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:${badgeColor};border:1px solid ${badgeColor};padding:2px 6px;letter-spacing:1px;">${badgeText}</span>
                    </div>
                    <div style="font-family:'DM Sans',sans-serif;font-size:.8rem;">Rate <strong style="color:var(--green);">${(a.direction||'').toUpperCase()}</strong> ${a.target_rate}</div>
                    <div style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:var(--text-dim);margin-top:4px;">${new Date(a.created_at).toLocaleString()}</div>
                </div>
                <div style="display:flex;gap:8px;align-items:center;">
                    <button onclick="toggleAlert(${a.id}, ${active ? 1 : 0})" style="background:none;border:1px solid var(--border);color:var(--text-dim);padding:6px 8px;cursor:pointer;font-family:'Share Tech Mono',monospace;font-size:.5rem;letter-spacing:1px;" title="${active?'Pause':'Activate'}">${active ? 'PAUSE' : 'ACTIVATE'}</button>
                    <button onclick="deleteAlert(${a.id})" style="background:none;border:none;cursor:pointer;color:var(--text-dim);" title="Delete">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
                    </button>
                </div>
            </div>
        </div>`;
    }).join('');
}

function renderChart() {
    const active = alerts.filter(a => (a.is_active || a.status === 'active') && !(a.is_triggered || a.status === 'triggered')).length;
    const triggered = alerts.filter(a => a.is_triggered || a.status === 'triggered').length;
    const inactive = alerts.length - active - triggered;

    const opts = {
        chart:{ type:'donut', height:200, background:'transparent', toolbar:{show:false} },
        series:[active, triggered, inactive].filter((_,i) => [active,triggered,inactive][i] > 0),
        labels:['ACTIVE','TRIGGERED','INACTIVE'].filter((_,i) => [active,triggered,inactive][i] > 0),
        colors:['#AAFF00','#ffcc00','#555'],
        legend:{ position:'bottom', fontFamily:'Share Tech Mono', fontSize:'10px', labels:{colors:'#888'} },
        plotOptions:{ pie:{ donut:{ size:'65%', labels:{ show:true, total:{ show:true, label:'TOTAL', fontFamily:'Share Tech Mono', fontSize:'11px', color:'#555' } } } } },
        stroke:{ colors:['#000'] },
        tooltip:{ theme:'dark', style:{fontFamily:'Share Tech Mono'} },
        theme:{ mode:'dark' },
        noData:{ text:'// NO DATA', style:{color:'#555',fontFamily:'Share Tech Mono'} },
    };
    if (statusChart) statusChart.updateOptions(opts);
    else { statusChart = new ApexCharts(document.getElementById('status-chart'), opts); statusChart.render(); }
}

async function createAlert() {
    const err = document.getElementById('alert-err');
    err.style.display = 'none';
    const from_currency = document.getElementById('alert-from').value;
    const to_currency = document.getElementById('alert-to').value;
    const direction = document.getElementById('alert-condition').value;
    const target_rate = parseFloat(document.getElementById('alert-rate').value);
    if (!from_currency || !to_currency || !target_rate) { err.textContent = '> ALL FIELDS REQUIRED'; err.style.display='block'; return; }
    if (!token()) { err.textContent = '> LOGIN REQUIRED'; err.style.display='block'; return; }
    const res = await fetch(`${API}/alerts`, { method:'POST', headers: authHeaders(), body: JSON.stringify({from_currency, to_currency, direction, target_rate}) });
    if (res.ok) { document.getElementById('alert-rate').value = ''; loadAlerts(); }
    else { const d = await res.json(); err.textContent = '> ' + (d.message || 'FAILED'); err.style.display='block'; }
}

async function toggleAlert(id, isActive) {
    await fetch(`${API}/alerts/${id}`, { method:'PUT', headers: authHeaders(), body: JSON.stringify({ is_active: !isActive }) });
    loadAlerts();
}

async function deleteAlert(id) {
    await fetch(`${API}/alerts/${id}`, { method:'DELETE', headers: authHeaders() });
    alerts = alerts.filter(a => a.id !== id);
    renderAlerts();
    renderChart();
}

loadCurrencies();
loadAlerts();
</script>
@endpush
