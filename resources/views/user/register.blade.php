<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>CurrenSee — Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{--green:#AAFF00;--green-dim:#7ACC00;--green-glow:rgba(170,255,0,0.15);--green-glow2:rgba(170,255,0,0.05);--surface:#080808;--surface2:#0f0f0f;--border:rgba(170,255,0,0.2);--border-hot:rgba(170,255,0,0.6);--text:#e0e0e0;--text-dim:#555;}
        *{margin:0;padding:0;box-sizing:border-box;}
        html,body{background:#000;color:var(--text);font-family:'DM Sans',sans-serif;min-height:100vh;}
        body::after{content:'';position:fixed;inset:0;background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,0,0,.1) 2px,rgba(0,0,0,.1) 4px);pointer-events:none;z-index:1;}
        body::before{content:'';position:fixed;inset:0;background-image:linear-gradient(var(--green-glow2) 1px,transparent 1px),linear-gradient(90deg,var(--green-glow2) 1px,transparent 1px);background-size:40px 40px;pointer-events:none;z-index:0;}
        .wrap{position:relative;z-index:2;min-height:100vh;max-width:480px;margin:0 auto;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:32px 24px;}
        .logo-block{text-align:center;margin-bottom:28px;animation:fadeInDown .6s ease forwards;}
        .logo-icon{width:56px;height:56px;border:1px solid var(--border-hot);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;position:relative;clip-path:polygon(0 0,calc(100% - 10px) 0,100% 10px,100% 100%,10px 100%,0 calc(100% - 10px));animation:pulse-glow 3s ease-in-out infinite;}
        .logo-icon svg{width:24px;height:24px;stroke:var(--green);fill:none;filter:drop-shadow(0 0 8px var(--green));}
        .logo-icon::before{content:'';position:absolute;top:0;right:0;width:10px;height:10px;border-top:1px solid var(--green);border-right:1px solid var(--green);}
        .logo-name{font-family:'Black Ops One',cursive;font-size:1.6rem;color:var(--green);text-shadow:0 0 15px var(--green),0 0 40px rgba(170,255,0,.3);letter-spacing:4px;}
        .logo-sub{font-family:'Share Tech Mono',monospace;font-size:.55rem;color:var(--text-dim);letter-spacing:4px;text-transform:uppercase;margin-top:4px;}
        .card{width:100%;background:var(--surface);border:1px solid var(--border);padding:28px 24px;position:relative;clip-path:polygon(0 0,calc(100% - 16px) 0,100% 16px,100% 100%,16px 100%,0 calc(100% - 16px));animation:fadeInUp .6s ease .2s both;}
        .card::before{content:'';position:absolute;top:0;right:0;width:16px;height:16px;border-top:1px solid var(--green);border-right:1px solid var(--green);}
        .card::after{content:'';position:absolute;bottom:0;left:0;width:16px;height:16px;border-bottom:1px solid var(--green);border-left:1px solid var(--green);}
        .card-title{font-family:'Syne',sans-serif;font-weight:700;font-size:.65rem;color:var(--green);letter-spacing:4px;text-transform:uppercase;margin-bottom:20px;display:flex;align-items:center;gap:8px;}
        .card-title::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,var(--border),transparent);}
        .field{margin-bottom:14px;}
        .lbl{font-family:'Share Tech Mono',monospace;font-size:.6rem;color:var(--text-dim);letter-spacing:2px;text-transform:uppercase;margin-bottom:6px;display:block;}
        .inp{width:100%;background:var(--surface2);border:1px solid var(--border);color:var(--text);font-family:'DM Sans',sans-serif;font-size:.9rem;padding:12px 14px;outline:none;transition:border-color .2s,box-shadow .2s;}
        .inp:focus{border-color:var(--green);box-shadow:0 0 12px var(--green-glow);}
        .inp::placeholder{color:var(--text-dim);}
        .btn{width:100%;background:var(--green);color:#000;font-family:'Syne',sans-serif;font-weight:800;font-size:.85rem;letter-spacing:4px;text-transform:uppercase;padding:14px;border:none;cursor:pointer;clip-path:polygon(0 0,calc(100% - 8px) 0,100% 8px,100% 100%,8px 100%,0 calc(100% - 8px));transition:all .2s;margin-top:8px;}
        .btn:hover{box-shadow:0 0 30px var(--green),0 0 60px rgba(170,255,0,.2);transform:translateY(-1px);}
        .btn:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none;}
        .err{border:1px solid #ff4444;background:rgba(255,68,68,.05);color:#ff4444;font-family:'Share Tech Mono',monospace;font-size:.65rem;letter-spacing:1px;padding:10px 14px;margin-bottom:16px;display:none;}
        .bottom-tag{margin-top:20px;font-family:'Share Tech Mono',monospace;font-size:.55rem;color:var(--text-dim);letter-spacing:2px;text-align:center;animation:fadeInUp .6s ease .4s both;}
        .bottom-tag a{color:var(--green);text-decoration:none;}
        @keyframes fadeInDown{from{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}
        @keyframes fadeInUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
        @keyframes pulse-glow{0%,100%{box-shadow:0 0 10px var(--green-glow)}50%{box-shadow:0 0 25px var(--green),0 0 50px rgba(170,255,0,.2)}}
        ::-webkit-scrollbar{width:4px;}::-webkit-scrollbar-thumb{background:var(--green-dim);}
    </style>
</head>
<body>
<div class="wrap">
    <div class="logo-block">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24" stroke-width="1.5">
                <circle cx="12" cy="12" r="9"/>
                <path d="M14.5 9A3.5 3.5 0 0 0 8 11c0 2 1.5 3 3.5 3.5S15 16 15 18a3.5 3.5 0 0 1-6.5 1.5"/>
                <line x1="12" y1="6" x2="12" y2="8"/>
                <line x1="12" y1="19" x2="12" y2="21"/>
            </svg>
        </div>
        <div class="logo-name">CURRENSEE</div>
        <div class="logo-sub">CREATE ACCOUNT_</div>
    </div>

    <div class="card">
        <div class="card-title">NEW REGISTRATION</div>
        <div class="err" id="err"></div>
        <div class="field">
            <label class="lbl" for="name">// FULL NAME</label>
            <input type="text" id="name" class="inp" placeholder="John Doe" autocomplete="name">
        </div>
        <div class="field">
            <label class="lbl" for="email">// EMAIL ADDRESS</label>
            <input type="email" id="email" class="inp" placeholder="you@example.com" autocomplete="email">
        </div>
        <div class="field">
            <label class="lbl" for="password">// PASSWORD</label>
            <input type="password" id="password" class="inp" placeholder="Min. 8 characters" autocomplete="new-password">
        </div>
        <div class="field">
            <label class="lbl" for="confirm">// CONFIRM PASSWORD</label>
            <input type="password" id="confirm" class="inp" placeholder="Repeat password">
        </div>
        <button class="btn" id="submit-btn" onclick="doRegister()">CREATE ACCOUNT</button>
    </div>

    <div class="bottom-tag">
        ALREADY REGISTERED? <a href="/app/login">LOGIN HERE</a>
    </div>
</div>

<script>
    if (localStorage.getItem('currensee_token')) window.location.href = '/app/converter';

    async function doRegister() {
        const btn = document.getElementById('submit-btn');
        const err = document.getElementById('err');
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('confirm').value;
        err.style.display = 'none';
        if (!name || !email || !password || !password_confirmation) { err.textContent = '> ALL FIELDS REQUIRED'; err.style.display='block'; return; }
        if (password !== password_confirmation) { err.textContent = '> PASSWORDS DO NOT MATCH'; err.style.display='block'; return; }
        btn.disabled = true; btn.textContent = 'CREATING...';
        try {
            const res = await fetch('/api/register', { method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({name, email, password, password_confirmation}) });
            const data = await res.json();
            if (!res.ok) {
                const msg = data.errors ? Object.values(data.errors).flat()[0] : (data.message || 'REGISTRATION FAILED');
                err.textContent = '> ' + msg; err.style.display='block'; btn.disabled=false; btn.textContent='CREATE ACCOUNT'; return;
            }
            localStorage.setItem('currensee_token', data.token);
            localStorage.setItem('currensee_user', JSON.stringify(data.user));
            window.location.href = '/app/converter';
        } catch(e) { err.textContent = '> CONNECTION ERROR'; err.style.display='block'; btn.disabled=false; btn.textContent='CREATE ACCOUNT'; }
    }
</script>
</body>
</html>
