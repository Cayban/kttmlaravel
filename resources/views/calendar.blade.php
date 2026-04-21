{{-- resources/views/calendar.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Calendar</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    /* ════════════════════════════════════════
       TOKENS
    ════════════════════════════════════════ */
    :root {
      --maroon:        #A52C30;
      --maroon2:       #7E1F23;
      --maroon3:       #C1363A;
      --maroon-light:  rgba(165,44,48,.10);
      --gold:          #F0C860;
      --gold2:         #E8B857;
      --ink:           #0F172A;
      --muted:         #64748B;
      --line:          rgba(15,23,42,.08);
      --card:          #FFFFFF;
      --sidebar-w:     72px;
      --bg:            #F1F4F9;
      --pad-x:         clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max:     1440px;
      --topbar-h:      72px;

      --c-deadline:    #EF4444;   --c-deadline-bg:    #FEF2F2;
      --c-reg:         #7E1F23;   --c-reg-bg:         #FFF0F0;
      --c-review:      #D97706;   --c-review-bg:      #FFFBEB;
      --c-submission:  #2563EB;   --c-submission-bg:  #EFF6FF;
      --c-pending:     #7C3AED;   --c-pending-bg:     #F5F3FF;
      --c-done:        #059669;   --c-done-bg:        #ECFDF5;
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

    /* ════ SIDEBAR ════ */
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0; width: var(--sidebar-w);
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
      color: #fff; font-size: .7rem; font-weight: 600;
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

    /* ════ LAYOUT ════ */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ════ TOPBAR ════ */
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
    .topbar-right {
      display: flex; align-items: center; justify-content: flex-end;
      flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
    }
    .page-title {
      font-size: clamp(0.95rem, 0.4vw + 0.85rem, 1.15rem);
      font-weight: 800; letter-spacing: -.3px;
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-sub {
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted); font-weight: 500;
      overflow-wrap: anywhere;
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
    .btn-primary:disabled { opacity: .6; cursor: not-allowed; transform: none; }
    .btn-primary.btn-block {
      width: 100%;
      max-width: 100%;
      justify-content: center;
      padding: 11px 16px;
      white-space: normal;
      text-align: center;
    }
    .btn-gold {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 7px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 18px);
      border-radius: 11px;
      box-shadow: 0 4px 14px rgba(240,200,96,.30);
      transition: transform .18s, opacity .18s;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-gold:hover { transform: translateY(-1px); opacity: .9; }
    .btn-outline {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 7px;
      background: var(--card); color: var(--muted);
      border: 1.5px solid var(--line); cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 600;
      padding: 9px clamp(10px, 2vw, 18px);
      border-radius: 11px;
      transition: all .18s;
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
      font-weight: 800; font-size: .85rem; color: #2a1a0b; cursor: pointer;
    }
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
    .howto-overlay {
      position: fixed; inset: 0; z-index: 200;
      background: rgba(15,23,42,.6); backdrop-filter: blur(8px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .howto-overlay.open { display: flex; }
    .howto-box {
      background: var(--card); border-radius: 24px;
      width: min(680px,96vw); max-height: 88vh;
      display: flex; flex-direction: column;
      box-shadow: 0 32px 80px rgba(15,23,42,.22);
      animation: howtoIn .3s cubic-bezier(.17,.67,.35,1.05) forwards; overflow: hidden;
    }
    @keyframes howtoIn { from{opacity:0;transform:translateY(20px) scale(.97);}to{opacity:1;transform:none;} }
    .howto-head {
      background: linear-gradient(135deg, var(--maroon2) 0%, #A52C30 55%, var(--maroon3) 100%);
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
      font-family: 'DM Mono', monospace; font-size: .6rem; font-weight: 700;
      letter-spacing: .18em; text-transform: uppercase; color: var(--gold); opacity: .85; margin-bottom: 5px;
    }
    .howto-title { font-size: 1.2rem; font-weight: 800; color: #fff; letter-spacing: -.3px; }
    .howto-sub   { font-size: .75rem; color: rgba(255,255,255,.6); margin-top: 4px; font-weight: 500; }
    .howto-close {
      width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.75); transition: background .15s; position: relative; z-index: 1;
    }
    .howto-close:hover { background: rgba(255,255,255,.22); color: #fff; }
    .howto-body { flex: 1; overflow-y: auto; padding: 22px 28px; display: flex; flex-direction: column; gap: 12px; }
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
      font-family: 'DM Mono', monospace; font-size: .72rem; font-weight: 800; color: var(--gold);
      box-shadow: 0 3px 10px rgba(165,44,48,.22);
    }
    .howto-step-body { flex: 1; min-width: 0; }
    .howto-step-title { font-size: .88rem; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
    .howto-step-desc  { font-size: .76rem; color: var(--muted); line-height: 1.6; font-weight: 500; overflow-wrap: anywhere; }
    .howto-step-tag {
      display: inline-flex; align-items: center; gap: 4px; margin-top: 7px;
      font-family: 'DM Mono', monospace; font-size: .6rem; font-weight: 700;
      color: var(--maroon); background: var(--maroon-light);
      border: 1px solid rgba(165,44,48,.18); padding: 2px 9px; border-radius: 20px;
      letter-spacing: .04em; text-transform: uppercase;
    }
    .howto-footer {
      padding: 16px 28px; border-top: 1px solid var(--line); flex-shrink: 0;
      display: flex; flex-wrap: wrap;
      align-items: center; justify-content: space-between; gap: 12px;
      background: linear-gradient(90deg,rgba(165,44,48,.03),rgba(240,200,96,.03));
    }
    .howto-footer-note {
      flex: 1 1 12rem; min-width: 0;
      font-size: .72rem; color: var(--muted); font-weight: 500;
      overflow-wrap: anywhere;
    }
    .howto-footer-note strong { color: var(--maroon); }
    .howto-footer .howto-got-it { flex: 0 1 auto; margin-left: auto; }
    .howto-got-it {
      display: inline-flex; align-items: center; justify-content: center;
      padding: 9px 18px; border-radius: 10px;
      background: linear-gradient(135deg,var(--maroon),var(--maroon2));
      color: #fff; border: none; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.12vw + 0.68rem, 0.76rem);
      font-weight: 700;
      box-shadow: 0 4px 14px rgba(165,44,48,.28); transition: transform .18s, box-shadow .18s;
      max-width: 100%;
      white-space: nowrap;
    }
    .howto-got-it:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(165,44,48,.35); }

    /* ════ CONTENT ════ */
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

    /* ════ SUMMARY STRIP ════ */
    .summary-strip {
      display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px; margin-bottom: 22px;
    }
    .sum-card {
      background: var(--card); border-radius: 18px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      padding: 18px 20px;
      display: flex; align-items: center; gap: 16px;
      transition: transform .2s, box-shadow .2s;
      position: relative; overflow: hidden;
    }
    .sum-card::after {
      content: ''; position: absolute;
      top: 0; left: 0; right: 0; height: 3px; border-radius: 18px 18px 0 0;
    }
    .sum-card:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(15,23,42,.09); }
    .sum-card.sc-deadline::after  { background: var(--c-deadline); }
    .sum-card.sc-unreg::after     { background: var(--maroon); }
    .sum-card.sc-pending::after   { background: var(--c-pending); }
    .sum-card.sc-done::after      { background: var(--c-done); }

    .sum-icon {
      width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
    }
    .sc-deadline .sum-icon { background: var(--c-deadline-bg); color: var(--c-deadline); }
    .sc-unreg    .sum-icon { background: var(--maroon-light);  color: var(--maroon); }
    .sc-pending  .sum-icon { background: var(--c-pending-bg);  color: var(--c-pending); }
    .sc-done     .sum-icon { background: var(--c-done-bg);     color: var(--c-done); }

    .sum-num   {
      font-size: clamp(1.2rem, 2.5vw, 1.85rem);
      font-weight: 800; letter-spacing: -1.5px; line-height: 1;
    }
    .sum-label {
      font-size: clamp(0.65rem, 0.12vw + 0.62rem, 0.72rem);
      color: var(--muted); font-weight: 600; margin-top: 3px;
      overflow-wrap: anywhere;
    }

    /* ════ PAGE BODY GRID ════ */
    .page-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 360px);
      gap: 18px; align-items: start;
    }

    /* ════ CALENDAR CARD ════ */
    .cal-card {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 16px rgba(15,23,42,.06);
      overflow: hidden;
    }
    .cal-head {
      background: linear-gradient(135deg, var(--maroon2) 0%, var(--maroon3) 100%);
      padding: clamp(12px, 2vw, 18px) clamp(14px, 2.5vw, 24px);
      display: flex; flex-wrap: wrap;
      align-items: center; justify-content: space-between;
      gap: 10px 12px;
    }
    .cal-head-right {
      display: flex; align-items: center; gap: 8px;
      flex-wrap: wrap;
      flex: 0 1 auto;
      justify-content: flex-end;
    }
    .cal-nav {
      width: 36px; height: 36px; border-radius: 10px;
      flex: 0 0 auto;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
      color: #fff; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.1rem; font-weight: 700;
      transition: background .18s;
    }
    .cal-nav:hover { background: rgba(255,255,255,.24); }
    .cal-month-wrap { text-align: center; flex: 1 1 8rem; min-width: 0; }
    .cal-month-name {
      font-size: clamp(0.88rem, 0.35vw + 0.8rem, 1.05rem);
      font-weight: 800; color: #fff; letter-spacing: -.2px;
      overflow-wrap: anywhere;
    }
    .cal-year       { font-family: 'DM Mono', monospace; font-size: .7rem; color: rgba(255,255,255,.55); margin-top: 2px; }
    .cal-today-btn {
      font-family: 'DM Mono', monospace; font-size: .65rem; font-weight: 700;
      letter-spacing: .08em; text-transform: uppercase;
      background: rgba(240,200,96,.2); border: 1px solid rgba(240,200,96,.4);
      color: var(--gold); padding: 5px 12px; border-radius: 20px; cursor: pointer;
      transition: background .18s;
    }
    .cal-today-btn:hover { background: rgba(240,200,96,.32); }

    .dow-row {
      display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));
      padding: 12px 16px 8px;
      background: rgba(126,31,35,.04);
      border-bottom: 1px solid var(--line);
    }
    .dow-cell {
      text-align: center;
      font-family: 'DM Mono', monospace;
      font-size: .58rem; font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; color: var(--muted);
    }
    .dow-cell.wknd { color: var(--maroon); }

    .days-grid {
      display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));
      border-right: 1px solid var(--line);
    }
    .day-cell {
      min-height: 90px; padding: 8px 9px;
      border-left: 1px solid var(--line);
      border-bottom: 1px solid var(--line);
      cursor: pointer; position: relative;
      transition: background .15s;
      display: flex; flex-direction: column;
    }
    .day-cell:hover { background: rgba(165,44,48,.04); }
    .day-cell.other-month { background: rgba(241,244,249,.6); }
    .day-cell.other-month .day-num { color: #c8d2dd; }
    .day-cell.is-today { background: rgba(165,44,48,.05); }
    .day-cell.is-today .day-num-inner {
      background: linear-gradient(135deg, var(--maroon), var(--maroon3));
      color: #fff; width: 26px; height: 26px; border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
    }
    .day-num {
      font-size: .78rem; font-weight: 700; color: var(--ink);
      margin-bottom: 5px; line-height: 1;
    }
    .day-cell.wknd-col .day-num { color: var(--maroon); }

    .day-chips { display: flex; flex-direction: column; gap: 2px; margin-top: 2px; }
    .day-chip {
      font-size: .56rem; font-weight: 700;
      padding: 2px 6px; border-radius: 4px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .day-chip.cat-deadline    { background: var(--c-deadline-bg);   color: var(--c-deadline); }
    .day-chip.cat-registration{ background: var(--c-reg-bg);        color: var(--c-reg); }
    .day-chip.cat-review      { background: var(--c-review-bg);     color: var(--c-review); }
    .day-chip.cat-submission  { background: var(--c-submission-bg); color: var(--c-submission); }
    .day-chip.done            { background: #eef0f3 !important; color: #a8b0bc !important; text-decoration: line-through; }

    .day-more { font-size: .55rem; color: var(--muted); font-weight: 700; margin-top: 2px; }

    .cal-legend {
      padding: 12px 20px; border-top: 1px solid var(--line);
      display: flex; gap: 14px; flex-wrap: wrap; align-items: center;
    }
    .legend-lbl {
      font-family: 'DM Mono', monospace; font-size: .58rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .1em; color: var(--muted);
    }
    .legend-item { display: flex; align-items: center; gap: 5px; font-size: .64rem; font-weight: 600; color: var(--muted); }
    .legend-pip  { width: 8px; height: 8px; border-radius: 3px; }

    /* ════ RIGHT SIDEBAR ════ */
    .right-col { display: flex; flex-direction: column; gap: 16px; }

    .panel {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden;
    }
    .panel-head {
      padding: 14px 20px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      display: flex; flex-wrap: wrap;
      align-items: center; justify-content: space-between;
      gap: 10px;
    }
    .panel-title { font-size: .9rem; font-weight: 800; color: #fff; }
    .panel-sub   { font-size: .68rem; color: rgba(255,255,255,.55); margin-top: 2px; }
    .panel-body  { padding: 16px 18px; }

    /* Add Task form */
    .form-stack { display: flex; flex-direction: column; gap: 12px; }
    .f-label {
      display: block; font-size: .62rem; font-weight: 700;
      letter-spacing: .09em; text-transform: uppercase;
      color: var(--muted); margin-bottom: 5px;
    }
    .f-input, .f-select, .f-textarea {
      width: 100%; padding: 9px 13px;
      border: 1.5px solid var(--line); background: var(--bg);
      border-radius: 11px; font-family: inherit; font-size: .8rem; color: var(--ink);
      outline: none; transition: border-color .2s, box-shadow .2s; resize: vertical;
    }
    .f-input:focus, .f-select:focus, .f-textarea:focus {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light); background: #fff;
    }
    .cat-swatches { display: flex; gap: 6px; flex-wrap: wrap; }
    .cat-swatch {
      padding: 5px 11px; border-radius: 20px;
      font-size: .63rem; font-weight: 700; cursor: pointer;
      border: 1.5px solid transparent; transition: all .15s;
      user-select: none;
    }
    .cat-swatch.deadline    { background: var(--c-deadline-bg);   color: var(--c-deadline);   border-color: rgba(239,68,68,.2); }
    .cat-swatch.registration{ background: var(--c-reg-bg);        color: var(--c-reg);        border-color: rgba(126,31,35,.2); }
    .cat-swatch.review      { background: var(--c-review-bg);     color: var(--c-review);     border-color: rgba(217,119,6,.2); }
    .cat-swatch.submission  { background: var(--c-submission-bg); color: var(--c-submission); border-color: rgba(37,99,235,.2); }
    .cat-swatch.selected    { box-shadow: 0 0 0 2.5px currentColor; transform: scale(1.07); }

    /* Recent list */
    .recent-list { display: flex; flex-direction: column; }
    .recent-item {
      display: flex; align-items: center; gap: 12px;
      padding: 11px 18px; border-bottom: 1px solid var(--line);
      transition: background .14s;
    }
    .recent-item:last-child { border-bottom: none; }
    .recent-item:hover { background: rgba(165,44,48,.03); }
    .recent-item.is-done { opacity: .55; }

    .ri-date {
      font-family: 'DM Mono', monospace;
      font-size: 1.25rem; font-weight: 800; color: var(--maroon);
      min-width: 32px; text-align: center; line-height: 1; flex-shrink: 0;
    }
    .recent-item.is-done .ri-date { color: var(--muted); }
    .ri-body  { flex: 1; min-width: 0; }
    .ri-title {
      font-size: .8rem; font-weight: 700; color: var(--ink);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .recent-item.is-done .ri-title { text-decoration: line-through; color: var(--muted); }
    .ri-meta  { font-size: .67rem; color: var(--muted); margin-top: 2px; }
    .ri-cat {
      font-size: .6rem; font-weight: 700; padding: 3px 9px;
      border-radius: 20px; flex-shrink: 0; white-space: nowrap;
    }
    .ri-cat.cat-deadline    { background: var(--c-deadline-bg);   color: var(--c-deadline); }
    .ri-cat.cat-registration{ background: var(--c-reg-bg);        color: var(--c-reg); }
    .ri-cat.cat-review      { background: var(--c-review-bg);     color: var(--c-review); }
    .ri-cat.cat-submission  { background: var(--c-submission-bg); color: var(--c-submission); }
    .ri-cat.cat-done        { background: var(--c-done-bg);       color: var(--c-done); }

    /* ════ DAY MODAL ════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.48); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: var(--card); border-radius: 22px;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      width: min(520px, calc(100vw - 2rem));
      max-width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      animation: mIn .26s cubic-bezier(.17,.67,.35,1.08);
    }
    @keyframes mIn { from { opacity:0; transform:translateY(20px) scale(.97); } to { opacity:1; transform:none; } }

    .modal-head {
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      padding: 18px 22px;
      display: flex; align-items: flex-start; justify-content: space-between;
      position: sticky; top: 0; z-index: 5;
    }
    .modal-eyebrow {
      font-family: 'DM Mono', monospace; font-size: .58rem; font-weight: 700;
      letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,.5);
      margin-bottom: 3px;
    }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: #fff; letter-spacing: -.2px; }
    .modal-count { font-size: .7rem; color: rgba(255,255,255,.55); margin-top: 3px; }
    .modal-x {
      width: 32px; height: 32px; border-radius: 9px;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: rgba(255,255,255,.7); font-size: .95rem;
      transition: background .15s; flex-shrink: 0;
    }
    .modal-x:hover { background: rgba(255,255,255,.22); }

    .modal-body   { padding: 18px 22px; display: flex; flex-direction: column; gap: 10px; }
    .modal-footer {
      padding: 14px 22px; border-top: 1px solid var(--line);
      display: flex; flex-wrap: wrap;
      justify-content: space-between; align-items: center;
      gap: 10px;
    }
    .modal-footer .btn-outline,
    .modal-footer .btn-primary {
      flex: 0 1 auto;
    }

    /* ── Logout modal (home-style) ── */
    .logout-modal-inner { padding: 28px; }
    .logout-modal-inner .modal-icon {
      width: 52px; height: 52px; border-radius: 16px;
      background: rgba(165,44,48,.1); color: var(--maroon);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 16px;
    }
    .logout-modal-inner .modal-title {
      font-size: 1.1rem; font-weight: 800;
      color: var(--ink); letter-spacing: -.2px;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .logout-modal-inner .modal-desc {
      font-size: 0.82rem; color: var(--muted);
      margin-top: 6px; line-height: 1.6;
    }
    .logout-modal-inner .modal-btns {
      display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; align-items: stretch;
    }
    .logout-modal-inner .btn-lo-cancel {
      display: inline-flex;
      align-items: center;
      flex: 1 1 120px;
      min-width: 0;
      padding: 12px; border-radius: 12px;
      border: 1.5px solid var(--line); background: none;
      font-family: inherit; font-size: 0.82rem; font-weight: 700;
      cursor: pointer; color: var(--muted); transition: background .18s;
      justify-content: center;
    }
    .logout-modal-inner .btn-lo-cancel:hover { background: var(--bg); }
    .logout-modal-inner .modal-btns form {
      flex: 1 1 140px;
      min-width: 0;
      display: flex;
    }
    .logout-modal-inner .btn-lo-confirm {
      width: 100%;
      padding: 12px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      border: none; font-family: inherit; font-size: 0.82rem;
      font-weight: 700; cursor: pointer; color: #fff;
      box-shadow: 0 6px 18px rgba(165,44,48,.25); transition: opacity .18s;
    }
    .logout-modal-inner .btn-lo-confirm:hover { opacity: .88; }

    /* Task row inside day modal */
    .mtask {
      background: var(--bg); border-radius: 14px;
      border: 1.5px solid var(--line); padding: 13px 14px;
      display: flex; gap: 12px; align-items: flex-start;
      transition: all .22s;
    }
    .mtask.is-done { filter: grayscale(1); opacity: .6; border-color: #e2e8f0; }
    .mtask-bar {
      width: 4px; border-radius: 4px; flex-shrink: 0;
      align-self: stretch; min-height: 44px;
    }
    .mtask-bar.cat-deadline    { background: var(--c-deadline); }
    .mtask-bar.cat-registration{ background: var(--c-reg); }
    .mtask-bar.cat-review      { background: var(--c-review); }
    .mtask-bar.cat-submission  { background: var(--c-submission); }
    .mtask-bar.done            { background: #bcc4ce; }

    .mtask-body { flex: 1; min-width: 0; }
    .mtask-top  { display: flex; align-items: center; gap: 7px; margin-bottom: 4px; flex-wrap: wrap; }
    .mtask-pill { font-size: .6rem; font-weight: 700; padding: 2px 9px; border-radius: 20px; }
    .mtask-pill.cat-deadline    { background: var(--c-deadline-bg);   color: var(--c-deadline); }
    .mtask-pill.cat-registration{ background: var(--c-reg-bg);        color: var(--c-reg); }
    .mtask-pill.cat-review      { background: var(--c-review-bg);     color: var(--c-review); }
    .mtask-pill.cat-submission  { background: var(--c-submission-bg); color: var(--c-submission); }
    .mtask-pill.done            { background: var(--c-done-bg);       color: var(--c-done); }

    .mtask-title { font-size: .88rem; font-weight: 700; color: var(--ink); }
    .mtask.is-done .mtask-title { text-decoration: line-through; color: var(--muted); }
    .mtask-meta { font-size: .68rem; color: var(--muted); margin-top: 3px; }

    .mark-done-btn {
      flex-shrink: 0; padding: 6px 13px; border-radius: 9px;
      border: 1.5px solid var(--line); background: var(--card);
      font-family: inherit; font-size: .68rem; font-weight: 700;
      color: var(--muted); cursor: pointer;
      display: flex; align-items: center; gap: 5px;
      transition: all .18s; white-space: nowrap;
    }
    .mark-done-btn:hover:not(:disabled) {
      border-color: var(--c-done); color: var(--c-done); background: var(--c-done-bg);
    }
    .mark-done-btn:disabled { cursor: default; }
    .mtask.is-done .mark-done-btn { border-color: #dde2e8; color: #b0b8c4; background: #f4f6f8; }

    .delete-task-btn {
      flex-shrink: 0; width: 30px; height: 30px; border-radius: 8px;
      border: 1.5px solid var(--line); background: var(--card);
      color: var(--muted); cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: all .18s;
    }
    .delete-task-btn:hover {
      border-color: #ef4444; color: #ef4444; background: #fef2f2;
    }

    .modal-empty { text-align: center; padding: 44px 20px; font-size: .8rem; color: var(--muted); }
    .modal-empty-icon { font-size: 2.2rem; margin-bottom: 10px; opacity: .3; }

    /* Spinner for loading states */
    .spinner {
      width: 16px; height: 16px; border-radius: 50%;
      border: 2px solid rgba(255,255,255,.3);
      border-top-color: #fff;
      animation: spin .6s linear infinite; flex-shrink: 0;
    }
    .spinner.dark {
      border-color: rgba(15,23,42,.15);
      border-top-color: var(--maroon);
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ════ TOAST ════ */
    .toast {
      position: fixed; top: 20px; right: 20px; z-index: 9999;
      left: auto;
      max-width: min(360px, calc(100vw - 2rem));
      min-width: 0;
      width: max-content;
      padding: 13px 18px; border-radius: 14px;
      font-weight: 700; font-size: clamp(0.75rem, 0.12vw + 0.72rem, 0.82rem);
      box-shadow: 0 10px 40px rgba(15,23,42,.15);
      animation: tin .3s ease-out; font-family: inherit;
      overflow-wrap: anywhere;
    }
    .toast.success { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: #2a1a0b; border-left: 4px solid var(--maroon); }
    .toast.error   { background: #ef4444; color: #fff; border-left: 4px solid #b91c1c; }
    .toast.hiding  { animation: tout .3s ease-out forwards; }
    @keyframes tin  { from { transform:translateX(380px); opacity:0; } to { transform:none; opacity:1; } }
    @keyframes tout { from { transform:none; opacity:1; } to { transform:translateX(380px); opacity:0; } }

    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
    .fu  { opacity:0; animation: fadeUp .38s forwards; }
    .fu1 { animation-delay:.04s; } .fu2 { animation-delay:.10s; }
    .fu3 { animation-delay:.16s; } .fu4 { animation-delay:.22s; }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 999px; }

    .cal-page-footer {
      margin-top: 20px;
      padding: 14px 0;
      border-top: 1px solid var(--line);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px 16px;
    }
    .cal-page-footer .footer-meta { font-size: 0.72rem; color: var(--muted); }
    .cal-page-footer .footer-version {
      font-family: 'DM Mono', monospace;
      font-size: 0.65rem;
      color: #94a3b8;
    }

    @media (max-width: 1100px) { .page-grid { grid-template-columns: minmax(0, 1fr); } }
    @media (max-width: 900px)  { .summary-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
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
      .page-sub { display: none; }
      .howto-footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
      .howto-footer .howto-got-it { margin-left: 0; }
      .howto-box { width: min(96vw, 100% - 2rem); }
    }
    @media (max-width: 640px)  {
      .day-cell { min-height: 58px; padding: 5px; }
      .summary-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .btn-howto-label,
      .btn-today-label,
      .btn-addtask-label { display: none; }
    }
    @media (max-width: 480px) {
      .summary-strip { grid-template-columns: minmax(0, 1fr); }
      .logout-modal-inner .modal-btns { flex-direction: column; }
      .logout-modal-inner .btn-lo-cancel,
      .logout-modal-inner .modal-btns form { flex: 0 0 auto; width: 100%; }
      .toast {
        left: var(--pad-x);
        right: var(--pad-x);
        width: auto;
        max-width: none;
      }
    }
  </style>
</head>
<body>

@php
  $user       = $user       ?? (object)['name' => 'KTTM User', 'role' => 'Staff'];
  $allRecords = $allRecords ?? [];
  $allTasks   = $allTasks   ?? [];

  $urlDashboard = url('/home');
  $urlRecords   = url('/records');
  $urlNew       = url('/ipassets/create');
  $urlLogout    = url('/logout');
  $urlInsights  = url('/insights');
  $urlCalendar  = url('/calendar');

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

  $initials = collect(explode(' ', $user->name))
      ->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');

  $now    = \Carbon\Carbon::now();
  $mStart = $now->copy()->startOfMonth();
  $mEnd   = $now->copy()->endOfMonth();

  $unregistered = collect($allRecords)
      ->filter(fn($r) => strtolower($r['status'] ?? '') !== 'registered')->count();
  $pending = collect($allTasks)
      ->filter(fn($t) => ($t['status'] ?? '') === 'pending')->count();
  $completed = collect($allTasks)
      ->filter(fn($t) => ($t['status'] ?? '') === 'done')->count();

  $deadlinesMonth = collect($allRecords)->filter(function($r) use ($mStart, $mEnd) {
    if(empty($r['registered']) || empty($r['type'])) return false;
    try {
      $base = \Carbon\Carbon::parse($r['registered']);
      $due  = match(strtolower(trim($r['type'] ?? ''))) {
        'patent'            => $base->copy()->addYears(20),
        'copyright'         => $base->copy()->addYears(70),
        'utility model'     => $base->copy()->addYears(10),
        'industrial design' => $base->copy()->addYears(15),
        default             => null,
      };
      return $due && $due->between($mStart, $mEnd);
    } catch(\Exception $e){ return false; }
  })->count();
@endphp

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
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

    <a href="{{ $urlInsights }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
      </svg>
      <span class="nav-tooltip">Insights</span>
    </a>

   

    <a href="{{ $urlCalendar }}" class="nav-item active">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
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

{{-- ═══════════════ MAIN ═══════════════ --}}
<div class="main-wrap">

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
        <div class="page-title">Calendar</div>
        <div class="page-sub">Tasks, deadlines &amp; scheduled activities</div>
      </div>
    </div>
    <div class="topbar-right">
      <button type="button" class="btn-howto" id="howToUseBtn" title="How to Use">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span class="btn-howto-label">How to Use</span>
      </button>
      <button type="button" class="btn-gold" id="todayTopBtn" title="Jump to today">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span class="btn-today-label">Today</span>
      </button>
      <button type="button" class="btn-primary" id="addTaskTopBtn" title="Add a new task">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span class="btn-addtask-label">Add Task</span>
      </button>
      
    </div>
  </header>

  <div class="content">

    {{-- SUMMARY STRIP --}}
    <div class="summary-strip fu fu1">

      <div class="sum-card sc-deadline">
        <div class="sum-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <div>
          <div class="sum-num" id="sumDeadlines">{{ $deadlinesMonth }}</div>
          <div class="sum-label">Deadlines This Month</div>
        </div>
      </div>

      <div class="sum-card sc-unreg">
        <div class="sum-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
          </svg>
        </div>
        <div>
          <div class="sum-num">{{ $unregistered }}</div>
          <div class="sum-label">Unregistered Records</div>
        </div>
      </div>

      <div class="sum-card sc-pending">
        <div class="sum-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div>
          <div class="sum-num" id="sumPending">{{ $pending }}</div>
          <div class="sum-label">Pending Tasks</div>
        </div>
      </div>

      <div class="sum-card sc-done">
        <div class="sum-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
        <div>
          <div class="sum-num" id="sumDone">{{ $completed }}</div>
          <div class="sum-label">Completed Tasks</div>
        </div>
      </div>

    </div>

    {{-- MAIN GRID --}}
    <div class="page-grid">

      {{-- ═ CALENDAR ═ --}}
      <div class="cal-card fu fu2">

        <div class="cal-head">
          <button type="button" class="cal-nav" id="prevMonth">&#8249;</button>
          <div class="cal-month-wrap">
            <div class="cal-month-name" id="calMonthName">—</div>
            <div class="cal-year"       id="calYear">—</div>
          </div>
          <div class="cal-head-right">
            <button type="button" class="cal-today-btn" id="calTodayBtn">Today</button>
            <button type="button" class="cal-nav" id="nextMonth">&#8250;</button>
          </div>
        </div>

        <div class="dow-row">
          <div class="dow-cell wknd">Sun</div>
          <div class="dow-cell">Mon</div>
          <div class="dow-cell">Tue</div>
          <div class="dow-cell">Wed</div>
          <div class="dow-cell">Thu</div>
          <div class="dow-cell">Fri</div>
          <div class="dow-cell wknd">Sat</div>
        </div>

        <div class="days-grid" id="calDaysGrid"></div>

        <div class="cal-legend">
          <span class="legend-lbl">Key&nbsp;—</span>
          <div class="legend-item"><div class="legend-pip" style="background:var(--c-deadline)"></div>Deadline</div>
          <div class="legend-item"><div class="legend-pip" style="background:var(--c-reg)"></div>Registration</div>
          <div class="legend-item"><div class="legend-pip" style="background:var(--c-review)"></div>Review</div>
          <div class="legend-item"><div class="legend-pip" style="background:var(--c-submission)"></div>Submission</div>
          <div class="legend-item"><div class="legend-pip" style="background:#bcc4ce"></div>Completed</div>
        </div>
      </div>

      {{-- ═ RIGHT COLUMN ═ --}}
      <div class="right-col">

        {{-- Add Task --}}
        <div class="panel fu fu3" id="addTaskPanel">
          <div class="panel-head">
            <div>
              <div class="panel-title">Add Task</div>
              <div class="panel-sub">Schedule a new activity or deadline</div>
            </div>
          </div>
          <div class="panel-body">
            <div class="form-stack">
              <div>
                <label class="f-label">Task Title *</label>
                <input id="ftTitle" class="f-input" type="text" placeholder="e.g. Submit patent renewal…">
              </div>
              <div>
                <label class="f-label">Date *</label>
                <input id="ftDate" class="f-input" type="date">
              </div>
              <div>
                <label class="f-label">Category</label>
                <div class="cat-swatches" id="catSwatches">
                  <span class="cat-swatch deadline selected"    data-cat="deadline">Deadline</span>
                  <span class="cat-swatch registration"         data-cat="registration">Registration</span>
                  <span class="cat-swatch review"               data-cat="review">Review</span>
                  <span class="cat-swatch submission"           data-cat="submission">Submission</span>
                </div>
              </div>
              <div>
                <label class="f-label">Author / Assigned To</label>
                <input id="ftAuthor" class="f-input" type="text" placeholder="{{ $user->name ?? 'KTTM User' }}">
              </div>
              <div>
                <label class="f-label">Notes <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.68rem;">(optional)</span></label>
                <textarea id="ftNotes" class="f-textarea" rows="2" placeholder="Any additional details…"></textarea>
              </div>
              <button type="button" class="btn-primary btn-block" id="addTaskSubmit">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Save to Calendar
              </button>
            </div>
          </div>
        </div>

        {{-- Recent Tasks --}}
        <div class="panel fu fu4">
          <div class="panel-head">
            <div>
              <div class="panel-title">Recent Tasks</div>
              <div class="panel-sub">Latest scheduled activities</div>
            </div>
            <span id="recentBadge" style="font-family:'DM Mono',monospace;font-size:.65rem;font-weight:700;color:var(--gold);background:rgba(240,200,96,.15);border:1px solid rgba(240,200,96,.25);padding:3px 10px;border-radius:20px;">0</span>
          </div>
          <div class="recent-list" id="recentList">
            <div style="padding:28px 20px;text-align:center;font-size:.78rem;color:var(--muted);">
              Loading tasks…
            </div>
          </div>
        </div>

      </div>
    </div>

    <footer class="cal-page-footer">
      <div class="footer-meta">© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="footer-version">IP Calendar — Task Tracker</div>
    </footer>

  </div>
</div>

{{-- DAY MODAL --}}
<div class="modal-overlay" id="dayModal">
  <div class="modal-box">
    <div class="modal-head">
      <div>
        <div class="modal-eyebrow">Daily Schedule</div>
        <div class="modal-title" id="dayModalTitle">—</div>
        <div class="modal-count" id="dayModalCount">—</div>
      </div>
      <button type="button" class="modal-x" id="closeDayModal">&#10005;</button>
    </div>
    <div class="modal-body" id="dayModalBody"></div>
    <div class="modal-footer">
      <button type="button" class="btn-outline" id="closeDayModal2">Close</button>
      <button type="button" class="btn-primary" id="addHereBtn">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Task Here
      </button>
    </div>
  </div>
</div>

{{-- HOW TO USE MODAL --}}
<div class="howto-overlay" id="howtoModal">
  <div class="howto-box">
    <div class="howto-head">
      <div class="howto-head-top">
        <div class="howto-head-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="howto-head-text">
          <div class="howto-eyebrow">Calendar Page · Guide</div>
          <div class="howto-title">How to Use the Calendar</div>
          <div class="howto-sub">Schedule tasks, track deadlines, and manage IP activities — step by step.</div>
        </div>
        <button type="button" class="howto-close" id="howtoClose">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>
    <div class="howto-body">
      <div class="howto-step">
        <div class="howto-step-num">01</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Navigate the Calendar</div>
          <div class="howto-step-desc">Use the <strong>← →</strong> arrows in the calendar header to move between months. Click <strong>Today</strong> in the topbar to jump back to the current month instantly. Today's date is highlighted with a maroon marker on the grid.</div>
          <span class="howto-step-tag">Calendar → Arrow Buttons</span>
        </div>
      </div>
      <div class="howto-step">
        <div class="howto-step-num">02</div>
        <div class="howto-step-body">
          <div class="howto-step-title">View Tasks for a Day</div>
          <div class="howto-step-desc">Click any date cell to open the <strong>Day Detail modal</strong>. It lists all tasks scheduled for that day — showing each task's category, status, author, and notes. Days that have tasks show coloured chips directly on the calendar cell.</div>
          <span class="howto-step-tag">Calendar → Click a Date</span>
        </div>
      </div>
      <div class="howto-step">
        <div class="howto-step-num">03</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Add a Task</div>
          <div class="howto-step-desc">Use the <strong>Add Task</strong> panel on the right sidebar. Fill in a title, pick a date, choose a category, optionally add your name and notes, then click <strong>Save to Calendar</strong>. The task appears on the calendar immediately without a page reload.</div>
          <span class="howto-step-tag">Right Panel → Add Task Form</span>
        </div>
      </div>
      <div class="howto-step">
        <div class="howto-step-num">04</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Task Categories</div>
          <div class="howto-step-desc">Each task has a colour-coded category: <strong>Deadline</strong> (red), <strong>Registration</strong> (maroon), <strong>Review</strong> (amber), and <strong>Submission</strong> (blue). Pick one using the coloured swatches in the form before saving.</div>
          <span class="howto-step-tag">Add Task Form → Category Swatches</span>
        </div>
      </div>
      <div class="howto-step">
        <div class="howto-step-num">05</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Mark a Task as Done</div>
          <div class="howto-step-desc">Open the Day Detail modal by clicking a date, then click <strong>Mark Done</strong> on any task card. The task turns grey with a strikethrough and the Completed count in the summary strip updates instantly.</div>
          <span class="howto-step-tag">Day Modal → Mark Done Button</span>
        </div>
      </div>
      <div class="howto-step">
        <div class="howto-step-num">06</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Delete a Task</div>
          <div class="howto-step-desc">Inside the Day Detail modal, each task card has a <strong>trash icon</strong>. Click it to permanently remove that task. The calendar grid and the Recent Tasks list both update immediately.</div>
          <span class="howto-step-tag">Day Modal → Trash Icon</span>
        </div>
      </div>
      <div class="howto-step">
        <div class="howto-step-num">07</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Summary Strip</div>
          <div class="howto-step-desc">The four cards at the top show: <strong>Deadlines This Month</strong>, <strong>Unregistered Records</strong>, <strong>Pending Tasks</strong>, and <strong>Completed Tasks</strong>. Pending and Completed update live as you add or complete tasks.</div>
          <span class="howto-step-tag">Top → Summary Cards</span>
        </div>
      </div>
      <div class="howto-step">
        <div class="howto-step-num">08</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Recent Tasks List</div>
          <div class="howto-step-desc">The <strong>Recent Tasks</strong> panel shows the 15 most recent tasks sorted by date — with the day number, title, author, and category badge. Completed tasks appear faded with a strikethrough for a quick status overview.</div>
          <span class="howto-step-tag">Right Panel → Recent Tasks</span>
        </div>
      </div>
    </div>
    <div class="howto-footer">
      <div class="howto-footer-note">Need more help? Contact your <strong>KTTM administrator</strong>.</div>
      <button type="button" class="howto-got-it" id="howtoCloseBtn">Got it, thanks!</button>
    </div>
  </div>
</div>

{{-- LOGOUT MODAL --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal-box" style="max-width:min(400px,calc(100vw - 2rem));">
    <div class="logout-modal-inner">
      <div class="modal-icon">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
       <div style="font-size:1.1rem;font-weight:800;font-family:'Syne',sans-serif;">Sign out of KTTM</div>
      <div style="font-size:.82rem;color:var(--muted);margin-top:6px;line-height:1.6;">This will end your session and return you to the public portal.</div>
      <div class="modal-btns">
        <button type="button" class="btn-lo-cancel" data-close-logout>Cancel</button>
        <form action="{{ $urlLogout }}" method="POST" id="logoutForm">
          @csrf
          <button type="submit" class="btn-lo-confirm">Sign Out</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
'use strict';

/* ══════════════════════════════════════════════
   CONSTANTS & STATE
══════════════════════════════════════════════ */
const CSRF   = document.querySelector('meta[name="csrf-token"]')?.content || '';
const API    = {
  list:   '/api/calendar-tasks',
  store:  '/api/calendar-tasks',
  done:   (id) => `/api/calendar-tasks/${id}/done`,
  remove: (id) => `/api/calendar-tasks/${id}`,
};

// Seed tasks from PHP (avoids an extra API call on first paint)
let tasks = @json(array_values($allTasks));

let CY, CM;
let activeDayISO = null;
let selCat = 'deadline';

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const CAT_LABEL = {
  deadline:'Deadline', registration:'Registration',
  review:'Review', submission:'Submission', done:'Completed'
};
const CAT_CSS = {
  deadline:'cat-deadline', registration:'cat-registration',
  review:'cat-review', submission:'cat-submission', done:'done'
};

function isoDate(y,m,d){ return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`; }
function todayISO(){ const d=new Date(); return isoDate(d.getFullYear(),d.getMonth(),d.getDate()); }
function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

function toast(msg, type='success'){
  const t=document.createElement('div');
  t.className=`toast ${type}`; t.textContent=msg;
  document.body.appendChild(t);
  setTimeout(()=>{ t.classList.add('hiding'); setTimeout(()=>t.remove(),300); }, 3200);
}

/* ══════════════════════════════════════════════
   API HELPERS
══════════════════════════════════════════════ */
async function apiPost(url, body){
  const r = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
    body: JSON.stringify(body),
  });
  return r.json();
}

async function apiPatch(url){
  const r = await fetch(url, {
    method: 'PATCH',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
  });
  return r.json();
}

async function apiDelete(url){
  const r = await fetch(url, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
  });
  return r.json();
}

/* ══════════════════════════════════════════════
   CATEGORY SWATCHES
══════════════════════════════════════════════ */
document.getElementById('catSwatches').addEventListener('click', e => {
  const sw = e.target.closest('.cat-swatch'); if(!sw) return;
  document.querySelectorAll('.cat-swatch').forEach(s=>s.classList.remove('selected'));
  sw.classList.add('selected');
  selCat = sw.dataset.cat;
});

/* ══════════════════════════════════════════════
   RENDER CALENDAR
══════════════════════════════════════════════ */
function renderCal(year, month){
  CY=year; CM=month;
  document.getElementById('calMonthName').textContent = MONTHS[month];
  document.getElementById('calYear').textContent = year;

  const firstDow  = new Date(year,month,1).getDay();
  const daysInMon = new Date(year,month+1,0).getDate();
  const prevDays  = new Date(year,month,0).getDate();
  const tiso      = todayISO();
  const total     = Math.ceil((firstDow+daysInMon)/7)*7;
  const grid      = document.getElementById('calDaysGrid');
  grid.innerHTML  = '';

  for(let i=0;i<total;i++){
    let cy=year, cm=month, cd, other=false;
    if(i<firstDow){
      cm=month-1; cd=prevDays-firstDow+i+1;
      if(cm<0){cm=11;cy=year-1;} other=true;
    } else if(i>=firstDow+daysInMon){
      cm=month+1; cd=i-firstDow-daysInMon+1;
      if(cm>11){cm=0;cy=year+1;} other=true;
    } else { cd=i-firstDow+1; }

    const iso    = isoDate(cy,cm,cd);
    const dow    = i%7;
    const isWknd = dow===0||dow===6;
    const isToday= iso===tiso;

    const cell = document.createElement('div');
    cell.className = 'day-cell'+(other?' other-month':'')+(isWknd?' wknd-col':'')+(isToday?' is-today':'');

    const numWrap = document.createElement('div'); numWrap.className='day-num';
    const numInner= document.createElement('span'); numInner.className='day-num-inner';
    numInner.textContent=cd; numWrap.appendChild(numInner); cell.appendChild(numWrap);

    // Match tasks by task_date field
    const dayTasks = tasks.filter(t => (t.task_date||'').slice(0,10) === iso);
    if(dayTasks.length){
      const chips=document.createElement('div'); chips.className='day-chips';
      dayTasks.slice(0,3).forEach(t=>{
        const chip=document.createElement('div');
        const isDone = t.status === 'done';
        chip.className='day-chip '+(isDone?'done':(CAT_CSS[t.category]||'cat-deadline'));
        chip.textContent=t.title; chips.appendChild(chip);
      });
      if(dayTasks.length>3){
        const more=document.createElement('div'); more.className='day-more';
        more.textContent=`+${dayTasks.length-3} more`; chips.appendChild(more);
      }
      cell.appendChild(chips);
    }
    cell.addEventListener('click',()=>openDay(iso));
    grid.appendChild(cell);
  }
}

/* ══════════════════════════════════════════════
   MONTH NAVIGATION
══════════════════════════════════════════════ */
function goToday(){ const d=new Date(); renderCal(d.getFullYear(),d.getMonth()); }
document.getElementById('prevMonth').addEventListener('click',()=>{ let m=CM-1,y=CY; if(m<0){m=11;y--;} renderCal(y,m); });
document.getElementById('nextMonth').addEventListener('click',()=>{ let m=CM+1,y=CY; if(m>11){m=0;y++;} renderCal(y,m); });
document.getElementById('calTodayBtn').addEventListener('click', goToday);
document.getElementById('todayTopBtn').addEventListener('click', goToday);

/* ══════════════════════════════════════════════
   DAY MODAL
══════════════════════════════════════════════ */
function openDay(iso){
  activeDayISO = iso;
  const [y,m,d] = iso.split('-').map(Number);
  const dateObj  = new Date(y,m-1,d);
  const label    = dateObj.toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric',year:'numeric'});
  const dt       = tasks.filter(t=>(t.task_date||'').slice(0,10)===iso);

  document.getElementById('dayModalTitle').textContent = label;
  document.getElementById('dayModalCount').textContent = dt.length
    ? `${dt.length} task${dt.length>1?'s':''} · ${dt.filter(t=>t.status==='done').length} completed`
    : 'No tasks scheduled';

  renderDayBody(iso);
  document.getElementById('dayModal').classList.add('open');
  document.body.style.overflow='hidden';
  document.getElementById('ftDate').value = iso;
}

function renderDayBody(iso){
  const body = document.getElementById('dayModalBody');
  const dt   = tasks.filter(t=>(t.task_date||'').slice(0,10)===iso);

  if(!dt.length){
    body.innerHTML=`<div class="modal-empty">
      <div class="modal-empty-icon">&#128219;</div>
      No tasks for this day.<br>
      <small style="opacity:.6;margin-top:6px;display:block;">Use "Add Task Here" to schedule one.</small>
    </div>`;
    return;
  }

  body.innerHTML = dt.map(t=>{
    const isDone = t.status === 'done';
    const cc  = isDone ? 'done' : (CAT_CSS[t.category]||'cat-deadline');
    const lbl = isDone ? 'Completed' : (CAT_LABEL[t.category]||t.category);
    return `<div class="mtask ${isDone?'is-done':''}" data-id="${t.id}">
      <div class="mtask-bar ${cc}"></div>
      <div class="mtask-body">
        <div class="mtask-top"><span class="mtask-pill ${cc}">${esc(lbl)}</span></div>
        <div class="mtask-title">${esc(t.title)}</div>
        <div class="mtask-meta">
          ${t.author?`By <strong>${esc(t.author)}</strong>`:''}
          ${t.notes?'&middot; '+esc(t.notes):''}
        </div>
      </div>
      <button class="mark-done-btn" data-id="${t.id}" ${isDone?'disabled':''}>
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        ${isDone?'Done':'Mark Done'}
      </button>
      <button class="delete-task-btn" data-id="${t.id}" title="Delete task">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <polyline points="3 6 5 6 21 6"/>
          <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
          <path d="M10 11v6M14 11v6"/>
          <path d="M9 6V4h6v2"/>
        </svg>
      </button>
    </div>`;
  }).join('');

  /* Mark Done */
  body.querySelectorAll('.mark-done-btn:not([disabled])').forEach(btn=>{
    btn.addEventListener('click', async e=>{
      e.stopPropagation();
      const id = Number(btn.dataset.id);
      btn.disabled = true;
      btn.innerHTML = '<div class="spinner"></div>';

      try {
        const res = await apiPatch(API.done(id));
        if(res.success){
          const idx = tasks.findIndex(t=>t.id===id);
          if(idx>-1) tasks[idx].status = 'done';
          renderDayBody(iso);
          renderCal(CY,CM);
          renderRecent();
          syncCounters();
          toast('Task marked as done ✓');
        } else {
          toast(res.message||'Could not update task.','error');
          btn.disabled = false;
          btn.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Mark Done';
        }
      } catch(err){
        toast('Network error — please try again.','error');
        btn.disabled = false;
      }
    });
  });

  /* Delete */
  body.querySelectorAll('.delete-task-btn').forEach(btn=>{
    btn.addEventListener('click', async e=>{
      e.stopPropagation();
      const id = Number(btn.dataset.id);
      btn.innerHTML = '<div class="spinner dark"></div>';
      btn.disabled = true;

      try {
        const res = await apiDelete(API.remove(id));
        if(res.success){
          tasks = tasks.filter(t=>t.id!==id);
          renderDayBody(iso);
          renderCal(CY,CM);
          renderRecent();
          syncCounters();
          toast('Task deleted');
        } else {
          toast(res.message||'Could not delete task.','error');
          btn.disabled = false;
        }
      } catch(err){
        toast('Network error — please try again.','error');
        btn.disabled = false;
      }
    });
  });

  // Update modal count
  const updated = tasks.filter(t=>(t.task_date||'').slice(0,10)===iso);
  document.getElementById('dayModalCount').textContent = updated.length
    ? `${updated.length} task${updated.length>1?'s':''} · ${updated.filter(t=>t.status==='done').length} completed`
    : 'No tasks scheduled';
}

function closeDay(){
  document.getElementById('dayModal').classList.remove('open');
  document.body.style.overflow='';
  activeDayISO=null;
}
document.getElementById('closeDayModal').addEventListener('click', closeDay);
document.getElementById('closeDayModal2').addEventListener('click', closeDay);
document.getElementById('dayModal').addEventListener('click', e=>{ if(e.target===e.currentTarget) closeDay(); });
document.getElementById('addHereBtn').addEventListener('click', ()=>{
  closeDay();
  document.getElementById('addTaskPanel').scrollIntoView({behavior:'smooth'});
  setTimeout(()=>document.getElementById('ftTitle').focus(), 350);
});

/* ══════════════════════════════════════════════
   ADD TASK  — posts to DB
══════════════════════════════════════════════ */
async function addTask(){
  const title  = (document.getElementById('ftTitle').value||'').trim();
  const date   = (document.getElementById('ftDate').value||'').trim();
  const author = (document.getElementById('ftAuthor').value||'').trim() || '{{ addslashes($user->name ?? "KTTM User") }}';
  const notes  = (document.getElementById('ftNotes').value||'').trim();

  if(!title){ toast('Please enter a task title.','error'); return; }
  if(!date) { toast('Please choose a date.','error');      return; }

  const btn = document.getElementById('addTaskSubmit');
  btn.disabled = true;
  btn.innerHTML = '<div class="spinner"></div> Saving…';

  try {
    const res = await apiPost(API.store, {
      title,
      task_date: date,
      category:  selCat,
      author:    author || null,
      notes:     notes  || null,
    });

    if(res.success && res.task){
      tasks.unshift(res.task);
      document.getElementById('ftTitle').value='';
      document.getElementById('ftNotes').value='';
      renderCal(CY,CM);
      renderRecent();
      syncCounters();
      toast(`"${title}" saved to ${date} ✓`);
    } else {
      const msg = res.errors
        ? Object.values(res.errors).flat().join(' ')
        : (res.message || 'Could not save task.');
      toast(msg,'error');
    }
  } catch(err){
    toast('Network error — please try again.','error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Save to Calendar';
  }
}

document.getElementById('addTaskSubmit').addEventListener('click', addTask);
document.getElementById('addTaskTopBtn').addEventListener('click',()=>{
  document.getElementById('addTaskPanel').scrollIntoView({behavior:'smooth'});
  setTimeout(()=>document.getElementById('ftTitle').focus(),300);
});

/* ══════════════════════════════════════════════
   RECENT LIST
══════════════════════════════════════════════ */
function renderRecent(){
  const list   = document.getElementById('recentList');
  const badge  = document.getElementById('recentBadge');
  const sorted = [...tasks].sort((a,b)=>{
    const da = (a.task_date||'').slice(0,10);
    const db = (b.task_date||'').slice(0,10);
    return da < db ? 1 : da > db ? -1 : 0;
  }).slice(0,15);

  badge.textContent = tasks.length;

  if(!sorted.length){
    list.innerHTML='<div style="padding:28px 20px;text-align:center;font-size:.78rem;color:var(--muted);">No tasks yet — add one above.</div>';
    return;
  }
  list.innerHTML = sorted.map(t=>{
    const iso  = (t.task_date||'').slice(0,10);
    const [y,m,d] = iso.split('-').map(Number);
    const dateObj  = new Date(y,m-1,d);
    const isDone = t.status === 'done';
    const cc  = isDone ? 'cat-done' : (CAT_CSS[t.category]||'cat-deadline');
    const lbl = isDone ? 'Done' : (CAT_LABEL[t.category]||t.category);
    const mon = dateObj.toLocaleDateString('en-US',{month:'short',year:'numeric'});
    return `<div class="recent-item ${isDone?'is-done':''}">
      <div class="ri-date">${String(d).padStart(2,'0')}</div>
      <div class="ri-body">
        <div class="ri-title">${esc(t.title)}</div>
        <div class="ri-meta">${esc(t.author||'KTTM')} &middot; ${esc(mon)}</div>
      </div>
      <span class="ri-cat ${cc}">${esc(lbl)}</span>
    </div>`;
  }).join('');
}

/* ══════════════════════════════════════════════
   SYNC COUNTERS (summary strip)
══════════════════════════════════════════════ */
function syncCounters(){
  const el = id=>document.getElementById(id);
  if(el('sumPending')) el('sumPending').textContent = tasks.filter(t=>t.status!=='done').length;
  if(el('sumDone'))    el('sumDone').textContent    = tasks.filter(t=>t.status==='done').length;
}

/* ══════════════════════════════════════════════
   LOGOUT
══════════════════════════════════════════════ */
document.getElementById('logoutBtn')?.addEventListener('click',()=>{
  document.getElementById('logoutModal').classList.add('open');
  document.body.style.overflow='hidden';
});
document.querySelectorAll('[data-close-logout]').forEach(b=>b.addEventListener('click',()=>{
  document.getElementById('logoutModal').classList.remove('open');
  document.body.style.overflow='';
}));
document.getElementById('logoutModal').addEventListener('click', e=>{
  if(e.target===e.currentTarget){ e.currentTarget.classList.remove('open'); document.body.style.overflow=''; }
});
document.addEventListener('keydown', e=>{
  if(e.key!=='Escape') return;
  closeDay();
  document.getElementById('logoutModal').classList.remove('open');
  if(document.getElementById('howtoModal').classList.contains('open')) closeHowto();
  document.body.style.overflow='';
});

/* ══════════════════════════════════════════════
   HOW TO USE
══════════════════════════════════════════════ */
function openHowto(){
  document.getElementById('howtoModal').classList.add('open');
  document.body.style.overflow='hidden';
}
function closeHowto(){
  document.getElementById('howtoModal').classList.remove('open');
  document.body.style.overflow='';
}
document.getElementById('howToUseBtn')?.addEventListener('click', openHowto);
document.getElementById('howtoClose')?.addEventListener('click', closeHowto);
document.getElementById('howtoCloseBtn')?.addEventListener('click', closeHowto);
document.getElementById('howtoModal').addEventListener('click', e=>{ if(e.target===e.currentTarget) closeHowto(); });

/* ══════════════════════════════════════════════
   BOOT
══════════════════════════════════════════════ */
const now = new Date();
document.getElementById('ftDate').value = isoDate(now.getFullYear(), now.getMonth(), now.getDate());
renderCal(now.getFullYear(), now.getMonth());
renderRecent();
syncCounters();

/* Mobile sidebar (same pattern as home / records / insights) */
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
  const dayOpen = document.getElementById('dayModal')?.classList.contains('open');
  if (!logoutOpen && !howtoOpen && !dayOpen) document.body.style.overflow = '';
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

})();
</script>
</body>
</html>