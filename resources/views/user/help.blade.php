@extends('layouts.app')

@section('title', 'Help Center')

@section('content')

<div class="animate-in stagger-1">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
        <a href="/app/profile" style="color:var(--text-dim);text-decoration:none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <div class="section-title" style="margin:0;">HELP CENTER</div>
    </div>

    <!-- Search FAQs -->
    <div class="cyber-card animate-in stagger-2" style="padding:12px 14px;">
        <label class="cyber-label">// SEARCH FAQ</label>
        <input type="text" id="faq-search" class="cyber-input" placeholder="Type a keyword..." oninput="filterFAQ()">
    </div>

    <!-- FAQ sections -->
    <div id="faq-container" class="animate-in stagger-3"></div>

    <!-- Contact support -->
    <div class="section-title">CONTACT SUPPORT</div>
    <div class="cyber-card animate-in stagger-4" style="padding:16px;">
        <div style="font-family:'DM Sans',sans-serif;font-size:.8rem;color:var(--text-dim);margin-bottom:16px;line-height:1.6;">
            Can't find your answer? Our support team is available to help you.
        </div>
        <div style="display:grid;gap:10px;">
            <a href="mailto:support@currensee.com" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--text);">
                <div style="width:36px;height:36px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
                </div>
                <div>
                    <div style="font-family:'Share Tech Mono',monospace;font-size:.6rem;color:var(--text-dim);">EMAIL</div>
                    <div style="font-family:'DM Sans',sans-serif;font-size:.8rem;color:var(--green);">support@currensee.com</div>
                </div>
            </a>
            <div class="cyber-divider"></div>
            <a href="/app/feedback" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:var(--text);">
                <div style="width:36px;height:36px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div>
                    <div style="font-family:'Share Tech Mono',monospace;font-size:.6rem;color:var(--text-dim);">IN-APP</div>
                    <div style="font-family:'DM Sans',sans-serif;font-size:.8rem;color:var(--green);">Submit a Feedback Report</div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const faqs = [
    {
        category: 'GETTING STARTED',
        items: [
            { q: 'How do I create an account?', a: 'Tap ACCOUNT in the bottom nav, then tap "CREATE ACCOUNT". Fill in your name, email and password to register.' },
            { q: 'Is CurrenSee free to use?', a: 'Yes — CurrenSee is completely free. All currency conversions, rate alerts, and news are available at no charge.' },
            { q: 'What currencies are supported?', a: 'CurrenSee supports 150+ world currencies. Go to RATES in the bottom nav to browse the full list.' },
        ]
    },
    {
        category: 'CURRENCY CONVERSION',
        items: [
            { q: 'How accurate are the exchange rates?', a: 'Rates are fetched from live financial data sources and updated in real-time. They may differ slightly from bank rates due to provider margins.' },
            { q: 'Are conversions saved automatically?', a: 'Yes — when you are logged in, every conversion is automatically saved to your History.' },
            { q: 'How do I swap the currencies?', a: 'On the Converter page, tap the swap button (⇄) between the FROM and TO selectors to instantly reverse the pair.' },
        ]
    },
    {
        category: 'RATE ALERTS',
        items: [
            { q: 'How do rate alerts work?', a: 'Set a target rate for any currency pair. When the live rate crosses your threshold, the alert is marked as triggered and you are notified.' },
            { q: 'Can I pause an alert without deleting it?', a: 'Yes — tap PAUSE on any active alert to temporarily disable it. Tap ACTIVATE to re-enable it later.' },
            { q: 'How many alerts can I set?', a: 'There is no hard limit on the number of alerts you can create.' },
        ]
    },
    {
        category: 'NOTIFICATIONS',
        items: [
            { q: 'How do I manage notification preferences?', a: 'Go to ACCOUNT → Notifications section. Toggle rate alert and app update notifications on or off.' },
            { q: 'Why am I not receiving notifications?', a: 'Ensure notifications are enabled in ACCOUNT settings and that your device or browser has notification permissions granted.' },
        ]
    },
    {
        category: 'ACCOUNT & SECURITY',
        items: [
            { q: 'How do I set my default base currency?', a: 'Go to ACCOUNT → Preferences, select your preferred base currency, then tap SAVE PREFERENCES.' },
            { q: 'How do I log out?', a: 'Scroll to the bottom of the ACCOUNT page and tap LOGOUT.' },
            { q: 'Is my data secure?', a: 'Yes — all data is transmitted over HTTPS and authentication uses secure token-based sessions.' },
        ]
    },
    {
        category: 'FEEDBACK & SUPPORT',
        items: [
            { q: 'How do I report a bug?', a: 'Go to ACCOUNT → Submit Feedback, select "BUG REPORT" as the category, and describe the issue.' },
            { q: 'How long does support take to respond?', a: 'Our team typically responds within 24-48 hours on business days.' },
        ]
    },
];

let openIndex = null;

function renderFAQ(data) {
    const container = document.getElementById('faq-container');
    if (!data.length) { container.innerHTML = '<div style="font-family:Share Tech Mono,monospace;font-size:.65rem;color:var(--text-dim);text-align:center;padding:30px;">// NO RESULTS FOUND</div>'; return; }
    container.innerHTML = data.map((section, si) => `
        <div style="margin-bottom:4px;">
            <div class="section-title">${section.category}</div>
            ${section.items.map((item, ii) => {
                const id = `faq-${si}-${ii}`;
                return `
                <div class="cyber-card" style="margin-bottom:6px;padding:0;overflow:hidden;">
                    <button onclick="toggleFAQ('${id}')" style="width:100%;background:none;border:none;cursor:pointer;padding:14px;display:flex;align-items:center;justify-content:space-between;gap:12px;text-align:left;">
                        <span style="font-family:'DM Sans',sans-serif;font-size:.82rem;color:var(--text);font-weight:500;">${item.q}</span>
                        <svg id="icon-${id}" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" style="flex-shrink:0;transition:transform .3s;"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div id="${id}" style="max-height:0;overflow:hidden;transition:max-height .3s ease;">
                        <div style="padding:0 14px 14px;font-family:'DM Sans',sans-serif;font-size:.78rem;color:var(--text-dim);line-height:1.6;border-top:1px solid var(--border);">${item.a}</div>
                    </div>
                </div>`;
            }).join('')}
        </div>`).join('');
}

function toggleFAQ(id) {
    const el = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    const isOpen = el.style.maxHeight !== '0px' && el.style.maxHeight !== '';
    // Close all
    document.querySelectorAll('[id^="faq-"]').forEach(e => { if(e.style) { e.style.maxHeight='0px'; } });
    document.querySelectorAll('[id^="icon-faq-"]').forEach(e => { e.style.transform='rotate(0deg)'; });
    if (!isOpen) { el.style.maxHeight = el.scrollHeight + 'px'; icon.style.transform = 'rotate(180deg)'; }
}

function filterFAQ() {
    const q = document.getElementById('faq-search').value.toLowerCase();
    if (!q) { renderFAQ(faqs); return; }
    const filtered = faqs.map(section => ({
        ...section,
        items: section.items.filter(item => item.q.toLowerCase().includes(q) || item.a.toLowerCase().includes(q))
    })).filter(s => s.items.length);
    renderFAQ(filtered);
}

renderFAQ(faqs);
</script>
@endpush
