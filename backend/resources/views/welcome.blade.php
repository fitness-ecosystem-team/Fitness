<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Fitness Ecosystem API</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #08110d;
            --surface: #101d17;
            --surface-2: #16251d;
            --line: rgba(255, 255, 255, .1);
            --text: #f2f8f4;
            --muted: #9eb0a6;
            --green: #69e69a;
            --green-deep: #173d27;
            --red: #ff8585;
            --red-deep: #401f22;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 15% 10%, rgba(72, 211, 128, .12), transparent 32rem),
                radial-gradient(circle at 85% 85%, rgba(70, 140, 100, .08), transparent 30rem),
                var(--bg);
        }
        .shell { width: min(1120px, calc(100% - 40px)); margin: 0 auto; }
        header { display: flex; align-items: center; justify-content: space-between; padding: 28px 0; }
        .brand { display: flex; align-items: center; gap: 12px; font-weight: 700; letter-spacing: -.02em; }
        .mark {
            display: grid; place-items: center; width: 38px; height: 38px;
            border: 1px solid rgba(105, 230, 154, .4); border-radius: 12px;
            color: var(--green); background: rgba(105, 230, 154, .08);
        }
        .mark svg { width: 21px; height: 21px; }
        .environment {
            padding: 7px 11px; border: 1px solid var(--line); border-radius: 8px;
            color: var(--muted); background: rgba(255,255,255,.03); font-size: 12px; font-weight: 600;
        }
        main { padding: 8vh 0 64px; }
        .eyebrow { color: var(--green); font-size: 13px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        h1 { max-width: 760px; margin: 16px 0 20px; font-size: clamp(42px, 7vw, 76px); line-height: .98; letter-spacing: -.06em; }
        .lead { max-width: 650px; margin: 0; color: var(--muted); font-size: clamp(17px, 2vw, 20px); line-height: 1.65; }
        .grid { display: grid; grid-template-columns: 1.25fr .75fr; gap: 18px; margin-top: 58px; }
        .card { padding: 26px; border: 1px solid var(--line); border-radius: 20px; background: rgba(16, 29, 23, .82); box-shadow: 0 24px 80px rgba(0,0,0,.22); }
        .card h2 { margin: 0 0 22px; font-size: 16px; letter-spacing: -.02em; }
        .status-row { display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 17px 0; border-top: 1px solid var(--line); }
        .status-row:first-of-type { border-top: 0; padding-top: 0; }
        .status-row:last-child { padding-bottom: 0; }
        .status-label { color: var(--muted); font-size: 14px; }
        .badge { display: inline-flex; align-items: center; gap: 8px; padding: 7px 11px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .badge.ok { color: var(--green); background: var(--green-deep); }
        .badge.error { color: var(--red); background: var(--red-deep); }
        .dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; box-shadow: 0 0 0 4px rgba(105,230,154,.09); }
        .endpoint { display: flex; align-items: center; gap: 12px; padding: 13px 0; border-top: 1px solid var(--line); font-size: 13px; }
        .endpoint:first-of-type { border-top: 0; padding-top: 0; }
        .method { min-width: 42px; color: var(--green); font-size: 10px; font-weight: 800; letter-spacing: .08em; }
        code { overflow-wrap: anywhere; color: #d9e6de; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
        footer { display: flex; justify-content: space-between; gap: 20px; padding: 30px 0; border-top: 1px solid var(--line); color: var(--muted); font-size: 12px; }
        @media (max-width: 760px) {
            .shell { width: min(100% - 28px, 1120px); }
            header { padding: 20px 0; }
            main { padding-top: 7vh; }
            .grid { grid-template-columns: 1fr; margin-top: 42px; }
            .card { padding: 21px; }
            footer { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header>
            <div class="brand">
                <span class="mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M7 5v14M17 5v14M3.5 9v6M20.5 9v6M7 12h10" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>Fitness Ecosystem</span>
            </div>
            <span class="environment">Production</span>
        </header>

        <main>
            <div class="eyebrow">Core infrastructure</div>
            <h1>The engine behind your wellness ecosystem.</h1>
            <p class="lead">Authentication, profiles, goals, activity, nutrition and connected wellness modules are available through the Fitness Ecosystem API.</p>

            <section class="grid">
                <div class="card">
                    <h2>System status</h2>
                    <div class="status-row">
                        <span class="status-label">API service</span>
                        <span class="badge {{ $apiOnline ? 'ok' : 'error' }}"><span class="dot"></span>{{ $apiOnline ? 'Operational' : 'Unavailable' }}</span>
                    </div>
                    <div class="status-row">
                        <span class="status-label">PostgreSQL database</span>
                        <span class="badge {{ $databaseConnected ? 'ok' : 'error' }}"><span class="dot"></span>{{ $databaseConnected ? 'Connected' : 'Unavailable' }}</span>
                    </div>
                    <div class="status-row">
                        <span class="status-label">API version</span>
                        <strong>v1</strong>
                    </div>
                </div>

                <div class="card">
                    <h2>Core endpoints</h2>
                    <div class="endpoint"><span class="method">POST</span><code>/api/register</code></div>
                    <div class="endpoint"><span class="method">POST</span><code>/api/login</code></div>
                    <div class="endpoint"><span class="method">GET</span><code>/api/profile</code></div>
                    <div class="endpoint"><span class="method">GET</span><code>/api/modules</code></div>
                </div>
            </section>
        </main>

        <footer>
            <span>Fitness Ecosystem API</span>
            <span>Secure infrastructure for connected wellness modules.</span>
        </footer>
    </div>
</body>
</html>
