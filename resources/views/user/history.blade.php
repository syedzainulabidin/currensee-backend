@extends('layouts.app')

@section('title', 'History')

@section('content')

<div class="animate-in stagger-1">
    <div class="section-title">CONVERSION HISTORY</div>

    <!-- Summary chart -->
    <div class="cyber-card animate-in stagger-2">
        <div class="cyber-label" style="margin-bottom:12px;">CURRENCIES CONVERTED (PIE)</div>
        <div id="pie-chart" style="min-height:220px;"></div>
    </div>

    <!-- Volume bar chart -->
    <div class="cyber-card animate-in stagger-3">
        <div class="cyber-label" style="margin-bottom:12px;">DAILY CONVERSION VOLUME</div>
        <div id="bar-chart" style="min-height:160px;"></div>
    </div>

    <!-- List header -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <div class="section-title" style="margin:0;">RECENT CONVERSIONS</div>
        <button onclick="clearAll()" style="font-family:'Share Tech Mono',monospace;font-size:.55rem;color:#ff4444;background:none;border:1px solid rgba(255,68,68,.3);padding:4px 10px;cursor:pointer;letter-spacing:1px;">CLEAR ALL</button>
    </div>

    <div id="history-list"><div style="font-family:'Share Tech Mono',monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// LOADING...</div></div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
const API = '/api';
const token = () => localStorage.getItem('currensee_token');
const authHeaders = () => ({ 'Accept':'application/json','Authorization':'Bearer '+token() });

let history = [];

async function loadHistory() {
    if (!token()) { document.getElementById('history-list').innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// LOGIN TO VIEW HISTORY</div>'; return; }
    const res = await fetch(`${API}/history`, { headers: authHeaders() });
    const data = await res.json();
    history = data.history || data.data || data || [];
    renderList();
    renderCharts();
}

function renderList() {
    const el = document.getElementById('history-list');
    if (!history.length) { el.innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:40px;">// NO CONVERSIONS YET</div>'; return; }
    el.innerHTML = history.map((h,i) => `
        <div class="cyber-card animate-in stagger-${Math.min(i+1,5)}" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;">
            <div>
                <div style="font-family:'Share Tech Mono',monospace;font-size:.7rem;color:var(--green);">${h.from_currency} → ${h.to_currency}</div>
                <div style="font-family:'DM Sans',sans-serif;font-size:.8rem;margin-top:2px;">${parseFloat(h.amount).toLocaleString()} <span style="color:var(--text-dim)">→</span> ${parseFloat(h.converted_amount).toFixed(4)}</div>
                <div style="font-family:'Share Tech Mono',monospace;font-size:.5rem;color:var(--text-dim);margin-top:4px;">${new Date(h.created_at).toLocaleString()}</div>
            </div>
            <button onclick="deleteOne(${h.id}, this)" style="background:none;border:none;cursor:pointer;color:var(--text-dim);" title="Delete">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </button>
        </div>`).join('');
}

function renderCharts() {
    // Pie: top currencies
    const currencyCount = {};
    history.forEach(h => { currencyCount[h.to_currency] = (currencyCount[h.to_currency]||0) + 1; });
    const labels = Object.keys(currencyCount).slice(0, 8);
    const vals = labels.map(k => currencyCount[k]);

    new ApexCharts(document.getElementById('pie-chart'), {
        chart:{ type:'donut', height:220, background:'transparent', toolbar:{show:false} },
        series: vals,
        labels: labels,
        colors:['#AAFF00','#7ACC00','#55AA00','#DDFF44','#BBFF22','#99EE00','#66CC00','#44AA00'],
        legend:{ position:'bottom', fontFamily:'Share Tech Mono', fontSize:'10px', labels:{colors:'#888'} },
        dataLabels:{ style:{fontFamily:'Share Tech Mono',fontSize:'10px'} },
        plotOptions:{ pie:{ donut:{ size:'60%', labels:{ show:true, total:{ show:true, label:'TOTAL', fontFamily:'Share Tech Mono', fontSize:'11px', color:'#555', formatter: w => w.globals.seriesTotals.reduce((a,b)=>a+b,0) } } } } },
        stroke:{ colors:['#000'] },
        tooltip:{ theme:'dark', style:{fontFamily:'Share Tech Mono'} },
        theme:{ mode:'dark' },
        noData:{ text:'// NO DATA', style:{color:'#555',fontFamily:'Share Tech Mono'} },
    }).render();

    // Bar: daily volume
    const dailyMap = {};
    history.forEach(h => { const d = h.created_at?.split('T')[0] || ''; if(d) dailyMap[d] = (dailyMap[d]||0) + 1; });
    const days = Object.keys(dailyMap).sort().slice(-7);
    const counts = days.map(d => dailyMap[d]);

    new ApexCharts(document.getElementById('bar-chart'), {
        chart:{ type:'bar', height:160, background:'transparent', toolbar:{show:false}, animations:{enabled:true,easing:'easeinout',speed:600} },
        series:[{ name:'Conversions', data: counts }],
        xaxis:{ categories: days.map(d => d.slice(5)), labels:{ style:{colors:'#555',fontFamily:'Share Tech Mono',fontSize:'9px'} }, axisBorder:{show:false}, axisTicks:{show:false} },
        yaxis:{ labels:{ style:{colors:'#555',fontFamily:'Share Tech Mono',fontSize:'9px'} } },
        colors:['#AAFF00'],
        plotOptions:{ bar:{ borderRadius:2, columnWidth:'60%' } },
        grid:{ borderColor:'rgba(170,255,0,0.08)', strokeDashArray:4 },
        tooltip:{ theme:'dark', style:{fontFamily:'Share Tech Mono'} },
        theme:{ mode:'dark' },
        noData:{ text:'// NO DATA', style:{color:'#555',fontFamily:'Share Tech Mono'} },
    }).render();
}

async function deleteOne(id, btn) {
    if (!confirm('Delete this record?')) return;
    btn.disabled = true;
    await fetch(`${API}/history/${id}`, { method:'DELETE', headers: authHeaders() });
    history = history.filter(h => h.id !== id);
    renderList();
    renderCharts();
}

async function clearAll() {
    if (!confirm('Clear ALL conversion history?')) return;
    await fetch(`${API}/history`, { method:'DELETE', headers: authHeaders() });
    history = [];
    renderList();
    renderCharts();
}

loadHistory();
</script>
@endpush
