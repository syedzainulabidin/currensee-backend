<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CurrenSee</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #0e0e0e; font-family: 'Segoe UI', Arial, sans-serif; color: #e0e0e0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #161616; border-radius: 16px; overflow: hidden; border: 1px solid #2a2a2a; }
        .header { background: linear-gradient(135deg, #0e0e0e 0%, #1a2a1a 100%); padding: 48px 40px 36px; text-align: center; border-bottom: 1px solid #2a2a2a; }
        .logo { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 24px; }
        .logo-icon { width: 44px; height: 44px; background: #AAFF00; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 18px; color: #000; }
        .logo-text { font-size: 22px; font-weight: 700; color: #fff; letter-spacing: -0.5px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #fff; line-height: 1.3; }
        .header h1 span { color: #AAFF00; }
        .body { padding: 40px; }
        .greeting { font-size: 16px; color: #b0b0b0; margin-bottom: 24px; line-height: 1.6; }
        .greeting strong { color: #fff; }
        .message { background: #1e1e1e; border: 1px solid #2a2a2a; border-left: 3px solid #AAFF00; border-radius: 10px; padding: 20px 24px; margin-bottom: 32px; font-size: 15px; color: #c0c0c0; line-height: 1.7; }
        .features { margin-bottom: 36px; }
        .features-title { font-size: 13px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
        .feature { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 14px; }
        .feature-icon { width: 32px; height: 32px; background: #1e2e1e; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; margin-top: 1px; }
        .feature-text { flex: 1; }
        .feature-text strong { display: block; font-size: 14px; color: #e0e0e0; margin-bottom: 2px; }
        .feature-text span { font-size: 13px; color: #888; line-height: 1.5; }
        .cta { text-align: center; margin-bottom: 36px; }
        .cta-btn { display: inline-block; background: #AAFF00; color: #000; font-weight: 700; font-size: 15px; padding: 14px 36px; border-radius: 10px; text-decoration: none; letter-spacing: 0.3px; }
        .divider { height: 1px; background: #2a2a2a; margin-bottom: 28px; }
        .closing { font-size: 14px; color: #888; line-height: 1.7; margin-bottom: 8px; }
        .closing strong { color: #c0c0c0; }
        .footer { background: #111; padding: 24px 40px; text-align: center; border-top: 1px solid #1e1e1e; }
        .footer p { font-size: 12px; color: #555; line-height: 1.7; }
        .footer a { color: #AAFF00; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        {{-- Header --}}
        <div class="header">
            <div class="logo">
                <div class="logo-icon">C$</div>
                <div class="logo-text">CurrenSee</div>
            </div>
            <h1>Welcome aboard, <span>{{ $user->name }}</span> 👋</h1>
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">
                Hey <strong>{{ $user->name }}</strong>, great to have you here. Your CurrenSee account is ready and waiting for you.
            </p>

            <div class="message">
                We built CurrenSee to make currency tracking effortless — whether you're converting on the go, keeping an eye on exchange rates, or staying updated with financial news. Everything you need, in one place, updated in real time.
            </div>

            <div class="features">
                <div class="features-title">Here's what you can do</div>

                <div class="feature">
                    <div class="feature-icon">💱</div>
                    <div class="feature-text">
                        <strong>Live Currency Conversion</strong>
                        <span>Convert between 150+ currencies with real-time rates, instantly.</span>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">🔔</div>
                    <div class="feature-text">
                        <strong>Rate Alerts</strong>
                        <span>Set a target rate and we'll notify you the moment it's hit.</span>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">📈</div>
                    <div class="feature-text">
                        <strong>Historical Trends</strong>
                        <span>View 30-day charts and track how currencies move over time.</span>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">📰</div>
                    <div class="feature-text">
                        <strong>Currency News</strong>
                        <span>Stay ahead with the latest financial news and market updates.</span>
                    </div>
                </div>
            </div>

            <div class="cta">
                <a href="#" class="cta-btn">Open CurrenSee →</a>
            </div>

            <div class="divider"></div>

            <p class="closing">
                If you have any questions, run into anything, or just want to share feedback — we're always here.<br><br>
                <strong>Happy converting,</strong><br>
                The CurrenSee Team
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>
                You're receiving this because you created a CurrenSee account.<br>
                &copy; {{ date('Y') }} CurrenSee. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
