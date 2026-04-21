{{-- resources/views/dev_records.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Records (Dev View)</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon:         #A52C30;
      --maroon2:        #7E1F23;
      --gold:           #F0C860;
      --gold2:          #E8B857;

      /* Dark theme — matches Dev_home */
      --bg:        #080C14;
      --bg2:       #0D1220;
      --bg3:       #111827;
      --card:      #0F1724;
      --card2:     #141E2E;
      --line:      rgba(255,255,255,0.06);
      --line2:     rgba(255,255,255,0.10);
      --ink:       #F1F5F9;
      --ink2:      #CBD5E1;
      --muted:     #64748B;
      --muted2:    #94A3B8;

      --dev-blue:       #3B82F6;
      --dev-blue2:      #2563EB;
      --dev-blue-light: rgba(59,130,246,0.12);
      --dev-blue-mid:   rgba(59,130,246,0.22);

      --green:     #10B981;
      --green-dim: rgba(16,185,129,0.12);
      --red:       #EF4444;
      --red-dim:   rgba(239,68,68,0.12);
      --amber:     #F59E0B;
      --amber-dim: rgba(245,158,11,0.12);

      --sidebar-w: 72px;
      --pad-x: clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max: 1600px;
      --maroon-light: rgba(165, 44, 48, 0.15);
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

    /* scrollbar */
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

    /* ── SIDEBAR — dark navy ── */
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0; width: var(--sidebar-w);
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

    /* ── MAIN LAYOUT ── */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ── TOPBAR ── */
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
      display: flex; align-items: center; gap: 12px;
      min-width: 0; flex: 1 1 auto;
    }
    .topbar-titles { min-width: 0; }
    .page-title {
      font-size: clamp(0.88rem, 0.35vw + 0.8rem, 1rem);
      font-weight: 800; letter-spacing: -.2px; color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-subtitle {
      font-size: clamp(0.65rem, 0.15vw + 0.62rem, 0.7rem);
      color: var(--muted); font-weight: 500; margin-top: 1px;
      overflow-wrap: anywhere;
    }
    .topbar-right {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto; min-width: 0; max-width: 100%;
      justify-content: flex-end;
    }
    .dev-mode-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--dev-blue-light); border: 1.5px solid var(--dev-blue-mid);
      border-radius: 20px; padding: 5px 12px;
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.55rem, 0.1vw + 0.52rem, 0.62rem);
      font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; color: var(--dev-blue);
      flex: 0 1 auto; max-width: 100%;
    }
    .readonly-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--amber-dim); border: 1.5px solid rgba(245,158,11,.25);
      border-radius: 20px; padding: 5px 12px;
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.55rem, 0.1vw + 0.52rem, 0.62rem);
      font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; color: var(--amber);
      flex: 0 1 auto; max-width: 100%;
    }

    /* ── CONTENT ── */
    .content {
      padding: clamp(14px, 2.5vw, 20px) var(--pad-x);
      flex: 1; background: var(--bg);
      width: 100%; max-width: var(--shell-max); margin: 0 auto;
      box-sizing: border-box;
    }

    /* ── HERO ── */
    .hero-card {
      background: linear-gradient(135deg, #0F172A 0%, #1a2744 55%, #0F172A 100%);
      border-radius: 24px;
      padding: clamp(18px, 3vw, 24px) clamp(18px, 3vw, 28px);
      box-shadow: 0 12px 40px rgba(15,23,42,.30);
      position: relative; overflow: hidden; margin-bottom: 20px;
    }
    .hero-card::before {
      content: ''; position: absolute; inset: 0; pointer-events: none;
      background-image: linear-gradient(rgba(59,130,246,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(59,130,246,.05) 1px, transparent 1px);
      background-size: 32px 32px;
    }
    .hero-card::after {
      content: ''; position: absolute; top: -60px; right: -60px;
      width: 240px; height: 240px; border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,.12), transparent 70%);
    }
    .hero-inner {
      position: relative; z-index: 1;
      display: flex; align-items: center; justify-content: space-between;
      gap: 16px 20px; flex-wrap: wrap;
    }
    .hero-inner > div:first-child { flex: 1 1 240px; min-width: 0; }
    .hero-eyebrow { font-size: 0.68rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--dev-blue); opacity: .85; margin-bottom: 6px; }
    .hero-title {
      font-size: clamp(1.05rem, 2vw + 0.55rem, 1.25rem);
      font-weight: 800; color: #fff; letter-spacing: -.3px; margin-bottom: 6px;
      overflow-wrap: anywhere;
    }
    .hero-sub {
      font-size: clamp(0.74rem, 0.25vw + 0.7rem, 0.8rem);
      color: rgba(255,255,255,.6); line-height: 1.6; max-width: 64ch;
      overflow-wrap: anywhere;
    }
    .btn-hero-full {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      background: rgba(59,130,246,.15); border: 1.5px solid rgba(59,130,246,.3);
      color: #93C5FD; border-radius: 12px;
      padding: clamp(8px, 1.2vw, 10px) clamp(14px, 2.5vw, 18px);
      font-family: inherit;
      font-size: clamp(0.72rem, 0.18vw + 0.68rem, 0.78rem);
      font-weight: 700; cursor: pointer;
      transition: background .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-hero-full:hover { background: rgba(59,130,246,.25); }

    /* ── FILTER CARD ── */
    .filter-card { background: var(--card); border-radius: 22px; padding: 22px 24px; border: 1px solid var(--line); box-shadow: 0 4px 20px rgba(0,0,0,.2); margin-bottom: 20px; }
    .filter-card-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
    .filter-title {
      font-size: clamp(0.92rem, 0.3vw + 0.85rem, 1rem);
      font-weight: 800; letter-spacing: -.2px; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .filter-sub   { font-size: 0.75rem; color: var(--muted); margin-top: 2px; overflow-wrap: anywhere; }
    .filter-row-1 { display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 10px; }
    .filter-row-2 { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px; margin-bottom: 14px; }
    .filter-input, .filter-select {
      width: 100%; border-radius: 12px; border: 1.5px solid var(--line2);
      background: var(--bg2); padding: 10px 14px;
      font-family: inherit; font-size: 0.8rem; font-weight: 600; color: var(--ink);
      outline: none; appearance: none; transition: border-color .18s, box-shadow .18s;
    }
    .filter-input:focus, .filter-select:focus { border-color: var(--dev-blue); box-shadow: 0 0 0 3px var(--dev-blue-light); }
    .filter-input::placeholder { color: var(--muted); font-weight: 500; }
    .filter-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
    .btn-search {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: linear-gradient(135deg, var(--dev-blue2), var(--dev-blue));
      color: #fff; border: none; cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.5vw, 20px);
      border-radius: 12px;
      box-shadow: 0 4px 14px rgba(59,130,246,.22); transition: all .18s;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-search:hover { box-shadow: 0 8px 20px rgba(59,130,246,.35); transform: translateY(-1px); }
    .btn-outline {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: var(--bg2); border: 1.5px solid var(--line2);
      color: var(--muted2); cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.2vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: clamp(9px, 1.2vw, 10px) clamp(14px, 2.2vw, 18px);
      border-radius: 12px; transition: all .18s; text-decoration: none;
      flex: 0 1 auto; max-width: 100%; box-sizing: border-box;
    }
    .btn-outline:hover { background: var(--bg3); color: var(--ink2); border-color: var(--line2); }
    .result-hint { font-size: 0.72rem; color: var(--muted); margin-top: 10px; }

    /* ── RECORDS GRID ── */
    .records-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 280px); gap: 16px; align-items: start; }

    /* ── TABLE CARD ── */
    .table-card { background: var(--card); border-radius: 22px; border: 1px solid var(--line); box-shadow: 0 4px 24px rgba(0,0,0,.25); overflow: hidden; }
    .table-card-header {
      padding: 20px 24px; border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between; gap: 12px;
      flex-wrap: wrap;
      background: linear-gradient(90deg, rgba(59,130,246,.06), rgba(0,0,0,.02));
    }
    .table-card-header > div:first-child { min-width: 0; flex: 1 1 200px; }
    .table-card-title {
      font-size: clamp(0.92rem, 0.3vw + 0.85rem, 1rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .table-card-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 2px; overflow-wrap: anywhere; }
    .table-card-body  { padding: 20px 24px; }
    .table-wrap { overflow: auto; max-height: 66vh; border-radius: 14px; border: 1px solid var(--line); background: var(--bg2); }
    table { min-width: 100%; border-collapse: collapse; font-size: 0.78rem; }
    thead th {
      background: var(--bg3); position: sticky; top: 0; z-index: 10;
      padding: 12px 14px; text-align: left;
      font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
      letter-spacing: .05em; color: var(--muted);
      border-bottom: 1px solid var(--line); white-space: nowrap;
    }
    thead th:first-child { cursor: pointer; transition: color .15s; }
    thead th:first-child:hover { color: var(--dev-blue); }
    tbody tr { border-bottom: 1px solid var(--line); transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(59,130,246,.05); }
    tbody td { padding: 11px 14px; vertical-align: top; color: var(--ink2); }
    .cell-id    { font-weight: 800; color: var(--dev-blue); white-space: nowrap; font-family: 'DM Mono', monospace; font-size: .72rem; }
    .cell-title { font-weight: 700; color: var(--ink); overflow-wrap: anywhere; }
    .cell-muted { color: var(--muted2); font-weight: 600; }
    .cell-link  { font-weight: 700; color: var(--dev-blue); text-decoration: underline; }
    .action-btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 5px;
      padding: 5px 12px; border-radius: 8px; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.66rem, 0.12vw + 0.63rem, 0.72rem);
      font-weight: 700;
      background: var(--dev-blue-light); color: var(--dev-blue);
      border: 1.5px solid var(--dev-blue-mid);
      transition: background .18s, box-shadow .18s, transform .15s;
      text-decoration: none;
      max-width: 100%; box-sizing: border-box;
      flex: 0 1 auto;
    }
    .action-btn:hover { background: var(--dev-blue); color: #fff; box-shadow: 0 4px 14px rgba(59,130,246,.35); transform: translateY(-1px); }

    /* table footer */
    .table-footer {
      padding: 14px clamp(14px, 3vw, 24px); border-top: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 10px 14px;
    }
    .table-footer-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
    .page-info { font-size: .72rem; color: var(--muted); font-family: 'DM Mono', monospace; }
    .page-btn {
      padding: 6px 14px; border-radius: 9px; border: 1.5px solid var(--line);
      background: var(--bg2); font-family: inherit; font-size: .75rem; font-weight: 700;
      color: var(--muted2); cursor: pointer; transition: all .15s;
    }
    .page-btn:hover:not(:disabled) { border-color: var(--dev-blue); color: var(--dev-blue); background: var(--dev-blue-light); }
    .page-btn:disabled { opacity: .4; cursor: not-allowed; }

    /* Status badges */
    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 999px; font-size: 0.65rem; font-weight: 800; border: 1.5px solid; white-space: nowrap; }
    .badge-registered { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .badge-review     { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
    .badge-filed      { background: #f8fafc; color: #475569; border-color: #cbd5e1; }
    .badge-attention  { background: #fffbeb; color: #92400e; border-color: #fde68a; }
    .badge-returned   { background: #fff1f2; color: #9f1239; border-color: #fecdd3; }
    .badge-expiring   { background: #fff7ed; color: #c2410c; border-color: #fed7aa; }
    .badge-default    { background: var(--bg); color: var(--muted); border-color: var(--line); }

    /* ── RIGHT COLUMN ── */
    .right-col { display: flex; flex-direction: column; gap: 16px; }
    .panel-card { background: var(--card); border-radius: 20px; padding: 20px; border: 1px solid var(--line); box-shadow: 0 4px 20px rgba(0,0,0,.2); }
    .panel-card-header { padding: 16px 18px; border-bottom: 1px solid var(--line); background: linear-gradient(90deg, rgba(59,130,246,.06), rgba(0,0,0,.02)); border-radius: 18px 18px 0 0; margin: -20px -20px 16px; }
    .panel-card-title { font-size: 0.85rem; font-weight: 800; color: var(--ink); }
    .panel-card-sub   { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
    .rv-item { display: block; padding: 9px 12px; border-radius: 10px; background: var(--bg2); border: 1px solid var(--line); font-size: 0.75rem; font-weight: 700; color: var(--dev-blue); text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: all .15s; margin-bottom: 6px; }
    .rv-item:hover { background: var(--dev-blue-light); border-color: var(--dev-blue-mid); }

    /* ── MODAL ── */
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
      position: relative; box-shadow: 0 32px 80px rgba(0,0,0,.5);
      animation: fadeUp .3s forwards;
      box-sizing: border-box;
    }
    .modal-box-xl {
      width: min(1200px, calc(100vw - 2rem)); max-width: 100%;
      max-height: min(90vh, 90dvh);
      display: flex; flex-direction: column; padding: 0; overflow: hidden;
      box-sizing: border-box;
    }
    .modal-icon { width: 52px; height: 52px; border-radius: 16px; background: rgba(239,68,68,.12); color: var(--red); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
    .modal-title { font-size: clamp(1rem, 0.35vw + 0.92rem, 1.1rem); font-weight: 800; color: var(--ink); overflow-wrap: anywhere; }
    .modal-desc  { font-size: 0.82rem; color: var(--muted2); margin-top: 6px; line-height: 1.6; overflow-wrap: anywhere; }
    .modal-btns  { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px; align-items: stretch; }
    .modal-btns .btn-cancel { flex: 1 1 120px; min-width: 0; justify-content: center; }
    .modal-btns form { flex: 1 1 140px; min-width: 0; display: flex; }
    .modal-btns form .btn-confirm { width: 100%; justify-content: center; }
    .btn-cancel  {
      padding: 12px; border-radius: 12px; background: var(--bg2); border: 1.5px solid var(--line2);
      font-family: inherit;
      font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem);
      font-weight: 700; color: var(--muted2); cursor: pointer; transition: all .18s;
      display: inline-flex; align-items: center; box-sizing: border-box;
    }
    .btn-cancel:hover { border-color: var(--dev-blue); color: var(--dev-blue); background: var(--dev-blue-light); }
    .btn-confirm {
      padding: 12px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon)); border: none;
      font-family: inherit;
      font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem);
      font-weight: 700; color: #fff; cursor: pointer;
      box-shadow: 0 6px 16px rgba(165,44,48,.3); transition: all .18s;
      display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;
    }
    .btn-confirm:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(165,44,48,.4); }
    .modal-close-x { position: absolute; top: 14px; right: 14px; width: 32px; height: 32px; border-radius: 9px; background: var(--bg2); border: 1.5px solid var(--line2); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted2); transition: all .15s; }
    .modal-close-x:hover { background: var(--dev-blue-light); color: var(--dev-blue); border-color: var(--dev-blue-mid); }
    .modal-xl-header { padding: 20px 24px; border-bottom: 1px solid var(--line); flex-shrink: 0; }
    .modal-xl-filters { padding: 14px clamp(14px, 3vw, 24px); border-bottom: 1px solid var(--line); display: flex; flex-wrap: wrap; gap: 8px; flex-shrink: 0; background: var(--bg2); align-items: stretch; }
    .modal-filter-input, .modal-filter-select {
      border-radius: 10px; border: 1.5px solid var(--line2); background: var(--bg3);
      padding: 8px 12px; font-family: inherit; font-size: .78rem; font-weight: 600; color: var(--ink);
      outline: none; appearance: none; transition: border-color .18s;
      min-width: 0; max-width: 100%; box-sizing: border-box;
    }
    .modal-filter-input { flex: 1 1 200px; }
    .modal-filter-select { flex: 0 1 160px; min-width: 120px; }
    .modal-filter-input:focus, .modal-filter-select:focus { border-color: var(--dev-blue); box-shadow: 0 0 0 3px var(--dev-blue-light); }
    .modal-xl-body { overflow-y: auto; flex: 1; padding: 0 clamp(14px, 3vw, 24px); }
    .modal-xl-footer {
      padding: 14px clamp(14px, 3vw, 24px); border-top: 1px solid var(--line);
      flex-shrink: 0;
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 10px 14px;
    }
    .modal-xl-footer-actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }

    /* ── TOAST ── */
    .toast {
      position: fixed; top: 88px; right: 20px; z-index: 9999;
      min-width: 0; max-width: calc(100vw - 2rem); width: min(360px, calc(100vw - 2rem));
      padding: 14px 18px; border-radius: 14px;
      box-shadow: 0 10px 40px rgba(15,23,42,.15);
      font-weight: 700; font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      display: flex; align-items: center; gap: 10px; animation: toastIn .3s ease-out;
      box-sizing: border-box;
    }

    @media (max-width: 1100px) {
      .records-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 900px) {
      .filter-row-2 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
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
      .filter-row-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .filter-actions .btn-search,
      .filter-actions .btn-outline { flex: 1 1 140px; justify-content: center; }
      .table-wrap { max-height: min(66vh, 65dvh); }
    }
    @media (max-width: 580px) {
      .filter-row-2 { grid-template-columns: minmax(0, 1fr); }
    }
    @media (max-width: 480px) {
      .modal-btns { flex-direction: column; }
      .modal-btns .btn-cancel,
      .modal-btns form { flex: 0 0 auto; width: 100%; }
      .action-btn { white-space: normal; text-align: center; }
      .page-btn { flex: 1 1 auto; min-width: 0; }
    }
    .toast.success { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: #2a1a0b; border-left: 4px solid var(--maroon); }
    .toast.error   { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border-left: 4px solid #b91c1c; }
    .toast.hiding  { animation: toastOut .3s ease-out; }
    @keyframes toastIn  { from { transform: translateX(400px); opacity:0; } to { transform: translateX(0); opacity:1; } }
    @keyframes toastOut { from { transform: translateX(0); opacity:1; } to { transform: translateX(400px); opacity:0; } }
    @keyframes fadeUp   { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:none} }
  </style>
</head>

<body>
@php
  $userName        = session('user_name', 'Developer');
  $userAvatarImage = session('user_avatar_image', null);
  $userInitials    = collect(explode(' ', $userName))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');
@endphp

{{-- Mobile sidebar backdrop (same pattern as home / records) --}}
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
      {{ $userName }}<br>
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
      <div class="topbar-titles">
        <div class="page-title">IP Records</div>
        <div class="page-subtitle">Browse &amp; filter IP records · Read-only</div>
      </div>
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
    <div class="hero-card">
      <div class="hero-inner">
        <div>
          <div class="hero-eyebrow">Developer View · Read-Only Access</div>
          <div class="hero-title">Browse &amp; inspect IP records.</div>
          <div class="hero-sub">Search, filter, and view all records for troubleshooting and verification. No editing or adding is available in this view.</div>
        </div>
        <button id="openFullPageBtn" type="button" class="btn-hero-full">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>
          </svg>
          View All Records
        </button>
      </div>
    </div>

    {{-- FILTERS --}}
    <div class="filter-card">
      <div class="filter-card-header">
        <div>
          <div class="filter-title">Search &amp; Filter</div>
          <div class="filter-sub">Narrow down records by any field</div>
        </div>
      </div>
      <div class="filter-row-1">
        <input type="search" id="viewAllSearch" class="filter-input" placeholder="Search by title, owner, record ID…">
      </div>
      <div class="filter-row-2">
        <select id="filterStatus" class="filter-select">
          <option value="">All status</option>
          @foreach($statuses as $st)
            <option value="{{ $st }}">{{ $st }}</option>
          @endforeach
        </select>
        <select id="filterCampus" class="filter-select">
          <option value="">All campuses</option>
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
      <div class="filter-actions">
        <button type="button" id="applySearchBtn" class="btn-search">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
          Search
        </button>
        <button type="button" id="resetFiltersBtn" class="btn-outline">Reset</button>
        <button id="openFullPageBtnFilter" type="button" class="btn-outline">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>
          </svg>
          View All Records
        </button>
      </div>
      <div id="resultHint" class="result-hint">Showing all records.</div>
    </div>

    {{-- RECORDS GRID --}}
    <div class="records-grid">

      {{-- TABLE --}}
      <div class="table-card">
        <div class="table-card-header">
          <div>
            <div class="table-card-title">All IP Records</div>
            <div class="table-card-sub">Read-only · Developer view</div>
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
                <tr><td colspan="16" style="text-align:center;padding:40px;color:var(--muted);">Loading…</td></tr>
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
              <div style="width:36px;height:36px;border-radius:10px;background:var(--dev-blue-light);color:var(--dev-blue);display:flex;align-items:center;justify-content:center;">
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

        {{-- Dev Access Info --}}
        <div class="panel-card">
          <div class="panel-card-header">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:36px;height:36px;border-radius:10px;background:var(--dev-blue-light);color:var(--dev-blue);display:flex;align-items:center;justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                </svg>
              </div>
              <div>
                <div class="panel-card-title">Dev Access</div>
                <div class="panel-card-sub">Your permissions on records</div>
              </div>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px;">
            @php $rows = [['View records','yes'],['Filter & search','yes'],['GDrive links','yes'],['Create / Edit','no'],['Delete records','no']]; @endphp
            @foreach($rows as [$label, $val])
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line);">
              <span style="font-size:.78rem;font-weight:600;color:var(--ink);">{{ $label }}</span>
              <span style="font-size:.65rem;font-weight:800;padding:2px 8px;border-radius:999px;
                {{ $val === 'yes' ? 'background:rgba(16,185,129,.1);color:#059669;' : 'background:var(--maroon-light);color:var(--maroon);' }}">
                {{ $val === 'yes' ? 'Yes' : 'No' }}
              </span>
            </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>

  </div>{{-- end content --}}
