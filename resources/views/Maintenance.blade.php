<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KTTM — System Unavailable</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --maroon:        #A52C30;
    --maroon2:       #7E1F23;
    --maroon3:       #C1363A;
    --maroon-light:  rgba(165,44,48,0.10);
    --maroon-mid:    rgba(165,44,48,0.22);
    --gold:          #F0C860;
    --gold2:         #E8B857;
    --ink:           #0F172A;
    --muted:         #64748B;
    --line:          rgba(15,23,42,0.09);
    --card:          rgba(255,255,255,0.82);
    --bg:            #F1F4F9;
    --glass:         rgba(255,255,255,0.55);
    --glass-b:       rgba(255,255,255,0.75);
  }

  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  html { -webkit-font-smoothing: antialiased; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--ink);
    overflow: hidden;
    height: 100vh;
    cursor: none;
  }

  /* ── CUSTOM CURSOR ── */
  .cursor {
    position: fixed; width: 10px; height: 10px;
    border-radius: 50%; background: var(--maroon);
    pointer-events: none; z-index: 9999;
    mix-blend-mode: multiply;
    transform: translate(-50%, -50%);
  }
  .cursor-ring {
    position: fixed; width: 34px; height: 34px;
    border-radius: 50%; border: 1.5px solid var(--gold);
    pointer-events: none; z-index: 9998;
    transition: left 0.28s cubic-bezier(.17,.67,.35,1.1),
                top  0.28s cubic-bezier(.17,.67,.35,1.1);
    transform: translate(-50%, -50%);
    opacity: 0.7;
  }

  /* ── BACKGROUND ── */
  .bg-layer {
    position: fixed; inset: 0; z-index: 0;
    background-image: url('images/abstractBGIMAGE7.png');
    background-size: cover; background-position: center;
  }
  .bg-overlay {
    position: fixed; inset: 0; z-index: 1;
    background: linear-gradient(135deg,
      rgba(241,244,249,0.82) 0%,
      rgba(220,210,200,0.42) 45%,
      rgba(241,244,249,0.75) 100%);
  }

  /* Noise grain */
  .bg-grain {
    position: fixed; inset: 0; z-index: 2;
    pointer-events: none; opacity: 0.025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  }

  /* Floating orbs */
  .orb {
    position: fixed; border-radius: 50%;
    pointer-events: none; z-index: 1; filter: blur(80px);
  }
  .orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(165,44,48,0.13), transparent);
    top: -120px; left: -120px;
    animation: drift1 16s ease-in-out infinite;
  }
  .orb-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(240,200,96,0.16), transparent);
    bottom: -60px; right: -80px;
    animation: drift2 20s ease-in-out infinite;
  }
  .orb-3 {
    width: 260px; height: 260px;
    background: radial-gradient(circle, rgba(165,44,48,0.07), transparent);
    top: 40%; right: 22%;
    animation: drift1 24s ease-in-out infinite reverse;
  }

  @keyframes drift1 {
    0%,100% { transform: translate(0,0); }
    33%      { transform: translate(30px,-20px); }
    66%      { transform: translate(-18px,28px); }
  }
  @keyframes drift2 {
    0%,100% { transform: translate(0,0); }
    40%     { transform: translate(-24px,18px); }
    70%     { transform: translate(20px,-16px); }
  }

  /* ── NAV BAR ── */
  nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 50;
    height: 68px; padding: 0 2.5rem;
    display: flex; align-items: center; justify-content: space-between;
    background: rgba(241,244,249,0.75);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--line);
    box-shadow: 0 2px 16px rgba(15,23,42,0.06);
  }
  .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
  .nav-logo-badge {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.9rem; color: var(--gold);
    box-shadow: 0 4px 14px rgba(165,44,48,0.28);
  }
  .nav-logo-text  { font-size: 0.95rem; font-weight: 800; color: var(--ink); letter-spacing: -0.2px; }
  .nav-logo-sub   { font-family: 'DM Mono', monospace; font-size: 0.6rem; letter-spacing: 0.16em; text-transform: uppercase; color: var(--muted); }

  /* Mode chip in nav */
  .nav-mode-chip {
    display: inline-flex; align-items: center; gap: 7px;
    font-family: 'DM Mono', monospace;
    font-size: 0.62rem; font-weight: 700; letter-spacing: 0.14em;
    text-transform: uppercase; padding: 6px 14px; border-radius: 20px;
  }
  .chip-maintenance-nav {
    background: rgba(245,158,11,0.12);
    border: 1px solid rgba(245,158,11,0.30);
    color: #92400E;
  }
  .chip-debug-nav {
    background: rgba(165,44,48,0.10);
    border: 1px solid rgba(165,44,48,0.25);
    color: var(--maroon2);
  }
  .chip-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: currentColor;
    animation: pulse 2s infinite;
  }
  @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.35} }

  /* ── MAIN CONTENT ── */
  .page-wrap {
  position: relative;
  z-index: 10;

  height: calc(100vh - 68px);

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 24px 24px; /* top-bottom space */
  margin-top: 68px;

  box-sizing: border-box; /* ✅ IMPORTANT */
}

  .card {
    padding: 2.4rem 2.8rem 2.1rem;
    width: 470px; max-width: 92vw;
    text-align: center;
    animation: cardIn 0.7s cubic-bezier(.17,.67,.35,1.1) forwards;
    opacity: 0;
  }
  @keyframes cardIn {
    from { opacity: 0; transform: translateY(24px) scale(0.97); }
    to   { opacity: 1; transform: none; }
  }

  /* Icon block */
  .icon-wrap {
    width: 72px; height: 72px; border-radius: 22px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.6rem;
    position: relative;
  }
  .icon-wrap.mode-maintenance {
    background: linear-gradient(135deg, rgba(245,158,11,0.18), rgba(245,158,11,0.08));
    border: 1.5px solid rgba(245,158,11,0.3);
    box-shadow: 0 8px 28px rgba(245,158,11,0.15);
  }
  .icon-wrap.mode-debug {
    background: linear-gradient(135deg, var(--maroon-mid), var(--maroon-light));
    border: 1.5px solid rgba(165,44,48,0.28);
    box-shadow: 0 8px 28px rgba(165,44,48,0.18);
  }
  /* Pulse ring behind icon */
  .icon-wrap::before {
    content: '';
    position: absolute; inset: -8px; border-radius: 28px;
    border: 1px solid currentColor; opacity: 0.15;
    animation: ringPulse 3s ease-in-out infinite;
  }
  @keyframes ringPulse {
    0%,100% { transform: scale(1); opacity: 0.15; }
    50%     { transform: scale(1.08); opacity: 0.05; }
  }

  /* Eyebrow */
  .eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-family: 'DM Mono', monospace;
    font-size: 0.62rem; letter-spacing: 0.24em;
    text-transform: uppercase;
    padding: 0.4rem 1rem; border-radius: 20px;
    margin-bottom: 1.1rem;
  }
  .eyebrow.mode-maintenance {
    color: #92400E;
    background: rgba(245,158,11,0.12);
    border: 1px solid rgba(245,158,11,0.25);
  }
  .eyebrow.mode-debug {
    color: var(--maroon2);
    background: var(--maroon-light);
    border: 1px solid rgba(165,44,48,0.18);
  }
  .eyebrow::before {
    content: ''; width: 6px; height: 6px; border-radius: 50%;
    background: currentColor;
    box-shadow: 0 0 0 3px rgba(currentColor, 0.18);
    flex-shrink: 0;
    animation: pulse 2s infinite;
  }

  /* Title */
  .main-title {
    font-size: 2rem; font-weight: 800;
    color: var(--ink); letter-spacing: -0.7px;
    line-height: 1.1; margin-bottom: 0.9rem;
  }
  .main-title .accent { color: var(--maroon); }

  /* Description */
  .main-desc {
    font-size: 0.88rem; color: var(--muted);
    line-height: 1.75; max-width: 380px;
    margin: 0 auto 2rem; font-weight: 400;
  }

  /* Divider */
  .divider {
    border: none; border-top: 1px solid var(--line);
    margin: 0 0 1.8rem;
  }

  /* Countdown block */
  .countdown-wrap {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    margin-bottom: 1.8rem;
  }
  .countdown-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; letter-spacing: 0.22em;
    text-transform: uppercase; color: var(--muted);
  }
  .countdown-timer {
    font-family: 'DM Mono', monospace;
    font-size: 2rem; font-weight: 700;
    color: var(--ink); letter-spacing: 0.08em;
    line-height: 1;
  }
  .countdown-timer.mode-maintenance { color: #92400E; }
  .countdown-timer.mode-debug       { color: var(--maroon); }
  .countdown-sub {
    font-size: 0.72rem; color: var(--muted); font-weight: 500;
  }

  /* Status rows */
  .status-rows {
    display: flex; flex-direction: column; gap: 8px;
    margin-bottom: 1.8rem;
  }
  .status-row {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 14px; border-radius: 12px;
    background: rgba(255,255,255,0.6);
    border: 1px solid var(--line);
    text-align: left;
  }
  .status-row-icon {
    width: 30px; height: 30px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .status-row-body { flex: 1; }
  .status-row-label { font-size: 0.76rem; font-weight: 700; color: var(--ink); }
  .status-row-sub   { font-size: 0.64rem; color: var(--muted); margin-top: 1px; font-family: 'DM Mono', monospace; }
  .status-badge {
    font-family: 'DM Mono', monospace; font-size: 0.58rem;
    font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
    padding: 2px 8px; border-radius: 20px;
  }
  .badge-amber {
    background: rgba(245,158,11,0.12); color: #92400E;
    border: 1px solid rgba(245,158,11,0.25);
  }
  .badge-red {
    background: var(--maroon-light); color: var(--maroon);
    border: 1px solid rgba(165,44,48,0.22);
  }
  .badge-green {
    background: rgba(16,185,129,0.10); color: #065F46;
    border: 1px solid rgba(16,185,129,0.22);
  }

  /* Footer note */
  .footer-note {
    font-size: 0.72rem; color: var(--muted);
    line-height: 1.6; margin-top: 0.4rem;
  }
  .footer-note strong { color: var(--maroon); font-weight: 700; }

  /* Seal */
  .page-seal {
    position: fixed; right: 7%; bottom: 11%;
    width: 120px; height: 120px; z-index: 10; opacity: 0;
    animation: fadeIn 1.4s 1.3s forwards;
    pointer-events: none;
  }
  .seal-svg { width: 100%; height: 100%; animation: rotateSeal 30s linear infinite; }

  @keyframes fadeIn  { to { opacity: 1; } }
  @keyframes rotateSeal {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
  }
  @keyframes fadeUp  { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:none} }

  /* Scroll indicator (subtle, bottom center) */
  .bottom-tag {
    position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%);
    z-index: 10;
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    opacity: 0; animation: fadeIn 1s 1.2s forwards;
  }
  .bottom-tag span {
    font-family: 'DM Mono', monospace;
    font-size: 0.58rem; letter-spacing: 0.22em;
    text-transform: uppercase; color: var(--muted);
  }
  .bottom-line {
    width: 1px; height: 36px;
    background: linear-gradient(to bottom, var(--maroon), transparent);
    animation: scrollPulse 2.4s ease-in-out infinite;
  }
  @keyframes scrollPulse { 0%,100%{opacity:1} 50%{opacity:.2} }
