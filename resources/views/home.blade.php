<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon: #A52C30;
      --maroon2: #7E1F23;
      --maroon-light: rgba(165,44,48,0.12);
      --gold: #F0C860;
      --gold2: #E8B857;
      --ink: #0F172A;
      --muted: #64748B;
      --line: rgba(15,23,42,.08);
      --card: #FFFFFF;
      --sidebar-w: 72px;
      --bg: #F1F4F9;
      --pad-x: clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max: 1440px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { -webkit-font-smoothing: antialiased; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg);
      color: var(--ink);
      min-height: 100vh;
      overflow-x: hidden;
      padding-left: env(safe-area-inset-left);
      padding-right: env(safe-area-inset-right);
    }
    body.has-maint-banner .sidebar { top: 49px; }
    body.has-maint-banner .main-wrap {
      min-height: calc(100vh - 49px);
      padding-top: 49px;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0;
      width: var(--sidebar-w);
      background: linear-gradient(180deg, var(--maroon2) 0%, var(--maroon) 100%);
      display: flex; flex-direction: column; align-items: center;
      padding: 20px 0; z-index: 50;
      box-shadow: 4px 0 24px rgba(165,44,48,.18);
    }
    .sidebar-logo {
      width: 42px; height: 42px; border-radius: 14px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 1rem; color: #2a1a0b;
      margin-bottom: 32px; flex-shrink: 0;
      box-shadow: 0 6px 18px rgba(240,200,96,.35);
    }
    .sidebar-nav {
      display: flex; flex-direction: column; align-items: center;
      gap: 6px; flex: 1; width: 100%;
    }
    .nav-item {
      width: 48px; height: 48px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.55); cursor: pointer;
      transition: background .18s, color .18s;
      text-decoration: none; position: relative;
    }
    .nav-item:hover { background: rgba(255,255,255,.12); color: #fff; }
    .nav-item.active {
      background: rgba(255,255,255,.18); color: #fff;
      box-shadow: 0 4px 16px rgba(0,0,0,.15);
    }
    .nav-item.active::before {
      content: ''; position: absolute; left: 0; top: 50%;
      transform: translateY(-50%); width: 3px; height: 24px;
      background: var(--gold); border-radius: 0 3px 3px 0;
    }
    .nav-tooltip {
      position: absolute; left: calc(100% + 12px); top: 50%;
      transform: translateY(-50%); background: var(--ink);
      color: #fff; font-size: 0.7rem; font-weight: 600;
      padding: 5px 10px; border-radius: 8px; white-space: nowrap;
      pointer-events: none; opacity: 0; transition: opacity .15s;
      letter-spacing: .04em;
    }
    .nav-item:hover .nav-tooltip { opacity: 1; }
    .sidebar-bottom {
      display: flex; flex-direction: column; align-items: center; gap: 6px;
    }

    /* ── MAIN LAYOUT ── */
    .main-wrap {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* ── TOPBAR ── */
    .topbar {
      min-height: 72px;
      background: var(--card);
      border-bottom: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 10px var(--pad-x);
      position: sticky;
      top: 0;
      z-index: 40;
      box-shadow: 0 2px 16px rgba(15,23,42,.05);
    }
    .topbar-left {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
      flex: 1 1 auto;
    }
    .page-title {
      font-size: clamp(0.95rem, 0.4vw + 0.85rem, 1.15rem);
      font-weight: 800;
      letter-spacing: -.3px;
      color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-subtitle {
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted);
      font-weight: 500;
      overflow-wrap: anywhere;
    }
    .topbar-search {
      display: flex; align-items: center; gap: 10px;
      background: var(--bg); border: 1.5px solid var(--line);
      border-radius: 12px; padding: 8px 16px;
      width: min(260px, 100%);
      max-width: 260px;
      flex: 1 1 160px;
      min-width: 0;
      transition: border-color .2s, box-shadow .2s;
    }
    .topbar-search:focus-within {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light);
    }
    .topbar-search input {
      background: none; border: none; outline: none;
      font-family: inherit; font-size: 0.82rem; color: var(--ink); width: 100%;
    }
    .topbar-search input::placeholder { color: #94a3b8; }
    .topbar-right {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
    }
    .icon-btn {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted); transition: all .18s;
      position: relative;
    }
    .icon-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    /* ── BELL BADGE ── */
    .bell-badge {
      position: absolute; top: -5px; right: -5px;
      min-width: 18px; height: 18px; border-radius: 99px;
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff; font-size: .6rem; font-weight: 800;
      display: flex; align-items: center; justify-content: center;
      padding: 0 4px; border: 2px solid var(--card);
      font-family: 'DM Mono', monospace;
      animation: bellPop .4s cubic-bezier(.17,.67,.35,1.3);
      box-shadow: 0 2px 8px rgba(239,68,68,.4);
    }
    @keyframes bellPop {
      from { transform: scale(0); opacity: 0; }
      to   { transform: scale(1); opacity: 1; }
    }
    .bell-ring {
      animation: bellRing .6s ease-in-out;
    }
    @keyframes bellRing {
      0%,100% { transform: rotate(0); }
      20%     { transform: rotate(12deg); }
      40%     { transform: rotate(-10deg); }
      60%     { transform: rotate(8deg); }
      80%     { transform: rotate(-6deg); }
    }

    .btn-primary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: 10px clamp(12px, 2.5vw, 20px);
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s;
      text-decoration: none;
      width: auto;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(165,44,48,.35); }

    .btn-howto {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      background: var(--bg);
      border: 1.5px solid var(--line);
      color: var(--muted);
      cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 16px);
      border-radius: 12px;
      transition: all .18s;
      width: auto;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-howto:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .avatar {
      width: 40px; height: 40px; border-radius: 12px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.85rem; color: #2a1a0b; cursor: pointer;
    }

    /* ── CONTENT ── */
    .content {
      padding: 18px var(--pad-x);
      flex: 1;
      width: 100%;
      max-width: var(--shell-max);
      margin: 0 auto;
      box-sizing: border-box;
      background-color: var(--bg);
      background-image: url('{{ asset("images/abstractBGIMAGE12.png") }}');
      background-repeat: no-repeat;
      background-position: center center;
      background-size: cover;
      background-attachment: fixed;
    }

    /* ── MAINTENANCE BANNER ── */
    .maint-banner {
      position: fixed; top: 0; left: 0; right: 0; z-index: 200;
      background: linear-gradient(90deg, #92400e 0%, #b45309 50%, #92400e 100%);
      border-bottom: 1px solid rgba(245,158,11,.35);
      display: flex; align-items: center; justify-content: center; gap: 12px;
      padding: 10px 20px;
      box-shadow: 0 4px 24px rgba(180,83,9,.28);
      animation: bannerSlideDown .5s cubic-bezier(.17,.67,.35,1.05) forwards;
    }
    @keyframes bannerSlideDown {
      from { opacity: 0; transform: translateY(-100%); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .maint-banner.hiding { animation: bannerSlideUp .3s ease-out forwards; }
    @keyframes bannerSlideUp {
      from { opacity: 1; transform: translateY(0); }
      to   { opacity: 0; transform: translateY(-100%); }
    }
    .maint-banner-icon {
      width: 28px; height: 28px; border-radius: 8px;
      background: rgba(255,255,255,.15);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0; color: #fde68a;
    }
    .maint-banner-body {
      display: flex; align-items: center; gap: 10px;
      flex-wrap: wrap; justify-content: center;
    }
    .maint-banner-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.78rem; font-weight: 800;
      color: #fef3c7; letter-spacing: .01em;
    }
    .maint-banner-sep {
      width: 1px; height: 14px;
      background: rgba(255,255,255,.25); flex-shrink: 0;
    }
    .maint-banner-time {
      font-size: 0.72rem; color: rgba(255,255,255,.75); font-weight: 500;
    }
    .maint-banner-countdown {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.15);
      border-radius: 20px; padding: 3px 12px;
      font-family: 'DM Mono', monospace;
      font-size: 0.78rem; font-weight: 800;
      color: #fde68a; letter-spacing: .06em;
    }
    .maint-banner-dismiss {
      position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
      width: 26px; height: 26px; border-radius: 7px;
      background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.15);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.6);
      transition: background .15s, color .15s;
    }
    .maint-banner-dismiss:hover { background: rgba(0,0,0,.35); color: #fff; }

    /* ── KPI STRIP ── */
    .kpi-strip {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 12px;
      margin-bottom: 14px;
    }
    .kpi-card {
      background: var(--card); border-radius: 14px;
      border: 1px solid var(--line);
      padding: 14px 16px;
      display: flex; align-items: center; gap: 12px;
      box-shadow: 0 1px 6px rgba(15,23,42,.05);
      transition: transform .18s, box-shadow .18s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(15,23,42,.09); }
    .kpi-icon {
      width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
    }
    .kpi-card.c1 .kpi-icon { background: rgba(165,44,48,.1); color: var(--maroon); }
    .kpi-card.c2 .kpi-icon { background: rgba(245,158,11,.1); color: #d97706; }
    .kpi-card.c3 .kpi-icon { background: rgba(16,185,129,.1); color: #059669; }
    .kpi-card.c4 .kpi-icon { background: rgba(59,130,246,.1); color: #2563eb; }
    .kpi-body { flex: 1; min-width: 0; }
    .kpi-val { font-size: 1.5rem; font-weight: 800; letter-spacing: -.5px; line-height: 1; }
    .kpi-card.c1 .kpi-val { color: var(--maroon); }
    .kpi-card.c2 .kpi-val { color: #d97706; }
    .kpi-card.c3 .kpi-val { color: #059669; }
    .kpi-card.c4 .kpi-val { color: #2563eb; }
    .kpi-label {
      font-size: clamp(0.6rem, 0.12vw + 0.56rem, 0.67rem);
      font-weight: 700;
      color: var(--muted);
      letter-spacing: .04em;
      text-transform: uppercase;
      margin-top: 3px;
      line-height: 1.3;
      overflow-wrap: break-word;
    }
    .kpi-sub { font-size: 0.62rem; color: #94a3b8; margin-top: 1px; }

    /* ── MAIN GRID ── */
    .main-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 268px);
      gap: 12px;
      align-items: stretch;
    }

    /* ── IP FILINGS OVERVIEW CARD ── */
    .ifo-card {
      background: linear-gradient(145deg, var(--maroon2) 0%, #9B2226 50%, var(--maroon) 100%);
      border-radius: 16px; padding: 0;
      box-shadow: 0 6px 24px rgba(165,44,48,.26);
      position: relative; overflow: hidden; margin-bottom: 10px;
    }
    .ifo-card::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 160px; height: 160px; border-radius: 50%;
      background: rgba(255,255,255,.04); pointer-events: none;
    }
    .ifo-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px 8px; position: relative; z-index: 2; }
    .ifo-title { font-size: 0.82rem; font-weight: 800; color: #fff; letter-spacing: -.1px; }
    .ifo-sub { font-size: 0.57rem; color: rgba(255,255,255,.45); margin-top: 2px; font-family: 'DM Mono', monospace; letter-spacing: .06em; }
    .ifo-gt-num { font-size: 1.45rem; font-weight: 800; color: var(--gold); line-height: 1; letter-spacing: -.5px; }
    .ifo-gt-label { font-size: 0.5rem; font-family: 'DM Mono', monospace; letter-spacing: .18em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-top: 2px; text-align: right; }
    .ifo-legend { display: flex; gap: 5px; flex-wrap: wrap; padding: 0 18px 7px; position: relative; z-index: 2; }
    .ifo-pill { display: flex; align-items: center; gap: 4px; font-size: 0.56rem; font-weight: 700; letter-spacing: .02em; padding: 2px 7px; border-radius: 20px; border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.75); background: rgba(255,255,255,.07); }
    .ifo-pill-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
    .ifo-col-heads { display: grid; grid-template-columns: 40px 1fr 38px 38px 38px 38px 38px 42px; padding: 0 18px 3px; position: relative; z-index: 2; }
    .ifo-ch { font-size: 0.47rem; font-family: 'DM Mono', monospace; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.3); text-align: center; }
    .ifo-ch:first-child { text-align: left; }
    .ifo-ch:last-child { text-align: right; }
    .ifo-rows-wrap { max-height: 174px; overflow-y: auto; padding: 0 18px 4px; position: relative; z-index: 2; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.1) transparent; }
    .ifo-rows-wrap::-webkit-scrollbar { width: 2px; }
    .ifo-rows-wrap::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 99px; }
    .ifo-row { display: grid; grid-template-columns: 40px 1fr 38px 38px 38px 38px 38px 42px; align-items: center; padding: 4px 0; border-bottom: 1px solid rgba(255,255,255,.04); transition: background .15s; }
    .ifo-row:last-child { border-bottom: none; }
    .ifo-row:hover { background: rgba(255,255,255,.05); padding-left: 4px; padding-right: 4px; margin: 0 -4px; }
    .ifo-row.peak { background: rgba(240,200,96,.09); border: 1px solid rgba(240,200,96,.18); border-radius: 8px; padding: 4px 6px; margin: 1px -6px; }
    .ifo-year { font-size: 0.65rem; font-weight: 800; color: rgba(255,255,255,.8); font-family: 'DM Mono', monospace; }
    .ifo-row.peak .ifo-year { color: var(--gold); }
    .ifo-bar-wrap { display: flex; height: 4px; border-radius: 99px; overflow: hidden; background: rgba(255,255,255,.07); margin: 0 6px; gap: 1px; }
    .ifo-seg { height: 100%; border-radius: 99px; transition: width 1.1s cubic-bezier(.4,0,.2,1); }
    .ifo-chip { font-size: 0.6rem; font-weight: 700; text-align: center; color: rgba(255,255,255,.55); }
    .ifo-row.peak .ifo-chip { color: rgba(255,255,255,.85); }
    .ifo-row-total { font-size: 0.68rem; font-weight: 800; text-align: right; color: rgba(255,255,255,.85); }
    .ifo-row.peak .ifo-row-total { color: var(--gold); }
    .ifo-footer { display: grid; grid-template-columns: 40px 1fr 38px 38px 38px 38px 38px 42px; align-items: center; padding: 7px 18px 12px; border-top: 1px solid rgba(255,255,255,.08); position: relative; z-index: 2; }
    .ifo-footer-label { font-size: 0.55rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: var(--gold); font-family: 'DM Mono', monospace; }
    .ifo-footer-chip { font-size: 0.68rem; font-weight: 800; text-align: center; color: var(--gold); }
    .ifo-footer-total { font-size: 0.82rem; font-weight: 800; text-align: right; color: var(--gold); }
    .c-patent  { background: #F0C860; }
    .c-utility { background: #60C8F0; }
    .c-design  { background: #A78BFA; }
    .c-tm      { background: #34D399; }
    .c-copyright { background: #F97316; }
    .ifo-yr-btn { padding: 3px 8px; border-radius: 6px; font-size: 0.57rem; font-weight: 700; color: rgba(255,255,255,.5); cursor: pointer; border: none; background: none; font-family: inherit; transition: background .15s, color .15s; letter-spacing: .03em; white-space: nowrap; }
    .ifo-yr-btn.active { background: rgba(255,255,255,.18); color: #fff; }
    .ifo-yr-btn:hover:not(.active) { background: rgba(255,255,255,.09); color: rgba(255,255,255,.8); }

    /* ── RIGHT COLUMN ── */
    .right-col { display: flex; flex-direction: column; gap: 10px; }

    /* ── QUICK STAT PAIR ── */
    .qs-pair { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .qs-card { border-radius: 13px; padding: 13px 15px; border: 1px solid transparent; transition: transform .18s; }
    .qs-card:hover { transform: translateY(-2px); }
    .qs-card.maroon-card { background: linear-gradient(135deg, var(--maroon2), var(--maroon)); box-shadow: 0 4px 16px rgba(165,44,48,.24); }
    .qs-card.gold-card { background: linear-gradient(135deg, #d4900e, #F0C860); box-shadow: 0 4px 16px rgba(240,168,32,.22); }
    .qs-label { font-size: 0.58rem; font-weight: 700; color: rgba(255,255,255,.65); letter-spacing: .07em; text-transform: uppercase; margin-bottom: 5px; }
    .qs-val { font-size: 1.45rem; font-weight: 800; color: #fff; line-height: 1; }
    .qs-desc { font-size: 0.6rem; color: rgba(255,255,255,.55); margin-top: 3px; }

    /* ── BOTTOM GRID ── */
    .bottom-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 10px;
      margin-top: 10px;
    }
    .activity-card { background: var(--card); border-radius: 13px; padding: 14px 16px; border: 1px solid var(--line); box-shadow: 0 1px 5px rgba(15,23,42,.04); transition: transform .18s, box-shadow .18s; }
    .activity-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(15,23,42,.09); }
    .ac-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
    .ac-icon { width: 32px; height: 32px; border-radius: 9px; background: linear-gradient(135deg, var(--maroon2), var(--maroon)); display: flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(165,44,48,.22); }
    .ac-icon svg { width: 15px; height: 15px; color: #fff; }
    .ac-menu { color: #94a3b8; cursor: pointer; display: flex; gap: 2px; }
    .ac-dot { width: 3px; height: 3px; border-radius: 50%; background: #94a3b8; }
    .ac-title {
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 800;
      color: var(--ink);
      overflow-wrap: break-word;
    }
    .ac-meta { font-size: 0.63rem; color: var(--muted); margin-top: 2px; }
    .progress-row { display: flex; align-items: center; justify-content: space-between; margin: 9px 0 4px; }
    .progress-label { font-size: 0.6rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
    .progress-pct { font-size: 0.65rem; font-weight: 800; color: var(--maroon); }
    .progress-track { height: 4px; background: #edf0f5; border-radius: 999px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--maroon), var(--gold)); transition: width 1.2s cubic-bezier(.4,0,.2,1); }
    .ac-target { font-size: 0.6rem; color: #94a3b8; margin-top: 5px; }

    /* ── STATUS BREAKDOWN + CAMPUS ── */
    .panel-card { background: var(--card); border-radius: 13px; padding: 13px 15px; border: 1px solid var(--line); box-shadow: 0 1px 5px rgba(15,23,42,.04); }
    .panel-title { font-size: 0.72rem; font-weight: 800; color: var(--ink); margin-bottom: 10px; letter-spacing: .01em; }
    .status-row { display: flex; align-items: center; gap: 8px; padding: 5px 0; border-bottom: 1px solid var(--line); }
    .status-row:last-child { border-bottom: none; }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .status-name { font-size: 0.7rem; font-weight: 600; color: var(--ink); flex: 1; }
    .status-count { font-size: 0.7rem; font-weight: 800; color: var(--maroon); }
    .status-bar-wrap { width: 44px; height: 3px; background: #edf0f5; border-radius: 999px; overflow: hidden; }
    .status-bar-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--maroon), var(--gold)); }

    /* ── CAMPUS PILLS ── */
    .campus-wrap { display: flex; flex-direction: column; gap: 5px; }
    .campus-row { display: flex; align-items: center; justify-content: space-between; background: var(--bg); border-radius: 9px; padding: 7px 11px; }
    .campus-name { font-size: 0.7rem; font-weight: 600; color: var(--ink); }
    .campus-badge { font-size: 0.6rem; font-weight: 800; color: var(--maroon); background: var(--maroon-light); padding: 2px 8px; border-radius: 20px; }

    /* ════════════════════════════════════════
       HOW TO USE MODAL
    ════════════════════════════════════════ */
    .howto-overlay {
      position: fixed; inset: 0; z-index: 200;
      background: rgba(15,23,42,.6); backdrop-filter: blur(8px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .howto-overlay.open { display: flex; }
    .howto-box {
      background: var(--card); border-radius: 24px;
      width: min(680px, 96vw); max-height: 88vh;
      display: flex; flex-direction: column;
      box-shadow: 0 32px 80px rgba(15,23,42,.22);
      animation: howtoIn .3s cubic-bezier(.17,.67,.35,1.05) forwards;
      overflow: hidden;
    }
    @keyframes howtoIn { from { opacity:0; transform:translateY(20px) scale(.97); } to { opacity:1; transform:none; } }
    .howto-head {
      background: linear-gradient(135deg, var(--maroon2) 0%, #A52C30 55%, #C1363A 100%);
      padding: 24px 28px 20px; position: relative; overflow: hidden; flex-shrink: 0;
    }
    .howto-head::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 180px; height: 180px; border-radius: 50%;
      background: rgba(255,255,255,.05); pointer-events: none;
    }
    .howto-head-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; position: relative; z-index: 1; }
    .howto-head-icon {
      width: 44px; height: 44px; border-radius: 14px; flex-shrink: 0;
      background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center; color: var(--gold);
    }
    .howto-head-text { flex: 1; }
    .howto-eyebrow {
      font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700;
      letter-spacing: .18em; text-transform: uppercase; color: var(--gold); opacity: .85; margin-bottom: 5px;
    }
    .howto-title { font-size: 1.2rem; font-weight: 800; color: #fff; letter-spacing: -.3px; }
    .howto-sub   { font-size: 0.75rem; color: rgba(255,255,255,.6); margin-top: 4px; font-weight: 500; }
    .howto-close {
      width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.75); transition: background .15s; position: relative; z-index: 1;
    }
    .howto-close:hover { background: rgba(255,255,255,.22); color: #fff; }
    .howto-body {
      flex: 1; overflow-y: auto; padding: 22px 28px;
      display: flex; flex-direction: column; gap: 12px;
    }
    .howto-body::-webkit-scrollbar { width: 4px; }
    .howto-body::-webkit-scrollbar-thumb { background: rgba(15,23,42,.1); border-radius: 99px; }
    .howto-step {
      display: flex; gap: 16px; align-items: flex-start;
      background: var(--bg); border-radius: 16px;
      border: 1.5px solid var(--line); padding: 16px 18px;
      transition: border-color .2s, box-shadow .2s;
    }
    .howto-step:hover { border-color: rgba(165,44,48,.2); box-shadow: 0 4px 16px rgba(165,44,48,.07); }
    .howto-step-num {
      width: 32px; height: 32px; border-radius: 10px; flex-shrink: 0;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      display: flex; align-items: center; justify-content: center;
      font-family: 'DM Mono', monospace; font-size: 0.72rem; font-weight: 800; color: var(--gold);
      box-shadow: 0 3px 10px rgba(165,44,48,.22);
    }
    .howto-step-body { flex: 1; min-width: 0; }
    .howto-step-title { font-size: 0.88rem; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
    .howto-step-desc  { font-size: 0.76rem; color: var(--muted); line-height: 1.6; font-weight: 500; overflow-wrap: break-word; }
    .howto-step-tag {
      display: inline-flex; align-items: center; gap: 4px; margin-top: 7px;
      font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700;
      color: var(--maroon); background: var(--maroon-light);
      border: 1px solid rgba(165,44,48,.18); padding: 2px 9px; border-radius: 20px;
      letter-spacing: .04em; text-transform: uppercase;
    }
    .howto-footer {
      padding: 16px clamp(16px, 3vw, 28px);
      border-top: 1px solid var(--line);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px 14px;
      background: linear-gradient(90deg, rgba(165,44,48,.03), rgba(240,200,96,.03));
    }
    .howto-footer-note {
      flex: 1 1 12rem;
      min-width: 0;
      overflow-wrap: break-word;
      font-size: 0.72rem;
      color: var(--muted);
      font-weight: 500;
    }
    .howto-footer-note strong { color: var(--maroon); }
    .howto-footer .btn-primary-sm {
      flex: 0 1 auto;
      margin-left: auto;
    }
    .cp-footer .modal-btns {
      margin-top: 0;
      flex-wrap: wrap;
      justify-content: flex-end;
      gap: 10px;
    }

    /* ════════════════════════════════════════
       TODAY'S TO-DO MODAL
    ════════════════════════════════════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center;
      padding: 16px;
    }
    .modal-overlay.open { display: flex; }

    /* Logout modal box (existing) */
    .modal-box {
      background: #fff; border-radius: 24px; padding: 32px;
      width: 380px; max-width: 94vw;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      animation: fadeSlideUp .3s forwards;
    }

    /* Todo modal box — wider */
    .todo-modal-box {
      background: var(--card); border-radius: 24px;
      width: 100%; max-width: 560px; max-height: 88vh;
      overflow: hidden; display: flex; flex-direction: column;
      box-shadow: 0 32px 80px rgba(15,23,42,.22);
      animation: todoModalIn .3s cubic-bezier(.17,.67,.35,1.05);
    }
    @keyframes todoModalIn {
      from { opacity: 0; transform: translateY(24px) scale(.97); }
      to   { opacity: 1; transform: none; }
    }

    /* Todo modal header */
    .todo-modal-head {
      background: linear-gradient(135deg, var(--maroon2) 0%, #9B2226 60%, var(--maroon) 100%);
      padding: 22px 24px 18px;
      position: relative; overflow: hidden; flex-shrink: 0;
    }
    .todo-modal-head::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 160px; height: 160px; border-radius: 50%;
      background: rgba(255,255,255,.05); pointer-events: none;
    }
    .todo-modal-head-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      position: relative; z-index: 2;
    }
    .todo-modal-eyebrow {
      font-family: 'DM Mono', monospace; font-size: .58rem; font-weight: 700;
      letter-spacing: .2em; text-transform: uppercase;
      color: rgba(255,255,255,.5); margin-bottom: 4px;
    }
    .todo-modal-title {
      font-size: 1.15rem; font-weight: 800; color: #fff; letter-spacing: -.2px;
    }
    .todo-modal-date {
      font-size: .72rem; color: rgba(255,255,255,.6); margin-top: 3px;
      font-family: 'DM Mono', monospace;
    }
    .todo-modal-close {
      width: 34px; height: 34px; border-radius: 10px;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.75); font-size: 1rem;
      transition: background .15s; flex-shrink: 0;
    }
    .todo-modal-close:hover { background: rgba(255,255,255,.22); }

    /* Stat pills inside header */
    .todo-modal-stats {
      display: flex; gap: 8px; margin-top: 14px;
      position: relative; z-index: 2;
    }
    .todo-stat-pill {
      display: flex; align-items: center; gap: 5px;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
      border-radius: 20px; padding: 4px 12px;
      font-size: .68rem; font-weight: 700; color: #fff;
    }
    .todo-stat-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
    .dot-pending  { background: #fbbf24; }
    .dot-done     { background: #34d399; }
    .dot-total    { background: rgba(255,255,255,.6); }

    /* Todo modal body — scrollable */
    .todo-modal-body {
      flex: 1; overflow-y: auto; padding: 18px 22px;
      display: flex; flex-direction: column; gap: 10px;
    }
    .todo-modal-body::-webkit-scrollbar { width: 4px; }
    .todo-modal-body::-webkit-scrollbar-thumb { background: rgba(15,23,42,.1); border-radius: 99px; }

    /* Individual task card */
    .todo-task-card {
      background: var(--bg); border-radius: 16px;
      border: 1.5px solid var(--line);
      padding: 14px 16px; display: flex; gap: 12px; align-items: flex-start;
      transition: border-color .2s, box-shadow .2s;
    }
    .todo-task-card:hover { border-color: rgba(165,44,48,.2); box-shadow: 0 4px 16px rgba(165,44,48,.07); }
    .todo-task-card.is-done { opacity: .55; filter: grayscale(.4); }

    /* Left color bar */
    .todo-task-bar {
      width: 4px; border-radius: 4px; flex-shrink: 0;
      align-self: stretch; min-height: 48px;
    }
    .bar-deadline    { background: #ef4444; }
    .bar-registration{ background: var(--maroon); }
    .bar-review      { background: #d97706; }
    .bar-submission  { background: #2563eb; }
    .bar-pending     { background: #7c3aed; }
    .bar-done        { background: #bcc4ce; }

    /* Task content */
    .todo-task-content { flex: 1; min-width: 0; }
    .todo-task-top { display: flex; align-items: center; gap: 7px; margin-bottom: 5px; flex-wrap: wrap; }

    /* Category pill */
    .todo-cat-pill {
      font-size: .58rem; font-weight: 700; padding: 2px 9px;
      border-radius: 20px; letter-spacing: .04em; text-transform: uppercase;
    }
    .pill-deadline    { background: #fef2f2; color: #ef4444; }
    .pill-registration{ background: #fff0f0; color: var(--maroon); }
    .pill-review      { background: #fffbeb; color: #d97706; }
    .pill-submission  { background: #eff6ff; color: #2563eb; }
    .pill-pending     { background: #f5f3ff; color: #7c3aed; }
    .pill-done        { background: #ecfdf5; color: #059669; }

    .todo-task-title {
      font-size: .88rem; font-weight: 700; color: var(--ink);
    }
    .todo-task-card.is-done .todo-task-title {
      text-decoration: line-through; color: var(--muted);
    }
    .todo-task-meta {
      font-size: .68rem; color: var(--muted); margin-top: 3px;
      display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
    }
    .todo-task-meta .meta-sep { opacity: .4; }

    /* Empty state */
    .todo-empty {
      text-align: center; padding: 52px 20px;
      display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .todo-empty-icon {
      width: 64px; height: 64px; border-radius: 20px;
      background: var(--maroon-light); display: flex; align-items: center; justify-content: center;
      color: var(--maroon);
    }
    .todo-empty-title { font-size: .9rem; font-weight: 800; color: var(--ink); }
    .todo-empty-sub   { font-size: .75rem; color: var(--muted); }

    /* Todo modal footer */
    .todo-modal-footer {
      padding: 14px clamp(14px, 3vw, 22px);
      border-top: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      flex-shrink: 0;
      background: var(--card);
    }
    .btn-outline-sm {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 9px 16px;
      border-radius: 10px;
      border: 1.5px solid var(--line);
      background: var(--card);
      font-family: inherit;
      font-size: .76rem;
      font-weight: 700;
      color: var(--muted);
      cursor: pointer;
      transition: all .18s;
      flex: 0 1 auto;
      max-width: 100%;
      box-sizing: border-box;
      white-space: nowrap;
    }
    .btn-outline-sm:hover { border-color: var(--maroon); color: var(--maroon); background: var(--maroon-light); }
    .btn-primary-sm {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 9px 18px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-size: .76rem;
      font-weight: 700;
      box-shadow: 0 4px 14px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s;
      text-decoration: none;
      flex: 0 1 auto;
      max-width: 100%;
      box-sizing: border-box;
      white-space: nowrap;
    }
    .btn-primary-sm:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(165,44,48,.35); }

    /* CREATE PROFILE MODAL */
    .create-profile-modal-box {
      background: var(--card); border-radius: 24px;
      width: min(720px, 96vw); max-height: 90vh;
      overflow: hidden; display: flex; flex-direction: column;
      box-shadow: 0 32px 80px rgba(15,23,42,.22);
      animation: todoModalIn .3s cubic-bezier(.17,.67,.35,1.05);
    }
    .create-profile-head {
      background: linear-gradient(135deg, var(--maroon2) 0%, #9B2226 60%, var(--maroon) 100%);
      padding: 22px 24px 18px;
      position: relative; overflow: hidden; flex-shrink: 0;
    }
    .create-profile-head::before {
      content: ''; position: absolute; top: -44px; right: -44px;
      width: 170px; height: 170px; border-radius: 50%;
      background: rgba(255,255,255,.06); pointer-events: none;
    }
    .create-profile-head-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      gap: 16px; position: relative; z-index: 2;
    }
    .create-profile-eyebrow {
      font-family: 'DM Mono', monospace; font-size: .58rem; font-weight: 700;
      letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,.55);
      margin-bottom: 4px;
    }
    .create-profile-title {
      font-size: 1.1rem; font-weight: 800; color: #fff; letter-spacing: -.2px;
    }
    .create-profile-sub {
      font-size: .74rem; color: rgba(255,255,255,.68); margin-top: 3px; line-height: 1.5;
    }
    .create-profile-body {
      padding: 22px 24px 24px;
      display: grid; grid-template-columns: 240px 1fr; gap: 18px;
      overflow-y: auto;
    }
    .create-profile-panel {
      background: linear-gradient(180deg, rgba(165,44,48,.04), rgba(240,200,96,.04));
      border: 1px solid var(--line); border-radius: 20px;
      padding: 18px;
    }
    .create-profile-preview {
      display: flex; flex-direction: column; align-items: center; text-align: center;
    }
    .create-profile-avatar {
      width: 104px; height: 104px; border-radius: 28px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b; display: flex; align-items: center; justify-content: center;
      font-size: 2rem; font-weight: 800; letter-spacing: -.04em;
      box-shadow: 0 14px 36px rgba(165,44,48,.2);
      border: 4px solid rgba(255,255,255,.7);
      overflow: hidden; margin-bottom: 14px;
    }
    .create-profile-avatar img {
      width: 100%; height: 100%; object-fit: cover; display: none;
    }
    .create-profile-preview-name {
      font-size: .92rem; font-weight: 800; color: var(--ink);
    }
    .create-profile-preview-sub {
      font-size: .72rem; color: var(--muted); margin-top: 4px; line-height: 1.5;
    }
    .cp-dropzone {
      margin-top: 16px; border: 2px dashed var(--line); border-radius: 16px;
      padding: 18px 14px; text-align: center; cursor: pointer;
      transition: border-color .18s, background .18s, transform .18s;
      background: rgba(255,255,255,.58);
    }
    .cp-dropzone:hover, .cp-dropzone.is-active {
      border-color: var(--maroon); background: var(--maroon-light);
      transform: translateY(-1px);
    }
    .cp-dropzone-icon {
      width: 42px; height: 42px; border-radius: 13px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 10px;
    }
    .cp-dropzone-title {
      font-size: .8rem; font-weight: 800; color: var(--ink); margin-bottom: 3px;
    }
    .cp-dropzone-sub {
      font-size: .69rem; color: var(--muted); line-height: 1.5;
    }
    .cp-form {
      display: flex; flex-direction: column; gap: 14px;
    }
    .cp-field {
      display: flex; flex-direction: column; gap: 6px;
    }
    .cp-label {
      font-family: 'DM Mono', monospace; font-size: .58rem; font-weight: 700;
      letter-spacing: .14em; text-transform: uppercase; color: var(--muted);
    }
    .cp-input-wrap { position: relative; }
    .cp-input {
      width: 100%; border: 1.5px solid var(--line); background: var(--bg);
      border-radius: 14px; padding: 13px 15px;
      font-family: inherit; font-size: .86rem; font-weight: 600; color: var(--ink);
      outline: none; transition: border-color .18s, box-shadow .18s, background .18s;
    }
    .cp-input.cp-input-password { padding-right: 48px; }
    .cp-input:focus {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light); background: #fff;
    }
    .cp-input::placeholder { color: #94a3b8; font-weight: 500; }
    .cp-eye {
      position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
      width: 24px; height: 24px; border: none; background: none; cursor: pointer;
      color: var(--muted); display: flex; align-items: center; justify-content: center;
      padding: 0;
    }
    .cp-eye:hover { color: var(--maroon); }
    .cp-note {
      font-size: .72rem; color: var(--muted); line-height: 1.6;
      background: linear-gradient(90deg, rgba(165,44,48,.04), rgba(240,200,96,.05));
      border: 1px solid rgba(165,44,48,.08); border-radius: 14px; padding: 12px 14px;
    }
    .cp-footer {
      padding: 16px clamp(14px, 3vw, 24px);
      border-top: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px 12px;
      background: linear-gradient(90deg, rgba(165,44,48,.03), rgba(240,200,96,.03));
      flex-shrink: 0;
    }
    .cp-footer-text {
      font-size: .72rem;
      color: var(--muted);
      line-height: 1.5;
      flex: 1 1 12rem;
      min-width: 0;
      overflow-wrap: break-word;
    }
    .cp-status {
      display: none; align-items: center; gap: 8px;
      margin-top: 12px; border-radius: 12px; padding: 11px 13px;
      font-size: .76rem; font-weight: 600;
    }
    .cp-status.show { display: flex; }
    .cp-status.error { background: rgba(165,44,48,.08); color: var(--maroon); border: 1px solid rgba(165,44,48,.18); }
    .cp-status.success { background: rgba(16,185,129,.08); color: #059669; border: 1px solid rgba(16,185,129,.18); }

    /* ════════════════════════════════════════
       NOTIFICATION DROPDOWN
    ════════════════════════════════════════ */
    .notif-dropdown {
      position: absolute; top: calc(100% + 10px); right: 0;
      width: 320px; background: var(--card);
      border-radius: 18px; border: 1px solid var(--line);
      box-shadow: 0 20px 60px rgba(15,23,42,.16);
      overflow: hidden; z-index: 200;
      display: none;
      animation: dropIn .22s cubic-bezier(.17,.67,.35,1.05);
    }
    .notif-dropdown.open { display: block; }
    @keyframes dropIn {
      from { opacity: 0; transform: translateY(-8px) scale(.97); }
      to   { opacity: 1; transform: none; }
    }
    .notif-drop-head {
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      padding: 14px 16px;
      display: flex; align-items: center; justify-content: space-between;
    }
    .notif-drop-title { font-size: .82rem; font-weight: 800; color: #fff; }
    .notif-drop-sub   { font-size: .62rem; color: rgba(255,255,255,.55); margin-top: 2px; }
    .notif-drop-badge {
      font-family: 'DM Mono', monospace; font-size: .65rem; font-weight: 700;
      background: rgba(240,200,96,.2); border: 1px solid rgba(240,200,96,.35);
      color: var(--gold); padding: 3px 9px; border-radius: 20px;
    }
    .notif-drop-list { max-height: 260px; overflow-y: auto; }
    .notif-drop-list::-webkit-scrollbar { width: 3px; }
    .notif-drop-list::-webkit-scrollbar-thumb { background: rgba(15,23,42,.1); border-radius: 99px; }
    .notif-drop-item {
      display: flex; align-items: center; gap: 10px;
      padding: 11px 16px; border-bottom: 1px solid var(--line);
      cursor: pointer; transition: background .14s;
    }
    .notif-drop-item:last-child { border-bottom: none; }
    .notif-drop-item:hover { background: rgba(165,44,48,.04); }
    .notif-drop-item.is-done { opacity: .5; }
    .notif-item-bar {
      width: 3px; height: 36px; border-radius: 3px; flex-shrink: 0;
    }
    .notif-item-title {
      font-size: .78rem; font-weight: 700; color: var(--ink); flex: 1;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .notif-drop-item.is-done .notif-item-title { text-decoration: line-through; color: var(--muted); }
    .notif-item-pill {
      font-size: .56rem; font-weight: 700; padding: 2px 8px;
      border-radius: 20px; flex-shrink: 0;
    }
    .notif-drop-footer {
      padding: 10px 16px; border-top: 1px solid var(--line);
      display: flex; justify-content: center;
    }
    .notif-view-all {
      font-size: .72rem; font-weight: 700; color: var(--maroon);
      cursor: pointer; background: none; border: none;
      font-family: inherit; display: flex; align-items: center; gap: 5px;
      transition: opacity .15s;
    }
    .notif-view-all:hover { opacity: .75; }
    .notif-drop-empty {
      padding: 28px 16px; text-align: center;
      font-size: .76rem; color: var(--muted);
    }

    /* ════════════════════════════════════════
       TOAST NUDGE
    ════════════════════════════════════════ */
    .todo-toast {
      position: fixed; bottom: 28px; right: 28px; z-index: 9999;
      background: var(--card); border-radius: 16px;
      border: 1px solid var(--line);
      box-shadow: 0 16px 48px rgba(15,23,42,.16);
      padding: 14px 18px; min-width: 300px; max-width: 360px;
      display: flex; align-items: center; gap: 12px;
      animation: toastSlideUp .4s cubic-bezier(.17,.67,.35,1.05);
      border-left: 4px solid var(--maroon);
    }
    .todo-toast.hiding {
      animation: toastSlideDown .3s ease-out forwards;
    }
    @keyframes toastSlideUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: none; }
    }
    @keyframes toastSlideDown {
      from { opacity: 1; transform: none; }
      to   { opacity: 0; transform: translateY(20px); }
    }
    .toast-icon {
      width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
      background: var(--maroon-light); display: flex; align-items: center; justify-content: center;
      color: var(--maroon);
    }
    .toast-content { flex: 1; min-width: 0; }
    .toast-title { font-size: .8rem; font-weight: 800; color: var(--ink); }
    .toast-sub   { font-size: .7rem; color: var(--muted); margin-top: 2px; }
    .toast-view-btn {
      font-size: .7rem; font-weight: 700; color: var(--maroon);
      background: var(--maroon-light); border: none; cursor: pointer;
      font-family: inherit; padding: 5px 12px; border-radius: 8px;
      white-space: nowrap; transition: opacity .15s;
    }
    .toast-view-btn:hover { opacity: .8; }
    .toast-close-btn {
      width: 24px; height: 24px; border-radius: 6px; border: none;
      background: var(--bg); cursor: pointer; font-size: .75rem;
      color: var(--muted); display: flex; align-items: center; justify-content: center;
      flex-shrink: 0; transition: background .15s;
    }
    .toast-close-btn:hover { background: var(--line); }

    /* ANIMATIONS */
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(16px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .anim { opacity: 0; animation: fadeSlideUp .5s forwards; }
    .anim-1 { animation-delay: .05s; }
    .anim-2 { animation-delay: .12s; }
    .anim-3 { animation-delay: .19s; }
    .anim-4 { animation-delay: .26s; }
    .anim-5 { animation-delay: .33s; }
    .anim-6 { animation-delay: .40s; }
    .anim-7 { animation-delay: .47s; }

    /* LOGOUT MODAL */
    .modal-icon { width: 52px; height: 52px; border-radius: 16px; background: rgba(165,44,48,.1); color: var(--maroon); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: var(--ink); }
    .modal-desc { font-size: 0.82rem; color: var(--muted); margin-top: 6px; line-height: 1.6; }
    .modal-btns {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 24px;
      justify-content: center;
    }
    .btn-cancel {
      flex: 0 1 auto;
      min-width: min(140px, 46%);
      max-width: 220px;
      padding: 12px clamp(14px, 3vw, 20px);
      border-radius: 12px;
      border: 1.5px solid var(--line);
      background: none;
      font-family: inherit;
      font-size: 0.82rem;
      font-weight: 700;
      cursor: pointer;
      color: var(--muted);
      transition: background .18s;
      box-sizing: border-box;
    }
    .btn-cancel:hover { background: var(--bg); }
    .btn-confirm {
      flex: 0 1 auto;
      min-width: min(140px, 46%);
      max-width: 220px;
      padding: 12px clamp(14px, 3vw, 20px);
      border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      border: none;
      font-family: inherit;
      font-size: 0.82rem;
      font-weight: 700;
      cursor: pointer;
      color: #fff;
      box-shadow: 0 6px 18px rgba(165,44,48,.25);
      transition: opacity .18s;
      box-sizing: border-box;
    }
    .btn-confirm:hover { opacity: .88; }

    /* SCROLLBAR */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 999px; }

    /* ════════════════════════════════════════
       STATUS DETAIL MODAL
    ════════════════════════════════════════ */
    .status-row {
      cursor: pointer;
      border-radius: 8px;
      transition: background .15s, padding .15s;
      padding: 5px 6px;
      margin: 0 -6px;
    }
    .status-row:hover { background: rgba(165,44,48,.06); }
    .status-row:last-child { border-bottom: none; }

    .status-modal-overlay {
      position: fixed; inset: 0; z-index: 300;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .status-modal-overlay.open { display: flex; }

    .status-modal-box {
      background: var(--card); border-radius: 24px;
      width: min(660px, 96vw); max-height: 88vh;
      display: flex; flex-direction: column;
      box-shadow: 0 32px 80px rgba(15,23,42,.22);
      animation: statusModalIn .28s cubic-bezier(.17,.67,.35,1.05);
      overflow: hidden;
    }
    @keyframes statusModalIn {
      from { opacity: 0; transform: translateY(20px) scale(.97); }
      to   { opacity: 1; transform: none; }
    }

    .status-modal-head {
      background: linear-gradient(135deg, var(--maroon2) 0%, #9B2226 60%, var(--maroon) 100%);
      padding: 22px 24px 0; position: relative; overflow: hidden; flex-shrink: 0;
    }
    .status-modal-head::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 160px; height: 160px; border-radius: 50%;
      background: rgba(255,255,255,.05); pointer-events: none;
    }
    .status-modal-head-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      position: relative; z-index: 2; margin-bottom: 16px;
    }
    .status-modal-eyebrow {
      font-family: 'DM Mono', monospace; font-size: .58rem; font-weight: 700;
      letter-spacing: .2em; text-transform: uppercase;
      color: rgba(255,255,255,.5); margin-bottom: 4px;
    }
    .status-modal-title { font-size: 1.1rem; font-weight: 800; color: #fff; letter-spacing: -.2px; }
    .status-modal-sub   { font-size: .72rem; color: rgba(255,255,255,.55); margin-top: 3px; }
    .status-modal-close {
      width: 34px; height: 34px; border-radius: 10px;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.75); font-size: 1rem;
      transition: background .15s; flex-shrink: 0;
    }
    .status-modal-close:hover { background: rgba(255,255,255,.22); }

    /* ── TABS ── */
    .status-modal-tabs {
      display: flex; gap: 4px; position: relative; z-index: 2;
      padding: 0 24px;
    }
    .smt-tab {
      padding: 9px 16px; border-radius: 10px 10px 0 0;
      font-size: .72rem; font-weight: 700; cursor: pointer;
      color: rgba(255,255,255,.5); background: transparent;
      border: none; font-family: inherit;
      transition: background .15s, color .15s;
      display: flex; align-items: center; gap: 6px;
    }
    .smt-tab:hover { color: rgba(255,255,255,.85); background: rgba(255,255,255,.08); }
    .smt-tab.active { background: var(--card); color: var(--maroon); }
    .smt-tab-badge {
      font-size: .6rem; font-weight: 800; padding: 1px 7px;
      border-radius: 20px; background: rgba(255,255,255,.18); color: #fff;
    }
    .smt-tab.active .smt-tab-badge { background: var(--maroon-light); color: var(--maroon); }

    /* ── BODY ── */
    .status-modal-body {
      flex: 1; overflow-y: auto; padding: 18px 22px;
      display: flex; flex-direction: column; gap: 10px;
    }
    .status-modal-body::-webkit-scrollbar { width: 4px; }
    .status-modal-body::-webkit-scrollbar-thumb { background: rgba(15,23,42,.1); border-radius: 99px; }

    .smt-panel { display: none; flex-direction: column; gap: 10px; }
    .smt-panel.active { display: flex; }

    /* ── RECORD CARD ── */
    .sr-card {
      background: var(--bg); border-radius: 14px;
      border: 1.5px solid var(--line); padding: 14px 16px;
      transition: border-color .2s, box-shadow .2s;
    }
    .sr-card:hover { border-color: rgba(165,44,48,.22); box-shadow: 0 4px 16px rgba(165,44,48,.08); }

    .sr-card-top {
      display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 10px;
    }
    .sr-card-title {
      font-size: .88rem;
      font-weight: 800;
      color: var(--ink);
      line-height: 1.3;
      flex: 1;
      min-width: 0;
      overflow-wrap: break-word;
    }
    .sr-card-id {
      font-family: 'DM Mono', monospace; font-size: .6rem; font-weight: 700;
      color: var(--maroon); background: var(--maroon-light);
      padding: 3px 9px; border-radius: 20px; white-space: nowrap; flex-shrink: 0;
    }
    .sr-card-grid {
      display: grid; grid-template-columns: 1fr 1fr; gap: 6px 14px;
    }
    .sr-field { display: flex; flex-direction: column; gap: 1px; }
    .sr-field-label {
      font-size: .56rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: .07em; color: #94a3b8;
    }
    .sr-field-val {
      font-size: .75rem; font-weight: 600; color: var(--ink);
    }
    .sr-field-val.muted { color: var(--muted); font-style: italic; }
    .sr-card-footer {
      margin-top: 10px; padding-top: 10px;
      border-top: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between; gap: 8px;
    }
    .sr-remarks {
      font-size: .7rem; color: var(--muted); flex: 1;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sr-gdrive-btn {
      display: inline-flex; align-items: center; gap: 5px;
      font-size: .68rem; font-weight: 700; color: var(--maroon);
      background: var(--maroon-light); border: none; cursor: pointer;
      font-family: inherit; padding: 5px 12px; border-radius: 8px;
      text-decoration: none; white-space: nowrap; transition: opacity .15s; flex-shrink: 0;
    }
    .sr-gdrive-btn:hover { opacity: .8; }

    .sr-view-btn {
      display: inline-flex; align-items: center; gap: 5px;
      font-size: .68rem; font-weight: 700; color: #fff;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      border: none; cursor: pointer; font-family: inherit;
      padding: 5px 12px; border-radius: 8px;
      text-decoration: none; white-space: nowrap; flex-shrink: 0;
      box-shadow: 0 3px 10px rgba(165,44,48,.22);
      transition: transform .15s, box-shadow .15s;
    }
    .sr-view-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(165,44,48,.32); }

    .sr-empty {
      text-align: center; padding: 40px 20px;
      display: flex; flex-direction: column; align-items: center; gap: 8px;
    }
    .sr-empty-icon {
      width: 52px; height: 52px; border-radius: 16px;
      background: var(--maroon-light); display: flex; align-items: center; justify-content: center;
      color: var(--maroon);
    }
    .sr-empty-title { font-size: .88rem; font-weight: 800; color: var(--ink); }
    .sr-empty-sub   { font-size: .74rem; color: var(--muted); }

    .status-modal-footer {
      padding: 14px 22px 18px; border-top: 1px solid var(--line);
      display: flex; justify-content: flex-end; flex-shrink: 0;
    }

    /* ════════════════════════════════════════
       HAMBURGER BUTTON (mobile only)
    ════════════════════════════════════════ */
    .hamburger-btn {
      display: none;
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg); border: 1.5px solid var(--line);
      align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted);
      transition: all .18s; flex-shrink: 0;
      -webkit-tap-highlight-color: transparent;
    }
    .hamburger-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    /* Mobile sidebar overlay backdrop */
    .sidebar-backdrop {
      display: none;
      position: fixed; inset: 0; z-index: 49;
      background: rgba(15,23,42,.45);
      backdrop-filter: blur(3px);
      -webkit-tap-highlight-color: transparent;
    }
    .sidebar-backdrop.open { display: block; }

    /* ════════════════════════════════════════
       RESPONSIVE — 1280px (large tablet / small laptop)
    ════════════════════════════════════════ */
    @media (max-width: 1280px) {
      .main-grid { grid-template-columns: minmax(0, 1fr) minmax(0, 240px); }
      .user-chip-name { max-width: 100px; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 1024px (tablet landscape)
    ════════════════════════════════════════ */
    @media (max-width: 1024px) {
      .main-grid { grid-template-columns: 1fr; }
      .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .create-profile-body { grid-template-columns: 1fr; }
      .bottom-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .right-col { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
      .qs-pair { grid-column: 1 / -1; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 768px (tablet portrait)
    ════════════════════════════════════════ */
    @media (max-width: 768px) {
      /* Sidebar becomes a slide-in drawer */
      .sidebar {
        transform: translateX(-100%);
        transition: transform .28s cubic-bezier(.4,0,.2,1);
        z-index: 50;
        width: 220px;
        align-items: flex-start;
        padding: 20px 12px;
      }
      .sidebar.mobile-open { transform: translateX(0); }
      .sidebar.mobile-open .sidebar-mobile-user { display: block !important; }
      .sidebar.mobile-open > .nav-item:first-child { display: none; }
      .sidebar-logo { margin-left: 4px; }
      .sidebar-nav { width: 100%; align-items: flex-start; }
      .nav-item {
        width: 100%; border-radius: 12px;
        justify-content: flex-start;
        padding: 0 12px; gap: 12px;
      }
      .nav-tooltip {
        /* Show labels inline instead of tooltip on mobile drawer */
        position: static; opacity: 1 !important; transform: none;
        background: none; color: rgba(255,255,255,.8);
        font-size: 0.78rem; font-weight: 600;
        padding: 0; border-radius: 0; pointer-events: auto;
        letter-spacing: .01em; white-space: nowrap;
      }
      .sidebar-bottom { width: 100%; align-items: flex-start; }

      /* Main content takes full width */
      .main-wrap { margin-left: 0; }

      /* Show hamburger, adjust topbar */
      .hamburger-btn { display: flex; }
      .topbar { padding: 8px 16px; min-height: 60px; }
      .topbar-left { gap: 10px; }
      .topbar-search { display: none; }
      .page-title { font-size: 1rem; }
      .page-subtitle { display: none; }

      /* Content padding */
      .content { padding: 14px var(--pad-x); }

      /* KPI strip 2 columns */
      .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; margin-bottom: 12px; }

      /* Main grid single col */
      .main-grid { grid-template-columns: 1fr; }

      /* Right col full width 2-col grid */
      .right-col { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
      .qs-pair { grid-column: 1 / -1; }

      /* Bottom grid 2 col */
      .bottom-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }

      /* IFO card table — hide less important columns */
      .ifo-col-heads,
      .ifo-row,
      .ifo-footer {
        grid-template-columns: 40px 1fr 34px 34px 34px 34px 38px;
      }
      /* Hide Copyright column on tablet */
      .ifo-col-heads .ifo-ch:nth-child(6),
      .ifo-row .ifo-chip:nth-child(6),
      .ifo-footer .ifo-footer-chip:nth-child(6) { display: none; }

      /* Notification dropdown */
      .notif-dropdown { width: calc(100vw - 32px); right: -60px; max-width: 340px; }
      .notif-item-title { white-space: normal; line-height: 1.35; overflow-wrap: anywhere; }

      /* Toast */
      .todo-toast { min-width: unset; max-width: calc(100vw - 32px); bottom: 16px; right: 16px; }

      /* Modals full-width */
      .howto-box { width: 96vw; }
      .todo-modal-box { max-width: 96vw; }
      .create-profile-modal-box { width: 96vw; }
      .status-modal-box { width: 96vw; }
      .modal-box { width: 96vw; max-width: 400px; }

      /* User chip compact */
      .user-chip-name { max-width: 80px; }
      .btn-howto-label { display: none; }
      .btn-howto { padding: 9px 10px; }

      /* Topbar buttons — compact */
      .btn-primary { padding: 9px 14px; font-size: 0.76rem; }
      .btn-profile { padding: 8px 10px; font-size: 0.74rem; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 480px (large phone)
    ════════════════════════════════════════ */
    @media (max-width: 480px) {
      .topbar { padding: 8px 12px; min-height: 56px; }
      .topbar-right { gap: 7px; }
      .content { padding: 12px var(--pad-x); }

      /* KPI full width stacked */
      .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
      .kpi-card { padding: 11px 12px; gap: 9px; }
      .kpi-val { font-size: 1.25rem; }

      /* Right col stacks */
      .right-col { grid-template-columns: 1fr; }
      .qs-pair { grid-column: auto; }

      /* Bottom grid single col */
      .bottom-grid { grid-template-columns: minmax(0, 1fr); }

      /* IFO card — mobile simplified */
      .ifo-col-heads,
      .ifo-row,
      .ifo-footer {
        grid-template-columns: 38px 1fr 32px 32px 32px 38px;
      }
      /* Hide Design & TM columns on small phones */
      .ifo-col-heads .ifo-ch:nth-child(5),
      .ifo-col-heads .ifo-ch:nth-child(6),
      .ifo-row .ifo-chip:nth-child(5),
      .ifo-row .ifo-chip:nth-child(6),
      .ifo-footer .ifo-footer-chip:nth-child(4),
      .ifo-footer .ifo-footer-chip:nth-child(5) { display: none; }

      .ifo-header { flex-direction: column; align-items: flex-start; gap: 10px; }
      .ifo-header > div:last-child { width: 100%; max-width: 100%; align-self: stretch; }
      .ifo-header > div:last-child > div:last-child {
        width: 100%;
        max-width: 100%;
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 6px;
      }

      /* Modals */
      .howto-head { padding: 18px 18px 16px; }
      .howto-body { padding: 16px 16px; }
      .howto-footer { padding: 14px 16px; flex-direction: column; align-items: center; gap: 10px; text-align: center; }
      .howto-footer .btn-primary-sm { margin-left: 0; }
      .howto-step { padding: 13px 14px; gap: 12px; }
      .todo-modal-head { padding: 18px 18px 14px; }
      .todo-modal-body { padding: 14px 16px; }
      .todo-modal-footer { padding: 12px 16px; flex-wrap: wrap; gap: 8px; }
      .status-modal-head { padding: 18px 18px 0; }
      .status-modal-body { padding: 14px 14px; }
      .status-modal-footer { padding: 12px 16px; }
      .status-modal-tabs { padding: 0 16px; gap: 2px; }
      .smt-tab { padding: 8px 10px; font-size: .68rem; }
      .create-profile-head { padding: 18px 18px 16px; }
      .create-profile-body { padding: 14px 16px; gap: 14px; }
      .cp-footer { padding: 12px 16px; flex-direction: column; align-items: center; gap: 10px; text-align: center; }
      .cp-footer .modal-btns { justify-content: center; width: 100%; max-width: 22rem; }
      .modal-box { padding: 24px 20px; }

      /* Notif dropdown full width */
      .notif-dropdown { width: calc(100vw - 24px); right: -80px; }

      /* Hide "How to Use" text label, icon only */
      .btn-howto { padding: 9px 10px; }

      /* Hide "New Record" text, show icon only */
      .btn-primary-text { display: none; }

      /* User chip — hide name/role on very small screens */
      .user-chip-info { display: none; }
      .user-chip { padding: 5px; }

      /* footer wrap */
      footer { flex-direction: column; gap: 6px; align-items: flex-start !important; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 360px (small phone)
    ════════════════════════════════════════ */
    @media (max-width: 380px) {
      .modal-btns { flex-direction: column; align-items: stretch; }
      .modal-btns .btn-cancel,
      .modal-btns .btn-confirm { max-width: none; width: 100%; min-width: 0; }
    }

    @media (max-width: 360px) {
      .topbar { padding: 8px 10px; }
      .content { padding: 10px var(--pad-x); }
      .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 7px; }
      .kpi-card { padding: 10px; }
      .kpi-val { font-size: 1.1rem; }
      .topbar-right { gap: 5px; }
      .btn-primary { padding: 8px 10px; font-size: 0.72rem; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 1440px (desktop / large monitor)
    ════════════════════════════════════════ */
    @media (min-width: 1440px) {
      :root {
        --sidebar-w: 82px;
        --pad-x: 2rem;
        --shell-max: 1600px;
      }

      .sidebar { width: var(--sidebar-w); padding: 24px 0; }
      .sidebar-logo { width: 48px; height: 48px; font-size: 1.1rem; margin-bottom: 36px; }
      .nav-item { width: 54px; height: 54px; border-radius: 16px; }

      .topbar { min-height: 80px; padding: 12px var(--pad-x); }
      .page-title { font-size: 1.2rem; }
      .page-subtitle { font-size: 0.78rem; }
      .topbar-search { max-width: 300px; padding: 10px 18px; }
      .icon-btn { width: 44px; height: 44px; border-radius: 14px; }
      .avatar { width: 44px; height: 44px; border-radius: 14px; font-size: 0.9rem; }

      .content {
        padding: 22px var(--pad-x);
        background-attachment: fixed;
        background-position: center center;
        background-size: cover;
      }

      .kpi-strip { gap: 16px; margin-bottom: 18px; align-items: stretch; }
      .kpi-card { padding: 18px 20px; gap: 14px; border-radius: 16px; align-self: auto; }
      .kpi-icon { width: 42px; height: 42px; border-radius: 12px; }
      .kpi-val { font-size: 1.75rem; }
      .kpi-label { font-size: 0.7rem; }

      .main-grid {
        grid-template-columns: minmax(0, 1fr) minmax(0, 300px);
        gap: 16px;
        align-items: stretch;
      }

      .ifo-card { border-radius: 18px; margin-bottom: 14px; }
      .ifo-header { padding: 18px 22px 10px; }
      .ifo-title { font-size: 0.9rem; }
      .ifo-gt-num { font-size: 1.65rem; }
      .ifo-legend { padding: 0 22px 10px; }
      .ifo-col-heads,
      .ifo-row,
      .ifo-footer { grid-template-columns: 48px 1fr 44px 44px 44px 44px 44px 50px; }
      .ifo-col-heads { padding: 0 22px 5px; }
      .ifo-rows-wrap { max-height: 200px; padding: 0 22px 6px; }
      .ifo-footer { padding: 9px 22px 14px; }
      .ifo-year { font-size: 0.72rem; }
      .ifo-row-total { font-size: 0.75rem; }
      .ifo-footer-total { font-size: 0.9rem; }

      .right-col {
        display: flex;
        flex-direction: column;
        gap: 14px;
        height: 100%;
      }

      .right-col .panel-card:last-child { flex: 1; }

      .qs-card { padding: 16px 18px; border-radius: 15px; }
      .qs-val { font-size: 1.65rem; }
      .qs-label { font-size: 0.62rem; }

      .bottom-grid { gap: 14px; margin-top: 14px; }
      .activity-card { padding: 18px 20px; border-radius: 15px; }
      .ac-title { font-size: 0.85rem; }
      .ac-icon { width: 36px; height: 36px; border-radius: 11px; }

      .panel-card { padding: 16px 18px; border-radius: 15px; }
      .panel-title { font-size: 0.78rem; margin-bottom: 12px; }
      .status-name { font-size: 0.74rem; }
      .status-count { font-size: 0.74rem; }
      .campus-name { font-size: 0.74rem; }
    }

    /* ════════════════════════════════════════
       RESPONSIVE — 1920px (full HD / widescreen)
    ════════════════════════════════════════ */
    @media (min-width: 1920px) {
      :root {
        --sidebar-w: 90px;
        --pad-x: 2.5rem;
        --shell-max: 1800px;
      }

      .sidebar { width: var(--sidebar-w); padding: 28px 0; }
      .sidebar-logo { width: 52px; height: 52px; font-size: 1.15rem; margin-bottom: 40px; }
      .nav-item { width: 58px; height: 58px; border-radius: 18px; }

      .topbar { min-height: 88px; padding: 14px var(--pad-x); }
      .page-title { font-size: 1.3rem; }
      .page-subtitle { font-size: 0.82rem; }
      .topbar-search { max-width: 340px; padding: 11px 20px; font-size: 0.88rem; }
      .icon-btn { width: 48px; height: 48px; border-radius: 15px; }
      .avatar { width: 48px; height: 48px; border-radius: 15px; font-size: 0.95rem; }
      .user-chip-name { max-width: 160px; font-size: 0.82rem; }
      .user-chip-avatar { width: 36px; height: 36px; border-radius: 12px; }

      .content {
        padding: 28px var(--pad-x);
        background-attachment: fixed;
        background-position: center center;
        background-size: cover;
      }

      .kpi-strip { gap: 20px; margin-bottom: 22px; align-items: stretch; }
      .kpi-card { padding: 22px 24px; gap: 16px; border-radius: 18px; }
      .kpi-icon { width: 48px; height: 48px; border-radius: 14px; }
      .kpi-val { font-size: 2rem; }
      .kpi-label { font-size: 0.72rem; }
      .kpi-sub { font-size: 0.66rem; }

      .main-grid {
        grid-template-columns: minmax(0, 1fr) minmax(0, 340px);
        gap: 20px;
        align-items: stretch;
      }

      .right-col {
        display: flex;
        flex-direction: column;
        gap: 18px;
        height: 100%;
      }

      .right-col .panel-card:last-child { flex: 1; }

      .ifo-card { border-radius: 20px; margin-bottom: 18px; }
      .ifo-header { padding: 22px 26px 12px; }
      .ifo-title { font-size: 0.96rem; }
      .ifo-sub { font-size: 0.62rem; }
      .ifo-gt-num { font-size: 1.85rem; }
      .ifo-legend { padding: 0 26px 12px; gap: 7px; }
      .ifo-pill { font-size: 0.62rem; padding: 3px 9px; }
      .ifo-col-heads,
      .ifo-row,
      .ifo-footer { grid-template-columns: 54px 1fr 50px 50px 50px 50px 50px 58px; }
      .ifo-col-heads { padding: 0 26px 6px; }
      .ifo-rows-wrap { max-height: 220px; padding: 0 26px 8px; }
      .ifo-footer { padding: 10px 26px 16px; }
      .ifo-year { font-size: 0.76rem; }
      .ifo-chip { font-size: 0.66rem; }
      .ifo-row-total { font-size: 0.8rem; }
      .ifo-footer-chip { font-size: 0.76rem; }
      .ifo-footer-total { font-size: 0.96rem; }
      .ifo-bar-wrap { height: 5px; }
      .qs-card { padding: 20px 22px; border-radius: 17px; }
      .qs-val { font-size: 1.85rem; }
      .qs-label { font-size: 0.65rem; }
      .qs-desc { font-size: 0.65rem; }

      .bottom-grid { gap: 18px; margin-top: 18px; }
      .activity-card { padding: 22px 24px; border-radius: 17px; }
      .ac-title { font-size: 0.9rem; }
      .ac-meta { font-size: 0.68rem; }
      .ac-icon { width: 40px; height: 40px; border-radius: 13px; }
      .progress-label { font-size: 0.64rem; }
      .progress-pct { font-size: 0.7rem; }
      .ac-target { font-size: 0.64rem; }

      .panel-card { padding: 20px 22px; border-radius: 17px; }
      .panel-title { font-size: 0.84rem; margin-bottom: 14px; }
      .status-name { font-size: 0.78rem; }
      .status-count { font-size: 0.78rem; }
      .status-row { padding: 7px 0; }
      .campus-row { padding: 9px 13px; border-radius: 11px; }
      .campus-name { font-size: 0.78rem; }
      .campus-badge { font-size: 0.65rem; padding: 3px 10px; }

      .btn-primary { font-size: 0.86rem; padding: 12px 24px; border-radius: 14px; }
      .btn-howto { font-size: 0.84rem; padding: 11px 18px; border-radius: 14px; }
    }

    /* ════════════════════════════════════════
       MAINTENANCE BANNER — mobile adjustments
    ════════════════════════════════════════ */
    @media (max-width: 768px) {
      .maint-banner { padding: 8px 40px 8px 16px; }
      .maint-banner-body { gap: 6px; font-size: .7rem; }
      .maint-banner-sep { display: none; }
      .maint-banner-time { display: none; }
      body.has-maint-banner .sidebar { top: 42px; }
      body.has-maint-banner .main-wrap { padding-top: 42px; min-height: calc(100vh - 42px); }
    }

    /* ── USER CHIP ── */
    .user-chip {
      display: flex; align-items: center; gap: 9px;
      background: var(--bg); border: 1.5px solid var(--line);
      border-radius: 14px; padding: 5px 12px 5px 6px;
      transition: border-color .18s, box-shadow .18s;
    }
    .user-chip:hover {
      border-color: rgba(165,44,48,.25);
      box-shadow: 0 2px 10px rgba(165,44,48,.08);
    }
    .user-chip-avatar {
      width: 32px; height: 32px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.72rem; color: #fff;
      flex-shrink: 0; letter-spacing: 0;
      box-shadow: 0 3px 10px rgba(0,0,0,.18);
    }
    .user-chip-info { display: flex; flex-direction: column; gap: 1px; min-width: 0; }
    .user-chip-name {
      font-size: 0.75rem; font-weight: 700; color: var(--ink);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      max-width: 130px; line-height: 1.2;
    }
    .user-chip-role {
      font-family: 'DM Mono', monospace;
      font-size: 0.55rem; font-weight: 600;
      letter-spacing: .1em; text-transform: uppercase;
      line-height: 1;
    }
    .user-chip-role.role-admin     { color: var(--maroon); }
    .user-chip-role.role-developer { color: #d97706; }

    /* ── PROFILE BUTTON ── */
    .btn-profile {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      background: var(--bg);
      border: 1.5px solid var(--line);
      color: var(--muted);
      cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 14px);
      border-radius: 12px;
      transition: all .18s;
      max-width: min(240px, 100%);
      flex: 0 1 auto;
      box-sizing: border-box;
      text-align: center;
      line-height: 1.25;
      overflow-wrap: anywhere;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-profile:hover {
      background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon);
    }
  </style>
</head>

<body>

@if(!empty($scheduledAt))
<div class="maint-banner" id="maintBanner">
  <div class="maint-banner-icon">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
  </div>
  <div class="maint-banner-body">
    <span class="maint-banner-title">Scheduled Maintenance</span>
    <div class="maint-banner-sep"></div>
    <span class="maint-banner-time">
      System going down on {{ \Carbon\Carbon::parse($scheduledAt)->format('M d, Y \a\t g:i A') }}
    </span>
    <div class="maint-banner-sep"></div>
    <span class="maint-banner-countdown" id="maintCountdown">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="maintCountdownVal">--:--:--</span>
    </span>
  </div>
  <button class="maint-banner-dismiss" id="maintDismiss" title="Dismiss">✕</button>
</div>
<script>
  document.body.classList.add('has-maint-banner');
</script>
@endif

@php
  // Pull logged-in user from session (set during profile login)
  $sessionName  = session('user_name');
  $sessionRole  = session('user_role');
  $sessionColor = session('user_avatar_color', '#A52C30');
  $user = $user ?? (object)[
    'name'         => $sessionName  ?? 'KTTM Admin',
    'role'         => $sessionRole  ?? 'admin',
    'avatar_color' => $sessionColor,
  ];
  // Attach avatar_color if not already on the object
  if (!isset($user->avatar_color)) {
    $user->avatar_color = $sessionColor;
  }
  // Build initials from name
  $userInitials = collect(explode(' ', $user->name))
    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
    ->take(2)->implode('');
  $roleLabel = match(strtolower($user->role ?? '')) {
    'admin'     => 'Admin',
    'developer' => 'Developer',
    default     => ucfirst($user->role ?? 'Staff'),
  };
  $normalizedUserName = strtolower(trim(preg_replace('/\s+/', ' ', preg_replace('/[^a-z0-9\s]/i', '', $user->name ?? ''))));
  $nameTokens = collect(explode(' ', $normalizedUserName))
    ->filter(fn($token) => $token !== '' && $token !== 'dr' && strlen($token) > 1)
    ->values()
    ->all();
  $normalizedComparableName = implode(' ', $nameTokens);
  $showCreateProfileFirst = in_array($normalizedComparableName, [
    'leando dalhag',
    'leandro dalhag',
  ], true);
  $kpis       = $kpis       ?? ['my_open' => 0, 'needs_attention' => 0, 'due_soon' => 0, 'total_records' => 0];
  $allRecords = $allRecords ?? [];
  $recent     = $recent     ?? [];

  /* ── Today's Tasks (mock data — will be replaced by DB query later) ── */
  $todayTasks = $todayTasks ?? [
    [
      'title'    => 'Submit Patent Renewal — Garcia Invention',
      'category' => 'deadline',
      'author'   => 'Juan dela Cruz',
      'notes'    => 'Urgent — expires end of week',
      'status'   => 'pending',
    ],
    [
      'title'    => 'Review Trademark Documentation',
      'category' => 'review',
      'author'   => 'Maria Santos',
      'notes'    => 'Check formatting and completeness',
      'status'   => 'pending',
    ],
    [
      'title'    => 'File Industrial Design Registration',
      'category' => 'registration',
      'author'   => 'Pedro Reyes',
      'notes'    => '',
      'status'   => 'done',
    ],
    [
      'title'    => 'Submit Utility Model Documents',
      'category' => 'submission',
      'author'   => 'Ana Lim',
      'notes'    => 'Attach supporting drawings',
      'status'   => 'pending',
    ],
  ];

  $todayPending   = collect($todayTasks)->where('status', 'pending')->count();
  $todayDone      = collect($todayTasks)->where('status', 'done')->count();
  $todayTotal     = count($todayTasks);

  $total = max(1, count($allRecords));
  $statusCounts = collect($allRecords)->countBy('status')->sortDesc();
  $typeCounts   = collect($allRecords)->countBy('type')->sortDesc();
  $campusCounts = collect($allRecords)->countBy('campus')->sortDesc()->take(5);
  $pct = fn($n) => (int) round(($n / $total) * 100);
  $statusTop = $statusCounts->take(6);
  $typeTop   = $typeCounts->take(3);
  $statusColors = ['#A52C30','#F0C860','#10b981','#3b82f6','#8b5cf6','#f97316'];

  $typeMap = [
    'patent'    => ['label' => 'Invention / Patent',  'keys' => ['patent','invention','invention / patent']],
    'utility'   => ['label' => 'Utility Models',       'keys' => ['utility model','utility models','utility']],
    'design'    => ['label' => 'Industrial Design',    'keys' => ['industrial design','design']],
    'tm'        => ['label' => 'Trademarks',           'keys' => ['trademark','trade mark','trademarks']],
    'copyright' => ['label' => 'Copyright',            'keys' => ['copyright']],
  ];

  $ifoByYear = collect($allRecords)
    ->groupBy(fn($r) => \Carbon\Carbon::parse($r['registered'] ?? now())->year)
    ->sortKeys()
    ->map(function($recs) use ($typeMap) {
      $row = [];
      foreach ($typeMap as $key => $def) {
        $row[$key] = $recs->filter(fn($r) => in_array(strtolower($r['type'] ?? ''), $def['keys']))->count();
      }
      $row['total'] = array_sum($row);
      return $row;
    });

  $allYears = \App\Models\IpRecord::whereNotNull('date_registered_deposited')
    ->selectRaw('EXTRACT(YEAR FROM date_registered_deposited) as year')
    ->distinct()->orderBy('year')
    ->pluck('year')->map(fn($y) => (int)$y)->toArray();

  if ($ifoByYear->isEmpty()) {
    $ifoByYear = collect([
      2014 => ['patent'=>0,  'utility'=>0,  'design'=>0,  'tm'=>1,  'copyright'=>0,  'total'=>1],
      2015 => ['patent'=>0,  'utility'=>0,  'design'=>0,  'tm'=>1,  'copyright'=>0,  'total'=>1],
      2016 => ['patent'=>0,  'utility'=>0,  'design'=>0,  'tm'=>4,  'copyright'=>0,  'total'=>4],
      2017 => ['patent'=>0,  'utility'=>0,  'design'=>15, 'tm'=>2,  'copyright'=>0,  'total'=>17],
      2018 => ['patent'=>0,  'utility'=>0,  'design'=>16, 'tm'=>1,  'copyright'=>0,  'total'=>17],
      2019 => ['patent'=>0,  'utility'=>3,  'design'=>17, 'tm'=>1,  'copyright'=>0,  'total'=>21],
      2020 => ['patent'=>0,  'utility'=>1,  'design'=>3,  'tm'=>0,  'copyright'=>0,  'total'=>4],
      2021 => ['patent'=>0,  'utility'=>9,  'design'=>4,  'tm'=>0,  'copyright'=>0,  'total'=>13],
      2022 => ['patent'=>0,  'utility'=>3,  'design'=>4,  'tm'=>0,  'copyright'=>0,  'total'=>7],
      2023 => ['patent'=>0,  'utility'=>0,  'design'=>4,  'tm'=>4,  'copyright'=>0,  'total'=>8],
      2024 => ['patent'=>1,  'utility'=>0,  'design'=>1,  'tm'=>1,  'copyright'=>0,  'total'=>3],
      2025 => ['patent'=>3,  'utility'=>0,  'design'=>0,  'tm'=>0,  'copyright'=>0,  'total'=>3],
      2026 => ['patent'=>0,  'utility'=>1,  'design'=>0,  'tm'=>0,  'copyright'=>0,  'total'=>1],
    ]);
  }

  $ifoGrandTotal = $ifoByYear->sum('total');
  $ifoTotals = [
    'patent'    => $ifoByYear->sum('patent'),
    'utility'   => $ifoByYear->sum('utility'),
    'design'    => $ifoByYear->sum('design'),
    'tm'        => $ifoByYear->sum('tm'),
    'copyright' => $ifoByYear->sum('copyright'),
  ];
  $ifoMaxTotal = max(1, $ifoByYear->max('total'));
  $ifoPeakYear = $ifoByYear->sortByDesc('total')->keys()->first();

  $urlNew     = url('/ipassets/create');
  $urlRecords = url('/records');
  $urlInsights= url('/insights');
  $urlLogout  = url('/logout');
  $urlCalendar= url('/calendar');

  $initials = collect(explode(' ', $user->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');

  // Avatar image from session (same as records / insights / calendar)
  $sessionAvatarImage = session('user_avatar_image', null);

  /* Category → CSS classes map */
  $catBarClass = [
    'deadline'     => 'bar-deadline',
    'registration' => 'bar-registration',
    'review'       => 'bar-review',
    'submission'   => 'bar-submission',
    'pending'      => 'bar-pending',
  ];
  $catPillClass = [
    'deadline'     => 'pill-deadline',
    'registration' => 'pill-registration',
    'review'       => 'pill-review',
    'submission'   => 'pill-submission',
    'pending'      => 'pill-pending',
  ];
  $catLabel = [
    'deadline'     => 'Deadline',
    'registration' => 'Registration',
    'review'       => 'Review',
    'submission'   => 'Submission',
    'pending'      => 'Pending',
  ];
  $registered = collect($allRecords)->filter(fn($r) => strtolower($r['status'] ?? '') === 'registered')->count();
  $pending    = collect($allRecords)->filter(fn($r) => in_array(strtolower($r['status'] ?? ''), ['pending','under review','filed']))->count();
@endphp

{{-- ══════════════ SIDEBAR ══════════════ --}}
{{-- Mobile sidebar backdrop --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

<aside class="sidebar" id="mainSidebar" aria-label="Main navigation">

  {{-- User avatar — shows profile picture or initials --}}
  <div class="sidebar-user-avatar nav-item" style="margin-bottom:20px; width:42px; height:42px; border-radius:14px; {{ $sessionAvatarImage ? 'background:transparent;' : 'background: linear-gradient(135deg, var(--gold), var(--gold2));' }} font-weight:800; font-size:0.78rem; color:#2a1a0b; box-shadow:0 6px 18px rgba(240,200,96,.35); cursor:default; flex-shrink:0; overflow:hidden; padding:0;">
    @if($sessionAvatarImage)
      <img src="{{ asset('storage/avatars/' . $sessionAvatarImage) }}"
           alt="{{ $userInitials }}"
           style="width:42px;height:42px;object-fit:cover;border-radius:14px;display:block;">
    @else
      {{ $userInitials }}
    @endif
    <span class="nav-tooltip" style="min-width:140px; line-height:1.5;">
      {{ $user->name }}<br>
      <span style="opacity:.65; font-weight:500; letter-spacing:.06em; text-transform:uppercase; font-size:.6rem;">{{ $roleLabel }}</span>
    </span>
  </div>

  {{-- Mobile-only: full name + role shown when sidebar is expanded --}}
  <div style="display:none;" class="sidebar-mobile-user">
    <div style="display:flex;align-items:center;gap:11px;padding:0 4px 20px;border-bottom:1px solid rgba(255,255,255,.12);margin-bottom:10px;width:100%;">
      <div style="width:38px;height:38px;border-radius:12px;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.78rem;color:#2a1a0b;{{ $sessionAvatarImage ? '' : 'background:linear-gradient(135deg,var(--gold),var(--gold2));' }}">
        @if($sessionAvatarImage)
          <img src="{{ asset('storage/avatars/' . $sessionAvatarImage) }}" alt="{{ $userInitials }}" style="width:38px;height:38px;object-fit:cover;border-radius:12px;display:block;">
        @else
          {{ $userInitials }}
        @endif
      </div>
      <div style="min-width:0;">
        <div style="font-size:0.84rem;font-weight:800;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
        <div style="font-size:0.62rem;font-weight:600;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.08em;margin-top:1px;">{{ $roleLabel }}</div>
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a href="{{ url('/home') }}" class="nav-item active">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
      <span class="nav-tooltip">Dashboard</span>
    </a>
    <a href="{{ $urlRecords }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
      </svg>
      <span class="nav-tooltip">Records</span>
    </a>
    <a href="{{ $urlInsights }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
      </svg>
      <span class="nav-tooltip">Insights</span>
    </a>
    <a href="{{ $urlCalendar }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="16" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      <span class="nav-tooltip">Calendar</span>
    </a>
  </nav>

  <div class="sidebar-bottom">
    {{-- Profile button — goes to profile page --}}
    <a href="{{ url('/profile') }}" class="nav-item" title="Profile">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
      </svg>
      <span class="nav-tooltip">Profile</span>
    </a>

    {{-- Logout --}}
    <button id="logoutBtn" class="nav-item" style="background:none;border:none;cursor:pointer;">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
      <button class="hamburger-btn" id="hamburgerBtn" aria-label="Open navigation menu" aria-expanded="false">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <line x1="3" y1="6"  x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
      <div>
        <div class="page-title">Dashboard</div>
        <div class="page-subtitle">Welcome back, {{ $user->name }}</div>
      </div>
    </div>

    

    <div class="topbar-right">
      @if($showCreateProfileFirst)
      <button type="button" class="btn-profile" id="openCreateProfileModal" title="Create Profile First">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <path d="M12 5v14"/><path d="M5 12h14"/>
        </svg>
        Create New Profile
      </button>
      @endif

      {{-- ── HOW TO USE BUTTON ── --}}
      <button id="howToUseBtn" class="btn-howto" title="How to Use">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span class="btn-howto-label">How to Use</span>
      </button>

      {{-- ── BELL BUTTON ── --}}
      <div class="icon-btn" id="bellBtn" style="position:relative;" title="Today's Tasks">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" id="bellIcon">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
        @if($todayPending > 0)
        <span class="bell-badge" id="bellBadge">{{ $todayPending }}</span>
        @endif

        {{-- NOTIFICATION DROPDOWN --}}
        <div class="notif-dropdown" id="notifDropdown">
          <div class="notif-drop-head">
            <div>
              <div class="notif-drop-title">Today's Tasks</div>
              <div class="notif-drop-sub">{{ now()->format('l, F j, Y') }}</div>
            </div>
            <span class="notif-drop-badge">{{ $todayTotal }} task{{ $todayTotal != 1 ? 's' : '' }}</span>
          </div>

          @if($todayTotal > 0)
          <div class="notif-drop-list">
            @foreach($todayTasks as $task)
            @php
              $isDone  = $task['status'] === 'done';
              $barCls  = $isDone ? 'bar-done' : ($catBarClass[$task['category']] ?? 'bar-pending');
              $pillCls = $isDone ? 'pill-done' : ($catPillClass[$task['category']] ?? 'pill-pending');
              $lbl     = $isDone ? 'Done' : ($catLabel[$task['category']] ?? ucfirst($task['category']));
            @endphp
            <div class="notif-drop-item {{ $isDone ? 'is-done' : '' }}" onclick="openTodoModal()">
              <div class="notif-item-bar {{ $barCls }}"></div>
              <div class="notif-item-title">{{ $task['title'] }}</div>
              <span class="notif-item-pill todo-cat-pill {{ $pillCls }}">{{ $lbl }}</span>
            </div>
            @endforeach
          </div>
          <div class="notif-drop-footer">
            <button class="notif-view-all" onclick="openTodoModal()">
              View all tasks
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
          @else
          <div class="notif-drop-empty">
            <div style="font-size:1.6rem;margin-bottom:6px;">🎉</div>
            No tasks scheduled for today.
          </div>
          @endif
        </div>
      </div>

      <a href="{{ $urlNew }}" class="btn-primary" title="New Record">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span class="btn-primary-text">New Record</span>
      </a>
      
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

    {{-- KPI STRIP --}}
    <div class="kpi-strip">
      <div class="kpi-card c1 anim anim-1">
        <div class="kpi-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
        <div class="kpi-body">
          <div class="kpi-val">{{ $kpis['my_open'] ?? 0 }}</div>
          <div class="kpi-label">Open Records</div>
          <div class="kpi-sub">Not yet registered</div>
        </div>
      </div>
      <div class="kpi-card c2 anim anim-2">
        <div class="kpi-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
        <div class="kpi-body">
          <div class="kpi-val">{{ $kpis['needs_attention'] ?? 0 }}</div>
          <div class="kpi-label">Needs Attention</div>
          <div class="kpi-sub">Action required</div>
        </div>
      </div>
      <div class="kpi-card c3 anim anim-3">
        <div class="kpi-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div class="kpi-body">
          <div class="kpi-val">{{ $kpis['due_soon'] ?? 0 }}</div>
          <div class="kpi-label">Due Soon</div>
          <div class="kpi-sub">Priority next</div>
        </div>
      </div>
      <div class="kpi-card c4 anim anim-4">
        <div class="kpi-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg></div>
        <div class="kpi-body">
          <div class="kpi-val">{{ $kpis['total_records'] ?? 0 }}</div>
          <div class="kpi-label">Total Records</div>
          <div class="kpi-sub">Full archive</div>
        </div>
      </div>
    </div>

    {{-- MAIN GRID --}}
    <div class="main-grid">
      <div>
        {{-- IP FILINGS OVERVIEW CARD --}}
        @php
          $ifoTypes = [
            'patent'    => ['label' => 'Patent',    'col' => 'c-patent'],
            'utility'   => ['label' => 'Utility',   'col' => 'c-utility'],
            'design'    => ['label' => 'Design',    'col' => 'c-design'],
            'tm'        => ['label' => 'Trademark', 'col' => 'c-tm'],
            'copyright' => ['label' => 'Copyright', 'col' => 'c-copyright'],
          ];
        @endphp
        <div class="ifo-card anim anim-3">
          <div class="ifo-header">
            <div>
              <div class="ifo-title">IP Filings Overview</div>
              <div class="ifo-sub">REGISTERED ASSETS BY YEAR &amp; TYPE</div>
            </div>
            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:10px;">
              <div>
                <div class="ifo-gt-num" id="ifoGrandTotal">{{ $ifoGrandTotal }}</div>
                <div class="ifo-gt-label">Total Filings</div>
              </div>
              <div style="display:flex; gap:3px; background:rgba(255,255,255,.10); border-radius:8px; padding:3px; align-items:center;">
                <button class="ifo-yr-btn active" onclick="filterIFO(this,'all')">All</button>
                <button class="ifo-yr-btn" onclick="filterIFO(this,'5')">Last 5</button>
                <button class="ifo-yr-btn" onclick="filterIFO(this,'3')">Last 3</button>
                <select id="ifoYearSelect" style="margin-left:8px; border-radius:6px; padding:2px 8px; background:rgba(255,255,255,.15); color:#222; border:none; font-size:1rem;" onchange="filterIFO(this,'year')">
                  <option value="">Year</option>
                  @foreach($allYears as $yr)
                    <option value="{{ $yr }}">{{ $yr }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="ifo-legend">
            <div class="ifo-pill"><div class="ifo-pill-dot c-patent"></div>Invention / Patent</div>
            <div class="ifo-pill"><div class="ifo-pill-dot c-utility"></div>Utility Models</div>
            <div class="ifo-pill"><div class="ifo-pill-dot c-design"></div>Industrial Design</div>
            <div class="ifo-pill"><div class="ifo-pill-dot c-tm"></div>Trademarks</div>
            <div class="ifo-pill"><div class="ifo-pill-dot c-copyright"></div>Copyright</div>
          </div>
          <div class="ifo-col-heads">
            <div class="ifo-ch">Year</div>
            <div class="ifo-ch">Distribution</div>
            <div class="ifo-ch">Pat.</div>
            <div class="ifo-ch">Util.</div>
            <div class="ifo-ch">Des.</div>
            <div class="ifo-ch">TM</div>
            <div class="ifo-ch">Copy.</div>
            <div class="ifo-ch">Total</div>
          </div>
          <div class="ifo-rows-wrap" id="ifoRows">
            @foreach($ifoByYear as $yr => $row)
            @php
              $rowTotal  = $row['total'];
              $isPeak    = ($yr == $ifoPeakYear);
              $barMax    = $ifoMaxTotal;
              $pPat  = $barMax > 0 ? round(($row['patent']    / $barMax) * 100) : 0;
              $pUtil = $barMax > 0 ? round(($row['utility']   / $barMax) * 100) : 0;
              $pDes  = $barMax > 0 ? round(($row['design']    / $barMax) * 100) : 0;
              $pTm   = $barMax > 0 ? round(($row['tm']        / $barMax) * 100) : 0;
              $pCopy = $barMax > 0 ? round(($row['copyright'] / $barMax) * 100) : 0;
            @endphp
            <div class="ifo-row {{ $isPeak ? 'peak' : '' }}"
                 data-year="{{ $yr }}" data-total="{{ $rowTotal }}"
                 data-patent="{{ $row['patent'] }}" data-utility="{{ $row['utility'] }}"
                 data-design="{{ $row['design'] }}" data-tm="{{ $row['tm'] }}"
                 data-copyright="{{ $row['copyright'] }}">
              <div class="ifo-year">
                {{ $yr }}
                @if($isPeak)<span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:var(--gold);margin-left:4px;vertical-align:middle;"></span>@endif
              </div>
              <div class="ifo-bar-wrap" data-pat="{{ $pPat }}" data-util="{{ $pUtil }}" data-des="{{ $pDes }}" data-tm="{{ $pTm }}" data-copy="{{ $pCopy }}">
                @if($pPat  > 0)<div class="ifo-seg c-patent"    style="width:0%"></div>@endif
                @if($pUtil > 0)<div class="ifo-seg c-utility"   style="width:0%"></div>@endif
                @if($pDes  > 0)<div class="ifo-seg c-design"    style="width:0%"></div>@endif
                @if($pTm   > 0)<div class="ifo-seg c-tm"        style="width:0%"></div>@endif
                @if($pCopy > 0)<div class="ifo-seg c-copyright" style="width:0%"></div>@endif
              </div>
              <div class="ifo-chip">{{ $row['patent']    ?: '—' }}</div>
              <div class="ifo-chip">{{ $row['utility']   ?: '—' }}</div>
              <div class="ifo-chip">{{ $row['design']    ?: '—' }}</div>
              <div class="ifo-chip">{{ $row['tm']        ?: '—' }}</div>
              <div class="ifo-chip">{{ $row['copyright'] ?: '—' }}</div>
              <div class="ifo-row-total">{{ $rowTotal }}</div>
            </div>
            @endforeach
          </div>
          <div class="ifo-footer">
            <div class="ifo-footer-label">Total</div>
            <div></div>
            <div class="ifo-footer-chip">{{ $ifoTotals['patent'] }}</div>
            <div class="ifo-footer-chip">{{ $ifoTotals['utility'] }}</div>
            <div class="ifo-footer-chip">{{ $ifoTotals['design'] }}</div>
            <div class="ifo-footer-chip">{{ $ifoTotals['tm'] }}</div>
            <div class="ifo-footer-chip">{{ $ifoTotals['copyright'] }}</div>
            <div class="ifo-footer-total">{{ $ifoGrandTotal }}</div>
          </div>
        </div>

        {{-- BOTTOM 3 ACTIVITY CARDS --}}
        <div class="bottom-grid">
          @php $topStatuses = $statusTop->take(3); @endphp
          @foreach($topStatuses as $label => $count)
          @php
            $pctVal = $pct($count);
            $icons  = ['M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'];
            $ic = $icons[$loop->index % 3];
          @endphp
          <div class="activity-card anim anim-{{ $loop->index + 5 }}">
            <div class="ac-header">
              <div class="ac-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="{{ $ic }}"/></svg>
              </div>

            </div>
            <div class="ac-title">{{ $label ?: '—' }}</div>
            <div class="ac-meta">{{ $count }} record{{ $count!=1?'s':'' }}</div>
            <div class="progress-row">
              <span class="progress-label">Progress</span>
              <span class="progress-pct">{{ $pctVal }}%</span>
            </div>
            <div class="progress-track"><div class="progress-fill" style="width: {{ $pctVal }}%"></div></div>
            <div class="ac-target">of {{ $total }} total records</div>
          </div>
          @endforeach
          @if($statusTop->count() === 0)
          @foreach(['Open', 'Filed', 'Registered'] as $placeholder)
          <div class="activity-card anim anim-{{ $loop->index + 5 }}">
            <div class="ac-header"><div class="ac-icon"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg></div></div>
            <div class="ac-title">{{ $placeholder }}</div>
            <div class="ac-meta">No data yet</div>
            <div class="progress-row"><span class="progress-label">Progress</span><span class="progress-pct">0%</span></div>
            <div class="progress-track"><div class="progress-fill" style="width:0%"></div></div>
            <div class="ac-target">Add records to see analytics</div>
          </div>
          @endforeach
          @endif
        </div>
      </div>

      {{-- RIGHT COLUMN --}}
      <div class="right-col">
        <div class="qs-pair">
          <div class="qs-card maroon-card anim anim-3">
            <div class="qs-label">Registered</div>
            <div class="qs-val">{{ $registered }}</div>
            <div class="qs-desc">Filed IPs</div>
          </div>
          <div class="qs-card gold-card anim anim-4">
            <div class="qs-label">Pending</div>
            <div class="qs-val">{{ $pending }}</div>
            <div class="qs-desc">In progress</div>
          </div>
        </div>
        <div class="panel-card anim anim-5">
          <div class="panel-title">Status Breakdown</div>
          @forelse($statusTop as $label => $count)
          @php $pctV = $pct($count); $ci = $loop->index; @endphp
          <div class="status-row">
            <div class="status-dot" style="background: {{ $statusColors[$ci % count($statusColors)] }};"></div>
            <div class="status-name">{{ $label ?: '—' }}</div>
            <div class="status-bar-wrap"><div class="status-bar-fill" style="width: {{ $pctV }}%;"></div></div>
            <div class="status-count">{{ $count }}</div>
          </div>
          @empty
          <div style="font-size:.78rem; color:var(--muted); text-align:center; padding: 16px 0;">No data yet.</div>
          @endforelse
        </div>
        <div class="panel-card anim anim-6">
          <div class="panel-title">Top Campuses</div>
          <div class="campus-wrap">
            @forelse($campusCounts as $campus => $count)
            <div class="campus-row">
              <div class="campus-name">{{ $campus ?: '—' }}</div>
              <div class="campus-badge">{{ $count }}</div>
            </div>
            @empty
            <div style="font-size:.78rem; color:var(--muted); text-align:center; padding: 8px 0;">No campus data yet.</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <footer style="margin-top:24px; padding: 16px 0; border-top:1px solid var(--line); display:flex; justify-content:space-between; align-items:center;">
      <div style="font-size:.72rem; color:var(--muted);">© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div style="font-size:.72rem; font-family:'DM Mono',monospace; color:#94a3b8;">Dashboard v2.0</div>
    </footer>

  </div>
</div>

{{-- ══════════════ STATUS DETAIL MODAL ══════════════ --}}
@php
  $smTabs = [
    ['key' => 'unregistered',   'label' => 'Unregistered',   'color' => '#A52C30', 'records' => $unregisteredRecords  ?? []],
    ['key' => 'under-review',   'label' => 'Under Review',   'color' => '#d97706', 'records' => $underReviewRecords   ?? []],
    ['key' => 'recently-filed', 'label' => 'Recently Filed', 'color' => '#2563eb', 'records' => $recentlyFiledRecords ?? []],
  ];
@endphp
<div class="status-modal-overlay" id="statusDetailModal">
  <div class="status-modal-box">

    {{-- Header --}}
    <div class="status-modal-head">
      <div class="status-modal-head-top">
        <div>
          <div class="status-modal-eyebrow">Status Breakdown</div>
          <div class="status-modal-title">Filing Status Details</div>
          <div class="status-modal-sub">Click a tab to browse records by status</div>
        </div>
        <button class="status-modal-close" id="closeStatusModal">&#10005;</button>
      </div>

      {{-- Tabs --}}
      <div class="status-modal-tabs">
        @foreach($smTabs as $i => $tab)
        <button class="smt-tab {{ $i === 0 ? 'active' : '' }}"
                onclick="switchSmTab('{{ $tab['key'] }}', this)">
          <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:{{ $tab['color'] }};opacity:{{ ($i===0)?'1':'.6' }};"></span>
          {{ $tab['label'] }}
          <span class="smt-tab-badge">{{ count($tab['records']) }}</span>
        </button>
        @endforeach
      </div>
    </div>

    {{-- Body --}}
    <div class="status-modal-body">
      @foreach($smTabs as $i => $tab)
      <div class="smt-panel {{ $i === 0 ? 'active' : '' }}" id="smt-panel-{{ $tab['key'] }}">
        @forelse($tab['records'] as $rec)
        <div class="sr-card">
          <div class="sr-card-top">
            <div class="sr-card-title">{{ $rec['title'] ?? '—' }}</div>
            @if(!empty($rec['id']))
            <div class="sr-card-id">{{ $rec['id'] }}</div>
            @endif
          </div>
          <div class="sr-card-grid">
            <div class="sr-field">
              <div class="sr-field-label">Type</div>
              <div class="sr-field-val {{ empty($rec['type']) ? 'muted' : '' }}">{{ $rec['type'] ?? '—' }}</div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Owner / Inventor</div>
              <div class="sr-field-val {{ empty($rec['owner']) ? 'muted' : '' }}">{{ $rec['owner'] ?? '—' }}</div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Campus</div>
              <div class="sr-field-val {{ empty($rec['campus']) ? 'muted' : '' }}">{{ $rec['campus'] ?? '—' }}</div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">College</div>
              <div class="sr-field-val {{ empty($rec['college']) ? 'muted' : '' }}">{{ $rec['college'] ?? '—' }}</div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Program</div>
              <div class="sr-field-val {{ empty($rec['program']) ? 'muted' : '' }}">{{ $rec['program'] ?? '—' }}</div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Class of Work</div>
              <div class="sr-field-val {{ empty($rec['class_of_work']) ? 'muted' : '' }}">{{ $rec['class_of_work'] ?? '—' }}</div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Date of Creation</div>
              <div class="sr-field-val {{ empty($rec['date_creation']) ? 'muted' : '' }}">
                {{ !empty($rec['date_creation']) ? \Carbon\Carbon::parse($rec['date_creation'])->format('M d, Y') : '—' }}
              </div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Date Filed / Registered</div>
              <div class="sr-field-val {{ empty($rec['registered']) ? 'muted' : '' }}">
                {{ !empty($rec['registered']) ? \Carbon\Carbon::parse($rec['registered'])->format('M d, Y') : '—' }}
              </div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Registration No.</div>
              <div class="sr-field-val {{ empty($rec['registration_number']) ? 'muted' : '' }}">{{ $rec['registration_number'] ?? '—' }}</div>
            </div>
            <div class="sr-field">
              <div class="sr-field-label">Status</div>
              <div class="sr-field-val" style="color:{{ $tab['color'] }}; font-weight:800;">{{ $rec['status'] ?? '—' }}</div>
            </div>
          </div>
          @if(!empty($rec['remarks']) || !empty($rec['gdrive_link']) || !empty($rec['id']))
          <div class="sr-card-footer">
            <div class="sr-remarks">{{ !empty($rec['remarks']) ? $rec['remarks'] : 'No remarks.' }}</div>
            <div style="display:flex;gap:6px;flex-shrink:0;">
              @if(!empty($rec['gdrive_link']))
              <a href="{{ $rec['gdrive_link'] }}" target="_blank" class="sr-gdrive-btn">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                View File
              </a>
              @endif
              @if(!empty($rec['id']))
              <a href="{{ url('/record-changes/' . urlencode($rec['id'])) }}" class="sr-view-btn">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View
              </a>
              @endif
            </div>
          </div>
          @endif
        </div>
        @empty
        <div class="sr-empty">
          <div class="sr-empty-icon">
            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
            </svg>
          </div>
          <div class="sr-empty-title">No records found</div>
          <div class="sr-empty-sub">There are currently no IP filings tagged as "{{ $tab['label'] }}".</div>
        </div>
        @endforelse
      </div>
      @endforeach
    </div>

    {{-- Footer --}}
    <div class="status-modal-footer">
      <button class="btn-outline-sm" id="closeStatusModalBtn">Close</button>
    </div>

  </div>
</div>

{{-- ══════════════ TODAY'S TO-DO MODAL ══════════════ --}}
<div class="modal-overlay" id="todoModal">
  <div class="todo-modal-box">

    {{-- Header --}}
    <div class="todo-modal-head">
      <div class="todo-modal-head-top">
        <div>
          <div class="todo-modal-eyebrow">Daily Agenda</div>
          <div class="todo-modal-title">Today's To-Do List</div>
          <div class="todo-modal-date">{{ now()->format('l, F j, Y') }}</div>
        </div>
        <button class="todo-modal-close" id="closeTodoModal">&#10005;</button>
      </div>
      <div class="todo-modal-stats">
        <div class="todo-stat-pill">
          <div class="dot dot-total"></div>
          {{ $todayTotal }} Total
        </div>
        <div class="todo-stat-pill">
          <div class="dot dot-pending"></div>
          {{ $todayPending }} Pending
        </div>
        <div class="todo-stat-pill">
          <div class="dot dot-done"></div>
          {{ $todayDone }} Done
        </div>
      </div>
    </div>

    {{-- Body --}}
    <div class="todo-modal-body">
      @if($todayTotal > 0)
        @foreach($todayTasks as $task)
        @php
          $isDone  = $task['status'] === 'done';
          $barCls  = $isDone ? 'bar-done' : ($catBarClass[$task['category']] ?? 'bar-pending');
          $pillCls = $isDone ? 'pill-done' : ($catPillClass[$task['category']] ?? 'pill-pending');
          $lbl     = $isDone ? 'Completed' : ($catLabel[$task['category']] ?? ucfirst($task['category']));
        @endphp
        <div class="todo-task-card {{ $isDone ? 'is-done' : '' }}">
          <div class="todo-task-bar {{ $barCls }}"></div>
          <div class="todo-task-content">
            <div class="todo-task-top">
              <span class="todo-cat-pill {{ $pillCls }}">{{ $lbl }}</span>
              @if($isDone)
              <span style="font-size:.6rem;font-weight:700;color:#059669;display:flex;align-items:center;gap:3px;">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Completed
              </span>
              @endif
            </div>
            <div class="todo-task-title">{{ $task['title'] }}</div>
            <div class="todo-task-meta">
              @if(!empty($task['author']))
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ $task['author'] }}
              @endif
              @if(!empty($task['notes']))
                <span class="meta-sep">·</span>
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                {{ $task['notes'] }}
              @endif
            </div>
          </div>
        </div>
        @endforeach
      @else
        <div class="todo-empty">
          <div class="todo-empty-icon">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
          </div>
          <div class="todo-empty-title">All clear for today!</div>
          <div class="todo-empty-sub">No tasks are scheduled. Head to the Calendar to add some.</div>
        </div>
      @endif
    </div>

    {{-- Footer --}}
    <div class="todo-modal-footer">
      <button class="btn-outline-sm" id="closeTodoModal2">
        Close
      </button>
      <a href="{{ $urlCalendar }}" class="btn-primary-sm">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="16" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/>
          <line x1="8" y1="2" x2="8" y2="6"/>
          <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        Open Calendar
      </a>
    </div>

  </div>
</div>

{{-- ══════════════ HOW TO USE MODAL ══════════════ --}}
<div class="howto-overlay" id="howtoModal">
  <div class="howto-box">

    {{-- Header --}}
    <div class="howto-head">
      <div class="howto-head-top">
        <div class="howto-head-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div class="howto-head-text">
          <div class="howto-eyebrow">KTTM · System Guide</div>
          <div class="howto-title">How to Use the System</div>
          <div class="howto-sub">A quick walkthrough of all major features available to you.</div>
        </div>
        <button class="howto-close" id="howtoClose">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
    </div>

    {{-- Steps --}}
    <div class="howto-body">

      <div class="howto-step">
        <div class="howto-step-num">01</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Dashboard — Your Home Base</div>
          <div class="howto-step-desc">The Dashboard gives you a real-time overview of all IP records — KPIs, filing trends by year, campus breakdown, and today's scheduled tasks. Use the bell icon to view and manage today's to-do list.</div>
          <span class="howto-step-tag">Current Page</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">02</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Records — Browse & Manage IP Filings</div>
          <div class="howto-step-desc">Go to <strong>Records</strong> to search, filter, and view all IP filings. Use the filter bar to narrow down by campus, category, or status. Click <strong>Edit Record</strong> to update any entry's details directly from the table.</div>
          <span class="howto-step-tag">Sidebar → Records</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">03</div>
        <div class="howto-step-body">
          <div class="howto-step-title">New Record — Add an IP Filing</div>
          <div class="howto-step-desc">Click <strong>+ New Record</strong> in the topbar to open the 3-step wizard. Fill in the IP details, add inventors, then review and submit. The system auto-assigns a record ID on successful save.</div>
          <span class="howto-step-tag">Topbar → New Record</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">04</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Change History — Track Record Activity</div>
          <div class="howto-step-desc">On any record in the Records table, click the <strong>View</strong> button to open that record's full change history. See every edit ever made, who made it, and what fields changed — with timestamps.</div>
          <span class="howto-step-tag">Records → View Button</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">05</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Insights — Analytics & Charts</div>
          <div class="howto-step-desc">The <strong>Insights</strong> page visualizes your data — filings by category, campus distribution, registration trends over time, gender breakdown, and top inventors. Use the filter panel to slice by type, campus, or status.</div>
          <span class="howto-step-tag">Sidebar → Insights</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">06</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Calendar — Schedule & Track Deadlines</div>
          <div class="howto-step-desc">Use the <strong>Calendar</strong> to create and track IP-related tasks — registration deadlines, review dates, submissions. Tasks appear on the bell notification and in the daily to-do modal on your dashboard.</div>
          <span class="howto-step-tag">Sidebar → Calendar</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">07</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Guest Access — Public View</div>
          <div class="howto-step-desc">Guests can browse and search all IP records in view-only mode — no login, no editing. </div>
          <span class="howto-step-tag">Guest · View Only</span>
        </div>
      </div>

    </div>

    {{-- Footer --}}
    <div class="howto-footer">
      <div class="howto-footer-note">Need more help? Contact your <strong>KTTM administrator</strong>.</div>
      <button class="btn-primary-sm" id="howtoCloseBtn">Got it, thanks!</button>
    </div>

  </div>
</div>

{{-- ══════════════ LOGOUT MODAL ══════════════ --}}
@if($showCreateProfileFirst)
<div class="modal-overlay" id="createProfileModal">
  <div class="create-profile-modal-box">
    <div class="create-profile-head">
      <div class="create-profile-head-top">
        <div>
          <div class="create-profile-eyebrow">Admin Setup</div>
          <div class="create-profile-title">Create New Profile</div>
          <div class="create-profile-sub">Add the profile name, set a password, and choose a profile picture before continuing.</div>
        </div>
        <button type="button" class="todo-modal-close" id="closeCreateProfileModal" aria-label="Close create profile modal">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
            <line x1="6" y1="6" x2="18" y2="18"/><line x1="6" y1="18" x2="18" y2="6"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="create-profile-body">
      <div class="create-profile-panel create-profile-preview">
        <div class="create-profile-avatar" id="createProfileAvatarPreview">
          <span id="createProfileAvatarInitials">NP</span>
          <img id="createProfileAvatarImage" alt="Profile preview">
        </div>
        <div class="create-profile-preview-name" id="createProfilePreviewName">New Profile</div>
        <div class="create-profile-preview-sub">This preview updates while you type the name and choose a picture.</div>

        <div class="cp-dropzone" id="createProfileDropzone">
          <input type="file" id="createProfileFileInput" accept="image/jpeg,image/png,image/webp" style="display:none;">
          <div class="cp-dropzone-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
              <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
          </div>
          <div class="cp-dropzone-title" id="createProfileDropzoneTitle">Choose Profile Picture</div>
          <div class="cp-dropzone-sub" id="createProfileDropzoneSub">Click or drag an image here. JPG, PNG, or WEBP up to 2MB.</div>
        </div>
      </div>

      <div class="create-profile-panel">
        <div class="cp-form">
          <div class="cp-field">
            <label class="cp-label" for="createProfileName">Name</label>
            <input type="text" class="cp-input" id="createProfileName" placeholder="Enter full profile name">
          </div>

          <div class="cp-field">
            <label class="cp-label" for="createProfilePassword">Password</label>
            <div class="cp-input-wrap">
              <input type="password" class="cp-input cp-input-password" id="createProfilePassword" placeholder="Enter profile password">
              <button type="button" class="cp-eye" id="toggleCreateProfilePassword" aria-label="Toggle password visibility">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="cp-note">
            Use this modal to prepare the new profile details in the same dashboard flow. The picture preview matches the profile page style so it feels consistent during setup.
          </div>

          <div class="cp-status" id="createProfileStatus">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span id="createProfileStatusText"></span>
          </div>
        </div>
      </div>
    </div>

    <div class="cp-footer">
      <div class="cp-footer-text">Name, password, and profile picture are all prepared here in one place.</div>
      <div class="modal-btns">
        <button type="button" class="btn-cancel" id="cancelCreateProfileModal">Cancel</button>
        <button type="button" class="btn-confirm" id="submitCreateProfileBtn">Create Profile</button>
      </div>
    </div>
    <input type="hidden" id="createProfileRoute" value="{{ url('/profile/create') }}">
  </div>
</div>
@endif

<div class="modal-overlay" id="logoutModal">
  <div class="modal-box">
    <div class="modal-icon">
      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>
    <div class="modal-title">Sign out of KTTM</div>
    <div class="modal-desc">This will end your current session and return you to the public portal.</div>
    <div class="modal-btns">
      <button class="btn-cancel" id="cancelLogout">Cancel</button>
      <form action="{{ $urlLogout }}" method="POST" style="flex:1;" id="logoutForm">
        @csrf
        <button type="submit" class="btn-confirm" style="width:100%;">Sign Out</button>
      </form>
    </div>
  </div>
</div>

<script>
(function() {

  /* ══════════════════════════════════════
     BELL / NOTIFICATION DROPDOWN
  ══════════════════════════════════════ */
  const bellBtn       = document.getElementById('bellBtn');
  const notifDropdown = document.getElementById('notifDropdown');
  const bellIcon      = document.getElementById('bellIcon');

  bellBtn?.addEventListener('click', function(e) {
    e.stopPropagation();
    const isOpen = notifDropdown.classList.contains('open');
    notifDropdown.classList.toggle('open');
    if (!isOpen) {
      // Ring animation on open
      bellIcon.classList.add('bell-ring');
      bellIcon.addEventListener('animationend', () => bellIcon.classList.remove('bell-ring'), { once: true });
    }
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    if (!bellBtn.contains(e.target)) {
      notifDropdown.classList.remove('open');
    }
  });

  /* ══════════════════════════════════════
     TODAY'S TO-DO MODAL
  ══════════════════════════════════════ */
  const todoModal       = document.getElementById('todoModal');
  const closeTodoModal  = document.getElementById('closeTodoModal');
  const closeTodoModal2 = document.getElementById('closeTodoModal2');

  window.openTodoModal = function() {
    notifDropdown.classList.remove('open');
    todoModal.classList.add('open');
    document.body.style.overflow = 'hidden';
  };

  function closeTodo() {
    todoModal.classList.remove('open');
    document.body.style.overflow = '';
  }

  closeTodoModal?.addEventListener('click', closeTodo);
  closeTodoModal2?.addEventListener('click', closeTodo);
  todoModal?.addEventListener('click', e => { if (e.target === todoModal) closeTodo(); });

  /* ══════════════════════════════════════
     AUTO TOAST NUDGE (once per session)
  ══════════════════════════════════════ */
  const pendingCount = {{ $todayPending }};

  if (pendingCount > 0 && !sessionStorage.getItem('kttm_todo_nudge_shown')) {
    sessionStorage.setItem('kttm_todo_nudge_shown', '1');

    setTimeout(() => {
      const toast = document.createElement('div');
      toast.className = 'todo-toast';
      toast.innerHTML = `
        <div class="toast-icon">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <div class="toast-content">
          <div class="toast-title">You have ${pendingCount} task${pendingCount > 1 ? 's' : ''} today</div>
          <div class="toast-sub">Don't forget your scheduled activities</div>
        </div>
        <button class="toast-view-btn" id="toastViewBtn">View</button>
        <button class="toast-close-btn" id="toastCloseBtn">✕</button>
      `;
      document.body.appendChild(toast);

      document.getElementById('toastViewBtn')?.addEventListener('click', () => {
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
        openTodoModal();
      });

      document.getElementById('toastCloseBtn')?.addEventListener('click', () => {
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
      });

      // Auto-dismiss after 8 seconds
      setTimeout(() => {
        if (toast.isConnected) {
          toast.classList.add('hiding');
          setTimeout(() => toast.remove(), 300);
        }
      }, 8000);

    }, 1800); // delay after page load
  }

  /* ══════════════════════════════════════
     HOW TO USE MODAL
  ══════════════════════════════════════ */
  const howtoModal   = document.getElementById('howtoModal');
  const howtoClose   = document.getElementById('howtoClose');
  const howtoCloseBtn= document.getElementById('howtoCloseBtn');
  const howToUseBtn  = document.getElementById('howToUseBtn');

  function openHowto()  { howtoModal.classList.add('open');    document.body.style.overflow = 'hidden'; }
  function closeHowto() { howtoModal.classList.remove('open'); document.body.style.overflow = ''; }

  howToUseBtn?.addEventListener('click', openHowto);
  howtoClose?.addEventListener('click', closeHowto);
  howtoCloseBtn?.addEventListener('click', closeHowto);
  howtoModal?.addEventListener('click', e => { if (e.target === howtoModal) closeHowto(); });

  const createProfileModal       = document.getElementById('createProfileModal');
  const openCreateProfileModal   = document.getElementById('openCreateProfileModal');
  const closeCreateProfileModal  = document.getElementById('closeCreateProfileModal');
  const cancelCreateProfileModal = document.getElementById('cancelCreateProfileModal');
  const createProfileNameInput   = document.getElementById('createProfileName');
  const createProfilePwInput     = document.getElementById('createProfilePassword');
  const toggleCreateProfilePw    = document.getElementById('toggleCreateProfilePassword');
  const createProfilePreviewName = document.getElementById('createProfilePreviewName');
  const createProfileInitials    = document.getElementById('createProfileAvatarInitials');
  const createProfileImage       = document.getElementById('createProfileAvatarImage');
  const createProfileFileInput   = document.getElementById('createProfileFileInput');
  const createProfileDropzone    = document.getElementById('createProfileDropzone');
  const createProfileDropTitle   = document.getElementById('createProfileDropzoneTitle');
  const createProfileDropSub     = document.getElementById('createProfileDropzoneSub');
  const createProfileStatus      = document.getElementById('createProfileStatus');
  const createProfileStatusText  = document.getElementById('createProfileStatusText');
  const submitCreateProfileBtn   = document.getElementById('submitCreateProfileBtn');
  const createProfileRoute       = document.getElementById('createProfileRoute');
  let createProfileSelectedFile  = null;

  function getCreateProfileInitials(name) {
    const cleaned = String(name || '').trim().replace(/\s+/g, ' ');
    if (!cleaned) return 'NP';
    return cleaned.split(' ').map(part => part.charAt(0).toUpperCase()).slice(0, 2).join('');
  }

  function updateCreateProfilePreview() {
    const value = createProfileNameInput?.value?.trim() || '';
    if (createProfilePreviewName) createProfilePreviewName.textContent = value || 'New Profile';
    if (createProfileInitials) createProfileInitials.textContent = getCreateProfileInitials(value);
  }

  function openCreateProfile() {
    if (!createProfileModal) return;
    createProfileModal.classList.add('open');
    document.body.style.overflow = 'hidden';
    updateCreateProfilePreview();
  }

  function closeCreateProfile() {
    if (!createProfileModal) return;
    createProfileModal.classList.remove('open');
    document.body.style.overflow = '';
  }

  function setCreateProfileStatus(message, type = 'error') {
    if (!createProfileStatus || !createProfileStatusText) return;
    createProfileStatus.className = `cp-status ${type} show`;
    createProfileStatusText.textContent = message;
  }

  function clearCreateProfileStatus() {
    if (!createProfileStatus || !createProfileStatusText) return;
    createProfileStatus.className = 'cp-status';
    createProfileStatusText.textContent = '';
  }

  function previewCreateProfileImage(file) {
    if (!file || !createProfileImage || !createProfileInitials) return;
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!allowed.includes(file.type)) {
      setCreateProfileStatus('Only JPG, PNG, or WEBP images are allowed.');
      return;
    }
    if (file.size > 2 * 1024 * 1024) {
      setCreateProfileStatus('Profile picture must be 2MB or smaller.');
      return;
    }

    clearCreateProfileStatus();
    createProfileSelectedFile = file;
    const reader = new FileReader();
    reader.onload = e => {
      createProfileImage.src = e.target.result;
      createProfileImage.style.display = 'block';
      createProfileInitials.style.display = 'none';
      if (createProfileDropTitle) createProfileDropTitle.textContent = file.name;
      if (createProfileDropSub) createProfileDropSub.textContent = 'Click or drag another image to replace this picture.';
    };
    reader.readAsDataURL(file);
  }

  openCreateProfileModal?.addEventListener('click', openCreateProfile);
  closeCreateProfileModal?.addEventListener('click', closeCreateProfile);
  cancelCreateProfileModal?.addEventListener('click', closeCreateProfile);
  createProfileModal?.addEventListener('click', e => { if (e.target === createProfileModal) closeCreateProfile(); });
  createProfileNameInput?.addEventListener('input', () => {
    updateCreateProfilePreview();
    clearCreateProfileStatus();
  });
  createProfileFileInput?.addEventListener('change', e => previewCreateProfileImage(e.target.files?.[0]));
  createProfileDropzone?.addEventListener('click', () => createProfileFileInput?.click());
  createProfileDropzone?.addEventListener('dragover', e => {
    e.preventDefault();
    createProfileDropzone.classList.add('is-active');
  });
  createProfileDropzone?.addEventListener('dragleave', () => {
    createProfileDropzone.classList.remove('is-active');
  });
  createProfileDropzone?.addEventListener('drop', e => {
    e.preventDefault();
    createProfileDropzone.classList.remove('is-active');
    previewCreateProfileImage(e.dataTransfer?.files?.[0]);
  });
  toggleCreateProfilePw?.addEventListener('click', () => {
    if (!createProfilePwInput) return;
    const isPassword = createProfilePwInput.type === 'password';
    createProfilePwInput.type = isPassword ? 'text' : 'password';
    toggleCreateProfilePw.innerHTML = isPassword
      ? `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
      : `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
  });
  submitCreateProfileBtn?.addEventListener('click', async () => {
    const name = createProfileNameInput?.value?.trim() || '';
    const password = createProfilePwInput?.value || '';

    clearCreateProfileStatus();

    if (!name) {
      setCreateProfileStatus('Please enter the profile name.');
      return;
    }
    if (password.length < 8) {
      setCreateProfileStatus('Password must be at least 8 characters.');
      return;
    }
    if (!createProfileRoute?.value) {
      setCreateProfileStatus('Create profile route is missing.');
      return;
    }

    submitCreateProfileBtn.disabled = true;
    submitCreateProfileBtn.textContent = 'Creating...';

    try {
      const formData = new FormData();
      formData.append('name', name);
      formData.append('password', password);
      if (createProfileSelectedFile) {
        formData.append('avatar', createProfileSelectedFile);
      }

      const response = await fetch(createProfileRoute.value, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData,
      });

      const contentType = response.headers.get('content-type') || '';
      const data = contentType.includes('application/json')
        ? await response.json()
        : { success: false, message: await response.text() };

      if (!response.ok || !data.success) {
        let message = data.message || 'Failed to create profile.';
        if (typeof message === 'string' && message.includes('<!DOCTYPE')) {
          message = 'The server returned an unexpected page instead of a JSON response.';
        }
        setCreateProfileStatus(message);
        return;
      }

      setCreateProfileStatus(data.message || 'Profile created successfully.', 'success');
      setTimeout(() => {
        window.location.href = '{{ url('/profile/select') }}';
      }, 900);
    } catch (error) {
      setCreateProfileStatus('Something went wrong while creating the profile.');
    } finally {
      submitCreateProfileBtn.disabled = false;
      submitCreateProfileBtn.textContent = 'Create Profile';
    }
  });

  /* ══════════════════════════════════════
     LOGOUT MODAL
  ══════════════════════════════════════ */
  const logoutBtn   = document.getElementById('logoutBtn');
  const logoutModal = document.getElementById('logoutModal');
  const cancelLogout= document.getElementById('cancelLogout');

  logoutBtn?.addEventListener('click', () => logoutModal.classList.add('open'));
  cancelLogout?.addEventListener('click', () => logoutModal.classList.remove('open'));
  logoutModal?.addEventListener('click', e => { if(e.target === logoutModal) logoutModal.classList.remove('open'); });

  /* ══════════════════════════════════════
     STATUS DETAIL MODAL
  ══════════════════════════════════════ */
  const statusDetailModal   = document.getElementById('statusDetailModal');
  const closeStatusModal    = document.getElementById('closeStatusModal');
  const closeStatusModalBtn = document.getElementById('closeStatusModalBtn');

  function openStatusModal() {
    statusDetailModal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeStatusDetailModal() {
    statusDetailModal.classList.remove('open');
    document.body.style.overflow = '';
  }

  window.switchSmTab = function(key, btn) {
    document.querySelectorAll('.smt-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.smt-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('smt-panel-' + key)?.classList.add('active');
    btn.classList.add('active');
  };

  document.querySelectorAll('.panel-card .status-row').forEach(row => {
    row.addEventListener('click', openStatusModal);
  });

  closeStatusModal?.addEventListener('click', closeStatusDetailModal);
  closeStatusModalBtn?.addEventListener('click', closeStatusDetailModal);
  statusDetailModal?.addEventListener('click', e => {
    if (e.target === statusDetailModal) closeStatusDetailModal();
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      closeHowto();
      closeTodo();
      closeCreateProfile();
      closeStatusDetailModal();
      logoutModal.classList.remove('open');
      notifDropdown.classList.remove('open');
      closeMobileSidebar();
      document.body.style.overflow = '';
    }
  });

  document.getElementById('logoutForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    logoutModal.classList.remove('open');
    setTimeout(() => window.location.href = '/', 200);
  });

  /* ══════════════════════════════════════
     IFO BAR ANIMATIONS
  ══════════════════════════════════════ */
  function animateIFOBars(maxTotal) {
    document.querySelectorAll('.ifo-row').forEach(row => {
      if (row.style.display === 'none') return;
      const keys   = ['patent','utility','design','tm','copyright'];
      const colors = {'patent':'c-patent','utility':'c-utility','design':'c-design','tm':'c-tm','copyright':'c-copyright'};
      const wrap = row.querySelector('.ifo-bar-wrap');
      if (!wrap) return;
      wrap.innerHTML = '';
      keys.forEach(k => {
        const val = parseInt(row.dataset[k] || 0);
        if (val <= 0) return;
        const seg = document.createElement('div');
        seg.className = 'ifo-seg ' + colors[k];
        seg.style.width = '0%';
        wrap.appendChild(seg);
        setTimeout(() => {
          seg.style.width = (maxTotal > 0 ? (val / maxTotal) * 100 : 0) + '%';
        }, 160);
      });
    });
  }

  window.filterIFO = function(btn, mode) {
    if (btn.tagName === 'BUTTON') {
      document.querySelectorAll('.ifo-yr-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('ifoYearSelect').selectedIndex = 0;
    } else if (btn.tagName === 'SELECT') {
      document.querySelectorAll('.ifo-yr-btn').forEach(b => b.classList.remove('active'));
    }

    const rows     = [...document.querySelectorAll('.ifo-row')];
    const allYears = rows.map(r => parseInt(r.dataset.year)).sort((a, b) => a - b);
    const visibleYears = mode === 'all'
      ? new Set(allYears)
      : (mode === 'year' && btn.value)
        ? new Set([parseInt(btn.value)])
        : new Set(allYears.slice(-parseInt(mode)));

    let newMax = 0, newGrand = 0;
    const totals = { patent: 0, utility: 0, design: 0, tm: 0, copyright: 0 };

    rows.forEach(row => {
      const yr = parseInt(row.dataset.year);
      if (visibleYears.has(yr)) {
        row.style.display = '';
        const t = parseInt(row.dataset.total || 0);
        if (t > newMax) newMax = t;
        newGrand        += t;
        totals.patent    += parseInt(row.dataset.patent    || 0);
        totals.utility   += parseInt(row.dataset.utility   || 0);
        totals.design    += parseInt(row.dataset.design    || 0);
        totals.tm        += parseInt(row.dataset.tm        || 0);
        totals.copyright += parseInt(row.dataset.copyright || 0);
      } else {
        row.style.display = 'none';
      }
    });

    rows.forEach(row => {
      if (row.style.display === 'none') return;
      const isPeak = parseInt(row.dataset.total || 0) === newMax;
      row.classList.toggle('peak', isPeak);
      const yearEl = row.querySelector('.ifo-year');
      if (yearEl) {
        const existingDot = yearEl.querySelector('span');
        if (existingDot) existingDot.remove();
        if (isPeak) {
          const dot = document.createElement('span');
          dot.style.cssText = 'display:inline-block;width:5px;height:5px;border-radius:50%;background:var(--gold);margin-left:4px;vertical-align:middle;';
          yearEl.appendChild(dot);
        }
      }
    });

    const gtEl = document.getElementById('ifoGrandTotal');
    if (gtEl) gtEl.textContent = newGrand;
    const chips = document.querySelectorAll('.ifo-footer-chip');
    const vals  = [totals.patent, totals.utility, totals.design, totals.tm, totals.copyright];
    chips.forEach((chip, i) => { chip.textContent = vals[i]; });
    const ftTotal = document.querySelector('.ifo-footer-total');
    if (ftTotal) ftTotal.textContent = newGrand;
    animateIFOBars(newMax);
  };

  const ifoCard = document.querySelector('.ifo-card');
  if (ifoCard) {
    const ifoObs = new IntersectionObserver(entries => {
      if (entries[0].isIntersecting) {
        const rows   = [...document.querySelectorAll('.ifo-row')];
        const maxTot = rows.reduce((mx, r) => Math.max(mx, parseInt(r.dataset.total || 0)), 0);
        animateIFOBars(maxTot);
        ifoObs.disconnect();
      }
    }, { threshold: 0.15 });
    ifoObs.observe(ifoCard);
  }

  document.querySelectorAll('.progress-fill').forEach(el => {
    const target = el.style.width;
    el.style.width = '0%';
    setTimeout(() => el.style.width = target, 400);
  });

  /* ── Maintenance Banner Countdown ── */
  @if(!empty($scheduledAt))
  (function() {
    const target  = new Date('{{ $scheduledAt }}');
    const valEl   = document.getElementById('maintCountdownVal');
    const banner  = document.getElementById('maintBanner');
    const dismiss = document.getElementById('maintDismiss');

    if (sessionStorage.getItem('kttm_maint_dismissed') === '{{ $scheduledAt }}') {
      if (banner) {
        banner.style.display = 'none';
        document.body.classList.remove('has-maint-banner');
      }
      return;
    }

    function updateMaintCountdown() {
      if (!valEl) return;
      const diff = target - new Date();
      if (diff <= 0) {
        valEl.textContent = 'Starting now...';
        return;
      }
      const d   = Math.floor(diff / 86400000);
      const h   = Math.floor((diff % 86400000) / 3600000);
      const m   = Math.floor((diff % 3600000) / 60000);
      const s   = Math.floor((diff % 60000) / 1000);
      const pad = n => String(n).padStart(2, '0');
      valEl.textContent = (d > 0 ? d + 'd ' : '') + pad(h) + ':' + pad(m) + ':' + pad(s);
    }

    updateMaintCountdown();
    setInterval(updateMaintCountdown, 1000);

    dismiss?.addEventListener('click', () => {
      banner.classList.add('hiding');
      sessionStorage.setItem('kttm_maint_dismissed', '{{ $scheduledAt }}');
      setTimeout(() => {
        banner.style.display = 'none';
        document.body.classList.remove('has-maint-banner');
      }, 320);
    });
  })();
  @endif

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
    document.body.style.overflow = 'hidden';
  }

  function closeMobileSidebar() {
    mainSidebar?.classList.remove('mobile-open');
    sidebarBackdrop?.classList.remove('open');
    hamburgerBtn?.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
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

  // Close sidebar on resize back to desktop
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeMobileSidebar();
  });

})();
</script>
</body>
</html>