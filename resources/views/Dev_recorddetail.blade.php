{{-- resources/views/dev_recorddetail.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Record Detail (Dev)</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon:         #A52C30;
      --maroon2:        #7E1F23;
      --maroon-light:   rgba(165,44,48,0.12);
      --gold:           #F0C860;
      --gold2:          #E8B857;
      --bg:             #080C14;
      --bg2:            #0D1220;
      --bg3:            #111827;
      --card:           #0F1724;
      --card2:          #141E2E;
      --line:           rgba(255,255,255,0.06);
      --line2:          rgba(255,255,255,0.10);
      --ink:            #F1F5F9;
      --ink2:           #CBD5E1;
      --muted:          #64748B;
      --muted2:         #94A3B8;
      --sidebar-w:      72px;
      --dev-dark:       #0F172A;
      --dev-darker:     #080D14;
      --dev-blue:       #3B82F6;
      --dev-blue2:      #2563EB;
      --dev-blue-light: rgba(59,130,246,0.12);
      --dev-blue-mid:   rgba(59,130,246,0.22);
      --dev-green:      #10B981;
      --dev-green-light:rgba(16,185,129,0.10);
      --dev-red:        #EF4444;
      --dev-red-light:  rgba(239,68,68,0.10);
      --amber:          #F59E0B;
      --amber-dim:      rgba(245,158,11,0.12);

      --pad-x:          clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max:      1600px;
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

    /* ══════════════════════════════════════
       SIDEBAR — dev blue accent
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
      background: var(--dev-blue); border-radius: 0 3px 3px 0;
    }
    .nav-tooltip {
      position: absolute; left: calc(100% + 12px); top: 50%;
      transform: translateY(-50%); background: var(--bg3);
      border: 1px solid var(--line2);
      color: var(--ink2); font-size: 0.7rem; font-weight: 600;
      padding: 5px 10px; border-radius: 8px; white-space: nowrap;
      pointer-events: none; opacity: 0; transition: opacity .15s;
      letter-spacing: .04em; z-index: 999;
    }
    .nav-item:hover .nav-tooltip { opacity: 1; }
    .sidebar-nav { display: flex; flex-direction: column; align-items: center; gap: 6px; flex: 1; width: 100%; }
    .sidebar-bottom { display: flex; flex-direction: column; align-items: center; gap: 6px; }

    .hamburger-btn {
      display: none;
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg2); border: 1px solid var(--line2);
      align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted2);
      transition: all .18s; flex-shrink: 0;
      -webkit-tap-highlight-color: transparent;
    }
    .hamburger-btn:hover { background: var(--dev-blue-light); border-color: var(--dev-blue-mid); color: var(--dev-blue); }
    .sidebar-backdrop {
      display: none;
      position: fixed; inset: 0; z-index: 49;
      background: rgba(0,0,0,.6);
      backdrop-filter: blur(3px);
      -webkit-tap-highlight-color: transparent;
    }
    .sidebar-backdrop.open { display: block; }

    /* ══════════════════════════════════════
       MAIN LAYOUT
    ══════════════════════════════════════ */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ══════════════════════════════════════
       TOPBAR
    ══════════════════════════════════════ */
    .topbar {
      min-height: 64px; background: var(--card);
      border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 8px var(--pad-x); position: sticky; top: 0; z-index: 40;
      box-shadow: 0 1px 24px rgba(0,0,0,.3);
    }
    .topbar-left {
      display: flex; align-items: center; gap: 10px 12px;
      flex-wrap: wrap; min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; flex: 1 1 160px; }
    .topbar-right {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto; min-width: 0; max-width: 100%;
      justify-content: flex-end;
    }
    .back-btn {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg2); border: 1.5px solid var(--line2);
      display: flex; align-items: center; justify-content: center;
      color: var(--muted2); text-decoration: none; transition: all .18s;
      flex-shrink: 0;
    }
    .back-btn:hover { background: var(--dev-blue-light); border-color: var(--dev-blue); color: var(--dev-blue); }
    .page-title {
      font-size: clamp(0.92rem, 0.4vw + 0.82rem, 1.15rem);
      font-weight: 800; letter-spacing: -.3px; color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-subtitle {
      font-size: clamp(0.68rem, 0.15vw + 0.64rem, 0.75rem);
      color: var(--muted); font-weight: 500;
      overflow-wrap: anywhere;
    }
    .record-id-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--dev-blue-light); border: 1.5px solid var(--dev-blue-mid);
      color: var(--dev-blue); padding: 5px 12px; border-radius: 12px;
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.62rem, 0.1vw + 0.58rem, 0.7rem); font-weight: 700;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
      overflow-wrap: anywhere;
    }
    .dev-mode-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--dev-blue-light); border: 1.5px solid var(--dev-blue-mid);
      border-radius: 20px; padding: 5px 12px;
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.55rem, 0.1vw + 0.52rem, 0.62rem);
      font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: var(--dev-blue);
      flex: 0 1 auto; max-width: 100%;
    }
    .readonly-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--amber-dim); border: 1.5px solid rgba(245,158,11,.25);
      border-radius: 20px; padding: 5px 12px;
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.55rem, 0.1vw + 0.52rem, 0.62rem);
      font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: var(--amber);
      flex: 0 1 auto; max-width: 100%;
    }

    /* ══════════════════════════════════════
       CONTENT
    ══════════════════════════════════════ */
    .content {
      padding: clamp(14px, 2.5vw, 18px) var(--pad-x);
      flex: 1; display: flex; flex-direction: column; min-height: 0;
      background: var(--bg);
      background-size: cover;
      width: 100%; max-width: var(--shell-max); margin: 0 auto;
      box-sizing: border-box;
    }

    /* ══════════════════════════════════════
       HERO BANNER — dark blue terminal feel
    ══════════════════════════════════════ */
    .hero-card {
      background: linear-gradient(135deg, #0F172A 0%, #1a2744 55%, #0F172A 100%);
      border-radius: 24px;
      padding: clamp(18px, 3vw, 24px) clamp(18px, 3vw, 28px);
      box-shadow: 0 12px 40px rgba(15,23,42,.30);
      position: relative; overflow: hidden; margin-bottom: 20px;
    }
    /* terminal grid */
    .hero-card::before {
      content: ''; position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(59,130,246,.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59,130,246,.05) 1px, transparent 1px);
      background-size: 32px 32px;
    }
    /* blue glow orb */
    .hero-card::after {
      content: ''; position: absolute; bottom: -60px; left: 35%;
      width: 280px; height: 280px; border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,.10), transparent 70%);
      pointer-events: none;
    }
    .hero-inner { position: relative; z-index: 1; min-width: 0; }
    .hero-eyebrow {
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.62rem, 0.12vw + 0.58rem, 0.68rem);
      font-weight: 800; letter-spacing: .1em;
      text-transform: uppercase; color: var(--dev-blue); opacity: .85; margin-bottom: 6px;
      overflow-wrap: anywhere;
    }
    .hero-title {
      font-size: clamp(1.05rem, 1.8vw + 0.6rem, 1.25rem);
      font-weight: 800; color: #fff;
      letter-spacing: -.3px; margin-bottom: 8px; line-height: 1.3;
      overflow-wrap: anywhere;
    }
    .hero-badges { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 12px; border-radius: 20px;
      font-size: 0.68rem; font-weight: 700; letter-spacing: .04em; border: 1px solid transparent;
    }
    .hb-type   { background: var(--dev-blue-mid); color: #93C5FD; border-color: rgba(59,130,246,.3); }
    .hb-campus { background: rgba(96,200,240,.10); color: #7dd3fc; border-color: rgba(96,200,240,.2); }
    .hb-status { background: rgba(52,211,153,.12); color: #34d399; border-color: rgba(52,211,153,.22); }
    .hb-status.st-attention { background: rgba(251,146,60,.12); color: #fb923c; border-color: rgba(251,146,60,.22); }
    .hb-status.st-default   { background: rgba(148,163,184,.10); color: #94a3b8; border-color: rgba(148,163,184,.2); }

    /* hero fields strip */
    .hero-fields {
      display: grid; grid-template-columns: repeat(5, minmax(0, 1fr));
      gap: 1px; background: rgba(59,130,246,.15);
      border-radius: 16px; overflow: hidden;
      border: 1px solid rgba(59,130,246,.2); position: relative; z-index: 2; margin-bottom: 10px;
    }
    .hero-field { background: rgba(0,0,0,.25); padding: 13px 16px; transition: background .2s; }
    .hero-field:hover { background: rgba(59,130,246,.08); }
    .hf-label {
      font-family: 'DM Mono', monospace;
      font-size: 0.56rem; font-weight: 700; letter-spacing: .16em;
      text-transform: uppercase; color: rgba(255,255,255,.35); margin-bottom: 5px;
    }
    .hf-value {
      font-size: clamp(0.76rem, 0.2vw + 0.7rem, 0.82rem);
      font-weight: 700; color: rgba(255,255,255,.9);
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      min-width: 0;
    }
    .hf-value.empty { color: rgba(255,255,255,.2); font-style: italic; font-weight: 400; }
    .hf-value a { color: #93C5FD; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
    .hf-value a:hover { text-decoration: underline; }
    .hero-owner-row {
      background: rgba(0,0,0,.2); border-radius: 12px; padding: 11px 16px;
      border: 1px solid rgba(59,130,246,.15); position: relative; z-index: 2;
    }
    .hero-owner-row .hf-value { white-space: normal; }

    /* ══════════════════════════════════════
       MAIN GRID
    ══════════════════════════════════════ */
    .main-grid { display: grid; grid-template-columns: 260px 1fr; gap: 16px; align-items: start; flex: 1; min-height: 0; }

    /* ══════════════════════════════════════
       LEFT PANEL
    ══════════════════════════════════════ */
    .info-panel { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 88px; align-self: start; }
    .panel-card {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line); box-shadow: 0 4px 20px rgba(0,0,0,.2); overflow: hidden;
    }
    .panel-card-header {
      padding: 16px 18px; border-bottom: 1px solid var(--line);
      background: linear-gradient(90deg, rgba(59,130,246,.06), rgba(0,0,0,.02));
    }
    .panel-card-title {
      font-size: clamp(0.8rem, 0.2vw + 0.74rem, 0.85rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .panel-card-sub   { font-size: 0.7rem; color: var(--muted); margin-top: 2px; overflow-wrap: anywhere; }
    .panel-body { padding: 14px 16px; display: flex; flex-direction: column; }
    .stat-row {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 6px 12px;
      padding: 9px 0; border-bottom: 1px solid var(--line);
    }
    .stat-row:last-child { border-bottom: none; }
    .stat-label { font-size: 0.76rem; font-weight: 600; color: var(--ink); flex: 1 1 auto; min-width: 0; overflow-wrap: anywhere; }
    .stat-val   {
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.68rem, 0.12vw + 0.64rem, 0.72rem);
      font-weight: 700; color: var(--dev-blue);
      text-align: right;
      flex: 0 1 auto;
      min-width: 0;
      max-width: min(180px, 100%);
      overflow-wrap: anywhere;
      word-break: break-word;
    }
    .stat-val.empty { color: var(--muted2); font-style: italic; }
    .access-row {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 6px 10px;
      padding: 8px 0; border-bottom: 1px solid var(--line);
    }
    .access-row:last-child { border-bottom: none; }
    .access-yes  { font-size: .65rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: var(--dev-green-light); color: var(--dev-green); }
    .access-no   { font-size: .65rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: var(--dev-red-light); color: var(--dev-red); }
    .access-cond { font-size: .65rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: rgba(245,158,11,.1); color: #d97706; }

    /* ══════════════════════════════════════
       DETAILS CARD
    ══════════════════════════════════════ */
    .details-card {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line); box-shadow: 0 4px 24px rgba(0,0,0,.25); overflow: hidden;
      display: flex; flex-direction: column;
    }
    .dc-header {
      padding: clamp(16px, 2.5vw, 20px) clamp(16px, 2.5vw, 24px); border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      background: linear-gradient(90deg, rgba(59,130,246,.06), rgba(0,0,0,.02));
    }
    .dc-header > div:first-child { min-width: 0; flex: 1 1 200px; }
    .dc-title {
      font-size: clamp(0.9rem, 0.35vw + 0.82rem, 1rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .dc-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 2px; overflow-wrap: anywhere; }
    .dc-badge {
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.62rem, 0.1vw + 0.58rem, 0.68rem);
      font-weight: 700;
      color: var(--dev-blue); background: var(--dev-blue-light);
      border: 1.5px solid var(--dev-blue-mid); padding: 4px 12px; border-radius: 20px;
      flex-shrink: 0; max-width: 100%; overflow-wrap: anywhere;
    }
    .dc-body {
      padding: clamp(16px, 2.5vw, 22px);
      display: flex; flex-direction: column;
      gap: clamp(16px, 2.5vw, 22px);
      flex: 1;
    }
    .detail-section { display: flex; flex-direction: column; gap: 10px; }
    .detail-section-title {
      font-family: 'DM Mono', monospace; font-size: 0.62rem; font-weight: 700;
      letter-spacing: .12em; text-transform: uppercase; color: var(--dev-blue);
      padding-bottom: 7px; border-bottom: 2px solid var(--dev-blue-light);
    }
    .detail-grid      { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .detail-grid.full { grid-template-columns: 1fr; }
    .detail-grid.three { grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
    .detail-item {
      background: var(--bg2); border-radius: 14px;
      border: 1.5px solid var(--line); padding: 13px 15px;
      transition: border-color .18s, box-shadow .18s;
    }
    .detail-item:hover { border-color: var(--dev-blue-mid); box-shadow: 0 3px 14px var(--dev-blue-light); }
    .di-label {
      font-family: 'DM Mono', monospace; font-size: 0.58rem; font-weight: 700;
      letter-spacing: .12em; text-transform: uppercase; color: var(--muted2); margin-bottom: 5px;
    }
    .di-value {
      font-size: clamp(0.78rem, 0.2vw + 0.72rem, 0.84rem);
      font-weight: 700; color: var(--ink);
      overflow-wrap: anywhere; word-break: break-word;
    }
    .di-value.empty { color: var(--muted2); font-style: italic; font-weight: 400; font-size: .8rem; }
    .di-value a { color: var(--dev-blue); text-decoration: underline; font-weight: 700; }
    .di-value a:hover { opacity: .8; }

    /* IP info box */
    .ip-info-box {
      background: linear-gradient(135deg, var(--dev-blue-light), rgba(0,0,0,.02));
      border: 1.5px solid var(--dev-blue-mid); border-radius: 16px;
      padding: clamp(14px, 2.5vw, 18px) clamp(14px, 2.5vw, 20px);
      display: flex; flex-wrap: wrap;
      gap: 16px; align-items: flex-start;
    }
    .ip-info-icon {
      width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
      background: var(--dev-blue-light);
      display: flex; align-items: center; justify-content: center; color: var(--dev-blue);
    }
    .ip-info-body { flex: 1; min-width: 0; }
    .ip-info-type { font-size: clamp(0.82rem, 0.2vw + 0.76rem, 0.88rem); font-weight: 800; color: var(--dev-blue); margin-bottom: 4px; overflow-wrap: anywhere; }
    .ip-info-desc { font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.78rem); color: var(--ink2); line-height: 1.6; overflow-wrap: anywhere; }
    .ip-info-tags { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 10px; }
    .ip-tag { font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; background: var(--dev-blue-light); color: var(--dev-blue); border: 1px solid var(--dev-blue-mid); }

    /* Remarks box */
    .remarks-box { background: var(--bg2); border: 1.5px solid var(--line); border-radius: 14px; padding: 14px 16px; }
    .remarks-text  { font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem); color: var(--ink); line-height: 1.7; overflow-wrap: anywhere; }
    .remarks-empty { font-size: 0.78rem; color: var(--muted2); font-style: italic; }

    /* ══════════════════════════════════════
       ACTIVITY LOG PANEL
    ══════════════════════════════════════ */
    .activity-item {
      display: flex; align-items: flex-start; gap: 12px;
      padding: 10px 0; border-bottom: 1px solid var(--line);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot { width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
    .dot-create { background: var(--dev-green); }
    .dot-update { background: var(--dev-blue); }
    .dot-delete { background: var(--dev-red); }
    .dot-other  { background: var(--muted); }
    .activity-info { flex: 1; min-width: 0; }
    .activity-action { font-size: clamp(0.74rem, 0.12vw + 0.7rem, 0.78rem); font-weight: 700; color: var(--ink); overflow-wrap: anywhere; }
    .activity-by    { font-size: 0.68rem; color: var(--muted2); margin-top: 2px; font-family: 'DM Mono', monospace; }
    .activity-time  {
      font-size: clamp(0.6rem, 0.08vw + 0.56rem, 0.65rem);
      color: var(--muted2);
      white-space: nowrap; font-family: 'DM Mono', monospace;
      flex-shrink: 0;
    }

    /* ══════════════════════════════════════
       LOGOUT MODAL
    ══════════════════════════════════════ */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(0,0,0,.7); backdrop-filter: blur(8px);
      display: none; align-items: center; justify-content: center;
      padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: var(--card); border: 1px solid var(--line2); border-radius: 24px;
      padding: clamp(20px, 4vw, 32px);
      width: min(440px, calc(100vw - 2rem)); max-width: 100%;
      box-shadow: 0 32px 80px rgba(0,0,0,.5);
      animation: fadeUp .3s forwards;
      box-sizing: border-box;
    }
    .modal-icon { width: 52px; height: 52px; border-radius: 16px; background: var(--maroon-light); color: var(--maroon); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
    .modal-title { font-size: clamp(1rem, 0.35vw + 0.92rem, 1.1rem); font-weight: 800; color: var(--ink); margin-bottom: 6px; overflow-wrap: anywhere; }
    .modal-desc  { font-size: 0.82rem; color: var(--muted); line-height: 1.6; margin-bottom: 22px; overflow-wrap: anywhere; }
    .modal-actions { display: flex; flex-wrap: wrap; gap: 10px; align-items: stretch; }
    .modal-actions .btn-cancel { flex: 1 1 120px; min-width: 0; justify-content: center; display: inline-flex; align-items: center; }
    .modal-actions form { flex: 1 1 140px; min-width: 0; display: flex; }
    .modal-actions form .btn-logout-confirm { width: 100%; justify-content: center; display: inline-flex; align-items: center; }
    .btn-cancel {
      padding: 11px; border-radius: 12px;
      background: var(--bg2); border: 1.5px solid var(--line2);
      font-family: inherit;
      font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem);
      font-weight: 700;
      color: var(--muted2); cursor: pointer; transition: all .18s;
      box-sizing: border-box;
    }
    .btn-cancel:hover { border-color: var(--dev-blue); color: var(--dev-blue); background: var(--dev-blue-light); }
    .btn-logout-confirm {
      padding: 11px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      border: none; font-family: inherit;
      font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem);
      font-weight: 700;
      color: #fff; cursor: pointer;
      box-shadow: 0 6px 16px rgba(165,44,48,.26); transition: all .18s;
      box-sizing: border-box;
    }
    .btn-logout-confirm:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(165,44,48,.34); }

    /* ══════════════════════════════════════
       FOOTER
    ══════════════════════════════════════ */
    .page-footer {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 16px;
      padding: 16px 0 4px; margin-top: 16px;
      border-top: 1px solid var(--line);
      font-size: clamp(0.66rem, 0.12vw + 0.62rem, 0.72rem);
      color: var(--muted2); font-weight: 500;
    }
    .page-footer > div { min-width: 0; overflow-wrap: anywhere; }

    /* animations */
    @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:none} }
    .anim { opacity:0; animation: fadeUp .5s forwards; }
    .anim-1 { animation-delay:.05s; }
    .anim-2 { animation-delay:.12s; }
    .anim-3 { animation-delay:.19s; }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 999px; }

    @media (max-width: 960px) {
      .main-grid { grid-template-columns: 1fr; }
      .info-panel { position: static; }
      .hero-fields { grid-template-columns: repeat(3, minmax(0, 1fr)); }
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
        background: none; color: rgba(255,255,255,.75);
        font-size: 0.78rem; font-weight: 600;
        padding: 0; border-radius: 0; pointer-events: auto;
        letter-spacing: .01em; white-space: normal;
        overflow-wrap: anywhere; line-height: 1.35;
        border: none;
      }
      .sidebar-bottom { width: 100%; align-items: flex-start; }
      .main-wrap { margin-left: 0; }
      .hamburger-btn { display: flex; }
      .topbar { min-height: 58px; }
      .page-subtitle { display: none; }
      .activity-item { flex-wrap: wrap; }
      .activity-time { white-space: normal; width: 100%; margin-left: 20px; margin-top: 2px; }
    }
    @media (max-width: 640px) {
      .hero-fields { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .detail-grid { grid-template-columns: 1fr; }
      .detail-grid.three { grid-template-columns: 1fr; }
      .hf-value { white-space: normal; overflow-wrap: anywhere; }
    }
    @media (max-width: 480px) {
      .hero-fields { grid-template-columns: minmax(0, 1fr); }
      .hero-badge { font-size: clamp(0.62rem, 0.08vw + 0.58rem, 0.68rem); }
      .modal-actions { flex-direction: column; }
      .modal-actions .btn-cancel,
      .modal-actions form { flex: 0 0 auto; width: 100%; }
    }
  </style>
</head>
<body>

@php
  $user   = $user   ?? (object)['name' => 'Developer', 'role' => 'developer'];
  $record = $record ?? null;

  $userAvatarImage = session('user_avatar_image', null);
  $userInitials    = collect(explode(' ', $user->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');

  $recordId = $recordId
      ?? request()->route('recordId')
      ?? request()->route('record_id')
      ?? request()->route('id')
      ?? request()->query('record_id');

  $statusClass = match(strtolower($record['status'] ?? '')) {
    'registered'      => 'hb-status',
    'under review'    => 'hb-status st-attention',
    'filed'           => 'hb-status st-attention',
    'needs attention' => 'hb-status st-attention',
    default           => 'hb-status st-default',
  };

  // IP type descriptions
  $ipDescriptions = [
    'patent'            => ['desc' => 'A patent grants an inventor exclusive rights to their invention for a limited period. It covers new and useful processes, machines, manufactures, or compositions of matter.', 'validity' => '20 years', 'tags' => ['Exclusive Rights', '20-Year Term', 'IPOPHL Filing']],
    'copyright'         => ['desc' => 'Copyright protects original works of authorship including literary, artistic, musical, and other creative works from the moment of creation.', 'validity' => '70 years', 'tags' => ['Automatic Protection', '70-Year Term', 'Creative Works']],
    'utility model'     => ['desc' => 'A utility model (petty patent) protects technical innovations that may not meet the full novelty requirements of a standard patent. Faster to obtain.', 'validity' => '10 years', 'tags' => ['Technical Innovation', '10-Year Term', 'Faster Process']],
    'industrial design' => ['desc' => 'An industrial design right protects the visual or ornamental aspects of a product — its shape, color, texture, or pattern.', 'validity' => '15 years', 'tags' => ['Visual Design', '15-Year Term', 'Aesthetic Protection']],
  ];
  $typeLower = strtolower(trim($record['type'] ?? ''));
  $ipInfo    = $ipDescriptions[$typeLower] ?? null;

  // Validity & due date calculation
  $dueDate  = '—';
  $validity = '—';
  $validityMap = ['patent'=>'20 years','copyright'=>'70 years','utility model'=>'10 years','industrial design'=>'15 years'];
  $yearsMap    = ['patent'=>20,'copyright'=>70,'utility model'=>10,'industrial design'=>15];

  if (!empty($record['type'])) {
    $validity = $validityMap[$typeLower] ?? '—';
  }

  $rawReg = $record['registered'] ?? null;
  if (!empty($rawReg)) {
    try {
      $d     = ($rawReg instanceof \Carbon\Carbon) ? $rawReg->copy() : \Carbon\Carbon::parse((string)$rawReg);
      $years = $yearsMap[$typeLower] ?? null;
      if ($years) $dueDate = $d->addYears($years)->format('M d, Y');
    } catch (\Exception $e) { $dueDate = '—'; }
  }

  $registeredFormatted = '—';
  if (!empty($rawReg)) {
    try {
      $registeredFormatted = ($rawReg instanceof \Carbon\Carbon)
        ? $rawReg->format('M d, Y')
        : \Carbon\Carbon::parse((string)$rawReg)->format('M d, Y');
    } catch (\Exception $e) {}
  }

  $registeredFormattedLong = '—';
  if (!empty($rawReg)) {
    try {
      $registeredFormattedLong = ($rawReg instanceof \Carbon\Carbon)
        ? $rawReg->format('F d, Y')
        : \Carbon\Carbon::parse((string)$rawReg)->format('F d, Y');
    } catch (\Exception $e) {}
  }

  $dateCreationFormatted = '—';
  if (!empty($record['date_creation'])) {
    try {
      $dateCreationFormatted = \Carbon\Carbon::parse((string)$record['date_creation'])->format('F d, Y');
    } catch (\Exception $e) {}
  }
@endphp

<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

{{-- ══════════════ SIDEBAR ══════════════ --}}
<aside class="sidebar" id="mainSidebar" aria-label="Main navigation">

  {{-- Avatar --}}
  <div class="nav-item" style="margin-bottom:20px;width:42px;height:42px;border-radius:14px;
    {{ $userAvatarImage ? 'background:transparent;' : 'background:linear-gradient(135deg,var(--dev-blue2),var(--dev-blue));' }}
    font-weight:800;font-size:0.78rem;color:#fff;
    box-shadow:0 6px 18px rgba(59,130,246,.35);cursor:default;flex-shrink:0;overflow:hidden;padding:0;">
    @if($userAvatarImage)
      <img src="{{ asset('storage/avatars/' . $userAvatarImage) }}" alt="{{ $userInitials }}"
           style="width:42px;height:42px;object-fit:cover;border-radius:14px;display:block;">
    @else
      {{ $userInitials }}
    @endif
    <span class="nav-tooltip" style="min-width:140px;line-height:1.5;">
      {{ $user->name }}<br>
      <span style="opacity:.65;font-weight:500;letter-spacing:.06em;text-transform:uppercase;font-size:.6rem;">Developer</span>
    </span>
  </div>

  <nav class="sidebar-nav">
    <a href="{{ url('/dev') }}" class="nav-item">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
      </svg>
      <span class="nav-tooltip">Dev Dashboard</span>
    </a>
    <a href="{{ url('/dev/records') }}" class="nav-item active">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <span class="nav-tooltip">Records (Read-only)</span>
    </a>
  </nav>

  <div class="sidebar-bottom">
    <a href="{{ url('/dev/profile') }}" class="nav-item">
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
      <button class="hamburger-btn" id="hamburgerBtn" type="button" aria-label="Open navigation menu" aria-expanded="false">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <line x1="3" y1="6"  x2="21" y2="6"/>
          <line x1="3" y1="12" x2="21" y2="12"/>
          <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
      </button>
      <a href="{{ url('/dev/records') }}" class="back-btn" title="Back to Records">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </a>
      <div class="topbar-titles">
        <div class="page-title">Record Detail</div>
        <div class="page-subtitle">IP record — developer view</div>
      </div>
      @if($recordId)
        <div class="record-id-pill">
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="3"/>
            <line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="15" x2="13" y2="15"/>
          </svg>
          {{ $recordId }}
        </div>
      @endif
    </div>
    <div class="topbar-right">
      <div class="readonly-pill">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
        Read-only
      </div>
      <div class="dev-mode-pill">Developer</div>
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

    {{-- HERO --}}
    <div class="hero-card anim anim-1">
      <div class="hero-inner">
        <div class="hero-eyebrow">IP Record · {{ $recordId ?? 'Unknown' }}</div>
        <div class="hero-title">
          {{ !empty($record['title']) ? $record['title'] : 'Record not found' }}
        </div>
        <div class="hero-badges">
          @if(!empty($record['type']))
            <span class="hero-badge hb-type">
              <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
              </svg>
              {{ $record['type'] }}
            </span>
          @endif
          @if(!empty($record['campus']))
            <span class="hero-badge hb-campus">
              <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
              </svg>
              {{ $record['campus'] }}
            </span>
          @endif
          @if(!empty($record['status']))
            <span class="hero-badge {{ $statusClass }}">
              <svg width="9" height="9" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9"/>
              </svg>
              {{ $record['status'] }}
            </span>
          @endif
        </div>

        {{-- Fields strip --}}
        <div class="hero-fields">
          <div class="hero-field">
            <div class="hf-label">Registration Number</div>
            <div class="hf-value @if(empty($record['registration_number'])) empty @endif">
              {{ $record['registration_number'] ?? '—' }}
            </div>
          </div>
          <div class="hero-field">
            <div class="hf-label">Date Registered</div>
            <div class="hf-value @if($registeredFormatted === '—') empty @endif">
              {{ $registeredFormatted }}
            </div>
          </div>
          <div class="hero-field">
            <div class="hf-label">Next Due</div>
            <div class="hf-value @if($dueDate === '—') empty @endif">{{ $dueDate }}</div>
          </div>
          <div class="hero-field">
            <div class="hf-label">Validity</div>
            <div class="hf-value @if($validity === '—') empty @endif">{{ $validity }}</div>
          </div>
          <div class="hero-field">
            <div class="hf-label">GDrive File</div>
            <div class="hf-value @if(empty($record['gdrive_link'])) empty @endif">
              @if(!empty($record['gdrive_link']))
                <a href="{{ $record['gdrive_link'] }}" target="_blank" rel="noopener">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                  </svg>
                  Open File ↗
                </a>
              @else
                No file attached
              @endif
            </div>
          </div>
        </div>

        {{-- Owner row --}}
        <div class="hero-owner-row">
          <div class="hf-label">Owner / Inventor(s)</div>
          <div class="hf-value @if(empty($record['owner'])) empty @endif">
            {{ !empty($record['owner']) ? $record['owner'] : 'No owner information on record' }}
          </div>
        </div>

      </div>{{-- /hero-inner --}}
    </div>{{-- /hero-card --}}

    {{-- MAIN GRID --}}
    <div class="main-grid anim anim-2">

      {{-- LEFT PANEL --}}
      <aside class="info-panel">

        {{-- Record Summary --}}
        <div class="panel-card">
          <div class="panel-card-header">
            <div class="panel-card-title">Record Summary</div>
            <div class="panel-card-sub">Key details at a glance</div>
          </div>
          <div class="panel-body">
            <div class="stat-row">
              <span class="stat-label">Record ID</span>
              <span class="stat-val">{{ $recordId ?? '—' }}</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">IP Type</span>
              <span class="stat-val {{ empty($record['type']) ? 'empty' : '' }}">{{ $record['type'] ?? '—' }}</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Campus</span>
              <span class="stat-val {{ empty($record['campus']) ? 'empty' : '' }}">{{ $record['campus'] ?? '—' }}</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Status</span>
              <span class="stat-val {{ empty($record['status']) ? 'empty' : '' }}">{{ $record['status'] ?? '—' }}</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Validity</span>
              <span class="stat-val {{ $validity === '—' ? 'empty' : '' }}">{{ $validity }}</span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Date Created</span>
              <span class="stat-val {{ $dateCreationFormatted === '—' ? 'empty' : '' }}">{{ $dateCreationFormatted }}</span>
            </div>
          </div>
        </div>

        {{-- Dev Access Level --}}
        <div class="panel-card">
          <div class="panel-card-header">
            <div class="panel-card-title">Dev Access Level</div>
            <div class="panel-card-sub">Your permissions on this record</div>
          </div>
          <div class="panel-body">
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">View full details</span>
              <span class="access-yes">Yes</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">Open GDrive file</span>
              <span class="access-yes">Yes</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">View activity log</span>
              <span class="access-yes">Yes</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">Edit this record</span>
              <span class="access-no">No</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">Create new records</span>
              <span class="access-no">No</span>
            </div>
          </div>
        </div>

        {{-- Activity Log --}}
        <div class="panel-card">
          <div class="panel-card-header">
            <div class="panel-card-title">Activity Log</div>
            <div class="panel-card-sub">Changes to this record</div>
          </div>
          <div class="panel-body" style="padding-top:10px;">
            @if($activityLog->isEmpty())
              <p style="font-size:.78rem;color:var(--muted);padding:8px 0;">No activity logged for this record.</p>
            @else
              @foreach($activityLog as $log)
              @php
                $dotClass = match(strtolower($log['action'] ?? '')) {
                  'created','added'           => 'dot-create',
                  'updated','edited','changed' => 'dot-update',
                  'deleted','removed'         => 'dot-delete',
                  default                     => 'dot-other',
                };
              @endphp
              <div class="activity-item">
                <span class="activity-dot {{ $dotClass }}"></span>
                <div class="activity-info">
                  <div class="activity-action">{{ $log['action'] ?? '—' }}</div>
                  <div class="activity-by">by {{ $log['user_name'] }}</div>
                </div>
                <div class="activity-time">{{ $log['time_ago'] }}</div>
              </div>
              @endforeach
            @endif
          </div>
        </div>

      </aside>

      {{-- RIGHT: full details card --}}
      <div class="details-card anim anim-3">
        <div class="dc-header">
          <div>
            <div class="dc-title">Full Record Information</div>
            <div class="dc-sub">Complete details for this IP record — developer view</div>
          </div>
          <div class="dc-badge"># {{ $recordId ?? '—' }}</div>
        </div>

        <div class="dc-body">

          {{-- Section 1: Core Information --}}
          <div class="detail-section">
            <div class="detail-section-title">Core Information</div>
            <div class="detail-grid full">
              <div class="detail-item">
                <div class="di-label">IP Title</div>
                <div class="di-value {{ empty($record['title']) ? 'empty' : '' }}">
                  {{ $record['title'] ?? 'No title available' }}
                </div>
              </div>
            </div>
            <div class="detail-grid">
              <div class="detail-item">
                <div class="di-label">IP Type / Category</div>
                <div class="di-value {{ empty($record['type']) ? 'empty' : '' }}">{{ $record['type'] ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Current Status</div>
                <div class="di-value {{ empty($record['status']) ? 'empty' : '' }}">{{ $record['status'] ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Campus / Unit</div>
                <div class="di-value {{ empty($record['campus']) ? 'empty' : '' }}">{{ $record['campus'] ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Registration Number</div>
                <div class="di-value {{ empty($record['registration_number']) ? 'empty' : '' }}">{{ $record['registration_number'] ?? '—' }}</div>
              </div>
            </div>
          </div>

          {{-- Section 2: Extended Academic Fields --}}
          <div class="detail-section">
            <div class="detail-section-title">Academic Classification</div>
            <div class="detail-grid three">
              <div class="detail-item">
                <div class="di-label">College</div>
                <div class="di-value {{ empty($record['college']) ? 'empty' : '' }}">{{ $record['college'] ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Program</div>
                <div class="di-value {{ empty($record['program']) ? 'empty' : '' }}">{{ $record['program'] ?? '—' }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Class of Work</div>
                <div class="di-value {{ empty($record['class_of_work']) ? 'empty' : '' }}">{{ $record['class_of_work'] ?? '—' }}</div>
              </div>
            </div>
          </div>

          {{-- Section 3: Dates & Timeline --}}
          <div class="detail-section">
            <div class="detail-section-title">Dates &amp; Timeline</div>
            <div class="detail-grid">
              <div class="detail-item">
                <div class="di-label">Date Created</div>
                <div class="di-value {{ $dateCreationFormatted === '—' ? 'empty' : '' }}">{{ $dateCreationFormatted }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Date Registered</div>
                <div class="di-value {{ $registeredFormattedLong === '—' ? 'empty' : '' }}">{{ $registeredFormattedLong }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Validity Period</div>
                <div class="di-value {{ $validity === '—' ? 'empty' : '' }}">{{ $validity }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Estimated Expiry</div>
                <div class="di-value {{ $dueDate === '—' ? 'empty' : '' }}">{{ $dueDate }}</div>
              </div>
            </div>
            <div class="detail-grid full">
              <div class="detail-item">
                <div class="di-label">GDrive Document</div>
                <div class="di-value {{ empty($record['gdrive_link']) ? 'empty' : '' }}">
                  @if(!empty($record['gdrive_link']))
                    <a href="{{ $record['gdrive_link'] }}" target="_blank" rel="noopener">Open attached file ↗</a>
                  @else
                    No document attached
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Section 4: Owner --}}
          <div class="detail-section">
            <div class="detail-section-title">Owner / Inventor(s)</div>
            <div class="detail-grid full">
              <div class="detail-item">
                <div class="di-label">Registered Owner or Inventor(s)</div>
                <div class="di-value {{ empty($record['owner']) ? 'empty' : '' }}" style="white-space:normal;line-height:1.6;">
                  {{ !empty($record['owner']) ? $record['owner'] : 'No owner information available for this record' }}
                </div>
              </div>
            </div>
          </div>

          {{-- Section 5: Remarks --}}
          <div class="detail-section">
            <div class="detail-section-title">Remarks</div>
            <div class="remarks-box">
              @if(!empty($record['remarks']))
                <div class="remarks-text">{{ $record['remarks'] }}</div>
              @else
                <div class="remarks-empty">No remarks on file for this record.</div>
              @endif
            </div>
          </div>

          {{-- Section 6: IP Type Info --}}
          @if($ipInfo)
          <div class="detail-section">
            <div class="detail-section-title">About This IP Type</div>
            <div class="ip-info-box">
              <div class="ip-info-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/>
                  <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div class="ip-info-body">
                <div class="ip-info-type">{{ $record['type'] }}</div>
                <div class="ip-info-desc">{{ $ipInfo['desc'] }}</div>
                <div class="ip-info-tags">
                  @foreach($ipInfo['tags'] as $tag)
                    <span class="ip-tag">{{ $tag }}</span>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
          @endif

        </div>{{-- /dc-body --}}
      </div>{{-- /details-card --}}

    </div>{{-- /main-grid --}}

    <footer class="page-footer">
      <div>© {{ now()->year }} · KTTM Intellectual Property Services</div>
      <div>Record Detail · Developer View</div>
    </footer>

  </div>{{-- /content --}}
</div>{{-- /main-wrap --}}

{{-- LOGOUT MODAL --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal-box">
    <div class="modal-icon">
      <svg width="22" height="22" fill="none" stroke="var(--maroon)" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>
    <div class="modal-title">Sign Out?</div>
    <div class="modal-desc">You'll be returned to the login page.</div>
    <div class="modal-actions">
      <button type="button" class="btn-cancel" id="cancelLogout">Cancel</button>
      <form method="POST" action="{{ url('/logout') }}">
        @csrf
        <button type="submit" class="btn-logout-confirm">Sign Out</button>
      </form>
    </div>
  </div>
</div>

<script>
(function(){
  const SCROLL_LOCK_MODAL_IDS = ['logoutModal'];

  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const anyModal = SCROLL_LOCK_MODAL_IDS.some(id => document.getElementById(id)?.classList.contains('open'));
    document.body.style.overflow = (sidebarOpen || anyModal) ? 'hidden' : '';
  }

  function openModal(id)  { document.getElementById(id)?.classList.add('open');    syncBodyScrollLock(); }
  function closeModal(id) { document.getElementById(id)?.classList.remove('open'); syncBodyScrollLock(); }

  document.getElementById('logoutBtn')?.addEventListener('click', ()=>openModal('logoutModal'));
  document.getElementById('cancelLogout')?.addEventListener('click', ()=>closeModal('logoutModal'));
  document.getElementById('logoutModal')?.addEventListener('click', e=>{ if(e.target===e.currentTarget) closeModal('logoutModal'); });

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

  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    const lo = document.getElementById('logoutModal');
    if (lo?.classList.contains('open')) { closeModal('logoutModal'); return; }
    if (mainSidebar?.classList.contains('mobile-open')) closeMobileSidebar();
  });
})();
</script>

</body>
</html>
