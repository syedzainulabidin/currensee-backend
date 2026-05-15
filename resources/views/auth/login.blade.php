<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>CurrenSee — Admin Access</title>

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
            background: #000;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            overflow: hidden;
        }

        #matrix-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            opacity: 0.08;
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

        .login-wrap {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            max-width: 480px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
        }

        /* ── LOGO AREA ── */
        .logo-block {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease forwards;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
            border: 1px solid var(--border-hot);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            position: relative;
            clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .logo-icon svg {
            width: 28px;
            height: 28px;
            stroke: var(--green);
            filter: drop-shadow(0 0 8px var(--green));
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 10px;
            height: 10px;
            border-top: 1px solid var(--green);
            border-right: 1px solid var(--green);
        }

        .logo-name {
            font-family: 'Black Ops One', cursive;
            font-size: 2rem;
            color: var(--green);
            text-shadow: 0 0 15px var(--green), 0 0 40px rgba(170, 255, 0, 0.3);
            letter-spacing: 4px;
        }

        .logo-sub {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.6rem;
            color: var(--text-dim);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* ── FORM CARD ── */
        .login-card {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 28px 24px;
            position: relative;
            clip-path: polygon(0 0, calc(100% - 16px) 0, 100% 16px, 100% 100%, 16px 100%, 0 calc(100% - 16px));
            animation: fadeInUp 0.6s ease 0.2s both;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 16px;
            height: 16px;
            border-top: 1px solid var(--green);
            border-right: 1px solid var(--green);
        }

        .login-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 16px;
            height: 16px;
            border-bottom: 1px solid var(--green);
            border-left: 1px solid var(--green);
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.65rem;
            color: var(--green);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, var(--border), transparent);
        }

        .field-group {
            margin-bottom: 18px;
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

        .error-msg {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.6rem;
            color: #ff4444;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        .flash-error {
            border: 1px solid #ff4444;
            background: rgba(255, 68, 68, 0.05);
            color: #ff4444;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 1px;
            padding: 10px 14px;
            margin-bottom: 18px;
        }

        .btn-login {
            width: 100%;
            background: var(--green);
            color: #000;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            padding: 14px;
            border: none;
            cursor: pointer;
            clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-login:hover {
            box-shadow: 0 0 30px var(--green), 0 0 60px rgba(170, 255, 0, 0.2);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* ── BOTTOM TAG ── */
        .bottom-tag {
            margin-top: 24px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.55rem;
            color: var(--text-dim);
            letter-spacing: 2px;
            text-align: center;
            animation: fadeInUp 0.6s ease 0.4s both;
        }

        /* ── CORNER DECORATIONS ── */
        .corner-tl,
        .corner-br {
            position: fixed;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.5rem;
            color: var(--text-dim);
            letter-spacing: 1px;
            z-index: 3;
        }

        .corner-tl {
            top: 16px;
            left: 16px;
        }

        .corner-br {
            bottom: 16px;
            right: 16px;
        }

        /* ── ANIMATIONS ── */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 10px var(--green-glow);
            }

            50% {
                box-shadow: 0 0 25px var(--green), 0 0 50px rgba(170, 255, 0, 0.2);
            }
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .cursor {
            animation: blink 1s step-end infinite;
        }

        ::-webkit-scrollbar {
            width: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--green-dim);
        }
    </style>
</head>

<body>

    <canvas id="matrix-bg"></canvas>

    <div class="corner-tl">SYS:ADMIN_AUTH v1.0</div>
    <div class="corner-br">CURRENSEE // SECURE</div>

    <div class="login-wrap">

        <!-- Logo -->
        <div class="logo-block">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M14.5 9A3.5 3.5 0 0 0 8 11c0 2 1.5 3 3.5 3.5S15 16 15 18a3.5 3.5 0 0 1-6.5 1.5" />
                    <line x1="12" y1="6" x2="12" y2="8" />
                    <line x1="12" y1="19" x2="12" y2="21" />
                </svg>
            </div>
            <div class="logo-name">CURRENSEE</div>
            <div class="logo-sub">ADMIN TERMINAL<span class="cursor">_</span></div>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="card-title">AUTHENTICATE</div>

            @if ($errors->any())
                <div class="flash-error">&gt; {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="field-group">
                    <label class="cyber-label" for="email">// EMAIL ADDRESS</label>
                    <input type="email" id="email" name="email" class="cyber-input"
                        placeholder="admin@currensee.com" value="{{ old('email') }}" autocomplete="email" required>
                </div>

                <div class="field-group">
                    <label class="cyber-label" for="password">// PASSWORD</label>
                    <input type="password" id="password" name="password" class="cyber-input" placeholder="••••••••••••"
                        autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn-login">INITIATE ACCESS</button>
            </form>
        </div>

        <div class="bottom-tag">
            UNAUTHORIZED ACCESS IS PROHIBITED // CURRENSEE &copy; {{ date('Y') }}
        </div>

    </div>

    <script>
        const canvas = document.getElementById('matrix-bg');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        const chars = 'CURRENSEE01アイウエオ$€£¥₿ADMIN▓░';
        const cols = Math.floor(canvas.width / 16);
        const drops = Array(cols).fill(1);

        function draw() {
            ctx.fillStyle = 'rgba(0,0,0,0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#AAFF00';
            ctx.font = '13px monospace';
            drops.forEach((y, i) => {
                ctx.fillText(chars[Math.floor(Math.random() * chars.length)], i * 16, y * 16);
                if (y * 16 > canvas.height && Math.random() > 0.975) drops[i] = 0;
                drops[i]++;
            });
        }
        setInterval(draw, 50);
    </script>
</body>

</html>
