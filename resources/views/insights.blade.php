<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Insights</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon:       #A52C30;
      --maroon2:      #7E1F23;
      --maroon3:      #C1363A;
      --maroon-light: rgba(165,44,48,0.10);
      --gold:         #F0C860;
      --gold2:        #E8B857;
      --ink:          #0F172A;
      --ink2:         #1e293b;
      --muted:        #64748B;
      --line:         rgba(15,23,42,.08);
      --card:         #FFFFFF;
      --sidebar-w:    72px;
      --bg:           #F1F4F9;
      --chart-bg:     #FAFBFD;
      --pad-x:        clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max:    1440px;
      --topbar-h:     72px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
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

    /* ══════════ SIDEBAR ══════════ */
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
      font-family: 'Plus Jakarta Sans', sans-serif;
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
    .nav-item:hover  { background: rgba(255,255,255,.12); color: #fff; }
    .nav-item.active { background: rgba(255,255,255,.18); color: #fff; box-shadow: 0 4px 16px rgba(0,0,0,.15); }
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
      letter-spacing: .04em; z-index: 999;
    }
    .nav-item:hover .nav-tooltip { opacity: 1; }
    .sidebar-bottom { display: flex; flex-direction: column; align-items: center; gap: 6px; }

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
    .sidebar-backdrop {
      display: none;
      position: fixed; inset: 0; z-index: 49;
      background: rgba(15,23,42,.45);
      backdrop-filter: blur(3px);
      -webkit-tap-highlight-color: transparent;
    }
    .sidebar-backdrop.open { display: block; }

    /* ══════════ MAIN ══════════ */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ══════════ TOPBAR ══════════ */
    .topbar {
      min-height: var(--topbar-h);
      background: var(--card);
      border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 10px var(--pad-x);
      position: sticky; top: 0; z-index: 40;
      box-shadow: 0 2px 16px rgba(15,23,42,.05);
    }
    .topbar-left {
      display: flex; align-items: center; gap: 12px;
      min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; }
    .topbar-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: var(--maroon-light); color: var(--maroon);
      border-radius: 8px; padding: 4px 10px;
      font-size: clamp(0.6rem, 0.1vw + 0.58rem, 0.68rem);
      font-weight: 700; letter-spacing: .06em;
      text-transform: uppercase; font-family: 'DM Mono', monospace;
      flex: 0 1 auto;
      max-width: 100%;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .page-title {
      font-size: clamp(0.95rem, 0.4vw + 0.85rem, 1.15rem);
      font-weight: 800; letter-spacing: -.3px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-sub {
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted); font-weight: 500;
      overflow-wrap: anywhere;
    }
    .topbar-right {
      display: flex; align-items: center; justify-content: flex-end;
      flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
    }
    .btn-primary {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 8px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: 10px clamp(12px, 2.5vw, 20px);
      border-radius: 12px; text-decoration: none;
      box-shadow: 0 6px 18px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(165,44,48,.35); }
    .btn-outline {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 7px;
      background: var(--card); color: var(--muted);
      border: 1.5px solid var(--line); cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 600;
      padding: 9px clamp(10px, 2vw, 18px);
      border-radius: 11px; text-decoration: none;
      transition: background .18s, border-color .18s, color .18s;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-outline:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .avatar {
      width: 40px; height: 40px; border-radius: 12px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.85rem; color: #2a1a0b; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ══════════ CONTENT ══════════ */
    .content {
      padding: clamp(14px, 2.5vw, 24px) var(--pad-x);
      flex: 1;
      width: 100%;
      max-width: var(--shell-max);
      margin: 0 auto;
      box-sizing: border-box;
      background: var(--bg) url('{{ asset("images/abstractBGIMAGE12.png") }}') no-repeat right center;
      background-size: cover;
    }

    /* ══════════ GLOBAL FILTER BAR ══════════ */
    .global-filters {
      display: flex; align-items: center; gap: 8px;
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px 16px 0 0;
      padding: 8px 12px;
      margin-bottom: 0;
      box-shadow: none;
      flex-wrap: wrap;
      border-bottom: 1px solid var(--line);
    }
    .gf-label {
      font-family: 'DM Mono', monospace;
      font-size: 0.6rem; font-weight: 700;
      letter-spacing: .14em; text-transform: uppercase;
      color: var(--muted); flex-shrink: 0; margin-right: 4px;
    }
    .gf-sep { width: 1px; height: 22px; background: var(--line); flex-shrink: 0; }
    .filter-select {
      padding: 5px 8px; border: 1.5px solid var(--line);
      background: var(--bg); border-radius: 8px;
      font-family: inherit;
      font-size: clamp(0.66rem, 0.12vw + 0.63rem, 0.72rem);
      font-weight: 600;
      color: var(--ink); outline: none; cursor: pointer;
      transition: border-color .18s;
      min-width: 0;
      max-width: 100%;
      flex: 1 1 7rem;
    }
    .filter-select:focus { border-color: var(--maroon); }
    .gf-reset {
      margin-left: auto;
      display: inline-flex; align-items: center; justify-content: center;
      gap: 5px;
      font-size: clamp(0.66rem, 0.12vw + 0.63rem, 0.72rem);
      font-weight: 700; color: var(--muted);
      background: none; border: 1.5px solid var(--line);
      border-radius: 8px; padding: 5px 10px; cursor: pointer;
      font-family: inherit; transition: all .15s;
      flex: 0 1 auto;
      max-width: 100%;
      white-space: nowrap;
    }
    .gf-reset:hover { border-color: var(--maroon); color: var(--maroon); background: var(--maroon-light); }

    /* ══════════ STICKY FILTER DOCK ══════════ */
    .sticky-dock {
      position: sticky;
      top: var(--topbar-h);
      z-index: 35;
      margin: 0 calc(-1 * var(--pad-x)) 20px;
      padding: 0 var(--pad-x);
      background: var(--bg);
      /* shadow only when stuck — driven by JS .is-stuck class */
      transition: box-shadow .2s;
    }
    .sticky-dock.is-stuck {
      box-shadow: 0 4px 24px rgba(15,23,42,.10);
    }
    /* When stuck, round corners become square on sides so it bleeds cleanly */
    .sticky-dock.is-stuck .global-filters,
    .sticky-dock.is-stuck .tab-nav {
      border-radius: 0;
      border-left: none;
      border-right: none;
    }
    .sticky-dock.is-stuck .global-filters {
      border-bottom: none;
      border-radius: 0;
    }
    .sticky-dock.is-stuck .tab-nav {
      border-top: none;
      border-radius: 0;
    }

    /* ══════════ TAB NAV ══════════ */
    .tab-nav {
      display: flex; flex-wrap: wrap;
      gap: 4px;
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 6px;
      margin-bottom: 0;
      box-shadow: 0 2px 10px rgba(15,23,42,.04);
    }
    .tab-btn {
      flex: 1 1 auto;
      display: inline-flex; align-items: center; justify-content: center;
      gap: 6px;
      padding: 8px 10px; border-radius: 11px;
      font-family: inherit;
      font-size: clamp(0.7rem, 0.12vw + 0.66rem, 0.78rem);
      font-weight: 700;
      border: none; cursor: pointer; background: none;
      color: var(--muted); transition: all .2s;
      white-space: nowrap;
      min-width: 0;
      max-width: 100%;
      box-sizing: border-box;
    }
    .tab-btn svg { flex-shrink: 0; }
    .tab-btn:hover { background: var(--bg); color: var(--ink); }
    .tab-btn.active {
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      color: #fff;
      box-shadow: 0 4px 16px rgba(165,44,48,.28);
    }
    .tab-btn .tab-count {
      background: rgba(255,255,255,.22); color: inherit;
      font-size: 0.65rem; font-weight: 800;
      padding: 2px 6px; border-radius: 20px;
      font-family: 'DM Mono', monospace;
    }
    .tab-btn:not(.active) .tab-count {
      background: var(--maroon-light); color: var(--maroon);
    }

    /* ══════════ TAB PANELS ══════════ */
    .tab-panel { display: none; }
    .tab-panel.active {
      display: block;
      animation: panelIn .28s cubic-bezier(.22,.61,.36,1) forwards;
    }
    @keyframes panelIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }

    /* ══════════ KPI STRIP ══════════ */
    .kpi-strip { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 20px; }
    .kpi-card {
      background: var(--card); border-radius: 18px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      padding: 20px 22px;
      position: relative; overflow: hidden;
      transition: transform .2s, box-shadow .2s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(15,23,42,.10); }
    .kpi-card::after {
      content: ''; position: absolute; top: 0; right: 0;
      width: 80px; height: 80px; border-radius: 50%;
      background: radial-gradient(circle, rgba(165,44,48,.06), transparent 70%);
      transform: translate(20px,-20px);
    }
    .kpi-icon {
      width: 38px; height: 38px; border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 14px; flex-shrink: 0;
    }
    .kpi-label { font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--muted); }
    .kpi-value {
      font-size: clamp(1.35rem, 3vw, 2.1rem);
      font-weight: 800; letter-spacing: -.04em;
      line-height: 1.1; margin-top: 4px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      overflow-wrap: anywhere;
    }
    .kpi-sub   { font-size: 0.7rem; color: var(--muted); margin-top: 5px; }
    .kpi-card.accent {
      background: linear-gradient(135deg, var(--maroon2) 0%, var(--maroon3) 100%);
      border-color: transparent;
      box-shadow: 0 8px 28px rgba(165,44,48,.22);
    }
    .kpi-card.accent::after { background: radial-gradient(circle, rgba(255,255,255,.1), transparent 70%); }
    .kpi-card.accent .kpi-label { color: rgba(255,255,255,.55); }
    .kpi-card.accent .kpi-value { color: #fff; }
    .kpi-card.accent .kpi-sub   { color: rgba(255,255,255,.55); }
    .kpi-card.gold-accent {
      background: linear-gradient(135deg, #c49a20, var(--gold2));
      border-color: transparent;
      box-shadow: 0 8px 28px rgba(240,200,96,.28);
    }
    .kpi-card.gold-accent .kpi-label { color: rgba(42,26,11,.5); }
    .kpi-card.gold-accent .kpi-value { color: #2a1a0b; }
    .kpi-card.gold-accent .kpi-sub   { color: rgba(42,26,11,.5); }

    /* ══════════ CHART CARDS ══════════ */
    .chart-card {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 14px rgba(15,23,42,.05);
      overflow: hidden;
      transition: box-shadow .2s;
    }
    .chart-card:hover { box-shadow: 0 8px 32px rgba(15,23,42,.09); }

    /* Header variants */
    .cch { /* chart card header base */
      padding: 16px 20px;
      border-bottom: 1px solid var(--line);
      display: flex; flex-wrap: wrap;
      align-items: flex-start; justify-content: space-between; gap: 12px;
    }
    .cch-plain   { background: var(--card); }
    .cch-accent  { background: linear-gradient(100deg, rgba(165,44,48,.06) 0%, rgba(240,200,96,.10) 100%); }
    .cch-dark    { background: linear-gradient(135deg, var(--maroon2) 0%, var(--maroon) 100%); }
    .cch-dark .chart-title  { color: #fff; }
    .cch-dark .chart-sub    { color: rgba(255,255,255,.55); }
    .cch-ink     { background: linear-gradient(135deg, var(--ink2) 0%, #2d3d55 100%); }
    .cch-ink .chart-title   { color: #fff; }
    .cch-ink .chart-sub     { color: rgba(255,255,255,.5); }

    .chart-title-wrap { display: flex; align-items: center; gap: 9px; min-width: 0; flex-wrap: wrap; }
    .cch > div:first-child { min-width: 0; flex: 1 1 auto; }
    .cch .dl-wrap { flex-shrink: 0; }
    .chart-chip {
      display: inline-flex; align-items: center;
      background: var(--maroon-light); color: var(--maroon);
      font-size: 0.6rem; font-weight: 800; letter-spacing: .1em;
      text-transform: uppercase; font-family: 'DM Mono', monospace;
      padding: 3px 8px; border-radius: 6px;
    }
    .cch-dark .chart-chip  { background: rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
    .cch-ink  .chart-chip  { background: rgba(255,255,255,.12); color: rgba(255,255,255,.8); }

    .chart-title {
      font-size: clamp(0.82rem, 0.2vw + 0.76rem, 0.9rem);
      font-weight: 800; letter-spacing: -.2px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      overflow-wrap: anywhere;
    }
    .chart-sub   { font-size: 0.68rem; color: var(--muted); margin-top: 3px; line-height: 1.4; }

    .chart-body  { padding: 18px 20px; }
    .chart-wrap  { position: relative; width: 100%; }
    .h-sm  { height: 200px; }
    .h-md  { height: 240px; }
    .h-lg  { height: 270px; }
    .h-xl  { height: 310px; }
    .h-2xl { height: 360px; }

    /* ══════════ DOWNLOAD DROPDOWN ══════════ */
    .dl-wrap { position: relative; display: inline-block; }
    .dl-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 12px; border-radius: 9px;
      font-family: inherit; font-weight: 700; font-size: 0.7rem;
      border: 1.5px solid var(--line); background: var(--bg);
      color: var(--muted); cursor: pointer; white-space: nowrap;
      transition: all .15s; flex-shrink: 0;
    }
    .dl-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .dl-btn.light { background: rgba(255,255,255,.14); border-color: rgba(255,255,255,.2); color: rgba(255,255,255,.85); }
    .dl-btn.light:hover { background: rgba(255,255,255,.25); border-color: rgba(255,255,255,.4); color: #fff; }
    .dl-menu {
      position: absolute; top: calc(100% + 6px); right: 0; z-index: 200;
      min-width: 160px; background: var(--card);
      border: 1.5px solid var(--line); border-radius: 14px;
      box-shadow: 0 14px 40px rgba(15,23,42,.14);
      display: none; flex-direction: column; overflow: hidden;
    }
    .dl-menu.open { display: flex; }
    .dl-menu button {
      padding: 10px 14px; border: none; background: var(--card);
      text-align: left; font-family: inherit; font-size: 0.78rem;
      font-weight: 600; color: var(--ink); cursor: pointer;
      border-bottom: 1px solid var(--line); transition: background .12s;
      white-space: nowrap;
    }
    .dl-menu button:last-child { border-bottom: none; }
    .dl-menu button:hover { background: var(--maroon-light); color: var(--maroon); }

    /* ══════════ LAYOUT GRIDS ══════════ */
    .grid-8-4   { display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 16px; }
    .grid-6-6   { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 16px; }
    .grid-4-4-4 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
    .grid-4-8   { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 2fr); gap: 16px; }
    .grid-7-5   { display: grid; grid-template-columns: minmax(0, 7fr) minmax(0, 5fr); gap: 16px; }
    .grid-full  { display: grid; grid-template-columns: minmax(0, 1fr); gap: 16px; }
    .section    { margin-bottom: 16px; }

    /* ══════════ DIVIDER LABEL ══════════ */
    .section-divider {
      display: flex; align-items: center; gap: 12px;
      margin: 4px 0 14px;
    }
    .section-divider span {
      font-family: 'DM Mono', monospace;
      font-size: 0.6rem; font-weight: 700; letter-spacing: .16em;
      text-transform: uppercase; color: var(--muted);
      white-space: nowrap;
    }
    .section-divider::before, .section-divider::after {
      content: ''; flex: 1; height: 1px; background: var(--line);
    }

    /* ══════════ CHART NOTE ══════════ */
    .chart-note {
      margin-top: 10px; padding: 8px 12px;
      border-radius: 10px; background: var(--bg);
      border: 1px solid var(--line);
      font-size: .68rem; color: var(--muted); line-height: 1.5;
    }

    /* ══════════ MODAL ══════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: var(--card); border-radius: 20px;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      width: min(380px, calc(100vw - 2rem));
      max-width: 100%;
      animation: modalIn .3s cubic-bezier(.17,.67,.35,1.1);
    }
    @keyframes modalIn { from { opacity:0; transform:translateY(20px) scale(.97); } to { opacity:1; transform:none; } }

    /* ══════════ ANIMATIONS ══════════ */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
    .fade-up { animation: fadeUp .4s forwards; }
    .fade-up-1 { animation: fadeUp .4s .06s both; }
    .fade-up-2 { animation: fadeUp .4s .12s both; }
    .fade-up-3 { animation: fadeUp .4s .18s both; }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 999px; }

    @media (max-width: 1100px) {
      .grid-8-4, .grid-4-4-4, .grid-4-8, .grid-7-5, .grid-6-6 { grid-template-columns: minmax(0, 1fr); }
      .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
      :root { --topbar-h: 60px; }
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
      .sidebar-nav { width: 100%; align-items: flex-start; }
      .nav-item {
        width: 100%; border-radius: 12px;
        justify-content: flex-start;
        padding: 0 12px; gap: 12px;
      }
      .nav-tooltip {
        position: static; opacity: 1 !important; transform: none;
        background: none; color: rgba(255,255,255,.8);
        font-size: 0.78rem; font-weight: 600;
        padding: 0; border-radius: 0; pointer-events: auto;
        letter-spacing: .01em; white-space: nowrap;
      }
      .sidebar-bottom { width: 100%; align-items: flex-start; }
      .main-wrap { margin-left: 0; }
      .hamburger-btn { display: flex; }
      .topbar-left { flex-wrap: wrap; }
      .page-sub { display: none; }
      .howto-footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
      }
      .howto-footer .btn-primary-sm { margin-left: 0; }
      .howto-box { width: min(96vw, 100% - 2rem); }
    }
    @media (max-width: 640px) {
      .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .tab-btn span.tab-label { display: none; }
      .tab-btn { flex: 1 1 calc(50% - 4px); min-width: calc(50% - 4px); }
      .global-filters .gf-reset { margin-left: 0; width: 100%; justify-content: center; }
      .btn-howto-label,
      .btn-records-label { display: none; }
    }
    @media (max-width: 480px) {
      .kpi-strip { grid-template-columns: minmax(0, 1fr); }
      .tab-btn {
        flex: 1 1 100%;
        min-width: 0;
        white-space: normal;
        text-align: center;
      }
      .tab-btn span.tab-label { display: inline; }
      .logout-modal-actions { flex-direction: column; }
      .logout-modal-actions .btn-outline,
      .logout-modal-actions form { flex: 0 0 auto; width: 100%; }
    }
    /* ══════════ HOW TO USE BUTTON ══════════ */
    .btn-howto {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 7px;
      background: var(--bg); border: 1.5px solid var(--line);
      color: var(--muted); cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 16px);
      border-radius: 12px; transition: all .18s;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-howto:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    /* ══════════ HOW TO USE MODAL ══════════ */
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
    .howto-step-desc  { font-size: 0.76rem; color: var(--muted); line-height: 1.6; font-weight: 500; overflow-wrap: anywhere; }
    .howto-step-tag {
      display: inline-flex; align-items: center; gap: 4px; margin-top: 7px;
      font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700;
      color: var(--maroon); background: var(--maroon-light);
      border: 1px solid rgba(165,44,48,.18); padding: 2px 9px; border-radius: 20px;
      letter-spacing: .04em; text-transform: uppercase;
    }
    .howto-footer {
      padding: 16px 28px; border-top: 1px solid var(--line); flex-shrink: 0;
      display: flex; flex-wrap: wrap;
      align-items: center; justify-content: space-between; gap: 12px;
      background: linear-gradient(90deg, rgba(165,44,48,.03), rgba(240,200,96,.03));
    }
    .howto-footer-note {
      flex: 1 1 12rem; min-width: 0;
      font-size: 0.72rem; color: var(--muted); font-weight: 500;
      overflow-wrap: anywhere;
    }
    .howto-footer .btn-primary-sm { flex: 0 1 auto; margin-left: auto; }
    .howto-footer-note strong { color: var(--maroon); }
    .btn-primary-sm {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 6px;
      padding: 9px 18px; border-radius: 10px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.12vw + 0.68rem, 0.76rem);
      font-weight: 700;
      box-shadow: 0 4px 14px rgba(165,44,48,.28); transition: transform .18s, box-shadow .18s;
      max-width: 100%;
      flex: 0 1 auto;
      white-space: nowrap;
    }
    .btn-primary-sm:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(165,44,48,.35); }

    .logout-modal-actions {
      display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px; align-items: stretch;
    }
    .logout-modal-actions .btn-outline {
      flex: 1 1 120px; justify-content: center; min-width: 0;
    }
    .logout-modal-actions form {
      flex: 1 1 140px; min-width: 0; display: flex;
    }
    .logout-modal-actions form .btn-primary {
      width: 100%; justify-content: center;
    }

  </style>
</head>

<body>

@php
  use App\Models\IpRecord;
  use Illuminate\Support\Facades\DB;

  $user = $user ?? (object)['name' => 'KTTM User', 'role' => 'Staff'];

  if(empty($allRecords)){
    $allRecords = IpRecord::all()->map(function($r){
      return [
        'id'         => $r->record_id,
        'title'      => $r->ip_title,
        'category'   => $r->category,
        'owner'      => $r->owner_inventor_summary,
        'campus'     => $r->campus,
        'status'     => $r->status,
        'registered' => $r->date_registered_deposited,
        'ipophl_id'  => $r->ipophl_id,
        'gdrive_link'=> $r->gdrive_link,
      ];
    })->toArray();
  }

  $total = max(1, count($allRecords));
  $norm  = fn($v) => (is_string($v) ? trim($v) : $v) === null || (is_string($v) ? trim($v) : $v) === '' ? '—' : (is_string($v) ? trim($v) : $v);

  $statusCounts = collect($allRecords)->map(fn($r)=>$norm($r['status']??null))->countBy()->sortDesc();
  $typeCounts   = collect($allRecords)->map(fn($r)=>$norm($r['category']??null))->countBy()->sortDesc();
  $campusCounts = collect($allRecords)->map(fn($r)=>$norm($r['campus']??null))->countBy()->sortDesc();

  $distinctIpTypes  = IpRecord::distinct('category')->whereNotNull('category')->pluck('category')->filter(fn($v)=>trim($v)!='')->sort()->values();
  $distinctStatuses = IpRecord::distinct('status')->whereNotNull('status')->pluck('status')->filter(fn($v)=>trim($v)!='')->sort()->values();
  $distinctCampuses = IpRecord::distinct('campus')->whereNotNull('campus')->pluck('campus')->filter(fn($v)=>trim($v)!='')->sort()->values();

  $byMonthRegistered = collect($allRecords)->map(fn($r)=>isset($r['registered'])?\Carbon\Carbon::parse($r['registered'])->format('Y-m'):null)->filter()->countBy()->sortKeys();
  $byYearRegistered  = collect($allRecords)->map(fn($r)=>isset($r['registered'])?\Carbon\Carbon::parse($r['registered'])->format('Y'):null)->filter()->countBy()->sortKeys();

  $campusLabels   = $campusCounts->keys()->filter(fn($k)=>$k!=='—')->values()->take(6);
  $categoryLabels = $typeCounts->keys()->filter(fn($k)=>$k!=='—')->values()->take(6);

  $catCampusMatrix = [];
  foreach($categoryLabels as $cat){
    $catCampusMatrix[$cat] = [];
    foreach($campusLabels as $camp){
      $catCampusMatrix[$cat][$camp] = collect($allRecords)->filter(fn($r)=>$norm($r['category']??null)===$cat && $norm($r['campus']??null)===$camp)->count();
    }
  }

  $statusTop    = $statusCounts->take(8);
  $statusOthers = $statusCounts->slice(8)->sum();
  if($statusOthers>0) $statusTop = $statusTop->merge(['Others'=>$statusOthers]);

  $typeTop    = $typeCounts->take(8);
  $typeOthers = $typeCounts->slice(8)->sum();
  if($typeOthers>0) $typeTop = $typeTop->merge(['Others'=>$typeOthers]);

  $campusTop    = $campusCounts->take(6);
  $campusOthers = $campusCounts->slice(6)->sum();
  if($campusOthers>0) $campusTop = $campusTop->merge(['Others'=>$campusOthers]);

  $rawGender = DB::table('ip_contributors')
    ->join('ip_records','ip_contributors.record_id','=','ip_records.record_id')
    ->selectRaw("COALESCE(NULLIF(TRIM(ip_contributors.gender),''),'Unknown') as gender_clean, count(*) as cnt")
    ->groupBy('gender_clean')->pluck('cnt','gender_clean');

  $genderCounts = collect(['Male'=>0,'Female'=>0,'Other'=>0,'Unknown'=>0]);
  foreach($rawGender as $g=>$cnt){ $r=ucfirst(strtolower($g)); if(!in_array($r,['Male','Female','Other','Unknown'])) $r='Other'; $genderCounts[$r]+=$cnt; }

  $rows = DB::table('ip_contributors')
    ->join('ip_records','ip_contributors.record_id','=','ip_records.record_id')
    ->selectRaw("COALESCE(NULLIF(TRIM(ip_records.category),''),'—') as category_clean, COALESCE(NULLIF(TRIM(ip_contributors.gender),''),'Unknown') as gender_clean, count(*) as cnt")
    ->groupBy('ip_records.category','ip_contributors.gender')->get();

  $grouped = [];
  foreach($rows as $r){ $cat=$r->category_clean; $gender=ucfirst(strtolower($r->gender_clean)); if(!in_array($gender,['Male','Female','Other','Unknown'])) $gender='Other'; if(!isset($grouped[$cat])) $grouped[$cat]=['category'=>$cat,'Male'=>0,'Female'=>0,'Other'=>0,'Unknown'=>0]; $grouped[$cat][$gender]+=(int)$r->cnt; }

  $genderByCategory=[];
  foreach($categoryLabels as $cat){ if(isset($grouped[$cat])) $genderByCategory[]=$grouped[$cat]; }
  foreach($grouped as $cat=>$vals){ if(!in_array($cat,$categoryLabels->toArray())) $genderByCategory[]=$vals; }

  $genderByCategoryRaw = DB::table('ip_contributors')
    ->join('ip_records','ip_contributors.record_id','=','ip_records.record_id')
    ->selectRaw("COALESCE(NULLIF(TRIM(ip_records.category),''),'—') as category, COALESCE(NULLIF(TRIM(ip_records.status),''),'') as status, COALESCE(NULLIF(TRIM(ip_records.campus),''),'') as campus, COALESCE(NULLIF(TRIM(ip_contributors.gender),''),'Unknown') as gender")
    ->get()->map(function($r){ $gender=ucfirst(strtolower($r->gender)); if(!in_array($gender,['Male','Female','Other','Unknown'])) $gender='Other'; return ['category'=>$r->category,'type'=>$r->category,'status'=>$r->status,'campus'=>$r->campus,'role'=>$gender]; });

  // ── Gender × Campus (new chart) ──────────────────────────────────────────
  // Use fully-qualified table.column in GROUP BY to avoid PostgreSQL ambiguous
  // column error — the COALESCE aliases clash with real column names after JOIN.
  $genderCampusRows = DB::table('ip_contributors')
    ->join('ip_records','ip_contributors.record_id','=','ip_records.record_id')
    ->selectRaw("COALESCE(NULLIF(TRIM(ip_records.campus),''),'Unknown') as campus_clean, COALESCE(NULLIF(TRIM(ip_contributors.gender),''),'Unknown') as gender_clean, count(*) as cnt")
    ->groupBy('ip_records.campus','ip_contributors.gender')->get();

  $genderCampusMap = [];
  foreach($genderCampusRows as $row){
    $camp = $row->campus_clean;
    $g    = ucfirst(strtolower($row->gender_clean));
    if(!in_array($g,['Male','Female','Other','Unknown'])) $g='Other';
    if(!isset($genderCampusMap[$camp])) $genderCampusMap[$camp]=['campus'=>$camp,'Male'=>0,'Female'=>0,'Other'=>0,'Unknown'=>0,'total'=>0];
    $genderCampusMap[$camp][$g]+=(int)$row->cnt;
    $genderCampusMap[$camp]['total']+=(int)$row->cnt;
  }
  // Sort by total contributors desc, take top 8 campuses
  usort($genderCampusMap, fn($a,$b)=>$b['total']<=>$a['total']);
  $genderCampusMap = array_slice($genderCampusMap, 0, 8);

  // Raw rows for client-side filter reactivity (gender × campus)
  // All columns fully qualified — ip_records owns campus/status/category,
  // ip_contributors owns gender. No GROUP BY needed here (one row per contributor).
  $genderByCampusRaw = DB::table('ip_contributors')
    ->join('ip_records','ip_contributors.record_id','=','ip_records.record_id')
    ->selectRaw("COALESCE(NULLIF(TRIM(ip_records.campus),''),'Unknown') as campus_clean, COALESCE(NULLIF(TRIM(ip_records.status),''),'') as status_clean, COALESCE(NULLIF(TRIM(ip_records.category),''),'') as category_clean, COALESCE(NULLIF(TRIM(ip_contributors.gender),''),'Unknown') as gender_clean")
    ->get()->map(function($r){ $g=ucfirst(strtolower($r->gender_clean)); if(!in_array($g,['Male','Female','Other','Unknown'])) $g='Other'; return ['campus'=>$r->campus_clean,'status'=>$r->status_clean,'category'=>$r->category_clean,'gender'=>$g]; });

  $invMap=[];
  foreach($allRecords as $r){ $name=trim($r['owner']??'')?:'—'; $cat=trim($r['category']??$r['type']??'')?:'—'; if(!isset($invMap[$name])) $invMap[$name]=['Patent'=>0,'Utility Model'=>0,'Industrial Design'=>0,'Copyright'=>0,'total'=>0]; if(array_key_exists($cat,$invMap[$name])) $invMap[$name][$cat]++; $invMap[$name]['total']++; }
  $topInventors=[]; foreach(collect($invMap)->sortByDesc('total')->take(8) as $name=>$data){ $topInventors[]=array_merge(['name'=>$name],$data); }

  $initials = collect(explode(' ',$user->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');

  // Pull session data for sidebar avatar
  $sessionName        = session('user_name',         $user->name ?? 'KTTM User');
  $sessionRole        = session('user_role',         $user->role ?? 'Staff');
  $sessionColor       = session('user_avatar_color', '#A52C30');
  $sessionAvatarImage = session('user_avatar_image', null);
  $roleLabel          = match(strtolower($sessionRole)) {
    'admin'     => 'Admin',
    'developer' => 'Developer',
    default     => ucfirst($sessionRole),
  };

  $urlDashboard = url('/home');
  $urlRecords   = url('/records');
  $urlInsights  = url('/insights');
  $urlLogout    = url('/logout');

  // Extra KPI: most recent registration year activity
  $latestYear = $byYearRegistered->keys()->last() ?? '—';
  $latestYearCount = $byYearRegistered->last() ?? 0;
  $registeredCount = collect($allRecords)->filter(fn($r)=>!empty($r['registered']))->count();

  $js = [
    'total'       => count($allRecords),
    'allRecords'  => $allRecords,
    'filterStatuses'   => $statusCounts->keys()->filter(fn($k)=>$k!=='—')->values()->toArray(),
    'filterCategories' => $typeCounts->keys()->filter(fn($k)=>$k!=='—')->values()->toArray(),
    'filterCampuses'   => $campusCounts->keys()->filter(fn($k)=>$k!=='—')->values()->toArray(),
    'status'  => ['labels'=>$statusTop->keys()->values(),'values'=>$statusTop->values()],
    'types'   => ['labels'=>$typeTop->keys()->values(),'values'=>$typeTop->values()],
    'campus'  => ['labels'=>$campusTop->keys()->values(),'values'=>$campusTop->values()],
    'monthsRegistered' => ['labels'=>$byMonthRegistered->keys()->values(),'values'=>$byMonthRegistered->values()],
    'yearsRegistered'  => ['labels'=>$byYearRegistered->keys()->values(),'values'=>$byYearRegistered->values()],
    'gender'  => ['labels'=>$genderCounts->keys()->values(),'values'=>$genderCounts->values()],
    'catCampus' => ['campusLabels'=>$campusLabels,'categoryLabels'=>$categoryLabels,'matrix'=>$catCampusMatrix],
    'topInventors' => [
      'labels' => collect($topInventors)->pluck('name')->values(),
      'series' => [
        'Patent'           => collect($topInventors)->pluck('Patent')->values(),
        'Utility Model'    => collect($topInventors)->pluck('Utility Model')->values(),
        'Industrial Design'=> collect($topInventors)->pluck('Industrial Design')->values(),
        'Copyright'        => collect($topInventors)->pluck('Copyright')->values(),
      ],
      'total' => collect($topInventors)->pluck('total')->values(),
    ],
    'genderByCategory' => [
      'labels' => collect($genderByCategory)->pluck('category')->values(),
      'series' => [
        'Male'    => collect($genderByCategory)->pluck('Male')->values(),
        'Female'  => collect($genderByCategory)->pluck('Female')->values(),
        'Other'   => collect($genderByCategory)->pluck('Other')->values(),
        'Unknown' => collect($genderByCategory)->pluck('Unknown')->values(),
      ],
    ],
    'genderByCategoryRaw' => $genderByCategoryRaw,
    'genderByCampus' => [
      'labels' => collect($genderCampusMap)->pluck('campus')->values(),
      'series' => [
        'Male'    => collect($genderCampusMap)->pluck('Male')->values(),
        'Female'  => collect($genderCampusMap)->pluck('Female')->values(),
        'Other'   => collect($genderCampusMap)->pluck('Other')->values(),
        'Unknown' => collect($genderCampusMap)->pluck('Unknown')->values(),
      ],
    ],
    'genderByCampusRaw' => $genderByCampusRaw,
  ];
@endphp

{{-- ══════════════ SIDEBAR ══════════════ --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
<aside class="sidebar" id="mainSidebar" aria-label="Main navigation">
  {{-- User avatar — shows photo or initials --}}
  <div class="nav-item" style="margin-bottom:20px; width:42px; height:42px; border-radius:14px; {{ $sessionAvatarImage ? 'background:transparent;' : 'background: linear-gradient(135deg, var(--gold), var(--gold2));' }} font-weight:800; font-size:0.78rem; color:#2a1a0b; box-shadow:0 6px 18px rgba(240,200,96,.35); cursor:default; flex-shrink:0; overflow:hidden; padding:0;">
    @if($sessionAvatarImage)
      <img src="{{ asset('storage/avatars/' . $sessionAvatarImage) }}"
           alt="{{ $initials }}"
           style="width:42px;height:42px;object-fit:cover;border-radius:14px;display:block;">
    @else
      {{ $initials ?: 'KT' }}
    @endif
    <span class="nav-tooltip" style="min-width:140px;line-height:1.5;">
      {{ $sessionName }}<br>
      <span style="opacity:.65;font-weight:500;letter-spacing:.06em;text-transform:uppercase;font-size:.6rem;">{{ $roleLabel }}</span>
    </span>
  </div>

  {{-- Mobile-only: full name + role shown when sidebar is expanded --}}
  <div style="display:none;" class="sidebar-mobile-user">
    <div style="display:flex;align-items:center;gap:11px;padding:0 4px 20px;border-bottom:1px solid rgba(255,255,255,.12);margin-bottom:10px;width:100%;">
      <div style="width:38px;height:38px;border-radius:12px;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.78rem;color:#2a1a0b;{{ $sessionAvatarImage ? '' : 'background:linear-gradient(135deg,var(--gold),var(--gold2));' }}">
        @if($sessionAvatarImage)
          <img src="{{ asset('storage/avatars/' . $sessionAvatarImage) }}" alt="{{ $initials }}" style="width:38px;height:38px;object-fit:cover;border-radius:12px;display:block;">
        @else
          {{ $initials ?: 'KT' }}
        @endif
      </div>
      <div style="min-width:0;">
        <div style="font-size:0.84rem;font-weight:800;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $sessionName }}</div>
        <div style="font-size:0.62rem;font-weight:600;color:rgba(255,255,255,.55);text-transform:uppercase;letter-spacing:.08em;margin-top:1px;">{{ $roleLabel }}</div>
      </div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a href="{{ $urlDashboard }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
      <span class="nav-tooltip">Dashboard</span>
    </a>

    <a href="{{ $urlRecords }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <span class="nav-tooltip">Records</span>
    </a>

    <a href="{{ $urlInsights }}" class="nav-item active">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
      </svg>
      <span class="nav-tooltip">Insights</span>
    </a>

    

    <a href="{{ url('/calendar') }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="16" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
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
    <button id="logoutBtn" type="button" class="nav-item" style="background:none;border:none;cursor:pointer;">
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
      <button class="hamburger-btn" id="hamburgerBtn" type="button" aria-label="Open navigation menu" aria-expanded="false">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <line x1="3" y1="6"  x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
      <div class="topbar-titles">
        <div class="page-title">Insights</div>
        <div class="page-sub">Analytics &amp; reporting dashboard</div>
      </div>
      <div class="topbar-badge">
        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
        {{ count($allRecords) }} records
      </div>
    </div>
    <div class="topbar-right">
      <button id="howToUseBtn" type="button" class="btn-howto" title="How to Use">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span class="btn-howto-label">How to Use</span>
      </button>
      <a href="{{ $urlRecords }}" class="btn-outline" title="Open Records">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
        <span class="btn-records-label">Records</span>
      </a>
      
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

    {{-- KPI STRIP --}}
    <div class="kpi-strip fade-up">
      <div class="kpi-card accent">
        <div class="kpi-icon" style="background:rgba(255,255,255,.14);">
          <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.8)" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="kpi-label">Total Records</div>
        <div class="kpi-value">{{ count($allRecords) }}</div>
        <div class="kpi-sub">{{ $registeredCount }} with registered date</div>
      </div>
      <div class="kpi-card gold-accent">
        <div class="kpi-icon" style="background:rgba(42,26,11,.10);">
          <svg width="18" height="18" fill="none" stroke="rgba(42,26,11,.6)" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="kpi-label">Campuses</div>
        <div class="kpi-value">{{ $campusCounts->count() }}</div>
        <div class="kpi-sub">distinct institutions</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-icon" style="background:var(--maroon-light);">
          <svg width="18" height="18" fill="none" stroke="var(--maroon)" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div class="kpi-label">Top Category</div>
        <div class="kpi-value" style="font-size:1.2rem;color:var(--maroon);">{{ $typeCounts->keys()->first() ?? '—' }}</div>
        <div class="kpi-sub">{{ $typeCounts->values()->first() ? $typeCounts->values()->first().' records' : '—' }}</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-icon" style="background:rgba(99,102,241,.1);">
          <svg width="18" height="18" fill="none" stroke="rgba(99,102,241,.8)" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        </div>
        <div class="kpi-label">Latest Activity</div>
        <div class="kpi-value" style="font-size:1.5rem;color:var(--ink);">{{ $latestYear }}</div>
        <div class="kpi-sub">{{ $latestYearCount }} records that year</div>
      </div>
    </div>

    {{-- STICKY DOCK: filter bar + tab nav --}}
    <div class="sticky-dock fade-up-1" id="stickyDock">

    {{-- GLOBAL FILTER BAR --}}
    <div class="global-filters">
      <span class="gf-label">Filter all</span>
      <div class="gf-sep"></div>
      <select id="gf-type" class="filter-select">
        <option value="">All IP Types</option>
        @foreach($distinctIpTypes as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
      </select>
      <select id="gf-status" class="filter-select">
        <option value="">All Statuses</option>
        @foreach($distinctStatuses as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
      </select>
      <select id="gf-campus" class="filter-select">
        <option value="">All Campuses</option>
        @foreach($distinctCampuses as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
      </select>
      <div class="gf-sep"></div>
      <span class="gf-label">Date</span>
      <select id="gf-year" class="filter-select">
        <option value="">All Years</option>
        {{-- populated by JS from KTTM_DATA --}}
      </select>
      <select id="gf-month" class="filter-select">
        <option value="">All Months</option>
        <option value="01">January</option>
        <option value="02">February</option>
        <option value="03">March</option>
        <option value="04">April</option>
        <option value="05">May</option>
        <option value="06">June</option>
        <option value="07">July</option>
        <option value="08">August</option>
        <option value="09">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
      </select>
      <button class="gf-reset" id="gfResetBtn">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        Reset
      </button>
    </div>

    {{-- TAB NAV --}}
    <nav class="tab-nav">
      <button class="tab-btn active" data-tab="overview">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span class="tab-label">Overview</span>
        <span class="tab-count">3</span>
      </button>
      <button class="tab-btn" data-tab="time">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span class="tab-label">Over Time</span>
        <span class="tab-count">2</span>
      </button>
      <button class="tab-btn" data-tab="distribution">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.21 15.89A10 10 0 118 2.83"/><path d="M22 12A10 10 0 0012 2v10z"/></svg>
        <span class="tab-label">Distribution</span>
        <span class="tab-count">3</span>
      </button>
      <button class="tab-btn" data-tab="contributors">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        <span class="tab-label">Contributors</span>
        <span class="tab-count">3</span>
      </button>
    </nav>
    </div>{{-- /sticky-dock --}}
    <div style="height:20px"></div>

    {{-- ══ TAB: OVERVIEW ══ --}}
    <div class="tab-panel active" id="tab-overview">

      <div class="section-divider fade-up-3"><span>Status &amp; Categories</span></div>

      {{-- Row: Status (4) + Category (4) + Campus (4) --}}
      <div class="grid-4-4-4 section fade-up-3">

        {{-- Status Breakdown --}}
        <div class="chart-card">
          <div class="cch cch-dark">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Status Breakdown</div>
                <span class="chart-chip">Doughnut</span>
              </div>
              <div class="chart-sub">Current record statuses</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn light" data-toggle="dlMenu-status">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-status" class="dl-menu">
                <button data-dl-img="chartStatus" data-fn="status_breakdown.png">PNG Image</button>
                <button data-dl-csv="status" data-fn="status_breakdown.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartStatus" data-fn="status_breakdown.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-md"><canvas id="chartStatus"></canvas></div>
          </div>
        </div>

        {{-- Category / IP Type --}}
        <div class="chart-card">
          <div class="cch cch-accent">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">IP Categories</div>
                <span class="chart-chip">Bar</span>
              </div>
              <div class="chart-sub">Top 8 types + others</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-types">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-types" class="dl-menu">
                <button data-dl-img="chartTypes" data-fn="category_mix.png">PNG Image</button>
                <button data-dl-csv="types" data-fn="category_mix.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartTypes" data-fn="category_mix.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-md"><canvas id="chartTypes"></canvas></div>
          </div>
        </div>

        {{-- Gender Distribution --}}
        <div class="chart-card">
          <div class="cch cch-plain">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Gender Distribution</div>
                <span class="chart-chip">Doughnut</span>
              </div>
              <div class="chart-sub">From ip_contributors</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-gender">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-gender" class="dl-menu">
                <button data-dl-img="chartGender" data-fn="gender_distribution.png">PNG Image</button>
                <button data-dl-csv="gender" data-fn="gender_distribution.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartGender" data-fn="gender_distribution.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-md"><canvas id="chartGender"></canvas></div>
            <div class="chart-note">Counts contributors, not unique records.</div>
          </div>
        </div>

      </div>

      <div class="section-divider"><span>Campus &amp; Category Cross-View</span></div>

      {{-- Row: Campus (4) + Category by Campus (8) --}}
      <div class="grid-4-8 section">

        {{-- Campus Distribution --}}
        <div class="chart-card">
          <div class="cch cch-plain">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Campus Distribution</div>
                <span class="chart-chip">Horiz. Bar</span>
              </div>
              <div class="chart-sub">Top 6 campuses</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-campus">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-campus" class="dl-menu">
                <button data-dl-img="chartCampus" data-fn="campus_distribution.png">PNG Image</button>
                <button data-dl-csv="campus" data-fn="campus_distribution.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartCampus" data-fn="campus_distribution.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-lg"><canvas id="chartCampus"></canvas></div>
          </div>
        </div>

        {{-- Category by Campus --}}
        <div class="chart-card">
          <div class="cch cch-accent">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Category by Campus</div>
                <span class="chart-chip">Grouped Bar</span>
              </div>
              <div class="chart-sub">IP types across all campuses</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-catcampus">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-catcampus" class="dl-menu">
                <button data-dl-img="chartCatCampus" data-fn="category_by_campus.png">PNG Image</button>
                <button data-dl-csv="catCampus" data-fn="category_by_campus.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartCatCampus" data-fn="category_by_campus.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-lg"><canvas id="chartCatCampus"></canvas></div>
          </div>
        </div>

      </div>
    </div>

    {{-- ══ TAB: OVER TIME ══ --}}
    <div class="tab-panel" id="tab-time">

      <div class="section-divider"><span>Registration Timeline</span></div>

      {{-- Monthly Trend (full width) --}}
      <div class="section">
        <div class="chart-card">
          <div class="cch cch-ink">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Records per Month</div>
                <span class="chart-chip">Area</span>
              </div>
              <div class="chart-sub">Registration date timeline — all months</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn light" data-toggle="dlMenu-trend">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-trend" class="dl-menu">
                <button data-dl-img="chartTrend" data-fn="records_per_month.png">PNG Image</button>
                <button data-dl-csv="trend" data-fn="records_per_month.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartTrend" data-fn="records_per_month.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-2xl"><canvas id="chartTrend"></canvas></div>
          </div>
        </div>
      </div>

      <div class="section-divider"><span>Annual Breakdown</span></div>

      {{-- Per Year --}}
      <div class="section">
        <div class="chart-card">
          <div class="cch cch-accent">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Records per Year</div>
                <span class="chart-chip">Bar</span>
              </div>
              <div class="chart-sub">Annual registration counts</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-year">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-year" class="dl-menu">
                <button data-dl-img="chartYear" data-fn="records_per_year.png">PNG Image</button>
                <button data-dl-csv="yearsRegistered" data-fn="records_per_year.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartYear" data-fn="records_per_year.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-lg"><canvas id="chartYear"></canvas></div>
          </div>
        </div>
      </div>

    </div>

    {{-- ══ TAB: DISTRIBUTION ══ --}}
    <div class="tab-panel" id="tab-distribution">

      <div class="section-divider"><span>Status &amp; IP Types</span></div>

      <div class="grid-6-6 section">

        {{-- Status (large doughnut with legend) --}}
        <div class="chart-card">
          <div class="cch cch-dark">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Status Breakdown</div>
                <span class="chart-chip">Doughnut</span>
              </div>
              <div class="chart-sub">Top 8 statuses + others</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn light" data-toggle="dlMenu-status2">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-status2" class="dl-menu">
                <button data-dl-img="chartStatus" data-fn="status_breakdown.png">PNG Image</button>
                <button data-dl-csv="status" data-fn="status_breakdown.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartStatus" data-fn="status_breakdown.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-xl"><canvas id="chartStatusLg"></canvas></div>
          </div>
        </div>

        {{-- Category bar (large) --}}
        <div class="chart-card">
          <div class="cch cch-accent">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">IP Category Mix</div>
                <span class="chart-chip">Bar</span>
              </div>
              <div class="chart-sub">Filtered by global selectors</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-types2">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-types2" class="dl-menu">
                <button data-dl-img="chartTypesLg" data-fn="category_mix.png">PNG Image</button>
                <button data-dl-csv="types" data-fn="category_mix.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartTypesLg" data-fn="category_mix.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-xl"><canvas id="chartTypesLg"></canvas></div>
          </div>
        </div>

      </div>

      <div class="section-divider"><span>Campus vs. Category</span></div>

      <div class="grid-4-8 section">
        {{-- Campus horizontal --}}
        <div class="chart-card">
          <div class="cch cch-plain">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Campus Split</div>
                <span class="chart-chip">Horiz. Bar</span>
              </div>
              <div class="chart-sub">Records per campus</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-campus2">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-campus2" class="dl-menu">
                <button data-dl-img="chartCampusLg" data-fn="campus_distribution.png">PNG Image</button>
                <button data-dl-csv="campus" data-fn="campus_distribution.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartCampusLg" data-fn="campus_distribution.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-lg"><canvas id="chartCampusLg"></canvas></div>
          </div>
        </div>

        {{-- Category by Campus stacked --}}
        <div class="chart-card">
          <div class="cch cch-ink">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Category by Campus</div>
                <span class="chart-chip">Stacked</span>
              </div>
              <div class="chart-sub">Categories stacked per campus</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn light" data-toggle="dlMenu-catcampus2">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-catcampus2" class="dl-menu">
                <button data-dl-img="chartCatCampusStacked" data-fn="category_by_campus_stacked.png">PNG Image</button>
                <button data-dl-csv="catCampus" data-fn="category_by_campus.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartCatCampusStacked" data-fn="category_by_campus_stacked.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-lg"><canvas id="chartCatCampusStacked"></canvas></div>
          </div>
        </div>
      </div>

    </div>

    {{-- ══ TAB: CONTRIBUTORS ══ --}}
    <div class="tab-panel" id="tab-contributors">

      <div class="section-divider"><span>Top Inventors &amp; Owners</span></div>

      <div class="section">
        <div class="chart-card">
          <div class="cch cch-dark">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Top Contributors / Inventors</div>
                <span class="chart-chip">Grouped Bar</span>
              </div>
              <div class="chart-sub">Stacked by IP category — top 8 by record count</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn light" data-toggle="dlMenu-topinv">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-topinv" class="dl-menu">
                <button data-dl-img="chartTopInventors" data-fn="top_inventors.png">PNG Image</button>
                <button data-dl-csv="topInventors" data-fn="top_inventors.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartTopInventors" data-fn="top_inventors.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-2xl"><canvas id="chartTopInventors"></canvas></div>
          </div>
        </div>
      </div>

      <div class="section-divider"><span>Gender by Category</span></div>

      <div class="section">
        <div class="chart-card">
          <div class="cch cch-accent">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Gender by IP Category</div>
                <span class="chart-chip">Grouped Bar</span>
              </div>
              <div class="chart-sub">From contributors table — Male / Female / Other / Unknown</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn" data-toggle="dlMenu-gendercat">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-gendercat" class="dl-menu">
                <button data-dl-img="chartGenderCategory" data-fn="gender_by_category.png">PNG Image</button>
                <button data-dl-csv="genderByCategory" data-fn="gender_by_category.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartGenderCategory" data-fn="gender_by_category.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-xl"><canvas id="chartGenderCategory"></canvas></div>
          </div>
        </div>
      </div>

      <div class="section-divider"><span>Gender by Campus</span></div>

      <div class="section">
        <div class="chart-card">
          <div class="cch cch-ink">
            <div>
              <div class="chart-title-wrap">
                <div class="chart-title">Gender by Campus</div>
                <span class="chart-chip">Grouped Bar</span>
              </div>
              <div class="chart-sub">Contributor head-count per campus — Male / Female / Other / Unknown</div>
            </div>
            <div class="dl-wrap">
              <button class="dl-btn light" data-toggle="dlMenu-gendercampus">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
              </button>
              <div id="dlMenu-gendercampus" class="dl-menu">
                <button data-dl-img="chartGenderCampus" data-fn="gender_by_campus.png">PNG Image</button>
                <button data-dl-csv="genderByCampus" data-fn="gender_by_campus.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartGenderCampus" data-fn="gender_by_campus.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="chart-body">
            <div class="chart-wrap h-xl"><canvas id="chartGenderCampus"></canvas></div>
            <div class="chart-note">Counts individual contributors (not unique records). Top 8 campuses by total contributor count. Respects the global IP Type and Status filters.</div>
          </div>
        </div>
      </div>

    </div>

    <footer style="margin-top:24px;padding:14px 0;border-top:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;">
      <div style="font-size:.72rem;color:var(--muted);">© {{ now()->year }} &bull; KTTM Intellectual Property Services</div>
      <div style="font-size:.68rem;font-family:'DM Mono',monospace;color:#94a3b8;">Insights &middot; Powered by Chart.js</div>
    </footer>

  </div>
</div>

{{-- ══ HOW TO USE MODAL ══ --}}
<div class="howto-overlay" id="howtoModal">
  <div class="howto-box">
    <div class="howto-head">
      <div class="howto-head-top">
        <div class="howto-head-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div class="howto-head-text">
          <div class="howto-eyebrow">Insights Page · Guide</div>
          <div class="howto-title">How to Use Insights</div>
          <div class="howto-sub">A walkthrough of every chart, filter, and export available to you.</div>
        </div>
        <button class="howto-close" id="howtoClose">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="howto-body">

      <div class="howto-step">
        <div class="howto-step-num">01</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Global Filters — Slice All Charts at Once</div>
          <div class="howto-step-desc">Use the filter bar at the top to narrow down all charts simultaneously by <strong>IP Type</strong>, <strong>Status</strong>, <strong>Campus</strong>, <strong>Year</strong>, or <strong>Month</strong>. Every chart on every tab updates instantly. Hit <strong>Reset</strong> to clear all filters and return to the full dataset.</div>
          <span class="howto-step-tag">Filter Bar → Dropdowns</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">02</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Overview Tab — Status, Categories &amp; Campus</div>
          <div class="howto-step-desc">The <strong>Overview</strong> tab shows three core breakdowns: current record statuses as a doughnut chart, IP category distribution as a bar chart, and top campuses by filing count. These give you a snapshot of the entire IP portfolio at a glance.</div>
          <span class="howto-step-tag">Tab → Overview</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">03</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Over Time Tab — Monthly &amp; Yearly Trends</div>
          <div class="howto-step-desc">The <strong>Over Time</strong> tab shows how IP filings have grown over the years with a yearly bar chart, and a monthly trend line for more granular view. Use the Year and Month filters to zoom into specific periods and spot filing patterns.</div>
          <span class="howto-step-tag">Tab → Over Time</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">04</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Distribution Tab — Types by Campus</div>
          <div class="howto-step-desc">The <strong>Distribution</strong> tab breaks down which IP types come from which campuses — shown as both a grouped bar chart and a stacked chart. This is useful for identifying which campuses contribute most to specific IP categories like Patents or Trademarks.</div>
          <span class="howto-step-tag">Tab → Distribution</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">05</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Contributors Tab — Top Inventors &amp; Gender</div>
          <div class="howto-step-desc">The <strong>Contributors</strong> tab shows the top inventors/owners ranked by total filings, broken down by IP type. It also includes a gender breakdown per category — useful for equity reporting and identifying your most active contributors.</div>
          <span class="howto-step-tag">Tab → Contributors</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">06</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Export Any Chart — PNG, PDF, or CSV</div>
          <div class="howto-step-desc">Every chart has an <strong>Export</strong> button in its header. Click it to choose your format — <strong>PNG</strong> for a clean image, <strong>PDF</strong> for a printable document, or <strong>Excel (CSV)</strong> for raw data you can open in a spreadsheet. Exports reflect whatever filters are currently active.</div>
          <span class="howto-step-tag">Chart Header → Export</span>
        </div>
      </div>

    </div>

    <div class="howto-footer">
      <div class="howto-footer-note">Need more help? Contact your <strong>KTTM administrator</strong>.</div>
      <button class="btn-primary-sm" id="howtoCloseBtn">Got it, thanks!</button>
    </div>
  </div>
</div>

{{-- ══ LOGOUT MODAL ══ --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal-box">
    <div style="padding:28px;">
      <div style="width:50px;height:50px;border-radius:14px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
      <div style="font-size:1.1rem;font-weight:800;font-family:'Plus Jakarta Sans',sans-serif;">Sign out of KTTM</div>
      <div style="font-size:.82rem;color:var(--muted);margin-top:6px;line-height:1.6;">This will end your session and return you to the public portal.</div>
      <div class="logout-modal-actions">
        <button type="button" class="btn-outline" data-close-logout>Cancel</button>
        <form id="logoutForm" action="{{ $urlLogout }}" method="POST">
          @csrf
          <button type="submit" class="btn-primary">Sign Out</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
const KTTM_DATA = @json($js);

// ══ Download helpers ══
function downloadBlob(fn,content,mime){ const b=new Blob([content],{type:mime}); const u=URL.createObjectURL(b); const a=document.createElement('a'); a.href=u; a.download=fn; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(u); }
function toCSV(headers,rows){ const e=v=>{const s=String(v??''); return /[",\n]/.test(s)?`"${s.replace(/"/g,'""')}"`  :s;}; return headers.map(e).join(',')+'\n'+rows.map(r=>r.map(e).join(',')).join('\n'); }
function downloadSimpleCSV(labels,values,fn,c1='Label',c2='Value'){ downloadBlob(fn,toCSV([c1,c2],labels.map((l,i)=>[l,values[i]??0])),'text/csv;charset=utf-8'); }
function downloadCatCampusCSV(p,fn){ const camps=p.campusLabels||[],cats=p.categoryLabels||[]; downloadBlob(fn,toCSV(['Category',...camps],cats.map(c=>[c,...camps.map(cp=>p.matrix?.[c]?.[cp]??0)])),'text/csv;charset=utf-8'); }
function downloadTopInventorsCSV(p,fn){ const l=p.labels||[],s=p.series||{}; downloadBlob(fn,toCSV(['Name','Patent','Utility Model','Industrial Design','Copyright','Total'],l.map((n,i)=>[n,s.Patent?.[i]??0,s['Utility Model']?.[i]??0,s['Industrial Design']?.[i]??0,s.Copyright?.[i]??0,p.total?.[i]??0])),'text/csv;charset=utf-8'); }
function downloadGenderByCategoryCSV(p,fn){ const l=p.labels||[],s=p.series||{}; downloadBlob(fn,toCSV(['Category','Male','Female','Other','Unknown'],l.map((c,i)=>[c,s.Male?.[i]??0,s.Female?.[i]??0,s.Other?.[i]??0,s.Unknown?.[i]??0])),'text/csv;charset=utf-8'); }
function downloadChartPNG(chart,fn){ const a=document.createElement('a'); a.href=chart.toBase64Image('image/png',1); a.download=fn||'chart.png'; a.click(); }
function downloadChartPDF(chart,fn){ const {jsPDF}=window.jspdf||{}; if(!jsPDF) return; const img=chart.toBase64Image('image/png',1); const pdf=new jsPDF({orientation:'landscape',unit:'mm',format:'a4'}); const pw=297,ph=210,m=10,mw=pw-m*2,mh=ph-m*2; const r=Math.min(mw/chart.canvas.width,mh/chart.canvas.height); pdf.addImage(img,'PNG',m,m,chart.canvas.width*r,chart.canvas.height*r); pdf.save(fn||'chart.pdf'); }

// ══ Chart color palette (maroon-gold theme) ══
const MAROON_PALETTE = [
  'rgba(165,44,48,.85)',  // maroon
  'rgba(240,200,96,.90)', // gold
  'rgba(245,158,11,.80)', // amber
  'rgba(30,41,59,.75)',   // ink
  'rgba(99,102,241,.65)', // indigo
  'rgba(20,184,166,.65)', // teal
  'rgba(236,72,153,.60)', // pink
  'rgba(59,130,246,.65)', // blue
];
function makeColors(n,palette){ const p=palette||MAROON_PALETTE; return Array.from({length:n},(_,i)=>p[i%p.length]); }
function fmtMonth(ym){ if(!ym) return ''; const [y,m]=String(ym).split('-'); return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][parseInt(m,10)-1]+' '+y; }

function baseOpts(legendPos='bottom'){
  return {
    responsive:true, maintainAspectRatio:false,
    plugins:{
      legend:{
        display:true, position:legendPos,
        labels:{ boxWidth:11, boxHeight:11, font:{size:11}, padding:14 }
      },
      tooltip:{ enabled:true, cornerRadius:8, padding:10 }
    }
  };
}

const charts = {};

// ── 1. Trend (smooth area — full width in Time tab) ──
{
  const el = document.getElementById('chartTrend');
  if(el){
    const ctx = el.getContext('2d');
    const g = ctx.createLinearGradient(0,0,0,360);
    g.addColorStop(0,'rgba(165,44,48,.45)');
    g.addColorStop(0.6,'rgba(240,200,96,.12)');
    g.addColorStop(1,'rgba(240,200,96,.0)');
    charts.chartTrend = new Chart(el,{
      type:'line',
      data:{
        labels:(KTTM_DATA.monthsRegistered.labels||[]).map(fmtMonth),
        datasets:[{
          label:'Records', data:KTTM_DATA.monthsRegistered.values,
          tension:.42, fill:true,
          borderColor:'rgba(165,44,48,.9)',
          backgroundColor:g,
          borderWidth:2.5,
          pointRadius:3.5, pointBackgroundColor:'rgba(165,44,48,.9)',
          pointHoverRadius:6,
        }]
      },
      options:{
        ...baseOpts('bottom'),
        plugins:{...baseOpts().plugins, legend:{display:false}},
        scales:{
          y:{beginAtZero:true, ticks:{precision:0}, grid:{color:'rgba(15,23,42,.05)'}},
          x:{grid:{display:false}, ticks:{maxRotation:45,font:{size:10}}}
        }
      }
    });
  }
}

// ── 2. Year (rounded bar) ──
{
  const el = document.getElementById('chartYear');
  if(el){
    const colors = (KTTM_DATA.yearsRegistered.labels||[]).map((_,i)=>
      i===(KTTM_DATA.yearsRegistered.labels.length-1) ? 'rgba(165,44,48,.9)' : makeColors(1)[0]
    );
    charts.chartYear = new Chart(el,{
      type:'bar',
      data:{
        labels:KTTM_DATA.yearsRegistered.labels,
        datasets:[{ label:'Records', data:KTTM_DATA.yearsRegistered.values, backgroundColor:colors, borderRadius:8, borderWidth:0 }]
      },
      options:{
        ...baseOpts(),
        plugins:{...baseOpts().plugins, legend:{display:false}},
        scales:{
          y:{beginAtZero:true, ticks:{precision:0}, grid:{color:'rgba(15,23,42,.05)'}},
          x:{grid:{display:false}}
        }
      }
    });
  }
}

// ── 3. Gender (doughnut — overview) ──
{
  const el = document.getElementById('chartGender');
  if(el){
    charts.chartGender = new Chart(el,{
      type:'doughnut',
      data:{ labels:KTTM_DATA.gender.labels, datasets:[{ data:KTTM_DATA.gender.values, backgroundColor:makeColors(KTTM_DATA.gender.labels.length), borderWidth:2, borderColor:'#fff', hoverBorderColor:'#fff' }] },
      options:{...baseOpts('bottom'), cutout:'65%'}
    });
  }
}

// ── 4. Status doughnut (overview card) ──
{
  const el = document.getElementById('chartStatus');
  if(el){
    charts.chartStatus = new Chart(el,{
      type:'doughnut',
      data:{ labels:KTTM_DATA.status.labels, datasets:[{ data:KTTM_DATA.status.values, backgroundColor:makeColors(KTTM_DATA.status.labels.length), borderWidth:2, borderColor:'#fff', hoverBorderColor:'#fff' }] },
      options:{...baseOpts('bottom'), cutout:'65%'}
    });
  }
}

// ── 4b. Status doughnut (distribution tab, larger) ──
{
  const el = document.getElementById('chartStatusLg');
  if(el){
    charts.chartStatusLg = new Chart(el,{
      type:'doughnut',
      data:{ labels:KTTM_DATA.status.labels, datasets:[{ data:KTTM_DATA.status.values, backgroundColor:makeColors(KTTM_DATA.status.labels.length), borderWidth:2, borderColor:'#1e293b', hoverBorderColor:'#fff' }] },
      options:{...baseOpts('right'), cutout:'60%'}
    });
  }
}

// ── 5. Types (bar — overview) ──
{
  const el = document.getElementById('chartTypes');
  if(el){
    charts.chartTypes = new Chart(el,{
      type:'bar',
      data:{ labels:KTTM_DATA.types.labels, datasets:[{ label:'Records', data:KTTM_DATA.types.values, backgroundColor:makeColors(KTTM_DATA.types.labels.length), borderRadius:6, borderWidth:0 }] },
      options:{
        ...baseOpts(),
        plugins:{...baseOpts().plugins, legend:{display:false}},
        scales:{ y:{beginAtZero:true,ticks:{precision:0},grid:{color:'rgba(15,23,42,.05)'}}, x:{grid:{display:false}} }
      }
    });
  }
}

// ── 5b. Types bar (distribution tab, larger) ──
{
  const el = document.getElementById('chartTypesLg');
  if(el){
    charts.chartTypesLg = new Chart(el,{
      type:'bar',
      data:{ labels:KTTM_DATA.types.labels, datasets:[{ label:'Records', data:KTTM_DATA.types.values, backgroundColor:makeColors(KTTM_DATA.types.labels.length), borderRadius:8, borderWidth:0 }] },
      options:{
        ...baseOpts(),
        plugins:{...baseOpts().plugins, legend:{display:false}},
        scales:{ y:{beginAtZero:true,ticks:{precision:0},grid:{color:'rgba(15,23,42,.05)'}}, x:{grid:{display:false}} }
      }
    });
  }
}

// ── 6a. Campus (horiz bar — overview) ──
{
  const el = document.getElementById('chartCampus');
  if(el){
    charts.chartCampus = new Chart(el,{
      type:'bar',
      data:{ labels:KTTM_DATA.campus.labels, datasets:[{ label:'Records', data:KTTM_DATA.campus.values, backgroundColor:makeColors(KTTM_DATA.campus.labels.length), borderRadius:6, borderWidth:0 }] },
      options:{
        ...baseOpts(),
        indexAxis:'y',
        plugins:{...baseOpts().plugins, legend:{display:false}},
        scales:{ x:{beginAtZero:true,ticks:{precision:0},grid:{color:'rgba(15,23,42,.05)'}}, y:{grid:{display:false}} }
      }
    });
  }
}

// ── 6b. Campus (horiz bar — distribution tab) ──
{
  const el = document.getElementById('chartCampusLg');
  if(el){
    charts.chartCampusLg = new Chart(el,{
      type:'bar',
      data:{ labels:KTTM_DATA.campus.labels, datasets:[{ label:'Records', data:KTTM_DATA.campus.values, backgroundColor:makeColors(KTTM_DATA.campus.labels.length), borderRadius:6, borderWidth:0 }] },
      options:{
        ...baseOpts(),
        indexAxis:'y',
        plugins:{...baseOpts().plugins, legend:{display:false}},
        scales:{ x:{beginAtZero:true,ticks:{precision:0},grid:{color:'rgba(15,23,42,.05)'}}, y:{grid:{display:false}} }
      }
    });
  }
}

// ── 7a. Category by Campus — grouped (overview) ──
{
  const el = document.getElementById('chartCatCampus');
  if(el){
    const camps=KTTM_DATA.catCampus.campusLabels||[], cats=KTTM_DATA.catCampus.categoryLabels||[];
    const colors=makeColors(camps.length);
    charts.chartCatCampus = new Chart(el,{
      type:'bar',
      data:{ labels:cats, datasets:camps.map((c,i)=>({ label:c, data:cats.map(cat=>KTTM_DATA.catCampus.matrix?.[cat]?.[c]??0), backgroundColor:colors[i], borderRadius:4, borderWidth:0 })) },
      options:{
        ...baseOpts('bottom'),
        scales:{ x:{stacked:false,grid:{display:false}}, y:{stacked:false,type:'logarithmic',min:1,grid:{color:'rgba(15,23,42,.05)'},ticks:{callback:function(v){return [1,5,10,50,100,500,1000,5000,10000].includes(v)?v.toLocaleString():'';}}}} 
      }
    });
  }
}

// ── 7b. Category by Campus — stacked (distribution tab) ──
{
  const el = document.getElementById('chartCatCampusStacked');
  if(el){
    const camps=KTTM_DATA.catCampus.campusLabels||[], cats=KTTM_DATA.catCampus.categoryLabels||[];
    const colors=makeColors(cats.length);
    // pivot: campus on x, categories as datasets
    charts.chartCatCampusStacked = new Chart(el,{
      type:'bar',
      data:{ labels:camps, datasets:cats.map((cat,i)=>({ label:cat, data:camps.map(c=>KTTM_DATA.catCampus.matrix?.[cat]?.[c]??0), backgroundColor:colors[i], borderRadius:0, borderWidth:0 })) },
      options:{
        ...baseOpts('bottom'),
        scales:{ x:{stacked:true,grid:{display:false}}, y:{stacked:true,beginAtZero:true,ticks:{precision:0},grid:{color:'rgba(15,23,42,.05)'}} }
      }
    });
  }
}

// ── 8. Top Inventors ──
{
  const el = document.getElementById('chartTopInventors');
  const p = KTTM_DATA.topInventors;
  if(el && (p?.labels?.length||0)>0){
    const palette=['rgba(165,44,48,.9)','rgba(240,200,96,.95)','rgba(245,158,11,.80)','rgba(30,41,59,.75)'];
    charts.chartTopInventors = new Chart(el,{
      type:'bar',
      data:{ labels:p.labels, datasets:[
        {label:'Patent',          data:p.series.Patent||[],           backgroundColor:palette[0], borderRadius:4, borderWidth:0},
        {label:'Utility Model',   data:p.series['Utility Model']||[], backgroundColor:palette[1], borderRadius:4, borderWidth:0},
        {label:'Industrial Design',data:p.series['Industrial Design']||[],backgroundColor:palette[2],borderRadius:4,borderWidth:0},
        {label:'Copyright',       data:p.series.Copyright||[],        backgroundColor:palette[3], borderRadius:4, borderWidth:0},
      ]},
      options:{
        ...baseOpts('bottom'),
        scales:{
          x:{stacked:false, grid:{display:false}},
          y:{stacked:false, beginAtZero:true, ticks:{precision:0}, grid:{color:'rgba(15,23,42,.05)'}}
        }
      }
    });
  }
}

// ── 9. Gender by Category ──
{
  const el = document.getElementById('chartGenderCategory');
  const p = KTTM_DATA.genderByCategory;
  if(el && (p?.labels?.length||0)>0){
    charts.chartGenderCategory = new Chart(el,{
      type:'bar',
      data:{ labels:p.labels, datasets:[
        {label:'Male',    data:p.series.Male||[],    backgroundColor:'rgba(165,44,48,.85)', borderRadius:4, borderWidth:0},
        {label:'Female',  data:p.series.Female||[],  backgroundColor:'rgba(240,200,96,.90)', borderRadius:4, borderWidth:0},
        {label:'Other',   data:p.series.Other||[],   backgroundColor:'rgba(245,158,11,.75)', borderRadius:4, borderWidth:0},
        {label:'Unknown', data:p.series.Unknown||[], backgroundColor:'rgba(15,23,42,.35)',  borderRadius:4, borderWidth:0},
      ]},
      options:{
        ...baseOpts('bottom'),
        scales:{
          x:{stacked:false, grid:{display:false}},
          y:{stacked:false, beginAtZero:true, ticks:{precision:0}, grid:{color:'rgba(15,23,42,.05)'}}
        }
      }
    });
  }
}

// ── 10. Gender by Campus ──
{
  const el = document.getElementById('chartGenderCampus');
  const p = KTTM_DATA.genderByCampus;
  if(el && (p?.labels?.length||0)>0){
    charts.chartGenderCampus = new Chart(el,{
      type:'bar',
      data:{ labels:p.labels, datasets:[
        {label:'Male',    data:p.series.Male||[],    backgroundColor:'rgba(165,44,48,.85)', borderRadius:4, borderWidth:0},
        {label:'Female',  data:p.series.Female||[],  backgroundColor:'rgba(240,200,96,.90)', borderRadius:4, borderWidth:0},
        {label:'Other',   data:p.series.Other||[],   backgroundColor:'rgba(245,158,11,.75)', borderRadius:4, borderWidth:0},
        {label:'Unknown', data:p.series.Unknown||[], backgroundColor:'rgba(15,23,42,.35)',  borderRadius:4, borderWidth:0},
      ]},
      options:{
        ...baseOpts('bottom'),
        scales:{
          x:{stacked:false, grid:{display:false}, ticks:{maxRotation:30, font:{size:10}}},
          y:{stacked:false, beginAtZero:true, ticks:{precision:0}, grid:{color:'rgba(15,23,42,.05)'}}
        }
      }
    });
  }
}
function getGlobalFilters(){
  return {
    cat:    document.getElementById('gf-type')?.value   || '',
    status: document.getElementById('gf-status')?.value || '',
    campus: document.getElementById('gf-campus')?.value || '',
    year:   document.getElementById('gf-year')?.value   || '',
    month:  document.getElementById('gf-month')?.value  || '',
  };
}

function filterRecords(catSel, statusSel, campusSel, yearSel, monthSel){
  return KTTM_DATA.allRecords.filter(r=>{
    const rc=((r.category||r.type)||'').toString().trim();
    const rs=((r.status)||'').toString().trim();
    const rp=((r.campus)||'').toString().trim();
    const reg=(r.registered||'').toString().trim();
    if(catSel    && rc!==catSel)    return false;
    if(statusSel && rs!==statusSel) return false;
    if(campusSel && rp!==campusSel) return false;
    if(yearSel  && !reg.startsWith(yearSel))              return false;
    if(monthSel && reg.substring(5,7) !== monthSel)       return false;
    return true;
  });
}

function rebuildAllCharts(){
  const {cat, status, campus, year, month} = getGlobalFilters();
  const filtered = filterRecords(cat, status, campus, year, month);

  // ── Trend ──
  const byMonth={};
  filtered.forEach(r=>{ if(!r.registered) return; const k=r.registered.substring(0,7); byMonth[k]=(byMonth[k]||0)+1; });
  const ml=Object.keys(byMonth).sort(), mv=ml.map(k=>byMonth[k]);
  if(charts.chartTrend){ charts.chartTrend.data.labels=ml.map(fmtMonth); charts.chartTrend.data.datasets[0].data=mv; charts.chartTrend.update(); }

  // ── Year ──
  const byYear={};
  filtered.forEach(r=>{ if(!r.registered) return; const k=r.registered.substring(0,4); byYear[k]=(byYear[k]||0)+1; });
  const yl=Object.keys(byYear).sort(), yv=yl.map(k=>byYear[k]);
  if(charts.chartYear){ charts.chartYear.data.labels=yl; charts.chartYear.data.datasets[0].data=yv; charts.chartYear.update(); }

  // ── Types (both instances) ──
  const typeCounts={};
  filtered.forEach(r=>{ const k=((r.category||r.type)||'').toString().trim()||'—'; typeCounts[k]=(typeCounts[k]||0)+1; });
  const typeEntries=Object.entries(typeCounts).sort((a,b)=>b[1]-a[1]);
  const typeTop=typeEntries.slice(0,8); const typeOthers=typeEntries.slice(8).reduce((s,[,v])=>s+v,0);
  if(typeOthers>0) typeTop.push(['Others',typeOthers]);
  const tLabels=typeTop.map(e=>e[0]), tValues=typeTop.map(e=>e[1]);
  [charts.chartTypes, charts.chartTypesLg].forEach(c=>{ if(c){ c.data.labels=tLabels; c.data.datasets[0].data=tValues; c.data.datasets[0].backgroundColor=makeColors(tLabels.length); c.update(); }});

  // ── Campus (both instances) ──
  const campusCounts={};
  filtered.forEach(r=>{ const k=((r.campus)||'').toString().trim()||'—'; campusCounts[k]=(campusCounts[k]||0)+1; });
  const campEntries=Object.entries(campusCounts).sort((a,b)=>b[1]-a[1]).slice(0,6);
  const cpLabels=campEntries.map(e=>e[0]), cpValues=campEntries.map(e=>e[1]);
  [charts.chartCampus, charts.chartCampusLg].forEach(c=>{ if(c){ c.data.labels=cpLabels; c.data.datasets[0].data=cpValues; c.data.datasets[0].backgroundColor=makeColors(cpLabels.length); c.update(); }});

  // ── Status (both instances) ──
  const statusCounts={};
  filtered.forEach(r=>{ const k=((r.status)||'').toString().trim()||'—'; statusCounts[k]=(statusCounts[k]||0)+1; });
  const stEntries=Object.entries(statusCounts).sort((a,b)=>b[1]-a[1]);
  const stTop=stEntries.slice(0,8); const stOthers=stEntries.slice(8).reduce((s,[,v])=>s+v,0);
  if(stOthers>0) stTop.push(['Others',stOthers]);
  const stLabels=stTop.map(e=>e[0]), stValues=stTop.map(e=>e[1]);
  [charts.chartStatus, charts.chartStatusLg].forEach(c=>{ if(c){ c.data.labels=stLabels; c.data.datasets[0].data=stValues; c.data.datasets[0].backgroundColor=makeColors(stLabels.length); c.update(); }});

  // ── Cat by Campus (grouped) ──
  const cats=Object.keys(Object.entries(typeCounts).filter(([k])=>k!=='—').sort((a,b)=>b[1]-a[1]).slice(0,6).reduce((o,[k])=>(o[k]=1,o),{}));
  const camps=cpLabels;
  const matrix={};
  cats.forEach(c=>{ matrix[c]={}; camps.forEach(p=>{ matrix[c][p]=0; }); });
  filtered.forEach(r=>{ const c=((r.category||r.type)||'').trim()||'—'; const p=((r.campus)||'').trim()||'—'; if(cats.includes(c)&&camps.includes(p)) matrix[c][p]=(matrix[c][p]||0)+1; });
  if(charts.chartCatCampus){ charts.chartCatCampus.data.labels=cats; charts.chartCatCampus.data.datasets=camps.map((c,i)=>({label:c,data:cats.map(ct=>matrix[ct][c]||0),backgroundColor:makeColors(camps.length)[i],borderRadius:4,borderWidth:0})); charts.chartCatCampus.options.scales.y.type='logarithmic'; charts.chartCatCampus.options.scales.y.min=1; charts.chartCatCampus.update(); }
  // stacked version (campus on x, cats as datasets)
  if(charts.chartCatCampusStacked){ charts.chartCatCampusStacked.data.labels=camps; charts.chartCatCampusStacked.data.datasets=cats.map((cat,i)=>({label:cat,data:camps.map(c=>matrix[cat][c]||0),backgroundColor:makeColors(cats.length)[i],borderRadius:0,borderWidth:0})); charts.chartCatCampusStacked.update(); }

  // ── Top Inventors ──
  const invMap={};
  filtered.forEach(r=>{ const n=(r.owner||'').toString().trim()||'—'; const c=((r.category||r.type)||'').toString().trim()||'—'; if(!invMap[n]) invMap[n]={Patent:0,'Utility Model':0,'Industrial Design':0,Copyright:0,total:0}; if(invMap[n][c]!==undefined) invMap[n][c]++; invMap[n].total++; });
  const topInv=Object.entries(invMap).sort(([,a],[,b])=>b.total-a.total).slice(0,8);
  const invLabels=topInv.map(e=>e[0]), invS={Patent:[],  'Utility Model':[],'Industrial Design':[],Copyright:[]};
  topInv.forEach(([,d])=>{ invS.Patent.push(d.Patent); invS['Utility Model'].push(d['Utility Model']); invS['Industrial Design'].push(d['Industrial Design']); invS.Copyright.push(d.Copyright); });
  if(charts.chartTopInventors){ charts.chartTopInventors.data.labels=invLabels; [invS.Patent,invS['Utility Model'],invS['Industrial Design'],invS.Copyright].forEach((d,i)=>{ charts.chartTopInventors.data.datasets[i].data=d; }); charts.chartTopInventors.update(); }

  // ── Gender by Category ──
  const raw=KTTM_DATA.genderByCategoryRaw||[];
  const gFiltered=raw.filter(r=>{
    const rc=(r.category||'').toString().trim(); const rs=(r.status||'').toString().trim(); const rp=(r.campus||'').toString().trim();
    if(cat    && rc!==cat)    return false;
    if(status && rs!==status) return false;
    if(campus && rp!==campus) return false;
    return true; // gender raw doesn't have registered date, skip date filter
  });
  const gCounts={};
  gFiltered.forEach(r=>{ const c=((r.category||r.type)||'').toString().trim()||'—'; let role=(r.role||'Unknown').toString().trim(); if(!['Male','Female','Other','Unknown'].includes(role)) role='Other'; if(!gCounts[c]) gCounts[c]={Male:0,Female:0,Other:0,Unknown:0}; gCounts[c][role]=(gCounts[c][role]||0)+1; });
  const gLabels=KTTM_DATA.genderByCategory.labels||[];
  const gSeries={Male:[],Female:[],Other:[],Unknown:[]};
  gLabels.forEach(c=>{ const row=gCounts[c]||{Male:0,Female:0,Other:0,Unknown:0}; Object.keys(gSeries).forEach(k=>gSeries[k].push(row[k])); });
  if(charts.chartGenderCategory){ charts.chartGenderCategory.data.labels=gLabels; ['Male','Female','Other','Unknown'].forEach((k,i)=>{ charts.chartGenderCategory.data.datasets[i].data=gSeries[k]; }); charts.chartGenderCategory.update(); }

  // ── Gender by Campus ──
  const gcRaw=KTTM_DATA.genderByCampusRaw||[];
  const gcFiltered=gcRaw.filter(r=>{
    const rc=(r.category||'').toString().trim(); const rs=(r.status||'').toString().trim();
    if(cat    && rc!==cat)    return false;
    if(status && rs!==status) return false;
    // campus filter intentionally NOT applied here — we want to show all campuses
    return true;
  });
  const gcMap={};
  gcFiltered.forEach(r=>{ const c=(r.campus||'Unknown').toString().trim()||'Unknown'; const g=(r.gender||'Unknown').toString().trim(); if(!gcMap[c]) gcMap[c]={Male:0,Female:0,Other:0,Unknown:0,total:0}; gcMap[c][g]=(gcMap[c][g]||0)+1; gcMap[c].total++; });
  const gcEntries=Object.entries(gcMap).sort(([,a],[,b])=>b.total-a.total).slice(0,8);
  const gcLabels=gcEntries.map(([k])=>k);
  const gcSeries={Male:[],Female:[],Other:[],Unknown:[]};
  gcEntries.forEach(([,d])=>{ gcSeries.Male.push(d.Male||0); gcSeries.Female.push(d.Female||0); gcSeries.Other.push(d.Other||0); gcSeries.Unknown.push(d.Unknown||0); });
  if(charts.chartGenderCampus){ charts.chartGenderCampus.data.labels=gcLabels; ['Male','Female','Other','Unknown'].forEach((k,i)=>{ charts.chartGenderCampus.data.datasets[i].data=gcSeries[k]; }); charts.chartGenderCampus.update(); }
}

// Global filter events
['gf-type','gf-status','gf-campus','gf-year','gf-month'].forEach(id=>
  document.getElementById(id)?.addEventListener('change', rebuildAllCharts)
);
document.getElementById('gfResetBtn')?.addEventListener('click',()=>{
  ['gf-type','gf-status','gf-campus','gf-year','gf-month'].forEach(id=>{ const el=document.getElementById(id); if(el) el.value=''; });
  rebuildAllCharts();
});

// ── Populate year dropdown from data ──
(function(){
  const sel = document.getElementById('gf-year');
  if(!sel) return;
  const years = (KTTM_DATA.yearsRegistered?.labels||[]).slice().sort((a,b)=>b-a);
  years.forEach(y=>{ const o=document.createElement('option'); o.value=y; o.textContent=y; sel.appendChild(o); });
})();

// ── Sticky dock IntersectionObserver (rootMargin tracks --topbar-h) ──
(function(){
  const dock = document.getElementById('stickyDock');
  if(!dock) return;
  const sentinel = document.createElement('div');
  sentinel.style.cssText = 'height:1px;pointer-events:none;';
  dock.parentNode.insertBefore(sentinel, dock);
  function stickyRootMargin(){
    const raw = getComputedStyle(document.documentElement).getPropertyValue('--topbar-h').trim();
    const px = parseFloat(raw) || 72;
    return `-${Math.round(px) + 1}px 0px 0px 0px`;
  }
  let obs = new IntersectionObserver(([entry])=>{
    dock.classList.toggle('is-stuck', !entry.isIntersecting);
  }, { threshold: 0, rootMargin: stickyRootMargin() });
  obs.observe(sentinel);
  window.addEventListener('resize', ()=>{
    obs.disconnect();
    obs = new IntersectionObserver(([entry])=>{
      dock.classList.toggle('is-stuck', !entry.isIntersecting);
    }, { threshold: 0, rootMargin: stickyRootMargin() });
    obs.observe(sentinel);
  });
})();

// ══ Tab switching ══
document.querySelectorAll('.tab-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p=>p.classList.remove('active'));
    btn.classList.add('active');
    const panel = document.getElementById('tab-' + btn.dataset.tab);
    if(panel) panel.classList.add('active');
    // Resize charts after panel becomes visible
    setTimeout(()=>{ Object.values(charts).forEach(c=>{ try{ c.resize(); }catch(e){} }); }, 50);
  });
});

// ══ Dropdown menus ══
function closeAllMenus(except){ document.querySelectorAll('.dl-menu').forEach(m=>{ if(m!==except) m.classList.remove('open'); }); }
document.querySelectorAll('[data-toggle]').forEach(btn=>{
  btn.addEventListener('click',e=>{ e.stopPropagation(); const m=document.getElementById(btn.dataset.toggle); if(!m) return; const willOpen=!m.classList.contains('open'); closeAllMenus(); if(willOpen) m.classList.add('open'); });
});
document.addEventListener('click',()=>closeAllMenus());
document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeAllMenus(); });

// ══ Download wiring ══
document.querySelectorAll('[data-dl-img]').forEach(btn=>{ btn.addEventListener('click',e=>{ e.stopPropagation(); const c=charts[btn.dataset.dlImg]; if(c) downloadChartPNG(c,btn.dataset.fn); closeAllMenus(); }); });
document.querySelectorAll('[data-dl-pdf]').forEach(btn=>{ btn.addEventListener('click',e=>{ e.stopPropagation(); const c=charts[btn.dataset.dlPdf]; if(c) downloadChartPDF(c,btn.dataset.fn); closeAllMenus(); }); });
document.querySelectorAll('[data-dl-csv]').forEach(btn=>{
  btn.addEventListener('click',e=>{
    e.stopPropagation(); const k=btn.dataset.dlCsv, fn=btn.dataset.fn;
    if(k==='trend'){ downloadSimpleCSV(KTTM_DATA.monthsRegistered.labels,KTTM_DATA.monthsRegistered.values,fn,'Month','Records'); }
    else if(k==='yearsRegistered'){ downloadSimpleCSV(KTTM_DATA.yearsRegistered.labels,KTTM_DATA.yearsRegistered.values,fn,'Year','Records'); }
    else if(k==='catCampus'){ downloadCatCampusCSV(KTTM_DATA.catCampus,fn); }
    else if(k==='topInventors'){ downloadTopInventorsCSV(KTTM_DATA.topInventors,fn); }
    else if(k==='genderByCategory'){ downloadGenderByCategoryCSV(KTTM_DATA.genderByCategory,fn); }
    else if(k==='genderByCampus'){
      const p=KTTM_DATA.genderByCampus, l=p.labels||[], s=p.series||{};
      downloadBlob(fn,toCSV(['Campus','Male','Female','Other','Unknown'],l.map((c,i)=>[c,s.Male?.[i]??0,s.Female?.[i]??0,s.Other?.[i]??0,s.Unknown?.[i]??0])),'text/csv;charset=utf-8');
    }
    else { const m={status:KTTM_DATA.status,types:KTTM_DATA.types,campus:KTTM_DATA.campus,gender:KTTM_DATA.gender}[k]; if(m) downloadSimpleCSV(m.labels,m.values,fn); }
    closeAllMenus();
  });
});

// ══ How To Use Modal ══
const howtoModal  = document.getElementById('howtoModal');
const howtoClose  = document.getElementById('howtoClose');
const howtoCloseBtn = document.getElementById('howtoCloseBtn');

function openHowto()  { howtoModal?.classList.add('open');    document.body.style.overflow = 'hidden'; }
function closeHowto() { howtoModal?.classList.remove('open'); document.body.style.overflow = ''; }

document.getElementById('howToUseBtn')?.addEventListener('click', openHowto);
howtoClose?.addEventListener('click', closeHowto);
howtoCloseBtn?.addEventListener('click', closeHowto);
howtoModal?.addEventListener('click', e => { if (e.target === howtoModal) closeHowto(); });

// ══ Logout modal ══
const logoutModal=document.getElementById('logoutModal');
document.getElementById('logoutBtn')?.addEventListener('click',()=>{ logoutModal?.classList.add('open'); document.body.style.overflow='hidden'; });
document.querySelectorAll('[data-close-logout]').forEach(b=>b.addEventListener('click',()=>{ logoutModal?.classList.remove('open'); document.body.style.overflow=''; }));
logoutModal?.addEventListener('click',e=>{ if(e.target===logoutModal){ logoutModal.classList.remove('open'); document.body.style.overflow=''; }});
document.addEventListener('keydown',e=>{ if(e.key==='Escape'){ closeHowto(); logoutModal?.classList.remove('open'); document.body.style.overflow=''; closeAllMenus(); }});
const logoutForm=document.getElementById('logoutForm');
if(logoutForm?.dataset.simulate==='true'){ logoutForm.addEventListener('submit',e=>{ e.preventDefault(); logoutModal?.classList.remove('open'); document.body.style.overflow=''; setTimeout(()=>window.location.href='{{ url('/') }}',200); }); }

/* Mobile sidebar (same pattern as home / records) */
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
  const logoutOpen = document.getElementById('logoutModal')?.classList.contains('open');
  const howtoOpen = document.getElementById('howtoModal')?.classList.contains('open');
  if (!logoutOpen && !howtoOpen) document.body.style.overflow = '';
}

hamburgerBtn?.addEventListener('click', function(e) {
  e.stopPropagation();
  const isOpen = mainSidebar?.classList.contains('mobile-open');
  isOpen ? closeMobileSidebar() : openMobileSidebar();
});

sidebarBackdrop?.addEventListener('click', closeMobileSidebar);

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

window.addEventListener('resize', function() {
  if (window.innerWidth > 768) closeMobileSidebar();
});
</script>

</body>
</html>