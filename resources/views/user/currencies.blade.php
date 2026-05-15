@extends('layouts.app')

@section('title', 'Currency Rates')

@section('content')

<div class="animate-in stagger-1">
    <div class="section-title">LIVE EXCHANGE RATES</div>

    <!-- Search + Base -->
    <div class="cyber-card animate-in stagger-2" style="padding:12px 14px;">
        <div style="display:grid;gap:10px;">
            <div>
                <label class="cyber-label">// BASE CURRENCY</label>
                <select id="base-currency" class="cyber-input" onchange="loadRates()">
                    <option value="">Loading...</option>
                </select>
            </div>
            <div>
                <label class="cyber-label">// SEARCH</label>
                <input type="text" id="search" class="cyber-input" placeholder="Search currency name or code..." oninput="filterRates()">
            </div>
        </div>
    </div>

    <!-- Top movers chart -->
    <div class="cyber-card animate-in stagger-3">
        <div class="cyber-label" style="margin-bottom:12px;">TOP 8 RATES vs <span id="base-label">USD</span></div>
        <div id="bar-chart" style="min-height:200px;"></div>
    </div>

    <!-- Rates grid -->
    <div class="section-title">ALL RATES</div>
    <div id="rates-grid"><div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// LOADING...</div></div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const API = '/api';
let allRates = {};
let allCurrencies = [];
let chart = null;
let currentBase = 'USD';

async function init() {
    const res = await fetch(`${API}/currencies`, { headers:{'Accept':'application/json'} });
    const data = await res.json();
    allCurrencies = data.currencies || data;
    const sel = document.getElementById('base-currency');
    sel.innerHTML = '';
    allCurrencies.forEach(c => sel.appendChild(new Option(`${c.code} — ${c.name}`, c.code)));
    // Prefer saved default currency
    const saved = localStorage.getItem('currensee_default_currency') || 'USD';
    sel.value = saved;
    currentBase = saved;
    loadRates();
}

async function loadRates() {
    currentBase = document.getElementById('base-currency').value;
    document.getElementById('base-label').textContent = currentBase;
    document.getElementById('rates-grid').innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// FETCHING RATES...</div>';
    try {
        const res = await fetch(`${API}/currencies/${currentBase}/rates`, { headers:{'Accept':'application/json'} });
        const data = await res.json();
        allRates = data.rates || {};
        filterRates();
        renderChart();
    } catch(e) {
        document.getElementById('rates-grid').innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:#ff4444;text-align:center;padding:40px;">// FAILED TO LOAD RATES</div>';
    }
}

function filterRates() {
    const q = document.getElementById('search').value.toLowerCase();
    const filtered = allCurrencies.filter(c => c.code !== currentBase && (!q || c.code.toLowerCase().includes(q) || c.name.toLowerCase().includes(q)));
    const el = document.getElementById('rates-grid');
    if (!filtered.length) { el.innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// NO MATCH</div>'; return; }
    el.innerHTML = filtered.map(c => {
        const rate = allRates[c.code];
        if (!rate) return '';
        return `
        <div class="cyber-card" style="margin-bottom:8px;padding:12px 14px;cursor:pointer;" onclick="window.location.href='/app/converter'">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-family:'Share Tech Mono',monospace;font-size:.75rem;color:var(--green);letter-spacing:2px;">${c.code}</div>
                    <div style="font-family:'DM Sans',sans-serif;font-size:.75rem;color:var(--text-dim);margin-top:2px;">${c.name}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-family:'Black Ops One',cursive;color:var(--green);font-size:1.05rem;text-shadow:0 0 8px var(--green);">${parseFloat(rate).toFixed(4)}</div>
                    <div style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:var(--text-dim);">per 1 ${currentBase}</div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function renderChart() {
    const targets = ['EUR','GBP','JPY','CHF','CAD','AED','AUD','PKR'].filter(c => c !== currentBase && allRates[c]);
    const rates = targets.map(c => parseFloat(allRates[c]));
    const opts = {
        chart:{ type:'bar', height:200, background:'transparent', toolbar:{show:false}, animations:{enabled:true,easing:'easeinout',speed:600} },
        series:[{ name:`Rate vs ${currentBase}`, data: rates }],
        xaxis:{ categories: targets, labels:{ style:{colors:'#555',fontFamily:'Share Tech Mono',fontSize:'9px'} }, axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis:{ labels:{ style:{colors:'#555',fontFamily:'Share Tech Mono',fontSize:'9px'}, formatter: v => v > 100 ? v.toFixed(0) : v.toFixed(3) } },
        colors:['#AAFF00'],
        plotOptions:{ bar:{ borderRadius:2, columnWidth:'55%', distributed:true } },
        legend:{ show:false },
        grid:{ borderColor:'rgba(170,255,0,0.08)', strokeDashArray:4 },
        tooltip:{ theme:'dark', style:{fontFamily:'Share Tech Mono'}, y:{ formatter: v => v.toFixed(4) } },
        theme:{ mode:'dark' },
        noData:{ text:'// NO DATA', style:{color:'#555',fontFamily:'Share Tech Mono'} },
    };
    if (chart) chart.updateOptions(opts);
    else { chart = new ApexCharts(document.getElementById('bar-chart'), opts); chart.render(); }
}

init();
</script>
@endpush
