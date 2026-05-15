<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CurrenSee Admin — @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Black+Ops+One&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap"
        rel="stylesheet">

    <!-- Tailwind -->
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
            --text-dim: #666;
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

        /* ── MATRIX CANVAS ── */
        #matrix-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.07;
            pointer-events: none;
        }

        /* ── SCANLINES ── */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: repeating-linear-gradient(0deg,
                    transparent,
                    transparent 2px,
                    rgba(0, 0, 0, 0.08) 2px,
                    rgba(0, 0, 0, 0.08) 4px);
            pointer-events: none;
            z-index: 1;
        }

        /* ── GRID OVERLAY ── */
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

        /* ── LAYOUT ── */
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

        .topbar-tag {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.6rem;
            color: var(--green-dim);
            border: 1px solid var(--border);
            padding: 2px 8px;
            letter-spacing: 2px;
        }

        /* ── MAIN CONTENT ── */
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

        /* ── CARDS ── */
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

        /* ── STAT CARDS ── */
        .stat-value {
            font-family: 'Black Ops One', cursive;
            font-size: 2rem;
            color: var(--green);
            text-shadow: 0 0 15px var(--green), 0 0 40px rgba(170, 255, 0, 0.3);
            line-height: 1;
        }

        .stat-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.6rem;
            color: var(--text-dim);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 4px;
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

        /* ── BUTTONS ── */
        .btn-green {
            background: var(--green);
            color: #000;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-green:hover {
            box-shadow: 0 0 20px var(--green), 0 0 40px rgba(170, 255, 0, 0.3);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--green);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 8px 16px;
            border: 1px solid var(--border-hot);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline:hover {
            background: var(--green-glow);
            box-shadow: 0 0 12px var(--green-glow);
        }

        .btn-danger {
            background: transparent;
            color: #ff4444;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 8px 16px;
            border: 1px solid rgba(255, 68, 68, 0.4);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-danger:hover {
            background: rgba(255, 68, 68, 0.1);
            box-shadow: 0 0 12px rgba(255, 68, 68, 0.2);
        }

        /* ── INPUTS ── */
        .cyber-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            padding: 10px 14px;
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

        /* ── TABLE ── */
        .cyber-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
        }

        .cyber-table th {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.58rem;
            letter-spacing: 2px;
            color: var(--green);
            text-transform: uppercase;
            padding: 8px 10px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .cyber-table td {
            padding: 10px;
            border-bottom: 1px solid rgba(170, 255, 0, 0.05);
            color: var(--text);
            vertical-align: middle;
        }

        .cyber-table tr:hover td {
            background: var(--green-glow2);
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
            background: rgba(255, 68, 68, 0.05);
        }

        .badge-yellow {
            color: #ffcc00;
            border: 1px solid rgba(255, 204, 0, 0.4);
            background: rgba(255, 204, 0, 0.05);
        }

        .badge-gray {
            color: var(--text-dim);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ── ALERTS ── */
        .flash-success {
            border: 1px solid var(--green);
            background: var(--green-glow);
            color: var(--green);
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 10px 16px;
            margin-bottom: 16px;
        }

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
                box-shadow: 0 0 8px var(--green-glow);
            }

            50% {
                box-shadow: 0 0 20px var(--green), 0 0 40px rgba(170, 255, 0, 0.2);
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
        }

        .stagger-2 {
            animation-delay: 0.10s;
        }

        .stagger-3 {
            animation-delay: 0.15s;
        }

        .stagger-4 {
            animation-delay: 0.20s;
        }

        .stagger-5 {
            animation-delay: 0.25s;
        }

        .stagger-6 {
            animation-delay: 0.30s;
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

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .pagination a,
        .pagination span {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.65rem;
            padding: 6px 10px;
            border: 1px solid var(--border);
            color: var(--text-dim);
            text-decoration: none;
            transition: all 0.2s;
        }

        .pagination a:hover {
            border-color: var(--green);
            color: var(--green);
        }

        .pagination .active span {
            border-color: var(--green);
            color: var(--green);
            background: var(--green-glow);
        }
    </style>
</head>

<body>

    <!-- Matrix Rain Canvas -->
    <canvas id="matrix-bg"></canvas>

    <div id="app">
        <!-- Top Bar -->
        <div class="topbar">
            <span class="topbar-logo flicker">CURRENSEE</span>
            <span class="topbar-tag">ADMIN_SYS</span>
            <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
                @csrf
                <button type="submit"
                    style="background:none;border:none;cursor:pointer;color:var(--text-dim);font-family:'Share Tech Mono',monospace;font-size:0.6rem;letter-spacing:1px;">
                    [EXIT]
                </button>
            </form>
        </div>

        <!-- Flash Messages -->
        <div class="main-content" style="padding-bottom:0">
            @if (session('success'))
                <div class="flash-success">&gt; {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="flash-error">&gt; {{ session('error') }}</div>
            @endif
        </div>

        <!-- Page Content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- Bottom Nav -->
    <nav class="bottom-nav">
        <a href="{{ route('admin.dashboard') }}"
            class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
                <rect x="14" y="14" width="7" height="7" />
            </svg>
            DASH
        </a>
        <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
            </svg>
            USERS
        </a>
        <a href="{{ route('admin.feedback') }}"
            class="nav-item {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            FEEDBACK
        </a>
        <a href="{{ route('admin.news') }}" class="nav-item {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <path
                    d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2" />
                <path d="M18 14h-8M15 18h-5M10 6h8v4h-8z" />
            </svg>
            NEWS
        </a>
        <a href="{{ route('admin.conversions') }}"
            class="nav-item {{ request()->routeIs('admin.conversions*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <polyline points="17 1 21 5 17 9" />
                <path d="M3 11V9a4 4 0 0 1 4-4h14M7 23l-4-4 4-4" />
                <path d="M21 13v2a4 4 0 0 1-4 4H3" />
            </svg>
            LOGS
        </a>
    </nav>

    <!-- Matrix Rain Script -->
    <script>
        const canvas = document.getElementById('matrix-bg');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const chars = 'CURRENSEE01アイウエオカキクケコ$€£¥₿▓░█';
        const cols = Math.floor(canvas.width / 16);
        const drops = Array(cols).fill(1);

        function drawMatrix() {
            ctx.fillStyle = 'rgba(0,0,0,0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#AAFF00';
            ctx.font = '13px Share Tech Mono, monospace';

            drops.forEach((y, i) => {
                const char = chars[Math.floor(Math.random() * chars.length)];
                ctx.fillText(char, i * 16, y * 16);
                if (y * 16 > canvas.height && Math.random() > 0.975) drops[i] = 0;
                drops[i]++;
            });
        }

        setInterval(drawMatrix, 50);

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>

    @stack('scripts')
</body>

</html>
