{{-- resources/views/Modifiedrecords.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Record Detail</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon:       #A52C30;
      --maroon2:      #7E1F23;
      --maroon3:      #C1363A;
      --maroon-light: rgba(165,44,48,0.10);
      --gold:         #F0C860;
      --gold2:        #E8B857;
      --ink:          #0F172A;
      --muted:        #64748B;
      --line:         rgba(15,23,42,.08);
      --card:         #FFFFFF;
      --sidebar-w:    72px;
      --bg:           #F1F4F9;

      /* ── Maroon-based "deep" palette ── */
      --deep:       #7E1F23;
      --deep2:      #6B1A1D;
      --deep3:      #8C2428;
      --deep-line:  rgba(255,255,255,.12);
      --deep-muted: rgba(255,255,255,.55);
      --pad-x:      clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max:  1440px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { -webkit-font-smoothing: antialiased; scroll-behavior: smooth; }

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
      box-shadow: 4px 0 24px rgba(165,44,48,.22);
    }
    .sidebar-logo {
      width: 42px; height: 42px; border-radius: 14px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 800; font-size: 1rem; color: #2a1a0b;
      margin-bottom: 32px; flex-shrink: 0;
      box-shadow: 0 6px 18px rgba(240,200,96,.35);
    }
    .sidebar-nav { display: flex; flex-direction: column; align-items: center; gap: 6px; flex: 1; width: 100%; }
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

    /* ══════════ MAIN LAYOUT ══════════ */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ══════════ TOPBAR ══════════ */
    .topbar {
      min-height: 68px;
      background: #fff;
      color: var(--ink);
      border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 10px var(--pad-x);
      position: sticky; top: 0; z-index: 40;
      box-shadow: 0 2px 16px rgba(15,23,42,.08);
    }
    .topbar-left  {
      display: flex; align-items: center; gap: 12px;
      min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; }
    .topbar-right {
      display: flex; align-items: center; justify-content: flex-end;
      flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
    }

    .back-btn {
      width: 36px; height: 36px; border-radius: 10px;
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      color: var(--muted); text-decoration: none;
      transition: all .18s;
      flex-shrink: 0;
    }
    .back-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    .topbar .page-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.95rem, 0.4vw + 0.85rem, 1.08rem);
      font-weight: 800; color: var(--maroon); letter-spacing: -.2px;
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .topbar .page-sub {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--ink); font-weight: 500; margin-top: 1px;
      overflow-wrap: anywhere;
    }

    .record-id-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: rgba(240,200,96,.12); border: 1px solid rgba(240,200,96,.25);
      color: var(--maroon); padding: 4px 12px; border-radius: 20px;
      font-family: 'DM Mono', monospace; font-size: clamp(0.62rem, 0.12vw + 0.6rem, 0.7rem); font-weight: 700;
      flex-shrink: 0; max-width: 100%;
      overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    .btn-primary {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: clamp(8px, 1.2vw, 9px) clamp(14px, 2.5vw, 18px);
      border-radius: 11px; text-decoration: none;
      box-shadow: 0 4px 16px rgba(165,44,48,.35);
      transition: transform .18s, box-shadow .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(165,44,48,.45); }

    .btn-gold {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b; border: none; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: clamp(7px, 1.2vw, 9px) clamp(12px, 2.2vw, 18px);
      border-radius: 11px; text-decoration: none;
      box-shadow: 0 4px 14px rgba(240,200,96,.30);
      transition: transform .18s, opacity .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
      white-space: nowrap;
    }
    .btn-gold:hover { transform: translateY(-1px); opacity: .9; }

    .btn-outline {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: var(--bg); color: var(--muted);
      border: 1.5px solid var(--line); cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 600;
      padding: clamp(8px, 1.2vw, 9px) clamp(14px, 2.2vw, 18px);
      border-radius: 11px; text-decoration: none;
      transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-outline:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    .btn-edit {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: var(--bg); color: var(--ink);
      border: 1.5px solid var(--line); cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: clamp(7px, 1.2vw, 8px) clamp(12px, 2vw, 16px);
      border-radius: 11px; text-decoration: none;
      transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-edit:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    .btn-archive {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: var(--bg); color: var(--muted);
      border: 1.5px solid var(--line); cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: clamp(7px, 1.2vw, 8px) clamp(12px, 2vw, 16px);
      border-radius: 11px;
      transition: all .18s; opacity: .75;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-archive:hover { background: #fef9ec; border-color: #d97706; color: #d97706; opacity: 1; }

    .avatar {
      font-family: 'Plus Jakarta Sans', sans-serif;
      width: 40px; height: 40px; border-radius: 12px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.85rem; color: #2a1a0b;
      flex-shrink: 0;
    }

    /* ══════════ HERO RECORD CARD ══════════ */
    .hero-card {
      background: linear-gradient(135deg, var(--maroon2) 0%, #C1363A 55%, var(--maroon) 100%);
      border-radius: 22px;
      padding: clamp(18px, 3vw, 24px) clamp(18px, 3vw, 28px);
      box-shadow: 0 12px 40px rgba(165,44,48,.30);
      position: relative; overflow: hidden; margin-bottom: 20px;
    }
    .hero-card::selection { background: rgba(255,255,255,.18); }
    /* decorative orbs */
    .hero-card::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 220px; height: 220px; border-radius: 50%; background: rgba(255,255,255,.04);
    }
    .hero-card::after {
      content: ''; position: absolute; bottom: -60px; left: 35%;
      width: 280px; height: 280px; border-radius: 50%; background: rgba(255,255,255,.03);
    }
    .hero-inner {
      position: relative; z-index: 1;
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 18px;
      padding: clamp(2px, .3vw, 4px);
    }

    .hero-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px 20px; margin-bottom: 20px; position: relative; z-index: 2;
    }
    .hero-top > div:first-child { min-width: 0; flex: 1 1 220px; }
    .hero-eyebrow {
      font-family: 'DM Mono', monospace;
      font-size: 0.68rem; font-weight: 800; letter-spacing: .1em;
      text-transform: uppercase; color: var(--gold); margin-bottom: 6px;
    }
    .hero-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(1.05rem, 2.2vw + 0.55rem, 1.25rem);
      font-weight: 800; color: #fff;
      letter-spacing: -.3px; margin-bottom: 8px; line-height: 1.3;
      overflow-wrap: anywhere;
    }
    .hero-title-placeholder { font-family: 'Plus Jakarta Sans', sans-serif; color: rgba(255,255,255,.25); font-style: italic; font-size: 1.2rem; }

    .hero-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
    .hero-badge {
      font-family: 'Plus Jakarta Sans', sans-serif;
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 10px; border-radius: 999px;
      font-size: 0.64rem; font-weight: 800; letter-spacing: .02em;
      border: 1px solid transparent;
    }
    .hb-type    { background: rgba(240,200,96,.15); color: var(--gold); border-color: rgba(240,200,96,.25); }
    .hb-campus  { background: rgba(96,200,240,.10); color: #7dd3fc; border-color: rgba(96,200,240,.2); }
    .hb-status  { background: rgba(52,211,153,.12); color: #34d399; border-color: rgba(52,211,153,.22); }
    .hb-status.st-attention { background: rgba(251,146,60,.12); color: #fb923c; border-color: rgba(251,146,60,.22); }
    .hb-status.st-default   { background: rgba(148,163,184,.10); color: #94a3b8; border-color: rgba(148,163,184,.2); }

    /* ── Hero fields grid ── */
    .hero-fields {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1px;
      background: rgba(255,255,255,.11);
      border-radius: 14px; overflow: hidden;
      border: 1px solid rgba(255,255,255,.10); position: relative; z-index: 2; margin-bottom: 10px;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.03);
    }
    .hero-field.span2 { grid-column: span 2; }
    .hero-field {
      background: rgba(255,255,255,.04); padding: 13px 16px;
      transition: background .2s;
    }
    .hero-field:hover { background: rgba(255,255,255,.08); }
    .hf-label {
      font-family: 'DM Mono', monospace;
      font-size: 0.56rem; font-weight: 700; letter-spacing: .16em;
      text-transform: uppercase; color: rgba(255,255,255,.44); margin-bottom: 6px;
    }
    .hf-value {
      font-size: 0.84rem; font-weight: 800; color: rgba(255,255,255,.96); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .hf-value.empty { color: rgba(255,255,255,.2); font-style: italic; font-weight: 400; }
    .hf-value a {
      color: var(--gold); text-decoration: none;
      display: inline-flex; align-items: center; gap: 5px;
    }
    .hf-value a:hover { text-decoration: underline; }

    /* ── Owner full row ── */
    .hero-owner-row {
      background: rgba(0,0,0,.14); border-radius: 12px; padding: 11px 16px;
      border: 1px solid rgba(255,255,255,.08); position: relative; z-index: 2;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.02);
    }
    .hero-owner-row .hf-value { white-space: normal; }

    /* ══════════ CONTENT AREA ══════════ */
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
    /* ══════════ MAIN GRID ══════════ */
    .main-grid {
      display: grid;
      grid-template-columns: minmax(0, 260px) minmax(0, 1fr);
      gap: 18px; align-items: start;
    }

    /* ══════════ FILTER PANEL ══════════ */
    .filter-panel {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden; position: sticky; top: 88px;
    }
    .fp-header {
      padding: 14px 18px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      border-bottom: 1px solid var(--deep-line);
    }
    .fp-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.85rem; font-weight: 800; color: #fff; }
    .fp-sub   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.68rem; color: var(--deep-muted); margin-top: 2px; }
    .fp-body  { padding: 16px; display: flex; flex-direction: column; gap: 12px; }

    .field-label {
      font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700;
      letter-spacing: .12em; text-transform: uppercase; color: var(--muted);
      margin-bottom: 5px; display: block;
    }
    .field-input, .field-select {
      width: 100%; padding: 9px 13px;
      border: 1.5px solid var(--line); background: var(--bg);
      border-radius: 11px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem;
      color: var(--ink); outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .field-input:focus, .field-select:focus {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light); background: #fff;
    }
    .fp-btn-row { display: flex; flex-wrap: wrap; gap: 7px; }
    .fp-apply {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 9px 12px; border-radius: 10px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.76rem);
      font-weight: 700;
      transition: opacity .18s;
      box-sizing: border-box;
    }
    .fp-apply:hover { opacity: .88; }
    .fp-reset {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 9px 12px; border-radius: 10px;
      background: var(--bg); color: var(--muted);
      border: 1.5px solid var(--line); cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.76rem);
      font-weight: 600;
      transition: all .18s;
      box-sizing: border-box;
    }
    .fp-reset:hover { border-color: var(--maroon); color: var(--maroon); }
    .fp-count {
      font-family: 'DM Mono', monospace; font-size: 0.65rem; color: var(--muted);
      text-align: center; padding: 4px 0;
    }

    /* ══════════ TIMELINE CARD ══════════ */
    .timeline-card {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden;
    }
    .tc-header {
      padding: 16px 22px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      border-bottom: 1px solid var(--deep-line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 10px 14px;
    }
    .tc-header > div:first-child { min-width: 0; flex: 1 1 200px; }
    .tc-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.92rem; font-weight: 800; color: #fff; }
    .tc-sub   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.7rem; color: var(--deep-muted); margin-top: 2px; }
    .tc-badge {
      flex-shrink: 0;
      font-family: 'DM Mono', monospace; font-size: 0.66rem; font-weight: 700;
      color: var(--gold); background: rgba(240,200,96,.12);
      border: 1px solid rgba(240,200,96,.22); padding: 4px 12px; border-radius: 20px;
    }

    .timeline-body {
      padding: 20px 22px;
      max-height: 68vh; overflow-y: auto;
    }
    .timeline-body::-webkit-scrollbar { width: 4px; }
    .timeline-body::-webkit-scrollbar-thumb { background: linear-gradient(180deg, var(--gold), var(--maroon)); border-radius: 999px; }

    /* ── True vertical timeline ── */
    .tl-list { position: relative; padding-left: 28px; }
    .tl-list::before {
      content: ''; position: absolute; left: 8px; top: 8px; bottom: 8px;
      width: 2px;
      background: linear-gradient(180deg, var(--maroon) 0%, var(--gold) 50%, rgba(15,23,42,.1) 100%);
      border-radius: 2px;
    }

    .tl-item {
      position: relative; margin-bottom: 14px; cursor: pointer;
    }
    .tl-item:last-child { margin-bottom: 0; }

    /* dot on the line */
    .tl-dot {
      position: absolute; left: -24px; top: 14px;
      width: 14px; height: 14px; border-radius: 50%;
      border: 2px solid var(--card);
      box-shadow: 0 0 0 2px currentColor;
      z-index: 2; transition: transform .2s;
    }
    .tl-item:hover .tl-dot { transform: scale(1.25); }
    .tl-dot.dot-created  { color: #10b981; background: #10b981; }
    .tl-dot.dot-modified { color: var(--gold); background: var(--gold); }
    .tl-dot.dot-archived { color: var(--maroon); background: var(--maroon); }
    .tl-dot.dot-default  { color: #94a3b8; background: #94a3b8; }

    .tl-card {
      background: var(--bg); border-radius: 14px;
      border: 1.5px solid var(--line); padding: 13px 16px;
      transition: background .15s, border-color .15s, box-shadow .15s, transform .15s;
    }
    .tl-item:hover .tl-card {
      background: #fff; border-color: rgba(165,44,48,.2);
      box-shadow: 0 4px 20px rgba(165,44,48,.09);
      transform: translateX(3px);
    }

    .tl-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .tl-left { flex: 1; min-width: 0; }
    .tl-badge-row { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 5px; }

    .action-badge {
      font-family: 'Plus Jakarta Sans', sans-serif;
      display: inline-flex; align-items: center; gap: 4px;
      padding: 2px 9px; border-radius: 20px;
      font-size: 0.64rem; font-weight: 700; border: 1px solid transparent;
    }
    .badge-created  { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .badge-modified { background: #fffbeb; color: #92400e; border-color: #fde68a; }
    .badge-archived { background: #fff1f2; color: #9f1239; border-color: #fecdd3; }
    .badge-default  { background: #f8fafc; color: #475569; border-color: #e2e8f0; }

    .tl-time {
      font-family: 'DM Mono', monospace; font-size: 0.62rem;
      color: var(--muted); white-space: nowrap;
    }
    .tl-summary {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
      font-weight: 700; color: var(--maroon);
      overflow-wrap: anywhere;
    }
    .tl-actor   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.7rem; color: var(--muted); margin-top: 3px; }
    .tl-arrow   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.7rem; color: var(--muted); flex-shrink: 0; padding-top: 2px; }

    /* ── inline changes ── */
    .tl-changes { margin-top: 10px; display: flex; flex-direction: column; gap: 6px; }
    .chg-row {
      background: #fff; border: 1px solid var(--line);
      border-radius: 10px; padding: 9px 12px;
    }
    .chg-field { font-family: 'DM Mono', monospace; font-size: 0.62rem; font-weight: 700; color: var(--maroon); margin-bottom: 4px; letter-spacing: .06em; text-transform: uppercase; }
    .chg-old   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.72rem; color: #b91c1c; }
    .chg-new   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.72rem; color: #065f46; margin-top: 2px; }
    .chg-none  { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.72rem; color: var(--muted); margin-top: 6px; font-style: italic; }

    .tl-empty {
      font-family: 'Plus Jakarta Sans', sans-serif;
      text-align: center; padding: 60px 20px;
      font-size: .8rem; color: var(--muted);
    }
    .tl-empty-icon { font-size: 2rem; margin-bottom: 10px; opacity: .3; }

    /* ══════════ MODAL ══════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(10,14,20,.65); backdrop-filter: blur(8px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: var(--card); border-radius: 22px;
      box-shadow: 0 40px 100px rgba(0,0,0,.22);
      width: min(560px, calc(100vw - 2rem));
      max-width: 100%;
      max-height: 90vh; overflow-y: auto;
      animation: modalIn .28s cubic-bezier(.17,.67,.35,1.08);
      box-sizing: border-box;
    }
    @keyframes modalIn { from { opacity:0; transform:translateY(22px) scale(.97); } to { opacity:1; transform:none; } }

    .modal-header {
      padding: 20px 24px 16px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      border-bottom: 1px solid rgba(255,255,255,.12);
      display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
      position: sticky; top: 0; z-index: 5;
    }
    .modal-header > div:first-child { min-width: 0; flex: 1; }
    .modal-eyebrow { font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: var(--gold); margin-bottom: 4px; }
    .modal-title   {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.9rem, 0.35vw + 0.82rem, 1.05rem);
      font-weight: 800; color: #fff; letter-spacing: -.2px;
      overflow-wrap: anywhere; line-height: 1.25;
    }
    .modal-close {
      width: 32px; height: 32px; border-radius: 9px;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.7); flex-shrink: 0;
      transition: all .15s;
    }
    .modal-close:hover { background: rgba(255,255,255,.22); color: #fff; }
    .modal-body   { padding: 20px 24px; display: flex; flex-direction: column; gap: 10px; }
    .modal-footer {
      padding: 14px 24px; border-top: 1px solid var(--line);
      display: flex; justify-content: flex-end; flex-wrap: wrap;
      gap: 10px; align-items: center;
    }
    .modal-footer form {
      display: flex;
      flex: 1 1 140px;
      min-width: 0;
      max-width: 100%;
      justify-content: flex-end;
    }
    .modal-footer form .btn-primary { width: 100%; justify-content: center; }

    .mc-row {
      background: var(--bg); border: 1px solid var(--line);
      border-radius: 14px; padding: 14px 16px;
    }
    .mc-field { font-family: 'DM Mono', monospace; font-size: 0.65rem; font-weight: 700; color: var(--maroon); letter-spacing: .08em; text-transform: uppercase; margin-bottom: 7px; }
    .mc-old   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; color: #b91c1c; overflow-wrap: anywhere; }
    .mc-new   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; color: #065f46; margin-top: 4px; overflow-wrap: anywhere; }

    /* ══════════ TOAST ══════════ */
    .toast {
      position: fixed; top: 20px; right: 20px; z-index: 9999;
      min-width: min(300px, calc(100vw - 2rem));
      max-width: calc(100vw - 2rem);
      padding: 13px 18px; border-radius: 14px;
      box-sizing: border-box;
      font-weight: 700; font-size: 0.82rem;
      box-shadow: 0 10px 40px rgba(15,23,42,.18);
      animation: slideIn .3s ease-out; font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .toast.success { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: #2a1a0b; border-left: 4px solid var(--maroon); }
    .toast.error   { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border-left: 4px solid #b91c1c; }
    .toast.hiding  { animation: slideOut .3s ease-out forwards; }
    @keyframes slideIn  { from { transform:translateX(400px); opacity:0; } to { transform:none; opacity:1; } }
    @keyframes slideOut { from { transform:none; opacity:1; } to { transform:translateX(400px); opacity:0; } }

    /* ══════════ ANIMATIONS ══════════ */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
    .fade-up { opacity:0; animation: fadeUp .4s forwards; }
    .fade-up-1 { animation-delay: .04s; }
    .fade-up-2 { animation-delay: .10s; }
    .fade-up-3 { animation-delay: .16s; }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 999px; }

    /* ══════════ RESPONSIVE ══════════ */
    @media (max-width: 960px) {
      .main-grid { grid-template-columns: minmax(0, 1fr); }
      .filter-panel { position: static; }
      .hero-fields { grid-template-columns: repeat(2, minmax(0, 1fr)); }
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
      .topbar {
        min-height: 60px;
        padding: 10px 12px;
        gap: 10px;
      }
      .topbar-left {
        gap: 10px;
        flex-wrap: nowrap;
      }
      .topbar-right {
        width: 100%;
        justify-content: flex-start;
        gap: 8px;
      }
      .topbar .page-sub { display: none; }
      .record-id-pill {
        margin-left: auto;
        max-width: 140px;
      }
      .btn-gold, .btn-edit, .btn-archive {
        padding: 8px 10px;
        border-radius: 12px;
        font-size: 0;
        min-width: 38px;
        min-height: 38px;
        gap: 0;
      }
      .btn-gold svg, .btn-edit svg, .btn-archive svg {
        width: 14px;
        height: 14px;
      }

      /* ── Compact hero card on mobile — matches guestrecorddetail ── */
      .hero-card {
        margin: 10px 12px 0;
        border-radius: 20px;
        padding: 14px;
        box-shadow: 0 10px 30px rgba(165,44,48,.24);
      }
      .hero-card::before {
        top: -50px; right: -50px;
        width: 200px; height: 200px;
      }
      .hero-card::after {
        bottom: -60px;
        width: 240px; height: 240px;
      }
      .hero-top { gap: 10px 14px; margin-bottom: 14px; }
      .hero-title {
        font-size: clamp(1rem, 3.2vw + 0.45rem, 1.2rem);
      }
      .hero-badges {
        gap: 6px;
        margin-bottom: 14px;
      }
      .hero-badge {
        padding: 4px 8px;
        font-size: 0.62rem;
      }
      .hero-fields {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        border-radius: 12px;
      }
      .hero-field {
        padding: 11px 12px;
      }
      .hf-label {
        font-size: 0.5rem;
        letter-spacing: .13em;
      }
      .hf-value {
        font-size: 0.78rem;
      }
      .hero-owner-row { margin-top: 8px; padding: 10px 14px; }
    }
    @media (max-width: 640px) {
      .hero-fields { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .hero-field.span2 { grid-column: span 2; }
      .timeline-body { max-height: none; }
      .tl-time { white-space: normal; overflow-wrap: anywhere; }
      .btn-gold-label { display: none; }
      .record-id-pill { max-width: 100%; }

      /* Further tighten hero on small phones */
      .hero-card {
        margin: 8px 12px 0;
        border-radius: 16px;
        padding: 14px;
      }
      .topbar {
        padding: 10px 10px 8px;
      }
      .topbar-left {
        flex-wrap: wrap;
      }
      .record-id-pill {
        margin-left: 0;
      }
    }
    @media (max-width: 480px) {
      .hero-fields .hf-value {
        white-space: normal;
        overflow: visible;
        text-overflow: unset;
        word-break: break-word;
      }
      .hero-fields {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
      .hero-field.span2 {
        grid-column: span 2;
      }
      .hero-card {
        margin: 8px 12px 0;
      }
      .modal-footer { flex-direction: column; align-items: stretch; }
      .modal-footer form { flex: 0 0 auto; width: 100%; justify-content: stretch; }
      .modal-footer .btn-outline,
      .modal-footer .btn-primary { width: 100%; justify-content: center; }
      .page-footer-kttm { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

@php
  $user     = $user     ?? (object)['name' => 'KTTM User', 'role' => 'Staff'];
  $record   = $record   ?? null;

  $urlDashboard = url('/home');
  $urlRecords   = url('/records');
  $urlInsights  = url('/insights');
  $urlNew       = url('/ipassets/create');
  $urlLogout    = url('/logout');

  $recordId = $recordId
      ?? request()->route('recordId')
      ?? request()->route('record_id')
      ?? request()->route('id')
      ?? request()->query('record_id');

  $initials = collect(explode(' ', $user->name))
      ->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');

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

  // Status badge class helper
  $statusClass = match(strtolower($record['status'] ?? '')) {
    'registered'      => 'hb-status',
    'under review'    => 'hb-status st-attention',
    'filed'           => 'hb-status st-attention',
    'needs attention' => 'hb-status st-attention',
    default           => 'hb-status st-default',
  };
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
    <a href="{{ $urlRecords }}" class="nav-item active">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <span class="nav-tooltip">Records</span>
    </a>
    <a href="{{ $urlInsights }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
      </svg>
      <span class="nav-tooltip">Insights</span>
    </a>
    
    <a href="{{ url('/calendar') }}" class="nav-item">
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
    <button type="button" id="logoutBtn" class="nav-item" style="background:none;border:none;cursor:pointer;">
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
      <button type="button" class="hamburger-btn" id="hamburgerBtn" aria-label="Open navigation menu" aria-expanded="false">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <line x1="3" y1="6"  x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
      <a href="{{ $urlRecords }}" class="back-btn" title="Back to Records">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      </a>
      <div class="topbar-titles">
        <div class="page-title">Record Detail</div>
        <div class="page-sub">Full profile &amp; audit trail</div>
      </div>
      @if($recordId)
        <div class="record-id-pill" title="{{ $recordId }}">
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="15" x2="13" y2="15"/></svg>
          {{ $recordId }}
        </div>
      @endif
    </div>
    <div class="topbar-right">
      <button type="button" id="refreshBtn" class="btn-gold" title="Refresh timeline">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>
        <span class="btn-gold-label">Refresh</span>
      </button>
      <a href="{{ $urlRecords }}?edit={{ urlencode($record['title'] ?? $recordId ?? '') }}" class="btn-edit" title="Edit this record">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit
      </a>
      <button type="button" class="btn-archive" title="Archive this record (coming soon)" disabled>
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
        Archive
      </button>
      
    </div>
  </header>

  {{-- HERO RECORD CARD --}}
  <div class="hero-card fade-up fade-up-1">
    <div class="hero-inner">
      <div class="hero-top">
        <div>
          <div class="hero-eyebrow">IP Record · {{ $recordId ?? 'Unknown' }}</div>
          <div class="hero-title" id="heroTitle">
            @if($record && !empty($record['title']))
              {{ $record['title'] }}
            @else
              <span class="hero-title-placeholder">Loading title…</span>
            @endif
          </div>
          <div class="hero-badges">
            @if($record && !empty($record['type']))
              <span class="hero-badge hb-type">
                <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                {{ $record['type'] }}
              </span>
            @endif
            @if($record && !empty($record['campus']))
              <span class="hero-badge hb-campus">
                <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                {{ $record['campus'] }}
              </span>
            @endif
            @if($record && !empty($record['status']))
              <span class="hero-badge {{ $statusClass }}">
                <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/></svg>
                {{ $record['status'] }}
              </span>
            @endif
          </div>
        </div>
      </div>

      {{-- Row 1: Reg. Number | Campus | College | Program --}}
      <div class="hero-fields">
      <div class="hero-field">
        <div class="hf-label">Reg. Number</div>
        <div class="hf-value @if(empty($record['registration_number'])) empty @endif" id="snapIpophl">
          {{ $record['registration_number'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Campus</div>
        <div class="hf-value @if(empty($record['campus'])) empty @endif" id="snapCampus">
          {{ $record['campus'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">College</div>
        <div class="hf-value @if(empty($record['college'])) empty @endif" id="snapCollege">
          {{ $record['college'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Program</div>
        <div class="hf-value @if(empty($record['program'])) empty @endif" id="snapProgram">
          {{ $record['program'] ?? '—' }}
        </div>
      </div>

      {{-- Row 2: Date Registered | Date Created | Next Due | Validity --}}
      <div class="hero-field">
        <div class="hf-label">Date Registered</div>
        <div class="hf-value @if(empty($record['registered'])) empty @endif" id="snapRegistered">
          @if(!empty($record['registered']))
            {{ \Carbon\Carbon::parse($record['registered'])->format('M d, Y') }}
          @else —
          @endif
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Date Created</div>
        <div class="hf-value @if(empty($record['date_creation'])) empty @endif" id="snapDateCreated">
          @if(!empty($record['date_creation']))
            {{ \Carbon\Carbon::parse($record['date_creation'])->format('M d, Y') }}
          @else —
          @endif
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Next Due</div>
        <div class="hf-value" id="snapDue">
          @php
            $dueDate = '—';
            if(!empty($record['registered']) && !empty($record['type'])) {
              $d = \Carbon\Carbon::parse($record['registered']);
              $dueDate = match(strtolower(trim($record['type']))) {
                'patent'           => $d->copy()->addYears(20)->format('M d, Y'),
                'copyright'        => $d->copy()->addYears(70)->format('M d, Y'),
                'utility model'    => $d->copy()->addYears(10)->format('M d, Y'),
                'industrial design'=> $d->copy()->addYears(15)->format('M d, Y'),
                default            => '—',
              };
            }
          @endphp
          {{ $dueDate }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Validity</div>
        <div class="hf-value" id="snapValidity">
          @php
            $validity = '—';
            if(!empty($record['type'])) {
              $validity = match(strtolower(trim($record['type']))) {
                'patent'           => '20 years',
                'copyright'        => '70 years',
                'utility model'    => '10 years',
                'industrial design'=> '15 years',
                default            => '—',
              };
            }
          @endphp
          {{ $validity }}
        </div>
      </div>

      {{-- Row 3: Class of Work | GDrive (span 2) | Remarks (span 2) --}}
      <div class="hero-field">
        <div class="hf-label">Class of Work</div>
        <div class="hf-value @if(empty($record['class_of_work'])) empty @endif" id="snapClassOfWork">
          {{ $record['class_of_work'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">GDrive File</div>
        <div class="hf-value @if(empty($record['gdrive_link'])) empty @endif" id="snapGdrive">
          @if(!empty($record['gdrive_link']))
            <a href="{{ $record['gdrive_link'] }}" target="_blank" rel="noopener">
              <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              Open File ↗
            </a>
          @else
            No file attached
          @endif
        </div>
      </div>
      <div class="hero-field span2">
        <div class="hf-label">Remarks</div>
        <div class="hf-value @if(empty($record['remarks'])) empty @endif" id="snapRemarks" style="white-space:normal;font-size:.78rem;">
          {{ $record['remarks'] ?? '—' }}
        </div>
      </div>
      </div>

      {{-- Owner row --}}
      <div class="hero-owner-row">
        <div class="hf-label">Owner / Inventor(s)</div>
        <div class="hf-value @if(empty($record['owner'])) empty @endif" id="snapOwner">
          {{ !empty($record['owner']) ? $record['owner'] : 'No owner information on record' }}
        </div>
      </div>
    </div>
  </div>

  {{-- CONTENT --}}
  <div class="content">

    <div class="main-grid">

      {{-- FILTER PANEL --}}
      <aside class="filter-panel fade-up fade-up-2">
        <div class="fp-header">
          <div class="fp-title">Filter Timeline</div>
          <div class="fp-sub">Narrow by action or keyword</div>
        </div>
        <div class="fp-body">
          <div>
            <label class="field-label">Search</label>
            <input id="searchInput" class="field-input" type="search" placeholder="field, value, actor…">
          </div>
          <div>
            <label class="field-label">Action Type</label>
            <select id="actionFilter" class="field-select">
              <option value="">All actions</option>
              <option value="created">Created</option>
              <option value="modified">Modified</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div>
            <label class="field-label">Sort Order</label>
            <select id="sortFilter" class="field-select">
              <option value="desc">Newest first</option>
              <option value="asc">Oldest first</option>
            </select>
          </div>
          <div class="fp-btn-row">
            <button type="button" id="applyBtn" class="fp-apply">Apply</button>
            <button type="button" id="resetBtn" class="fp-reset">Reset</button>
          </div>
          <div class="fp-count" id="countHint">—</div>
        </div>
      </aside>

      {{-- TIMELINE --}}
      <div class="timeline-card fade-up fade-up-3">
        <div class="tc-header">
          <div>
            <div class="tc-title">Change Timeline</div>
            <div class="tc-sub">Click any event to view full field diff</div>
          </div>
          <div class="tc-badge"># {{ $recordId ?? '—' }}</div>
        </div>
        <div class="timeline-body">
          <div class="tl-list" id="timeline">
            <div class="tl-empty">
              <div class="tl-empty-icon">⏳</div>
              Loading changes…
            </div>
          </div>
        </div>
      </div>

    </div>

    <footer class="page-footer-kttm" style="margin-top:20px;padding:14px 0;border-top:1px solid var(--line);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px 16px;">
      <div style="font-size:.72rem;color:var(--muted);">© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div style="font-family:'DM Mono',monospace;font-size:.65rem;color:#94a3b8;">Record Detail · Audit Trail</div>
    </footer>

  </div>
</div>

{{-- ══ LOGOUT MODAL ══ --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal-box" style="max-width:380px;">
    <div class="modal-header">
      <div>
        <div class="modal-eyebrow">Session</div>
        <div class="modal-title">Sign out of KTTM</div>
      </div>
      <button type="button" class="modal-close" data-close-logout>✕</button>
    </div>
    <div class="modal-body">
      <p style="font-family:'Plus Jakarta Sans',sans-serif;font-size:.82rem;color:var(--muted);line-height:1.65;">This will end your current session and return you to the public portal.</p>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn-outline" data-close-logout>Cancel</button>
      <form id="logoutForm" action="{{ $urlLogout }}" method="POST">
        @csrf
        <button type="submit" class="btn-primary">Sign Out</button>
      </form>
    </div>
  </div>
</div>

{{-- ══ EVENT DETAIL MODAL ══ --}}
<div class="modal-overlay" id="eventModal">
  <div class="modal-box" style="max-width:560px;">
    <div class="modal-header">
      <div>
        <div class="modal-eyebrow">Field Diff</div>
        <div class="modal-title" id="eventTitle">Event Details</div>
        <div style="font-size:.72rem;color:var(--deep-muted);margin-top:3px;" id="eventMeta">—</div>
      </div>
      <button type="button" class="modal-close" data-close-event>✕</button>
    </div>
    <div class="modal-body" id="eventChanges"><!-- filled by JS --></div>
    <div class="modal-footer">
      <button type="button" class="btn-primary" data-close-event>Close</button>
    </div>
  </div>
</div>

<script>
(function(){
  const RECORD_ID = @json((string) $recordId);

  // ── Toast ──
  function showToast(msg, type='success', dur=4000){
    const t = document.createElement('div');
    t.className = `toast ${type}`; t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => { t.classList.add('hiding'); setTimeout(() => t.remove(), 300); }, dur);
  }

  // ── Modals + scroll lock (works with mobile sidebar) ──
  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const anyModal = ['logoutModal', 'eventModal'].some(
      mid => document.getElementById(mid)?.classList.contains('open')
    );
    document.body.style.overflow = (sidebarOpen || anyModal) ? 'hidden' : '';
  }

  function openModal(id) {
    const m = document.getElementById(id);
    if (m) { m.classList.add('open'); syncBodyScrollLock(); }
  }
  function closeModal(id) {
    const m = document.getElementById(id);
    if (m) { m.classList.remove('open'); syncBodyScrollLock(); }
  }

  document.getElementById('logoutBtn')?.addEventListener('click', () => openModal('logoutModal'));
  document.querySelectorAll('[data-close-logout]').forEach(b => b.addEventListener('click', () => closeModal('logoutModal')));
  document.getElementById('logoutModal')?.addEventListener('click', e => { if(e.target===e.currentTarget) closeModal('logoutModal'); });
  document.querySelectorAll('[data-close-event]').forEach(b => b.addEventListener('click', () => closeModal('eventModal')));
  document.getElementById('eventModal')?.addEventListener('click', e => { if(e.target===e.currentTarget) closeModal('eventModal'); });
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (document.getElementById('logoutModal')?.classList.contains('open')) closeModal('logoutModal');
    else if (document.getElementById('eventModal')?.classList.contains('open')) closeModal('eventModal');
  });

  // ── Helpers ──
  function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;'); }

  function timeAgo(date){
    const s = Math.floor((new Date() - date) / 1000);
    if(s < 60)  return 'just now';
    const m = Math.floor(s/60);   if(m < 60)  return `${m}m ago`;
    const h = Math.floor(m/60);   if(h < 24)  return `${h}h ago`;
    const d = Math.floor(h/24);   if(d < 7)   return `${d}d ago`;
    return date.toLocaleDateString();
  }

  function getBadge(action){
    const a = String(action||'').toLowerCase();
    if(a==='created')             return { cls:'badge-created',  dot:'dot-created',  icon:'✚', label:'Created'  };
    if(a==='modified'||a==='updated') return { cls:'badge-modified', dot:'dot-modified', icon:'✎', label:'Modified' };
    if(a==='archived')            return { cls:'badge-archived', dot:'dot-archived', icon:'⦸', label:'Archived' };
    return { cls:'badge-default', dot:'dot-default', icon:'•', label:action||'Event' };
  }

  function normalizeChanges(changes){
    if(!changes) return [];
    if(typeof changes==='string'){ try{ changes=JSON.parse(changes); }catch(e){ return []; } }
    if(typeof changes!=='object') return [];
    return Object.entries(changes).map(([field, v]) => ({
      field,
      old: (v && typeof v==='object') ? v.old : undefined,
      new: (v && typeof v==='object') ? v.new : undefined,
    }));
  }

  function matchesSearch(evt, needle){
    if(!needle) return true;
    const n = needle.toLowerCase();
    if(`${evt.action||''} ${evt.actor||''} ${evt.note||''}`.toLowerCase().includes(n)) return true;
    for(const c of normalizeChanges(evt.changes)){
      if(`${c.field} ${c.old??''} ${c.new??''}`.toLowerCase().includes(n)) return true;
    }
    return false;
  }

  // ── State ──
  let allEvents = [];

  // ── Render timeline ──
  function renderTimeline(events){
    const tl   = document.getElementById('timeline');
    const hint = document.getElementById('countHint');
    if(!tl) return;

    if(!events || !events.length){
      tl.innerHTML = `<div class="tl-empty"><div class="tl-empty-icon">📭</div>No matching events found.</div>`;
      if(hint) hint.textContent = '0 event(s).';
      return;
    }
    if(hint) hint.textContent = `${events.length} event(s) shown.`;

    tl.innerHTML = events.map((evt, idx) => {
      const ts     = new Date(evt.timestamp || evt.created_at);
      const ago    = isNaN(ts.getTime()) ? '—' : timeAgo(ts);
      const pretty = isNaN(ts.getTime()) ? 'Unknown time' : ts.toLocaleString();
      const badge  = getBadge(evt.action);
      const changes = normalizeChanges(evt.changes);

      const changesHtml = changes.length
        ? changes.map(c => {
            const ov = (c.old===null||c.old===undefined||c.old==='') ? '(empty)' : String(c.old);
            const nv = (c.new===null||c.new===undefined||c.new==='') ? '(empty)' : String(c.new);
            return `<div class="chg-row">
              <div class="chg-field">${esc(c.field)}</div>
              <div class="chg-old">Before: ${esc(ov)}</div>
              <div class="chg-new">After: ${esc(nv)}</div>
            </div>`;
          }).join('')
        : '<div class="chg-none">No field changes recorded for this event.</div>';

      return `<div class="tl-item" data-idx="${idx}">
        <div class="tl-dot ${badge.dot}"></div>
        <div class="tl-card">
          <div class="tl-top">
            <div class="tl-left">
              <div class="tl-badge-row">
                <span class="action-badge ${badge.cls}">${badge.icon} ${esc(badge.label)}</span>
                <span class="tl-time">${esc(ago)} · ${esc(pretty)}</span>
              </div>
              <div class="tl-summary">${esc(evt.summary || evt.note || 'Change event')}</div>
              ${evt.actor ? `<div class="tl-actor">By <strong>${esc(evt.actor)}</strong></div>` : ''}
            </div>
            <div class="tl-arrow">Details →</div>
          </div>
          <div class="tl-changes">${changesHtml}</div>
        </div>
      </div>`;
    }).join('');

    tl.querySelectorAll('.tl-item').forEach(el => {
      el.addEventListener('click', () => {
        const i = Number(el.dataset.idx);
        if(!isNaN(i) && events[i]) openEvent(events[i]);
      });
    });
  }

  function openEvent(evt){
    const ts     = new Date(evt.timestamp || evt.created_at);
    const pretty = isNaN(ts.getTime()) ? 'Unknown time' : ts.toLocaleString();
    const badge  = getBadge(evt.action);

    const titleEl   = document.getElementById('eventTitle');
    const metaEl    = document.getElementById('eventMeta');
    const changesEl = document.getElementById('eventChanges');

    if(metaEl)   metaEl.textContent = `Record ${RECORD_ID} · ${pretty}${evt.actor ? ' · '+evt.actor : ''}`;
    if(titleEl)  titleEl.innerHTML  = `<span style="display:inline-flex;align-items:center;gap:8px;">
      <span class="action-badge ${badge.cls}">${badge.icon} ${esc(badge.label)}</span>
      <span style="color:#fff;">${esc(evt.summary || evt.note || 'Event details')}</span>
    </span>`;

    const changes = normalizeChanges(evt.changes);
    if(changesEl){
      if(!changes.length){
        changesEl.innerHTML = '<div class="mc-row" style="color:var(--muted);font-size:.82rem;">No change details available for this event.</div>';
      } else {
        changesEl.innerHTML = changes.map(c => {
          const ov = (c.old===null||c.old===undefined||c.old==='') ? '(empty)' : String(c.old);
          const nv = (c.new===null||c.new===undefined||c.new==='') ? '(empty)' : String(c.new);
          return `<div class="mc-row">
            <div class="mc-field">${esc(c.field)}</div>
            <div class="mc-old"><strong>Before:</strong> ${esc(ov)}</div>
            <div class="mc-new"><strong>After:</strong> ${esc(nv)}</div>
          </div>`;
        }).join('');
      }
    }
    openModal('eventModal');
  }

  // ── Filters ──
  function applyFilters(){
    const needle = (document.getElementById('searchInput')?.value  || '').trim();
    const action = (document.getElementById('actionFilter')?.value || '').trim().toLowerCase();
    const sort   = (document.getElementById('sortFilter')?.value   || 'desc').trim();

    let filtered = allEvents.slice();
    if(action) filtered = filtered.filter(e => {
      const a = String(e.action||'').toLowerCase();
      return a===action || (action==='modified' && a==='updated');
    });
    if(needle) filtered = filtered.filter(e => matchesSearch(e, needle));
    filtered.sort((a,b) => {
      const da = new Date(a.timestamp||a.created_at||0).getTime();
      const db = new Date(b.timestamp||b.created_at||0).getTime();
      return sort==='asc' ? da-db : db-da;
    });
    renderTimeline(filtered);
  }

  // ── Update hero snapshot from API data ──
  function updateSnapshot(record){
    if(!record) return;
    const set = (id, val) => { const el=document.getElementById(id); if(el) el.textContent = val||'—'; };
    const fmtDate = raw => {
      if(!raw) return '—';
      try { return new Date(raw).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}); }
      catch(e){ return raw; }
    };

    set('heroTitle',       record.title);
    set('snapOwner',       record.owner);
    set('snapIpophl',      record.registration_number);
    set('snapCampus',      record.campus);
    set('snapCollege',     record.college);
    set('snapProgram',     record.program);
    set('snapClassOfWork', record.class_of_work);
    set('snapRemarks',     record.remarks);
    set('snapRegistered',  fmtDate(record.registered));
    set('snapDateCreated', fmtDate(record.date_creation));

    // Computed due date + validity
    if(record.registered){
      try {
        const d = new Date(record.registered);
        const typeLow = (record.type||'').toLowerCase().trim();
        const yearsMap = {'patent':20,'copyright':70,'utility model':10,'industrial design':15};
        const yrs = yearsMap[typeLow];
        if(yrs){
          const due = new Date(d); due.setFullYear(due.getFullYear() + yrs);
          set('snapDue',      fmtDate(due));
          set('snapValidity', yrs + ' years');
        }
      } catch(e){}
    }

    // GDrive link (innerHTML needed for anchor)
    const gd = document.getElementById('snapGdrive');
    if(gd){
      if(record.gdrive_link){
        gd.innerHTML = `<a href="${esc(record.gdrive_link)}" target="_blank" rel="noopener">
          <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Open File ↗
        </a>`;
      } else {
        gd.textContent = 'No file attached';
      }
    }
  }

  // ── API load ──
  async function loadChanges(){
    const tl = document.getElementById('timeline');
    if(!RECORD_ID){
      if(tl) tl.innerHTML = '<div class="tl-empty"><div class="tl-empty-icon">⚠️</div>Missing record ID.</div>';
      return;
    }
    try {
      const res = await fetch(`/api/records/${encodeURIComponent(String(RECORD_ID))}/changes`, {
        headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content||'' },
        credentials: 'same-origin'
      });
      if(!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
      allEvents = Array.isArray(data.events) ? data.events : [];
      applyFilters();
      if(data.record) updateSnapshot(data.record);
    } catch(err){
      console.error(err);
      if(tl) tl.innerHTML = `<div class="tl-empty"><div class="tl-empty-icon">❌</div>Failed to load. <button id="retryBtn" style="text-decoration:underline;background:none;border:none;cursor:pointer;font-weight:700;color:var(--maroon);">Retry</button></div>`;
      document.getElementById('retryBtn')?.addEventListener('click', loadChanges);
      showToast('Failed to load record changes.','error');
    }
  }

  // ── Button wiring ──
  document.getElementById('refreshBtn')?.addEventListener('click', async () => {
    const btn = document.getElementById('refreshBtn');
    if(btn){ btn.style.opacity='.5'; btn.style.pointerEvents='none'; }
    showToast('Refreshing…','success',1800);
    await loadChanges();
    if(btn){ btn.style.opacity='1'; btn.style.pointerEvents='auto'; }
  });

  document.getElementById('applyBtn')?.addEventListener('click', applyFilters);
  document.getElementById('resetBtn')?.addEventListener('click', () => {
    const si = document.getElementById('searchInput');      if(si) si.value='';
    const af = document.getElementById('actionFilter');     if(af) af.value='';
    const sf = document.getElementById('sortFilter');       if(sf) sf.value='desc';
    applyFilters();
  });
  document.getElementById('searchInput')?.addEventListener('keypress', e => { if(e.key==='Enter') applyFilters(); });
  document.getElementById('actionFilter')?.addEventListener('change', applyFilters);
  document.getElementById('sortFilter')?.addEventListener('change', applyFilters);

  // ── Mobile sidebar (same pattern as home / records) ──
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

  // ── Boot ──
  loadChanges();
  setInterval(loadChanges, 30000);
})();
</script>

</body>
</html>