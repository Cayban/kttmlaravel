<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>KTTM — Profile</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon:       #A52C30;
      --maroon2:      #7E1F23;
      --maroon3:      #C1363A;
      --maroon-light: rgba(165,44,48,0.12);
      --maroon-mid:   rgba(165,44,48,0.22);
      --gold:         #F0C860;
      --gold2:        #E8B857;
      --gold3:        #D4A030;
      --ink:          #0F172A;
      --muted:        #64748B;
      --line:         rgba(15,23,42,.08);
      --card:         #FFFFFF;
      --sidebar-w:    72px;
      --bg:           #F1F4F9;
      --pad-x:        clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max:    1440px;
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

    /* ══════════════════════════════════════
       SIDEBAR  (identical to home)
    ══════════════════════════════════════ */
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0;
      width: var(--sidebar-w);
      background: linear-gradient(180deg, var(--maroon2) 0%, var(--maroon) 100%);
      display: flex; flex-direction: column; align-items: center;
      padding: 20px 0; z-index: 50;
      box-shadow: 4px 0 24px rgba(165,44,48,.18);
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
      letter-spacing: .04em; z-index: 999;
    }
    .nav-item:hover .nav-tooltip { opacity: 1; }
    .sidebar-nav {
      display: flex; flex-direction: column; align-items: center;
      gap: 6px; flex: 1; width: 100%;
    }
    .sidebar-bottom {
      display: flex; flex-direction: column; align-items: center; gap: 6px;
    }

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

    /* ══════════════════════════════════════
       MAIN LAYOUT
    ══════════════════════════════════════ */
    .main-wrap {
      margin-left: var(--sidebar-w);
      min-height: 100vh;
      display: flex; flex-direction: column;
    }

    /* ══════════════════════════════════════
       TOPBAR
    ══════════════════════════════════════ */
    .topbar {
      min-height: 72px;
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
    .page-title  {
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
      display: flex; align-items: center; justify-content: flex-end;
      flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
    }
    .btn-back {
      display: inline-flex; align-items: center; justify-content: center;
      gap: 7px;
      background: var(--bg); border: 1.5px solid var(--line);
      color: var(--muted); cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 16px);
      border-radius: 12px; transition: all .18s;
      text-decoration: none;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-back:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }

    /* ══════════════════════════════════════
       CONTENT
    ══════════════════════════════════════ */
    .content {
      padding: clamp(14px, 2.5vw, 24px) var(--pad-x);
      flex: 1;
      width: 100%;
      max-width: var(--shell-max);
      margin: 0 auto;
      box-sizing: border-box;
    }

    /* ══════════════════════════════════════
       HERO BANNER
    ══════════════════════════════════════ */
    .profile-hero {
      background: linear-gradient(135deg, #7E1F23 0%, #B22D32 48%, #8E2328 100%);
      border-radius: 24px;
      padding: clamp(20px, 4vw, 34px) clamp(18px, 3.5vw, 36px);
      position: relative; overflow: hidden;
      display: flex; flex-wrap: wrap;
      align-items: center; gap: clamp(16px, 3vw, 28px);
      margin-bottom: 20px;
      box-shadow: 0 14px 44px rgba(165,44,48,.28);
      border: 1px solid rgba(255,255,255,.08);
    }
    /* decorative circles */
    .profile-hero::before {
      content: ''; position: absolute;
      top: -70px; right: -70px;
      width: 250px; height: 250px; border-radius: 50%;
      background: rgba(255,255,255,.06); pointer-events: none;
    }
    .profile-hero::after {
      content: ''; position: absolute;
      bottom: -50px; right: 90px;
      width: 180px; height: 180px; border-radius: 50%;
      background: rgba(240,200,96,.08); pointer-events: none;
    }
    /* grid overlay */
    .hero-grid {
      position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
      background-size: 40px 40px;
      mask-image: radial-gradient(ellipse 100% 100% at 50% 50%, black 30%, transparent 80%);
    }
    .hero-panel {
      position: relative; z-index: 1;
      width: 100%;
      display: flex; flex-wrap: wrap; align-items: center;
      gap: clamp(16px, 3vw, 28px);
      padding: clamp(8px, 1vw, 10px);
      border-radius: 18px;
      background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
      border: 1px solid rgba(255,255,255,.07);
      box-shadow: inset 0 1px 0 rgba(255,255,255,.06);
    }

    .hero-avatar {
      width: 92px; height: 92px; border-radius: 24px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 1.85rem; color: #2a1a0b;
      flex-shrink: 0; position: relative; z-index: 2;
      box-shadow: 0 14px 36px rgba(0,0,0,.26);
      border: 3px solid rgba(255,255,255,.16);
    }
    .hero-info { flex: 1 1 12rem; min-width: 0; position: relative; z-index: 2; }
    .hero-eyebrow {
      font-family: 'DM Mono', monospace;
      font-size: 0.58rem; letter-spacing: .22em; text-transform: uppercase;
      color: var(--gold); opacity: .82; margin-bottom: 8px;
    }
    .hero-name {
      font-size: clamp(1.2rem, 3vw + 0.5rem, 1.8rem);
      font-weight: 800; color: #fff;
      letter-spacing: -.5px; line-height: 1.08; margin-bottom: 12px;
      overflow-wrap: anywhere;
      text-shadow: 0 1px 0 rgba(0,0,0,.08);
    }
    .hero-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .hero-role-badge {
      display: inline-flex; align-items: center; gap: 5px;
      font-family: 'DM Mono', monospace;
      font-size: 0.6rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
      padding: 5px 12px; border-radius: 999px;
      backdrop-filter: blur(6px);
    }
    .badge-admin     { background: rgba(240,200,96,.18); color: var(--gold); border: 1px solid rgba(240,200,96,.3); }
    .badge-developer { background: rgba(240,200,96,.18); color: var(--gold); border: 1px solid rgba(240,200,96,.3); }
    .badge-staff     { background: rgba(255,255,255,.15); color: rgba(255,255,255,.9); border: 1px solid rgba(255,255,255,.25); }
    .badge-guest     { background: rgba(99,102,241,.18); color: #a5b4fc; border: 1px solid rgba(99,102,241,.3); }
    .hero-since {
      display: inline-flex; align-items: center;
      font-size: 0.74rem; color: rgba(255,255,255,.84);
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 999px;
      background: rgba(255,255,255,.08);
      border: 1px solid rgba(255,255,255,.1);
      backdrop-filter: blur(6px);
      min-width: 0;
      max-width: 100%;
    }
    .hero-meta {
      position: relative; z-index: 2;
      display: flex; flex-direction: column;
      align-items: flex-end; gap: 10px;
      flex: 0 1 auto;
      min-width: 0;
      margin-left: auto;
    }
    .hero-stat-pill {
      min-width: 132px;
      background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.1);
      border-radius: 16px; padding: 12px 16px; text-align: right;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.05);
    }
    .hero-stat-num   { font-size: 1.45rem; font-weight: 800; color: var(--gold); line-height: 1; }
    .hero-stat-label {
      font-family: 'DM Mono', monospace;
      font-size: 0.52rem; letter-spacing: .14em; text-transform: uppercase;
      color: rgba(255,255,255,.46); margin-top: 4px;
    }

    /* ══════════════════════════════════════
       MAIN GRID
    ══════════════════════════════════════ */
    .profile-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
    }

    /* ══════════════════════════════════════
       SECTION CARD
    ══════════════════════════════════════ */
    .section-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 1px 6px rgba(15,23,42,.05);
      transition: box-shadow .2s;
    }
    .section-card:hover { box-shadow: 0 6px 24px rgba(15,23,42,.09); }
    .section-head {
      display: flex; flex-wrap: wrap;
      align-items: center; gap: 12px;
      padding: 18px 22px 14px;
      border-bottom: 1px solid var(--line);
    }
    .section-icon {
      width: 36px; height: 36px; border-radius: 11px;
      background: var(--maroon-light);
      display: flex; align-items: center; justify-content: center;
      color: var(--maroon); flex-shrink: 0;
    }
    .section-title {
      font-size: clamp(0.82rem, 0.2vw + 0.76rem, 0.88rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .section-sub   { font-size: 0.67rem; color: var(--muted); margin-top: 1px; font-weight: 500; }
    .section-body  { padding: 20px 22px; display: flex; flex-direction: column; gap: 14px; }

    /* ══════════════════════════════════════
       INFO ROWS
    ══════════════════════════════════════ */
    .info-row {
      display: flex; flex-direction: column; gap: 3px;
    }
    .info-label {
      font-family: 'DM Mono', monospace;
      font-size: 0.58rem; letter-spacing: .14em; text-transform: uppercase;
      color: var(--muted); font-weight: 600;
    }
    .info-value {
      font-size: 0.88rem; font-weight: 600; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .info-value.mono {
      font-family: 'DM Mono', monospace; font-size: 0.82rem;
    }
    .info-divider {
      height: 1px; background: var(--line); margin: 2px 0;
    }

    /* role pill inline */
    .role-pill {
      display: inline-flex; align-items: center; gap: 5px;
      font-family: 'DM Mono', monospace;
      font-size: 0.6rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
      padding: 3px 10px; border-radius: 20px;
    }
    .role-pill.admin     { background: var(--maroon-light); color: var(--maroon); border: 1px solid rgba(165,44,48,.2); }
    .role-pill.developer { background: rgba(245,158,11,.1);  color: #b45309;      border: 1px solid rgba(245,158,11,.2); }
    .role-pill.staff     { background: rgba(15,23,42,.06);   color: var(--ink);   border: 1px solid rgba(15,23,42,.12); }
    .role-pill.guest     { background: rgba(99,102,241,.08); color: #4f46e5;      border: 1px solid rgba(99,102,241,.2); }
    .role-pill.custom    { background: rgba(20,184,166,.08); color: #0d9488;      border: 1px solid rgba(20,184,166,.2); }
    .custom-role-wrap { display: none; flex-direction: column; gap: 4px; margin-top: 4px; }
    .custom-role-wrap.open { display: flex; }
    .custom-role-hint { font-size: 0.68rem; color: var(--muted); font-family: 'DM Mono', monospace; }
    .role-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

    /* ══════════════════════════════════════
       PASSWORD FIELDS
    ══════════════════════════════════════ */
    .field { display: flex; flex-direction: column; gap: 5px; }
    .field label {
      font-family: 'DM Mono', monospace;
      font-size: 0.58rem; letter-spacing: .14em; text-transform: uppercase;
      color: var(--muted); font-weight: 600;
    }
    .pw-wrap { position: relative; }
    .field input[type="password"],
    .field input[type="text"] {
      width: 100%; padding: 0.78rem 2.8rem 0.78rem 1rem;
      border: 1.5px solid var(--line);
      background: var(--bg);
      font-family: inherit; font-size: 0.85rem; color: var(--ink);
      border-radius: 12px; outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .field input:focus {
      border-color: var(--maroon);
      box-shadow: 0 0 0 3px var(--maroon-light);
      background: #fff;
    }
    .field input::placeholder { color: #b0b8c8; }
    .pw-eye {
      position: absolute; right: .75rem; top: 50%;
      transform: translateY(-50%);
      background: none; border: none; cursor: pointer;
      color: var(--muted); display: flex; align-items: center; padding: 0;
      transition: color .15s;
    }
    .pw-eye:hover { color: var(--maroon); }

    /* save button */
    .btn-save {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      color: #fff; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: 11px clamp(14px, 2.5vw, 22px);
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(165,44,48,.26);
      transition: transform .18s, box-shadow .18s, opacity .18s;
      align-self: flex-end;
      margin-top: 4px;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(165,44,48,.32); }
    .btn-save:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* pw error / success */
    .pw-alert {
      display: none; align-items: center; gap: 8px;
      border-radius: 10px; padding: 10px 14px;
      font-size: 0.78rem; font-weight: 600;
    }
    .pw-alert.error   { background: rgba(165,44,48,.08); border: 1px solid rgba(165,44,48,.2); color: var(--maroon); }
    .pw-alert.success { background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2); color: #059669; }
    .pw-alert.show { display: flex; }

    /* ══════════════════════════════════════
       NAME EDIT INLINE
    ══════════════════════════════════════ */
    .name-display-wrap {
      display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }
    .name-edit-btn {
      display: inline-flex; align-items: center; gap: 5px;
      background: none; border: 1.5px solid var(--line);
      color: var(--muted); border-radius: 9px;
      padding: 4px 11px; font-family: inherit;
      font-size: 0.7rem; font-weight: 700; cursor: pointer;
      transition: all .18s; white-space: nowrap; flex-shrink: 0;
    }
    .name-edit-btn:hover { border-color: var(--maroon); color: var(--maroon); background: var(--maroon-light); }
    .name-edit-wrap {
      display: none; flex-direction: column; gap: 8px; margin-top: 4px;
    }
    .name-edit-wrap.open { display: flex; }
    .role-display-hidden { display: none !important; }
    .name-edit-input {
      width: 100%; padding: 0.72rem 1rem;
      border: 1.5px solid var(--line); background: var(--bg);
      font-family: inherit; font-size: 0.85rem; color: var(--ink);
      border-radius: 12px; outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .name-edit-input:focus {
      border-color: var(--maroon);
      box-shadow: 0 0 0 3px var(--maroon-light);
      background: #fff;
    }
    .name-edit-actions {
      display: flex; flex-wrap: wrap;
      gap: 8px;
    }
    .name-save-btn {
      flex: 1 1 8rem;
      min-width: 0;
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      color: #fff; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: 9px clamp(12px, 2vw, 18px);
      border-radius: 11px;
      box-shadow: 0 4px 14px rgba(165,44,48,.24);
      transition: transform .18s, box-shadow .18s, opacity .18s;
    }
    .name-save-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(165,44,48,.3); }
    .name-save-btn:disabled { opacity: .6; cursor: not-allowed; transform: none; }
    .name-cancel-btn {
      flex: 1 1 6rem;
      min-width: 0;
      display: inline-flex; align-items: center; justify-content: center;
      background: var(--bg); border: 1.5px solid var(--line);
      color: var(--muted); cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 16px);
      border-radius: 11px; transition: all .18s;
      box-sizing: border-box;
      white-space: nowrap;
    }
    .name-cancel-btn:hover { border-color: var(--maroon); color: var(--maroon); background: var(--maroon-light); }

    /* ══════════════════════════════════════
       PASSWORD STRENGTH METER
    ══════════════════════════════════════ */
    .strength-wrap {
      display: flex; flex-direction: column; gap: 5px;
      margin-top: 2px;
    }
    .strength-bar-track {
      height: 5px; border-radius: 99px;
      background: var(--line);
      overflow: hidden;
    }
    .strength-bar-fill {
      height: 100%; border-radius: 99px; width: 0%;
      transition: width .35s ease, background .35s ease;
    }
    .strength-label-row {
      display: flex; align-items: center; justify-content: space-between;
    }
    .strength-hint {
      font-size: 0.68rem; color: var(--muted); font-weight: 500; line-height: 1.5;
    }
    .strength-badge {
      font-family: 'DM Mono', monospace;
      font-size: 0.6rem; font-weight: 700;
      letter-spacing: .1em; text-transform: uppercase;
      padding: 2px 9px; border-radius: 20px;
      transition: background .3s, color .3s;
    }
    .strength-0 .strength-bar-fill  { width: 0%;   background: transparent; }
    .strength-1 .strength-bar-fill  { width: 25%;  background: #ef4444; }
    .strength-1 .strength-badge     { background: rgba(239,68,68,.1);   color: #dc2626; border: 1px solid rgba(239,68,68,.2); }
    .strength-2 .strength-bar-fill  { width: 50%;  background: #f97316; }
    .strength-2 .strength-badge     { background: rgba(249,115,22,.1);  color: #ea580c; border: 1px solid rgba(249,115,22,.2); }
    .strength-3 .strength-bar-fill  { width: 75%;  background: #eab308; }
    .strength-3 .strength-badge     { background: rgba(234,179,8,.1);   color: #ca8a04; border: 1px solid rgba(234,179,8,.2); }
    .strength-4 .strength-bar-fill  { width: 100%; background: #10b981; }
    .strength-4 .strength-badge     { background: rgba(16,185,129,.1);  color: #059669; border: 1px solid rgba(16,185,129,.2); }

    /* ══════════════════════════════════════
       SESSION CARD
    ══════════════════════════════════════ */
    .session-row {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 0; border-bottom: 1px solid var(--line);
    }
    .session-row:last-child { border-bottom: none; }
    .session-icon {
      width: 32px; height: 32px; border-radius: 9px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .session-label { font-size: 0.7rem; font-weight: 600; color: var(--muted); }
    .session-value { font-size: 0.82rem; font-weight: 700; color: var(--ink); margin-top: 1px; }
    .session-value.mono { font-family: 'DM Mono', monospace; font-size: 0.76rem; }

    /* active badge */
    .active-badge {
      display: inline-flex; align-items: center; gap: 5px;
      background: rgba(16,185,129,.14); color: #d1fae5;
      border: 1px solid rgba(16,185,129,.26);
      font-family: 'DM Mono', monospace; font-size: 0.58rem;
      font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
      padding: 5px 10px; border-radius: 999px;
      box-shadow: inset 0 1px 0 rgba(255,255,255,.04);
    }
    .active-dot {
      width: 6px; height: 6px; border-radius: 50%; background: #34d399;
      animation: pulse 2s ease-in-out infinite;
      box-shadow: 0 0 0 4px rgba(52,211,153,.12);
    }
    @keyframes pulse {
      0%,100% { opacity: 1; } 50% { opacity: .4; }
    }

    /* ══════════════════════════════════════
       LOGOUT MODAL (reused from home)
    ══════════════════════════════════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: #fff; border-radius: 24px;
      padding: clamp(22px, 4vw, 32px);
      width: min(380px, calc(100vw - 2rem));
      max-width: 100%;
      box-sizing: border-box;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      animation: fadeSlideUp .3s forwards;
    }
    @keyframes fadeSlideUp {
      from { opacity:0; transform:translateY(16px) scale(.98); }
      to   { opacity:1; transform:none; }
    }
    .modal-icon {
      width: 52px; height: 52px; border-radius: 16px; margin-bottom: 16px;
      background: rgba(165,44,48,.1); display: flex; align-items: center; justify-content: center;
    }
    .modal-title {
      font-size: clamp(1.05rem, 0.3vw + 0.95rem, 1.2rem);
      font-weight: 800; color: var(--ink); margin-bottom: 6px;
      overflow-wrap: anywhere;
    }
    .modal-desc  {
      font-size: clamp(0.76rem, 0.15vw + 0.72rem, 0.82rem);
      color: var(--muted); line-height: 1.6; margin-bottom: 22px;
      overflow-wrap: anywhere;
    }
    .modal-actions {
      display: flex; flex-wrap: wrap;
      gap: 10px; align-items: stretch;
    }
    .modal-actions form {
      flex: 1 1 140px;
      min-width: 0;
      display: flex;
    }
    .btn-cancel {
      flex: 1 1 120px;
      min-width: 0;
      padding: 11px; border-radius: 12px;
      background: var(--bg); border: 1.5px solid var(--line);
      font-family: inherit;
      font-size: clamp(0.76rem, 0.12vw + 0.73rem, 0.82rem);
      font-weight: 700;
      color: var(--muted); cursor: pointer; transition: all .18s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-sizing: border-box;
    }
    .btn-cancel:hover { border-color: var(--maroon); color: var(--maroon); background: var(--maroon-light); }
    .btn-confirm-logout {
      display: inline-flex;
      align-items: center;
      width: 100%;
      padding: 11px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      border: none; font-family: inherit;
      font-size: clamp(0.76rem, 0.12vw + 0.73rem, 0.82rem);
      font-weight: 700;
      color: #fff; cursor: pointer;
      box-shadow: 0 6px 16px rgba(165,44,48,.26);
      transition: transform .18s, box-shadow .18s;
      box-sizing: border-box;
      justify-content: center;
    }
    .btn-confirm-logout:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(165,44,48,.34); }

    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner { width:14px;height:14px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite; }

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
      .page-subtitle { display: none; }
      .profile-hero { border-radius: 20px; }
      .hero-panel {
        padding: 0;
        background: none;
        border: none;
        box-shadow: none;
      }
      .hero-meta { align-items: flex-start; width: 100%; margin-left: 0; }
    }
    @media (max-width: 640px) {
      .profile-grid { grid-template-columns: minmax(0, 1fr); }
      .btn-back-label { display: none; }
      .name-display-wrap { flex-wrap: wrap; }
    }
    @media (max-width: 480px) {
      .modal-actions { flex-direction: column; }
      .modal-actions .btn-cancel,
      .modal-actions form { flex: 0 0 auto; width: 100%; }
    }
  </style>
</head>

<body>
@php
  // Pull from session
  $userName     = session('user_name',         'KTTM User');
  $userBaseRole = session('user_base_role',    session('user_role', 'staff'));
  $userCustomRole = session('user_custom_role', null);
  $userRole     = session('user_role',         'staff');
  $userColor    = session('user_avatar_color', '#A52C30');
  $accountEmail = session('account_email',     '—');

  // Compute initials & labels
  $userInitials = collect(explode(' ', $userName))
    ->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
  $baseRoleLabel = match(strtolower($userBaseRole)) {
    'admin'     => 'Admin',
    'developer' => 'Developer',
    default     => 'Staff',
  };
  $customRoleLabel = $userCustomRole ? ucfirst($userCustomRole) : null;
  // Hero badge shows custom role if set, otherwise base role
  $roleLabel  = $customRoleLabel ?? $baseRoleLabel;
  $knownRoles = ['admin','developer','staff'];
  $roleSlug   = in_array(strtolower($userBaseRole), $knownRoles) ? strtolower($userBaseRole) : 'staff';
  $badgeClass = 'badge-' . $roleSlug;
  $pillClass  = $roleSlug;

  $urlHome     = url('/home');
  $urlRecords  = url('/records');
  $urlInsights = url('/insights');
  $urlCalendar = url('/calendar');
@endphp

{{-- ══════════════ SIDEBAR ══════════════ --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
<aside class="sidebar" id="mainSidebar" aria-label="Main navigation">

  {{-- User avatar --}}
  @php $sidebarAvatar = session('user_avatar_image'); @endphp
  <div class="nav-item" style="margin-bottom:20px; width:42px; height:42px; border-radius:14px; {{ $sidebarAvatar ? 'background:transparent;' : 'background: linear-gradient(135deg, var(--gold), var(--gold2));' }} font-weight:800; font-size:0.78rem; color:#2a1a0b; box-shadow:0 6px 18px rgba(240,200,96,.35); cursor:default; flex-shrink:0; overflow:hidden; padding:0;">
    @if($sidebarAvatar)
      <img src="{{ asset('storage/avatars/' . $sidebarAvatar) }}"
           alt="{{ $userInitials }}"
           style="width:42px;height:42px;object-fit:cover;border-radius:14px;display:block;">
    @else
      {{ $userInitials }}
    @endif
    <span class="nav-tooltip" style="min-width:140px;line-height:1.5;">
      {{ $userName }}<br>
      <span style="opacity:.65;font-weight:500;letter-spacing:.06em;text-transform:uppercase;font-size:.6rem;">{{ $roleLabel }}</span>
    </span>
  </div>

  <nav class="sidebar-nav">
    <a href="{{ $urlHome }}" class="nav-item">
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
    <a href="{{ $urlCalendar }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="3" y="4" width="18" height="16" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
      <span class="nav-tooltip">Calendar</span>
    </a>
  </nav>

  <div class="sidebar-bottom">
    {{-- Profile — active on this page --}}
    <button type="button" class="nav-item active" style="background:none;border:none;cursor:default;">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
      </svg>
      <span class="nav-tooltip">Profile</span>
    </button>
    {{-- Logout --}}
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
      <div class="topbar-titles">
        <div class="page-title">My Profile</div>
        <div class="page-subtitle">Manage your account details and security</div>
      </div>
    </div>
    <div class="topbar-right">
      <a href="{{ $urlHome }}" class="btn-back" title="Back to Dashboard">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
        <span class="btn-back-label">Back to Dashboard</span>
      </a>
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

    {{-- ── HERO BANNER ── --}}
    <div class="profile-hero">
      <div class="hero-grid"></div>

      <div class="hero-panel">
        {{-- Avatar --}}
        @php $heroAvatar = session('user_avatar_image'); @endphp
        <div class="hero-avatar" style="{{ $heroAvatar ? 'background:transparent;padding:0;overflow:hidden;' : 'background: linear-gradient(135deg, var(--gold), var(--gold2));' }}">
          @if($heroAvatar)
            <img src="{{ asset('storage/avatars/' . $heroAvatar) }}"
                 alt="{{ $userInitials }}"
                 style="width:92px;height:92px;object-fit:cover;border-radius:24px;display:block;">
          @else
            {{ $userInitials }}
          @endif
        </div>

        {{-- Info --}}
        <div class="hero-info">
          <div class="hero-eyebrow">Intellectual Property Records System</div>
          <div class="hero-name">{{ $userName }}</div>
          <div class="hero-badges">
            <span class="hero-role-badge {{ $badgeClass }}">
              <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
              {{ $baseRoleLabel }}
            </span>
            @if($customRoleLabel)
            <span class="hero-role-badge" style="background:rgba(20,184,166,.18);color:#0d9488;border:1px solid rgba(20,184,166,.3);">
              <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
              {{ $customRoleLabel }}
            </span>
            @endif
            <span class="hero-since">{{ $accountEmail }}</span>
          </div>
        </div>

        {{-- Meta stat --}}
        <div class="hero-meta">
          <div class="hero-stat-pill">
            <div class="hero-stat-num">{{ now()->format('Y') }}</div>
            <div class="hero-stat-label">Active Since</div>
          </div>
          <div class="active-badge">
            <span class="active-dot"></span>
            Session Active
          </div>
        </div>
      </div>
    </div>

    {{-- ── PROFILE GRID ── --}}
    <div class="profile-grid">

      {{-- ── CARD 1: Account Information ── --}}
      <div class="section-card">
        <div class="section-head">
          <div class="section-icon">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <div>
            <div class="section-title">Account Information</div>
            <div class="section-sub">Your profile details on this system</div>
          </div>
        </div>
        <div class="section-body">
          {{-- Name alert --}}
          <div id="nameAlert" class="pw-alert error" style="margin-bottom:8px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="nameAlertText"></span>
          </div>

          <div class="info-row">
            <div class="info-label">Full Name</div>
            <div class="info-value" style="flex:1;">
              {{-- Display mode --}}
              <div class="name-display-wrap">
                <span id="nameDisplay">{{ $userName }}</span>
                <button type="button" class="name-edit-btn" onclick="openNameEdit()">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                  Edit
                </button>
              </div>
              {{-- Edit mode --}}
              <div class="name-edit-wrap" id="nameEditWrap">
                <input type="text" id="nameInput" class="name-edit-input"
                       placeholder="Enter your full name"
                       maxlength="60" autocomplete="off">
                <div class="name-edit-actions">
                  <button type="button" class="name-cancel-btn" onclick="closeNameEdit()">Cancel</button>
                  <button type="button" class="name-save-btn" id="nameSaveBtn" onclick="submitNameChange()">
                    <span id="nameSaveText">Save Name</span>
                    <span id="nameSaveSpinner" style="display:none;"><div class="spinner"></div></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="info-divider"></div>
          {{-- Base Role row (Admin / Staff) --}}
          <div class="info-row">
            <div class="info-label">Base Role</div>
            <div class="info-value" style="flex:1;">
              <div class="name-display-wrap" id="baseRoleDisplayWrap">
                <span class="role-pill {{ $roleSlug }}" id="baseRolePillDisplay">
                  <span class="role-dot"></span>
                  <span id="baseRoleDisplay">{{ $baseRoleLabel }}</span>
                </span>
                @if(strtolower($userBaseRole) !== 'developer')
                <button type="button" class="name-edit-btn" onclick="openBaseRoleEdit()">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                  Edit
                </button>
                @endif
              </div>
              <div class="name-edit-wrap" id="baseRoleEditWrap">
                <select id="baseRoleSelect" class="name-edit-input" style="cursor:pointer;">
                  <option value="admin" {{ strtolower($userBaseRole)==='admin' ? 'selected' : '' }}>Admin</option>
                  <option value="staff" {{ strtolower($userBaseRole)==='staff' ? 'selected' : '' }}>Staff</option>
                </select>
                <div class="name-edit-actions">
                  <button type="button" class="name-cancel-btn" onclick="closeBaseRoleEdit()">Cancel</button>
                  <button type="button" class="name-save-btn" id="baseRoleSaveBtn" onclick="submitBaseRoleChange()">
                    <span id="baseRoleSaveText">Save</span>
                    <span id="baseRoleSaveSpinner" style="display:none;"><div class="spinner"></div></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="info-divider"></div>
          {{-- Custom Role row --}}
          <div class="info-row">
            <div class="info-label">Custom Role</div>
            <div class="info-value" style="flex:1;">
              {{-- Role alert --}}
              <div id="roleAlert" class="pw-alert error" style="margin-bottom:8px;display:none;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span id="roleAlertText"></span>
              </div>
              <div class="name-display-wrap" id="customRoleDisplayWrap">
                <span class="role-pill custom" id="customRolePillDisplay" style="{{ $customRoleLabel ? '' : 'display:none;' }}">
                  <span class="role-dot"></span>
                  <span id="customRoleDisplay">{{ $customRoleLabel ?? '' }}</span>
                </span>
                <span id="customRoleEmpty" style="font-size:0.78rem;color:var(--muted);{{ $customRoleLabel ? 'display:none;' : '' }}">None set</span>
                <button type="button" class="name-edit-btn" onclick="openCustomRoleEdit()">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                  </svg>
                  Edit
                </button>
              </div>
              <div class="name-edit-wrap" id="customRoleEditWrap">
                <input type="text" id="customRoleInput" class="name-edit-input"
                       placeholder="e.g. Assistant Director"
                       maxlength="50" autocomplete="off"
                       value="{{ $customRoleLabel ?? '' }}">
                <span class="custom-role-hint">Leave blank to remove. Letters, numbers and spaces only.</span>
                <div class="name-edit-actions">
                  <button type="button" class="name-cancel-btn" onclick="closeCustomRoleEdit()">Cancel</button>
                  <button type="button" class="name-save-btn" id="customRoleSaveBtn" onclick="submitCustomRoleChange()">
                    <span id="customRoleSaveText">Save</span>
                    <span id="customRoleSaveSpinner" style="display:none;"><div class="spinner"></div></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="info-divider"></div>
          <div class="info-row">
            <div class="info-label">Account Email</div>
            <div class="info-value mono">{{ $accountEmail }}</div>
          </div>
          <div class="info-divider"></div>
          <div class="info-row">
            <div class="info-label">Account ID</div>
            <div class="info-value mono">#{{ session('account_id', '—') }}</div>
          </div>
        </div>
      </div>

      {{-- ── CARD 2: Change Password ── --}}
      <div class="section-card">
        <div class="section-head">
          <div class="section-icon">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
          </div>
          <div>
            <div class="section-title">Change Password</div>
            <div class="section-sub">Update your profile password</div>
          </div>
        </div>
        <div class="section-body">

          {{-- Alert --}}
          <div id="pwAlert" class="pw-alert error">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="pwAlertText"></span>
          </div>

          {{-- Current Password (required to verify identity — eye toggle kept) --}}
          <div class="field">
            <label>Current Password</label>
            <div class="pw-wrap">
              <input type="password" id="pwCurrent" placeholder="••••••••••" autocomplete="current-password">
              <button type="button" class="pw-eye" onclick="togglePw('pwCurrent', this)">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          {{-- New Password + Strength Meter --}}
          <div class="field">
            <label>New Password</label>
            <div class="pw-wrap">
              <input type="password" id="pwNew" placeholder="••••••••••"
                     autocomplete="new-password" oninput="updateStrength(this.value)">
              <button type="button" class="pw-eye" onclick="togglePw('pwNew', this)">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>

            {{-- Strength Meter --}}
            <div class="strength-wrap strength-0" id="strengthWrap">
              <div class="strength-bar-track">
                <div class="strength-bar-fill" id="strengthBar"></div>
              </div>
              <div class="strength-label-row">
                <span class="strength-hint" id="strengthHint">Use 8+ characters, numbers &amp; symbols</span>
                <span class="strength-badge" id="strengthBadge" style="display:none;"></span>
              </div>
            </div>
          </div>

          {{-- Confirm New Password --}}
          <div class="field">
            <label>Confirm New Password</label>
            <div class="pw-wrap">
              <input type="password" id="pwConfirm" placeholder="••••••••••"
                     autocomplete="new-password" oninput="checkMatch()">
              <button type="button" class="pw-eye" onclick="togglePw('pwConfirm', this)">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <span id="matchHint" style="font-size:0.68rem;font-weight:600;display:none;margin-top:2px;"></span>
          </div>

          <button class="btn-save" id="pwSaveBtn" onclick="submitPasswordChange()">
            <span id="pwSaveText">Save New Password</span>
            <span id="pwSaveSpinner" style="display:none;"><div class="spinner"></div></span>
          </button>

        </div>
      </div>

      {{-- ── CARD 3: Session Info ── --}}
      <div class="section-card">
        <div class="section-head">
          <div class="section-icon">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
          <div>
            <div class="section-title">Session Info</div>
            <div class="section-sub">Current login session details</div>
          </div>
        </div>
        <div class="section-body" style="padding-top:14px;padding-bottom:14px;">
          <div class="session-row">
            <div class="session-icon">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
              <div class="session-label">Logged in as</div>
              <div class="session-value">{{ $userName }}</div>
            </div>
          </div>
          <div class="session-row">
            <div class="session-icon">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
            </div>
            <div>
              <div class="session-label">Account Email</div>
              <div class="session-value mono">{{ $accountEmail }}</div>
            </div>
          </div>
          <div class="session-row">
            <div class="session-icon">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
              <div class="session-label">Session Date</div>
              <div class="session-value mono">{{ session('session_started_at', now()->format('M d, Y · h:i A')) }}</div>
            </div>
          </div>
          <div class="session-row">
            <div class="session-icon">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
              <div class="session-label">Status</div>
              <div class="session-value" style="margin-top:3px;">
                <span class="active-badge">
                  <span class="active-dot"></span>
                  Active
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ── CARD 4: Change Profile Picture ── --}}
      <div class="section-card">
        <div class="section-head">
          <div class="section-icon">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
              <circle cx="12" cy="13" r="4"/>
            </svg>
          </div>
          <div>
            <div class="section-title">Profile Picture</div>
            <div class="section-sub">Upload a photo to personalize your profile</div>
          </div>
        </div>
        <div class="section-body">

          {{-- Current avatar preview --}}
          <div style="display:flex; align-items:center; gap:18px;">
            <div id="avatarPreviewWrap" style="position:relative; flex-shrink:0;">
              @php $avatarImage = session('user_avatar_image'); @endphp
              @if($avatarImage)
                <img id="avatarPreviewImg"
                     src="{{ asset('storage/avatars/' . $avatarImage) }}"
                     alt="Profile"
                     style="width:72px;height:72px;border-radius:50%;object-fit:cover;
                            border:3px solid var(--line);box-shadow:0 4px 16px rgba(15,23,42,.12);">
              @else
                <div id="avatarPreviewInitials"
                     style="width:72px;height:72px;border-radius:50%;
                            background:linear-gradient(135deg,{{ $userColor }}cc,{{ $userColor }});
                            display:flex;align-items:center;justify-content:center;
                            font-weight:800;font-size:1.3rem;color:#fff;
                            border:3px solid var(--line);box-shadow:0 4px 16px rgba(15,23,42,.12);">
                  {{ $userInitials }}
                </div>
                <img id="avatarPreviewImg"
                     src=""
                     alt="Profile"
                     style="width:72px;height:72px;border-radius:50%;object-fit:cover;
                            border:3px solid var(--line);box-shadow:0 4px 16px rgba(15,23,42,.12);
                            display:none;">
              @endif
            </div>
            <div>
              <div style="font-size:0.82rem;font-weight:700;color:var(--ink);margin-bottom:3px;">
                {{ $avatarImage ? 'Current photo' : 'No photo yet' }}
              </div>
              <div style="font-size:0.72rem;color:var(--muted);font-weight:500;line-height:1.5;">
                JPG, PNG or WEBP · Max 2MB<br>Recommended: at least 200×200px
              </div>
            </div>
          </div>

          {{-- Upload area --}}
          <div id="avatarDropZone"
               style="border:2px dashed var(--line);border-radius:14px;
                      padding:22px 16px;text-align:center;cursor:pointer;
                      transition:border-color .2s,background .2s;position:relative;"
               onclick="document.getElementById('avatarFileInput').click()"
               ondragover="handleDragOver(event)"
               ondragleave="handleDragLeave(event)"
               ondrop="handleDrop(event)">
            <input type="file" id="avatarFileInput" accept="image/jpeg,image/png,image/webp"
                   style="display:none;" onchange="handleFileSelect(event)">
            <div id="dropZoneContent">
              <div style="width:38px;height:38px;border-radius:11px;background:var(--maroon-light);
                          display:flex;align-items:center;justify-content:center;
                          color:var(--maroon);margin:0 auto 10px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
              </div>
              <div style="font-size:0.82rem;font-weight:700;color:var(--ink);margin-bottom:3px;">
                Click to upload or drag & drop
              </div>
              <div style="font-size:0.7rem;color:var(--muted);">JPG, PNG, WEBP up to 2MB</div>
            </div>
          </div>

          {{-- Alert --}}
          <div id="avatarAlert" class="pw-alert" style="display:none;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span id="avatarAlertText"></span>
          </div>

          {{-- Save button --}}
          {{-- Hidden input carries the upload URL safely into JS --}}
          <input type="hidden" id="avatarUploadUrl" value="{{ url('/profile/upload-avatar') }}">

          <button id="avatarSaveBtn" class="btn-save" onclick="uploadAvatar()" disabled
                  style="align-self:flex-end;">
            <span id="avatarSaveText" style="display:flex;align-items:center;gap:7px;">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
              Upload Photo
            </span>
            <span id="avatarSaveSpinner" style="display:none;" class="spinner"></span>
          </button>

        </div>
      </div>

    </div>{{-- end profile-grid --}}
  </div>{{-- end content --}}
</div>{{-- end main-wrap --}}

{{-- ══════════════ LOGOUT MODAL ══════════════ --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal-box">
    <div class="modal-icon">
      <svg width="22" height="22" fill="none" stroke="var(--maroon)" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>
    <div class="modal-title">Sign Out?</div>
    <p class="modal-desc">You'll be returned to the login page. Any unsaved changes will be lost.</p>
    <div class="modal-actions">
      <button type="button" class="btn-cancel" id="cancelLogout">Cancel</button>
      <form method="POST" action="{{ url('/logout') }}" id="logoutForm">
        @csrf
        <button type="submit" class="btn-confirm-logout">Sign Out</button>
      </form>
    </div>
  </div>
</div>

<script>
  /* ══════════════════════════════════════
     PASSWORD EYE TOGGLE
  ══════════════════════════════════════ */
  function togglePw(inputId, btn) {
    const input    = document.getElementById(inputId);
    const isHidden = input.type === 'password';
    input.type     = isHidden ? 'text' : 'password';
    btn.innerHTML  = isHidden
      ? `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
      : `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
  }

  /* ══════════════════════════════════════
     PASSWORD STRENGTH METER
  ══════════════════════════════════════ */
  function scorePassword(pw) {
    if (!pw) return 0;
    let score = 0;
    if (pw.length >= 8)  score++;
    if (pw.length >= 12) score++;
    if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    // Map to 1–4
    if (score <= 1) return 1;
    if (score === 2) return 2;
    if (score === 3) return 3;
    return 4;
  }

  const strengthLabels = {
    1: 'Weak',
    2: 'Fair',
    3: 'Strong',
    4: 'Very Strong',
  };
  const strengthHints = {
    0: 'Use 8+ characters, numbers &amp; symbols',
    1: 'Too short or simple — add more variety',
    2: 'Getting there — try adding numbers or symbols',
    3: 'Good — add a symbol or more length to make it stronger',
    4: 'Great password! ✓',
  };

  function updateStrength(pw) {
    const wrap  = document.getElementById('strengthWrap');
    const badge = document.getElementById('strengthBadge');
    const hint  = document.getElementById('strengthHint');

    if (!pw) {
      wrap.className  = 'strength-wrap strength-0';
      badge.style.display = 'none';
      hint.innerHTML  = strengthHints[0];
      checkMatch();
      return;
    }

    const level = scorePassword(pw);
    wrap.className      = `strength-wrap strength-${level}`;
    badge.style.display = '';
    badge.textContent   = strengthLabels[level];
    hint.innerHTML      = strengthHints[level];
    checkMatch();
  }

  /* ══════════════════════════════════════
     CONFIRM PASSWORD MATCH CHECK
  ══════════════════════════════════════ */
  function checkMatch() {
    const newPw   = document.getElementById('pwNew').value;
    const confirm = document.getElementById('pwConfirm').value;
    const hint    = document.getElementById('matchHint');

    if (!confirm) { hint.style.display = 'none'; return; }

    if (newPw === confirm) {
      hint.style.display = '';
      hint.style.color   = '#059669';
      hint.textContent   = '✓ Passwords match';
    } else {
      hint.style.display = '';
      hint.style.color   = 'var(--maroon)';
      hint.textContent   = '✗ Passwords do not match';
    }
  }

  /* ══════════════════════════════════════
     CHANGE PASSWORD SUBMIT
  ══════════════════════════════════════ */
  async function submitPasswordChange() {
    const current  = document.getElementById('pwCurrent').value;
    const newPw    = document.getElementById('pwNew').value;
    const confirm  = document.getElementById('pwConfirm').value;
    const alertEl  = document.getElementById('pwAlert');
    const alertTxt = document.getElementById('pwAlertText');
    const btn      = document.getElementById('pwSaveBtn');
    const btnText  = document.getElementById('pwSaveText');
    const spinner  = document.getElementById('pwSaveSpinner');

    const showAlert = (msg, type = 'error') => {
      alertEl.className    = `pw-alert ${type} show`;
      alertTxt.textContent = msg;
      // Auto-dismiss success after 4 seconds
      if (type === 'success') {
        setTimeout(() => alertEl.classList.remove('show'), 4000);
      }
    };
    alertEl.classList.remove('show');

    // ── Client-side guards ─────────────────────────────
    if (!current)              { showAlert('Please enter your current password.'); return; }
    if (!newPw)                { showAlert('Please enter a new password.'); return; }
    if (newPw.length < 8)      { showAlert('New password must be at least 8 characters.'); return; }
    if (scorePassword(newPw) < 2) { showAlert('Password is too weak. Add uppercase letters, numbers or symbols.'); return; }
    if (newPw !== confirm)     { showAlert('New passwords do not match.'); return; }
    if (newPw === current)     { showAlert('New password must be different from your current password.'); return; }

    // ── Submit ─────────────────────────────────────────
    btn.disabled          = true;
    btnText.style.display = 'none';
    spinner.style.display = 'inline-flex';

    try {
      const resp = await fetch('{{ url('/profile/change-password') }}', {
        method:      'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept':        'application/json',
          'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ current_password: current, new_password: newPw }),
      });

      const data = await resp.json();

      if (resp.ok && data.success) {
        showAlert('Password updated successfully!', 'success');
        // Clear all fields and reset meter
        document.getElementById('pwCurrent').value = '';
        document.getElementById('pwNew').value     = '';
        document.getElementById('pwConfirm').value = '';
        updateStrength('');
        document.getElementById('matchHint').style.display = 'none';
      } else {
        showAlert(data.message || 'Current password is incorrect.');
      }
    } catch (e) {
      showAlert('Something went wrong. Please try again.');
    } finally {
      btn.disabled          = false;
      btnText.style.display = '';
      spinner.style.display = 'none';
    }
  }

  /* ══════════════════════════════════════
     CHANGE NAME
  ══════════════════════════════════════ */
  function openNameEdit() {
    const current = document.getElementById('nameDisplay').textContent.trim();
    document.getElementById('nameInput').value = current;
    document.getElementById('nameEditWrap').classList.add('open');
    document.getElementById('nameAlert').classList.remove('show');
    document.getElementById('nameInput').focus();
  }

  function closeNameEdit() {
    document.getElementById('nameEditWrap').classList.remove('open');
    document.getElementById('nameAlert').classList.remove('show');
  }

  async function submitNameChange() {
    const newName  = document.getElementById('nameInput').value.trim();
    const alertEl  = document.getElementById('nameAlert');
    const alertTxt = document.getElementById('nameAlertText');
    const btn      = document.getElementById('nameSaveBtn');
    const btnText  = document.getElementById('nameSaveText');
    const spinner  = document.getElementById('nameSaveSpinner');

    const showAlert = (msg, type = 'error') => {
      alertEl.className    = `pw-alert ${type} show`;
      alertTxt.textContent = msg;
      if (type === 'success') setTimeout(() => alertEl.classList.remove('show'), 4000);
    };
    alertEl.classList.remove('show');

    // ── Client-side guards ──────────────────────────────
    if (!newName)           { showAlert('Name cannot be empty.'); return; }
    if (newName.length < 2) { showAlert('Name must be at least 2 characters.'); return; }
    if (newName.length > 60){ showAlert('Name cannot exceed 60 characters.'); return; }

    btn.disabled          = true;
    btnText.style.display = 'none';
    spinner.style.display = 'inline-flex';

    try {
      const resp = await fetch('{{ url('/profile/change-name') }}', {
        method:      'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept':        'application/json',
          'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ name: newName }),
      });

      const data = await resp.json();

      if (resp.ok && data.success) {
        // Update displayed name everywhere on the page
        document.getElementById('nameDisplay').textContent = data.name;
        document.querySelector('.hero-name') && (document.querySelector('.hero-name').textContent = data.name);
        closeNameEdit();
        showAlert('Name updated successfully!', 'success');
      } else {
        showAlert(data.message || 'Could not update name. Please try again.');
      }
    } catch (e) {
      showAlert('Something went wrong. Please try again.');
    } finally {
      btn.disabled          = false;
      btnText.style.display = '';
      spinner.style.display = 'none';
    }
  }

  /* ── Base Role edit (Admin / Staff) ── */
  window.openBaseRoleEdit = function() {
    document.getElementById('baseRoleDisplayWrap').classList.add('role-display-hidden');
    document.getElementById('baseRoleEditWrap').classList.add('open');
  };
  window.closeBaseRoleEdit = function() {
    document.getElementById('baseRoleEditWrap').classList.remove('open');
    document.getElementById('baseRoleDisplayWrap').classList.remove('role-display-hidden');
  };
  window.submitBaseRoleChange = async function() {
    const newRole = document.getElementById('baseRoleSelect').value;
    const btn     = document.getElementById('baseRoleSaveBtn');
    const btnText = document.getElementById('baseRoleSaveText');
    const spinner = document.getElementById('baseRoleSaveSpinner');
    btn.disabled = true; btnText.style.display = 'none'; spinner.style.display = 'inline-flex';
    try {
      const resp = await fetch('{{ url('/profile/change-base-role') }}', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ base_role: newRole }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        const label = newRole === 'admin' ? 'Admin' : 'Staff';
        document.getElementById('baseRoleDisplay').textContent = label;
        const pill = document.getElementById('baseRolePillDisplay');
        pill.className = `role-pill ${newRole}`;
        const heroBadge = document.querySelector('.hero-role-badge');
        if (heroBadge) { heroBadge.textContent = label; heroBadge.className = `hero-role-badge badge-${newRole}`; }
        closeBaseRoleEdit();
      } else {
        alert(data.message || 'Could not update base role.');
      }
    } catch(e) { alert('Something went wrong.'); }
    finally { btn.disabled=false; btnText.style.display=''; spinner.style.display='none'; }
  };

  /* ── Custom Role edit ── */
  window.openCustomRoleEdit = function() {
    document.getElementById('customRoleDisplayWrap').classList.add('role-display-hidden');
    document.getElementById('customRoleEditWrap').classList.add('open');
    document.getElementById('roleAlert').style.display = 'none';
    document.getElementById('customRoleInput').focus();
  };
  window.closeCustomRoleEdit = function() {
    document.getElementById('customRoleEditWrap').classList.remove('open');
    document.getElementById('customRoleDisplayWrap').classList.remove('role-display-hidden');
    document.getElementById('roleAlert').style.display = 'none';
  };
  window.submitCustomRoleChange = async function() {
    const val     = document.getElementById('customRoleInput').value.trim();
    const alertEl = document.getElementById('roleAlert');
    const alertTx = document.getElementById('roleAlertText');
    const btn     = document.getElementById('customRoleSaveBtn');
    const btnText = document.getElementById('customRoleSaveText');
    const spinner = document.getElementById('customRoleSaveSpinner');
    const showAlert = (msg, type='error') => {
      alertEl.className = `pw-alert ${type} show`; alertEl.style.display='flex';
      alertTx.textContent = msg;
      if (type==='success') setTimeout(() => { alertEl.classList.remove('show'); alertEl.style.display='none'; }, 4000);
    };
    alertEl.style.display = 'none';
    if (val && val.length > 50) { showAlert('Custom role cannot exceed 50 characters.'); return; }
    if (val && !/^[a-zA-Z0-9 _\-]+$/.test(val)) { showAlert('Letters, numbers, spaces, hyphens and underscores only.'); return; }
    btn.disabled=true; btnText.style.display='none'; spinner.style.display='inline-flex';
    try {
      const resp = await fetch('{{ url('/profile/change-custom-role') }}', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ custom_role: val }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        const pill  = document.getElementById('customRolePillDisplay');
        const empty = document.getElementById('customRoleEmpty');
        if (val) {
          const label = val.charAt(0).toUpperCase() + val.slice(1);
          document.getElementById('customRoleDisplay').textContent = label;
          pill.style.display  = '';
          empty.style.display = 'none';
        } else {
          pill.style.display  = 'none';
          empty.style.display = '';
        }
        closeCustomRoleEdit();
        showAlert('Custom role updated!', 'success');
      } else {
        showAlert(data.message || 'Could not update custom role.');
      }
    } catch(e) { showAlert('Something went wrong.'); }
    finally { btn.disabled=false; btnText.style.display=''; spinner.style.display='none'; }
  };

  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const logoutOpen  = document.getElementById('logoutModal')?.classList.contains('open');
    document.body.style.overflow = (sidebarOpen || logoutOpen) ? 'hidden' : '';
  }

  /* ── Logout modal ── */
  document.getElementById('logoutBtn')?.addEventListener('click', () => {
    document.getElementById('logoutModal').classList.add('open');
    syncBodyScrollLock();
  });
  document.getElementById('cancelLogout')?.addEventListener('click', () => {
    document.getElementById('logoutModal').classList.remove('open');
    syncBodyScrollLock();
  });
  document.getElementById('logoutModal').addEventListener('click', e => {
    if (e.target === document.getElementById('logoutModal')) {
      document.getElementById('logoutModal').classList.remove('open');
      syncBodyScrollLock();
    }
  });
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    const lm = document.getElementById('logoutModal');
    if (lm?.classList.contains('open')) {
      lm.classList.remove('open');
      syncBodyScrollLock();
    }
  });

  /* ── Avatar upload ── */
  let selectedFile = null;

  function showAvatarAlert(msg, type = 'error') {
    const el  = document.getElementById('avatarAlert');
    const txt = document.getElementById('avatarAlertText');
    el.className = `pw-alert ${type} show`;
    el.style.display = 'flex';
    txt.textContent  = msg;
  }

  function clearAvatarAlert() {
    const el = document.getElementById('avatarAlert');
    el.style.display = 'none';
    el.classList.remove('show','error','success');
  }

  function previewFile(file) {
    if (!file) return;

    // Validate type
    const allowed = ['image/jpeg','image/png','image/webp'];
    if (!allowed.includes(file.type)) {
      showAvatarAlert('Only JPG, PNG, or WEBP files are allowed.');
      return;
    }
    // Validate size (2MB)
    if (file.size > 2 * 1024 * 1024) {
      showAvatarAlert('File is too large. Maximum size is 2MB.');
      return;
    }

    clearAvatarAlert();
    selectedFile = file;

    // Show image preview
    const reader = new FileReader();
    reader.onload = e => {
      const img      = document.getElementById('avatarPreviewImg');
      const initials = document.getElementById('avatarPreviewInitials');
      img.src        = e.target.result;
      img.style.display = 'block';
      if (initials) initials.style.display = 'none';
    };
    reader.readAsDataURL(file);

    // Update drop zone text
    document.getElementById('dropZoneContent').innerHTML = `
      <div style="font-size:0.82rem;font-weight:700;color:var(--maroon);margin-bottom:3px;">
        ✓ ${file.name}
      </div>
      <div style="font-size:0.7rem;color:var(--muted);">Click to choose a different file</div>`;

    document.getElementById('avatarSaveBtn').disabled = false;
  }

  function handleFileSelect(e) { previewFile(e.target.files[0]); }

  function handleDragOver(e) {
    e.preventDefault();
    const dz = document.getElementById('avatarDropZone');
    dz.style.borderColor = 'var(--maroon)';
    dz.style.background  = 'var(--maroon-light)';
  }

  function handleDragLeave(e) {
    const dz = document.getElementById('avatarDropZone');
    dz.style.borderColor = 'var(--line)';
    dz.style.background  = '';
  }

  function handleDrop(e) {
    e.preventDefault();
    handleDragLeave(e);
    const file = e.dataTransfer.files[0];
    if (file) previewFile(file);
  }

  async function uploadAvatar() {
    if (!selectedFile) return;

    const btn     = document.getElementById('avatarSaveBtn');
    const text    = document.getElementById('avatarSaveText');
    const spinner = document.getElementById('avatarSaveSpinner');

    btn.disabled        = true;
    text.style.display  = 'none';
    spinner.style.display = 'inline-flex';
    clearAvatarAlert();

    const formData = new FormData();
    formData.append('avatar', selectedFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
      const uploadUrl = document.getElementById('avatarUploadUrl').value;
      const resp = await fetch(uploadUrl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
      });
      const contentType = resp.headers.get('content-type') || '';
      const data = contentType.includes('application/json')
        ? await resp.json()
        : { success: false, message: await resp.text() };

      if (resp.ok && data.success) {
        showAvatarAlert('Profile picture updated successfully!', 'success');
        selectedFile = null;

        // Swap the live preview to the newly uploaded photo
        if (data.url) {
          const img      = document.getElementById('avatarPreviewImg');
          const initials = document.getElementById('avatarPreviewInitials');
          img.src              = data.url + '?t=' + Date.now(); // cache-bust
          img.style.display    = 'block';
          if (initials) initials.style.display = 'none';
        }

        // Reset drop zone text
        document.getElementById('dropZoneContent').innerHTML = `
          <div style="font-size:0.82rem;font-weight:700;color:#059669;margin-bottom:3px;">✓ Upload successful</div>
          <div style="font-size:0.7rem;color:var(--muted);">Click to replace photo</div>`;

        // Keep button disabled — re-enable only if user picks a new file
        btn.disabled          = true;
        text.style.display    = '';
        spinner.style.display = 'none';
      } else {
        let message = data.message || 'Upload failed. Please try again.';

        if (typeof message === 'string' && message.includes('<!DOCTYPE')) {
          message = 'Upload failed — server returned an error page. Check Laravel logs.';
        }

        showAvatarAlert(message);
        btn.disabled          = false;
        text.style.display    = '';
        spinner.style.display = 'none';
      }
    } catch(e) {
      console.error('Avatar upload error:', e);
      showAvatarAlert('Error: ' + (e.message || e.toString()));
      btn.disabled          = false;
      text.style.display    = '';
      spinner.style.display = 'none';
    }
  }

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
</script>

</body>
</html>