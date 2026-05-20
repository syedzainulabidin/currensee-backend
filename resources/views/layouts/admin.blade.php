<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CurrenSee Admin — @yield('title', 'Dashboard')</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- Toastify -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 240px;
            --bg: #0e0e0e;
            --sidebar-bg: #0a0a0a;
            --surface: #161616;
            --surface2: #1e1e1e;
            --surface3: #252525;
            --border: rgba(255,255,255,0.07);
            --border2: rgba(255,255,255,0.12);
            --green: #AAFF00;
            --green-dim: rgba(170,255,0,0.08);
            --green-border: rgba(170,255,0,0.25);
            --text: #f0f0f0;
            --text-2: #888;
            --text-3: #4a4a4a;
            --red: #ef4444;
            --red-dim: rgba(239,68,68,0.1);
            --yellow: #f59e0b;
            --yellow-dim: rgba(245,158,11,0.1);
            --blue: #6366f1;
            --radius: 10px;
            --radius-sm: 6px;
        }

        html, body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        /* ── LAYOUT ── */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo-icon {
            width: 34px; height: 34px;
            background: var(--green);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-logo-icon svg { width: 18px; height: 18px; color: #000; }

        .sidebar-logo-text {
            font-weight: 800;
            font-size: 15px;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .sidebar-logo-sub {
            font-size: 10px;
            color: var(--text-3);
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-3);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 8px 10px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: var(--radius-sm);
            color: var(--text-2);
            font-weight: 500;
            font-size: 13.5px;
            transition: all 0.15s;
            margin-bottom: 2px;
            cursor: pointer;
        }

        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

        .nav-item:hover {
            background: var(--surface2);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--green-dim);
            color: var(--green);
            border: 1px solid var(--green-border);
        }

        .nav-item.active svg { color: var(--green); }

        .nav-badge {
            margin-left: auto;
            background: var(--red);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid var(--border);
        }

        .sidebar-admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            margin-bottom: 6px;
        }

        .sidebar-avatar {
            width: 30px; height: 30px;
            background: var(--green);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #000;
            flex-shrink: 0;
        }

        .sidebar-admin-name { font-size: 12.5px; font-weight: 600; color: var(--text); }
        .sidebar-admin-role { font-size: 10px; color: var(--text-3); }

        /* ── MAIN CONTENT ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOP BAR ── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(14,14,14,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-2);
        }

        .topbar-left svg { width: 14px; height: 14px; }
        .topbar-page { color: var(--text); font-weight: 600; }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-time {
            font-size: 12px;
            color: var(--text-3);
            font-variant-numeric: tabular-nums;
        }

        /* ── PAGE CONTENT ── */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.5px; line-height: 1.2; }
        .page-subtitle { font-size: 13px; color: var(--text-2); margin-top: 3px; }

        .page-actions { display: flex; align-items: center; gap: 8px; }

        /* ── CARDS ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .card-title { font-size: 14px; font-weight: 600; color: var(--text); }
        .card-subtitle { font-size: 12px; color: var(--text-2); margin-top: 2px; }
        .card-body { padding: 20px; }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 20px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            transition: border-color 0.2s;
        }

        .stat-card:hover { border-color: var(--border2); }

        .stat-card-icon {
            width: 38px; height: 38px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .stat-card-icon svg { width: 18px; height: 18px; }

        .stat-card-value { font-size: 26px; font-weight: 800; letter-spacing: -1px; line-height: 1; margin-bottom: 4px; }
        .stat-card-label { font-size: 12px; color: var(--text-2); font-weight: 500; }
        .stat-card-delta { font-size: 11px; margin-top: 6px; font-weight: 600; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }

        thead tr { border-bottom: 1px solid var(--border); }

        th {
            padding: 11px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-2);
            letter-spacing: 0.4px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        td {
            padding: 13px 16px;
            font-size: 13.5px;
            color: var(--text);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr { transition: background 0.1s; }
        tbody tr:hover td { background: var(--surface2); }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-green { background: var(--green-dim); color: var(--green); border: 1px solid var(--green-border); }
        .badge-red { background: var(--red-dim); color: var(--red); border: 1px solid rgba(239,68,68,0.25); }
        .badge-yellow { background: var(--yellow-dim); color: var(--yellow); border: 1px solid rgba(245,158,11,0.25); }
        .badge-gray { background: var(--surface3); color: var(--text-2); border: 1px solid var(--border2); }
        .badge-blue { background: rgba(99,102,241,0.1); color: var(--blue); border: 1px solid rgba(99,102,241,0.25); }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
            text-decoration: none;
        }

        .btn svg { width: 14px; height: 14px; }

        .btn-primary { background: var(--green); color: #000; }
        .btn-primary:hover { background: #c4ff2a; box-shadow: 0 0 20px rgba(170,255,0,0.3); }

        .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border2); }
        .btn-secondary:hover { background: var(--surface3); }

        .btn-danger-soft { background: var(--red-dim); color: var(--red); border: 1px solid rgba(239,68,68,0.2); }
        .btn-danger-soft:hover { background: rgba(239,68,68,0.2); }

        .btn-warning-soft { background: var(--yellow-dim); color: var(--yellow); border: 1px solid rgba(245,158,11,0.2); }
        .btn-warning-soft:hover { background: rgba(245,158,11,0.2); }

        .btn-ghost { background: transparent; color: var(--text-2); padding: 7px 10px; }
        .btn-ghost:hover { background: var(--surface2); color: var(--text); }

        .btn-sm { padding: 6px 11px; font-size: 12px; }
        .btn-sm svg { width: 12px; height: 12px; }

        /* ── INPUTS ── */
        .input-group { position: relative; display: flex; }
        .input-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-3); pointer-events: none; }
        .input-icon svg { width: 14px; height: 14px; }

        input[type="text"], input[type="email"], input[type="search"], select, textarea {
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            padding: 9px 12px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            width: 100%;
        }

        .has-icon-left input, .has-icon-left select { padding-left: 34px; }

        input:focus, select:focus, textarea:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(170,255,0,0.08);
        }

        input::placeholder, textarea::placeholder { color: var(--text-3); }

        select option { background: var(--surface2); }

        /* ── FILTER BAR ── */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .filter-bar .input-group { flex: 1; min-width: 200px; max-width: 340px; }

        /* ── TOOLBAR (card header with search) ── */
        .toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .toolbar-search { flex: 1; min-width: 180px; max-width: 300px; }

        .toolbar-filters { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-left: auto; }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid var(--border2);
            background: var(--surface2);
            color: var(--text-2);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .filter-chip:hover { border-color: var(--green-border); color: var(--text); }
        .filter-chip.active { background: var(--green-dim); border-color: var(--green-border); color: var(--green); }

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            font-size: 12.5px;
            color: var(--text-2);
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-pages { display: flex; align-items: center; gap: 4px; }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border-radius: var(--radius-sm);
            font-size: 12.5px;
            font-weight: 500;
            border: 1px solid var(--border2);
            background: var(--surface2);
            color: var(--text-2);
            text-decoration: none;
            transition: all 0.15s;
        }

        .page-btn:hover { border-color: var(--green-border); color: var(--green); }
        .page-btn.current { background: var(--green); color: #000; border-color: var(--green); font-weight: 700; }
        .page-btn.disabled { opacity: 0.35; cursor: not-allowed; pointer-events: none; }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--text-2);
        }

        .empty-icon {
            width: 48px; height: 48px;
            background: var(--surface2);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }

        .empty-icon svg { width: 22px; height: 22px; color: var(--text-3); }
        .empty-title { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .empty-desc { font-size: 13px; color: var(--text-2); }

        /* ── AVATAR ── */
        .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
            flex-shrink: 0;
        }

        /* ── DIVIDER ── */
        .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }

        /* ── ALERT FLASH ── */
        .flash-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: var(--radius);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .flash-bar svg { width: 16px; height: 16px; flex-shrink: 0; }
        .flash-success { background: rgba(170,255,0,0.07); border: 1px solid var(--green-border); color: var(--green); }
        .flash-error { background: var(--red-dim); border: 1px solid rgba(239,68,68,0.25); color: var(--red); }

        /* ── TABS ── */
        .tabs { display: flex; gap: 2px; border-bottom: 1px solid var(--border); margin-bottom: 0; }

        .tab {
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-2);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            text-decoration: none;
            transition: all 0.15s;
        }

        .tab:hover { color: var(--text); }
        .tab.active { color: var(--green); border-bottom-color: var(--green); }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--surface3); border-radius: 4px; }

        /* ── CONFIRM MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show { display: flex; }

        .modal {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: 14px;
            padding: 28px;
            width: 100%;
            max-width: 400px;
            margin: 16px;
        }

        .modal-icon {
            width: 44px; height: 44px;
            background: var(--red-dim);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }

        .modal-icon svg { width: 22px; height: 22px; color: var(--red); }
        .modal-title { font-size: 17px; font-weight: 700; margin-bottom: 8px; }
        .modal-desc { font-size: 13px; color: var(--text-2); line-height: 1.6; margin-bottom: 24px; }
        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }

        /* ── UTILS ── */
        .text-green { color: var(--green); }
        .text-red { color: var(--red); }
        .text-yellow { color: var(--yellow); }
        .text-dim { color: var(--text-2); }
        .text-muted { color: var(--text-3); }
        .font-mono { font-family: 'SF Mono', 'Fira Code', monospace; font-size: 12px; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mb-6 { margin-bottom: 24px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
        @media (max-width: 900px) {
            .grid-4 { grid-template-columns: 1fr 1fr; }
            .grid-3 { grid-template-columns: 1fr 1fr; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Confirm Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal">
        <div class="modal-icon"><i data-lucide="alert-triangle"></i></div>
        <div class="modal-title" id="modalTitle">Are you sure?</div>
        <div class="modal-desc" id="modalDesc">This action cannot be undone.</div>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button class="btn btn-danger-soft" id="modalConfirmBtn">Confirm</button>
        </div>
    </div>
</div>

<div class="admin-layout">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <i data-lucide="circle-dollar-sign"></i>
            </div>
            <div>
                <div class="sidebar-logo-text">CurrenSee</div>
                <div class="sidebar-logo-sub">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Overview</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                Dashboard
            </a>

            <div class="nav-section-label" style="margin-top:12px;">Manage</div>

            <a href="{{ route('admin.users') }}"
               class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i data-lucide="users"></i>
                Users
            </a>

            <a href="{{ route('admin.conversions') }}"
               class="nav-item {{ request()->routeIs('admin.conversions*') ? 'active' : '' }}">
                <i data-lucide="arrow-left-right"></i>
                Conversions
            </a>

            <a href="{{ route('admin.alerts') }}"
               class="nav-item {{ request()->routeIs('admin.alerts*') ? 'active' : '' }}">
                <i data-lucide="bell"></i>
                Rate Alerts
            </a>

            <a href="{{ route('admin.news') }}"
               class="nav-item {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
                <i data-lucide="newspaper"></i>
                News Feed
            </a>

            <a href="{{ route('admin.feedback') }}"
               class="nav-item {{ request()->routeIs('admin.feedback*') ? 'active' : '' }}">
                <i data-lucide="message-square"></i>
                Feedback
                @php $newFeedback = \App\Models\Feedback::where('status','new')->count(); @endphp
                @if($newFeedback > 0)
                    <span class="nav-badge">{{ $newFeedback }}</span>
                @endif
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-admin-info">
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="sidebar-admin-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="sidebar-admin-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:flex-start;">
                    <i data-lucide="log-out"></i>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="main-wrap">

        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <i data-lucide="home" style="width:14px;height:14px;color:var(--text-3);"></i>
                <span style="color:var(--text-3);">/</span>
                <span class="topbar-page">@yield('title', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                <span class="topbar-time" id="topbar-clock"></span>
                <div style="width:1px;height:18px;background:var(--border2);"></div>
                <span style="font-size:12px;color:var(--text-2);">{{ auth()->user()->email ?? '' }}</span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">

            {{-- Flash Messages via Toastify (handled in script below) --}}
            @if(session('success'))
                <div id="flash-success" data-msg="{{ session('success') }}" style="display:none;"></div>
            @endif
            @if(session('error'))
                <div id="flash-error" data-msg="{{ session('error') }}" style="display:none;"></div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    // Init Lucide icons
    lucide.createIcons();

    // Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('topbar-clock').textContent =
            now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Toastify flash messages
    const successEl = document.getElementById('flash-success');
    const errorEl   = document.getElementById('flash-error');

    if (successEl) {
        Toastify({
            text: successEl.dataset.msg,
            duration: 4000,
            gravity: 'top',
            position: 'right',
            style: { background: '#161616', border: '1px solid rgba(170,255,0,0.3)', color: '#AAFF00', borderRadius: '10px', fontSize: '13px', fontFamily: 'Inter, sans-serif', fontWeight: '500', boxShadow: '0 8px 32px rgba(0,0,0,0.4)' },
            avatar: '✓',
        }).showToast();
    }

    if (errorEl) {
        Toastify({
            text: errorEl.dataset.msg,
            duration: 5000,
            gravity: 'top',
            position: 'right',
            style: { background: '#161616', border: '1px solid rgba(239,68,68,0.3)', color: '#ef4444', borderRadius: '10px', fontSize: '13px', fontFamily: 'Inter, sans-serif', fontWeight: '500', boxShadow: '0 8px 32px rgba(0,0,0,0.4)' },
            avatar: '✕',
        }).showToast();
    }

    // Confirm Modal
    let pendingForm = null;

    function confirmAction(title, desc, formId) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalDesc').textContent  = desc;
        pendingForm = document.getElementById(formId);
        document.getElementById('confirmModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.remove('show');
        pendingForm = null;
    }

    document.getElementById('modalConfirmBtn').addEventListener('click', () => {
        if (pendingForm) pendingForm.submit();
        closeModal();
    });

    document.getElementById('confirmModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
</script>

@stack('scripts')
</body>
</html>
