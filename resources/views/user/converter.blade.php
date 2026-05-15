@extends('layouts.app')

@section('title', 'Converter')

@section('content')

<div class="animate-in stagger-1">
    <div class="section-title">CURRENCY CONVERTER</div>

    <!-- Amount + Currency selects -->
    <div class="cyber-card">
        <div style="display:grid;gap:12px;">
            <div>
                <label class="cyber-label">// AMOUNT</label>
                <input type="number" id="amount" class="cyber-input" placeholder="1.00" value="1" min="0" step="any">
            </div>
            <div style="display:grid;grid-template-columns:1fr auto 1fr;gap:8px;align-items:end;">
                <div>
                    <label class="cyber-label">// FROM</label>
                    <select id="from-currency" class="cyber-input"><option value="">Loading...</option></select>
                </div>
                <button onclick="swapCurrencies()" style="background:none;border:1px solid var(--border);color:var(--green);padding:12px 10px;cursor:pointer;transition:all .2s;margin-bottom:0;" onmouseover="this.style.borderColor='var(--border-hot)'" onmouseout="this.style.borderColor='var(--border)'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                </button>
                <div>
                    <label class="cyber-label">// TO</label>
                    <select id="to-currency" class="cyber-input"><option value="">Loading...</option></select>
                </div>
            </div>
            <button class="btn-green" onclick="doConvert()">CONVERT</button>
        </div>
    </div>

    <!-- Result -->
    <div class="cyber-card animate-in stagger-2" id="result-card" style="display:none;">
        <div class="cyber-label">RESULT</div>
        <div class="amount-display glow-pulse" style="font-size:2.2rem;margin:8px 0 4px;" id="result-amount">—</div>
        <div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;color:var(--text-dim);" id="result-rate"></div>
        <div style="font-family:'Share Tech Mono',monospace;font-size:.55rem;color:var(--text-dim);margin-top:6px;" id="result-time"></div>
    </div>

    <!-- Rate Chart -->
    <div class="cyber-card animate-in stagger-3">
        <div class="cyber-label" style="margin-bottom:12px;">7-DAY RATE TREND</div>
        <div id="rate-chart" style="min-height:160px;"></div>
        <div style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:var(--text-dim);margin-top:8px;text-align:right;" id="chart-label"></div>
    </div>

    <!-- Quick rates -->
    <div class="section-title" style="margin-top:4px;">LIVE RATES</div>
    <div id="quick-rates" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;"></div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const API = '/api';
const token = () => localStorage.getItem('currensee_token');
const authHeaders = () => ({ 'Content-Type':'application/json','Accept':'application/json', ...(token() ? {'Authorization':'Bearer '+token()} : {}) });

let chart = null;
let currencies = [];

async function loadCurrencies() {
    const res = await fetch(`${API}/currencies`, { headers: authHeaders() });
    const data = await res.json();
    currencies = data.currencies || data;
    const fromSel = document.getElementById('from-currency');
    const toSel = document.getElementById('to-currency');
    fromSel.innerHTML = '';
    toSel.innerHTML = '';
    currencies.forEach(c => {
        const label = `${c.code} — ${c.name}`;
        fromSel.appendChild(new Option(label, c.code));
        toSel.appendChild(new Option(label, c.code));
    });
    fromSel.value = 'USD';
    toSel.value = 'PKR';
    loadQuickRates();
    loadChart();
}

async function doConvert() {
    const amount = parseFloat(document.getElementById('amount').value) || 1;
    const from = document.getElementById('from-currency').value;
    const to = document.getElementById('to-currency').value;
    if (!from || !to) return;
    const res = await fetch(`${API}/convert`, { method:'POST', headers: authHeaders(), body: JSON.stringify({ from, to, amount }) });
    const data = await res.json();
    const card = document.getElementById('result-card');
    card.style.display = 'block';
    document.getElementById('result-amount').textContent = `${data.converted_amount?.toFixed(4)} ${to}`;
    document.getElementById('result-rate').textContent = `1 ${from} = ${data.rate?.toFixed(6)} ${to}`;
    document.getElementById('result-time').textContent = `// RATE AT ${new Date().toUTCString()}`;
    loadChart();
}

function swapCurrencies() {
    const f = document.getElementById('from-currency');
    const t = document.getElementById('to-currency');
    [f.value, t.value] = [t.value, f.value];
    loadChart();
}

async function loadChart() {
    const from = document.getElementById('from-currency').value;
    const to = document.getElementById('to-currency').value;
    if (!from || !to) return;
    try {
        const res = await fetch(`${API}/currencies/${from}/historical/${to}`, { headers: authHeaders() });
        const data = await res.json();
        const series = data.history || [];
        const dates = series.map(d => d.date);
        const rates = series.map(d => d.rate);
        document.getElementById('chart-label').textContent = `${from} / ${to} — LAST ${dates.length} DAYS`;

        const opts = {
            chart: { type:'area', height:160, background:'transparent', toolbar:{show:false}, sparkline:{enabled:false}, animations:{enabled:true,easing:'easeinout',speed:600} },
            series: [{ name:`${from}/${to}`, data: rates }],
            xaxis: { categories: dates, labels:{ style:{colors:'#555',fontFamily:'Share Tech Mono',fontSize:'9px'}, rotate:0 }, axisBorder:{show:false}, axisTicks:{show:false} },
            yaxis: { labels:{ style:{colors:'#555',fontFamily:'Share Tech Mono',fontSize:'9px'}, formatter: v => v.toFixed(3) } },
            stroke: { curve:'smooth', width:2, colors:['#AAFF00'] },
            fill: { type:'gradient', gradient:{ shadeIntensity:1, opacityFrom:0.3, opacityTo:0, stops:[0,100], colorStops:[{offset:0,color:'#AAFF00',opacity:.3},{offset:100,color:'#AAFF00',opacity:0}] } },
            grid: { borderColor:'rgba(170,255,0,0.08)', strokeDashArray:4 },
            tooltip: { theme:'dark', style:{fontFamily:'Share Tech Mono'}, x:{format:'dd MMM'} },
            theme: { mode:'dark' },
            markers: { size:0 },
        };

        if (chart) { chart.updateOptions(opts); }
        else { chart = new ApexCharts(document.getElementById('rate-chart'), opts); chart.render(); }
    } catch(e) { document.getElementById('rate-chart').innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.6rem;color:#555;padding:20px;text-align:center;">// CHART DATA UNAVAILABLE</div>'; }
}

async function loadQuickRates() {
    const from = document.getElementById('from-currency').value || 'USD';
    try {
        const res = await fetch(`${API}/currencies/${from}/rates`, { headers: authHeaders() });
        const data = await res.json();
        const rates = data.rates || {};
        const targets = ['EUR','GBP','PKR','JPY','AED','CAD','CHF','AUD'].filter(c => c !== from);
        const el = document.getElementById('quick-rates');
        el.innerHTML = targets.map(c => rates[c] ? `
            <div class="cyber-card" style="margin:0;padding:12px;">
                <div style="font-family:'Share Tech Mono',monospace;font-size:.55rem;color:var(--text-dim);">${from} → ${c}</div>
                <div style="font-family:'Black Ops One',cursive;color:var(--green);font-size:1rem;margin-top:4px;text-shadow:0 0 10px var(--green);">${parseFloat(rates[c]).toFixed(4)}</div>
            </div>` : '').join('');
    } catch(e) {}
}

loadCurrencies();
document.getElementById('from-currency').addEventListener('change', () => { loadQuickRates(); loadChart(); });
document.getElementById('to-currency').addEventListener('change', loadChart);
</script>
@endpush