</style>
</head>
<body>

{{-- Custom cursor --}}
<div class="cursor"   id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>

{{-- Background layers --}}
<div class="bg-layer"></div>
<div class="bg-overlay"></div>
<div class="bg-grain"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

{{-- Nav --}}
<nav>
  <a class="nav-logo" href="#">
    <div class="nav-logo-badge">K</div>
    <div>
      <div class="nav-logo-text">KTTM</div>
      <div class="nav-logo-sub">IP Records System</div>
    </div>
  </a>
  @php $isDebug = (isset($mode) && $mode === 'debug'); @endphp
  <div class="nav-mode-chip {{ $isDebug ? 'chip-debug-nav' : 'chip-maintenance-nav' }}">
    <span class="chip-dot"></span>
    {{ $isDebug ? 'Debug Mode Active' : 'Under Maintenance' }}
  </div>
</nav>

{{-- Page --}}
<div class="page-wrap">
  <div class="card">

    {{-- Icon --}}
    <div class="icon-wrap {{ $isDebug ? 'mode-debug' : 'mode-maintenance' }}" style="color:{{ $isDebug ? 'var(--maroon)' : '#D97706' }};">
      @if($isDebug)
        {{-- Bug / shield icon --}}
        <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
      @else
        {{-- Wrench / tool icon --}}
        <svg width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
        </svg>
      @endif
    </div>

    {{-- Eyebrow --}}
    <div class="eyebrow {{ $isDebug ? 'mode-debug' : 'mode-maintenance' }}">
      {{ $isDebug ? 'System Inspection' : 'Scheduled Maintenance' }}
    </div>

    {{-- Title --}}
    <h1 class="main-title">
      @if($isDebug)
        We're fixing <span class="accent">something</span>
      @else
        Back <span class="accent">shortly</span>
      @endif
    </h1>

    {{-- Description --}}
    <p class="main-desc">
      @if($isDebug)
        A bug has been reported and our team is actively investigating. The system is temporarily paused to ensure nothing else is affected.
      @else
        The KTTM IP Records System is currently undergoing scheduled maintenance. We're working to improve your experience and will be back shortly.
      @endif
    </p>

    <hr class="divider">

    {{-- Countdown — only shown if scheduled_at is set --}}
    @if(!empty($scheduledAt))
    <div class="countdown-wrap">
      <div class="countdown-label">Estimated return in</div>
      <div class="countdown-timer {{ $isDebug ? 'mode-debug' : 'mode-maintenance' }}" id="countdownDisplay">—</div>
      <div class="countdown-sub">{{ \Carbon\Carbon::parse($scheduledAt)->format('F j, Y · g:i A') }}</div>
    </div>
    <hr class="divider">
    @endif

    {{-- Status rows --}}
    <div class="status-rows">
      <div class="status-row">
        <div class="status-row-icon" style="background:{{ $isDebug ? 'var(--maroon-light)' : 'rgba(245,158,11,0.12)' }};color:{{ $isDebug ? 'var(--maroon)' : '#D97706' }};">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            @if($isDebug)
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            @else
              <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
            @endif
          </svg>
        </div>
        <div class="status-row-body">
          <div class="status-row-label">{{ $isDebug ? 'Debug Mode' : 'Maintenance Mode' }}</div>
          <div class="status-row-sub">{{ $isDebug ? 'Active bug investigation in progress' : 'System temporarily suspended' }}</div>
        </div>
        <span class="status-badge {{ $isDebug ? 'badge-red' : 'badge-amber' }}">Active</span>
      </div>

      <div class="status-row">
        <div class="status-row-icon" style="background:rgba(16,185,129,0.10);color:#059669;">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
          </svg>
        </div>
        <div class="status-row-body">
          <div class="status-row-label">Database</div>
          <div class="status-row-sub">Records are safe and intact</div>
        </div>
        <span class="status-badge badge-green">Secure</span>
      </div>

      <div class="status-row">
        <div class="status-row-icon" style="background:rgba(59,130,246,0.10);color:#1D4ED8;">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="2" y="2" width="20" height="8" rx="2"/>
            <rect x="2" y="14" width="20" height="8" rx="2"/>
            <line x1="6" y1="6" x2="6.01" y2="6"/>
            <line x1="6" y1="18" x2="6.01" y2="18"/>
          </svg>
        </div>
        <div class="status-row-body">
          <div class="status-row-label">File Storage</div>
          <div class="status-row-sub">All uploaded files are preserved</div>
        </div>
        <span class="status-badge" style="background:rgba(59,130,246,0.10);color:#1D4ED8;border:1px solid rgba(59,130,246,0.22);">Online</span>
      </div>
    </div>

    {{-- Footer note --}}
    <p class="footer-note">
      @if($isDebug)
        If you reported a bug, our developer is already on it. Access will be restored as soon as the issue is resolved. Contact <strong>KTTM Support</strong> if urgent.
      @else
        Thank you for your patience. If you need immediate assistance, please contact <strong>KTTM Support</strong> directly.
      @endif
    </p>

  </div>{{-- end card --}}
