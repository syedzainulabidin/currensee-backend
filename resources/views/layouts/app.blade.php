<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>CurrenSee — @yield('title', 'Currency')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Black+Ops+One&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --green: #AAFF00;
            --green-dim: #7ACC00;
            --green-glow: rgba(170, 255, 0, 0.15);
            --green-glow2: rgba(170, 255, 0, 0.05);
            --black: #000000;
            --surface: #080808;
            --surface2: #0f0f0f;
            --border: rgba(170, 255, 0, 0.2);
            --border-hot: rgba(170, 255, 0, 0.6);
            --text: #e0e0e0;
            --text-dim: #555;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            background: var(--black);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px,
                    rgba(0, 0, 0, 0.1) 2px, rgba(0, 0, 0, 0.1) 4px);
            pointer-events: none;
            z-index: 1;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--green-glow2) 1px, transparent 1px),
                linear-gradient(90deg, var(--green-glow2) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        #app {
            position: relative;
            z-index: 2;
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            padding-bottom: 80px;
        }

        /* ── TOP BAR ── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(0, 0, 0, 0.92);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-logo {
            font-family: 'Black Ops One', cursive;
            font-size: 1.2rem;
            color: var(--green);
            text-shadow: 0 0 10px var(--green), 0 0 30px rgba(170, 255, 0, 0.3);
            letter-spacing: 2px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-tag {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.6rem;
            color: var(--text-dim);
            letter-spacing: 1px;
        }

        /* ── MAIN ── */
        .main-content {
            padding: 20px 16px;
        }

        /* ── BOTTOM NAV ── */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            z-index: 50;
            background: rgba(0, 0, 0, 0.95);
            border-top: 1px solid var(--border);
            backdrop-filter: blur(16px);
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 10px 0 14px;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: var(--text-dim);
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.55rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.2s;
            padding: 4px 12px;
            position: relative;
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.5;
        }

        .nav-item.active,
        .nav-item:hover {
            color: var(--green);
            text-shadow: 0 0 8px var(--green);
        }

        .nav-item.active svg,
        .nav-item:hover svg {
            filter: drop-shadow(0 0 6px var(--green));
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 2px;
            background: var(--green);
            box-shadow: 0 0 10px var(--green);
        }

        /* ── CYBER CARD ── */
        .cyber-card {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
            clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 12px 100%, 0 calc(100% - 12px));
            transition: border-color 0.3s;
        }

        .cyber-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 12px;
            height: 12px;
            border-top: 1px solid var(--green);
            border-right: 1px solid var(--green);
        }

        .cyber-card:hover {
            border-color: var(--border-hot);
            box-shadow: 0 0 20px var(--green-glow);
        }

        /* ── AMOUNT DISPLAY ── */
        .amount-display {
            font-family: 'Black Ops One', cursive;
            color: var(--green);
            text-shadow: 0 0 15px var(--green), 0 0 40px rgba(170, 255, 0, 0.3);
            line-height: 1;
        }

        /* ── INPUTS ── */
        .cyber-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            padding: 12px 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .cyber-input:focus {
            border-color: var(--green);
            box-shadow: 0 0 12px var(--green-glow);
        }

        .cyber-input::placeholder {
            color: var(--text-dim);
        }

        select.cyber-input {
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23AAFF00' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }

        .cyber-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.6rem;
            color: var(--text-dim);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: block;
        }

        /* ── BUTTONS ── */
        .btn-green {
            background: var(--green);
            color: #000;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 13px 24px;
            border: none;
            cursor: pointer;
            clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
            transition: all 0.2s;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-green:hover {
            box-shadow: 0 0 25px var(--green), 0 0 50px rgba(170, 255, 0, 0.2);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--green);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 10px 20px;
            border: 1px solid var(--border-hot);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-outline:hover {
            background: var(--green-glow);
        }

        /* ── SECTION TITLE ── */
        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--green);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, var(--border), transparent);
        }

        /* ── BADGE ── */
        .badge {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.55rem;
            letter-spacing: 1px;
            padding: 2px 8px;
            text-transform: uppercase;
        }

        .badge-green {
            color: var(--green);
            border: 1px solid var(--green);
            background: var(--green-glow2);
        }

        .badge-red {
            color: #ff4444;
            border: 1px solid rgba(255, 68, 68, 0.4);
        }

        .badge-yellow {
            color: #ffcc00;
            border: 1px solid rgba(255, 204, 0, 0.4);
        }

        /* ── FLASH ── */
        .flash-error {
            border: 1px solid #ff4444;
            background: rgba(255, 68, 68, 0.05);
            color: #ff4444;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 10px 16px;
            margin-bottom: 16px;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                text-shadow: 0 0 15px var(--green), 0 0 40px rgba(170, 255, 0, 0.3);
            }

            50% {
                text-shadow: 0 0 25px var(--green), 0 0 60px rgba(170, 255, 0, 0.5);
            }
        }

        @keyframes flicker {

            0%,
            95%,
            100% {
                opacity: 1;
            }

            96% {
                opacity: 0.8;
            }

            97% {
                opacity: 1;
            }

            98% {
                opacity: 0.6;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }

        .glow-pulse {
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .flicker {
            animation: flicker 5s infinite;
        }

        .stagger-1 {
            animation-delay: 0.05s;
            opacity: 0;
        }

        .stagger-2 {
            animation-delay: 0.10s;
            opacity: 0;
        }

        .stagger-3 {
            animation-delay: 0.15s;
            opacity: 0;
        }

        .stagger-4 {
            animation-delay: 0.20s;
            opacity: 0;
        }

        .stagger-5 {
            animation-delay: 0.25s;
            opacity: 0;
        }

        /* ── LOADING ── */
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-top-color: var(--green);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--black);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--green-dim);
        }

        /* ── DIVIDER ── */
        .cyber-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 16px 0;
        }
    </style>
