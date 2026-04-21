<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — Guest Portal</title>

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
      --ink2:         #1e293b;
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
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 32px; flex-shrink: 0;
      overflow: hidden;
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
      letter-spacing: .04em; z-index: 100;
    }
    .nav-item:hover .nav-tooltip { opacity: 1; }
    .sidebar-bottom {
      display: flex; flex-direction: column; align-items: center; gap: 6px;
    }

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
      display: flex; align-items: center; gap: 14px;
      min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; }
    .page-title {
      font-size: clamp(0.98rem, 0.45vw + 0.88rem, 1.12rem);
      font-weight: 800; letter-spacing: -.3px; color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-subtitle {
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted); font-weight: 500; margin-top: 1px;
      overflow-wrap: anywhere;
    }
    .topbar-right {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
      justify-content: flex-end;
    }
    .guest-pill {
      display: flex; align-items: center; gap: 7px;
      background: var(--maroon-light); border: 1.5px solid rgba(165,44,48,.2);
      border-radius: 12px; padding: 7px 14px;
      font-size: clamp(0.7rem, 0.15vw + 0.67rem, 0.75rem);
      font-weight: 700; color: var(--maroon);
      max-width: 100%;
      min-width: 0;
    }
    .guest-pill-text { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0; }
    .guest-pill-dot {
      width: 7px; height: 7px; border-radius: 50%;
      background: var(--maroon);
      box-shadow: 0 0 0 3px rgba(165,44,48,.2);
    }
    .icon-btn {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted); transition: all .18s;
      text-decoration: none;
    }
    .icon-btn:hover { background: var(--maroon-light); color: var(--maroon); }
    .avatar {
      width: 40px; height: 40px; border-radius: 12px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.85rem; color: #2a1a0b;
      flex-shrink: 0;
    }

    /* ══════════ CONTENT ══════════ */
    .content {
      padding: clamp(14px, 2.5vw, 18px) var(--pad-x);
      flex: 1;
      width: 100%;
      max-width: var(--shell-max);
      margin: 0 auto;
      box-sizing: border-box;
      background: var(--bg) url('{{ asset("images/abstractBGIMAGE12.png") }}') no-repeat right center;
      background-size: cover;
    }

    /* ══════════ HERO BANNER ══════════ */
    .hero {
      position: relative; overflow: hidden;
      background: linear-gradient(135deg, var(--maroon2) 0%, var(--maroon) 55%, #C1363A 100%);
      border-radius: 20px;
      padding: clamp(18px, 3vw, 24px) clamp(18px, 3vw, 28px);
      margin-bottom: 14px;
      box-shadow: 0 8px 32px rgba(165,44,48,.25);
    }
    /* decorative circles */
    .hero::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 200px; height: 200px; border-radius: 50%;
      background: rgba(255,255,255,.04);
    }
    .hero::after {
      content: ''; position: absolute; bottom: -60px; right: 80px;
      width: 220px; height: 220px; border-radius: 50%;
      background: rgba(240,200,96,.05);
    }
    .hero-deco-ring {
      position: absolute; top: 20px; right: 160px;
      width: 100px; height: 100px; border-radius: 50%;
      border: 28px solid rgba(255,255,255,.04);
    }
    .hero-inner {
      position: relative; z-index: 1;
      display: flex; align-items: center; flex-wrap: wrap;
      gap: clamp(18px, 3vw, 28px);
    }
    .hero-text { flex: 1 1 260px; min-width: 0; }
    .hero-eyebrow {
      display: inline-flex; align-items: center; gap: 7px;
      background: rgba(240,200,96,.18); border: 1px solid rgba(240,200,96,.3);
      border-radius: 999px; padding: 3px 12px;
      font-family: 'DM Mono', monospace;
      font-size: 0.58rem; font-weight: 500; letter-spacing: .12em;
      text-transform: uppercase; color: var(--gold);
      margin-bottom: 10px;
    }
    .hero-eyebrow-dot { width: 4px; height: 4px; border-radius: 50%; background: var(--gold); }
    .hero-title {
      font-size: clamp(1.15rem, 2.2vw + 0.55rem, 1.45rem);
      font-weight: 800;
      color: #fff; line-height: 1.2;
      letter-spacing: -.4px; margin-bottom: 8px;
      overflow-wrap: anywhere;
    }
    .hero-title span { color: var(--gold); }
    .hero-desc {
      font-size: clamp(0.74rem, 0.25vw + 0.7rem, 0.78rem);
      color: rgba(255,255,255,.68);
      line-height: 1.65; max-width: 440px; margin-bottom: 14px;
      overflow-wrap: anywhere;
    }
    .hero-actions { display: flex; align-items: center; gap: 9px; flex-wrap: wrap; }
    .btn-hero-primary {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b; border: none; border-radius: 11px;
      padding: clamp(8px, 1.2vw, 9px) clamp(14px, 2.5vw, 18px);
      font-family: inherit;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.76rem);
      font-weight: 800;
      cursor: pointer; text-decoration: none;
      box-shadow: 0 4px 14px rgba(240,200,96,.35);
      transition: all .2s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-hero-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 22px rgba(240,200,96,.44); }
    .btn-hero-ghost {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: rgba(255,255,255,.12); border: 1.5px solid rgba(255,255,255,.22);
      color: #fff; border-radius: 11px;
      padding: clamp(7px, 1.2vw, 8px) clamp(12px, 2.2vw, 16px);
      font-family: inherit;
      font-size: clamp(0.71rem, 0.17vw + 0.67rem, 0.75rem);
      font-weight: 700;
      cursor: pointer; text-decoration: none; transition: background .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-hero-ghost:hover { background: rgba(255,255,255,.2); }

    /* hero right side stat cluster */
    .hero-stats {
      display: flex; flex-direction: column; gap: 8px;
      flex: 0 1 auto;
      min-width: 0;
      width: 100%;
      max-width: 320px;
    }
    .hstat {
      background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.14);
      border-radius: 13px; padding: 10px 14px;
      min-width: 108px; backdrop-filter: blur(4px);
      transition: background .18s;
    }
    .hstat:hover { background: rgba(255,255,255,.16); }
    .hstat-val {
      
      font-size: 1.55rem; font-weight: 800; color: #fff; line-height: 1;
    }
    .hstat-val.gold { color: var(--gold); }
    .hstat-label {
      font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,.55);
      letter-spacing: .07em; text-transform: uppercase; margin-top: 3px;
    }
    .hstat-row { display: flex; flex-wrap: wrap; gap: 8px; }
    .hstat-row .hstat { flex: 1 1 100px; min-width: 0; }

    /* ══════════ LOWER GRID ══════════ */
    .lower-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 290px);
      gap: 14px;
      align-items: start;
    }

    /* ══════════ WHAT YOU CAN DO ══════════ */
    .section-label {
      font-family: 'DM Mono', monospace;
      font-size: 0.62rem; font-weight: 500; letter-spacing: .14em;
      text-transform: uppercase; color: var(--muted);
      margin-bottom: 10px;
    }

    .can-do-grid {
      display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 10px; margin-bottom: 12px;
    }
    .cando-card {
      background: var(--card); border-radius: 16px;
      border: 1px solid var(--line);
      padding: 16px 18px 14px;
      box-shadow: 0 2px 8px rgba(15,23,42,.04);
      display: flex; flex-direction: column; gap: 0;
      transition: transform .2s, box-shadow .2s;
      text-decoration: none; color: inherit;
      position: relative; overflow: hidden;
    }
    .cando-card:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(15,23,42,.08); }
    .cando-card.featured {
      background: linear-gradient(135deg, var(--ink2), var(--ink));
      border-color: transparent;
      box-shadow: 0 6px 24px rgba(15,23,42,.18);
      grid-column: span 2;
    }
    .cando-card.featured:hover { box-shadow: 0 10px 30px rgba(15,23,42,.26); }
    .cando-card-deco {
      position: absolute; bottom: -20px; right: -20px;
      width: 90px; height: 90px; border-radius: 50%;
      background: rgba(240,200,96,.06);
    }
    .cando-icon {
      width: 36px; height: 36px; border-radius: 11px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 10px; flex-shrink: 0;
    }
    .cando-icon.maroon { background: var(--maroon-light); color: var(--maroon); }
    .cando-icon.gold   { background: rgba(240,200,96,.15); color: #b8860b; }
    .cando-icon.green  { background: rgba(16,185,129,.1); color: #059669; }
    .cando-icon.white  { background: rgba(255,255,255,.12); color: #fff; }
    .cando-title {
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
      font-weight: 800; color: var(--ink);
      margin-bottom: 4px; line-height: 1.3;
      overflow-wrap: anywhere;
    }
    .cando-card.featured .cando-title { color: #fff; }
    .cando-desc {
      font-size: clamp(0.66rem, 0.15vw + 0.63rem, 0.71rem);
      color: var(--muted); line-height: 1.55; flex: 1;
      overflow-wrap: anywhere;
    }
    .cando-card.featured .cando-desc { color: rgba(255,255,255,.62); }
    .cando-link {
      display: inline-flex; align-items: center; gap: 5px;
      margin-top: 10px; font-size: 0.7rem; font-weight: 700;
      color: var(--maroon); text-decoration: none;
      transition: gap .15s;
    }
    .cando-link:hover { gap: 10px; }
    .cando-card.featured .cando-link { color: var(--gold); }
    .cando-featured-inner {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 14px 18px;
      position: relative; z-index: 1;
    }
    .cando-featured-text { flex: 1 1 200px; min-width: 0; }
    .cando-featured-badge {
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b; border-radius: 11px;
      padding: clamp(8px, 1.2vw, 9px) clamp(12px, 2.2vw, 16px);
      font-size: clamp(0.7rem, 0.15vw + 0.67rem, 0.74rem);
      font-weight: 800;
      text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      box-shadow: 0 4px 14px rgba(240,200,96,.28);
      transition: all .2s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .cando-featured-badge:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(240,200,96,.42); }

    /* ══════════ ACCESS BANNER ══════════ */
    .access-banner {
      background: var(--card); border-radius: 14px;
      border: 1px solid var(--line);
      padding: 13px 16px;
      box-shadow: 0 2px 8px rgba(15,23,42,.04);
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 13px;
    }
    .access-banner-text { flex: 1 1 200px; min-width: 0; }
    .access-banner-icon {
      width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 12px rgba(240,200,96,.26);
    }
    .access-banner-title { font-size: 0.8rem; font-weight: 800; color: var(--ink); overflow-wrap: anywhere; }
    .access-banner-sub { font-size: 0.66rem; color: var(--muted); margin-top: 2px; line-height: 1.45; overflow-wrap: anywhere; }
    .access-banner-btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      background: var(--maroon-light); color: var(--maroon);
      border: 1.5px solid rgba(165,44,48,.18); border-radius: 10px;
      padding: 8px 16px; font-family: inherit;
      font-size: clamp(0.7rem, 0.15vw + 0.67rem, 0.74rem);
      font-weight: 700; cursor: pointer; transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .access-banner-btn:hover { background: rgba(165,44,48,.18); }

    /* ══════════ RIGHT COLUMN ══════════ */
    .right-col { display: flex; flex-direction: column; gap: 12px; }

    /* access level card */
    .access-card {
      background: linear-gradient(135deg, var(--maroon2) 0%, var(--maroon) 100%);
      border-radius: 16px; padding: 16px 18px;
      box-shadow: 0 6px 22px rgba(165,44,48,.22);
      position: relative; overflow: hidden;
    }
    .access-card::after {
      content: ''; position: absolute; bottom: -28px; right: -28px;
      width: 110px; height: 110px; border-radius: 50%;
      background: rgba(240,200,96,.07);
    }
    .access-card-inner { position: relative; z-index: 1; }
    .access-card-eyebrow {
      font-family: 'DM Mono', monospace;
      font-size: 0.6rem; font-weight: 500; letter-spacing: .14em;
      text-transform: uppercase; color: rgba(255,255,255,.55);
      margin-bottom: 10px;
    }
    .access-card-level {
      
      font-size: 1.3rem; font-weight: 800; color: #fff; line-height: 1;
      margin-bottom: 3px;
    }
    .access-card-sub {
      font-size: 0.7rem; color: rgba(255,255,255,.6); margin-bottom: 12px;
    }
    .access-divider {
      height: 1px; background: rgba(255,255,255,.1); margin-bottom: 10px;
    }
    .access-row {
      display: flex; align-items: center; gap: 8px; padding: 5px 0;
    }
    .access-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .access-dot.yes { background: #34d399; }
    .access-dot.no  { background: rgba(255,255,255,.25); }
    .access-dot.cond { background: var(--gold); }
    .access-text { font-size: 0.76rem; font-weight: 600; color: rgba(255,255,255,.8); flex: 1; }
    .access-badge {
      font-size: 0.62rem; font-weight: 800; padding: 2px 8px; border-radius: 999px;
    }
    .access-badge.yes  { background: rgba(52,211,153,.15); color: #34d399; }
    .access-badge.no   { background: rgba(255,255,255,.08); color: rgba(255,255,255,.35); }
    .access-badge.cond { background: rgba(240,200,96,.15); color: var(--gold); }

    /* quick links card */
    .quicklinks-card {
      background: var(--card); border-radius: 16px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 8px rgba(15,23,42,.04);
      overflow: hidden;
    }
    .ql-header {
      padding: 16px 20px 14px;
      border-bottom: 1px solid var(--line);
    }
    .ql-title { font-size: 0.85rem; font-weight: 800; color: var(--ink); }
    .ql-item {
      display: flex; align-items: center; gap: 13px;
      padding: 13px 20px; text-decoration: none; color: inherit;
      border-bottom: 1px solid var(--line);
      transition: background .15s;
    }
    .ql-item:last-child { border-bottom: none; }
    .ql-item:hover { background: var(--bg); }
    .ql-icon {
      width: 36px; height: 36px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .ql-icon.maroon { background: var(--maroon-light); color: var(--maroon); }
    .ql-icon.gold   { background: rgba(240,200,96,.15); color: #b8860b; }
    .ql-icon.slate  { background: rgba(100,116,139,.1); color: var(--muted); }
    .ql-label { font-size: 0.8rem; font-weight: 700; color: var(--ink); overflow-wrap: anywhere; }
    .ql-sub   { font-size: 0.68rem; color: var(--muted); margin-top: 1px; overflow-wrap: anywhere; }
    .ql-arrow { margin-left: auto; color: #cbd5e1; flex-shrink: 0; }
    .ql-item:hover .ql-arrow { color: var(--maroon); }
    .ql-item > div:not(.ql-icon) { min-width: 0; }

    /* tip card */
    .tip-card {
      background: linear-gradient(135deg, rgba(240,200,96,.12), rgba(240,200,96,.05));
      border: 1.5px solid rgba(240,200,96,.25);
      border-radius: 18px; padding: 18px 20px;
      display: flex; gap: 13px; align-items: flex-start;
    }
    .tip-icon {
      width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
      background: rgba(240,200,96,.2); color: #b8860b;
      display: flex; align-items: center; justify-content: center;
    }
    .tip-title { font-size: 0.78rem; font-weight: 800; color: var(--ink); margin-bottom: 4px; }
    .tip-text  { font-size: 0.72rem; color: var(--muted); line-height: 1.6; overflow-wrap: anywhere; }

    /* ══════════ FOOTER ══════════ */
    .page-footer {
      margin-top: 18px; padding: 12px 0;
      border-top: 1px solid var(--line);
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 10px 16px;
    }
    .page-footer-left  { font-size: .72rem; color: var(--muted); }
    .page-footer-right { font-size: .72rem; font-family: 'DM Mono', monospace; color: #94a3b8; }

    /* ══════════ MODALS ══════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center;
      padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
      box-sizing: border-box;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: #fff; border-radius: 24px;
      padding: clamp(22px, 4vw, 32px);
      width: min(420px, calc(100vw - 2rem));
      max-width: 100%;
      position: relative;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      animation: fadeSlideUp .3s forwards;
      box-sizing: border-box;
    }
    .modal-icon {
      width: 52px; height: 52px; border-radius: 16px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
    }
    .modal-title {
      font-size: clamp(0.95rem, 0.35vw + 0.88rem, 1.05rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere; line-height: 1.3;
    }
    .modal-desc  { font-size: 0.8rem; color: var(--muted); margin-top: 6px; line-height: 1.6; }
    .modal-rule-list { margin-top: 18px; display: flex; flex-direction: column; gap: 10px; }
    .modal-rule-item { display: flex; align-items: flex-start; gap: 10px; }
    .modal-rule-num {
      width: 22px; height: 22px; border-radius: 6px; flex-shrink: 0;
      background: var(--maroon-light); color: var(--maroon);
      font-size: 0.68rem; font-weight: 800;
      display: flex; align-items: center; justify-content: center;
    }
    .modal-rule-text { font-size: 0.78rem; color: var(--ink); font-weight: 600; line-height: 1.5; }
    .modal-btns {
      display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px;
      align-items: stretch;
    }
    .btn-cancel {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 12px; border-radius: 12px;
      border: 1.5px solid var(--line); background: none;
      font-family: inherit;
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
      font-weight: 700; color: var(--muted); cursor: pointer;
      transition: all .18s;
      box-sizing: border-box;
    }
    .btn-cancel:hover { background: var(--bg); }
    .btn-confirm {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 12px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none;
      font-family: inherit;
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
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
      color: var(--muted); display: flex; align-items: center; justify-content: center;
      transition: background .15s;
    }
    .modal-close-x:hover { background: #e2e8f0; color: var(--ink); }

    @media (max-width: 900px) {
      .lower-grid { grid-template-columns: minmax(0, 1fr); }
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
    @media (max-width: 640px) {
      .can-do-grid { grid-template-columns: minmax(0, 1fr); }
      .cando-card.featured { grid-column: span 1; }
      .guest-pill-long { display: none; }
    }
    @media (max-width: 480px) {
      .modal-btns { flex-direction: column; }
      .modal-btns .btn-cancel,
      .modal-btns .btn-confirm { flex: 0 0 auto; width: 100%; }
    }

    /* ══════════ ANIMATIONS ══════════ */
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .anim { opacity: 0; animation: fadeSlideUp .5s forwards; }
    .anim-1 { animation-delay: .05s; }
    .anim-2 { animation-delay: .12s; }
    .anim-3 { animation-delay: .19s; }
    .anim-4 { animation-delay: .26s; }
    .anim-5 { animation-delay: .33s; }
    .anim-6 { animation-delay: .40s; }
  </style>
</head>
<body>

  @php
    $guest        = $guest        ?? (object)['name' => 'Guest Viewer', 'role' => 'Guest'];
    $urlHome      = url('/');
    $urlGuestHome = url('/guest');
    $urlGuestRec  = url('/guest/records');
    $urlSupport   = url('/support');
    $urlHowTo     = url('/how-to-file');
    $kttmEmail    = $kttmEmail    ?? '<a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="c8a3bcbca588bda6a1beadbabba1bcb1e6adacbd">[email&#160;protected]</a>';

    $totalRecords    = $totalRecords    ?? '—';
    $pendingCount    = $pendingCount    ?? '—';
    $registeredCount = $registeredCount ?? '—';
    $campusCount     = $campusCount     ?? '—';

    $guestInitials = collect(preg_split('/\s+/', trim($guest->name ?? ''), -1, PREG_SPLIT_NO_EMPTY))
      ->map(fn ($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
    if ($guestInitials === '') {
      $guestInitials = 'G';
    }
  @endphp

  {{-- ── SIDEBAR ── --}}
  <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
  <aside class="sidebar" id="mainSidebar" aria-label="Guest navigation">
    <div class="sidebar-logo">
      <img src="{{ asset('images/guest icon.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">
    </div>

    <nav class="sidebar-nav">

      <a href="{{ $urlGuestHome }}" class="nav-item active">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span class="nav-tooltip">Home</span>
      </a>

      <a href="{{ $urlGuestRec }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/>
          <line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
        <span class="nav-tooltip">Records</span>
      </a>

      <a href="{{ $urlHowTo }}" class="nav-item">
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
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
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
          <div class="page-title">Guest Portal</div>
          <div class="page-subtitle">View-only access · KTTM IP Services</div>
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

      {{-- ── HERO BANNER ── --}}
      <div class="hero anim anim-1">
        <div class="hero-deco-ring"></div>
        <div class="hero-inner">

          <div class="hero-text">
            <div class="hero-eyebrow">
              <span class="hero-eyebrow-dot"></span>
              Guest Access · View Only
            </div>
            <div class="hero-title">
              Browse KTTM's<br><span>IP Records</span> freely.
            </div>
            <div class="hero-desc">
              Explore intellectual property filings from all campuses — patents, trademarks, copyrights, and more.
              To file or correct a record, contact KTTM staff directly.
            </div>
            <div class="hero-actions">
              <a href="{{ $urlGuestRec }}" class="btn-hero-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                </svg>
                Browse Records
              </a>
              <button type="button" id="openRulesHero" class="btn-hero-ghost">
                Guest Rules
              </button>
              <a href="#" data-open-contact class="btn-hero-ghost">
                Contact Support
              </a>
            </div>
          </div>

          <div class="hero-stats">
            <div class="hstat-row">
              <div class="hstat">
                <div class="hstat-val gold">{{ $totalRecords }}</div>
                <div class="hstat-label">Total Records</div>
              </div>
              <div class="hstat">
                <div class="hstat-val">{{ $campusCount }}</div>
                <div class="hstat-label">Campuses</div>
              </div>
            </div>
            <div class="hstat-row">
              <div class="hstat">
                <div class="hstat-val">{{ $registeredCount }}</div>
                <div class="hstat-label">Registered</div>
              </div>
              <div class="hstat">
                <div class="hstat-val">{{ $pendingCount }}</div>
                <div class="hstat-label">Pending</div>
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- ── LOWER GRID ── --}}
      <div class="lower-grid">

        {{-- LEFT: what you can do --}}
        <div>
          <div class="section-label">What you can do</div>

          <div class="can-do-grid">

            {{-- Featured: Browse Records --}}
            <div class="cando-card featured anim anim-2">
              <div class="cando-card-deco"></div>
              <div class="cando-featured-inner">
                <div class="cando-featured-text">
                  <div class="cando-icon white" style="margin-bottom:12px;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                      <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                      <polyline points="14 2 14 8 20 8"/>
                      <line x1="16" y1="13" x2="8" y2="13"/>
                      <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                  </div>
                  <div class="cando-title">Browse IP Records</div>
                  <div class="cando-desc">Search, filter, and view all publicly available IP filings — by campus, type, status, or keyword.</div>
                  <a href="{{ $urlGuestRec }}" class="cando-link">
                    Open Records
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                  </a>
                </div>
                <a href="{{ $urlGuestRec }}" class="cando-featured-badge">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                  </svg>
                  Go to Records
                </a>
              </div>
            </div>

            {{-- View Record Details --}}
            <div class="cando-card anim anim-3">
              <div class="cando-icon maroon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </div>
              <div class="cando-title">View Record Details</div>
              <div class="cando-desc">Click any record to see full details — title, owner, campus, registration number, and more.</div>
              <a href="{{ $urlGuestRec }}" class="cando-link">
                Browse Records
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
              </a>
            </div>

            {{-- Filing Guide --}}
            <div class="cando-card anim anim-4">
              <div class="cando-icon gold">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/>
                  <path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
                </svg>
              </div>
              <div class="cando-title">Read the Filing Guide</div>
              <div class="cando-desc">Understand the steps and documents needed to file. Read as reference, then coordinate with KTTM staff to submit.</div>
              <a href="{{ $urlHowTo }}" class="cando-link">
                Open Guide
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
              </a>
            </div>

          </div>

          {{-- Contact KTTM banner --}}
          <div class="access-banner anim anim-5">
            <div class="access-banner-icon">
              <svg width="22" height="22" fill="none" stroke="#2a1a0b" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
              </svg>
            </div>
            <div class="access-banner-text">
              <div class="access-banner-title">Need to file or correct a record?</div>
              <div class="access-banner-sub">Guests can't create or edit records directly. Reach out to KTTM and include your Record ID and a description.</div>
            </div>
            <button type="button" class="access-banner-btn" data-open-contact>
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
              </svg>
              Contact Support
            </button>
          </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="right-col">

          {{-- Access level card --}}
          <div class="access-card anim anim-2">
            <div class="access-card-inner">
              <div class="access-card-eyebrow">Your access level</div>
              <div class="access-card-level">Guest</div>
              <div class="access-card-sub">View-only · Read access</div>
              <div class="access-divider"></div>
              <div class="access-row">
                <span class="access-dot yes"></span>
                <span class="access-text">Search &amp; filter records</span>
                <span class="access-badge yes">Yes</span>
              </div>
              <div class="access-row">
                <span class="access-dot yes"></span>
                <span class="access-text">View record details</span>
                <span class="access-badge yes">Yes</span>
              </div>
              <div class="access-row">
                <span class="access-dot cond"></span>
                <span class="access-text">View attachments</span>
                <span class="access-badge cond">If enabled</span>
              </div>
              <div class="access-row">
                <span class="access-dot no"></span>
                <span class="access-text">Create or edit records</span>
                <span class="access-badge no">No</span>
              </div>
              <div class="access-row">
                <span class="access-dot no"></span>
                <span class="access-text">Approve or route filings</span>
                <span class="access-badge no">No</span>
              </div>
            </div>
          </div>

          {{-- Quick links --}}
          <div class="quicklinks-card anim anim-3">
            <div class="ql-header">
              <div class="ql-title">Quick Links</div>
            </div>
            <a href="{{ $urlGuestRec }}" class="ql-item">
              <div class="ql-icon maroon">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                </svg>
              </div>
              <div>
                <div class="ql-label">IP Records</div>
                <div class="ql-sub">Browse all filings</div>
              </div>
              <svg class="ql-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </a>
            <a href="{{ $urlHowTo }}" class="ql-item">
              <div class="ql-icon gold">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/>
                  <path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
                </svg>
              </div>
              <div>
                <div class="ql-label">Filing Guide</div>
                <div class="ql-sub">How to submit IP</div>
              </div>
              <svg class="ql-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </a>
            <a href="#" data-open-contact class="ql-item">
              <div class="ql-icon slate">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
              </div>
              <div>
                <div class="ql-label">Contact Support</div>
                <div class="ql-sub">Corrections &amp; enquiries</div>
              </div>
              <svg class="ql-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </a>
            <button type="button" id="openRules2" class="ql-item" style="background:none;border:none;width:100%;text-align:left;cursor:pointer;">
              <div class="ql-icon slate">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="12" y1="8" x2="12" y2="12"/>
                  <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
              </div>
              <div>
                <div class="ql-label">Guest Rules</div>
                <div class="ql-sub">What you're allowed to do</div>
              </div>
              <svg class="ql-arrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </button>
          </div>

          {{-- Tip --}}
          <div class="tip-card anim anim-4">
            <div class="tip-icon">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
              </svg>
            </div>
            <div>
              <div class="tip-title">Search Tip</div>
              <div class="tip-text">Use the filter bar on the Records page to narrow results by IP type, campus, or status for faster browsing.</div>
            </div>
          </div>

        </div>
      </div>

      {{-- FOOTER --}}
      <footer class="page-footer">
        <div class="page-footer-left">© {{ now()->year }} • KTTM Intellectual Property Services</div>
        <div class="page-footer-right">Guest Portal · View-only Access</div>
      </footer>

    </div>
  </div>

  {{-- GUEST RULES MODAL --}}
  <div class="modal-overlay" id="rulesModal">
    <div class="modal-box">
      <button type="button" class="modal-close-x" data-close-rules>
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <div class="modal-icon">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
      </div>
      <div class="modal-title">Guest Rules</div>
      <div class="modal-desc">Please review these guidelines before browsing records.</div>
      <div class="modal-rule-list">
        <div class="modal-rule-item">
          <div class="modal-rule-num">1</div>
          <div class="modal-rule-text">Guests can browse and view record details freely.</div>
        </div>
        <div class="modal-rule-item">
          <div class="modal-rule-num">2</div>
          <div class="modal-rule-text">Guests cannot create, edit, or delete records.</div>
        </div>
        <div class="modal-rule-item">
          <div class="modal-rule-num">3</div>
          <div class="modal-rule-text">For filing and corrections, contact KTTM via Support.</div>
        </div>
        <div class="modal-rule-item">
          <div class="modal-rule-num">4</div>
          <div class="modal-rule-text">Some attachments may be restricted depending on access policy.</div>
        </div>
      </div>
      <div class="modal-btns">
        <button type="button" class="btn-cancel" data-close-rules>Close</button>
        <button type="button" class="btn-confirm" data-close-rules>Understood</button>
      </div>
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
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
      </div>
      <div class="modal-title">Contact KTTM Support</div>
      <div class="modal-desc">For questions, corrections, or filing assistance, reach us directly.</div>
      <div style="margin-top:16px;background:var(--bg);border-radius:14px;padding:16px 18px;">
        <div style="font-size:.68rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;">Email</div>
        <a href="/cdn-cgi/l/email-protection#fa8181dade918e8e97bf979b9396da8787" style="font-size:.9rem;font-weight:800;color:var(--maroon);text-decoration:none;">
          {{ $kttmEmail }}
        </a>
        <div style="font-size:.72rem;color:var(--muted);margin-top:4px;">Include your Record ID and a description of the issue.</div>
      </div>
      <div class="modal-btns">
        <button type="button" class="btn-cancel" data-close-contact>Close</button>
        <a href="/cdn-cgi/l/email-protection#add6d68d89c6d9d9c0e8c0ccc4c18dd0d0" class="btn-confirm">Send Email</a>
      </div>
    </div>
  </div>

  <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
  (function(){
    function syncBodyScrollLock() {
      const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
      const anyModal = ['rulesModal', 'contactModal'].some(
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

    // Rules modal
    ['openRules', 'openRules2', 'openRulesTop', 'openRulesHero'].forEach(id =>
      document.getElementById(id)?.addEventListener('click', () => openModal('rulesModal'))
    );
    document.querySelectorAll('[data-close-rules]').forEach(b =>
      b.addEventListener('click', () => closeModal('rulesModal'))
    );
    document.getElementById('rulesModal')?.addEventListener('click', e => {
      if (e.target.id === 'rulesModal') closeModal('rulesModal');
    });

    // Contact modal
    document.querySelectorAll('[data-open-contact]').forEach(el =>
      el.addEventListener('click', e => { e.preventDefault(); openModal('contactModal'); })
    );
    document.querySelectorAll('[data-close-contact]').forEach(b =>
      b.addEventListener('click', () => closeModal('contactModal'))
    );
    document.getElementById('contactModal')?.addEventListener('click', e => {
      if (e.target.id === 'contactModal') closeModal('contactModal');
    });

    document.addEventListener('keydown', e => {
      if (e.key !== 'Escape') return;
      if (document.getElementById('rulesModal')?.classList.contains('open')) closeModal('rulesModal');
      else if (document.getElementById('contactModal')?.classList.contains('open')) closeModal('contactModal');
    });

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

    hamburgerBtn?.addEventListener('click', function(ev) {
      ev.stopPropagation();
      const isOpen = mainSidebar?.classList.contains('mobile-open');
      isOpen ? closeMobileSidebar() : openMobileSidebar();
    });

    sidebarBackdrop?.addEventListener('click', closeMobileSidebar);

    mainSidebar?.querySelectorAll('a.nav-item').forEach(link => {
      link.addEventListener('click', () => {
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