</div>{{-- end main-wrap --}}

{{-- LOGOUT MODAL --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal-box">
    <button type="button" class="modal-close-x" id="cancelLogout">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
    <div class="modal-icon">
      <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>
    <div class="modal-title">Sign Out?</div>
    <div class="modal-desc">You'll be returned to the login page.</div>
    <div class="modal-btns">
      <button type="button" class="btn-cancel" id="cancelLogout2">Cancel</button>
      <form method="POST" action="{{ url('/logout') }}">
        @csrf
        <button type="submit" class="btn-confirm">Sign Out</button>
      </form>
    </div>
  </div>
</div>

{{-- FULL TABLE MODAL --}}
<div class="modal-overlay" id="fullTableModal">
  <div class="modal-box modal-box-xl">
    <button type="button" class="modal-close-x" data-close-fullpage style="position:absolute;top:14px;right:14px;z-index:10;">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
    <div class="modal-xl-header">
      <div style="font-size:1.05rem;font-weight:800;color:var(--ink);">All IP Records</div>
      <div style="font-size:.72rem;color:var(--muted);margin-top:2px;">Complete list · read-only</div>
    </div>
    <div class="modal-xl-filters">
      <input id="modalSearch" type="search" placeholder="Search any column…" class="modal-filter-input" />
      <select id="modalFilterStatus" class="modal-filter-select">
        <option value="">All status</option>
        @foreach($statuses as $st)<option value="{{ $st }}">{{ $st }}</option>@endforeach
      </select>
      <select id="modalFilterCampus" class="modal-filter-select">
        <option value="">All campus</option>
        @foreach($campuses as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
      </select>
      <select id="modalFilterType" class="modal-filter-select">
        <option value="">All categories</option>
        @foreach($types as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
      </select>
      <select id="modalFilterCollege" class="modal-filter-select">
        <option value="">All colleges</option>
        @foreach($colleges ?? [] as $col)<option value="{{ $col }}">{{ $col }}</option>@endforeach
      </select>
      <select id="modalFilterProgram" class="modal-filter-select">
        <option value="">All programs</option>
        @foreach($programs ?? [] as $prog)<option value="{{ $prog }}">{{ $prog }}</option>@endforeach
      </select>
    </div>
    <div class="modal-xl-body">
      <table style="min-width:100%;border-collapse:collapse;font-size:.78rem;">
        <thead>
          <tr>
            <th id="modalSortBtn" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;cursor:pointer;">
              Record ID <span id="modalSortIcon" style="opacity:.5;">⇅</span>
            </th>
            <th data-col="title"       style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);min-width:260px;">IP Title</th>
            <th data-col="category"    style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Category</th>
            <th data-col="owner"       style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);min-width:200px;">Owner / Inventor</th>
            <th data-col="campus"      style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Campus</th>
            <th data-col="college"     style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">College</th>
            <th data-col="program"     style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Program</th>
            <th data-col="classofwork" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Class of Work</th>
            <th data-col="status"      style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Status</th>
            <th data-col="datecreated" style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Date Created</th>
            <th data-col="registered"  style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Date Registered</th>
            <th data-col="regnumber"   style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Reg. Number</th>
            <th data-col="gdrive"      style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);min-width:100px;">GDrive</th>
            <th data-col="actions"     style="background:var(--bg);position:sticky;top:0;z-index:5;padding:11px 14px;text-align:left;font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted);border-bottom:1px solid var(--line);white-space:nowrap;">Actions</th>
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
  const urlRecordsListPage = '{{ url('/dev/records') }}';
  const SCROLL_LOCK_MODAL_IDS = ['logoutModal', 'fullTableModal'];

  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const anyModal = SCROLL_LOCK_MODAL_IDS.some(id => document.getElementById(id)?.classList.contains('open'));
    document.body.style.overflow = (sidebarOpen || anyModal) ? 'hidden' : '';
  }

  function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

  // ── Modal helpers ──
  function openModal(id)  { document.getElementById(id)?.classList.add('open');    syncBodyScrollLock(); }
  function closeModal(id) { document.getElementById(id)?.classList.remove('open'); syncBodyScrollLock(); }

  // Logout
  document.getElementById('logoutBtn')?.addEventListener('click', ()=>openModal('logoutModal'));
  document.getElementById('cancelLogout')?.addEventListener('click', ()=>closeModal('logoutModal'));
  document.getElementById('cancelLogout2')?.addEventListener('click', ()=>closeModal('logoutModal'));
  document.getElementById('logoutModal')?.addEventListener('click', e=>{ if(e.target.id==='logoutModal') closeModal('logoutModal'); });

  // Full table modal
  ['openFullPageBtn','openFullPageBtnFilter'].forEach(id=>{
    document.getElementById(id)?.addEventListener('click', ()=>{ openModal('fullTableModal'); fetchModalRecords(1); });
  });
  document.querySelectorAll('[data-close-fullpage]').forEach(b=>b.addEventListener('click',()=>closeModal('fullTableModal')));
  document.getElementById('fullTableModal')?.addEventListener('click',e=>{ if(e.target.id==='fullTableModal') closeModal('fullTableModal'); });

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

  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    const ft = document.getElementById('fullTableModal');
    if (ft?.classList.contains('open')) { closeModal('fullTableModal'); return; }
    const lo = document.getElementById('logoutModal');
    if (lo?.classList.contains('open')) { closeModal('logoutModal'); return; }
    if (mainSidebar?.classList.contains('mobile-open')) closeMobileSidebar();
  });

  // ── Badge map ──
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
      if(typeLow==='patent')            { d.setFullYear(d.getFullYear()+20); due=d.toLocaleDateString(); validity='20 yrs'; }
      else if(typeLow==='copyright')    { d.setFullYear(d.getFullYear()+70); due=d.toLocaleDateString(); validity='70 yrs'; }
      else if(typeLow==='utility model'){ d.setFullYear(d.getFullYear()+10); due=d.toLocaleDateString(); validity='10 yrs'; }
      else if(typeLow==='industrial design'){ d.setFullYear(d.getFullYear()+15); due=d.toLocaleDateString(); validity='15 yrs'; }
      else if(typeLow==='trademark')    { d.setFullYear(d.getFullYear()+10); due=d.toLocaleDateString(); validity='10 yrs'; }
    }} catch(e){}
    return `<tr class="record-row" style="border-bottom:1px solid var(--line);cursor:pointer;" onclick="addRV('${id}','${esc(r.ip_title||r.title||'')}')">
      <td class="cell-id record-id">${id}</td>
      <td data-col="title"><span class="cell-title">${esc(r.ip_title||r.title||'—')}</span></td>
      <td data-col="category" class="cell-muted" style="white-space:nowrap;">${esc(r.category||r.type||'—')}</td>
      <td data-col="owner" class="cell-muted">${esc(r.owner_inventor||r.owner||'—')}</td>
      <td data-col="campus" class="cell-muted" style="white-space:nowrap;">${esc(r.campus||'—')}</td>
      <td data-col="college" class="cell-muted" style="white-space:nowrap;">${esc(r.college||'—')}</td>
      <td data-col="program" class="cell-muted" style="white-space:nowrap;">${esc(r.program||'—')}</td>
      <td data-col="classofwork" class="cell-muted" style="white-space:nowrap;">${esc(r.class_of_work||'—')}</td>
      <td data-col="status" style="white-space:nowrap;"><span class="badge ${bc}">${esc(r.status||'—')}</span></td>
      <td data-col="datecreated" class="cell-muted" style="white-space:nowrap;">${esc(created)}</td>
      <td data-col="registered" class="cell-muted" style="white-space:nowrap;">${esc(reg)}</td>
      <td data-col="nextdue" class="cell-muted" style="white-space:nowrap;">${esc(due)}</td>
      <td data-col="validity" class="cell-muted" style="white-space:nowrap;">${esc(validity)}</td>
      <td data-col="regnumber" class="cell-muted" style="white-space:nowrap;">${esc(r.registration_number||'—')}</td>
      <td data-col="gdrive">${r.gdrive_link?`<a href="${esc(r.gdrive_link)}" target="_blank" class="cell-link">Open file</a>`:'<span class="cell-muted">—</span>'}</td>
      <td data-col="actions" style="white-space:nowrap;"><button type="button" class="action-btn" onclick="event.stopPropagation();window.location.href='/dev/recorddetail/${id}'">View</button></td>
    </tr>`;
  }

  function buildModalRow(r){
    const rawReg = r.date_registered_deposited || r.registered || null;
    const reg = rawReg ? new Date(rawReg).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'2-digit'}) : '—';
    const rawCreated = r.date_creation || null;
    const created = rawCreated ? new Date(rawCreated).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'2-digit'}) : '—';
    const bc = badgeMap[r.status] || 'badge-default';
    const id = esc(r.record_id||r.id||'');
    return `<tr style="border-bottom:1px solid var(--line);transition:background .15s;" onmouseover="this.style.background='rgba(59,130,246,.03)'" onmouseout="this.style.background=''">
      <td style="padding:10px 14px;font-family:'DM Mono',monospace;font-size:.7rem;font-weight:800;color:var(--dev-blue);white-space:nowrap;">${esc(r.record_id||r.id||'—')}</td>
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
      <td data-col="gdrive" style="padding:10px 14px;">${r.gdrive_link?`<a href="${esc(r.gdrive_link)}" target="_blank" style="font-weight:700;color:var(--dev-blue);text-decoration:underline;">Open file</a>`:'<span style="color:var(--muted);">—</span>'}</td>
      <td data-col="actions" style="padding:10px 14px;white-space:nowrap;"><button type="button" class="action-btn" onclick="event.stopPropagation();window.location.href='/dev/recorddetail/${id}'">View</button></td>
    </tr>`;
  }

  // ── Main table ──
  const q = document.getElementById('viewAllSearch');
  const campus  = document.getElementById('filterCampus');
  const type    = document.getElementById('filterType');
  const status  = document.getElementById('filterStatus');
  const college = document.getElementById('filterCollege');
  const program = document.getElementById('filterProgram');
  const resultHint = document.getElementById('resultHint');
  let currentPage = 1, lastPage = 1;

  async function fetchRecords(page=1){
    currentPage = page || 1;
    const params = new URLSearchParams();
    if(q?.value.trim())    params.set('q',       q.value.trim());
    if(type?.value)        params.set('type',    type.value);
    if(status?.value)      params.set('status',  status.value);
    if(campus?.value)      params.set('campus',  campus.value);
    if(college?.value)     params.set('college', college.value);
    if(program?.value)     params.set('program', program.value);
    params.set('page', currentPage); params.set('per_page', 50);
    const tbody = document.getElementById('mainTableBody');
    if(tbody) tbody.innerHTML = '<tr><td colspan="16" style="text-align:center;padding:40px;color:var(--muted);">Loading…</td></tr>';
    try {
      const resp = await fetch('/api/records?'+params, {headers:{'Accept':'application/json'}});
      if(!resp.ok) throw new Error();
      const data = await resp.json();
      const items = data.data || []; lastPage = data.last_page || 1;
      if(tbody) tbody.innerHTML = items.length
        ? items.map(r=>buildRow(r)).join('')
        : '<tr><td colspan="16" style="text-align:center;padding:40px;color:var(--muted);">No records found.</td></tr>';
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
    fetchRecords(1);
  });
  q?.addEventListener('keypress', e=>{ if(e.key==='Enter') fetchRecords(1); });
  [campus, type, status, college, program].forEach(el=>el?.addEventListener('change', ()=>fetchRecords(1)));
  document.getElementById('prevPageBtn')?.addEventListener('click', ()=>{ if(currentPage>1) fetchRecords(currentPage-1); });
  document.getElementById('nextPageBtn')?.addEventListener('click', ()=>fetchRecords(currentPage+1));

  // ── Modal table ──
  let modalPage = 1, modalLast = 1;
  async function fetchModalRecords(page=1){
    modalPage = page || 1;
    const params = new URLSearchParams();
    const ms   = document.getElementById('modalSearch');
    const mc   = document.getElementById('modalFilterCampus');
    const mt   = document.getElementById('modalFilterType');
    const mst  = document.getElementById('modalFilterStatus');
    const mcol = document.getElementById('modalFilterCollege');
    const mprog= document.getElementById('modalFilterProgram');
    if(ms?.value.trim()) params.set('q',       ms.value.trim());
    if(mt?.value)        params.set('type',    mt.value);
    if(mst?.value)       params.set('status',  mst.value);
    if(mc?.value)        params.set('campus',  mc.value);
    if(mcol?.value)      params.set('college', mcol.value);
    if(mprog?.value)     params.set('program', mprog.value);
    params.set('page', modalPage); params.set('per_page', 100);
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

  // ── Sort ──
  let mainSortAsc = true;
  document.getElementById('mainSortBtn')?.addEventListener('click', ()=>{
    const tbody = document.getElementById('mainTableBody');
    if(!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr.record-row'));
    rows.sort((a,b)=>{ const ai=(a.querySelector('.record-id')?.textContent||'').trim(); const bi=(b.querySelector('.record-id')?.textContent||'').trim(); return mainSortAsc ? ai.localeCompare(bi) : bi.localeCompare(ai); });
    rows.forEach(r=>tbody.appendChild(r));
    mainSortAsc = !mainSortAsc;
    const icon = document.getElementById('mainSortIcon');
    if(icon) icon.textContent = mainSortAsc ? '⬆' : '⬇';
  });

  // ── Recently Viewed ──
  const rvContainer = document.getElementById('recentlyViewed');
  window.addRV = function(id, title){
    try {
      let arr = JSON.parse(localStorage.getItem('dev_rv_records')||'[]');
      arr.unshift({id, title});
      arr = arr.filter((v,i,a)=>a.findIndex(x=>x.id===v.id)===i).slice(0,5);
      localStorage.setItem('dev_rv_records', JSON.stringify(arr));
      renderRV();
    } catch(e){}
  };
  function renderRV(){
    try {
      const items = JSON.parse(localStorage.getItem('dev_rv_records')||'[]');
      if(!rvContainer) return;
      rvContainer.innerHTML = items.length
        ? items.map(i=>`<a href="/dev/recorddetail/${encodeURIComponent(i.id)}" class="rv-item">${esc(i.title||i.id)}</a>`).join('')
        : '<p style="font-size:.75rem;color:var(--muted);">No recently viewed records.</p>';
    } catch(e){}
  }

  // ── Boot ──
  fetchRecords(1);
  renderRV();

})();
</script>
</body>
</html>