</head>

<body>

    <div id="app">
        <div class="topbar">
            <span class="topbar-logo flicker">CURRENSEE</span>
            <div class="topbar-right">
                <span class="user-tag" id="user-tag">SYS_ONLINE</span>
                <button onclick="logout()" id="logout-btn"
                    style="display:none;background:none;border:none;cursor:pointer;color:var(--text-dim);font-family:'Share Tech Mono',monospace;font-size:0.6rem;letter-spacing:1px;">
                    [EXIT]
                </button>
            </div>
        </div>

        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Bottom Nav -->
    <nav class="bottom-nav">
        <a href="/app/converter" class="nav-item {{ request()->is('app/converter') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
            CONVERT
        </a>
        <a href="/app/currencies" class="nav-item {{ request()->is('app/currencies') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M14.5 9A3.5 3.5 0 0 0 8 11c0 2 1.5 3 3.5 3.5S15 16 15 18a3.5 3.5 0 0 1-6.5 1.5"/><line x1="12" y1="6" x2="12" y2="8"/><line x1="12" y1="19" x2="12" y2="21"/></svg>
            RATES
        </a>
        <a href="/app/alerts" class="nav-item {{ request()->is('app/alerts') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            ALERTS
        </a>
        <a href="/app/news" class="nav-item {{ request()->is('app/news') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
            NEWS
        </a>
        <a href="/app/profile" class="nav-item {{ request()->is('app/profile','app/feedback','app/help','app/history') ? 'active' : '' }}" id="nav-account">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            ACCOUNT
        </a>
    </nav>

    <script>
        // Auth state
        const _layoutToken = localStorage.getItem('currensee_token');
        const _layoutUser = JSON.parse(localStorage.getItem('currensee_user') || 'null');
        if (_layoutToken && _layoutUser) {
            document.getElementById('user-tag').textContent = _layoutUser.name.toUpperCase().substring(0, 8);
            document.getElementById('logout-btn').style.display = 'inline';
        }

        async function logout() {
            const token = localStorage.getItem('currensee_token');
            if (token) {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                });
            }
            localStorage.removeItem('currensee_token');
            localStorage.removeItem('currensee_user');
            window.location.href = '/app/login';
        }
    </script>

    @stack('scripts')
</body>

</html>
