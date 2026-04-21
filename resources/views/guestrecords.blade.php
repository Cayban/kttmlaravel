<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Records for Guest</title>

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
      letter-spacing: .04em; z-index: 100;
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
    .topbar-left  {
      display: flex; align-items: center; gap: 14px;
      min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; }
    .page-title   {
      font-size: clamp(0.98rem, 0.45vw + 0.88rem, 1.15rem);
      font-weight: 800; letter-spacing: -.3px; color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-subtitle{
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted); font-weight: 500;
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
    .icon-btn {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted); transition: all .18s;
      text-decoration: none;
    }
    .icon-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
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
      width: 100%;
      max-width: var(--shell-max);
      margin: 0 auto;
      box-sizing: border-box;
      background: var(--bg) url('{{ asset("images/abstractBGIMAGE12.png") }}') no-repeat right center;
      background-size: cover;
    }

    /* ── HERO DARK CARD ── */
    .hero-card {
      background: linear-gradient(135deg, var(--maroon2) 0%, #C1363A 55%, var(--maroon) 100%);
      border-radius: 24px;
      padding: clamp(18px, 3vw, 24px) clamp(18px, 3vw, 28px);
      box-shadow: 0 12px 40px rgba(165,44,48,.30);
      position: relative; overflow: hidden; margin-bottom: 20px;
    }
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
      display: flex; align-items: center; justify-content: space-between;
      gap: 16px 20px; flex-wrap: wrap;
    }
    .hero-inner > div:first-child { flex: 1 1 240px; min-width: 0; }
    .hero-eyebrow { font-size: 0.68rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--gold); margin-bottom: 6px; }
    .hero-title   {
      font-size: clamp(1.05rem, 2vw + 0.55rem, 1.25rem);
      font-weight: 800; color: #fff; letter-spacing: -.3px; margin-bottom: 6px;
      overflow-wrap: anywhere;
    }
    .hero-sub     {
      font-size: clamp(0.74rem, 0.25vw + 0.7rem, 0.8rem);
      color: rgba(255,255,255,.72); line-height: 1.6; max-width: 64ch;
      overflow-wrap: anywhere;
    }
    .btn-hero-full {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
      color: #fff; border-radius: 12px;
      padding: clamp(8px, 1.2vw, 10px) clamp(14px, 2.5vw, 18px);
      font-family: inherit;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700; cursor: pointer;
      transition: background .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-hero-full:hover { background: rgba(255,255,255,.22); }

    /* ── FILTERS CARD ── */
    .filter-card {
      background: var(--card); border-radius: 22px; padding: 22px 24px;
      border: 1px solid var(--line); box-shadow: 0 2px 12px rgba(15,23,42,.05);
      margin-bottom: 20px;
    }
    .filter-card-header {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px; margin-bottom: 16px;
    }
    .filter-title  { font-size: 1rem; font-weight: 800; letter-spacing: -.2px; color: var(--ink); }
    .filter-sub    { font-size: 0.75rem; color: var(--muted); margin-top: 2px; }
    .filter-grid   { display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 14px; }
    .filter-row-1  { display: grid; grid-template-columns: 1fr; gap: 10px; }
    .filter-row-2  { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px; }
    .filter-input, .filter-select {
      width: 100%; border-radius: 12px; border: 1.5px solid var(--line);
      background: var(--bg); padding: 10px 14px;
      font-family: inherit; font-size: 0.8rem; font-weight: 600; color: var(--ink);
      outline: none; appearance: none; transition: border-color .18s, box-shadow .18s;
    }
    .filter-input:focus, .filter-select:focus {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light);
    }
    .filter-input::placeholder { color: #94a3b8; font-weight: 500; }
    .filter-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

    /* Filter toggle button — hidden on desktop, shown on mobile via media query */
    .filter-toggle-btn {
      display: none;
      align-items: center; gap: 6px;
      padding: 9px 14px; border-radius: 11px;
      border: 1.5px solid var(--line); background: var(--bg);
      font-family: inherit; font-size: 0.78rem; font-weight: 700;
      color: var(--muted); cursor: pointer;
      transition: all .18s; white-space: nowrap;
    }
    .filter-toggle-btn:hover,
    .filter-toggle-btn.active {
      border-color: var(--maroon); color: var(--maroon); background: var(--maroon-light);
    }
    .filter-toggle-btn .ftb-count {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 18px; height: 18px; border-radius: 20px;
      background: var(--maroon); color: #fff;
      font-size: 0.6rem; font-weight: 800; padding: 0 4px;
    }
    .filter-toggle-btn .ftb-count.hidden { display: none; }
    .btn-search {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.5vw, 20px);
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(165,44,48,.22);
      transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-search:hover { box-shadow: 0 8px 20px rgba(165,44,48,.32); transform: translateY(-1px); }
    .btn-gold {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.5vw, 20px);
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(240,200,96,.22);
      transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-gold:hover { box-shadow: 0 8px 20px rgba(240,200,96,.35); transform: translateY(-1px); }
    .btn-outline {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: var(--bg); border: 1.5px solid var(--line);
      color: var(--muted); cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.2vw, 18px);
      border-radius: 12px;
      transition: all .18s; text-decoration: none;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-outline:hover { background: #e9ecf1; color: var(--ink); border-color: #cbd5e1; }
    .result-hint { font-size: 0.72rem; color: var(--muted); margin-top: 10px; }

    /* ── MAIN GRID ── */
    .records-grid {
      display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 280px);
      gap: 16px; align-items: start;
    }

    /* ── TABLE CARD ── */
    .table-card {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line); box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden;
    }
    .table-card-header {
      padding: 20px 24px; border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px;
      background: linear-gradient(90deg, rgba(165,44,48,.04), rgba(240,200,96,.04));
    }
    .table-card-title { font-size: 1rem; font-weight: 800; color: var(--ink); }
    .table-card-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }
    .table-card-body  { padding: 20px 24px; }
    .table-wrap {
      overflow: auto; max-height: 66vh; border-radius: 14px;
      border: 1px solid var(--line); background: var(--bg);
    }
    table { min-width: 100%; border-collapse: collapse; font-size: 0.78rem; }
    thead th {
      background: var(--card); position: sticky; top: 0; z-index: 10;
      padding: 12px 14px; text-align: left;
      font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
      letter-spacing: .05em; color: var(--muted);
      border-bottom: 1px solid var(--line); white-space: nowrap;
    }
    thead th:first-child { cursor: pointer; transition: color .15s; }
    thead th:first-child:hover { color: var(--maroon); }
    tbody tr { border-bottom: 1px solid var(--line); transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(165,44,48,.04); }
    tbody td { padding: 11px 14px; vertical-align: top; }
    .cell-id    { font-weight: 800; color: var(--maroon); white-space: nowrap; font-family: 'DM Mono', monospace; font-size: .72rem; }
    .cell-title { font-weight: 700; color: var(--ink); overflow-wrap: anywhere; }
    .cell-muted { color: var(--muted); font-weight: 600; }
    .cell-link  { font-weight: 700; color: var(--maroon); text-decoration: underline; }
    .action-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 5px;
  padding: 5px 14px; border-radius: 8px; border: none; cursor: pointer;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: clamp(0.66rem, 0.12vw + 0.64rem, 0.72rem);
  font-weight: 700; letter-spacing: .03em;
  background: var(--maroon-light); color: var(--maroon);
  border: 1.5px solid rgba(165,44,48,.2);
  transition: background .18s, box-shadow .18s, transform .15s, color .18s;
  text-decoration: none;
  max-width: 100%; box-sizing: border-box;
  flex: 0 1 auto;
}
.action-btn:hover {
  background: var(--maroon); color: #fff;
  box-shadow: 0 4px 14px rgba(165,44,48,.35);
  transform: translateY(-1px);
  border-color: var(--maroon);
}
.action-btn:active {
  transform: translateY(0);
  box-shadow: 0 2px 6px rgba(165,44,48,.25);
}

    /* Status badges */
    .badge {
      display: inline-flex; align-items: center;
      padding: 3px 10px; border-radius: 999px;
      font-size: 0.65rem; font-weight: 800; border: 1.5px solid;
      white-space: nowrap;
    }
    .badge-registered   { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .badge-review       { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
    .badge-filed        { background: #f8fafc; color: #475569; border-color: #cbd5e1; }
    .badge-attention    { background: #fffbeb; color: #92400e; border-color: #fde68a; }
    .badge-returned     { background: #fff1f2; color: #9f1239; border-color: #fecdd3; }
    .badge-expiring     { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
    .badge-default      { background: var(--bg); color: var(--muted); border-color: var(--line); }

    /* ── RIGHT COLUMN ── */
    .right-col { display: flex; flex-direction: column; gap: 16px; }

    .panel-card {
      background: var(--card); border-radius: 20px; padding: 20px;
      border: 1px solid var(--line); box-shadow: 0 2px 12px rgba(15,23,42,.05);
    }
    .panel-card-header {
      padding: 16px 18px; border-bottom: 1px solid var(--line);
      background: linear-gradient(90deg, rgba(240,200,96,.08), rgba(165,44,48,.06));
      border-radius: 18px 18px 0 0; margin: -20px -20px 16px;
    }
    .panel-card-title { font-size: 0.85rem; font-weight: 800; color: var(--ink); }
    .panel-card-sub   { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }

    .rv-item {
      display: block; padding: 9px 12px; border-radius: 10px;
      background: var(--bg); border: 1px solid var(--line);
      font-size: 0.75rem; font-weight: 700; color: var(--maroon);
      text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      transition: all .15s; margin-bottom: 6px;
    }
    .rv-item:hover { background: var(--maroon-light); border-color: rgba(165,44,48,.2); }

    /* ── TOAST ── */
    .toast {
      position: fixed; top: 88px; right: 20px; z-index: 9999;
      min-width: min(280px, calc(100vw - 2rem));
      max-width: calc(100vw - 2rem);
      padding: 14px 18px; border-radius: 14px;
      box-shadow: 0 10px 40px rgba(15,23,42,.15);
      font-weight: 700; font-size: 0.8rem;
      display: flex; align-items: center; gap: 10px;
      animation: toastIn .3s ease-out;
      font-family: 'Plus Jakarta Sans', sans-serif;
      box-sizing: border-box;
    }
    .toast.success { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: #2a1a0b; border-left: 4px solid var(--maroon); }
    .toast.error   { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border-left: 4px solid #b91c1c; }
    .toast.hiding  { animation: toastOut .3s ease-out; }
    @keyframes toastIn  { from { transform: translateX(400px); opacity:0; } to { transform: translateX(0); opacity:1; } }
    @keyframes toastOut { from { transform: translateX(0); opacity:1; } to { transform: translateX(400px); opacity:0; } }

    /* ── MODALS ── */
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
      width: min(440px, calc(100vw - 2rem));
      max-width: 100%;
      position: relative;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      animation: fadeSlideUp .3s forwards;
      box-sizing: border-box;
    }
    .modal-box-xl {
      width: min(1200px, calc(100vw - 2rem));
      max-width: 100%;
      max-height: min(90vh, 100dvh);
      display: flex; flex-direction: column; padding: 0; overflow: hidden;
    }
    .modal-icon {
      width: 52px; height: 52px; border-radius: 16px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
    }
    .modal-title {
      font-size: clamp(0.98rem, 0.35vw + 0.9rem, 1.1rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere; line-height: 1.3;
    }
    .modal-desc  { font-size: 0.82rem; color: var(--muted); margin-top: 6px; line-height: 1.6; overflow-wrap: anywhere; }
    .modal-btns  {
      display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px;
      align-items: stretch;
    }
    .modal-btns form {
      flex: 1 1 140px;
      min-width: 0;
      display: flex;
    }
    .modal-btns form .btn-confirm { width: 100%; justify-content: center; }
    .btn-cancel {
      flex: 1 1 120px;
      min-width: 0;
      max-width: 100%;
      padding: 12px; border-radius: 12px;
      border: 1.5px solid var(--line); background: none;
      font-family: inherit;
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
      font-weight: 700; color: var(--muted); cursor: pointer; transition: all .18s;
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
      display: inline-flex; align-items: center; justify-content: center;
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

    /* Full table modal internals */
    .modal-xl-header { padding: 24px 28px; border-bottom: 1px solid var(--line); flex-shrink: 0; }
    .modal-xl-filters { padding: 16px 28px; border-bottom: 1px solid var(--line); flex-shrink: 0; display: flex; flex-wrap: wrap; gap: 8px; }
    .modal-filter-row-main {
      display: flex; align-items: center; gap: 8px;
      flex: 1 1 100%;
      min-width: 0;
    }
    .modal-filter-row-extra {
      display: flex; flex-wrap: wrap; gap: 8px;
      flex: 1 1 100%;
      min-width: 0;
    }
    .modal-filter-toggle-btn {
      display: none;
      align-items: center; gap: 6px;
      padding: 9px 12px; border-radius: 11px;
      border: 1.5px solid var(--line); background: var(--bg);
      font-family: inherit; font-size: 0.76rem; font-weight: 700;
      color: var(--muted); cursor: pointer; white-space: nowrap;
      transition: all .18s;
      flex: 0 0 auto;
    }
    .modal-filter-toggle-btn:hover,
    .modal-filter-toggle-btn.active {
      border-color: var(--maroon); color: var(--maroon); background: var(--maroon-light);
    }
    .modal-filter-toggle-btn .mftb-count {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 18px; height: 18px; border-radius: 20px;
      background: var(--maroon); color: #fff;
      font-size: 0.6rem; font-weight: 800; padding: 0 4px;
    }
    .modal-filter-toggle-btn .mftb-count.hidden { display: none; }
    .modal-xl-body { flex: 1; overflow: auto; padding: 0; }
    .modal-filter-input, .modal-filter-select {
      border-radius: 10px; border: 1.5px solid var(--line);
      background: var(--bg); padding: 8px 12px;
      font-family: inherit; font-size: 0.78rem; font-weight: 600; color: var(--ink);
      outline: none; appearance: none; transition: border-color .18s, box-shadow .18s;
      min-width: 0;
      max-width: 100%;
    }
    .modal-filter-select { min-width: 120px; flex: 0 1 160px; }
    .modal-filter-input { flex: 1 1 200px; min-width: 0; max-width: 100%; }
    .modal-filter-input:focus, .modal-filter-select:focus {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light);
    }

    /* ── ANIMATIONS ── */
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .anim { opacity: 0; animation: fadeSlideUp .5s forwards; }
    .anim-1 { animation-delay: .05s; }
    .anim-2 { animation-delay: .12s; }
    .anim-3 { animation-delay: .19s; }

    /* ── PAGINATION ── */
    .table-footer {
      padding: 14px 20px; border-top: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 10px 14px;
      background: linear-gradient(90deg, rgba(165,44,48,.03), rgba(240,200,96,.03));
    }
    .table-footer-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
    .page-info { font-size: 0.72rem; color: var(--muted); font-family: 'DM Mono', monospace; }
    .page-btn {
      padding: 7px 16px; border-radius: 10px;
      border: 1.5px solid var(--line); background: var(--card);
      font-family: inherit; font-size: 0.75rem; font-weight: 700;
      color: var(--muted); cursor: pointer; transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .page-btn:hover:not(:disabled) { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .page-btn:disabled { opacity: 0.38; cursor: default; }

    /* ── FOOTER ── */
    .page-footer {
      margin-top: 24px; padding: 16px 0;
      border-top: 1px solid var(--line);
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 10px 16px;
    }
    .page-footer-left  { font-size: .72rem; color: var(--muted); }
    .page-footer-right { font-size: .72rem; font-family: 'DM Mono', monospace; color: #94a3b8; }

    .modal-xl-footer {
      padding: 14px 24px; border-top: 1px solid var(--line); flex-shrink: 0;
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 10px 12px;
    }
    .modal-xl-footer-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; justify-content: flex-end; }

    @media (max-width: 900px) {
      .records-grid { grid-template-columns: minmax(0, 1fr); }
      .filter-row-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
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
      .modal-box-xl {
        width: min(100vw - 1rem, 1200px);
        max-height: min(94vh, 100dvh);
        border-radius: 20px;
      }
      .modal-xl-header {
        padding: 20px 22px 16px;
        padding-right: 56px;
      }
      .modal-xl-filters {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        padding: 14px 22px 18px;
      }
      .modal-filter-row-main,
      .modal-filter-row-extra {
        display: contents;
      }
      .modal-filter-input { grid-column: 1 / -1; }
      .modal-filter-select,
      .modal-filter-input {
        width: 100%;
        min-width: 0;
      }
      .modal-xl-footer {
        padding: 12px 18px;
      }
    }

    /* ── MOBILE FILTER: compact collapsible design ── */
    @media (max-width: 580px) {
      /* Tighten the card itself */
      .filter-card {
        padding: 14px 14px 12px;
        border-radius: 16px;
        margin-bottom: 14px;
      }

      /* Header: title left, filter toggle right */
      .filter-card-header {
        margin-bottom: 12px;
        gap: 8px;
      }
      .filter-title  { font-size: 0.9rem; }
      .filter-sub    { font-size: 0.7rem; }

      /* Search row stays full-width and always visible */
      .filter-row-1 { margin-bottom: 0; }
      .filter-input  { font-size: 0.82rem; padding: 10px 12px; }

      /* Filter toggle button — shows/hides .filter-row-2 */
      .filter-toggle-btn {
        display: inline-flex !important;
      }

      /* Dropdowns panel: hidden by default, shown when .filters-open */
      .filter-row-2 {
        display: none;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 10px;
      }
      .filter-row-2.filters-open {
        display: grid;
      }
      .filter-select { font-size: 0.78rem; padding: 9px 10px; }

      /* Actions row: always visible, compact */
      .filter-actions {
        margin-top: 10px;
        gap: 7px;
        justify-content: stretch;
      }
      .filter-actions .btn-search { flex: 1; justify-content: center; }
      .filter-actions .btn-outline { flex: 0 0 auto; }
      .filter-actions .btn-gold    { flex: 0 0 auto; }
      .btn-gold-label { display: none; }
      .guest-pill-long { display: none; }
      .modal-overlay {
        align-items: flex-end;
        padding: 10px 10px max(10px, env(safe-area-inset-bottom));
      }
      .modal-box-xl {
        width: 100%;
        max-height: min(92vh, 100dvh);
        border-radius: 20px 20px 0 0;
      }
      .modal-xl-header {
        padding: 18px 16px 14px;
        padding-right: 52px;
      }
      .modal-xl-filters {
        display: block;
        padding: 12px 16px 16px;
      }
      .modal-filter-row-main {
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .modal-filter-row-extra {
        display: none;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 10px;
      }
      .modal-filter-row-extra.filters-open {
        display: grid;
      }
      .modal-filter-toggle-btn {
        display: inline-flex;
      }
      .modal-filter-select,
      .modal-filter-input {
        font-size: 0.76rem;
        padding: 10px 11px;
      }
      .modal-close-x {
        top: 10px;
        right: 10px;
      }
    }

    @media (max-width: 480px) {
      .modal-btns { flex-direction: column; }
      .modal-btns .btn-cancel,
      .modal-btns form { flex: 0 0 auto; width: 100%; }
      .table-footer { flex-direction: column; align-items: stretch; }
      .table-footer-actions { justify-content: center; }

      .filter-card { padding: 12px; }
      .filter-row-2 { grid-template-columns: 1fr; }
      .filter-actions .btn-search,
      .filter-actions .btn-outline,
      .filter-actions .btn-gold { flex: 1; justify-content: center; }
      .modal-xl-filters {
        display: block;
      }
      .modal-filter-row-extra {
        grid-template-columns: 1fr;
      }
      .modal-filter-input {
        grid-column: auto;
      }
      .modal-xl-footer,
      .modal-xl-footer-actions {
        width: 100%;
      }
      .modal-xl-footer {
        flex-direction: column;
        align-items: stretch;
      }
      .modal-xl-footer-actions {
        justify-content: stretch;
      }
      .modal-xl-footer-actions .page-btn {
        flex: 1 1 0;
      }
    }
  </style>
</head>

<body>

  @php
    $user = $user ?? (object)['name' => 'Guest Viewer', 'role' => 'Guest'];
    $recent     = $recent     ?? [];
    $allRecords = $allRecords ?? [];

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();

    $urlDashboard       = url('/home');
    $urlGuest           = url('/guest');
    $urlRecords         = url('/ip-records');
    $urlNew             = url('/ipassets/create');
    $urlSupport         = url('/support');
    $urlLogout          = url('/logout');
    $urlProfile         = url('/profile');
    $urlRecordsListPage = url('/ip-records');
    $urlHowTo           = url('/how-to-file');

    $guestInitials = collect(preg_split('/\s+/', trim($user->name ?? ''), -1, PREG_SPLIT_NO_EMPTY))
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
      <a href="{{ $urlGuest }}" class="nav-item">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span class="nav-tooltip">Home</span>
      </a>

      <a href="{{ $urlRecords }}" class="nav-item active">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
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
      <button type="button" id="logoutBtn" class="nav-item" style="background:none;border:none;">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        <span class="nav-tooltip">Log Out</span>
      </button>
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
          <div class="page-title">IP Records</div>
          <div class="page-subtitle">Browse &amp; filter IP records · View-only</div>
        </div>
      </div>
      <div class="topbar-right">
        <div class="guest-pill" title="{{ $user->name }} · Guest">
          <span class="guest-pill-dot"></span>
          <span class="guest-pill-text">
            <span class="guest-pill-long">{{ $user->name }} · </span>Guest
          </span>
        </div>
        <div class="avatar">{{ $guestInitials }}</div>
      </div>
    </header>

    {{-- CONTENT --}}
    <div class="content">

      {{-- HERO --}}
      <div class="hero-card anim anim-1">
        <div class="hero-inner">
          <div>
            <div class="hero-eyebrow">Records Workspace · View Only</div>
            <div class="hero-title">Browse &amp; filter IP records.</div>
            <div class="hero-sub">Search quickly by title, owner, or ID — then narrow down using campus, type, and status filters. Guests can view only.</div>
          </div>
          <button id="openFullPageBtn" type="button" class="btn-hero-full" title="View all records in full screen">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>
            </svg>
            View All Records
          </button>
        </div>
      </div>

      {{-- FILTERS CARD --}}
      <div class="filter-card anim anim-2">
        <div class="filter-card-header">
          <div>
            <div class="filter-title">Search + Filters</div>
            <div class="filter-sub">Press <strong>Enter</strong> or click <strong>Search</strong> to apply.</div>
          </div>
        </div>
        <div class="filter-grid">
          <div class="filter-row-1" style="display:flex; gap:8px; align-items:center;">
            <input id="viewAllSearch" type="search" placeholder="Search by ID, title, owner, campus…"
              class="filter-input" style="flex:1;" />
            {{-- Filter toggle: mobile only --}}
            <button type="button" class="filter-toggle-btn" id="filterToggleBtn" aria-expanded="false">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <line x1="4" y1="6" x2="20" y2="6"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
                <line x1="11" y1="18" x2="13" y2="18"/>
              </svg>
              Filters
              <span class="ftb-count hidden" id="filterCount">0</span>
            </button>
          </div>
          </div>
          <div class="filter-row-2">
            <select id="filterStatus" class="filter-select">
              <option value="">All status</option>
              @foreach($statuses as $st)
                <option value="{{ $st }}">{{ $st }}</option>
              @endforeach
            </select>
            <select id="filterCampus" class="filter-select">
              <option value="">All campus</option>
              @foreach($campuses as $c)
                <option value="{{ $c }}">{{ $c }}</option>
              @endforeach
            </select>
            <select id="filterType" class="filter-select">
              <option value="">All categories</option>
              @foreach($types as $t)
                <option value="{{ $t }}">{{ $t }}</option>
              @endforeach
            </select>
            <select id="filterCollege" class="filter-select">
              <option value="">All colleges</option>
              @foreach($colleges ?? [] as $col)
                <option value="{{ $col }}">{{ $col }}</option>
              @endforeach
            </select>
            <select id="filterProgram" class="filter-select">
              <option value="">All programs</option>
              @foreach($programs ?? [] as $prog)
                <option value="{{ $prog }}">{{ $prog }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="filter-actions">
          <button type="button" id="applySearchBtn" class="btn-search">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            Search
          </button>
          <button type="button" id="resetFiltersBtn" class="btn-outline">Reset</button>
          <button type="button" id="openFullPageBtnFilter" class="btn-gold" title="View all records">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>
            </svg>
            <span class="btn-gold-label">View All Records</span>
          </button>
        </div>
        <div id="resultHint" class="result-hint">Showing all records.</div>
      </div>

      {{-- RECORDS GRID --}}
      <div class="records-grid anim anim-3">

        {{-- PRIMARY TABLE CARD --}}
        <div class="table-card">
          <div class="table-card-header">
            <div>
              <div class="table-card-title">All IP Records</div>
              <div class="table-card-sub">Filtered client-side · fast search</div>
            </div>
          </div>
          <div class="table-card-body">
            <div class="table-wrap">
              <table id="recordsTable">
                <thead>
                  <tr>
                    <th id="mainSortBtn">Record ID <span id="mainSortIcon" style="opacity:.5;">⇅</span></th>
                    <th data-col="title" style="min-width:260px;">IP Title</th>
                    <th data-col="category">Category</th>
                    <th data-col="owner" style="min-width:200px;">Owner / Inventor</th>
                    <th data-col="campus">Campus</th>
                    <th data-col="college">College</th>
                    <th data-col="program">Program</th>
                    <th data-col="classofwork">Class of Work</th>
                    <th data-col="status">Status</th>
                    <th data-col="datecreated">Date Created</th>
                    <th data-col="registered">Date Registered</th>
                    <th data-col="nextdue">Next Due</th>
                    <th data-col="validity">Validity</th>
                    <th data-col="regnumber">Reg. Number</th>
                    <th data-col="gdrive" style="min-width:120px;">GDrive</th>
                    <th data-col="actions">Actions</th>
                  </tr>
                </thead>
                <tbody id="mainTableBody">
                  <tr><td colspan="14" style="text-align:center;padding:40px;color:var(--muted);">Loading…</td></tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="table-footer">
            <div class="page-info" id="pageInfo">Page 1</div>
            <div class="table-footer-actions">
              <button type="button" id="prevPageBtn" class="page-btn" disabled>← Prev</button>
              <button type="button" id="nextPageBtn" class="page-btn">Next →</button>
            </div>
          </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="right-col">

          {{-- Recently Viewed --}}
          <div class="panel-card">
            <div class="panel-card-header">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 13"/>
                  </svg>
                </div>
                <div>
                  <div class="panel-card-title">Recently Viewed</div>
                  <div class="panel-card-sub">Last 5 records you opened</div>
                </div>
              </div>
            </div>
            <div id="recentlyViewed">
              <p style="font-size:.75rem;color:var(--muted);">No recently viewed records.</p>
            </div>
          </div>

          {{-- Quick stats panel --}}
          <div class="panel-card">
            <div class="panel-card-header">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,var(--gold),var(--gold2));display:flex;align-items:center;justify-content:center;">
                  <svg width="16" height="16" fill="none" stroke="#2a1a0b" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                  </svg>
                </div>
                <div>
                  <div class="panel-card-title">Access Info</div>
                  <div class="panel-card-sub">Guest permissions</div>
                </div>
              </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px;">
              <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);">
                <span style="font-size:.78rem;font-weight:600;color:var(--ink);">View records</span>
                <span style="font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:999px;background:rgba(16,185,129,.1);color:#059669;">Yes</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);">
                <span style="font-size:.78rem;font-weight:600;color:var(--ink);">Filter &amp; search</span>
                <span style="font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:999px;background:rgba(16,185,129,.1);color:#059669;">Yes</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);">
                <span style="font-size:.78rem;font-weight:600;color:var(--ink);">GDrive links</span>
                <span style="font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:999px;background:rgba(245,158,11,.1);color:#d97706;">If enabled</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);">
                <span style="font-size:.78rem;font-weight:600;color:var(--ink);">Create / Edit</span>
                <span style="font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:999px;background:var(--maroon-light);color:var(--maroon);">No</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;">
                <span style="font-size:.78rem;font-weight:600;color:var(--ink);">Delete records</span>
                <span style="font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:999px;background:var(--maroon-light);color:var(--maroon);">No</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- FOOTER --}}
      <footer class="page-footer">
        <div class="page-footer-left">© {{ now()->year }} • KTTM Intellectual Property Services</div>
        <div class="page-footer-right">Records Workspace · Guest View</div>
      </footer>

    </div>
  </div>

  {{-- ── LOGOUT MODAL ── --}}
  <div class="modal-overlay" id="logoutModal">
    <div class="modal-box">
      <button type="button" class="modal-close-x" data-close-logout>
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <div class="modal-icon">
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
      <div class="modal-title">Sign out of KTTM?</div>
      <div class="modal-desc">This will end your session and return you to the public portal.</div>
      <div class="modal-btns">
        <button type="button" class="btn-cancel" data-close-logout>Cancel</button>
        <form id="logoutForm" action="{{ $urlLogout }}" method="POST" data-simulate="true">
          @csrf
          <button type="submit" class="btn-confirm">Sign Out</button>
        </form>
      </div>
    </div>
  </div>

  {{-- ── FULL TABLE MODAL ── --}}
  <div class="modal-overlay" id="fullTableModal">
    <div class="modal-box modal-box-xl">
      <button type="button" class="modal-close-x" data-close-fullpage style="position:absolute;top:14px;right:14px;z-index:10;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <div class="modal-xl-header">
        <div style="font-size:1.05rem;font-weight:800;color:var(--ink);">All IP Records</div>
        <div style="font-size:.72rem;color:var(--muted);margin-top:2px;">Complete list · full details</div>
      </div>
      
      <div class="modal-xl-filters">
        <div class="modal-filter-row-main">
          <input id="modalSearch" type="search" placeholder="Search any column…" class="modal-filter-input" />
          <button type="button" class="modal-filter-toggle-btn" id="modalFilterToggleBtn" aria-expanded="false">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <line x1="4" y1="6" x2="20" y2="6"/>
              <line x1="8" y1="12" x2="16" y2="12"/>
              <line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
            Filters
            <span class="mftb-count hidden" id="modalFilterCount">0</span>
          </button>
        </div>
        <div class="modal-filter-row-extra" id="modalFilterRowExtra">
          <select id="modalFilterStatus" class="modal-filter-select">
            <option value="">All status</option>
            @foreach($statuses as $st)
              <option value="{{ $st }}">{{ $st }}</option>
            @endforeach
          </select>
          <select id="modalFilterCampus" class="modal-filter-select">
            <option value="">All campus</option>
            @foreach($campuses as $c)
              <option value="{{ $c }}">{{ $c }}</option>
            @endforeach
          </select>
          <select id="modalFilterType" class="modal-filter-select">
            <option value="">All categories</option>
            @foreach($types as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
          <select id="modalFilterCollege" class="modal-filter-select">
            <option value="">All colleges</option>
            @foreach($colleges ?? [] as $col)
              <option value="{{ $col }}">{{ $col }}</option>
            @endforeach
          </select>
          <select id="modalFilterProgram" class="modal-filter-select">
            <option value="">All programs</option>
            @foreach($programs ?? [] as $prog)
              <option value="{{ $prog }}">{{ $prog }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-xl-body">
        <table style="min-width:100%;border-collapse:collapse;font-size:.78rem;">
          <thead>
            <tr>
              <th id="modalSortBtn" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;cursor:pointer;">
                Record ID <span id="modalSortIcon" style="opacity:.5;">⇅</span>
              </th>
              <th data-col="title" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);min-width:260px;">IP Title</th>
              <th data-col="category" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Category</th>
              <th data-col="owner" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);min-width:200px;">Owner / Inventor</th>
              <th data-col="campus" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Campus</th>
              <th data-col="college" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">College</th>
              <th data-col="program" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Program</th>
              <th data-col="classofwork" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Class of Work</th>
              <th data-col="status" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Status</th>
              <th data-col="datecreated" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Date Created</th>
              <th data-col="registered" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Date Registered</th>
              <th data-col="regnumber" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Reg. Number</th>
              <th data-col="gdrive" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);min-width:100px;">GDrive</th>
              <th data-col="actions" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Actions</th>
            </tr>
          </thead>
          <tbody id="modalTableBody">
            <tr><td colspan="14" style="text-align:center;padding:40px;color:var(--muted);">Loading…</td></tr>
          </tbody>
        </table>
      </div>
      <div class="modal-xl-footer">
        <div class="page-info" id="modalPageInfo">Page 1</div>
        <div class="modal-xl-footer-actions">
          <button type="button" id="modalPrevBtn" class="page-btn" disabled>← Prev</button>
          <button type="button" id="modalNextBtn" class="page-btn">Next →</button>
          <button type="button" data-close-fullpage class="btn-outline" style="padding:7px 18px;">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  (function(){
    const urlRecordsListPage = '{{ $urlRecordsListPage }}';

    function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

    // ── Toast ──
    function showToast(msg, type='success', dur=4500){
      const t = document.createElement('div');
      t.className = `toast ${type}`;
      t.innerHTML = `<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        ${type==='success'
          ? '<path d="M20 6 9 17l-5-5"/>'
          : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}
      </svg><span>${msg}</span>`;
      document.body.appendChild(t);
      setTimeout(()=>{ t.classList.add('hiding'); setTimeout(()=>t.remove(), 300); }, dur);
    }

    // ── Modal helpers + scroll lock (drawer + modals) ──
    function syncBodyScrollLock() {
      const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
      const anyModal = ['logoutModal', 'fullTableModal'].some(
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

    // Logout
    ['logoutBtn','logoutBtnCard'].forEach(id=>document.getElementById(id)?.addEventListener('click',()=>openModal('logoutModal')));
    document.querySelectorAll('[data-close-logout]').forEach(b=>b.addEventListener('click',()=>closeModal('logoutModal')));
    document.getElementById('logoutModal')?.addEventListener('click',e=>{ if(e.target.id==='logoutModal') closeModal('logoutModal'); });
    const logoutForm = document.getElementById('logoutForm');
    if(logoutForm?.dataset.simulate==='true'){
      logoutForm.addEventListener('submit', ev=>{ ev.preventDefault(); closeModal('logoutModal'); setTimeout(()=>window.location.href='{{ url('/') }}',220); });
    }

    // Full table modal
    ['openFullPageBtn','openFullPageBtnTop','openFullPageBtnFilter'].forEach(id=>{
      document.getElementById(id)?.addEventListener('click',()=>{
        openModal('fullTableModal');
        const mt = document.getElementById('modalFilterType');
        applyModalColumnLayout(mt?.value || '');
        updateModalFilterCount();
        fetchModalRecords(1);
      });
    });
    document.querySelectorAll('[data-close-fullpage]').forEach(b=>b.addEventListener('click',()=>closeModal('fullTableModal')));
    document.getElementById('fullTableModal')?.addEventListener('click',e=>{ if(e.target.id==='fullTableModal') closeModal('fullTableModal'); });

    // ── Shared row builder ──
    const badgeMap = {'Registered':'badge-registered','Under Review':'badge-review','Filed':'badge-filed','Needs Attention':'badge-attention','Returned':'badge-returned','Close to Expiration':'badge-expiring'};

    function buildRow(r){
      const rawReg = r.date_registered_deposited || r.registered || null;
      const reg = rawReg ? new Date(rawReg).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'2-digit'}) : '—';
      const rawCreated = r.date_creation || null;
      const created = rawCreated ? new Date(rawCreated).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'2-digit'}) : '—';
      const bc = badgeMap[r.status] || 'badge-default';
      const id = esc(r.record_id||r.id||'');
      const typeLow = (r.category||r.type||'').toLowerCase();
      let due='—', validity='—';
      try { if(rawReg){
        const d = new Date(rawReg);
        if(typeLow==='patent')           { d.setFullYear(d.getFullYear()+20); due=d.toLocaleDateString(); validity='20 yrs'; }
        else if(typeLow==='copyright')   { d.setFullYear(d.getFullYear()+70); due=d.toLocaleDateString(); validity='70 yrs'; }
        else if(typeLow==='utility model'){ d.setFullYear(d.getFullYear()+10); due=d.toLocaleDateString(); validity='10 yrs'; }
        else if(typeLow==='industrial design'){ d.setFullYear(d.getFullYear()+15); due=d.toLocaleDateString(); validity='15 yrs'; }
        else if(typeLow==='trademark')   { d.setFullYear(d.getFullYear()+10); due=d.toLocaleDateString(); validity='10 yrs'; }
      }} catch(e){}
      return `<tr class="record-row" style="border-bottom:1px solid var(--line);cursor:pointer;" onclick="addRV('${id}','${esc(r.ip_title||r.title||'')}')">
        <td class="cell-id record-id">${id}</td>
        <td data-col="title" class="record-title"><span class="cell-title">${esc(r.ip_title||r.title||'—')}</span></td>
        <td data-col="category" class="cell-muted record-type" style="white-space:nowrap;">${esc(r.category||r.type||'—')}</td>
        <td data-col="owner" class="cell-muted record-owner">${esc(r.owner_inventor||r.owner||'—')}</td>
        <td data-col="campus" class="cell-muted record-campus" style="white-space:nowrap;">${esc(r.campus||'—')}</td>
        <td data-col="college" class="cell-muted" style="white-space:nowrap;">${esc(r.college||'—')}</td>
        <td data-col="program" class="cell-muted" style="white-space:nowrap;">${esc(r.program||'—')}</td>
        <td data-col="classofwork" class="cell-muted" style="white-space:nowrap;">${esc(r.class_of_work||'—')}</td>
        <td data-col="status" class="record-status" style="white-space:nowrap;"><span class="badge ${bc}">${esc(r.status||'—')}</span></td>
        <td data-col="datecreated" class="cell-muted" style="white-space:nowrap;">${esc(created)}</td>
        <td data-col="registered" class="cell-muted record-registered" style="white-space:nowrap;">${esc(reg)}</td>
        <td data-col="nextdue" class="cell-muted" style="white-space:nowrap;">${esc(due)}</td>
        <td data-col="validity" class="cell-muted" style="white-space:nowrap;">${esc(validity)}</td>
        <td data-col="regnumber" class="cell-muted" style="white-space:nowrap;">${esc(r.registration_number||'—')}</td>
        <td data-col="gdrive" class="record-link">${r.gdrive_link?`<a href="${esc(r.gdrive_link)}" target="_blank" class="cell-link">Open file</a>`:'<span class="cell-muted">—</span>'}</td>
        <td data-col="actions" style="white-space:nowrap;"><button type="button" class="action-btn viewBtn" data-record-id="${id}" onclick="event.stopPropagation();window.location.href='/guestrecorddetail/${id}'">View</button></td>
      </tr>`;
    }

    function buildModalRow(r){
      const rawReg = r.date_registered_deposited || r.registered || null;
      const reg = rawReg ? new Date(rawReg).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'2-digit'}) : '—';
      const rawCreated = r.date_creation || null;
      const created = rawCreated ? new Date(rawCreated).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'2-digit'}) : '—';
      const bc = badgeMap[r.status] || 'badge-default';
      return `<tr class="modal-record-row" style="border-bottom:1px solid var(--line);transition:background .15s;" onmouseover="this.style.background='rgba(165,44,48,.04)'" onmouseout="this.style.background=''">
        <td style="padding:10px 14px;font-family:'DM Mono',monospace;font-size:.7rem;font-weight:800;color:var(--maroon);white-space:nowrap;">${esc(r.record_id||r.id||'—')}</td>
        <td data-col="title" style="padding:10px 14px;font-weight:700;color:var(--ink);">${esc(r.ip_title||r.title||'—')}</td>
        <td data-col="category" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(r.category||r.type||'—')}</td>
        <td data-col="owner" style="padding:10px 14px;color:var(--muted);font-weight:600;">${esc(r.owner_inventor||r.owner||'—')}</td>
        <td data-col="campus" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(r.campus||'—')}</td>
        <td data-col="college" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(r.college||'—')}</td>
        <td data-col="program" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(r.program||'—')}</td>
        <td data-col="classofwork" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(r.class_of_work||'—')}</td>
        <td data-col="status" style="padding:10px 14px;white-space:nowrap;"><span class="badge ${bc}">${esc(r.status||'—')}</span></td>
        <td data-col="datecreated" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(created)}</td>
        <td data-col="registered" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(reg)}</td>
        <td data-col="regnumber" style="padding:10px 14px;color:var(--muted);font-weight:600;white-space:nowrap;">${esc(r.registration_number||'—')}</td>
        <td data-col="gdrive" style="padding:10px 14px;">${r.gdrive_link?`<a href="${esc(r.gdrive_link)}" target="_blank" style="font-weight:700;color:var(--maroon);text-decoration:underline;">Open file</a>`:'<span style="color:var(--muted);">—</span>'}</td>
        <td data-col="actions" style="padding:10px 14px;white-space:nowrap;"><button type="button" class="action-btn" onclick="event.stopPropagation();window.location.href='/guestrecorddetail/${esc(r.record_id||r.id||'')}'">View</button></td>
      </tr>`;
    }

    // ── Main table: API pagination ──
    const q        = document.getElementById('viewAllSearch');
    const campus   = document.getElementById('filterCampus');
    const type     = document.getElementById('filterType');
    const status   = document.getElementById('filterStatus');
    const college  = document.getElementById('filterCollege');
    const program  = document.getElementById('filterProgram');
    const resultHint = document.getElementById('resultHint');
    let currentPage = 1, lastPage = 1;

    async function fetchRecords(page=1){
      currentPage = page || 1;
      const params = new URLSearchParams();
      if(q?.value.trim())       params.set('q',       q.value.trim());
      if(type?.value)           params.set('type',    type.value);
      if(status?.value)         params.set('status',  status.value);
      if(campus?.value)         params.set('campus',  campus.value);
      if(college?.value)        params.set('college', college.value);
      if(program?.value)        params.set('program', program.value);
      params.set('page',    currentPage);
      params.set('per_page', 50);
      const tbody = document.getElementById('mainTableBody');
      if(tbody) tbody.innerHTML = '<tr><td colspan="14" style="text-align:center;padding:40px;color:var(--muted);">Loading…</td></tr>';
      try {
        const resp = await fetch('/api/records?'+params, {headers:{'Accept':'application/json'}});
        if(!resp.ok) throw new Error();
        const data = await resp.json();
        const items = data.data || []; lastPage = data.last_page || 1;
        if(tbody) tbody.innerHTML = items.length
          ? items.map(r=>buildRow(r)).join('')
          : '<tr><td colspan="14" style="text-align:center;padding:40px;color:var(--muted);">No records found.</td></tr>';
        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${lastPage}`;
        document.getElementById('prevPageBtn').disabled = currentPage <= 1;
        document.getElementById('nextPageBtn').disabled = currentPage >= lastPage;
        if(resultHint) resultHint.textContent = items.length ? `Showing ${items.length} of ${data.total||'?'} record(s).` : 'No results.';
      } catch(e){ console.error(e); }
    }

    document.getElementById('applySearchBtn')?.addEventListener('click', ()=>fetchRecords(1));
    document.getElementById('resetFiltersBtn')?.addEventListener('click', ()=>{
      if(q) q.value=''; if(campus) campus.value='';
      if(type) type.value=''; if(status) status.value='';
      if(college) college.value=''; if(program) program.value='';
      applyColumnLayout('');
      fetchRecords(1);
    });
    q?.addEventListener('keypress', e=>{ if(e.key==='Enter') fetchRecords(1); });
    [campus, type, status, college, program].forEach(el=>el?.addEventListener('change', ()=>fetchRecords(1)));
    type?.addEventListener('change', () => applyColumnLayout(type.value));
    document.getElementById('prevPageBtn')?.addEventListener('click', ()=>{ if(currentPage>1) fetchRecords(currentPage-1); });
    document.getElementById('nextPageBtn')?.addEventListener('click', ()=>fetchRecords(currentPage+1));

    /* ══════════════════════════════════════════════
       COLUMN LAYOUT SWAP — by IP Category filter
    ══════════════════════════════════════════════ */
    const GUEST_COL_SCHEMAS = {
      // HTML <td> order: title→category→owner→campus→college→program→classofwork→status→datecreated→registered→nextdue→validity→regnumber→gdrive→actions
      copyright: {
        regnumber:   'Reg. Number',
        title:       'Title of Work',
        category:    'Category',
        owner:       'Authors',
        campus:      'Campus',
        college:     'College',
        program:     'Program',
        classofwork: 'Class of Work',
        status:      'Status',
        datecreated: 'Date Created',
        registered:  'Date Registered/Deposited',
        nextdue:     'Next Due',
        validity:    'Validity',
        gdrive:      'GDrive',
        actions:     'Actions',
      },
      patent: {
        regnumber:   'Reg. Number',
        title:       'Title of Application',
        category:    'Category',
        owner:       'Inventors',
        status:      'Status',
        datecreated: 'Date Patented/Submitted',
        registered:  'Registration Date',
        nextdue:     'Next Due',
        validity:    'Validity',
        gdrive:      'GDrive',
        actions:     'Actions',
      },
      trademark: {
        regnumber:   'Reg. Number',
        title:       'Mark',
        category:    'Category',
        status:      'Status',
        datecreated: 'Filing Date',
        registered:  'Registration Date',
        nextdue:     'Next Due',
        validity:    'Validity',
        gdrive:      'GDrive',
        actions:     'Actions',
      },
    };
    GUEST_COL_SCHEMAS['utility model']     = GUEST_COL_SCHEMAS.patent;
    GUEST_COL_SCHEMAS['industrial design'] = GUEST_COL_SCHEMAS.patent;

    const GUEST_DEFAULT_LABELS = {
      title:       'IP Title',
      category:    'Category',
      owner:       'Owner / Inventor',
      campus:      'Campus',
      college:     'College',
      program:     'Program',
      classofwork: 'Class of Work',
      status:      'Status',
      datecreated: 'Date Created',
      registered:  'Date Registered',
      nextdue:     'Next Due',
      validity:    'Validity',
      regnumber:   'Reg. Number',
      gdrive:      'GDrive',
      actions:     'Actions',
    };

    function applyColumnLayout(selectedType) {
      const table  = document.getElementById('recordsTable');
      if (!table) return;
      const key    = selectedType.trim().toLowerCase();
      const schema = GUEST_COL_SCHEMAS[key] || null;
      const headers = table.querySelectorAll('thead th[data-col]');

      headers.forEach(th => {
        const col = th.getAttribute('data-col');
        if (!schema) {
          th.style.display = '';
          if (GUEST_DEFAULT_LABELS[col]) {
            const first = Array.from(th.childNodes).find(n => n.nodeType === 3);
            if (first) first.textContent = GUEST_DEFAULT_LABELS[col] + ' ';
            else if (th.childNodes[0]) th.childNodes[0].textContent = GUEST_DEFAULT_LABELS[col];
          }
        } else {
          if (schema[col] !== undefined) {
            th.style.display = '';
            const first = Array.from(th.childNodes).find(n => n.nodeType === 3);
            if (first) first.textContent = schema[col] + ' ';
            else if (th.childNodes[0]) th.childNodes[0].textContent = schema[col];
          } else {
            th.style.display = 'none';
          }
        }
      });

      function applyToRows() {
        table.querySelectorAll('tbody tr.record-row').forEach(row => {
          row.querySelectorAll('td[data-col]').forEach(td => {
            const col = td.getAttribute('data-col');
            td.style.display = (!schema || schema[col] !== undefined) ? '' : 'none';
          });
        });
      }
      applyToRows();

      const tbody = table.querySelector('tbody');
      if (tbody._colObserver) tbody._colObserver.disconnect();
      if (schema) {
        const obs = new MutationObserver(() => applyToRows());
        obs.observe(tbody, { childList: true });
        tbody._colObserver = obs;
      } else {
        tbody._colObserver = null;
      }
    }

    /* ══════════════════════════════════════════════
       MODAL COLUMN LAYOUT SWAP — by IP Category filter
    ══════════════════════════════════════════════ */
    function applyModalColumnLayout(selectedType) {
      const modalBox = document.getElementById('fullTableModal');
      if (!modalBox) return;
      const key    = (selectedType||'').trim().toLowerCase();
      const schema = GUEST_COL_SCHEMAS[key] || null;

      // Update headers
      modalBox.querySelectorAll('thead th[data-col]').forEach(th => {
        const col = th.getAttribute('data-col');
        if (!schema) {
          th.style.display = '';
          if (GUEST_DEFAULT_LABELS[col]) th.childNodes[0].textContent = GUEST_DEFAULT_LABELS[col];
        } else {
          if (schema[col] !== undefined) {
            th.style.display = '';
            th.childNodes[0].textContent = schema[col];
          } else {
            th.style.display = 'none';
          }
        }
      });

      // Update existing body rows
      function applyToModalRows() {
        modalBox.querySelectorAll('tbody tr.modal-record-row').forEach(row => {
          row.querySelectorAll('td[data-col]').forEach(td => {
            const col = td.getAttribute('data-col');
            td.style.display = (!schema || schema[col] !== undefined) ? '' : 'none';
          });
        });
      }
      applyToModalRows();

      // Watch for new rows injected by fetchModalRecords
      const tbody = modalBox.querySelector('tbody');
      if (tbody._modalColObserver) tbody._modalColObserver.disconnect();
      if (schema) {
        const obs = new MutationObserver(() => applyToModalRows());
        obs.observe(tbody, { childList: true });
        tbody._modalColObserver = obs;
      } else {
        tbody._modalColObserver = null;
      }
    }

    // ── Modal table: API pagination ──
    let modalPage = 1, modalLast = 1;

    async function fetchModalRecords(page=1){
      modalPage = page || 1;
      const params = new URLSearchParams();
      const ms   = document.getElementById('modalSearch');
      const mc   = document.getElementById('modalFilterCampus');
      const mt   = document.getElementById('modalFilterType');
      const mst  = document.getElementById('modalFilterStatus');
      const mcol  = document.getElementById('modalFilterCollege');
      const mprog = document.getElementById('modalFilterProgram');
      if(ms?.value.trim())   params.set('q',       ms.value.trim());
      if(mt?.value)          params.set('type',    mt.value);
      if(mst?.value)         params.set('status',  mst.value);
      if(mc?.value)          params.set('campus',  mc.value);
      if(mcol?.value)        params.set('college', mcol.value);
      if(mprog?.value)       params.set('program', mprog.value);
      params.set('page',    modalPage);
      params.set('per_page', 100);
      const mb = document.getElementById('modalTableBody');
      if(mb) mb.innerHTML = '<tr><td colspan="14" style="text-align:center;padding:40px;color:var(--muted);">Loading…</td></tr>';
      try {
        const resp = await fetch('/api/records?'+params, {headers:{'Accept':'application/json'}});
        if(!resp.ok) throw new Error();
        const data = await resp.json();
        const items = data.data || []; modalLast = data.last_page || 1;
        if(mb) mb.innerHTML = items.length
          ? items.map(r=>buildModalRow(r)).join('')
          : '<tr><td colspan="14" style="text-align:center;padding:40px;color:var(--muted);">No records found.</td></tr>';
        document.getElementById('modalPageInfo').textContent = `Page ${modalPage} of ${modalLast}`;
        document.getElementById('modalPrevBtn').disabled = modalPage <= 1;
        document.getElementById('modalNextBtn').disabled = modalPage >= modalLast;
      } catch(e){ console.error(e); }
    }

    document.getElementById('modalPrevBtn')?.addEventListener('click', ()=>{ if(modalPage>1) fetchModalRecords(modalPage-1); });
    document.getElementById('modalNextBtn')?.addEventListener('click', ()=>fetchModalRecords(modalPage+1));
    ['modalSearch','modalFilterCampus','modalFilterType','modalFilterStatus','modalFilterCollege','modalFilterProgram'].forEach(id=>{
      document.getElementById(id)?.addEventListener(id==='modalSearch'?'input':'change', ()=>fetchModalRecords(1));
    });
    document.getElementById('modalFilterType')?.addEventListener('change', function(){ applyModalColumnLayout(this.value); });

    const modalFilterToggleBtn = document.getElementById('modalFilterToggleBtn');
    const modalFilterRowExtra  = document.getElementById('modalFilterRowExtra');
    const modalFilterCount     = document.getElementById('modalFilterCount');
    const modalFilterSelects   = [
      document.getElementById('modalFilterStatus'),
      document.getElementById('modalFilterCampus'),
      document.getElementById('modalFilterType'),
      document.getElementById('modalFilterCollege'),
      document.getElementById('modalFilterProgram'),
    ].filter(Boolean);

    function updateModalFilterCount() {
      if (!modalFilterCount) return;
      const active = modalFilterSelects.filter(s => s.value !== '').length;
      modalFilterCount.textContent = active;
      modalFilterCount.classList.toggle('hidden', active === 0);
      modalFilterToggleBtn?.classList.toggle('active', active > 0);
    }

    modalFilterSelects.forEach(s => s.addEventListener('change', updateModalFilterCount));

    modalFilterToggleBtn?.addEventListener('click', function() {
      const isOpen = modalFilterRowExtra?.classList.contains('filters-open');
      modalFilterRowExtra?.classList.toggle('filters-open', !isOpen);
      this.setAttribute('aria-expanded', String(!isOpen));
    });

    // ── Sort (main table) ──
    let mainSortAsc = true;
    document.getElementById('mainSortBtn')?.addEventListener('click', ()=>{
      const tbody = document.getElementById('mainTableBody');
      if(!tbody) return;
      const rows = Array.from(tbody.querySelectorAll('tr.record-row'));
      rows.sort((a,b)=>{
        const ai=(a.querySelector('.record-id')?.textContent||'').trim();
        const bi=(b.querySelector('.record-id')?.textContent||'').trim();
        return mainSortAsc ? ai.localeCompare(bi) : bi.localeCompare(ai);
      });
      rows.forEach(r=>tbody.appendChild(r));
      mainSortAsc = !mainSortAsc;
      const icon = document.getElementById('mainSortIcon');
      if(icon) icon.textContent = mainSortAsc ? '⬆' : '⬇';
    });

    // ── Recently Viewed ──
    const rvContainer = document.getElementById('recentlyViewed');

    window.addRV = function(id, title){
      try {
        let arr = JSON.parse(localStorage.getItem('rv_records')||'[]');
        arr.unshift({id, title});
        arr = arr.filter((v,i,a)=>a.findIndex(x=>x.id===v.id)===i).slice(0,5);
        localStorage.setItem('rv_records', JSON.stringify(arr));
        renderRV();
      } catch(e){}
    };

    function renderRV(){
      try {
        const items = JSON.parse(localStorage.getItem('rv_records')||'[]');
        if(!rvContainer) return;
        rvContainer.innerHTML = items.length
          ? items.map(i=>`<a href="${urlRecordsListPage}?highlight=${encodeURIComponent(i.id)}" class="rv-item">${esc(i.title||i.id)}</a>`).join('')
          : '<p style="font-size:.75rem;color:var(--muted);">No recently viewed records.</p>';
      } catch(e){}
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
      if (document.getElementById('fullTableModal')?.classList.contains('open')) closeModal('fullTableModal');
      else if (document.getElementById('logoutModal')?.classList.contains('open')) closeModal('logoutModal');
      else if (mainSidebar?.classList.contains('mobile-open')) closeMobileSidebar();
    });

    // ── Boot ──
    fetchRecords(1);
    renderRV();
    updateModalFilterCount();

    // ── Mobile Filter Toggle ──
    const filterToggleBtn = document.getElementById('filterToggleBtn');
    const filterRow2      = document.querySelector('.filter-row-2');
    const filterCount     = document.getElementById('filterCount');
    const filterSelects   = document.querySelectorAll('.filter-row-2 .filter-select');

    function updateFilterCount() {
      if (!filterCount) return;
      const active = [...filterSelects].filter(s => s.value !== '').length;
      filterCount.textContent = active;
      filterCount.classList.toggle('hidden', active === 0);
      if (filterToggleBtn) filterToggleBtn.classList.toggle('active', active > 0);
    }

    filterSelects.forEach(s => s.addEventListener('change', updateFilterCount));

    filterToggleBtn?.addEventListener('click', function() {
      const isOpen = filterRow2?.classList.contains('filters-open');
      filterRow2?.classList.toggle('filters-open', !isOpen);
      this.setAttribute('aria-expanded', String(!isOpen));
    });

  })();
  </script>

</body>
</html>