</div>{{-- end page-wrap --}}

{{-- Decorative seal --}}
<div class="page-seal">
  <svg class="seal-svg" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="65" cy="65" r="60" stroke="#A52C30" stroke-width="0.8" stroke-dasharray="3 5" opacity="0.4"/>
    <circle cx="65" cy="65" r="50" stroke="#F0C860" stroke-width="0.5" opacity="0.3"/>
    <text font-family="DM Mono" font-size="7.5" fill="#A52C30" letter-spacing="4" opacity="0.6">
      <textPath href="#sealCirclePath">KTTM  ·  INTELLECTUAL  PROPERTY  ·  RECORDS  ·  </textPath>
    </text>
    <defs>
      <path id="sealCirclePath" d="M 65,65 m -44,0 a 44,44 0 1,1 88,0 a 44,44 0 1,1 -88,0"/>
    </defs>
    <text x="65" y="60" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="13" fill="#0F172A" font-weight="800" letter-spacing="-0.5">KTTM</text>
    <text x="65" y="74" text-anchor="middle" font-family="DM Mono, monospace" font-size="5.5" fill="#A52C30" letter-spacing="2.5">OFFICE</text>
  </svg>
</div>



<script>
  /* ── Custom cursor ── */
  const cursor     = document.getElementById('cursor');
  const cursorRing = document.getElementById('cursorRing');
  document.addEventListener('mousemove', e => {
    cursor.style.left     = e.clientX + 'px';
    cursor.style.top      = e.clientY + 'px';
    cursorRing.style.left = e.clientX + 'px';
    cursorRing.style.top  = e.clientY + 'px';
  });

  /* ── Countdown timer ── */
  @if(!empty($scheduledAt))
  (function() {
    const target   = new Date('{{ \Carbon\Carbon::parse($scheduledAt)->toIso8601String() }}');
    const display  = document.getElementById('countdownDisplay');
    const isDebug  = {{ $isDebug ? 'true' : 'false' }};

    function pad(n) { return String(n).padStart(2, '0'); }

    function tick() {
      const diff = target - new Date();
      if (!display) return;

      if (diff <= 0) {
        display.textContent = 'Any moment now…';
        // Auto-refresh — maintenance may have ended
        setTimeout(() => window.location.reload(), 5000);
        return;
      }

      const d = Math.floor(diff / 86400000);
      const h = Math.floor((diff % 86400000) / 3600000);
      const m = Math.floor((diff % 3600000) / 60000);
      const s = Math.floor((diff % 60000) / 1000);

      display.textContent = (d > 0 ? d + 'd ' : '') + pad(h) + ':' + pad(m) + ':' + pad(s);
    }

    tick();
    setInterval(tick, 1000);
  })();
  @endif

  /* ── Auto-poll every 30s — check if system is back online ── */
  setInterval(async function() {
    try {
      const resp = await fetch('/maintenance/status', { method: 'GET', credentials: 'same-origin' });
      if (resp.ok) {
        const data = await resp.json();
        if (!data.active) {
          window.location.href = '/';
        }
      }
    } catch(e) { /* silent — server may be mid-restart */ }
  }, 30000);
</script>
</body>
</html>