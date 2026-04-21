{{-- resources/views/dev_home.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Developer Panel</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      /* Brand */
      --maroon:  #A52C30;
      --maroon2: #7E1F23;
      --gold:    #F0C860;
      --gold2:   #E8B857;

      /* Dark theme base */
      --bg:        #080C14;
      --bg2:       #0D1220;
      --bg3:       #111827;
      --card:      #0F1724;
      --card2:     #141E2E;
      --line:      rgba(255,255,255,0.06);
      --line2:     rgba(255,255,255,0.10);
      --ink:       #F1F5F9;
      --ink2:      #CBD5E1;
      --muted:     #64748B;
      --muted2:    #94A3B8;

      /* Accent colors */
      --blue:      #3B82F6;
      --blue2:     #2563EB;
      --blue-dim:  rgba(59,130,246,0.12);
      --blue-mid:  rgba(59,130,246,0.22);
      --green:     #10B981;
      --green-dim: rgba(16,185,129,0.12);
      --red:       #EF4444;
      --red-dim:   rgba(239,68,68,0.12);
      --amber:     #F59E0B;
      --amber-dim: rgba(245,158,11,0.12);
      --purple:    #8B5CF6;
      --purple-dim:rgba(139,92,246,0.12);

      --sidebar-w: 72px;
      --pad-x: clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max: 1600px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { -webkit-font-smoothing: antialiased; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg); color: var(--ink);
      min-height: 100vh; overflow-x: hidden;
      padding-left: env(safe-area-inset-left);
      padding-right: env(safe-area-inset-right);
    }

    /* scrollbar */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

    /* ══════════════════════════════════════
       SIDEBAR — maroon brand, dark glow
    ══════════════════════════════════════ */
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0;
      width: var(--sidebar-w);
      background: linear-gradient(180deg, #070B14 0%, #0D1220 60%, #111827 100%);
      display: flex; flex-direction: column; align-items: center;
      padding: 20px 0; z-index: 50;
      box-shadow: 4px 0 32px rgba(0,0,0,.5), inset -1px 0 0 rgba(59,130,246,.08);
    }
    .nav-item {
      width: 48px; height: 48px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.35); cursor: pointer;
      transition: background .18s, color .18s;
      text-decoration: none; position: relative;
    }
    .nav-item:hover { background: rgba(59,130,246,.15); color: rgba(255,255,255,.85); }
    .nav-item.active {
      background: rgba(59,130,246,.2); color: #93C5FD;
      box-shadow: 0 4px 16px rgba(59,130,246,.2);
    }
    .nav-item.active::before {
      content: ''; position: absolute; left: 0; top: 50%;
      transform: translateY(-50%); width: 3px; height: 24px;
      background: var(--blue); border-radius: 0 3px 3px 0;
    }
    .nav-tooltip {
      position: absolute; left: calc(100% + 12px); top: 50%;
      transform: translateY(-50%);
      background: var(--bg3); border: 1px solid var(--line2);
      color: var(--ink2); font-size: 0.7rem; font-weight: 600;
      padding: 5px 10px; border-radius: 8px; white-space: nowrap;
      pointer-events: none; opacity: 0; transition: opacity .15s;
      letter-spacing: .04em; z-index: 999;
    }
    .nav-item:hover .nav-tooltip { opacity: 1; }
    .sidebar-nav { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; width: 100%; }
    .sidebar-bottom { display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .nav-divider {
      width: 32px; height: 1px;
      background: rgba(59,130,246,.15); margin: 8px 0;
    }

    /* ══════════════════════════════════════
       MAIN LAYOUT
    ══════════════════════════════════════ */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ══════════════════════════════════════
       TOPBAR
    ══════════════════════════════════════ */
    .topbar {
      min-height: 64px;
      background: var(--card);
      border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 8px var(--pad-x); position: sticky; top: 0; z-index: 40;
      box-shadow: 0 1px 24px rgba(0,0,0,.3);
    }
    .topbar-left {
      display: flex; align-items: center; gap: 12px;
      min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; }
    .page-title  {
      font-size: clamp(0.88rem, 0.35vw + 0.8rem, 1rem);
      font-weight: 800; letter-spacing: -.2px; color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-sub    {
      font-size: clamp(0.65rem, 0.15vw + 0.62rem, 0.7rem);
      color: var(--muted); font-weight: 500; margin-top: 1px;
      overflow-wrap: anywhere;
    }
    .topbar-right {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto; min-width: 0; max-width: 100%;
      justify-content: flex-end;
    }

    .status-chip {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 5px 12px; border-radius: 20px;
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.52rem, 0.1vw + 0.5rem, 0.6rem);
      font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
      flex: 0 1 auto; max-width: 100%;
    }
    .chip-online { background: var(--green-dim); border: 1px solid rgba(16,185,129,.25); color: var(--green); }
    .chip-dev    { background: var(--blue-dim);  border: 1px solid rgba(59,130,246,.25);  color: var(--blue); }
    .chip-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    .live-clock {
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.65rem, 0.12vw + 0.62rem, 0.72rem);
      font-weight: 700; color: var(--muted2);
      letter-spacing: .06em;
      flex-shrink: 0; white-space: nowrap;
    }

    /* ══════════════════════════════════════
       CONTENT
    ══════════════════════════════════════ */
    .content {
      padding: clamp(14px, 2.5vw, 20px) var(--pad-x);
      flex: 1;
      width: 100%; max-width: var(--shell-max); margin: 0 auto;
      box-sizing: border-box;
    }

    /* ══════════════════════════════════════
       HERO BANNER
    ══════════════════════════════════════ */
    .dev-hero {
      background: linear-gradient(135deg, #0a1628 0%, #0f1f3d 50%, #0d1829 100%);
      border: 1px solid rgba(59,130,246,.15);
      border-radius: 18px;
      padding: clamp(16px, 3vw, 24px) clamp(16px, 3vw, 28px);
      position: relative; overflow: hidden;
      display: flex; align-items: center; gap: 16px 20px; flex-wrap: wrap;
      margin-bottom: 18px;
      box-shadow: 0 8px 40px rgba(0,0,0,.4), inset 0 1px 0 rgba(59,130,246,.1);
    }
    .dev-hero::before {
      content: ''; position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(59,130,246,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59,130,246,.04) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .dev-hero::after {
      content: ''; position: absolute; top: -80px; right: -80px;
      width: 280px; height: 280px; border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,.1), transparent 70%);
      pointer-events: none;
    }
    .hero-av {
      width: 64px; height: 64px; border-radius: 16px;
      background: linear-gradient(135deg, var(--blue2), var(--blue));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 1.3rem; color: #fff;
      flex-shrink: 0; position: relative; z-index: 2;
      box-shadow: 0 8px 24px rgba(59,130,246,.35);
      border: 2px solid rgba(59,130,246,.3); overflow: hidden;
    }
    .hero-av img { width:64px;height:64px;object-fit:cover;border-radius:14px; }
    .hero-text { flex: 1 1 200px; min-width: 0; position: relative; z-index: 2; }
    .hero-eye {
      font-family: 'DM Mono', monospace; font-size: 0.55rem;
      letter-spacing: .22em; text-transform: uppercase;
      color: var(--blue); opacity: .8; margin-bottom: 4px;
    }
    .hero-name {
      font-size: clamp(1.05rem, 2vw + 0.6rem, 1.35rem);
      font-weight: 800; color: #fff; letter-spacing: -.3px; margin-bottom: 8px;
      overflow-wrap: anywhere;
    }
    .hero-pills { display: flex; gap: 7px; flex-wrap: wrap; }
    .hero-pill {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 10px; border-radius: 20px;
      font-family: 'DM Mono', monospace; font-size: 0.58rem;
      font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    }
    .pill-dev    { background: var(--blue-mid);  border: 1px solid rgba(59,130,246,.3);  color: #93C5FD; }
    .pill-online { background: var(--green-dim); border: 1px solid rgba(16,185,129,.25); color: var(--green); }
    .hero-stats {
      position: relative; z-index: 2; display: flex; gap: 12px;
      flex-wrap: wrap; flex: 1 1 100%; justify-content: flex-start;
    }
    .hstat {
      background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
      border-radius: 12px; padding: 10px 16px; text-align: center;
      min-width: 72px; flex: 1 1 auto; max-width: 100%;
    }
    .hstat-num   { font-family: 'DM Mono', monospace; font-size: 1.3rem; font-weight: 800; color: var(--blue); line-height: 1.15; }
    .hstat-label {
      font-family: 'DM Mono', monospace; font-size: 0.5rem; letter-spacing: .14em; text-transform: uppercase;
      color: rgba(255,255,255,.45); margin-top: 4px; line-height: 1.25;
      overflow-wrap: anywhere;
    }

    /* ══════════════════════════════════════
       KPI STRIP
    ══════════════════════════════════════ */
    .kpi-strip { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 10px; margin-bottom: 18px; }
    .kpi {
      background: var(--card); border: 1px solid var(--line);
      border-radius: 14px; padding: 14px 16px;
      display: flex; align-items: center; gap: 12px;
      transition: border-color .18s, box-shadow .18s;
    }
    .kpi:hover { border-color: var(--line2); box-shadow: 0 4px 20px rgba(0,0,0,.2); }
    .kpi-icon {
      width: 34px; height: 34px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .kpi-icon.blue   { background: var(--blue-dim);   color: var(--blue); }
    .kpi-icon.green  { background: var(--green-dim);  color: var(--green); }
    .kpi-icon.red    { background: var(--red-dim);    color: var(--red); }
    .kpi-icon.amber  { background: var(--amber-dim);  color: var(--amber); }
    .kpi-icon.purple { background: var(--purple-dim); color: var(--purple); }
    .kpi-body { min-width: 0; }
    .kpi-val   { font-family: 'DM Mono', monospace; font-size: 1.4rem; font-weight: 800; line-height: 1; }
    .kpi-val.blue   { color: var(--blue); }
    .kpi-val.green  { color: var(--green); }
    .kpi-val.red    { color: var(--red); }
    .kpi-val.amber  { color: var(--amber); }
    .kpi-val.purple { color: var(--purple); }
    .kpi-lbl   { font-size: 0.62rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; margin-top: 2px; }

    /* ══════════════════════════════════════
       MAIN GRID
    ══════════════════════════════════════ */
    .dev-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 320px); gap: 14px; align-items: start; }
    .dev-left  { display: flex; flex-direction: column; gap: 14px; }
    .dev-right { display: flex; flex-direction: column; gap: 14px; }

    /* ══════════════════════════════════════
       SECTION CARDS
    ══════════════════════════════════════ */
    .sec {
      background: var(--card); border: 1px solid var(--line);
      border-radius: 18px; overflow: hidden;
      box-shadow: 0 2px 16px rgba(0,0,0,.15);
    }
    .sec-head {
      padding: 16px 20px; border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 8px 12px;
    }
    .sec-head-l {
      display: flex; align-items: center; gap: 10px;
      min-width: 0; flex: 1 1 180px;
    }
    .sec-ico {
      width: 34px; height: 34px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .sec-ico.blue   { background: var(--blue-dim);   color: var(--blue); }
    .sec-ico.green  { background: var(--green-dim);  color: var(--green); }
    .sec-ico.red    { background: var(--red-dim);    color: var(--red); }
    .sec-ico.amber  { background: var(--amber-dim);  color: var(--amber); }
    .sec-ico.purple { background: var(--purple-dim); color: var(--purple); }
    .sec-title { font-size: 0.85rem; font-weight: 800; color: var(--ink); }
    .sec-sub   { font-size: 0.65rem; color: var(--muted); margin-top: 1px; }
    .sec-badge {
      font-family: 'DM Mono', monospace; font-size: 0.58rem; font-weight: 700;
      padding: 3px 9px; border-radius: 20px; letter-spacing: .08em;
    }
    .badge-blue   { background: var(--blue-dim);   color: var(--blue);   border: 1px solid rgba(59,130,246,.25); }
    .badge-green  { background: var(--green-dim);  color: var(--green);  border: 1px solid rgba(16,185,129,.25); }
    .badge-amber  { background: var(--amber-dim);  color: var(--amber);  border: 1px solid rgba(245,158,11,.25); }
    .badge-purple { background: var(--purple-dim); color: var(--purple); border: 1px solid rgba(139,92,246,.25); }
    .sec-body  { padding: 18px 20px; }

    /* ══════════════════════════════════════
       ACCESS CONTROL — USER CARDS
    ══════════════════════════════════════ */
    .user-cards { display: flex; flex-direction: column; gap: 10px; }
    .user-card {
      display: flex; align-items: center; gap: 12px;
      padding: 12px 14px; border-radius: 12px;
      border: 1px solid var(--line); background: var(--bg2);
      transition: border-color .18s, background .18s;
    }
    .user-card:hover { border-color: var(--line2); background: var(--bg3); }
    .u-av {
      width: 40px; height: 40px; border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.82rem; color: #fff;
      flex-shrink: 0; overflow: hidden;
    }
    .u-av img { width:40px;height:40px;object-fit:cover;border-radius:11px; }
    .u-info { flex: 1; min-width: 0; }
    .u-name { font-size: 0.84rem; font-weight: 700; color: var(--ink); }
    .u-meta { font-size: 0.66rem; color: var(--muted); margin-top: 2px; font-family: 'DM Mono', monospace; }
    .u-actions { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .u-btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 4px;
      padding: 5px 11px; border-radius: 8px; border: 1px solid;
      font-family: inherit;
      font-size: clamp(0.62rem, 0.1vw + 0.6rem, 0.68rem);
      font-weight: 700;
      cursor: pointer; transition: all .18s; background: none;
      flex: 0 1 auto; min-width: 0; max-width: 100%; box-sizing: border-box;
    }
    .u-btn-reset  { color: var(--blue);  border-color: rgba(59,130,246,.3); }
    .u-btn-reset:hover  { background: var(--blue-dim); }
    .u-btn-off { color: var(--red);   border-color: rgba(239,68,68,.3); }
    .u-btn-off:hover { background: var(--red-dim); }
    .u-btn-on  { color: var(--green); border-color: rgba(16,185,129,.3); }
    .u-btn-on:hover  { background: var(--green-dim); }
    .u-btn-hist { color: var(--muted2); border-color: var(--line); }
    .u-btn-hist:hover { color: var(--ink2); background: var(--bg3); }
    .sdot {
      width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
    }
    .sdot.on  { background: var(--green); box-shadow: 0 0 0 3px rgba(16,185,129,.2); }
    .sdot.off { background: var(--muted); }

    /* ══════════════════════════════════════
       AUDIT LOG
    ══════════════════════════════════════ */
    .log-list { display: flex; flex-direction: column; }
    .log-row {
      display: flex; align-items: flex-start; gap: 12px;
      padding: 10px 0; border-bottom: 1px solid var(--line);
    }
    .log-row:last-child { border-bottom: none; }
    .log-dot { width: 7px; height: 7px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
    .dc { background: var(--green); }
    .du { background: var(--blue); }
    .dd { background: var(--red); }
    .do { background: var(--muted); }
    .log-body { flex: 1; min-width: 0; }
    .log-action { font-size: 0.76rem; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .log-by     { font-size: 0.65rem; color: var(--muted); margin-top: 2px; font-family: 'DM Mono', monospace; }
    .log-time   { font-size: 0.62rem; color: var(--muted); white-space: nowrap; margin-top: 4px; font-family: 'DM Mono', monospace; }

    .log-tag {
      display: inline-flex; align-items: center;
      font-family: 'DM Mono', monospace; font-size: 0.55rem;
      font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
      padding: 1px 7px; border-radius: 20px; margin-left: 6px; vertical-align: middle;
    }
    .tag-c { background: var(--green-dim); color: var(--green); }
    .tag-u { background: var(--blue-dim);  color: var(--blue);  }
    .tag-d { background: var(--red-dim);   color: var(--red);   }
    .tag-o { background: rgba(255,255,255,.06); color: var(--muted2); }

    /* ══════════════════════════════════════
       SECURITY PANEL
    ══════════════════════════════════════ */
    .sec-rows { display: flex; flex-direction: column; }
    .srow {
      display: flex; align-items: center; justify-content: space-between;
      padding: 9px 0; border-bottom: 1px solid var(--line);
    }
    .srow:last-child { border-bottom: none; }
    .srow-label { font-size: 0.75rem; font-weight: 600; color: var(--ink2); }
    .srow-val {
      font-family: 'DM Mono', monospace; font-size: 0.72rem;
      font-weight: 700;
    }
    .sv-blue   { color: var(--blue); }
    .sv-green  { color: var(--green); }
    .sv-amber  { color: var(--amber); }
    .sv-red    { color: var(--red); }
    .sv-muted  { color: var(--muted2); }

    /* ══════════════════════════════════════
       SUPPORT / HELP CENTER
    ══════════════════════════════════════ */
    .support-list { display: flex; flex-direction: column; gap: 8px; }
    .support-item {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 11px 14px; border-radius: 12px;
      border: 1px solid var(--line); background: var(--bg2);
      transition: border-color .18s; cursor: pointer;
    }
    .support-item:hover { border-color: var(--line2); }
    .support-item.unread { border-color: rgba(59,130,246,.3); background: rgba(59,130,246,.05); }
    .si-av {
      width: 34px; height: 34px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.72rem; color: #fff;
      flex-shrink: 0;
    }
    .si-body { flex: 1; min-width: 0; }
    .si-name { font-size: 0.78rem; font-weight: 700; color: var(--ink); }
    .si-msg  { font-size: 0.7rem; color: var(--muted2); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .si-right { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
    .si-time { font-family: 'DM Mono', monospace; font-size: 0.6rem; color: var(--muted); }
    .si-unread-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--blue); }

    .support-empty {
      text-align: center; padding: 24px 0;
      font-size: 0.78rem; color: var(--muted);
    }

    /* ══════════════════════════════════════
       MAINTENANCE PANEL
    ══════════════════════════════════════ */
    .maint-rows { display: flex; flex-direction: column; }
    .mrow {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 0; border-bottom: 1px solid var(--line);
    }
    .mrow:last-child { border-bottom: none; }
    .mrow-ico {
      width: 30px; height: 30px; border-radius: 9px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .mrow-body { flex: 1; }
    .mrow-label { font-size: 0.76rem; font-weight: 700; color: var(--ink2); }
    .mrow-sub   { font-size: 0.63rem; color: var(--muted); margin-top: 1px; }
    .mrow-status {
      font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700;
      padding: 2px 8px; border-radius: 20px;
    }
    .ms-ok     { background: var(--green-dim);  color: var(--green);  border: 1px solid rgba(16,185,129,.25); }
    .ms-warn   { background: var(--amber-dim);  color: var(--amber);  border: 1px solid rgba(245,158,11,.25); }
    .ms-info   { background: var(--blue-dim);   color: var(--blue);   border: 1px solid rgba(59,130,246,.25); }
    .ms-off    { background: var(--red-dim);    color: var(--red);    border: 1px solid rgba(239,68,68,.25); }

    /* ══════════════════════════════════════
       SYSTEM CONTROL PANEL
    ══════════════════════════════════════ */
    .ctrl-panel {
      display: flex; flex-direction: column; gap: 10px;
    }
    .ctrl-row {
      display: flex; align-items: center; gap: 12px;
      padding: 12px 14px; border-radius: 14px;
      border: 1px solid var(--line); background: var(--bg2);
      transition: border-color .2s, background .2s;
    }
    .ctrl-row:hover { border-color: var(--line2); background: var(--bg3); }
    .ctrl-row.active-maintenance { border-color: rgba(245,158,11,.35); background: rgba(245,158,11,.05); }
    .ctrl-row.active-debug       { border-color: rgba(239,68,68,.35);   background: rgba(239,68,68,.05); }
    .ctrl-ico {
      width: 34px; height: 34px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .ctrl-body { flex: 1; min-width: 0; }
    .ctrl-label { font-size: 0.8rem; font-weight: 700; color: var(--ink); }
    .ctrl-sub   { font-size: 0.63rem; color: var(--muted); margin-top: 2px; font-family: 'DM Mono', monospace; }

    /* Toggle Switch */
    .toggle-wrap { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .toggle-lbl  { font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700; letter-spacing:.08em; }
    .toggle {
      position: relative; width: 40px; height: 22px; flex-shrink: 0;
    }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
      position: absolute; cursor: pointer; inset: 0;
      background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.12);
      border-radius: 22px; transition: all .25s;
    }
    .toggle-slider::before {
      content: ''; position: absolute;
      width: 16px; height: 16px; border-radius: 50%; background: #fff;
      left: 3px; top: 2px; transition: transform .25s, background .25s;
      box-shadow: 0 2px 6px rgba(0,0,0,.4);
    }
    .toggle input:checked + .toggle-slider { background: var(--amber); border-color: var(--amber); }
    .toggle input:checked + .toggle-slider::before { transform: translateX(18px); }
    .toggle.debug-toggle input:checked + .toggle-slider { background: var(--red); border-color: var(--red); }

    /* Scheduled maintenance row */
    .sched-row {
      display: flex; flex-direction: column; gap: 10px;
      padding: 12px 14px; border-radius: 14px;
      border: 1px solid var(--line); background: var(--bg2);
    }
    .sched-top { display: flex; align-items: center; gap: 12px; }
    .sched-inputs { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .sched-input {
      flex: 1; min-width: 140px;
      padding: 7px 10px; border-radius: 9px;
      border: 1px solid var(--line2); background: var(--bg);
      font-family: 'DM Mono', monospace; font-size: 0.72rem;
      color: var(--ink); outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .sched-input:focus { border-color: var(--amber); box-shadow: 0 0 0 3px rgba(245,158,11,.15); }
    .sched-input::-webkit-calendar-picker-indicator { filter: invert(.5); }
    .btn-sched {
      padding: 7px 14px; border-radius: 9px;
      background: linear-gradient(135deg, rgba(245,158,11,.2), rgba(245,158,11,.12));
      border: 1px solid rgba(245,158,11,.3); color: var(--amber);
      font-family: inherit;
      font-size: clamp(0.68rem, 0.1vw + 0.65rem, 0.72rem);
      font-weight: 700;
      cursor: pointer; transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-sched:hover { background: rgba(245,158,11,.25); border-color: var(--amber); }
    .btn-sched-clear {
      padding: 7px 14px; border-radius: 9px;
      background: var(--red-dim); border: 1px solid rgba(239,68,68,.3);
      color: var(--red); font-family: inherit; font-size: 0.72rem;
      font-weight: 700; cursor: pointer; transition: all .18s;
    }

    /* Countdown */
    .countdown-bar {
      display: none; align-items: center; gap: 10px;
      padding: 8px 12px; border-radius: 10px;
      background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.2);
      margin-top: 4px;
    }
    .countdown-bar.visible { display: flex; }
    .countdown-lbl { font-size: 0.65rem; color: var(--muted2); font-weight: 600; }
    .countdown-val {
      font-family: 'DM Mono', monospace; font-size: 0.78rem;
      font-weight: 800; color: var(--amber); letter-spacing: .06em;
    }

    /* Maintenance Log */
    .mlog-list { display: flex; flex-direction: column; max-height: 180px; overflow-y: auto; }
    .mlog-row {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 0; border-bottom: 1px solid var(--line);
      font-size: 0.7rem;
    }
    .mlog-row:last-child { border-bottom: none; }
    .mlog-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .mlog-dot.on  { background: var(--amber); }
    .mlog-dot.off { background: var(--muted); }
    .mlog-dot.debug-on  { background: var(--red); }
    .mlog-dot.debug-off { background: var(--muted); }
    .mlog-body { flex: 1; color: var(--ink2); font-weight: 600; }
    .mlog-time { font-family: 'DM Mono', monospace; font-size: 0.6rem; color: var(--muted); }
    .mlog-empty { font-size: 0.75rem; color: var(--muted); text-align: center; padding: 16px 0; }

    /* Topbar warning chip */
    .chip-maintenance { background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.3); color: var(--amber); }
    .chip-debug       { background: var(--red-dim);       border: 1px solid rgba(239,68,68,.3);  color: var(--red); }

    /* Impersonate button */
    .u-btn-imp { color: var(--purple); border-color: rgba(139,92,246,.3); }
    .u-btn-imp:hover { background: var(--purple-dim); }

    /* ══════════════════════════════════════
       IMPERSONATION FLOATING BAR
    ══════════════════════════════════════ */
    .imp-bar {
      display: none;
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
      background: linear-gradient(90deg, #2d1b69, #1e1050);
      border-bottom: 2px solid var(--purple);
      padding: 10px max(24px, env(safe-area-inset-right)) 10px max(calc(var(--sidebar-w) + 24px), env(safe-area-inset-left));
      align-items: center; gap: 14px; flex-wrap: wrap;
      box-shadow: 0 4px 24px rgba(139,92,246,.3);
      animation: slideDown .3s ease-out;
    }
    .imp-bar.visible { display: flex; }
    @keyframes slideDown { from{transform:translateY(-100%)} to{transform:translateY(0)} }
    .imp-avatar {
      width: 32px; height: 32px; border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.7rem; color: #fff;
      background: var(--purple); flex-shrink: 0; overflow: hidden;
    }
    .imp-avatar img { width:32px;height:32px;object-fit:cover;border-radius:9px; }
    .imp-label {
      flex: 1 1 200px;
      min-width: 0;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700; color: #fff;
      overflow-wrap: anywhere;
    }
    .imp-label span { color: var(--purple); font-family: 'DM Mono', monospace; font-weight: 800; }
    .imp-role {
      font-family: 'DM Mono', monospace; font-size: 0.6rem;
      font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
      padding: 3px 10px; border-radius: 20px;
      background: rgba(139,92,246,.2); border: 1px solid rgba(139,92,246,.4); color: #C4B5FD;
    }
    .imp-exit {
      padding: 7px 16px; border-radius: 9px;
      background: rgba(139,92,246,.25); border: 1px solid rgba(139,92,246,.4);
      color: #C4B5FD; font-family: inherit;
      font-size: clamp(0.7rem, 0.15vw + 0.66rem, 0.75rem);
      font-weight: 700;
      cursor: pointer; transition: all .18s;
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .imp-exit:hover { background: rgba(139,92,246,.4); color: #fff; }

    /* ══════════════════════════════════════
       SYSTEM CONTROL MODALS
    ══════════════════════════════════════ */
    .ctrl-modal-box {
      background: var(--card); border: 1px solid var(--line2);
      border-radius: 22px; padding: clamp(20px, 4vw, 28px);
      width: min(440px, calc(100vw - 2rem)); max-width: 100%;
      box-sizing: border-box;
      box-shadow: 0 32px 80px rgba(0,0,0,.6);
      animation: fadeUp .25s forwards;
    }
    .ctrl-modal-icon {
      width: 52px; height: 52px; border-radius: 16px;
      display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
    }
    .ctrl-modal-icon.amber { background: rgba(245,158,11,.15); color: var(--amber); }
    .ctrl-modal-icon.red   { background: var(--red-dim);       color: var(--red); }
    .ctrl-modal-icon.purple{ background: var(--purple-dim);    color: var(--purple); }
    .ctrl-modal-title { font-size: clamp(0.95rem, 0.3vw + 0.88rem, 1.05rem); font-weight: 800; color: var(--ink); margin-bottom: 6px; overflow-wrap: anywhere; }
    .ctrl-modal-sub   { font-size: 0.78rem; color: var(--muted2); line-height: 1.6; margin-bottom: 20px; overflow-wrap: anywhere; }
    .ctrl-modal-warn  {
      padding: 10px 14px; border-radius: 11px; margin-bottom: 18px;
      font-size: 0.74rem; font-weight: 600; line-height: 1.5;
      display: flex; align-items: flex-start; gap: 9px;
    }
    .ctrl-modal-warn.amber { background: rgba(245,158,11,.1); border: 1px solid rgba(245,158,11,.25); color: #FCD34D; }
    .ctrl-modal-warn.red   { background: var(--red-dim);       border: 1px solid rgba(239,68,68,.25);  color: #FCA5A5; }
    .btn-activate-amber {
      flex: 1 1 140px; min-width: 0; padding: 10px; border-radius: 11px;
      background: linear-gradient(135deg, #b45309, var(--amber));
      border: none; font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      color: #000; cursor: pointer; transition: all .18s;
      box-shadow: 0 6px 16px rgba(245,158,11,.25);
      box-sizing: border-box;
    }
    .btn-activate-amber:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(245,158,11,.35); }
    .btn-activate-red {
      flex: 1 1 140px; min-width: 0; padding: 10px; border-radius: 11px;
      background: linear-gradient(135deg, #b91c1c, var(--red));
      border: none; font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      color: #fff; cursor: pointer; transition: all .18s;
      box-shadow: 0 6px 16px rgba(239,68,68,.25);
      box-sizing: border-box;
    }
    .btn-activate-red:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(239,68,68,.35); }

    /* Impersonate modal */
    .imp-modal-box {
      background: var(--card); border: 1px solid rgba(139,92,246,.25);
      border-radius: 22px; padding: clamp(20px, 4vw, 28px);
      width: min(440px, calc(100vw - 2rem)); max-width: 100%;
      box-sizing: border-box;
      box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 60px rgba(139,92,246,.1);
      animation: fadeUp .25s forwards;
    }
    .imp-user-preview {
      display: flex; align-items: center; gap: 14px;
      padding: 14px; border-radius: 14px;
      background: rgba(139,92,246,.08); border: 1px solid rgba(139,92,246,.2);
      margin-bottom: 18px;
    }
    .imp-prev-av {
      width: 44px; height: 44px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.85rem; color: #fff; flex-shrink: 0; overflow: hidden;
    }
    .imp-prev-av img { width:44px;height:44px;object-fit:cover;border-radius:12px; }
    .imp-prev-name { font-size: 0.9rem; font-weight: 800; color: var(--ink); }
    .imp-prev-role { font-family: 'DM Mono', monospace; font-size: 0.65rem; color: var(--purple); margin-top: 2px; }
    .btn-imp-confirm {
      flex: 1 1 140px; min-width: 0; padding: 10px; border-radius: 11px;
      background: linear-gradient(135deg, #5b21b6, var(--purple));
      border: none; font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      color: #fff; cursor: pointer; transition: all .18s;
      box-shadow: 0 6px 16px rgba(139,92,246,.3);
      box-sizing: border-box;
    }
    .btn-imp-confirm:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(139,92,246,.4); }

    /* ══════════════════════════════════════
       MODALS
    ══════════════════════════════════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(0,0,0,.7); backdrop-filter: blur(8px);
      display: none; align-items: center; justify-content: center;
      padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: var(--card); border: 1px solid var(--line2);
      border-radius: 22px; padding: clamp(20px, 4vw, 28px);
      width: min(420px, calc(100vw - 2rem)); max-width: 100%;
      box-sizing: border-box;
      box-shadow: 0 32px 80px rgba(0,0,0,.5);
      animation: fadeUp .25s forwards;
    }
    @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:none} }
    .modal-title { font-size: 1.05rem; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
    .modal-sub   { font-size: 0.78rem; color: var(--muted2); margin-bottom: 18px; }
    .mfield label {
      font-family: 'DM Mono', monospace; font-size: 0.58rem;
      letter-spacing: .12em; text-transform: uppercase;
      color: var(--muted2); font-weight: 600; display: block; margin-bottom: 5px;
    }
    .mfield input {
      width: 100%; padding: .72rem 1rem; border-radius: 11px;
      border: 1px solid var(--line2); background: var(--bg2);
      font-family: inherit; font-size: 0.84rem; color: var(--ink);
      outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .mfield input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-dim); }
    .mfield + .mfield { margin-top: 12px; }
    .modal-alert {
      display: none; align-items: center; gap: 8px;
      border-radius: 10px; padding: 9px 13px; margin-top: 12px;
      font-size: 0.76rem; font-weight: 600;
    }
    .modal-alert.error   { background: var(--red-dim);   border: 1px solid rgba(239,68,68,.2);   color: var(--red);   display:flex; }
    .modal-alert.success { background: var(--green-dim); border: 1px solid rgba(16,185,129,.2);  color: var(--green); display:flex; }
    .modal-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; align-items: stretch; }
    .modal-actions .btn-cancel,
    .modal-actions .btn-confirm,
    .modal-actions .btn-activate-amber,
    .modal-actions .btn-activate-red,
    .modal-actions .btn-imp-confirm,
    .modal-actions .btn-logout {
      flex: 1 1 120px; min-width: 0;
    }
    .btn-cancel {
      padding: 10px; border-radius: 11px;
      background: var(--bg2); border: 1px solid var(--line2);
      font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      color: var(--muted2); cursor: pointer; transition: all .18s;
      box-sizing: border-box;
    }
    .btn-cancel:hover { border-color: var(--red); color: var(--red); }
    .btn-confirm {
      padding: 10px; border-radius: 11px;
      background: linear-gradient(135deg, var(--blue2), var(--blue));
      border: none; font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      color: #fff; cursor: pointer; transition: all .18s;
      box-shadow: 0 6px 16px rgba(59,130,246,.3);
      box-sizing: border-box;
    }
    .btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(59,130,246,.38); }
    .btn-confirm:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* history modal */
    .history-box {
      background: var(--card); border: 1px solid var(--line2);
      border-radius: 22px; padding: 0;
      width: min(500px, calc(100vw - 2rem)); max-width: 100%; max-height: min(80vh, 80dvh);
      box-sizing: border-box;
      display: flex; flex-direction: column;
      box-shadow: 0 32px 80px rgba(0,0,0,.5);
      animation: fadeUp .25s forwards; overflow: hidden;
    }
    .history-head { padding: 22px 26px; border-bottom: 1px solid var(--line); }
    .history-body { padding: 18px 26px; overflow-y: auto; flex: 1; }
    .history-item {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 9px 0; border-bottom: 1px solid var(--line);
    }
    .history-item:last-child { border-bottom: none; }

    /* logout modal */
    .logout-box {
      background: var(--card); border: 1px solid var(--line2);
      border-radius: 22px; padding: clamp(20px, 4vw, 28px);
      width: min(360px, calc(100vw - 2rem)); max-width: 100%;
      box-sizing: border-box;
      box-shadow: 0 32px 80px rgba(0,0,0,.5);
      animation: fadeUp .25s forwards;
    }
    .logout-ico {
      width: 48px; height: 48px; border-radius: 14px;
      background: rgba(165,44,48,.15); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; margin-bottom: 14px;
    }
    .btn-logout {
      padding: 10px; border-radius: 11px; width: 100%;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      border: none; font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      color: #fff; cursor: pointer; box-shadow: 0 6px 16px rgba(165,44,48,.28);
      transition: all .18s;
    }
    .btn-logout:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(165,44,48,.38); }

    .logout-actions {
      display: flex; flex-wrap: wrap; gap: 10px; align-items: stretch;
    }
    .logout-actions .btn-cancel { flex: 1 1 120px; min-width: 0; }
    .logout-actions form {
      flex: 1 1 140px; min-width: 0; display: flex;
    }
    .logout-actions form .btn-logout { width: 100%; }

    /* ══════════════════════════════════════
       TOAST
    ══════════════════════════════════════ */
    .toast {
      position: fixed; top: 76px; right: 20px; z-index: 9999;
      min-width: 0; max-width: calc(100vw - 2rem); width: min(360px, calc(100vw - 2rem));
      padding: 12px 16px; border-radius: 12px;
      font-weight: 700; font-size: 0.78rem;
      display: flex; align-items: center; gap: 10px;
      animation: toastIn .3s ease-out; font-family: 'Plus Jakarta Sans', sans-serif;
      border: 1px solid;
    }
    .toast.success { background: var(--bg3); border-color: rgba(16,185,129,.3); color: var(--green);  }
    .toast.error   { background: var(--bg3); border-color: rgba(239,68,68,.3);  color: var(--red); }
    .toast.hiding  { animation: toastOut .3s ease-out forwards; }
    @keyframes toastIn  { from{transform:translateX(400px);opacity:0} to{transform:translateX(0);opacity:1} }
    @keyframes toastOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(400px)} }

    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner {
      width: 13px; height: 13px; border-radius: 50%;
      border: 2px solid rgba(255,255,255,.25); border-top-color: #fff;
      animation: spin .7s linear infinite; display: inline-block;
    }

    .fade-up { opacity:0; transform:translateY(14px); animation: fadeUp .5s forwards; }
    .d1{animation-delay:.1s} .d2{animation-delay:.18s} .d3{animation-delay:.26s} .d4{animation-delay:.34s}

    /* ════════════════════════════════════════
       HAMBURGER BUTTON (mobile only)
    ════════════════════════════════════════ */
    .hamburger-btn {
      display: none;
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg2); border: 1px solid var(--line2);
      align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted2);
      transition: all .18s; flex-shrink: 0;
      -webkit-tap-highlight-color: transparent;
    }
    .hamburger-btn:hover { background: var(--blue-dim); border-color: rgba(59,130,246,.3); color: var(--blue); }

    /* Mobile sidebar overlay backdrop */
    .sidebar-backdrop {
      display: none;
      position: fixed; inset: 0; z-index: 49;
      background: rgba(0,0,0,.6);
      backdrop-filter: blur(3px);
      -webkit-tap-highlight-color: transparent;
    }
    .sidebar-backdrop.open { display: block; }

    /* ════════════════════════════════════════
       RESPONSIVE — 1280px
    ════════════════════════════════════════ */
    @media (max-width: 1280px) {
      .dev-grid { grid-template-columns: minmax(0, 1fr) minmax(0, 290px); }
      .kpi-strip { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .hero-stats { gap: 8px; }
      .hstat { min-width: 68px; padding: 8px 12px; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 1100px (tablet landscape)
    ════════════════════════════════════════ */
    @media (max-width: 1100px) {
      .dev-grid { grid-template-columns: 1fr; }
      .kpi-strip { grid-template-columns: repeat(3, minmax(0, 1fr)); }
      .dev-right { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 768px (tablet portrait)
    ════════════════════════════════════════ */
    @media (max-width: 768px) {
      /* Sidebar becomes slide-in drawer */
      .sidebar {
        transform: translateX(-100%);
        transition: transform .28s cubic-bezier(.4,0,.2,1);
        z-index: 50;
        width: 220px;
        align-items: flex-start;
        padding: 20px 12px;
      }
      .sidebar.mobile-open { transform: translateX(0); }
      .sidebar-nav { width: 100%; align-items: flex-start; }
      .nav-item {
        width: 100%; border-radius: 12px;
        justify-content: flex-start;
        padding: 0 12px; gap: 12px;
      }
      /* Show nav labels inline in drawer */
      .nav-tooltip {
        position: static; opacity: 1 !important; transform: none;
        background: none; color: rgba(255,255,255,.75);
        font-size: 0.78rem; font-weight: 600;
        padding: 0; border-radius: 0; pointer-events: auto;
        letter-spacing: .01em; white-space: nowrap;
        border: none;
      }
      .sidebar-bottom { width: 100%; align-items: flex-start; }
      .nav-divider { width: 100%; margin: 6px 0; }

      /* Main content full width */
      .main-wrap { margin-left: 0; }

      /* Show hamburger */
      .hamburger-btn { display: flex; }
      .topbar { padding: 8px var(--pad-x); min-height: 58px; }
      .topbar-left { gap: 10px; }
      .page-sub { display: none; }
      .live-clock { display: none; }

      /* Content — vertical rhythm matches hero card inset */
      .content { padding: 14px var(--pad-x); box-sizing: border-box; }

      /* Hero banner stacks — same horizontal inset as .content */
      .dev-hero {
        flex-direction: column; align-items: stretch;
        padding: clamp(14px, 3vw, 18px) var(--pad-x);
        gap: 14px;
      }
      .dev-hero::after { top: -40px; right: -40px; width: 180px; height: 180px; }
      /* 4 hero metrics: 2×2 grid so cells don’t shrink into a cramped row */
      .hero-stats {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        align-items: stretch;
      }
      .hstat {
        flex: none; min-width: 0; width: 100%;
        padding: 10px 12px;
      }
      .hstat-num { font-size: clamp(1rem, 3.5vw + 0.5rem, 1.2rem); }
      .hstat-label { font-size: clamp(0.52rem, 0.8vw + 0.45rem, 0.58rem); letter-spacing: .1em; }

      /* KPI 3-col */
      .kpi-strip { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px 12px; margin-bottom: 14px; }
      .kpi { padding: 11px 12px; gap: 8px; }
      .kpi-val { font-size: 1.2rem; }

      /* Grid single col */
      .dev-grid { grid-template-columns: 1fr; }
      .dev-right { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }

      /* User card actions wrap */
      .u-actions { flex-wrap: wrap; gap: 5px; }

      /* Modals full width */
      .ctrl-modal-box { width: 96vw; padding: 22px 20px; }
      .imp-modal-box  { width: 96vw; padding: 22px 20px; }
      .modal-box      { width: 96vw; padding: 22px 20px; }
      .logout-box     { width: 96vw; padding: 22px 20px; }
      .history-box    { width: 96vw; max-width: none; }

      /* Impersonation bar */
      .imp-bar {
        padding: 8px max(16px, env(safe-area-inset-right)) 8px max(16px, env(safe-area-inset-left));
        gap: 10px;
      }
      .imp-role { display: none; }

      /* Toast */
      .toast { right: 12px; top: 68px; min-width: calc(100vw - 24px); max-width: none; }

      /* Status chips compact */
      .status-chip { padding: 4px 8px; font-size: 0.55rem; }

      /* Sched inputs stack */
      .sched-inputs { flex-direction: column; }
      .sched-input { min-width: unset; width: 100%; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 480px (large phone)
    ════════════════════════════════════════ */
    @media (max-width: 480px) {
      .topbar { padding: 8px var(--pad-x); min-height: 54px; }
      .topbar-right { gap: 7px; }
      .content { padding: 12px var(--pad-x); }

      /* Hero — keep side padding aligned with page shell */
      .dev-hero { padding: 14px var(--pad-x); }
      .hero-av { width: 48px; height: 48px; font-size: 1rem; }
      .hero-av img { width: 48px; height: 48px; }
      .hero-name { font-size: 1.1rem; }
      .hero-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
      }
      .hstat { padding: 10px 10px; }
      .hstat-num { font-size: clamp(1.05rem, 4.2vw, 1.2rem); }
      .hstat-label { font-size: 0.56rem; letter-spacing: .09em; }

      /* KPI: one column so labels aren’t squeezed */
      .kpi-strip { grid-template-columns: 1fr; gap: 10px; }

      /* Right col stacks */
      .dev-right { grid-template-columns: 1fr; }

      /* Section head — badge doesn’t hug screen edge */
      .sec-head { flex-wrap: wrap; gap: 8px 12px; align-items: flex-start; }
      .sec-head .sec-badge { flex-shrink: 0; margin-left: auto; }

      /* User card — actions below info */
      .user-card { flex-wrap: wrap; }
      .u-actions { width: 100%; justify-content: flex-start; }
      .u-btn { white-space: normal; text-align: center; }

      /* Sched row */
      .sched-top { flex-wrap: wrap; }

      /* Status chips hide */
      .status-chip { display: none; }

      /* Modals */
      .ctrl-modal-box { padding: 18px 16px; }
      .imp-modal-box  { padding: 18px 16px; }
      .modal-box      { padding: 18px 16px; }
      .logout-box     { padding: 18px 16px; }
      .history-head   { padding: 16px 18px; }
      .history-body   { padding: 14px 18px; }
      .modal-actions  { flex-direction: column; }
      .btn-cancel, .btn-confirm, .btn-activate-amber,
      .btn-activate-red, .btn-imp-confirm, .btn-logout { flex: none; width: 100%; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 360px (small phone)
    ════════════════════════════════════════ */
    @media (max-width: 360px) {
      .topbar { padding: 8px var(--pad-x); }
      .content { padding: 10px var(--pad-x); }
      .kpi-strip { gap: 8px; }
      .kpi { padding: 12px 14px; gap: 10px; }
      .kpi-val { font-size: 1rem; }
      .kpi-lbl { font-size: 0.55rem; }
      .dev-hero { padding: 12px var(--pad-x); }
      .hero-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
      .hstat { padding: 9px 8px; }
    }
  </style>
</head>

<body>
@php
  $userName        = session('user_name',         'Developer');
  $userColor       = session('user_avatar_color', '#3B82F6');
  $userAvatarImage = session('user_avatar_image', null);
  $userInitials    = collect(explode(' ', $userName))
    ->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
@endphp

{{-- ══════════════ IMPERSONATION BAR ══════════════ --}}
@if(session('impersonating'))
<div class="imp-bar visible" id="impBar">
  <div class="imp-avatar" style="background:linear-gradient(135deg,{{ session('impersonating_color','#8B5CF6') }}cc,{{ session('impersonating_color','#8B5CF6') }});">
    @if(session('impersonating_avatar'))
      <img src="{{ asset('storage/avatars/'.session('impersonating_avatar')) }}" alt="">
    @else
      {{ collect(explode(' ',session('impersonating_name','?')))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('') }}
    @endif
  </div>
  <div class="imp-label">
    Viewing as <span>{{ session('impersonating_name') }}</span>
  </div>
  <span class="imp-role">{{ strtoupper(session('impersonating_role','user')) }}</span>
  <form method="POST" action="{{ url('/dev/impersonate/exit') }}" style="margin:0;">
    @csrf
    <button type="submit" class="imp-exit">
      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Exit Impersonation
    </button>
  </form>
</div>
@endif

{{-- ══════════════ SIDEBAR ══════════════ --}}
{{-- Mobile sidebar backdrop --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<aside class="sidebar" id="mainSidebar" aria-label="Main navigation">

  {{-- User avatar --}}
  <div class="nav-item" style="margin-bottom:18px;width:42px;height:42px;border-radius:14px;
    {{ $userAvatarImage ? 'background:transparent;' : 'background:linear-gradient(135deg,#2563EB,#3B82F6);' }}
    font-weight:800;font-size:0.78rem;color:#fff;
    box-shadow:0 6px 18px rgba(59,130,246,.35);cursor:default;flex-shrink:0;overflow:hidden;padding:0;">
    @if($userAvatarImage)
      <img src="{{ asset('storage/avatars/' . $userAvatarImage) }}" alt="{{ $userInitials }}"
           style="width:42px;height:42px;object-fit:cover;border-radius:14px;display:block;">
    @else
      {{ $userInitials }}
    @endif
    <span class="nav-tooltip" style="min-width:140px;line-height:1.5;">
      {{ $userName }}<br>
      <span style="opacity:.6;font-weight:500;letter-spacing:.06em;text-transform:uppercase;font-size:.58rem;">Developer</span>
    </span>
  </div>

  <nav class="sidebar-nav">
    {{-- Dashboard --}}
    <a href="{{ url('/dev') }}" class="nav-item active">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
      </svg>
      <span class="nav-tooltip">Dashboard</span>
    </a>
    {{-- Records --}}
    <a href="{{ url('/dev/records') }}" class="nav-item">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <span class="nav-tooltip">Records</span>
    </a>

    <div class="nav-divider"></div>

    
    {{-- Logs --}}
    <a href="#logs" class="nav-item" onclick="scrollTo('#logs')">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 3l7 3v6c0 5-3.5 8-7 9-3.5-1-7-4-7-9V6l7-3z"/>
        <path d="M9.5 12.5l1.7 1.7 3.3-3.7"/>
      </svg>
      <span class="nav-tooltip">Audit Logs</span>
    </a>
    
  </nav>

  <div class="sidebar-bottom">
    <a href="{{ url('/dev/profile') }}" class="nav-item">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
      </svg>
      <span class="nav-tooltip">Profile</span>
    </a>
    <button type="button" id="logoutBtn" class="nav-item" style="background:none;border:none;cursor:pointer;">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
      <span class="nav-tooltip">Log Out</span>
    </button>
  </div>
</aside>

{{-- ══════════════ MAIN ══════════════ --}}
<div class="main-wrap">

  {{-- TOPBAR --}}
  <header class="topbar">
    <div class="topbar-left">
      {{-- Hamburger: visible only on mobile (≤768px) --}}
      <button type="button" class="hamburger-btn" id="hamburgerBtn" aria-label="Open navigation menu" aria-expanded="false">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <line x1="3" y1="6"  x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
      <div class="topbar-titles">
        <div class="page-title">Developer Panel</div>
        <div class="page-sub">KTTM System Control &amp; Monitoring</div>
      </div>
    </div>
    <div class="topbar-right">
      <div class="live-clock" id="liveClock"></div>
      @if(!empty($systemFlags['maintenance_mode']))
      <span class="status-chip chip-maintenance">
        <span class="chip-dot"></span> Maintenance ON
      </span>
      @endif
      @if(!empty($systemFlags['debug_mode']))
      <span class="status-chip chip-debug">
        <span class="chip-dot"></span> Debug ON
      </span>
      @endif
      <span class="status-chip chip-online">
        <span class="chip-dot"></span> Online
      </span>
      <span class="status-chip chip-dev">
        <span class="chip-dot"></span> Dev Mode
      </span>
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

    {{-- HERO --}}
    <div class="dev-hero fade-up">
      <div class="hero-av">
        @if($userAvatarImage)
          <img src="{{ asset('storage/avatars/' . $userAvatarImage) }}" alt="{{ $userInitials }}">
        @else
          {{ $userInitials }}
        @endif
      </div>
      <div class="hero-text">
        <div class="hero-eye">KTTM · IP Records System · Developer Console</div>
        <div class="hero-name">{{ $userName }}</div>
        <div class="hero-pills">
          <span class="hero-pill pill-dev">
            <span style="width:4px;height:4px;border-radius:50%;background:currentColor;"></span>
            Developer
          </span>
          <span class="hero-pill pill-online">
            <span style="width:4px;height:4px;border-radius:50%;background:currentColor;"></span>
            Active Session
          </span>
        </div>
      </div>
      <div class="hero-stats">
        <div class="hstat">
          <div class="hstat-num">{{ $kpis['total_records'] }}</div>
          <div class="hstat-label">Records</div>
        </div>
        <div class="hstat">
          <div class="hstat-num" style="color:var(--blue);">{{ $kpis['active_admins'] }}</div>
          <div class="hstat-label">Admins Online</div>
        </div>
        <div class="hstat">
          <div class="hstat-num" style="color:var(--green);">{{ $kpis['active_guests'] }}</div>
          <div class="hstat-label">Guests Online</div>
        </div>
        <div class="hstat">
          <div class="hstat-num" style="color:var(--green);">{{ $storageMB }}</div>
          <div class="hstat-label">MB Used</div>
        </div>
      </div>
    </div>

    {{-- KPI STRIP --}}
    <div class="kpi-strip fade-up d1">
      <div class="kpi">
        <div class="kpi-icon blue"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="kpi-body"><div class="kpi-val blue">{{ $kpis['total_records'] }}</div><div class="kpi-lbl">Total Records</div></div>
      </div>
      <div class="kpi">
        <div class="kpi-icon green"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div class="kpi-body"><div class="kpi-val green">{{ $kpis['registered'] }}</div><div class="kpi-lbl">Registered</div></div>
      </div>
      <div class="kpi">
        <div class="kpi-icon amber"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div class="kpi-body"><div class="kpi-val amber">{{ $kpis['pending'] }}</div><div class="kpi-lbl">Pending</div></div>
      </div>
      <div class="kpi">
        <div class="kpi-icon blue">
          {{-- Admin silhouette icon --}}
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
          </svg>
        </div>
        <div class="kpi-body">
          <div class="kpi-val blue">{{ $kpis['active_admins'] }}</div>
          <div class="kpi-lbl">Admins Online</div>
        </div>
      </div>
      <div class="kpi">
        <div class="kpi-icon green">
          {{-- Guest / eye icon --}}
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
          </svg>
        </div>
        <div class="kpi-body">
          <div class="kpi-val green">{{ $kpis['active_guests'] }}</div>
          <div class="kpi-lbl">Guests Online</div>
        </div>
      </div>
      <div class="kpi">
        <div class="kpi-icon amber"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <div class="kpi-body"><div class="kpi-val amber">{{ $storageMB }}<span style="font-size:.8rem;color:var(--muted);font-weight:600;">MB</span></div><div class="kpi-lbl">Storage</div></div>
      </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="dev-grid">

      {{-- ══ LEFT COLUMN ══ --}}
      <div class="dev-left">

        {{-- ACCESS CONTROL --}}
        <div class="sec fade-up d2" id="access">
          <div class="sec-head">
            <div class="sec-head-l">
              <div class="sec-ico blue">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                  <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
              </div>
              <div>
                <div class="sec-title">Access Control</div>
                <div class="sec-sub">Manage user access, passwords &amp; sessions</div>
              </div>
            </div>
            <span class="sec-badge badge-blue">{{ $users->where('role','!=','developer')->count() }} users</span>
          </div>
          <div class="sec-body">
            <div class="user-cards">
              @foreach($users->where('role','!=','developer') as $u)
              @php
                $uI = collect(explode(' ',$u->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
                $ll = $u->last_login_at ? \Carbon\Carbon::parse($u->last_login_at)->diffForHumans() : 'Never';
              @endphp
              <div class="user-card" id="ucard{{ $u->id }}">
                <span class="sdot {{ $u->is_active ? 'on' : 'off' }}" id="sdot{{ $u->id }}"></span>
                <div class="u-av" style="background:linear-gradient(135deg,{{ $u->avatar_color }}cc,{{ $u->avatar_color }});">
                  @if($u->avatar_image)<img src="{{ asset('storage/avatars/'.$u->avatar_image) }}" alt="{{ $uI }}">@else{{ $uI }}@endif
                </div>
                <div class="u-info">
                  <div class="u-name">{{ $u->name }}</div>
                  <div class="u-meta">{{ ucfirst($u->role) }} · <span id="albl{{ $u->id }}">{{ $u->is_active ? 'Active' : 'Inactive' }}</span> · {{ $ll }}</div>
                </div>
                <div class="u-actions">
                  <button class="u-btn u-btn-hist" onclick="openHistory({{ $u->id }},'{{ addslashes($u->name) }}')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 13"/></svg>
                    History
                  </button>
                  <button class="u-btn u-btn-imp" onclick="openImpersonateModal({{ $u->id }},'{{ addslashes($u->name) }}','{{ addslashes($u->role) }}','{{ $u->avatar_color }}','{{ $u->avatar_image ?? '' }}')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    Impersonate
                  </button>
                  <button class="u-btn u-btn-reset" onclick="openResetModal({{ $u->id }},'{{ addslashes($u->name) }}')">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Reset PW
                  </button>
                  <button class="u-btn {{ $u->is_active ? 'u-btn-off' : 'u-btn-on' }}" id="tbtn{{ $u->id }}" onclick="toggleActive({{ $u->id }})">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18.36 6.64a9 9 0 11-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                    <span id="tlbl{{ $u->id }}">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</span>
                  </button>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- AUDIT LOG --}}
        <div class="sec fade-up d3" id="logs">
          <div class="sec-head">
            <div class="sec-head-l">
              <div class="sec-ico green">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div>
                <div class="sec-title">Audit Log</div>
                <div class="sec-sub">Recent system activity across all users</div>
              </div>
            </div>
            <span class="sec-badge badge-green">Live</span>
          </div>
          <div class="sec-body">
            @if($recentActivity->isEmpty())
              <p style="font-size:.78rem;color:var(--muted);text-align:center;padding:18px 0;">No activity logged yet.</p>
            @else
            <div class="log-list">
              @foreach($recentActivity as $log)
              @php
                $dc = match(strtolower($log['action']??'')) {
                  'created','added'           => ['dot'=>'dc','tag'=>'tag-c','lbl'=>'CREATE'],
                  'updated','edited','changed' => ['dot'=>'du','tag'=>'tag-u','lbl'=>'UPDATE'],
                  'deleted','removed'          => ['dot'=>'dd','tag'=>'tag-d','lbl'=>'DELETE'],
                  default                      => ['dot'=>'do','tag'=>'tag-o','lbl'=>'EVENT'],
                };
              @endphp
              <div class="log-row">
                <span class="log-dot {{ $dc['dot'] }}"></span>
                <div class="log-body">
                  <div class="log-action">
                    {{ $log['action'] ?? '—' }}
                    <span class="log-tag {{ $dc['tag'] }}">{{ $dc['lbl'] }}</span>
                    @if(!empty($log['record_title']))
                      <span style="color:var(--muted2);font-weight:500;font-size:.72rem;"> — {{ Str::limit($log['record_title'],38) }}</span>
                    @endif
                  </div>
                  <div class="log-by">by {{ $log['user_name'] ?? 'Unknown' }}</div>
                </div>
                <div class="log-time">{{ $log['time_ago'] ?? '—' }}</div>
              </div>
              @endforeach
            </div>
            @endif
          </div>
        </div>

      </div>{{-- end left --}}

      {{-- ══ RIGHT COLUMN ══ --}}
      <div class="dev-right">

        {{-- SECURITY --}}
        <div class="sec fade-up d2" id="security">
          <div class="sec-head">
            <div class="sec-head-l">
              <div class="sec-ico red">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
              </div>
              <div>
                <div class="sec-title">Security</div>
                <div class="sec-sub">Environment &amp; session status</div>
              </div>
            </div>
          </div>
          <div class="sec-body" style="padding-top:14px;padding-bottom:14px;">
            <div class="sec-rows">
              <div class="srow">
                <span class="srow-label">Environment</span>
                <span class="srow-val {{ app()->environment('production') ? 'sv-green' : 'sv-amber' }}">
                  {{ strtoupper(app()->environment()) }}
                </span>
              </div>
              <div class="srow">
                <span class="srow-label">Debug Mode</span>
                <span class="srow-val {{ config('app.debug') ? 'sv-red' : 'sv-green' }}">
                  {{ config('app.debug') ? 'ON — Disable in Prod' : 'OFF' }}
                </span>
              </div>
              <div class="srow">
                <span class="srow-label">PHP Version</span>
                <span class="srow-val sv-muted">{{ phpversion() }}</span>
              </div>
              <div class="srow">
                <span class="srow-label">Laravel Version</span>
                <span class="srow-val sv-muted">{{ app()->version() }}</span>
              </div>
              <div class="srow">
                <span class="srow-label">Session Driver</span>
                <span class="srow-val sv-blue">{{ strtoupper(config('session.driver','file')) }}</span>
              </div>
              <div class="srow">
                <span class="srow-label">Active Profiles</span>
                <span class="srow-val sv-green">{{ $kpis['active_users'] }} / {{ $kpis['total_users'] }}</span>
              </div>
              <div class="srow">
                <span class="srow-label">Server Time</span>
                <span class="srow-val sv-muted" id="serverTime">{{ now()->format('H:i:s') }}</span>
              </div>
            </div>
          </div>
        </div>

        {{-- SYSTEM CONTROL PANEL --}}
        <div class="sec fade-up d3" id="maintenance">
          <div class="sec-head">
            <div class="sec-head-l">
              <div class="sec-ico amber">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                </svg>
              </div>
              <div>
                <div class="sec-title">System Control</div>
                <div class="sec-sub">Maintenance, debug &amp; system modes</div>
              </div>
            </div>
            <span class="sec-badge badge-amber" id="ctrlStatusBadge">
              {{ (!empty($systemFlags['maintenance_mode']) || !empty($systemFlags['debug_mode'])) ? 'ACTIVE' : 'All Clear' }}
            </span>
          </div>
          <div class="sec-body" style="padding-top:14px;padding-bottom:14px;">
            <div class="ctrl-panel">

              {{-- MAINTENANCE MODE --}}
              <div class="ctrl-row {{ !empty($systemFlags['maintenance_mode']) ? 'active-maintenance' : '' }}" id="ctrlRowMaint">
                <div class="ctrl-ico" style="background:var(--amber-dim);color:var(--amber);">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                  </svg>
                </div>
                <div class="ctrl-body">
                  <div class="ctrl-label">Maintenance Mode</div>
                  <div class="ctrl-sub" id="maintSubLabel">
                    {{ !empty($systemFlags['maintenance_mode']) ? 'ACTIVE — Admins &amp; guests are blocked' : 'Off — System is fully accessible' }}
                  </div>
                </div>
                <div class="toggle-wrap">
                  <span class="toggle-lbl" id="maintToggleLbl" style="color:{{ !empty($systemFlags['maintenance_mode']) ? 'var(--amber)' : 'var(--muted)' }};">
                    {{ !empty($systemFlags['maintenance_mode']) ? 'ON' : 'OFF' }}
                  </span>
                  <label class="toggle">
                    <input type="checkbox" id="maintToggle" {{ !empty($systemFlags['maintenance_mode']) ? 'checked' : '' }}
                      onchange="handleMaintToggle(this.checked)">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
              </div>

              {{-- DEBUG MODE --}}
              <div class="ctrl-row {{ !empty($systemFlags['debug_mode']) ? 'active-debug' : '' }}" id="ctrlRowDebug">
                <div class="ctrl-ico" style="background:var(--red-dim);color:var(--red);">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                  </svg>
                </div>
                <div class="ctrl-body">
                  <div class="ctrl-label">Debug Mode</div>
                  <div class="ctrl-sub" id="debugSubLabel">
                    {{ !empty($systemFlags['debug_mode']) ? 'ACTIVE — System paused for inspection' : 'Off — No active bugs being traced' }}
                  </div>
                </div>
                <div class="toggle-wrap">
                  <span class="toggle-lbl" id="debugToggleLbl" style="color:{{ !empty($systemFlags['debug_mode']) ? 'var(--red)' : 'var(--muted)' }};">
                    {{ !empty($systemFlags['debug_mode']) ? 'ON' : 'OFF' }}
                  </span>
                  <label class="toggle debug-toggle">
                    <input type="checkbox" id="debugToggle" {{ !empty($systemFlags['debug_mode']) ? 'checked' : '' }}
                      onchange="handleDebugToggle(this.checked)">
                    <span class="toggle-slider"></span>
                  </label>
                </div>
              </div>

              {{-- SCHEDULED MAINTENANCE --}}
              <div class="sched-row">
                <div class="sched-top">
                  <div class="ctrl-ico" style="background:var(--blue-dim);color:var(--blue);">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                      <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                  </div>
                  <div class="ctrl-body">
                    <div class="ctrl-label">Scheduled Maintenance</div>
                    <div class="ctrl-sub" id="schedSubLabel">
                      @if(!empty($systemFlags['scheduled_at']))
                        Scheduled: {{ \Carbon\Carbon::parse($systemFlags['scheduled_at'])->format('M d, Y · H:i') }}
                      @else
                        No schedule set — set a date/time below
                      @endif
                    </div>
                  </div>
                </div>
                <div class="sched-inputs">
                  <input type="datetime-local" class="sched-input" id="schedInput"
                    value="{{ !empty($systemFlags['scheduled_at']) ? \Carbon\Carbon::parse($systemFlags['scheduled_at'])->format('Y-m-d\TH:i') : '' }}"
                    min="{{ now()->format('Y-m-d\TH:i') }}">
                  <button class="btn-sched" onclick="submitSchedule()">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><polyline points="20 6 9 17 4 12"/></svg>
                    Set Schedule
                  </button>
                  @if(!empty($systemFlags['scheduled_at']))
                  <button class="btn-sched-clear" id="clearSchedBtn" onclick="clearSchedule()">Clear</button>
                  @endif
                </div>
                <div class="countdown-bar {{ !empty($systemFlags['scheduled_at']) ? 'visible' : '' }}" id="countdownBar">
                  <svg width="12" height="12" fill="none" stroke="var(--amber)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  <span class="countdown-lbl">Activates in</span>
                  <span class="countdown-val" id="countdownVal">—</span>
                </div>
              </div>

            </div>
          </div>
        </div>

        {{-- MAINTENANCE LOG --}}
        <div class="sec fade-up d4" id="maintLog">
          <div class="sec-head">
            <div class="sec-head-l">
              <div class="sec-ico purple">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div>
                <div class="sec-title">Maintenance Log</div>
                <div class="sec-sub">Mode toggle history — dev only</div>
              </div>
            </div>
            <span class="sec-badge badge-purple">Dev Only</span>
          </div>
          <div class="sec-body" style="padding-top:10px;padding-bottom:10px;">
            <div class="mlog-list" id="maintLogList">
              @if(empty($maintenanceLogs) || count($maintenanceLogs) === 0)
                <div class="mlog-empty">No maintenance events logged yet.</div>
              @else
                @foreach($maintenanceLogs as $mlog)
                <div class="mlog-row">
                  <span class="mlog-dot {{ str_contains($mlog['action'],'debug') ? (str_contains($mlog['action'],'ON') ? 'debug-on' : 'debug-off') : (str_contains($mlog['action'],'ON') ? 'on' : 'off') }}"></span>
                  <div class="mlog-body">{{ $mlog['action'] }}</div>
                  <div class="mlog-time">{{ $mlog['by'] }} · {{ $mlog['time_ago'] }}</div>
                </div>
                @endforeach
              @endif
            </div>
          </div>
        </div>

        

      </div>{{-- end right --}}
    </div>{{-- end grid --}}
  </div>{{-- end content --}}
</div>{{-- end main-wrap --}}

{{-- ══════════════ RESET PASSWORD MODAL ══════════════ --}}
<div class="modal-overlay" id="resetModal">
  <div class="modal-box">
    <div class="modal-title">Reset Password</div>
    <div class="modal-sub" id="resetModalSub">Set a new password for this user.</div>
    <div class="mfield">
      <label>New Password</label>
      <input type="password" id="resetPwInput" placeholder="Min. 8 characters" autocomplete="new-password">
    </div>
    <div class="mfield">
      <label>Confirm Password</label>
      <input type="password" id="resetPwConfirm" placeholder="Re-enter password" autocomplete="new-password">
    </div>
    <div class="modal-alert" id="resetAlert"></div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeResetModal()">Cancel</button>
      <button class="btn-confirm" id="resetConfirmBtn" onclick="submitReset()">
        <span id="resetBtnText">Reset Password</span>
        <span id="resetBtnSpinner" style="display:none;"><span class="spinner"></span></span>
      </button>
    </div>
  </div>
</div>

{{-- ══════════════ LOGIN HISTORY MODAL ══════════════ --}}
<div class="modal-overlay" id="historyModal">
  <div class="history-box">
    <div class="history-head">
      <div style="font-size:.95rem;font-weight:800;color:var(--ink);" id="historyTitle">Activity History</div>
      <div style="font-size:.72rem;color:var(--muted2);margin-top:3px;font-family:'DM Mono',monospace;" id="historyLastLogin"></div>
    </div>
    <div class="history-body" id="historyBody">
      <p style="font-size:.78rem;color:var(--muted);text-align:center;padding:20px 0;">Loading…</p>
    </div>
    <div style="padding:14px 26px;border-top:1px solid var(--line);">
      <button class="btn-cancel" onclick="closeHistory()" style="width:100%;">Close</button>
    </div>
  </div>
</div>

{{-- ══════════════ LOGOUT MODAL ══════════════ --}}
<div class="modal-overlay" id="logoutModal">
  <div class="logout-box">
    <div class="logout-ico">
      <svg width="20" height="20" fill="none" stroke="var(--maroon)" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>
    <div style="font-size:1.1rem;font-weight:800;color:var(--ink);margin-bottom:5px;">Sign Out?</div>
    <p style="font-size:.78rem;color:var(--muted2);line-height:1.6;margin-bottom:20px;">You'll be returned to the login page.</p>
    <div class="logout-actions">
      <button type="button" class="btn-cancel" id="cancelLogout">Cancel</button>
      <form method="POST" action="{{ url('/logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Sign Out</button>
      </form>
    </div>
  </div>
</div>

{{-- ══════════════ MAINTENANCE MODE MODAL ══════════════ --}}
<div class="modal-overlay" id="maintModal">
  <div class="ctrl-modal-box">
    <div class="ctrl-modal-icon amber" id="maintModalIcon">
      <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
      </svg>
    </div>
    <div class="ctrl-modal-title" id="maintModalTitle">Enable Maintenance Mode?</div>
    <div class="ctrl-modal-sub" id="maintModalSub">All admins and guests will be immediately redirected to the maintenance page. You will retain full access as developer.</div>
    <div class="ctrl-modal-warn amber" id="maintModalWarn">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      <span id="maintModalWarnText">This will block all active supervisor sessions immediately.</span>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="cancelMaintToggle()">Cancel</button>
      <button class="btn-activate-amber" id="maintConfirmBtn" onclick="confirmMaintToggle()">
        <span id="maintConfirmText">Enable Maintenance Mode</span>
        <span id="maintConfirmSpinner" style="display:none;"><span class="spinner" style="border-top-color:#000;"></span></span>
      </button>
    </div>
  </div>
</div>

{{-- ══════════════ DEBUG MODE MODAL ══════════════ --}}
<div class="modal-overlay" id="debugModal">
  <div class="ctrl-modal-box">
    <div class="ctrl-modal-icon red">
      <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
    </div>
    <div class="ctrl-modal-title" id="debugModalTitle">Enable Debug Mode?</div>
    <div class="ctrl-modal-sub" id="debugModalSub">The system will be paused for all non-developer users. Use this when actively tracing a bug reported by a supervisor.</div>
    <div class="ctrl-modal-warn red">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span id="debugModalWarnText">Supervisors will be blocked until you turn debug mode off.</span>
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="cancelDebugToggle()">Cancel</button>
      <button class="btn-activate-red" id="debugConfirmBtn" onclick="confirmDebugToggle()">
        <span id="debugConfirmText">Enable Debug Mode</span>
        <span id="debugConfirmSpinner" style="display:none;"><span class="spinner"></span></span>
      </button>
    </div>
  </div>
</div>

{{-- ══════════════ IMPERSONATE MODAL ══════════════ --}}
<div class="modal-overlay" id="impersonateModal">
  <div class="imp-modal-box">
    <div class="ctrl-modal-icon purple">
      <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
      </svg>
    </div>
    <div class="ctrl-modal-title">Impersonate User</div>
    <div class="ctrl-modal-sub">You'll browse the system exactly as this user sees it — in a new tab. Your developer session stays intact.</div>
    <div class="imp-user-preview" id="impUserPreview">
      <div class="imp-prev-av" id="impPrevAv" style="background:linear-gradient(135deg,#8B5CF6cc,#8B5CF6);">
        <span id="impPrevInitials">?</span>
      </div>
      <div>
        <div class="imp-prev-name" id="impPrevName">—</div>
        <div class="imp-prev-role" id="impPrevRole">—</div>
      </div>
    </div>
    <div class="ctrl-modal-warn amber" style="margin-bottom:18px;">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      Opens in a new tab. A persistent bar will remind you that you're impersonating. Click "Exit Impersonation" to return to your dev session.
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeImpersonateModal()">Cancel</button>
      <button class="btn-imp-confirm" id="impConfirmBtn" onclick="confirmImpersonate()">
        <span id="impConfirmText">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:5px;"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          Open as this user
        </span>
        <span id="impConfirmSpinner" style="display:none;"><span class="spinner"></span></span>
      </button>
    </div>
  </div>
</div>

<script>
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  const SCROLL_LOCK_MODAL_IDS = ['resetModal', 'historyModal', 'logoutModal', 'maintModal', 'debugModal', 'impersonateModal'];

  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const anyModal = SCROLL_LOCK_MODAL_IDS.some(id => document.getElementById(id)?.classList.contains('open'));
    document.body.style.overflow = (sidebarOpen || anyModal) ? 'hidden' : '';
  }

  let resetTargetId = null;

  /* ── Live Clock ── */
  function updateClock() {
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    document.getElementById('liveClock').textContent =
      pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
  }
  updateClock();
  setInterval(updateClock, 1000);

  /* ── Smooth scroll for sidebar links ── */
  window.scrollTo = function(selector) {
    const el = document.querySelector(selector);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  /* ── Toast ── */
  function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = `toast ${type}`;
    t.innerHTML = `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">${type==='success'?'<polyline points="20 6 9 17 4 12"/>':'<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}</svg>${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.classList.add('hiding'); setTimeout(() => t.remove(), 300); }, 4000);
  }

  /* ════════════════════════════════════════
     MAINTENANCE MODE TOGGLE
  ════════════════════════════════════════ */
  let pendingMaintState = false;

  function handleMaintToggle(isOn) {
    // Revert visual immediately — wait for confirmation
    document.getElementById('maintToggle').checked = !isOn;
    pendingMaintState = isOn;

    const modal = document.getElementById('maintModal');
    const title  = document.getElementById('maintModalTitle');
    const sub    = document.getElementById('maintModalSub');
    const warn   = document.getElementById('maintModalWarnText');
    const btn    = document.getElementById('maintConfirmText');

    if (isOn) {
      title.textContent = 'Enable Maintenance Mode?';
      sub.textContent   = 'All admins and guests will be immediately redirected to the maintenance page. You will retain full access as developer.';
      warn.textContent  = 'This will block all active supervisor sessions immediately.';
      btn.textContent   = 'Enable Maintenance Mode';
    } else {
      title.textContent = 'Disable Maintenance Mode?';
      sub.textContent   = 'The system will become accessible again to all admins and guests.';
      warn.textContent  = 'All users will regain access as soon as this is confirmed.';
      btn.textContent   = 'Disable Maintenance Mode';
    }
    modal.classList.add('open');
    syncBodyScrollLock();
  }

  function cancelMaintToggle() {
    document.getElementById('maintModal').classList.remove('open');
    syncBodyScrollLock();
  }

  async function confirmMaintToggle() {
    const btn     = document.getElementById('maintConfirmBtn');
    const btnText = document.getElementById('maintConfirmText');
    const spinner = document.getElementById('maintConfirmSpinner');
    btn.disabled = true; btnText.style.display = 'none'; spinner.style.display = '';

    try {
      const resp = await fetch('/dev/system/maintenance', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ enabled: pendingMaintState }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        document.getElementById('maintToggle').checked = pendingMaintState;
        const row = document.getElementById('ctrlRowMaint');
        const lbl = document.getElementById('maintToggleLbl');
        const sub = document.getElementById('maintSubLabel');
        if (pendingMaintState) {
          row.classList.add('active-maintenance');
          lbl.style.color = 'var(--amber)'; lbl.textContent = 'ON';
          sub.textContent = 'ACTIVE — Admins & guests are blocked';
        } else {
          row.classList.remove('active-maintenance');
          lbl.style.color = 'var(--muted)'; lbl.textContent = 'OFF';
          sub.textContent = 'Off — System is fully accessible';
        }
        updateCtrlBadge();
        appendMaintLog(data.log_entry);
        showToast(pendingMaintState ? 'Maintenance Mode enabled.' : 'Maintenance Mode disabled.');
        document.getElementById('maintModal').classList.remove('open');
        syncBodyScrollLock();
      } else {
        showToast(data.message || 'Failed to update.', 'error');
      }
    } catch(e) { showToast('Something went wrong.', 'error'); }
    finally { btn.disabled=false; btnText.style.display=''; spinner.style.display='none'; }
  }

  /* ════════════════════════════════════════
     DEBUG MODE TOGGLE
  ════════════════════════════════════════ */
  let pendingDebugState = false;

  function handleDebugToggle(isOn) {
    document.getElementById('debugToggle').checked = !isOn;
    pendingDebugState = isOn;

    const title = document.getElementById('debugModalTitle');
    const sub   = document.getElementById('debugModalSub');
    const warn  = document.getElementById('debugModalWarnText');
    const btn   = document.getElementById('debugConfirmText');

    if (isOn) {
      title.textContent = 'Enable Debug Mode?';
      sub.textContent   = 'The system will be paused for all non-developer users. Use this when actively tracing a bug.';
      warn.textContent  = 'Supervisors will be blocked until you turn debug mode off.';
      btn.textContent   = 'Enable Debug Mode';
    } else {
      title.textContent = 'Disable Debug Mode?';
      sub.textContent   = 'The system will resume normal operation for all users.';
      warn.textContent  = 'All supervisors and guests will regain access immediately.';
      btn.textContent   = 'Disable Debug Mode';
    }
    document.getElementById('debugModal').classList.add('open');
    syncBodyScrollLock();
  }

  function cancelDebugToggle() {
    document.getElementById('debugModal').classList.remove('open');
    syncBodyScrollLock();
  }

  async function confirmDebugToggle() {
    const btn     = document.getElementById('debugConfirmBtn');
    const btnText = document.getElementById('debugConfirmText');
    const spinner = document.getElementById('debugConfirmSpinner');
    btn.disabled = true; btnText.style.display = 'none'; spinner.style.display = '';

    try {
      const resp = await fetch('/dev/system/debug', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ enabled: pendingDebugState }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        document.getElementById('debugToggle').checked = pendingDebugState;
        const row = document.getElementById('ctrlRowDebug');
        const lbl = document.getElementById('debugToggleLbl');
        const sub = document.getElementById('debugSubLabel');
        if (pendingDebugState) {
          row.classList.add('active-debug');
          lbl.style.color = 'var(--red)'; lbl.textContent = 'ON';
          sub.textContent = 'ACTIVE — System paused for inspection';
        } else {
          row.classList.remove('active-debug');
          lbl.style.color = 'var(--muted)'; lbl.textContent = 'OFF';
          sub.textContent = 'Off — No active bugs being traced';
        }
        updateCtrlBadge();
        appendMaintLog(data.log_entry);
        showToast(pendingDebugState ? 'Debug Mode enabled.' : 'Debug Mode disabled.');
        document.getElementById('debugModal').classList.remove('open');
        syncBodyScrollLock();
      } else {
        showToast(data.message || 'Failed to update.', 'error');
      }
    } catch(e) { showToast('Something went wrong.', 'error'); }
    finally { btn.disabled=false; btnText.style.display=''; spinner.style.display='none'; }
  }

  /* ════════════════════════════════════════
     CTRL BADGE UPDATE
  ════════════════════════════════════════ */
  function updateCtrlBadge() {
    const maintOn = document.getElementById('maintToggle').checked;
    const debugOn = document.getElementById('debugToggle').checked;
    document.getElementById('ctrlStatusBadge').textContent = (maintOn || debugOn) ? 'ACTIVE' : 'All Clear';
  }

  /* ════════════════════════════════════════
     MAINTENANCE LOG — append entry live
  ════════════════════════════════════════ */
  function appendMaintLog(entry) {
    if (!entry) return;
    const list = document.getElementById('maintLogList');
    // Remove empty state if present
    const empty = list.querySelector('.mlog-empty');
    if (empty) empty.remove();

    const isDebug = (entry.action||'').toLowerCase().includes('debug');
    const isOn    = (entry.action||'').includes('ON');
    const dotCls  = isDebug ? (isOn ? 'debug-on' : 'debug-off') : (isOn ? 'on' : 'off');

    const row = document.createElement('div');
    row.className = 'mlog-row';
    row.innerHTML = `
      <span class="mlog-dot ${dotCls}"></span>
      <div class="mlog-body">${entry.action}</div>
      <div class="mlog-time">${entry.by} · just now</div>
    `;
    list.prepend(row);
  }

  /* ════════════════════════════════════════
     SCHEDULED MAINTENANCE
  ════════════════════════════════════════ */
  async function submitSchedule() {
    const val = document.getElementById('schedInput').value;
    if (!val) { showToast('Please pick a date and time.', 'error'); return; }

    try {
      const resp = await fetch('/dev/system/schedule', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ scheduled_at: val }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        schedTarget = new Date(val);
        document.getElementById('countdownBar').classList.add('visible');
        document.getElementById('schedSubLabel').textContent = 'Scheduled: ' + schedTarget.toLocaleString();
        showToast('Maintenance scheduled successfully.');
      } else { showToast(data.message || 'Failed to schedule.', 'error'); }
    } catch(e) { showToast('Something went wrong.', 'error'); }
  }

  async function clearSchedule() {
    try {
      const resp = await fetch('/dev/system/schedule/clear', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        schedTarget = null;
        document.getElementById('schedInput').value = '';
        document.getElementById('countdownBar').classList.remove('visible');
        document.getElementById('schedSubLabel').textContent = 'No schedule set — set a date/time below';
        document.getElementById('countdownVal').textContent = '—';
        showToast('Schedule cleared.');
      } else { showToast(data.message || 'Failed to clear.', 'error'); }
    } catch(e) { showToast('Something went wrong.', 'error'); }
  }

  /* ── Countdown Timer ── */
  let schedTarget = (function() {
    const inp = document.getElementById('schedInput');
    return inp && inp.value ? new Date(inp.value) : null;
  })();

  let schedActivated = false;

  function updateCountdown() {
    if (!schedTarget) return;
    const diff = schedTarget - new Date();
    if (diff <= 0) {
      document.getElementById('countdownVal').textContent = 'Activating…';

      // Only fire once — auto-activate maintenance via API
      if (!schedActivated) {
        schedActivated = true;
        fetch('/dev/system/schedule/activate', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept':       'application/json',
            'X-CSRF-TOKEN': CSRF,
          },
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            document.getElementById('countdownVal').textContent = 'Active';
            document.getElementById('countdownBar').classList.remove('visible');
            document.getElementById('ctrlRowMaint').classList.add('active-maintenance');
            document.getElementById('maintToggle').checked = true;
            document.getElementById('maintToggleLbl').textContent = 'ON';
            document.getElementById('maintToggleLbl').style.color = 'var(--amber)';
            document.getElementById('maintSubLabel').textContent = 'ACTIVE — Admins & guests are blocked';
            document.getElementById('ctrlStatusBadge').textContent = 'ACTIVE';
            document.getElementById('schedSubLabel').textContent = 'No schedule set — set a date/time below';
            schedTarget = null;
            showToast('Maintenance mode auto-activated.', 'success');
          }
        })
        .catch(() => {
          document.getElementById('countdownVal').textContent = 'Activation failed — toggle manually';
        });
      }
      return;
    }
    const d = Math.floor(diff / 86400000);
    const h = Math.floor((diff % 86400000) / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    const pad = n => String(n).padStart(2,'0');
    document.getElementById('countdownVal').textContent =
      (d > 0 ? d + 'd ' : '') + pad(h) + ':' + pad(m) + ':' + pad(s);
  }
  updateCountdown();
  setInterval(updateCountdown, 1000);

  /* ════════════════════════════════════════
     IMPERSONATE USER
  ════════════════════════════════════════ */
  let impTargetId = null;

  function openImpersonateModal(id, name, role, color, avatar) {
    impTargetId = id;
    document.getElementById('impPrevName').textContent = name;
    document.getElementById('impPrevRole').textContent = ucfirst(role);

    const av = document.getElementById('impPrevAv');
    av.style.background = `linear-gradient(135deg,${color}cc,${color})`;
    if (avatar) {
      av.innerHTML = `<img src="/storage/avatars/${avatar}" alt="${getInitials(name)}" style="width:44px;height:44px;object-fit:cover;border-radius:12px;">`;
    } else {
      document.getElementById('impPrevInitials').textContent = getInitials(name);
      av.innerHTML = `<span id="impPrevInitials">${getInitials(name)}</span>`;
    }
    document.getElementById('impersonateModal').classList.add('open');
    syncBodyScrollLock();
  }

  function closeImpersonateModal() {
    document.getElementById('impersonateModal').classList.remove('open');
    impTargetId = null;
    syncBodyScrollLock();
  }

  async function confirmImpersonate() {
    if (!impTargetId) return;
    const btn     = document.getElementById('impConfirmBtn');
    const btnText = document.getElementById('impConfirmText');
    const spinner = document.getElementById('impConfirmSpinner');
    btn.disabled = true; btnText.style.display = 'none'; spinner.style.display = '';

    try {
      const resp = await fetch(`/dev/impersonate/${impTargetId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
      });
      const data = await resp.json();
      if (resp.ok && data.success && data.redirect) {
        closeImpersonateModal();
        showToast('Opening impersonation session…');
        setTimeout(() => window.open(data.redirect, '_blank'), 600);
      } else { showToast(data.message || 'Failed to impersonate.', 'error'); }
    } catch(e) { showToast('Something went wrong.', 'error'); }
    finally { btn.disabled=false; btnText.style.display=''; spinner.style.display='none'; }
  }

  function getInitials(name) {
    return name.split(' ').map(w => w.charAt(0).toUpperCase()).slice(0,2).join('');
  }
  function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  /* ── Reset Password ── */
  function openResetModal(id, name) {
    resetTargetId = id;
    document.getElementById('resetModalSub').textContent = `Set a new password for ${name}.`;
    document.getElementById('resetPwInput').value = '';
    document.getElementById('resetPwConfirm').value = '';
    document.getElementById('resetAlert').className = 'modal-alert';
    document.getElementById('resetAlert').textContent = '';
    document.getElementById('resetModal').classList.add('open');
    syncBodyScrollLock();
  }
  function closeResetModal() {
    document.getElementById('resetModal').classList.remove('open');
    resetTargetId = null;
    syncBodyScrollLock();
  }
  async function submitReset() {
    const pw      = document.getElementById('resetPwInput').value;
    const confirm = document.getElementById('resetPwConfirm').value;
    const alertEl = document.getElementById('resetAlert');
    const btn     = document.getElementById('resetConfirmBtn');
    const btnText = document.getElementById('resetBtnText');
    const spinner = document.getElementById('resetBtnSpinner');
    const setAlert = (msg, type='error') => { alertEl.className=`modal-alert ${type}`; alertEl.textContent=msg; };

    if (!pw)            { setAlert('Please enter a new password.'); return; }
    if (pw.length < 8)  { setAlert('Password must be at least 8 characters.'); return; }
    if (pw !== confirm) { setAlert('Passwords do not match.'); return; }

    btn.disabled = true; btnText.style.display = 'none'; spinner.style.display = '';

    try {
      const resp = await fetch(`/dev/users/${resetTargetId}/reset-password`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ new_password: pw }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) { closeResetModal(); showToast('Password reset successfully.'); }
      else setAlert(data.message || 'Failed to reset password.');
    } catch(e) { setAlert('Something went wrong. Please try again.'); }
    finally { btn.disabled=false; btnText.style.display=''; spinner.style.display='none'; }
  }

  /* ── Toggle Active ── */
  async function toggleActive(id) {
    try {
      const resp = await fetch(`/dev/users/${id}/toggle-active`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        const on = data.is_active;
        document.getElementById(`sdot${id}`).className = `sdot ${on ? 'on' : 'off'}`;
        document.getElementById(`albl${id}`).textContent = on ? 'Active' : 'Inactive';
        const btn = document.getElementById(`tbtn${id}`);
        btn.className = `u-btn ${on ? 'u-btn-off' : 'u-btn-on'}`;
        document.getElementById(`tlbl${id}`).textContent = on ? 'Deactivate' : 'Activate';
        showToast(data.message);
      } else showToast(data.message || 'Failed to update status.', 'error');
    } catch(e) { showToast('Something went wrong.', 'error'); }
  }

  /* ── Login History ── */
  async function openHistory(id, name) {
    document.getElementById('historyTitle').textContent = `Activity — ${name}`;
    document.getElementById('historyLastLogin').textContent = 'Loading…';
    document.getElementById('historyBody').innerHTML = '<p style="font-size:.78rem;color:var(--muted);text-align:center;padding:20px 0;">Loading…</p>';
    document.getElementById('historyModal').classList.add('open');
    syncBodyScrollLock();
    try {
      const resp = await fetch(`/dev/users/${id}/login-history`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
      const data = await resp.json();
      if (resp.ok && data.success) {
        document.getElementById('historyLastLogin').textContent = data.last_login_at
          ? `Last login: ${new Date(data.last_login_at).toLocaleString()}`
          : 'No login recorded yet.';
        if (!data.logs || data.logs.length === 0) {
          document.getElementById('historyBody').innerHTML = '<p style="font-size:.78rem;color:var(--muted);text-align:center;padding:20px 0;">No activity found.</p>';
          return;
        }
        const dotClass = a => {
          const l = (a||'').toLowerCase();
          if (['created','added'].includes(l))           return 'dc';
          if (['updated','edited','changed'].includes(l)) return 'du';
          if (['deleted','removed'].includes(l))          return 'dd';
          return 'do';
        };
        document.getElementById('historyBody').innerHTML = data.logs.map(log => `
          <div class="history-item">
            <span class="log-dot ${dotClass(log.action)}" style="margin-top:5px;flex-shrink:0;"></span>
            <div class="log-body">
              <div class="log-action">${log.action ?? '—'}${log.record_title ? ' — <span style="color:var(--muted2);">'+log.record_title+'</span>' : ''}</div>
            </div>
            <div class="log-time">${log.time_ago ?? '—'}</div>
          </div>`).join('');
      } else {
        document.getElementById('historyBody').innerHTML = '<p style="font-size:.78rem;color:var(--muted);text-align:center;padding:20px 0;">Could not load history.</p>';
      }
    } catch(e) {
      document.getElementById('historyBody').innerHTML = '<p style="font-size:.78rem;color:var(--red);text-align:center;padding:20px 0;">Error loading history.</p>';
    }
  }
  function closeHistory() {
    document.getElementById('historyModal').classList.remove('open');
    syncBodyScrollLock();
  }

  /* ── Logout ── */
  document.getElementById('logoutBtn').addEventListener('click', () => {
    document.getElementById('logoutModal').classList.add('open');
    syncBodyScrollLock();
  });
  document.getElementById('cancelLogout').addEventListener('click', () => {
    document.getElementById('logoutModal').classList.remove('open');
    syncBodyScrollLock();
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      ['resetModal','historyModal','logoutModal','maintModal','debugModal','impersonateModal'].forEach(id => {
        document.getElementById(id)?.classList.remove('open');
      });
      closeMobileSidebar();
    }
  });
  ['resetModal','historyModal','logoutModal','maintModal','debugModal','impersonateModal'].forEach(id => {
    const m = document.getElementById(id);
    m.addEventListener('click', e => {
      if (e.target === m) {
        m.classList.remove('open');
        syncBodyScrollLock();
      }
    });
  });

  /* ══════════════════════════════════════
     MOBILE SIDEBAR TOGGLE
  ══════════════════════════════════════ */
  const hamburgerBtn    = document.getElementById('hamburgerBtn');
  const mainSidebar     = document.getElementById('mainSidebar');
  const sidebarBackdrop = document.getElementById('sidebarBackdrop');

  function openMobileSidebar() {
    mainSidebar?.classList.add('mobile-open');
    sidebarBackdrop?.classList.add('open');
    hamburgerBtn?.setAttribute('aria-expanded', 'true');
    syncBodyScrollLock();
  }

  function closeMobileSidebar() {
    mainSidebar?.classList.remove('mobile-open');
    sidebarBackdrop?.classList.remove('open');
    hamburgerBtn?.setAttribute('aria-expanded', 'false');
    syncBodyScrollLock();
  }

  hamburgerBtn?.addEventListener('click', function(e) {
    e.stopPropagation();
    const isOpen = mainSidebar?.classList.contains('mobile-open');
    isOpen ? closeMobileSidebar() : openMobileSidebar();
  });

  sidebarBackdrop?.addEventListener('click', closeMobileSidebar);

  // Close sidebar on nav link click (mobile UX)
  mainSidebar?.querySelectorAll('a.nav-item').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) closeMobileSidebar();
    });
  });

  mainSidebar?.querySelectorAll('button.nav-item').forEach(btn => {
    btn.addEventListener('click', () => {
      if (window.innerWidth <= 768) closeMobileSidebar();
    });
  });

  // Close sidebar on resize back to desktop
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeMobileSidebar();
  });
</script>
</body>
</html>