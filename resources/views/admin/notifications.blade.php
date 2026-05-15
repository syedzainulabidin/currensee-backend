@extends('layouts.admin')
@section('title', 'Notifications')

@section('content')

    <!-- Header -->
    <div class="animate-in" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:2px;margin-bottom:4px;">
            &gt; NOTIFICATION_BROADCAST
        </div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--text);">
            Notifications
        </h1>
    </div>

    <!-- System Status -->
    <div class="cyber-card animate-in stagger-1" style="margin-bottom:20px;">
        <div class="section-title" style="margin-bottom:12px;">SYSTEM STATUS</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    FCM STATUS</div>
                <span class="badge badge-green" style="animation:pulse-glow 2s ease-in-out infinite;">● CONNECTED</span>
            </div>
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    TOTAL USERS</div>
                <div style="font-family:'Black Ops One',cursive;font-size:1.1rem;color:var(--green);">
                    {{ \App\Models\User::where('role', 'user')->count() }}
                </div>
            </div>
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    PUSH OPTED-IN</div>
                <div style="font-family:'Black Ops One',cursive;font-size:1.1rem;color:var(--green);">
                    {{ \App\Models\User::where('role', 'user')->whereJsonDoesntContain('preferences->notifications->push_enabled', false)->count() }}
                </div>
            </div>
            <div>
                <div
                    style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:4px;">
                    ACTIVE ALERTS</div>
                <div style="font-family:'Black Ops One',cursive;font-size:1.1rem;color:#ffcc00;">
                    {{ \App\Models\RateAlert::where('is_active', true)->count() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Broadcast Form -->
    <div class="section-title animate-in stagger-2">SEND BROADCAST</div>

    <div class="cyber-card animate-in stagger-2" style="margin-bottom:20px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--text-dim);letter-spacing:1px;margin-bottom:16px;padding:8px;border:1px solid rgba(255,204,0,0.2);background:rgba(255,204,0,0.03);">
            ⚠ FCM INTEGRATION REQUIRED — Connect Firebase Cloud Messaging in your .env to enable push delivery.
        </div>

        <div style="margin-bottom:16px;">
            <label class="cyber-label">// NOTIFICATION TITLE</label>
            <input type="text" class="cyber-input" id="notif-title" placeholder="e.g. RATE ALERT: USD/PKR"
                maxlength="65">
        </div>

        <div style="margin-bottom:16px;">
            <label class="cyber-label">// MESSAGE BODY</label>
            <textarea class="cyber-input" id="notif-body" rows="3" placeholder="Your message to all users..." maxlength="200"
                style="resize:vertical;"></textarea>
            <div style="font-family:'Share Tech Mono',monospace;font-size:0.55rem;color:var(--text-dim);margin-top:4px;">
                <span id="char-count">0</span>/200 CHARS
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <label class="cyber-label">// TARGET AUDIENCE</label>
            <select class="cyber-input" id="notif-target"
                style="appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23AAFF00' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 12px center;padding-right:36px;cursor:pointer;">
                <option value="all">ALL USERS</option>
                <option value="push_enabled">PUSH ENABLED ONLY</option>
            </select>
        </div>

        <button class="btn-green" onclick="sendBroadcast()" id="send-btn">
            BROADCAST NOTIFICATION
        </button>

        <div id="send-status"
            style="display:none;margin-top:12px;font-family:'Share Tech Mono',monospace;font-size:0.65rem;letter-spacing:1px;padding:10px;border:1px solid var(--border);">
        </div>
    </div>

    <!-- Info Cards -->
    <div class="section-title animate-in stagger-3">INTEGRATION GUIDE</div>

    <div class="cyber-card animate-in stagger-3" style="margin-bottom:10px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--green);letter-spacing:1px;margin-bottom:8px;">
            STEP 01 — FIREBASE SETUP</div>
        <div style="font-size:0.78rem;color:var(--text-dim);line-height:1.6;">
            Create a Firebase project → Enable Cloud Messaging → Download service account JSON → Add credentials to <span
                style="color:var(--green);font-family:'Share Tech Mono',monospace;">.env</span>
        </div>
    </div>

    <div class="cyber-card animate-in stagger-4" style="margin-bottom:10px;">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--green);letter-spacing:1px;margin-bottom:8px;">
            STEP 02 — RATE ALERTS (AUTO)</div>
        <div style="font-size:0.78rem;color:var(--text-dim);line-height:1.6;">
            Create a scheduled Laravel command that runs every hour, checks active alerts against live rates from <span
                style="color:var(--green);font-family:'Share Tech Mono',monospace;">ExchangeRateService</span>, and fires
            FCM push when threshold is hit.
        </div>
    </div>

    <div class="cyber-card animate-in stagger-5">
        <div
            style="font-family:'Share Tech Mono',monospace;font-size:0.6rem;color:var(--green);letter-spacing:1px;margin-bottom:8px;">
            STEP 03 — FLUTTER APP</div>
        <div style="font-size:0.78rem;color:var(--text-dim);line-height:1.6;">
            Add <span style="color:var(--green);font-family:'Share Tech Mono',monospace;">firebase_messaging</span> package
            to Flutter. Register device tokens on login → store in user preferences JSON column.
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.getElementById('notif-body').addEventListener('input', function() {
            document.getElementById('char-count').textContent = this.value.length;
        });

        async function sendBroadcast() {
            const title = document.getElementById('notif-title').value.trim();
            const body = document.getElementById('notif-body').value.trim();
            const status = document.getElementById('send-status');
            const btn = document.getElementById('send-btn');

            if (!title || !body) {
                status.style.display = 'block';
                status.style.color = '#ff4444';
                status.style.borderColor = 'rgba(255,68,68,0.4)';
                status.textContent = '> ERROR: TITLE AND MESSAGE REQUIRED';
                return;
            }

            btn.textContent = 'TRANSMITTING...';
            btn.disabled = true;

            // Placeholder — wire to your FCM endpoint when ready
            setTimeout(() => {
                status.style.display = 'block';
                status.style.color = '#ffcc00';
                status.style.borderColor = 'rgba(255,204,0,0.4)';
                status.textContent = '> FCM NOT CONFIGURED — Connect Firebase to enable delivery.';
                btn.textContent = 'BROADCAST NOTIFICATION';
                btn.disabled = false;
            }, 1000);
        }
    </script>
@endpush
