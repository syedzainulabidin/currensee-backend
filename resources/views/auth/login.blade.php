<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CurrenSee Admin — Sign In</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0a0a;
            --surface: #141414;
            --surface2: #1c1c1c;
            --border: rgba(255,255,255,0.07);
            --border2: rgba(255,255,255,0.12);
            --green: #AAFF00;
            --green-dim: rgba(170,255,0,0.06);
            --green-border: rgba(170,255,0,0.2);
            --text: #f0f0f0;
            --text-2: #888;
            --text-3: #444;
            --red: #ef4444;
            --red-dim: rgba(239,68,68,0.08);
        }

        html, body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Subtle grid background */
        body {
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .login-outer {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        /* Logo */
        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 36px;
        }

        .logo-icon {
            width: 40px; height: 40px;
            background: var(--green);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .logo-icon svg { width: 20px; height: 20px; color: #000; }

        .logo-text {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .logo-sub {
            font-size: 11px;
            color: var(--text-3);
            font-weight: 500;
            margin-top: 1px;
        }

        /* Card */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
        }

        .card-header-text {
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 19px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 5px;
        }

        .card-subtitle {
            font-size: 13px;
            color: var(--text-2);
        }

        /* Error banner */
        .error-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--red-dim);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--red);
        }

        .error-banner svg { width: 15px; height: 15px; flex-shrink: 0; }

        /* Form */
        .field { margin-bottom: 16px; }

        label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 7px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border2);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            padding: 10px 14px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(170,255,0,0.08);
        }

        input::placeholder { color: var(--text-3); }

        .btn-submit {
            width: 100%;
            background: var(--green);
            color: #000;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            padding: 11px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.15s, box-shadow 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: #c4ff2a;
            box-shadow: 0 0 24px rgba(170,255,0,0.25);
        }

        .btn-submit svg { width: 15px; height: 15px; }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: var(--text-3);
        }
    </style>
</head>
<body>

<div class="login-outer">
    <div class="login-box">

        <!-- Logo -->
        <div class="logo-area">
            <div class="logo-icon">
                <i data-lucide="circle-dollar-sign"></i>
            </div>
            <div>
                <div class="logo-text">CurrenSee</div>
                <div class="logo-sub">Admin Panel</div>
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-header-text">
                <div class="card-title">Welcome back</div>
                <div class="card-subtitle">Sign in to your admin account</div>
            </div>

            @if($errors->any())
                <div class="error-banner">
                    <i data-lucide="alert-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"
                           placeholder="admin@currensee.com"
                           value="{{ old('email') }}"
                           autocomplete="email" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••"
                           autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn-submit">
                    <i data-lucide="log-in"></i>
                    Sign in
                </button>
            </form>
        </div>

        <div class="login-footer">CurrenSee &copy; {{ date('Y') }} — Restricted access</div>

    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
