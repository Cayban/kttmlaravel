<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — How to File</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

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

    /* Hamburger (mobile) + drawer backdrop — matches home / records */
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

    /* ── MAIN LAYOUT ── */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ── TOPBAR ── */
    .topbar {
      min-height: 72px; background: var(--card);
      border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 10px var(--pad-x); position: sticky; top: 0; z-index: 40;
      box-shadow: 0 2px 16px rgba(15,23,42,.05);
    }
    .topbar-left {
      display: flex; align-items: center; gap: 12px;
      min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; }
    .page-title {
      font-size: clamp(0.95rem, 0.4vw + 0.85rem, 1.15rem);
      font-weight: 800; letter-spacing: -.3px; color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-subtitle {
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted); font-weight: 500;
      overflow-wrap: anywhere;
    }
    .topbar-right {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto; min-width: 0; max-width: 100%;
      justify-content: flex-end;
    }
    .icon-btn {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted); transition: all .18s;
      text-decoration: none;
      flex-shrink: 0;
    }
    .icon-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .btn-primary {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.5vw, 20px);
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s; text-decoration: none;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(165,44,48,.35); }
    .guest-pill {
      display: flex; align-items: center; gap: 7px;
      background: var(--maroon-light); border: 1.5px solid rgba(165,44,48,.2);
      border-radius: 12px; padding: 7px 14px;
      font-size: clamp(0.7rem, 0.15vw + 0.67rem, 0.75rem);
      font-weight: 700; color: var(--maroon);
      max-width: 100%; min-width: 0;
    }
    .guest-pill-text { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .guest-pill-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--maroon); box-shadow: 0 0 0 3px rgba(165,44,48,.2); flex-shrink: 0; }
    .avatar {
      width: 40px; height: 40px; border-radius: 12px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.85rem; color: #2a1a0b;
      flex-shrink: 0;
    }

    /* ── CONTENT ── */
    .content {
      padding: clamp(14px, 2.5vw, 18px) var(--pad-x);
      flex: 1;
      width: 100%; max-width: var(--shell-max); margin: 0 auto;
      box-sizing: border-box;
      background: var(--bg) url('{{ asset("images/abstractBGIMAGE12.png") }}') no-repeat right center;
      background-size: cover;
    }

    /* ── HERO CARD (dark maroon, like the chart card) ── */
    .hero-card {
      background: linear-gradient(135deg, var(--maroon2) 0%, #C1363A 55%, var(--maroon) 100%);
      border-radius: 24px;
      padding: clamp(18px, 3vw, 26px) clamp(18px, 3vw, 28px);
      box-shadow: 0 12px 40px rgba(165,44,48,.30);
      position: relative; overflow: hidden; margin-bottom: 20px;
    }
    .hero-card::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 220px; height: 220px; border-radius: 50%;
      background: rgba(255,255,255,.04);
    }
    .hero-card::after {
      content: ''; position: absolute; bottom: -60px; left: 35%;
      width: 280px; height: 280px; border-radius: 50%;
      background: rgba(255,255,255,.03);
    }
    .hero-inner {
      position: relative; z-index: 1;
      display: flex; align-items: center; justify-content: space-between; gap: 16px 20px; flex-wrap: wrap;
    }
    .hero-inner > div:first-child { flex: 1 1 240px; min-width: 0; }
    .hero-eyebrow { font-size: 0.68rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--gold); margin-bottom: 6px; }
    .hero-title {
      font-size: clamp(1.05rem, 2.2vw + 0.55rem, 1.3rem);
      font-weight: 800; color: #fff; letter-spacing: -.3px; margin-bottom: 6px;
      line-height: 1.3;
      overflow-wrap: anywhere;
    }
    .hero-sub {
      font-size: clamp(0.74rem, 0.25vw + 0.7rem, 0.8rem);
      color: rgba(255,255,255,.72); line-height: 1.6; max-width: 64ch;
      overflow-wrap: anywhere;
    }
    .hero-badges  { display: flex; gap: 8px; margin-top: 14px; flex-wrap: wrap; }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(255,255,255,.12); border: 1.5px solid rgba(255,255,255,.2);
      border-radius: 999px; padding: 5px 12px;
      font-size: 0.68rem; font-weight: 800; color: rgba(255,255,255,.9); letter-spacing: .04em;
    }
    .hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; }
    .btn-hero-white {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
      color: #fff; border-radius: 12px;
      padding: clamp(8px, 1.2vw, 10px) clamp(14px, 2.5vw, 18px);
      font-family: inherit;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700;
      cursor: pointer; transition: background .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
      text-align: center;
    }
    .btn-hero-white:hover { background: rgba(255,255,255,.22); }

    /* ── MAIN CONTENT GRID ── */
    .content-grid {
      display: grid; grid-template-columns: minmax(0, 280px) minmax(0, 1fr);
      gap: 16px; align-items: start;
    }

    /* ── SIDEBAR PANEL (selector card) ── */
    .side-panel {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden; position: sticky; top: 88px;
    }
    .side-panel-header {
      padding: 18px 20px; border-bottom: 1px solid var(--line);
      background: linear-gradient(135deg, rgba(165,44,48,.06), rgba(240,200,96,.06));
    }
    .side-panel-title { font-size: 0.88rem; font-weight: 800; color: var(--ink); }
    .side-panel-sub   { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
    .side-panel-body  { padding: 18px 20px; display: flex; flex-direction: column; gap: 12px; }

    /* IP type selector */
    .type-select-wrap { position: relative; }
    .type-select-wrap::after {
      content: ''; position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
      width: 0; height: 0; border-left: 5px solid transparent;
      border-right: 5px solid transparent; border-top: 6px solid var(--muted); pointer-events: none;
    }
    .type-select {
      width: 100%; border-radius: 14px; border: 1.5px solid var(--line);
      background: var(--bg); padding: 11px 16px;
      font-family: inherit; font-size: 0.82rem; font-weight: 600; color: var(--ink);
      outline: none; appearance: none; cursor: pointer;
      transition: border-color .2s, box-shadow .2s;
    }
    .type-select:focus { border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light); }

    /* Jump buttons */
    .jump-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 8px; }
    .jump-btn {
      padding: 9px 8px; border-radius: 12px; border: 1.5px solid var(--line);
      background: var(--bg); color: var(--muted);
      font-family: inherit;
      font-size: clamp(0.65rem, 0.12vw + 0.62rem, 0.7rem);
      font-weight: 700;
      cursor: pointer; text-align: center; transition: all .18s;
      min-width: 0; max-width: 100%;
    }
    .jump-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    /* Forms list */
    .forms-label { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); }
    .form-link-item {
      display: flex; align-items: center; gap: 10px;
      background: var(--bg); border-radius: 12px; padding: 11px 14px;
      border: 1.5px solid var(--line); cursor: pointer; text-decoration: none;
      transition: all .18s;
    }
    .form-link-item:hover { background: var(--maroon-light); border-color: var(--maroon); }
    .form-link-icon {
      width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center;
    }
    .form-link-text { font-size: 0.75rem; font-weight: 700; color: var(--ink); min-width: 0; overflow-wrap: anywhere; }

    /* Tip box */
    .tip-box {
      background: rgba(240,200,96,.1); border: 1.5px solid rgba(240,200,96,.3);
      border-radius: 12px; padding: 12px 14px;
      font-size: 0.72rem; color: var(--muted); line-height: 1.6;
      overflow-wrap: anywhere;
    }
    .tip-box strong { color: var(--ink); }

    /* ── MAIN PANEL ── */
    .main-panel {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden;
    }
    .main-panel-header {
      padding: 20px 24px; border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between; gap: 12px;
      flex-wrap: wrap;
      background: linear-gradient(90deg, rgba(165,44,48,.05), rgba(240,200,96,.05));
    }
    .main-panel-header > div:first-child { min-width: 0; flex: 1 1 200px; }
    .main-panel-title {
      font-size: clamp(0.88rem, 0.3vw + 0.8rem, 0.95rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .main-panel-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 2px; overflow-wrap: anywhere; }
    .main-panel-body  { padding: 24px; }

    /* ── INNER CONTENT LAYOUT (tabs + summary) ── */
    .inner-grid {
      display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 300px);
      gap: 16px; align-items: start;
    }

    /* ── TYPE HEADER CARD ── */
    .type-header-card {
      background: var(--bg); border-radius: 18px; padding: 18px 20px;
      border: 1px solid var(--line); margin-bottom: 14px;
    }
    .type-header-top { display: flex; align-items: start; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .type-header-top > div:first-child { min-width: 0; flex: 1 1 200px; }
    .type-eyebrow { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); }
    .type-title   {
      font-size: clamp(0.98rem, 0.35vw + 0.88rem, 1.1rem);
      font-weight: 800; color: var(--ink); margin-top: 4px; letter-spacing: -.2px;
      overflow-wrap: anywhere;
    }
    .type-sub     { font-size: 0.78rem; color: var(--muted); margin-top: 4px; line-height: 1.6; overflow-wrap: anywhere; }
    .ip-badge-group { display: flex; flex-direction: column; gap: 6px; flex-shrink: 0; }
    .ip-badge {
      display: inline-flex; align-items: center; gap: 7px;
      background: var(--card); border: 1.5px solid var(--line);
      border-radius: 10px; padding: 5px 12px;
      font-size: 0.68rem; font-weight: 800; color: var(--muted);
    }
    .ip-badge-dot { width: 6px; height: 6px; border-radius: 50%; }

    /* ── TABS ── */
    .tabs-bar { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 14px; }
    .tab-btn {
      display: inline-flex; align-items: center; justify-content: center;
      padding: 8px clamp(10px, 2vw, 16px); border-radius: 12px; border: 1.5px solid var(--line);
      background: var(--bg); color: var(--muted);
      font-family: inherit;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      cursor: pointer; transition: all .18s;
      flex: 0 1 auto; min-width: 0; max-width: 100%;
    }
    .tab-btn:hover { background: var(--card); border-color: #cbd5e1; color: var(--ink); }
    .tab-btn.active {
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border-color: transparent;
      box-shadow: 0 4px 14px rgba(165,44,48,.25);
    }

    /* ── STEP CARDS ── */
    .steps-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 12px; margin-top: 14px; }
    .step-card {
      background: var(--bg); border-radius: 16px; padding: 16px;
      border: 1px solid var(--line);
      transition: transform .2s, box-shadow .2s;
    }
    .step-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(15,23,42,.08); }
    .step-header { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .step-icon-wrap {
      width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      display: flex; align-items: center; justify-content: center;
      color: #fff; box-shadow: 0 4px 12px rgba(165,44,48,.22);
    }
    .step-num { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); }
    .step-title { font-size: 0.85rem; font-weight: 800; color: var(--ink); margin-top: 2px; overflow-wrap: anywhere; }
    .step-body  { font-size: 0.75rem; color: var(--muted); line-height: 1.6; overflow-wrap: anywhere; }

    /* ── REQ BLOCKS ── */
    .req-header {
      display: flex; align-items: start; justify-content: space-between; gap: 12px; margin-bottom: 14px;
      flex-wrap: wrap;
    }
    .req-header > div:first-child { min-width: 0; flex: 1 1 200px; }
    .req-title  { font-size: 0.88rem; font-weight: 800; color: var(--ink); overflow-wrap: anywhere; }
    .req-note   { font-size: 0.78rem; color: var(--muted); line-height: 1.6; margin-top: 3px; overflow-wrap: anywhere; }
    .req-icon-box {
      width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center;
    }
    .req-block {
      background: var(--bg); border-radius: 14px; padding: 14px 16px;
      border: 1px solid var(--line); margin-bottom: 10px;
    }
    .req-block-title {
      display: flex; align-items: center; gap: 8px;
      font-size: 0.72rem; font-weight: 800; color: var(--muted);
      text-transform: uppercase; letter-spacing: .05em; margin-bottom: 8px;
    }
    .req-block-icon {
      width: 24px; height: 24px; border-radius: 7px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .req-list { display: flex; flex-direction: column; gap: 5px; }
    .req-list-item {
      display: flex; align-items: flex-start; gap: 7px;
      font-size: 0.75rem; color: var(--muted); line-height: 1.5;
    }
    .req-list-item > span:last-child { min-width: 0; overflow-wrap: anywhere; }
    .req-list-dot {
      width: 5px; height: 5px; border-radius: 50%;
      background: var(--maroon); flex-shrink: 0; margin-top: 5px; opacity: .75;
    }
    .req-email-box {
      background: rgba(240,200,96,.1); border: 1.5px solid rgba(240,200,96,.3);
      border-radius: 12px; padding: 12px 16px; margin-top: 12px;
      font-size: 0.75rem; color: var(--muted);
    }
    .req-email-box strong { color: var(--ink); }

    /* ── CLAIM PANEL ── */
    .claim-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 12px; margin-top: 14px; }
    .claim-card {
      background: var(--bg); border-radius: 14px; padding: 14px 16px; border: 1px solid var(--line);
    }
    .claim-step-label {
      display: flex; align-items: center; gap: 8px;
      font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em;
      color: var(--muted); margin-bottom: 8px;
    }
    .claim-step-icon { width: 22px; height: 22px; border-radius: 6px; background: var(--maroon-light); color: var(--maroon); display: flex; align-items: center; justify-content: center; }
    .claim-body { font-size: 0.75rem; color: var(--muted); line-height: 1.6; overflow-wrap: anywhere; }
    .claim-note {
      background: rgba(240,200,96,.1); border: 1.5px solid rgba(240,200,96,.3);
      border-radius: 12px; padding: 12px 16px; margin-top: 12px;
      font-size: 0.75rem; color: var(--muted); line-height: 1.6;
    }

    /* ── SUMMARY CARD (right sticky) ── */
    .summary-card {
      background: var(--bg); border-radius: 18px; padding: 18px 20px;
      border: 1px solid var(--line);
    }
    .summary-card-title {
      font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
      letter-spacing: .06em; color: var(--muted); margin-bottom: 12px;
    }
    .summary-block {
      background: var(--card); border-radius: 12px; padding: 13px 14px;
      border: 1px solid var(--line); margin-bottom: 8px;
    }
    .summary-block-label {
      display: flex; align-items: center; gap: 7px;
      font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
      letter-spacing: .06em; color: var(--muted); margin-bottom: 6px;
    }
    .summary-block-icon {
      width: 20px; height: 20px; border-radius: 6px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center;
    }
    .summary-block-val  { font-size: 0.82rem; font-weight: 800; color: var(--maroon); }
    .summary-block-body { font-size: 0.75rem; color: var(--muted); line-height: 1.5; overflow-wrap: anywhere; }
    .checklist-item {
      display: flex; align-items: flex-start; gap: 7px;
      font-size: 0.75rem; color: var(--muted); line-height: 1.5; padding: 3px 0;
    }
    .checklist-item > span:last-child { min-width: 0; overflow-wrap: anywhere; }
    .checklist-dot { width: 5px; height: 5px; border-radius: 50%; background: var(--maroon); flex-shrink: 0; margin-top: 5px; opacity: .75; }
    .summary-note {
      background: var(--card); border-radius: 12px; padding: 12px 14px;
      border: 1px solid var(--line); margin-top: 8px;
      font-size: 0.72rem; color: var(--muted); line-height: 1.6;
    }
    .summary-note strong { color: var(--ink); }

    /* ── PANEL hidden ── */
    .tab-panel.hidden { display: none; }

    /* ── MODALS ── */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center;
      padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: #fff; border-radius: 24px; padding: clamp(20px, 4vw, 32px);
      width: min(420px, calc(100vw - 2rem)); max-width: 100%; position: relative;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      animation: fadeSlideUp .3s forwards;
      box-sizing: border-box;
    }
    .modal-box-wide {
      width: min(820px, calc(100vw - 2rem));
      max-width: 100%;
    }
    .modal-icon {
      width: 52px; height: 52px; border-radius: 16px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
    }
    .modal-title { font-size: clamp(1rem, 0.35vw + 0.92rem, 1.1rem); font-weight: 800; color: var(--ink); overflow-wrap: anywhere; }
    .modal-desc  { font-size: 0.82rem; color: var(--muted); margin-top: 6px; line-height: 1.6; overflow-wrap: anywhere; }
    .modal-btns {
      display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px;
      align-items: stretch;
    }
    .modal-btns .btn-cancel { flex: 1 1 120px; justify-content: center; min-width: 0; }
    .modal-btns .btn-confirm { flex: 1 1 140px; min-width: 0; justify-content: center; }
    .modal-preview-actions {
      display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 10px; margin-top: 16px;
    }
    .modal-preview-actions .btn-confirm,
    .modal-preview-actions .btn-cancel {
      flex: 0 1 auto; min-width: 0; max-width: 100%;
      padding: 10px clamp(14px, 3vw, 20px);
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.78rem);
    }
    .btn-cancel {
      padding: 12px; border-radius: 12px;
      border: 1.5px solid var(--line); background: none;
      font-family: inherit;
      font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem);
      font-weight: 700; color: var(--muted); cursor: pointer; transition: all .18s;
      display: inline-flex; align-items: center; box-sizing: border-box;
    }
    .btn-cancel:hover { background: var(--bg); }
    .btn-confirm {
      padding: 12px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none;
      font-family: inherit;
      font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem);
      font-weight: 700; cursor: pointer;
      box-shadow: 0 4px 14px rgba(165,44,48,.25); transition: all .18s;
      display: inline-flex; align-items: center; justify-content: center; text-decoration: none;
      box-sizing: border-box;
    }
    .btn-confirm:hover { box-shadow: 0 8px 20px rgba(165,44,48,.35); }
    .modal-close-x {
      position: absolute; top: 14px; right: 14px;
      width: 32px; height: 32px; border-radius: 8px;
      background: var(--bg); border: none; cursor: pointer;
      color: var(--muted); display: flex; align-items: center; justify-content: center; transition: background .15s;
    }
    .modal-close-x:hover { background: #e2e8f0; color: var(--ink); }

    /* ── ANIMATIONS ── */
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .anim { opacity: 0; animation: fadeSlideUp .5s forwards; }
    .anim-1 { animation-delay: .05s; }
    .anim-2 { animation-delay: .12s; }
    .anim-3 { animation-delay: .19s; }
    .anim-4 { animation-delay: .26s; }

    /* ── FOOTER ── */
    .page-footer {
      margin-top: 24px; padding: 16px 0;
      border-top: 1px solid var(--line);
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 8px 16px;
    }
    .page-footer-left, .page-footer-right { min-width: 0; overflow-wrap: anywhere; }
    .page-footer-left  { font-size: .72rem; color: var(--muted); }
    .page-footer-right { font-size: .72rem; font-family: 'DM Mono', monospace; color: #94a3b8; }

    .sticky-summary { position: sticky; top: 88px; }

    @media (max-width: 1000px) {
      .inner-grid { grid-template-columns: 1fr; }
      .steps-grid { grid-template-columns: 1fr; }
      .claim-grid { grid-template-columns: 1fr; }
      .sticky-summary { position: static; }
    }
    @media (max-width: 780px) {
      .content-grid { grid-template-columns: 1fr; }
      .side-panel { position: static; }
    }
    @media (max-width: 768px) {
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
      .topbar { min-height: 64px; }
      .page-subtitle { display: none; }
    }
    @media (max-width: 580px) {
      .guest-pill-long { display: none; }
      .jump-grid { grid-template-columns: minmax(0, 1fr); }
      .tab-btn { text-align: center; justify-content: center; }
    }
    @media (max-width: 480px) {
      .modal-btns { flex-direction: column; }
      .modal-btns .btn-cancel,
      .modal-btns .btn-confirm { flex: 0 0 auto; width: 100%; }
      .modal-preview-actions { flex-direction: column; align-items: stretch; }
      .modal-preview-actions .btn-confirm,
      .modal-preview-actions .btn-cancel { width: 100%; justify-content: center; }
      .main-panel-body { padding: clamp(16px, 4vw, 24px); }
    }
  </style>
</head>

<body>

  @php
    $guest       = $guest ?? (object)['name' => 'Guest Viewer', 'role' => 'Guest'];
    $urlHome     = url('/');
    $urlGuestHome= url('/guest');
    $urlGuestRec = url('/guest/records');
    $urlSupport  = url('/support');
    $urlHowTo    = url('/how-to-file');
    $urlForms    = url('/support');

    $guestInitials = collect(preg_split('/\s+/', trim($guest->name ?? ''), -1, PREG_SPLIT_NO_EMPTY))
      ->map(fn ($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
    if ($guestInitials === '') {
      $guestInitials = 'G';
    }
  @endphp

  {{-- ── SIDEBAR ── --}}
  <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
  <aside class="sidebar" id="mainSidebar" aria-label="Guest navigation">
    <div class="sidebar-logo">K</div>

    <nav class="sidebar-nav">
      <a href="{{ $urlGuestHome }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span class="nav-tooltip">Home</span>
      </a>

      <a href="{{ $urlGuestRec }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <span class="nav-tooltip">Records</span>
      </a>

      <a href="{{ $urlHowTo }}" class="nav-item active">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
        </svg>
        <span class="nav-tooltip">How to File</span>
      </a>

      
    </nav>

    <div class="sidebar-bottom">
      <a href="{{ $urlHome }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span class="nav-tooltip">Log Out</span>
      </a>
    </div>
  </aside>

  {{-- ── MAIN WRAP ── --}}
  <div class="main-wrap">

    {{-- TOPBAR --}}
    <header class="topbar">
      <div class="topbar-left">
        <button type="button" class="hamburger-btn" id="hamburgerBtn" aria-label="Open navigation menu" aria-expanded="false">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <line x1="3" y1="6"  x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <div class="topbar-titles">
          <div class="page-title">How to File</div>
          <div class="page-subtitle">IP Type Guide · IPOPHL + KTTM Checklist</div>
        </div>
      </div>
      <div class="topbar-right">
        <div class="guest-pill" title="{{ $guest->name }} · Guest">
          <span class="guest-pill-dot"></span>
          <span class="guest-pill-text">
            <span class="guest-pill-long">{{ $guest->name }} · </span>Guest
          </span>
        </div>
        <div class="avatar">{{ $guestInitials }}</div>
      </div>
    </header>

    {{-- CONTENT --}}
    <div class="content">

      {{-- HERO CARD --}}
      <div class="hero-card anim anim-1">
        <div class="hero-inner">
          <div>
            <div class="hero-eyebrow">How to File · IP Type Guide</div>
            <div class="hero-title">Choose an IP type — view Steps + Requirements in one clean layout.</div>
            <div class="hero-sub">Select an IP type on the left, then use the tabs to switch between Filing Steps, KTTM Requirements, and Incentive Claim details.</div>
            <div class="hero-badges">
              <span class="hero-badge"><span class="hero-badge-dot" style="background:var(--gold);"></span>IPOPHL Filing</span>
              <span class="hero-badge"><span class="hero-badge-dot" style="background:rgba(255,255,255,.7);"></span>BatStateU Incentive</span>
              <span class="hero-badge"><span class="hero-badge-dot" style="background:rgba(255,255,255,.5);"></span>View-only</span>
            </div>
          </div>
          <button id="openContactBtnHero" class="btn-hero-white" type="button">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
            </svg>
            Need help? Contact KTTM
          </button>
        </div>
      </div>

      {{-- CONTENT GRID --}}
      <div class="content-grid anim anim-2">

        {{-- LEFT SELECTOR PANEL --}}
        <aside class="side-panel">
          <div class="side-panel-header">
            <div class="side-panel-title">Select IP Type</div>
            <div class="side-panel-sub">Content updates instantly on change.</div>
          </div>
          <div class="side-panel-body">

            <div>
              <div class="forms-label" style="margin-bottom:8px;">IP Type</div>
              <div class="type-select-wrap">
                <select id="ipTypeSelect" class="type-select">
                  <option value="patent"   selected>Invention Patent</option>
                  <option value="utility">Utility Model</option>
                  <option value="design">Industrial Design</option>
                  <option value="trademark">Trademark</option>
                  <option value="copyright">Copyright</option>
                </select>
              </div>
            </div>

            <div class="jump-grid">
              <button id="jumpSteps" class="jump-btn" type="button">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline;margin-right:3px;"><polyline points="6 9 12 15 18 9"/></svg>
                Steps
              </button>
              <button id="jumpReq" class="jump-btn" type="button">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline;margin-right:3px;"><polyline points="6 9 12 15 18 9"/></svg>
                Requirements
              </button>
            </div>

            <div>
              <div class="forms-label" style="margin-bottom:8px;">Downloadable Forms</div>
              <div style="display:flex;flex-direction:column;gap:7px;">
                <a class="form-link-item formLink" href="#"
                   data-file="/forms/BatStateU-FO-RMS-05_Intellectual_Property_Evaluation_Form_Rev._02.docx"
                   data-preview="/forms/BatStateU-FO-RMS-05_Intellectual_Property_Evaluation_Form_Rev._02.pdf"
                   data-title="IP Evaluation Form">
                  <div class="form-link-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                  </div>
                  <div class="form-link-text">IP Evaluation Form</div>
                </a>
                <a class="form-link-item formLink" href="#"
                   data-file="/forms/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.docx"
                   data-preview="/forms/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.pdf"
                   data-title="Invention Disclosure Form">
                  <div class="form-link-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                  </div>
                  <div class="form-link-text">Invention Disclosure</div>
                </a>
                <a class="form-link-item formLink" href="#"
                   data-file="/forms/Copyright_Forms.docx"
                   data-preview="/forms/Copyright_Forms.pdf"
                   data-title="Copyright Form">
                  <div class="form-link-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                  </div>
                  <div class="form-link-text">Copyright Form</div>
                </a>
              </div>
            </div>

            <div class="tip-box">
              <strong>Tip:</strong> Use the <strong>tabs</strong> on the right panel to switch between Steps, Requirements, and Incentive Claim without scrolling.
            </div>

          </div>
        </aside>

        {{-- RIGHT MAIN PANEL --}}
        <main class="main-panel">
          <div class="main-panel-header">
            <div>
              <div class="main-panel-title">Filing Guide</div>
              <div class="main-panel-sub" id="ipSubtitle">Invention Patent steps and notes.</div>
            </div>
          </div>
          <div class="main-panel-body">
            <div class="inner-grid">

              {{-- LEFT: tabs + panels --}}
              <div>
                <div class="type-header-card">
                  <div class="type-header-top">
                    <div>
                      <div class="type-eyebrow">Selected IP Type</div>
                      <div class="type-title" id="ipTitle">Invention Patent</div>
                      <div class="type-sub">Use tabs below to navigate without scrolling.</div>
                    </div>
                    <div class="ip-badge-group">
                      <span class="ip-badge"><span class="ip-badge-dot" style="background:var(--maroon);"></span>IPOPHL</span>
                      <span class="ip-badge"><span class="ip-badge-dot" style="background:var(--gold2);"></span>BatStateU</span>
                    </div>
                  </div>
                  <div class="tabs-bar">
                    <button data-tab="steps"        class="tab-btn active">Steps</button>
                    <button data-tab="requirements" class="tab-btn">KTTM Requirements</button>
                    <button data-tab="claim"        class="tab-btn">Incentive Claim</button>
                  </div>
                </div>

                {{-- Steps Panel --}}
                <div id="panel-steps" class="tab-panel">
                  <div id="stepsAnchor" class="sr-only"></div>
                  <div id="ipSteps" class="steps-grid">
                    {{-- Injected by JS --}}
                  </div>
                </div>

                {{-- Requirements Panel --}}
                <div id="panel-requirements" class="tab-panel hidden">
                  <div id="reqAnchor" class="sr-only"></div>
                  <div id="ipRequirements">
                    {{-- Injected by JS --}}
                  </div>
                </div>

                {{-- Incentive Claim Panel --}}
                <div id="panel-claim" class="tab-panel hidden">
                  <div style="display:flex;align-items:start;justify-content:space-between;gap:12px;margin-bottom:14px;">
                    <div>
                      <div class="type-eyebrow">BatStateU Incentive Claim</div>
                      <div style="font-size:.88rem;font-weight:800;color:var(--ink);margin-top:4px;">Basic Claim Flow</div>
                    </div>
                    <div style="width:40px;height:40px;border-radius:12px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7H14a3.5 3.5 0 010 7H6"/>
                      </svg>
                    </div>
                  </div>
                  <div class="claim-grid">
                    <div class="claim-card">
                      <div class="claim-step-label">
                        <div class="claim-step-icon">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v12H5.2L4 17.2z"/></svg>
                        </div>
                        Step 1: Request
                      </div>
                      <div class="claim-body">Submit request letter for IP incentive and attach: <strong style="color:var(--ink);">Certificate of Registration (IPOPHL)</strong> and <strong style="color:var(--ink);">Authority to Collect</strong>.</div>
                    </div>
                    <div class="claim-card">
                      <div class="claim-step-label">
                        <div class="claim-step-icon">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                        </div>
                        Step 2: Verification
                      </div>
                      <div class="claim-body">Documents are reviewed and verified. If something is missing, the inventor/author is informed to comply.</div>
                    </div>
                  </div>
                  <div class="claim-note">
                    <strong style="color:var(--ink);">Eligibility:</strong> Full-time researchers, faculty, students, and employees affiliated with BatStateU/NEU at the time of application, and who served as inventor/author, may file the claim.
                  </div>
                </div>

              </div>

              {{-- RIGHT: Sticky summary --}}
              <aside class="sticky-summary">
                <div class="summary-card" id="ipSummaryCard">
                  {{-- Injected by JS --}}
                </div>
                <div class="summary-note" style="margin-top:10px;">
                  <strong>Note:</strong> Guests can view only. For official submission, coordinate with KTTM (Step 0).
                </div>
              </aside>

            </div>
          </div>
        </main>

      </div>

      {{-- FOOTER --}}
      <footer class="page-footer">
        <div class="page-footer-left">© {{ now()->year }} • KTTM Intellectual Property Services</div>
        <div class="page-footer-right">How to File · Guest View</div>
      </footer>

    </div>
  </div>

  {{-- CONTACT MODAL --}}
  <div class="modal-overlay" id="contactModal">
    <div class="modal-box">
      <button type="button" class="modal-close-x" data-close-contact>
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <div class="modal-icon" style="background:rgba(240,200,96,.15);color:#b8860b;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/>
        </svg>
      </div>
      <div class="modal-title">Contact KTTM</div>
      <div class="modal-desc">For questions or filing assistance, reach out to our team directly.</div>
      <div style="margin-top:16px;background:var(--bg);border-radius:14px;padding:16px 18px;">
        <div style="font-size:.68rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;">Email</div>
        <div style="font-size:.92rem;font-weight:800;color:var(--maroon);">itso@g.batstate-u.edu.ph</div>
        <div style="font-size:.72rem;color:var(--muted);margin-top:4px;">Copy and paste this address into your mail client.</div>
      </div>
      <div class="modal-btns">
        <button type="button" class="btn-cancel" data-close-contact>Close</button>
        <a href="mailto:itso@g.batstate-u.edu.ph" class="btn-confirm">Send Email</a>
      </div>
    </div>
  </div>

  {{-- FORM PREVIEW MODAL --}}
  <div class="modal-overlay" id="formPreviewModal">
    <div class="modal-box modal-box-wide">
      <button type="button" class="modal-close-x" data-close-form>
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <div class="modal-title" id="formPreviewLabel" style="margin-bottom:16px;"></div>
      <div style="width:100%;height:min(60vh,70dvh);min-height:200px;border-radius:12px;overflow:hidden;border:1px solid var(--line);">
        <iframe id="formPreviewObject" src="" width="100%" height="100%" style="border:none;">
          <p style="font-size:.82rem;color:var(--muted);padding:16px;">Preview not available. <a id="formDownloadLinkFallback" href="#" style="color:var(--maroon);">Download the file</a>.</p>
        </iframe>
      </div>
      <div class="modal-preview-actions">
        <a id="formDownloadLink" href="#" download class="btn-confirm">Download</a>
        <button type="button" data-close-form class="btn-cancel">Close</button>
      </div>
    </div>
  </div>

  <script>
  (function(){
    const KTTM_EMAIL = "itso@g.batstate-u.edu.ph";

    // ── Data ──
    const DATA = {
      patent: {
        icon:"beaker", title:"Invention Patent",
        subtitle:"For new inventions (product/process/technical improvement).",
        term:"20 years (maintenance fees yearly starting from the 5th year).",
        benefits:"Exclusive right to exclude others from making/using/selling the invention during the life of the patent.",
        kttm_eval:{
          title:"Send files to KTTM for evaluation",
          note:`All files are sent to <strong>${KTTM_EMAIL}</strong> for evaluation.`,
          blocks:[
            {h:"Required Forms",items:["IP Evaluation Form (BatStateU-FO-RMS-05)","Invention Disclosure Form (BatStateU-FO-RMS-08)"]},
            {h:"Description of the invention / patent",items:["Title","Brief statement of nature and purpose","Short explanation of the drawing (if any)","Detailed enabling description","Abstract of the invention"]},
            {h:"Drawing (if any)",items:["Place drawings in A4 size paper"]}
          ]
        },
        steps:[
          {icon:"edit",h:"Prepare technical documents",p:"Draft description, claims, abstract, and drawings (if applicable). Ensure novelty and inventiveness."},
          {icon:"send",h:"File at IPOPHL",p:"Submit the patent application and pay filing fees (online or via IPOPHL channels)."},
          {icon:"search",h:"Examinations",p:"Formality checks + substantive examination (technical review)."},
          {icon:"award",h:"Receive certificate / proof",p:"Keep the Certificate of Registration and related papers for incentive claim."},
          {icon:"coins",h:"Claim incentive (BatStateU)",p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification."}
        ],
        checklist:["Description, claims, abstract, drawings (if needed)","Inventor/owner details, campus/unit","Certificate of Registration (for incentive claim)","Authority to Collect"]
      },
      utility:{
        icon:"wrench", title:"Utility Model",
        subtitle:"For practical improvements (generally faster; no inventive-step requirement like patents).",
        term:"7 years (non-renewable).",
        benefits:"Exclusive right granted for an invention without requiring inventive step.",
        kttm_eval:{
          title:"Send files to KTTM for evaluation",
          note:`All files are sent to <strong>${KTTM_EMAIL}</strong> for evaluation.`,
          blocks:[
            {h:"Required Forms",items:["IP Evaluation Form (BatStateU-FO-RMS-05)","Invention Disclosure Form (BatStateU-FO-RMS-08)"]},
            {h:"Description of the utility model",items:["Title","Brief statement of nature and purpose","Short explanation of the drawing (if any)","Detailed enabling description","Abstract"]},
            {h:"Drawing (if any)",items:["Place drawings in A4 size paper"]}
          ]
        },
        steps:[
          {icon:"edit",h:"Prepare documents",p:"Write the technical description and claims. Include drawings if needed."},
          {icon:"send",h:"File at IPOPHL",p:"Submit the utility model application and pay fees."},
          {icon:"clock",h:"Review / processing",p:"Processing depends on IPOPHL rules; utility models typically move faster."},
          {icon:"award",h:"Receive certificate / proof",p:"Keep the Certificate of Registration or proof for your records."},
          {icon:"coins",h:"Claim incentive (BatStateU)",p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification."}
        ],
        checklist:["Description, claims, drawings (if needed)","Inventor/owner details, campus/unit","Certificate of Registration (for incentive claim)","Authority to Collect"]
      },
      design:{
        icon:"palette", title:"Industrial Design",
        subtitle:"Protects the appearance (shape/pattern/ornamentation), not the function.",
        term:"5 years, renewable twice (two consecutive periods of 5 years each).",
        benefits:"Prevents others from making/selling/importing articles bearing a substantially similar copy of the protected design.",
        kttm_eval:{
          title:"Send files to KTTM for evaluation",
          note:`All files are sent to <strong>${KTTM_EMAIL}</strong> for evaluation.`,
          blocks:[
            {h:"Required Form",items:["IP Evaluation Form (BatStateU-FO-RMS-05)"]},
            {h:"Design drawing specification",items:["Draw in A4 size paper with different views:","Top, Bottom, Left, Right, Front, Back, Isometric, Perspective"]}
          ]
        },
        steps:[
          {icon:"image",h:"Prepare design materials",p:"Prepare design drawings/images showing all views; add short design description."},
          {icon:"send",h:"File at IPOPHL",p:"Submit industrial design application with design representations and pay fees."},
          {icon:"search",h:"Review / publication",p:"Design applications are examined according to IPOPHL procedures."},
          {icon:"award",h:"Receive certificate / proof",p:"Once registered, keep the Certificate of Registration."},
          {icon:"coins",h:"Claim incentive (BatStateU)",p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification."}
        ],
        checklist:["Design drawings/images (required views)","Owner/creator details, campus/unit","Certificate of Registration (for incentive claim)","Authority to Collect"]
      },
      trademark:{
        icon:"badge", title:"Trademark",
        subtitle:"Protects your brand name, logo, sign, symbol, or slogan.",
        term:"10 years, renewable for 10 years at a time.",
        benefits:"Gives the owner the exclusive right to prevent others from using/exploiting the mark.",
        kttm_eval:{
          title:"Send files to KTTM for evaluation",
          note:`All files are sent to <strong>${KTTM_EMAIL}</strong> for evaluation.`,
          blocks:[
            {h:"Required Form",items:["IP Evaluation Form (BatStateU-FO-RMS-06)"]},
            {h:"Digital image of the trademark",items:["JPEG format, not exceeding 1MB","2 in × 3 in (50.8 mm × 76.2 mm)","Black & white unless claiming color(s)"]}
          ]
        },
        extraGuidelines:`<div style="margin-top:12px;background:rgba(240,200,96,.08);border:1.5px solid rgba(240,200,96,.25);border-radius:12px;padding:14px 16px;">
          <div style="font-size:.72rem;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Additional Notes</div>
          <div style="font-size:.75rem;color:var(--muted);line-height:1.6;">
            Identify the class index where your IP belongs by referring to the
            <a href="https://www.wipo.int/classifications/nice/" target="_blank" style="color:var(--maroon);font-weight:700;">WIPO Nice Classification</a>.
            Provide the image file of your logo, program name, or tagline to be protected.
          </div>
        </div>`,
        steps:[
          {icon:"scan",h:"Check availability (recommended)",p:"Do a trademark search to see if a similar mark already exists."},
          {icon:"package",h:"Prepare filing details",p:"Prepare mark/logo, owner info, and goods/services classification."},
          {icon:"send",h:"File at IPOPHL",p:"Submit the trademark application and pay fees."},
          {icon:"shield",h:"Publication / opposition",p:"Application may be published; third parties can oppose depending on the process."},
          {icon:"award",h:"Receive certificate / proof",p:"Once registered, keep the Certificate of Registration."},
          {icon:"coins",h:"Claim incentive (BatStateU)",p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification."}
        ],
        checklist:["Mark/logo file + short description","Goods/services classification","Owner details, campus/unit","Certificate of Registration (for incentive claim)","Authority to Collect"]
      },
      copyright:{
        icon:"book", title:"Copyright",
        subtitle:"Protects literary, artistic works (including software).",
        term:"Lifetime of the author plus 50 years.",
        benefits:"Creator holds the exclusive right to use/authorize others to use the work on agreed terms.",
        kttm_eval:{
          title:"Send files to KTTM for evaluation",
          note:`All files are sent to <strong>${KTTM_EMAIL}</strong> for evaluation.`,
          blocks:[
            {h:"Copies and forms",items:[
              "Three (3) hard copies + one (1) soft copy of duly accomplished and notarized Application for Copyright Registration Form (Typewritten)",
              "Three (3) hard copies + one (1) soft copy of Affidavit (Notarized) and Copyright Forms",
              `Two (2) soft copies of the work in CD with label of Title and Authors`,
              `One (1) soft copy of the work to be sent to ITSO email address (${KTTM_EMAIL})`
            ]},
            {h:"Packaging",items:[
              "Secure all requirements in one long brown envelope",
              "Ensure correct title on notarized application, NLP on affidavit, and co-ownership forms are matched"
            ]}
          ]
        },
        extraGuidelines:`<div style="margin-top:12px;background:rgba(240,200,96,.08);border:1.5px solid rgba(240,200,96,.25);border-radius:12px;padding:14px 16px;">
          <div style="font-size:.72rem;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Important Note</div>
          <div style="font-size:.75rem;color:var(--muted);line-height:1.6;">
            Ensure that the title on the notarized Application for Copyright Registration Form, NLP Affidavit, and Affidavit on Copyright Co-ownership are consistent and exactly matched.
            Please also send a soft copy of the materials to be copyrighted to <strong style="color:var(--ink);">${KTTM_EMAIL}</strong>.
          </div>
        </div>`,
        steps:[
          {icon:"folder",h:"Prepare your work and details",p:"Prepare copies of the work and author/ownership details."},
          {icon:"info",h:"Registration (optional but helpful)",p:"Copyright exists upon creation; registration helps strengthen evidence."},
          {icon:"send",h:"Submit requirements",p:"Submit the required forms, copies, and any email/CD requirements based on your institution workflow."},
          {icon:"award",h:"Receive proof (if registered)",p:"Keep proof/certificate if registration is completed."},
          {icon:"coins",h:"Claim incentive (BatStateU)",p:"Submit Request Letter + Certificate of Registration (if any) + Authority to Collect for verification."}
        ],
        checklist:["Copy of work + required forms/affidavit","Author/owner details, campus/unit","Certificate/proof (if registered for incentive claim)","Authority to Collect"]
      }
    };

    // ── Icon SVG map ──
    const S = `fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"`;
    const ICONS = {
      edit:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4Z"/></svg>`,
      send:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4Z"/></svg>`,
      search:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>`,
      award:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>`,
      coins:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6"/><path d="M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/></svg>`,
      clock:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>`,
      image:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m8 13 2-2 4 4 2-2 3 3"/><path d="M8.5 9.5h.01"/></svg>`,
      scan:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M4 7V5a2 2 0 012-2h2"/><path d="M20 7V5a2 2 0 00-2-2h-2"/><path d="M4 17v2a2 2 0 002 2h2"/><path d="M20 17v2a2 2 0 01-2 2h-2"/><path d="M7 12h10"/></svg>`,
      package:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M16.5 9.4 7.5 4.2"/><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73Z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/></svg>`,
      shield:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M12 2 4 5v6c0 5 3.5 9.4 8 11 4.5-1.6 8-6 8-11V5Z"/><path d="M9 12l2 2 4-4"/></svg>`,
      folder:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M3 7a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2Z"/></svg>`,
      info:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>`,
      palette:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M12 22a10 10 0 110-20 6 6 0 010 12h-1a2 2 0 000 4h1a2 2 0 010 4Z"/><path d="M7.5 10.5h.01"/><path d="M9.5 6.5h.01"/><path d="M14.5 6.5h.01"/><path d="M16.5 10.5h.01"/></svg>`,
      badge:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M12 2 4 5v6c0 5 3.5 9.4 8 11 4.5-1.6 8-6 8-11V5Z"/></svg>`,
      book:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2Z"/></svg>`,
      check:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M20 6 9 17l-5-5"/></svg>`,
      file:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>`,
      mail:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/></svg>`,
      beaker:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M9 3v3l-5 9a4 4 0 003.5 6h9A4 4 0 0020 15l-5-9V3"/><path d="M8 12h8"/></svg>`,
      wrench:`<svg class="h-5 w-5" viewBox="0 0 24 24" ${S}><path d="M14.7 6.3a4 4 0 00-5.7 5.7L3 18l3 3 6-6a4 4 0 005.7-5.7l-3 3-2-2 3-3Z"/></svg>`,
    };
    function icon(name){ return ICONS[name] || ICONS.info; }

    // ── Elements ──
    const selectEl   = document.getElementById('ipTypeSelect');
    const subtitleEl = document.getElementById('ipSubtitle');
    const titleEl    = document.getElementById('ipTitle');
    const stepsEl    = document.getElementById('ipSteps');
    const reqEl      = document.getElementById('ipRequirements');
    const summaryEl  = document.getElementById('ipSummaryCard');

    // ── Tab logic ──
    const tabBtns = Array.from(document.querySelectorAll('.tab-btn'));
    const panels  = Array.from(document.querySelectorAll('.tab-panel'));

    function setTab(key){
      tabBtns.forEach(b => {
        const active = b.getAttribute('data-tab') === key;
        b.classList.toggle('active', active);
      });
      panels.forEach(p => {
        p.classList.toggle('hidden', p.id !== 'panel-' + key);
      });
    }
    tabBtns.forEach(b => b.addEventListener('click', () => setTab(b.getAttribute('data-tab'))));
    setTab('steps');

    // ── Render ──
    function render(key){
      const d = DATA[key] || DATA.patent;
      subtitleEl.textContent = d.subtitle;
      titleEl.textContent    = d.title;

      // Steps
      stepsEl.innerHTML = d.steps.map((s, i) => `
        <div class="step-card">
          <div class="step-header">
            <div class="step-icon-wrap">${icon(s.icon)}</div>
            <div>
              <div class="step-num">Step ${i+1}</div>
              <div class="step-title">${s.h}</div>
            </div>
          </div>
          <div class="step-body">${s.p}</div>
        </div>
      `).join('');

      // Requirements
      if(d.kttm_eval){
        reqEl.innerHTML = `
          <div class="req-header">
            <div>
              <div class="req-title">${d.kttm_eval.title}</div>
              <div class="req-note">${d.kttm_eval.note}</div>
            </div>
            <div class="req-icon-box">${icon('mail')}</div>
          </div>
          ${(d.kttm_eval.blocks||[]).map(b=>`
            <div class="req-block">
              <div class="req-block-title">
                <div class="req-block-icon">${icon('file')}</div>
                ${b.h}
              </div>
              <div class="req-list">
                ${(b.items||[]).map(x=>`<div class="req-list-item"><span class="req-list-dot"></span><span>${x}</span></div>`).join('')}
              </div>
            </div>
          `).join('')}
          <div class="req-email-box"><strong>Send to:</strong> ${KTTM_EMAIL}</div>
          ${d.extraGuidelines || ''}
        `;
      } else {
        reqEl.innerHTML = `<div style="font-size:.78rem;color:var(--muted);">No requirements data found for this type.</div>`;
      }

      // Summary card
      summaryEl.innerHTML = `
        <div style="display:flex;align-items:start;justify-content:space-between;gap:10px;margin-bottom:12px;">
          <div>
            <div class="summary-card-title">At a glance</div>
            <div style="font-size:.9rem;font-weight:800;color:var(--ink);">${d.title}</div>
          </div>
          <div style="width:38px;height:38px;border-radius:12px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;flex-shrink:0;">${icon(d.icon||'info')}</div>
        </div>

        <div class="summary-block">
          <div class="summary-block-label">
            <div class="summary-block-icon">${icon('badge')}</div>
            Benefits
          </div>
          <div class="summary-block-body">${d.benefits}</div>
        </div>

        <div class="summary-block">
          <div class="summary-block-label">
            <div class="summary-block-icon">${icon('clock')}</div>
            Term of Protection
          </div>
          <div class="summary-block-val">${d.term}</div>
        </div>

        <div class="summary-block">
          <div class="summary-block-label">
            <div class="summary-block-icon">${icon('check')}</div>
            Quick Checklist
          </div>
          <div style="margin-top:4px;">
            ${d.checklist.map(x=>`<div class="checklist-item"><span class="checklist-dot"></span><span>${x}</span></div>`).join('')}
          </div>
        </div>
      `;
    }

    selectEl?.addEventListener('change', () => render(selectEl.value));
    render('patent');

    // ── Jump buttons ──
    document.getElementById('jumpSteps')?.addEventListener('click', () => {
      setTab('steps');
      document.getElementById('stepsAnchor')?.scrollIntoView({behavior:'smooth',block:'start'});
    });
    document.getElementById('jumpReq')?.addEventListener('click', () => {
      setTab('requirements');
      document.getElementById('reqAnchor')?.scrollIntoView({behavior:'smooth',block:'start'});
    });

    function clearFormPreviewIframe() {
      document.getElementById('formPreviewObject')?.setAttribute('src', '');
    }

    function syncBodyScrollLock() {
      const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
      const anyModal = ['contactModal', 'formPreviewModal'].some(
        mid => document.getElementById(mid)?.classList.contains('open')
      );
      document.body.style.overflow = (sidebarOpen || anyModal) ? 'hidden' : '';
    }

    function openModal(id) {
      document.getElementById(id)?.classList.add('open');
      syncBodyScrollLock();
    }
    function closeModal(id) {
      document.getElementById(id)?.classList.remove('open');
      syncBodyScrollLock();
    }

    function closeFormPreviewModal() {
      clearFormPreviewIframe();
      closeModal('formPreviewModal');
    }

    // Contact
    ['openContactBtn','openContactBtnHero'].forEach(id =>
      document.getElementById(id)?.addEventListener('click', () => openModal('contactModal'))
    );
    document.querySelectorAll('[data-close-contact]').forEach(b => b.addEventListener('click', () => closeModal('contactModal')));
    document.getElementById('contactModal')?.addEventListener('click', e => { if(e.target.id==='contactModal') closeModal('contactModal'); });

    // Form preview
    document.querySelectorAll('.formLink').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        const file    = btn.getAttribute('data-file');
        const preview = btn.getAttribute('data-preview') || file;
        const title   = btn.getAttribute('data-title') || '';
        document.getElementById('formPreviewLabel').textContent = title;
        document.getElementById('formPreviewObject').setAttribute('src', preview);
        document.getElementById('formDownloadLink').setAttribute('href', file);
        document.getElementById('formDownloadLinkFallback')?.setAttribute('href', file);
        openModal('formPreviewModal');
      });
    });
    document.querySelectorAll('[data-close-form]').forEach(b => b.addEventListener('click', () => closeFormPreviewModal()));
    document.getElementById('formPreviewModal')?.addEventListener('click', e => {
      if (e.target.id === 'formPreviewModal') closeFormPreviewModal();
    });

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

    hamburgerBtn?.addEventListener('click', function(ev) {
      ev.stopPropagation();
      const isOpen = mainSidebar?.classList.contains('mobile-open');
      isOpen ? closeMobileSidebar() : openMobileSidebar();
    });

    sidebarBackdrop?.addEventListener('click', closeMobileSidebar);

    mainSidebar?.querySelectorAll('a.nav-item').forEach(link => {
      link.addEventListener('click', () => { if (window.innerWidth <= 768) closeMobileSidebar(); });
    });

    window.addEventListener('resize', function() {
      if (window.innerWidth > 768) closeMobileSidebar();
    });

    document.addEventListener('keydown', e => {
      if (e.key !== 'Escape') return;
      if (document.getElementById('formPreviewModal')?.classList.contains('open')) closeFormPreviewModal();
      else if (document.getElementById('contactModal')?.classList.contains('open')) closeModal('contactModal');
      else if (mainSidebar?.classList.contains('mobile-open')) closeMobileSidebar();
    });

  })();
  </script>

</body>
</html>