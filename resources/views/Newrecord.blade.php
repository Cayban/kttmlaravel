<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — New Record</title>

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
      --deep-line:    rgba(255,255,255,.12);
      --deep-muted:   rgba(255,255,255,.55);
      --step-total:   3;
      --pad-x:        clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max:    1440px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { -webkit-font-smoothing: antialiased; scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg); color: var(--ink);
      min-height: 100vh; overflow-x: hidden;
      padding-left: env(safe-area-inset-left);
      padding-right: env(safe-area-inset-right);
    }

    /* ══════════ SIDEBAR ══════════ */
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0; width: var(--sidebar-w);
      background: linear-gradient(180deg, var(--maroon2) 0%, var(--maroon) 100%);
      display: flex; flex-direction: column; align-items: center;
      padding: 20px 0; z-index: 50;
      box-shadow: 4px 0 24px rgba(165,44,48,.22);
    }
    .sidebar-logo {
      width: 42px; height: 42px; border-radius: 14px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1rem; color: #2a1a0b;
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
    .main-wrap {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* ══════════ TOPBAR ══════════ */
    .topbar {
      min-height: 68px;
      background: #fff;
      border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 10px var(--pad-x);
      position: sticky; top: 0; z-index: 40;
      box-shadow: 0 2px 16px rgba(15,23,42,.06);
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
      color: var(--muted); text-decoration: none; transition: all .18s;
      flex-shrink: 0;
    }
    .back-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .page-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.95rem, 0.4vw + 0.85rem, 1.08rem);
      font-weight: 800; color: var(--ink);
      letter-spacing: -.2px; line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-sub {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted); font-weight: 500; margin-top: 1px;
      overflow-wrap: anywhere;
    }
    .btn-gold {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b; border: none; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: clamp(7px, 1.2vw, 9px) clamp(12px, 2vw, 16px);
      border-radius: 10px;
      box-shadow: 0 4px 14px rgba(240,200,96,.28);
      transition: transform .18s, box-shadow .18s;
      flex: 0 1 auto;
      max-width: 100%;
      box-sizing: border-box;
      white-space: nowrap;
    }
    .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(240,200,96,.38); }
    .avatar {
      width: 36px; height: 36px; border-radius: 10px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 0.8rem; color: #2a1a0b;
      flex-shrink: 0;
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

    /* ══════════ WIZARD SHELL ══════════ */
    .wizard-wrap {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 280px);
      gap: 20px;
      align-items: start;
    }
    .wizard-main  { grid-column: 1; display: flex; flex-direction: column; gap: 20px; }
    .wizard-aside { grid-column: 2; display: flex; flex-direction: column; gap: 16px; position: sticky; top: 88px; align-self: start; }
    .wizard-footer { grid-column: 1 / -1; }

    /* ══════════ PAGE HEADER ══════════ */
    .page-hero {
      display: flex; align-items: flex-start; justify-content: space-between;
      flex-wrap: wrap; gap: 16px 20px;
    }
    .page-hero > div:first-child { min-width: 0; flex: 1 1 220px; }
    .hero-eyebrow {
      font-family: 'DM Mono', monospace;
      font-size: 0.6rem; font-weight: 700; letter-spacing: .2em;
      text-transform: uppercase; color: var(--maroon); margin-bottom: 8px;
    }
    .hero-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(1.2rem, 2.5vw + 0.5rem, 1.7rem);
      font-weight: 800; color: var(--ink);
      letter-spacing: -.5px; line-height: 1.15;
      overflow-wrap: anywhere;
    }
    .hero-sub {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.74rem, 0.35vw + 0.7rem, 0.82rem);
      color: var(--muted); margin-top: 6px; font-weight: 500;
      overflow-wrap: anywhere; line-height: 1.45;
    }
    .record-id-badge {
      flex-shrink: 0;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      border-radius: 18px; padding: 18px 22px; min-width: 200px;
      box-shadow: 0 8px 28px rgba(165,44,48,.22);
      position: relative; overflow: hidden;
    }
    .record-id-badge::before {
      content: ''; position: absolute; top: -20px; right: -20px;
      width: 100px; height: 100px; border-radius: 50%;
      background: rgba(255,255,255,.05);
    }
    .rib-eyebrow {
      font-family: 'DM Mono', monospace; font-size: 0.56rem;
      font-weight: 700; letter-spacing: .18em; text-transform: uppercase;
      color: rgba(255,255,255,.45); margin-bottom: 6px;
    }
    .rib-val {
      font-family: 'DM Mono', monospace; font-size: 1.1rem;
      font-weight: 700; color: var(--gold);
    }
    .rib-val.empty { color: rgba(255,255,255,.3); font-size: .8rem; font-weight: 400; }
    .rib-sub { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.65rem; color: rgba(255,255,255,.4); margin-top: 4px; }

    /* ══════════ STEP INDICATOR ══════════ */
    .steps-bar {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      padding: 6px;
      display: flex; gap: 4px;
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
    }
    .step-pill {
      flex: 1 1 0;
      min-width: 0;
      border-radius: 14px;
      padding: clamp(10px, 1.5vw, 12px) clamp(12px, 2vw, 16px);
      display: flex; align-items: center; gap: 12px;
      cursor: pointer; transition: background .2s;
      border: none; background: none; font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;
      box-sizing: border-box;
    }
    .step-pill:hover:not(.active) { background: var(--bg); }
    .step-pill.active { background: linear-gradient(135deg, var(--maroon2), var(--maroon3)); }
    .step-pill.done   { background: var(--bg); }
    .step-num {
      width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 0.72rem; font-weight: 800;
    }
    .step-pill.active .step-num { background: rgba(255,255,255,.18); color: #fff; }
    .step-pill.done   .step-num { background: var(--maroon-light); color: var(--maroon); }
    .step-pill:not(.active):not(.done) .step-num { background: var(--line); color: var(--muted); }
    .step-check { display: none; }
    .step-pill.done .step-check { display: flex; align-items: center; justify-content: center; color: var(--maroon); }
    .step-pill.done .step-num-inner { display: none; }
    .step-info { min-width: 0; }
    .step-label {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.62rem, 0.12vw + 0.6rem, 0.7rem);
      font-weight: 800; letter-spacing: .04em; text-transform: uppercase;
      overflow-wrap: anywhere;
    }
    .step-pill.active .step-label { color: #fff; }
    .step-pill.done   .step-label { color: var(--ink); }
    .step-pill:not(.active):not(.done) .step-label { color: var(--muted); }
    .step-desc {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.6rem, 0.1vw + 0.58rem, 0.66rem);
      font-weight: 500; margin-top: 1px;
      overflow-wrap: anywhere; line-height: 1.35;
    }
    .step-pill.active .step-desc { color: rgba(255,255,255,.6); }
    .step-pill.done   .step-desc { color: var(--muted); }
    .step-pill:not(.active):not(.done) .step-desc { color: #94a3b8; }
    .step-divider {
      width: 1px; background: var(--line); flex-shrink: 0; margin: 10px 0;
    }

    /* ══════════ FORM CARD ══════════ */
    .form-card {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      box-shadow: 0 4px 20px rgba(15,23,42,.06);
      overflow: hidden;
    }
    .form-card-head {
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      padding: clamp(16px, 2.5vw, 20px) clamp(18px, 3vw, 28px);
      display: flex; align-items: center; gap: 16px;
      flex-wrap: wrap;
    }
    .form-card-head > div:nth-child(2) { min-width: 0; flex: 1 1 160px; }
    .fch-icon {
      width: 40px; height: 40px; border-radius: 12px;
      background: rgba(255,255,255,.12);
      display: flex; align-items: center; justify-content: center;
      color: var(--gold); flex-shrink: 0;
    }
    .fch-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.82rem, 0.35vw + 0.76rem, 0.95rem);
      font-weight: 800; color: #fff;
      overflow-wrap: anywhere; line-height: 1.25;
    }
    .fch-sub {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.64rem, 0.15vw + 0.62rem, 0.7rem);
      color: var(--deep-muted); margin-top: 2px;
      overflow-wrap: anywhere; line-height: 1.35;
    }
    .fch-step-badge {
      margin-left: auto; flex-shrink: 0;
      font-family: 'DM Mono', monospace; font-size: 0.65rem; font-weight: 700;
      color: var(--gold); background: rgba(240,200,96,.12);
      border: 1px solid rgba(240,200,96,.22); padding: 4px 12px; border-radius: 20px;
    }
    .form-body { padding: clamp(18px, 3vw, 28px); }

    /* ══════════ SECTION DIVIDER ══════════ */
    .sec-divider {
      font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700;
      letter-spacing: .14em; text-transform: uppercase; color: var(--maroon);
      display: flex; align-items: center; gap: 12px;
      margin: 24px 0 18px;
    }
    .sec-divider::after { content: ''; flex: 1; height: 1px; background: var(--maroon-light); }
    .sec-divider:first-child { margin-top: 0; }

    /* ══════════ FIELDS ══════════ */
    .field-grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    .field-grid-3 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
    .span-2 { grid-column: span 2; }
    .span-3 { grid-column: span 3; }
    .field-group { display: flex; flex-direction: column; gap: 6px; }
    .field-label {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.7rem; font-weight: 700; color: var(--muted);
      letter-spacing: .05em; text-transform: uppercase;
      display: flex; align-items: center; gap: 5px;
    }
    .req { color: var(--maroon); }
    .field-input, .field-select, .field-textarea {
      width: 100%; border-radius: 12px;
      border: 1.5px solid var(--line);
      background: var(--bg);
      padding: 11px 15px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.84rem; color: var(--ink);
      transition: border-color .2s, box-shadow .2s, background .2s;
      outline: none;
    }
    .field-input:focus, .field-select:focus, .field-textarea:focus {
      border-color: var(--maroon);
      box-shadow: 0 0 0 3px var(--maroon-light);
      background: #fff;
    }
    .field-input::placeholder, .field-textarea::placeholder { color: #94a3b8; }
    .field-input:disabled { background: #f1f4f9; color: var(--muted); cursor: not-allowed; opacity: .6; }
    .field-select { cursor: pointer; }
    .field-textarea { resize: vertical; min-height: 96px; }
    .field-error { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.7rem; color: #ef4444; font-weight: 700; }
    .field-hint  { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.68rem; color: #e09b1a; font-weight: 600; }

    .select-wrap { position: relative; }
    .select-wrap::after {
      content: ''; position: absolute; right: 13px; top: 50%;
      transform: translateY(-50%); pointer-events: none;
      width: 0; height: 0;
      border-left: 5px solid transparent; border-right: 5px solid transparent;
      border-top: 6px solid var(--muted);
    }

    .dateLocked { pointer-events: none; user-select: none; opacity: .55; }

    /* ══════════ RECORD ID STRIP ══════════ */
    .id-strip {
      display: flex; align-items: center; gap: 14px;
      background: var(--bg); border-radius: 14px; padding: 14px 18px;
      border: 1.5px solid var(--line); margin-bottom: 20px;
    }
    .id-strip > div:not(.id-strip-icon) { min-width: 0; flex: 1; }
    .id-strip-icon {
      width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center;
    }
    .id-strip-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.65rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .06em; }
    .id-strip-val   { font-family: 'DM Mono', monospace; font-size: 0.92rem; font-weight: 800; color: var(--ink); margin-top: 2px; overflow-wrap: anywhere; }
    .id-strip-val.empty { color: #94a3b8; font-weight: 400; font-size: .8rem; }

    /* ══════════ INVENTORS ══════════ */
    .inventors-box {
      background: var(--bg); border-radius: 14px;
      border: 1.5px solid var(--line); overflow: hidden;
    }
    .inventors-head {
      padding: 12px 16px; border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
    }
    .inventors-head-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.72rem; font-weight: 800; color: var(--ink); letter-spacing: .02em;
    }
    .inventors-head-sub { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.65rem; color: var(--muted); margin-top: 1px; }
    .inventors-body { padding: 12px; display: flex; flex-direction: column; gap: 8px; min-height: 60px; }
    .inventor-row {
      display: flex; align-items: center; gap: 10px;
      background: #fff; border-radius: 12px; padding: 10px 12px;
      border: 1.5px solid var(--line); transition: border-color .2s;
    }
    .inventor-row:focus-within { border-color: var(--maroon); }
    .inventor-idx {
      font-family: 'Plus Jakarta Sans', sans-serif;
      width: 26px; height: 26px; border-radius: 7px; flex-shrink: 0;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.68rem; font-weight: 800;
    }
    .inventor-row .field-input { background: var(--bg); border-radius: 9px; padding: 8px 12px; }
    .inventor-row .field-select { background: var(--bg); border-radius: 9px; padding: 8px 12px; }
    .inventor-name-cell   { flex: 1 1 160px; min-width: 0; }
    .inventor-gender-cell { flex: 0 1 130px; min-width: 0; max-width: 100%; }
    .btn-remove {
      width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
      background: rgba(239,68,68,.08); color: #ef4444;
      border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: background .18s;
    }
    .btn-remove:hover { background: rgba(239,68,68,.18); }
    .inventors-empty {
      font-family: 'Plus Jakarta Sans', sans-serif;
      text-align: center; padding: 16px; font-size: .78rem; color: #94a3b8; font-weight: 600;
    }
    .btn-add-inventor {
      display: flex; align-items: center; justify-content: center; gap: 6px;
      width: 100%; padding: 11px; margin-top: 2px;
      background: none; border: 1.5px dashed rgba(165,44,48,.25);
      border-radius: 0 0 12px 12px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.76rem; font-weight: 700;
      color: var(--maroon); cursor: pointer; transition: all .18s;
    }
    .btn-add-inventor:hover { background: var(--maroon-light); border-color: var(--maroon); }

    /* ══════════ STEP NAVIGATION ══════════ */
    .step-nav {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px 16px;
      padding: clamp(14px, 2.5vw, 20px) clamp(18px, 3vw, 28px);
      border-top: 1px solid var(--line);
      background: #fafbfc;
    }
    .step-nav-left  {
      display: flex; align-items: center; flex-wrap: wrap; gap: 10px;
      min-width: 0; flex: 1 1 auto;
    }
    .step-nav-right {
      display: flex; align-items: center; flex-wrap: wrap; gap: 10px;
      justify-content: flex-end;
      min-width: 0; flex: 0 1 auto;
      max-width: 100%;
    }
    .btn-prev {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.5vw, 20px);
      border-radius: 11px;
      border: 1.5px solid var(--line); background: var(--card);
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      color: var(--muted); cursor: pointer; transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-prev:hover { border-color: #cbd5e1; color: var(--ink); }
    .btn-next {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      padding: clamp(9px, 1.2vw, 10px) clamp(16px, 2.8vw, 22px);
      border-radius: 11px; border: none;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      cursor: pointer; box-shadow: 0 4px 16px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-next:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(165,44,48,.38); }
    .btn-save {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      padding: clamp(9px, 1.2vw, 10px) clamp(16px, 3vw, 24px);
      border-radius: 11px; border: none;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      cursor: pointer; box-shadow: 0 4px 16px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(165,44,48,.38); }
    .btn-clear {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.2vw, 18px);
      border-radius: 11px;
      border: 1.5px solid var(--line); background: none;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700;
      color: var(--muted); cursor: pointer; transition: all .18s;
      text-decoration: none;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-clear:hover { background: var(--bg); color: var(--ink); }
    .progress-text {
      font-family: 'DM Mono', monospace; font-size: 0.68rem;
      font-weight: 700; color: var(--muted);
      overflow-wrap: anywhere;
    }

    /* ══════════ STEP PANELS ══════════ */
    .step-panel { display: none; }
    .step-panel.active { display: block; animation: stepIn .3s ease; }
    @keyframes stepIn {
      from { opacity: 0; transform: translateX(14px); }
      to   { opacity: 1; transform: translateX(0); }
    }

    /* ══════════ REVIEW PANEL ══════════ */
    .review-grid {
      display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px;
    }
    .review-item {
      background: var(--bg); border-radius: 12px;
      border: 1.5px solid var(--line); padding: 12px 15px;
    }
    .review-item.full { grid-column: span 2; }
    .rv-label {
      font-family: 'DM Mono', monospace; font-size: 0.57rem; font-weight: 700;
      letter-spacing: .12em; text-transform: uppercase; color: var(--muted); margin-bottom: 4px;
    }
    .rv-val {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.78rem, 0.2vw + 0.74rem, 0.84rem);
      font-weight: 700; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .rv-val.empty { color: #cbd5e1; font-style: italic; font-weight: 400; font-size: .78rem; }
    .review-inventors {
      display: flex; flex-direction: column; gap: 6px; margin-top: 4px;
    }
    .ri-pill {
      font-family: 'Plus Jakarta Sans', sans-serif;
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--maroon-light); color: var(--maroon);
      padding: 4px 10px; border-radius: 20px;
      font-size: 0.72rem; font-weight: 700;
    }

    /* ══════════ MODALS ══════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(10,14,20,.65); backdrop-filter: blur(8px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: var(--card); border-radius: 22px;
      box-shadow: 0 40px 100px rgba(0,0,0,.22);
      width: min(420px, calc(100vw - 2rem));
      max-width: 100%;
      animation: stepIn .28s ease;
      box-sizing: border-box;
    }
    .modal-head {
      padding: 20px 24px 16px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      border-bottom: 1px solid rgba(255,255,255,.12);
      display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;
      border-radius: 22px 22px 0 0;
    }
    .modal-eyebrow { font-family: 'DM Mono', monospace; font-size: 0.58rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: var(--gold); margin-bottom: 4px; }
    .modal-title   { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 800; color: #fff; }
    .modal-close {
      width: 30px; height: 30px; border-radius: 8px;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
      color: #fff; font-size: 0.9rem; cursor: pointer;
      display: flex; align-items: center; justify-content: center; transition: background .15s;
    }
    .modal-close:hover { background: rgba(255,255,255,.22); }
    .modal-body { font-family: 'Plus Jakarta Sans', sans-serif; padding: 20px 24px; font-size: 0.82rem; color: var(--muted); line-height: 1.65; }
    .modal-list { display: flex; flex-direction: column; gap: 6px; margin-top: 12px; }
    .modal-list-item {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg); border-radius: 10px; padding: 9px 13px;
      font-size: 0.76rem; color: var(--ink); font-weight: 600;
      border-left: 3px solid var(--maroon);
    }
    .modal-footer {
      padding: 14px 24px; border-top: 1px solid var(--line);
      display: flex; flex-wrap: wrap; gap: 10px;
    }
    .btn-modal-cancel {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 10px; border-radius: 11px;
      border: 1.5px solid var(--line); background: none;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700; color: var(--muted);
      cursor: pointer; transition: all .18s;
      box-sizing: border-box;
    }
    .btn-modal-cancel:hover { background: var(--bg); }
    .btn-modal-confirm {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 10px; border-radius: 11px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      cursor: pointer; box-shadow: 0 4px 14px rgba(165,44,48,.25);
      transition: all .18s;
      box-sizing: border-box;
    }
    .btn-modal-confirm:hover { box-shadow: 0 8px 20px rgba(165,44,48,.38); }

    /* ══════════ TOAST ══════════ */
    .toast {
      position: fixed; bottom: 24px; right: 24px; z-index: 200;
      min-width: 280px; padding: 13px 18px; border-radius: 14px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 0.8rem;
      display: flex; align-items: center; gap: 10px;
      box-shadow: 0 12px 40px rgba(2,6,23,.18);
      animation: toastIn .3s ease;
    }
    @keyframes toastIn  { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:none; } }
    @keyframes toastOut { from { opacity:1; transform:none; } to { opacity:0; transform:translateY(16px); } }
    .toast.hiding { animation: toastOut .3s ease forwards; }
    .toast.success { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: #2a1a0b; border-left: 4px solid var(--maroon); }
    .toast.error   { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border-left: 4px solid #b91c1c; }

    /* ══════════ FOOTER ══════════ */
    footer.wizard-footer,
    footer {
      padding: 16px 0; border-top: 1px solid var(--line);
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 10px 16px;
    }
    footer div:first-child { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .72rem; color: var(--muted); }
    footer div:last-child  { font-family: 'DM Mono', monospace; font-size: .65rem; color: #94a3b8; }

    /* ══════════ ASIDE CARDS ══════════ */
    .aside-card {
      background: var(--card); border-radius: 18px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden;
    }
    .aside-card-head {
      display: flex; align-items: center; gap: 8px;
      padding: 12px 16px; border-bottom: 1px solid var(--line);
      font-size: 0.68rem; font-weight: 800; letter-spacing: .06em;
      text-transform: uppercase; color: var(--muted);
    }
    .aside-id-val {
      font-family: 'DM Mono', monospace; font-size: 1rem;
      font-weight: 700; color: var(--maroon); padding: 14px 16px 4px;
    }
    .aside-id-val.empty { color: #94a3b8; font-size: .82rem; font-weight: 400; font-style: italic; }
    .aside-id-sub { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.65rem; color: var(--muted); padding: 0 16px 14px; }

    .aside-preview-rows { padding: 12px; display: flex; flex-direction: column; gap: 8px; }
    .apr-row { display: flex; flex-direction: column; gap: 2px; }
    .apr-label {
      font-family: 'DM Mono', monospace; font-size: 0.56rem; font-weight: 700;
      letter-spacing: .12em; text-transform: uppercase; color: var(--muted);
    }
    .apr-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; font-weight: 700; color: var(--ink); }
    .apr-val.empty { color: #cbd5e1; font-style: italic; font-weight: 400; font-size: .75rem; }
    .apr-empty { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .75rem; color: #94a3b8; text-align: center; padding: 10px 0; font-style: italic; }

    .aside-tips {
      background: linear-gradient(145deg, var(--maroon2) 0%, #9B2A2E 55%, #C1363A 100%);
      border-radius: 20px; padding: 0;
      box-shadow: 0 10px 32px rgba(165,44,48,.30);
      position: relative; overflow: hidden;
      border: 1px solid rgba(255,255,255,.08);
    }
    .aside-tips::before {
      content: ''; position: absolute; top: -50px; right: -50px;
      width: 180px; height: 180px; border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 70%);
      pointer-events: none;
    }
    .aside-tips::after {
      content: ''; position: absolute; bottom: -40px; left: -30px;
      width: 140px; height: 140px; border-radius: 50%;
      background: radial-gradient(circle, rgba(240,200,96,.08) 0%, transparent 70%);
      pointer-events: none;
    }
    .tips-header {
      display: flex; align-items: center; gap: 11px;
      padding: 16px 18px 14px; position: relative; z-index: 1;
      border-bottom: 1px solid rgba(255,255,255,.10);
    }
    .tips-icon {
      width: 36px; height: 36px; border-radius: 11px; flex-shrink: 0;
      background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18);
      display: flex; align-items: center; justify-content: center;
      color: var(--gold);
    }
    .tips-label {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.84rem; font-weight: 800; color: #fff; letter-spacing: -.1px;
    }
    .tips-sublabel {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 0.62rem; color: rgba(255,255,255,.42); margin-top: 1px; font-weight: 500;
    }
    .tips-list { display: flex; flex-direction: column; gap: 0; position: relative; z-index: 1; padding: 8px 10px 12px; }
    .tips-item {
      font-family: 'Plus Jakarta Sans', sans-serif;
      display: flex; align-items: flex-start; gap: 9px;
      font-size: .72rem; color: rgba(255,255,255,.78);
      font-weight: 500; line-height: 1.5;
      padding: 8px 9px; border-radius: 10px;
      transition: background .18s; min-width: 0;
    }
    .tips-item span.tips-text { flex: 1; min-width: 0; }
    .tips-item:hover { background: rgba(255,255,255,.07); }
    .tips-item strong { color: var(--gold); font-weight: 700; }
    .tips-num {
      min-width: 20px; width: 20px; height: 20px; border-radius: 6px; flex-shrink: 0;
      background: rgba(240,200,96,.18); border: 1px solid rgba(240,200,96,.28);
      color: var(--gold); font-size: 0.58rem; font-weight: 800;
      display: flex; align-items: center; justify-content: center;
      font-family: 'DM Mono', monospace; margin-top: 2px; letter-spacing: 0;
    }
     /* LOGOUT MODAL */
    .logout-modal-inner { padding: 28px; }
    .modal-icon { width: 52px; height: 52px; border-radius: 16px; background: rgba(165,44,48,.1); color: var(--maroon); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
    .logout-modal-inner .modal-title { font-size: 1.1rem; font-weight: 800; color: var(--ink); font-family: 'Plus Jakarta Sans', sans-serif; }
    .modal-desc { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem; color: var(--muted); margin-top: 6px; line-height: 1.6; }
    .modal-btns {
      display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px;
      align-items: stretch;
    }
    .modal-btns form {
      flex: 1 1 140px;
      min-width: 0;
      display: flex;
    }
    .btn-cancel {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 12px; border-radius: 12px;
      border: 1.5px solid var(--line); background: none;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
      font-weight: 700; cursor: pointer; color: var(--muted);
      transition: background .18s;
      box-sizing: border-box;
    }
    .btn-cancel:hover { background: var(--bg); }
    .btn-confirm {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      padding: 12px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      border: none;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
      font-weight: 700; cursor: pointer; color: #fff;
      box-shadow: 0 6px 18px rgba(165,44,48,.25);
      transition: opacity .18s;
      box-sizing: border-box;
    }
    .btn-confirm:hover { opacity: .88; }
    @media (max-width: 900px) {
      .wizard-wrap { grid-template-columns: 1fr; }
      .wizard-aside { grid-column: 1; grid-row: auto; position: static; }
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
      .topbar { min-height: 60px; }
      .page-sub { display: none; }
    }
    @media (max-width: 700px) {
      .field-grid-2, .field-grid-3 { grid-template-columns: minmax(0, 1fr); }
      .span-2, .span-3 { grid-column: span 1; }
      .steps-bar { flex-direction: column; }
      .step-divider { width: 100%; height: 1px; margin: 0; }
      .step-pill { flex: 0 1 auto; width: 100%; }
      .review-grid { grid-template-columns: minmax(0, 1fr); }
      .review-item.full { grid-column: span 1; }
      .step-nav { flex-direction: column; align-items: stretch; }
      .step-nav-right { justify-content: stretch; }
      .step-nav-right .btn-next,
      .step-nav-right .btn-save,
      .step-nav-right .btn-clear { flex: 1 1 auto; justify-content: center; }
      .step-nav-left .btn-prev { flex: 0 1 auto; }
    }
    @media (max-width: 640px) {
      .btn-gold-label { display: none; }
      .inventor-row { flex-wrap: wrap; }
      .inventor-name-cell   { flex: 1 1 100%; }
      .inventor-gender-cell { flex: 1 1 100%; }
    }
    @media (max-width: 480px) {
      .modal-btns { flex-direction: column; }
      .modal-btns .btn-cancel,
      .modal-btns form { flex: 0 0 auto; width: 100%; }
      .modal-footer .btn-modal-cancel,
      .modal-footer .btn-modal-confirm { flex: 0 0 auto; width: 100%; }
    }
  </style>
</head>
<body>

  @php
    $user         = $user         ?? (object)['name' => 'KTTM User', 'role' => 'Staff'];
    $campuses     = $campuses     ?? ['Alangilan','ARASOF-Nasugbu','Balayan','Lemery','Lipa','Malvar','Pablo Borbon','Rosario','San Juan'];
    $types        = $types        ?? ['Patent','Utility Model','Industrial Design','Copyright','Trademark'];
    $statuses     = $statuses     ?? ['Registered','Unregistered','Recently Filed','Close to Expiry'];
    $colleges     = $colleges     ?? [];
    $programs     = $programs     ?? [];
    $nextRecordId = $nextRecordId ?? '';
    $initials     = strtoupper(substr($user->name ?? 'K', 0, 1) . (strpos($user->name ?? '', ' ') !== false ? substr($user->name, strpos($user->name, ' ') + 1, 1) : 'T'));

    $urlDashboard = url('/home');
    $urlRecords   = url('/records');
    $urlLogout    = url('/logout');
    $urlProfile   = url('/profile');
    $urlStore     = url('/ipassets');
  @endphp

  {{-- SIDEBAR --}}
  <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
  <aside class="sidebar" id="mainSidebar" aria-label="Main navigation">
    <div class="sidebar-logo">K</div>
    <nav class="sidebar-nav">
      <a href="{{ $urlDashboard }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span class="nav-tooltip">Dashboard</span>
      </a>
      <a href="{{ $urlRecords }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <span class="nav-tooltip">Records</span>
      </a>
      <a href="{{ url('/insights') }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
        </svg>
        <span class="nav-tooltip">Insights</span>
      </a>
      
      <a href="{{ url('/calendar') }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="4" width="18" height="16" rx="2"/>
          <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <span class="nav-tooltip">Calendar</span>
      </a>
    </nav>
    <div class="sidebar-bottom">
    
      <button type="button" id="logoutBtn" class="nav-item" style="background:none;border:none;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span class="nav-tooltip">Log Out</span>
      </button>
    </div>
  </aside>

  {{-- MAIN --}}
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
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
          </svg>
        </a>
        <div class="topbar-titles">
          <div class="page-title">New IP Record</div>
          <div class="page-sub">Dashboard › Records › New Record</div>
        </div>
      </div>
      <div class="topbar-right">
        <button id="fillDemoBtn" type="button" class="btn-gold" title="Fill demo data">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
          </svg>
          <span class="btn-gold-label">Fill Demo</span>
        </button>
        <div class="avatar">{{ $initials }}</div>
      </div>
    </header>

    {{-- CONTENT --}}
    <div class="content">
      <div class="wizard-wrap">

        {{-- ═══ LEFT MAIN COLUMN ═══ --}}
        <div class="wizard-main">

        {{-- PAGE HERO --}}
        <div class="page-hero">
          <div>
            <div class="hero-eyebrow">IP Record Entry</div>
            <div class="hero-title">Add a New Record</div>
            <div class="hero-sub">Complete all three steps. You can still edit the record after saving.</div>
          </div>
        </div>

        {{-- STEP INDICATOR --}}
        <div class="steps-bar" id="stepsBar">

          <button type="button" class="step-pill active" data-step="1" id="stepBtn1">
            <div class="step-num">
              <span class="step-num-inner">1</span>
              <span class="step-check">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
            <div class="step-info">
              <div class="step-label">Identification</div>
              <div class="step-desc">Title, type &amp; status</div>
            </div>
          </button>

          <div class="step-divider"></div>

          <button type="button" class="step-pill" data-step="2" id="stepBtn2">
            <div class="step-num">
              <span class="step-num-inner">2</span>
              <span class="step-check">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
            <div class="step-info">
              <div class="step-label">Ownership</div>
              <div class="step-desc">Inventors &amp; campus</div>
            </div>
          </button>

          <div class="step-divider"></div>

          <button type="button" class="step-pill" data-step="3" id="stepBtn3">
            <div class="step-num">
              <span class="step-num-inner">3</span>
              <span class="step-check">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              </span>
            </div>
            <div class="step-info">
              <div class="step-label">Filing Details</div>
              <div class="step-desc">Dates, refs &amp; review</div>
            </div>
          </button>

        </div>

        {{-- FORM --}}
        <form id="createRecordForm" action="{{ $urlStore }}" method="POST">
          @csrf
          <input type="hidden" id="inventorsData" name="inventors" value="[]" />

          {{-- ═══ STEP 1: IDENTIFICATION ═══ --}}
          <div class="form-card step-panel active" id="panel1">
            <div class="form-card-head">
              <div class="fch-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div>
                <div class="fch-title">Step 1 — Identification</div>
                <div class="fch-sub">What is this IP record about?</div>
              </div>
              <div class="fch-step-badge">1 of 3</div>
            </div>
            <div class="form-body">

              {{-- Auto ID strip --}}
              <div class="id-strip">
                <div class="id-strip-icon">
                  <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                  </svg>
                </div>
                <div>
                  <div class="id-strip-label">Record ID — Auto-generated</div>
                  <div class="id-strip-val {{ $nextRecordId ? '' : 'empty' }}">{{ $nextRecordId ?: 'Will be assigned on save' }}</div>
                </div>
              </div>

              <div class="sec-divider">IP Details</div>
              <div class="field-grid-2" style="margin-bottom:16px;">
                {{-- IP Title --}}
                <div class="span-2 field-group">
                  <label for="title" class="field-label">IP Title <span class="req">*</span></label>
                  <input id="title" name="title" type="text" required
                    value="{{ old('title') }}"
                    placeholder="e.g., Smart Sensor-Based Pediatric Screening Kiosk"
                    class="field-input" />
                  @error('title')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Type / Category --}}
                <div class="field-group">
                  <label for="type" class="field-label">Category <span class="req">*</span></label>
                  <div class="select-wrap">
                    <select id="type" name="type" required class="field-select">
                      <option value="">Select category</option>
                      @foreach($types as $t)
                        <option value="{{ $t }}" @selected(old('type') == $t)>{{ $t }}</option>
                      @endforeach
                    </select>
                  </div>
                  @error('type')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="field-group">
                  <label for="status" class="field-label">Status <span class="req">*</span></label>
                  <div class="select-wrap">
                    <select id="status" name="status" required class="field-select">
                      <option value="">Select status</option>
                      <option value="Registered"     @selected(old('status') == 'Registered')>Registered</option>
                      <option value="Unregistered"   @selected(old('status') == 'Unregistered')>Unregistered</option>
                      <option value="Recently Filed" @selected(old('status') == 'Recently Filed')>Recently Filed</option>
                      <option value="Close to Expiry" @selected(old('status') == 'Close to Expiry')>Close to Expiry</option>
                    </select>
                  </div>
                  @error('status')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Class of Work --}}
                <div class="span-2 field-group">
                  <label for="class_of_work" class="field-label">Class of Work</label>
                  <input id="class_of_work" name="class_of_work" type="text"
                    value="{{ old('class_of_work') }}"
                    placeholder="e.g., Literary, Musical, Artistic…"
                    class="field-input" />
                  @error('class_of_work')<div class="field-error">{{ $message }}</div>@enderror
                </div>

              </div>

            </div>
            <div class="step-nav">
              <div class="step-nav-left">
                <span class="progress-text">Step 1 of 3</span>
              </div>
              <div class="step-nav-right">
                <button type="button" class="btn-clear" id="resetBtn">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>
                  </svg>
                  Clear
                </button>
                <button type="button" class="btn-next" id="next1Btn">
                  Next — Ownership
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          {{-- ═══ STEP 2: OWNERSHIP ═══ --}}
          <div class="form-card step-panel" id="panel2">
            <div class="form-card-head">
              <div class="fch-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                  <circle cx="9" cy="7" r="4"/>
                  <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                </svg>
              </div>
              <div>
                <div class="fch-title">Step 2 — Ownership</div>
                <div class="fch-sub">Who created it and where?</div>
              </div>
              <div class="fch-step-badge">2 of 3</div>
            </div>
            <div class="form-body">

              <div class="sec-divider">Inventors / Authors</div>

              <div class="inventors-box">
                <div class="inventors-head">
                  <div>
                    <div class="inventors-head-title">Owner / Inventor List <span style="color:var(--maroon);font-weight:800;">*</span></div>
                    <div class="inventors-head-sub">Add each inventor or author with their gender</div>
                  </div>
                </div>
                <div class="inventors-body" id="inventorsList">
                  <div class="inventors-empty">No inventors added yet. Click below to begin.</div>
                </div>
                <button id="addInventorBtn" type="button" class="btn-add-inventor">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                  </svg>
                  Add Inventor / Author
                </button>
              </div>
              <div id="inventorsError" class="field-error" style="display:none;margin-top:8px;"></div>

              <div class="sec-divider" style="margin-top:24px;">Location</div>
              <div class="field-grid-3">
                <div class="field-group">
                  <label for="campus" class="field-label">Campus <span class="req">*</span></label>
                  <div class="select-wrap">
                    <select id="campus" name="campus" required class="field-select">
                      <option value="">Select campus</option>
                      @foreach($campuses as $c)
                        <option value="{{ $c }}" @selected(old('campus') == $c)>{{ $c }}</option>
                      @endforeach
                    </select>
                  </div>
                  @error('campus')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                  <label for="college" class="field-label">College</label>
                  <input id="college" name="college" type="text" list="collegeList"
                    value="{{ old('college') }}"
                    placeholder="Type or select — or N/A"
                    class="field-input" autocomplete="off" />
                  <datalist id="collegeList">
                    <option value="N/A">
                    @foreach($colleges as $col)
                      <option value="{{ $col }}">
                    @endforeach
                  </datalist>
                </div>

                <div class="field-group">
                  <label for="program" class="field-label">Program</label>
                  <input id="program" name="program" type="text" list="programList"
                    value="{{ old('program') }}"
                    placeholder="Type or select — or N/A"
                    class="field-input" autocomplete="off" />
                  <datalist id="programList">
                    <option value="N/A">
                    @foreach($programs as $prog)
                      <option value="{{ $prog }}">
                    @endforeach
                  </datalist>
                </div>
              </div>

            </div>
            <div class="step-nav">
              <div class="step-nav-left">
                <button type="button" class="btn-prev" id="prev2Btn">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                  </svg>
                  Back
                </button>
                <span class="progress-text">Step 2 of 3</span>
              </div>
              <div class="step-nav-right">
                <button type="button" class="btn-next" id="next2Btn">
                  Next — Filing Details
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          {{-- ═══ STEP 3: FILING DETAILS + REVIEW ═══ --}}
          <div class="form-card step-panel" id="panel3">
            <div class="form-card-head">
              <div class="fch-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                  <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
              </div>
              <div>
                <div class="fch-title">Step 3 — Filing Details &amp; Review</div>
                <div class="fch-sub">Add reference numbers, links, then confirm and save</div>
              </div>
              <div class="fch-step-badge">3 of 3</div>
            </div>
            <div class="form-body">

              <div class="sec-divider">Filing Information</div>
              <div class="field-grid-2" style="margin-bottom:16px;">

                <div class="field-group">
                  <label for="registration_number" class="field-label">
                    Registration Number
                    <span id="registrationHint" class="field-hint" style="display:none;">— Not applicable for this status</span>
                  </label>
                  <input id="registration_number" name="registration_number" type="text"
                    value="{{ old('registration_number') }}"
                    placeholder="e.g., 4-2026-000123"
                    class="field-input" />
                </div>

                <div class="field-group">
                  <label for="registered" class="field-label">
                    Date Registered
                    <span id="registeredHint" class="field-hint" style="display:none;">— Not applicable for this status</span>
                  </label>
                  <input id="registered" name="registered" type="date"
                    value="{{ old('registered') }}"
                    class="field-input" />
                  @error('registered')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                  <label for="date_creation" class="field-label">Date of Creation</label>
                  <input id="date_creation" name="date_creation" type="date"
                    value="{{ old('date_creation') }}"
                    class="field-input" />
                </div>

                <div class="field-group">
                  <label for="gdrive_link" class="field-label">GDrive Link</label>
                  <input id="gdrive_link" name="gdrive_link" type="url"
                    value="{{ old('gdrive_link') }}"
                    placeholder="https://drive.google.com/…"
                    class="field-input" />
                  @error('gdrive_link')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="span-2 field-group">
                  <label for="remarks" class="field-label">Remarks (optional)</label>
                  <textarea id="remarks" name="remarks" rows="3"
                    placeholder="Any extra notes or remarks about this record…"
                    class="field-textarea">{{ old('remarks') }}</textarea>
                  @error('remarks')<div class="field-error">{{ $message }}</div>@enderror
                </div>

              </div>

              {{-- REVIEW SUMMARY --}}
              <div class="sec-divider">Review Summary</div>
              <div class="review-grid" id="reviewGrid">
                <div class="review-item">
                  <div class="rv-label">Category</div>
                  <div class="rv-val empty" id="rv-type">—</div>
                </div>
                <div class="review-item">
                  <div class="rv-label">Status</div>
                  <div class="rv-val empty" id="rv-status">—</div>
                </div>
                <div class="review-item full">
                  <div class="rv-label">IP Title</div>
                  <div class="rv-val empty" id="rv-title">—</div>
                </div>
                <div class="review-item">
                  <div class="rv-label">Campus</div>
                  <div class="rv-val empty" id="rv-campus">—</div>
                </div>
                <div class="review-item">
                  <div class="rv-label">College / Program</div>
                  <div class="rv-val empty" id="rv-college">—</div>
                </div>
                <div class="review-item full">
                  <div class="rv-label">Inventors / Authors</div>
                  <div id="rv-inventors"><div class="rv-val empty">None added yet</div></div>
                </div>
              </div>

            </div>
            <div class="step-nav">
              <div class="step-nav-left">
                <button type="button" class="btn-prev" id="prev3Btn">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                  </svg>
                  Back
                </button>
                <span class="progress-text">Step 3 of 3</span>
              </div>
              <div class="step-nav-right">
                <a href="{{ $urlRecords }}" class="btn-clear">Cancel</a>
                <button type="submit" class="btn-save" id="saveBtn">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                    <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                  </svg>
                  Save Record
                </button>
              </div>
            </div>
          </div>

        </form>

        </div>{{-- /wizard-main --}}

        {{-- ═══ RIGHT ASIDE COLUMN ═══ --}}
        <aside class="wizard-aside">

          {{-- Record ID Card --}}
          <div class="aside-card">
            <div class="aside-card-head">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
              Auto-assigned ID
            </div>
            <div class="aside-id-val {{ $nextRecordId ? '' : 'empty' }}">
              {{ $nextRecordId ?: 'Pending save' }}
            </div>
            <div class="aside-id-sub">Assigned on successful submission</div>
          </div>

          {{-- Live Preview Card --}}
          <div class="aside-card" id="asidePreview">
            <div class="aside-card-head">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              Live Preview
            </div>
            <div class="aside-preview-rows" id="asidePreviewRows">
              <div class="apr-empty">Fill in fields to see a preview here.</div>
            </div>
          </div>

          {{-- Quick Tips --}}
          <div class="aside-tips">
            <div class="tips-header">
              <div class="tips-icon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
              </div>
              <div>
                <div class="tips-label">Quick Tips</div>
                <div class="tips-sublabel">For a smooth submission</div>
              </div>
            </div>
            <div class="tips-list">
              <div class="tips-item"><span class="tips-num">1</span><span class="tips-text">Keep the title specific and searchable.</span></div>
              <div class="tips-item"><span class="tips-num">2</span><span class="tips-text"><strong>Recently Filed</strong> &amp; <strong>Unregistered</strong> will lock the Date Registered field.</span></div>
              <div class="tips-item"><span class="tips-num">3</span><span class="tips-text">Use a GDrive link with the correct share access.</span></div>
              <div class="tips-item"><span class="tips-num">4</span><span class="tips-text">Click completed steps to go back and edit.</span></div>
            </div>
          </div>

        </aside>

        {{-- Footer spans full width --}}
        <footer class="wizard-footer">
          <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
          <div>New Record · Wizard v3.0</div>
        </footer>

      </div>{{-- /wizard-wrap --}}
    </div>{{-- /content --}}
  </div>{{-- /main-wrap --}}

  {{-- LOGOUT MODAL --}}
  <div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
      <div class="logout-modal-inner">
        <div class="modal-icon">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </div>
        <div style="font-size:1.1rem;font-weight:800;font-family:'Plus Jakarta Sans',sans-serif;">Sign out of KTTM</div>
        <div class="modal-desc">This will end your current session and return you to the public portal.</div>
        <div class="modal-btns">
          <button type="button" class="btn-cancel" id="cancelLogout">Cancel</button>
          <form action="{{ $urlLogout }}" method="POST" id="logoutForm">
            @csrf
            <button type="submit" class="btn-confirm">Sign Out</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- DUPLICATE MODAL --}}
  <div class="modal-overlay" id="duplicateModal">
    <div class="modal-box">
      <div class="modal-head" style="background:linear-gradient(135deg,#92400e,#b45309);">
        <div>
          <div class="modal-eyebrow">Warning</div>
          <div class="modal-title">Possible Duplicate Found</div>
        </div>
        <button type="button" class="modal-close" id="closeDuplicateBtn">✕</button>
      </div>
      <div class="modal-body">
        We found existing records with similar titles. Are you sure this is a new record?
        <div class="modal-list" id="duplicateList"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-modal-cancel" id="viewExistingBtn">View Existing</button>
        <button type="button" class="btn-modal-confirm" id="createAnywayBtn">Create Anyway</button>
      </div>
    </div>
  </div>

  <script>
  (function(){

    /* ── Toast ── */
    function showToast(msg, type='success', dur=4000) {
      const t = document.createElement('div');
      t.className = 'toast ' + type;
      const icon = type === 'success'
        ? `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>`
        : `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`;
      t.innerHTML = icon + `<span>${msg}</span>`;
      document.body.appendChild(t);
      setTimeout(() => { t.classList.add('hiding'); setTimeout(() => t.remove(), 320); }, dur);
    }

    /* ── Modals + scroll lock (works with mobile sidebar) ── */
    function syncBodyScrollLock() {
      const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
      const anyModal = ['logoutModal', 'duplicateModal'].some(
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

    document.getElementById('logoutBtn')?.addEventListener('click', () => openModal('logoutModal'));
    document.getElementById('cancelLogout')?.addEventListener('click', () => closeModal('logoutModal'));
    document.getElementById('logoutModal')?.addEventListener('click', e => { if(e.target.id==='logoutModal') closeModal('logoutModal'); });
    document.getElementById('closeDuplicateBtn')?.addEventListener('click', () => closeModal('duplicateModal'));
    document.getElementById('duplicateModal')?.addEventListener('click', e => { if(e.target.id==='duplicateModal') closeModal('duplicateModal'); });
    document.addEventListener('keydown', e => {
      if (e.key !== 'Escape') return;
      if (document.getElementById('logoutModal')?.classList.contains('open')) closeModal('logoutModal');
      else if (document.getElementById('duplicateModal')?.classList.contains('open')) closeModal('duplicateModal');
    });

    document.getElementById('logoutForm')?.addEventListener('submit', function(e) {
      closeModal('logoutModal');
      setTimeout(() => window.location.href = '/', 200);
    });

    /* ── Wizard step navigation ── */
    let currentStep = 1;
    const totalSteps = 3;

    function showStep(n) {
      for(let i = 1; i <= totalSteps; i++) {
        const panel  = document.getElementById('panel' + i);
        const btn    = document.getElementById('stepBtn' + i);
        panel?.classList.toggle('active', i === n);
        if(!btn) continue;
        btn.classList.remove('active','done');
        if(i === n)       btn.classList.add('active');
        else if(i < n)    btn.classList.add('done');
      }
      currentStep = n;
      if(n === 3) updateReview();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* Allow clicking step pills to navigate back */
    document.querySelectorAll('.step-pill').forEach(btn => {
      btn.addEventListener('click', () => {
        const s = parseInt(btn.dataset.step);
        if(s < currentStep) showStep(s);
      });
    });

    document.getElementById('next1Btn')?.addEventListener('click', () => {
      const title  = document.getElementById('title');
      const type   = document.getElementById('type');
      const status = document.getElementById('status');
      if(!title?.value.trim()) { showToast('Please enter the IP Title.', 'error'); title?.focus(); return; }
      if(!type?.value)         { showToast('Please select a Category.', 'error');  type?.focus();  return; }
      if(!status?.value)       { showToast('Please select a Status.', 'error');    status?.focus(); return; }
      showStep(2);
    });

    document.getElementById('next2Btn')?.addEventListener('click', () => {
      const campus = document.getElementById('campus');
      if(!campus?.value) { showToast('Please select a Campus.', 'error'); campus?.focus(); return; }
      if(inventors.length === 0) {
        document.getElementById('inventorsError').textContent = 'Please add at least one inventor.';
        document.getElementById('inventorsError').style.display = 'block';
        showToast('Please add at least one inventor.', 'error');
        return;
      }
      document.getElementById('inventorsError').style.display = 'none';
      showStep(3);
    });

    document.getElementById('prev2Btn')?.addEventListener('click', () => showStep(1));
    document.getElementById('prev3Btn')?.addEventListener('click', () => showStep(2));

    /* ── Review summary ── */
    function updateReview() {
      const get = id => document.getElementById(id)?.value?.trim() || '';
      const setText = (id, val) => {
        const el = document.getElementById(id); if(!el) return;
        el.textContent = val || '—';
        el.className = 'rv-val' + (val ? '' : ' empty');
      };
      setText('rv-title',  get('title'));
      setText('rv-type',   get('type'));
      setText('rv-status', get('status'));
      setText('rv-campus', get('campus'));
      const college = get('college'); const program = get('program');
      setText('rv-college', [college, program].filter(Boolean).join(' / ') || '');
      const rvInv = document.getElementById('rv-inventors');
      if(rvInv) {
        if(inventors.length === 0) {
          rvInv.innerHTML = '<div class="rv-val empty">None added yet</div>';
        } else {
          rvInv.innerHTML = '<div class="review-inventors">' +
            inventors.map(inv => `<span class="ri-pill">${escapeHtml(inv.name || '—')}${inv.gender ? ' · ' + escapeHtml(inv.gender) : ''}</span>`).join('') +
            '</div>';
        }
      }
    }

    /* ── Inventors ── */
    let inventors = [];
    const inventorsList  = document.getElementById('inventorsList');
    const inventorsData  = document.getElementById('inventorsData');
    const inventorsError = document.getElementById('inventorsError');

    function escapeHtml(s) {
      return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function renderInventors() {
      inventorsList.innerHTML = '';
      if(inventors.length === 0) {
        inventorsList.innerHTML = '<div class="inventors-empty">No inventors added yet. Click below to begin.</div>';
        inventorsData.value = '[]';
        return;
      }
      inventors.forEach((inv, idx) => {
        const row = document.createElement('div');
        row.className = 'inventor-row';
        row.innerHTML = `
          <div class="inventor-idx">${idx + 1}</div>
          <div class="inventor-name-cell">
            <input type="text" placeholder="Full name" value="${escapeHtml(inv.name || '')}"
              class="field-input" style="border-radius:9px;"
              onchange="updateInventor(${idx},'name',this.value)" />
          </div>
          <div class="inventor-gender-cell">
            <select class="field-select" style="border-radius:9px;padding:9px 12px;"
              onchange="updateInventor(${idx},'gender',this.value)">
              <option value="" ${!inv.gender?'selected':''}>Gender</option>
              <option value="Male"   ${inv.gender==='Male'?'selected':''}>Male</option>
              <option value="Female" ${inv.gender==='Female'?'selected':''}>Female</option>
              <option value="Other"  ${inv.gender==='Other'?'selected':''}>Other</option>
            </select>
          </div>
          <button type="button" class="btn-remove" onclick="removeInventor(${idx})">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>`;
        inventorsList.appendChild(row);
      });
      inventorsData.value = JSON.stringify(inventors);
    }

    window.updateInventor = function(idx, field, val) {
      if(inventors[idx]) { inventors[idx][field] = val; inventorsData.value = JSON.stringify(inventors); }
    };
    window.removeInventor = function(idx) {
      inventors.splice(idx, 1); renderInventors(); updateAsidePreview();
      showToast('Inventor removed.', 'success', 1800);
    };

    document.getElementById('addInventorBtn')?.addEventListener('click', () => {
      inventors.push({ name: '', gender: '' });
      renderInventors(); updateAsidePreview();
      setTimeout(() => {
        const inputs = inventorsList.querySelectorAll('input[type="text"]');
        inputs[inputs.length - 1]?.focus();
      }, 50);
    });

    /* ── Date Registered lock ── */
    const statusSelect       = document.getElementById('status');
    const registeredInput    = document.getElementById('registered');
    const registeredHint     = document.getElementById('registeredHint');
    const regNumInput        = document.getElementById('registration_number');
    const regNumHint         = document.getElementById('registrationHint');

    const LOCKED_STATUSES = ['recently filed', 'unregistered'];

    function shouldLock(val) {
      return LOCKED_STATUSES.includes(String(val ?? '').trim().toLowerCase().replace(/\s+/g,' '));
    }
    function lockRegistered() {
      // Lock Date Registered
      if(registeredInput) {
        registeredInput.value = ''; registeredInput.disabled = true;
        registeredInput.classList.add('dateLocked');
      }
      if(registeredHint) registeredHint.style.display = 'inline';
      // Lock Registration Number
      if(regNumInput) {
        regNumInput.value = ''; regNumInput.disabled = true;
        regNumInput.classList.add('dateLocked');
      }
      if(regNumHint) regNumHint.style.display = 'inline';
    }
    function unlockRegistered() {
      // Unlock Date Registered
      if(registeredInput) {
        registeredInput.disabled = false;
        registeredInput.classList.remove('dateLocked');
      }
      if(registeredHint) registeredHint.style.display = 'none';
      // Unlock Registration Number
      if(regNumInput) {
        regNumInput.disabled = false;
        regNumInput.classList.remove('dateLocked');
      }
      if(regNumHint) regNumHint.style.display = 'none';
    }
    statusSelect?.addEventListener('change', () => shouldLock(statusSelect.value) ? lockRegistered() : unlockRegistered());
    shouldLock(statusSelect?.value) ? lockRegistered() : unlockRegistered();

    /* ── Reset ── */
    document.getElementById('resetBtn')?.addEventListener('click', () => {
      document.getElementById('createRecordForm')?.reset();
      inventors = []; renderInventors();
      shouldLock(statusSelect?.value) ? lockRegistered() : unlockRegistered();
      showStep(1);
      showToast('Form cleared.', 'success', 1800);
    });

    /* ── Demo fill ── */
    document.getElementById('fillDemoBtn')?.addEventListener('click', () => {
      const set = (id, v) => { const el = document.getElementById(id); if(el) el.value = v; };
      set('title',               'Sample IP Record Title');
      set('type',                '{{ $types[0] ?? "Patent" }}');
      set('status',              'Recently Filed');
      set('class_of_work',       'Literary Work');
      set('campus',              '{{ $campuses[0] ?? "Alangilan" }}');
      set('registration_number', 'REG-2026-000001');
      set('gdrive_link',         'https://drive.google.com/');
      set('remarks',             'Sample remarks for the new record.');
      inventors = [{ name: 'Juan Dela Cruz', gender: 'Male' }, { name: 'Maria Santos', gender: 'Female' }];
      renderInventors();
      shouldLock(statusSelect?.value) ? lockRegistered() : unlockRegistered();
      showToast('Demo data filled.', 'success', 1800);
    });

    /* ── Duplicate modal ── */
    let bypassDuplicate = false;

    function showDuplicateModal(matches) {
      const list = document.getElementById('duplicateList'); if(!list) return;
      list.innerHTML = '';
      matches.forEach(m => {
        const el = document.createElement('div');
        el.className = 'modal-list-item';
        el.textContent = `${m.record_id} — ${m.ip_title}`;
        list.appendChild(el);
      });
      openModal('duplicateModal');
    }

    document.getElementById('createAnywayBtn')?.addEventListener('click', () => {
      bypassDuplicate = true; closeModal('duplicateModal');
      document.getElementById('createRecordForm')?.submit();
    });
    document.getElementById('viewExistingBtn')?.addEventListener('click', () => {
      const titleVal = document.getElementById('title')?.value.trim() ?? '';
      window.location.href = '{{ $urlRecords }}' + (titleVal ? '?q=' + encodeURIComponent(titleVal) : '');
    });

    /* ── Form submit ── */
    document.getElementById('createRecordForm')?.addEventListener('submit', async (e) => {
      e.preventDefault();
      if(inventors.length === 0) {
        inventorsError.textContent = 'Please add at least one inventor.';
        inventorsError.style.display = 'block';
        showToast('Please add at least one inventor.', 'error');
        showStep(2); return;
      }
      inventorsError.style.display = 'none';
      if(shouldLock(statusSelect?.value)) { registeredInput.value = ''; if(regNumInput) regNumInput.value = ''; }
      if(!e.target.checkValidity()) { showToast('Please complete all required fields.', 'error'); return; }

      if(!bypassDuplicate) {
        const titleVal = document.getElementById('title')?.value.trim() ?? '';
        if(titleVal) {
          try {
            const resp = await fetch("{{ url('/ipassets/check-title') }}?title=" + encodeURIComponent(titleVal));
            if(resp.ok) {
              const items = await resp.json();
              if(Array.isArray(items) && items.length > 0) { showDuplicateModal(items); return; }
            }
          } catch(err) { /* proceed */ }
        }
      }
      e.target.submit();
    });

    /* ── Live aside preview ── */
    function updateAsidePreview() {
      const rows = document.getElementById('asidePreviewRows');
      if(!rows) return;
      const fields = [
        { label: 'Title',    val: document.getElementById('title')?.value.trim() },
        { label: 'Category', val: document.getElementById('type')?.value },
        { label: 'Status',   val: document.getElementById('status')?.value },
        { label: 'Campus',   val: document.getElementById('campus')?.value },
        { label: 'College',  val: document.getElementById('college')?.value },
      ];
      const filled = fields.filter(f => f.val);
      if(!filled.length && inventors.length === 0) {
        rows.innerHTML = '<div class="apr-empty">Fill in fields to see a preview here.</div>';
        return;
      }
      let html = filled.map(f =>
        `<div class="apr-row"><div class="apr-label">${f.label}</div><div class="apr-val">${escapeHtml(f.val)}</div></div>`
      ).join('');
      if(inventors.length) {
        html += `<div class="apr-row"><div class="apr-label">Inventors</div><div class="apr-val">${inventors.map(i=>escapeHtml(i.name||'—')).join(', ')}</div></div>`;
      }
      rows.innerHTML = html;
    }

    ['title','type','status','campus','college'].forEach(id => {
      document.getElementById(id)?.addEventListener('input',  updateAsidePreview);
      document.getElementById(id)?.addEventListener('change', updateAsidePreview);
    });

    /* Init */
    renderInventors();
    updateAsidePreview();

    /* Mobile sidebar (same pattern as home / records) */
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

  })();
  </script>

</body>
</html>