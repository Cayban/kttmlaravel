<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Records</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
        .record-owner {
          max-width: 160px;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
        }
        .record-college, .record-program, .record-classofwork {
          max-width: 90px;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }
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

    /* Hamburger (mobile) + drawer backdrop — matches home.blade.php */
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
    /* ── MAIN ── */
    .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* ── TOPBAR ── */
    .topbar {
      min-height: 72px;
      background: var(--card);
      border-bottom: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px 12px;
      padding: 10px var(--pad-x);
      position: sticky;
      top: 0;
      z-index: 40;
      box-shadow: 0 2px 16px rgba(15,23,42,.05);
    }
    .topbar-left {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
      flex: 1 1 auto;
    }
    .page-title {
      font-size: clamp(0.95rem, 0.4vw + 0.85rem, 1.15rem);
      font-weight: 800;
      letter-spacing: -.3px;
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-sub {
      font-size: clamp(0.68rem, 0.2vw + 0.64rem, 0.75rem);
      color: var(--muted);
      font-weight: 500;
      overflow-wrap: anywhere;
    }
    .topbar-search {
      display: flex; align-items: center; gap: 10px;
      background: var(--bg); border: 1.5px solid var(--line);
      border-radius: 12px; padding: 8px 16px;
      width: min(280px, 100%);
      max-width: 280px;
      flex: 1 1 160px;
      min-width: 0;
      transition: border-color .2s, box-shadow .2s;
    }
    .topbar-search:focus-within { border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light); }
    .topbar-search input { background: none; border: none; outline: none; font-family: inherit; font-size: 0.82rem; color: var(--ink); width: 100%; }
    .topbar-search input::placeholder { color: #94a3b8; }
    .topbar-right {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
    }
    .icon-btn {
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted); transition: all .18s;
    }
    .icon-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .btn-primary {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.74rem, 0.15vw + 0.7rem, 0.8rem);
      font-weight: 700;
      padding: 10px clamp(12px, 2.5vw, 20px);
      border-radius: 12px;
      text-decoration: none;
      box-shadow: 0 6px 18px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s;
      width: auto;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(165,44,48,.35); }
    .btn-gold {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b;
      border: none;
      cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 18px);
      border-radius: 11px;
      text-decoration: none;
      box-shadow: 0 4px 14px rgba(240,200,96,.30);
      transition: transform .18s, opacity .18s;
      width: auto;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-gold:hover { transform: translateY(-1px); opacity: .9; }
    .btn-gold-compact {
      padding: 7px 14px;
      font-size: clamp(0.7rem, 0.12vw + 0.66rem, 0.75rem);
    }
    .btn-outline {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      background: var(--card);
      color: var(--muted);
      border: 1.5px solid var(--line);
      cursor: pointer;
      font-family: inherit;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 600;
      padding: 9px clamp(10px, 2vw, 18px);
      border-radius: 11px;
      text-decoration: none;
      transition: background .18s, border-color .18s, color .18s;
      width: auto;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-outline:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .btn-howto {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      background: var(--bg);
      border: 1.5px solid var(--line);
      color: var(--muted);
      cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      font-weight: 700;
      padding: 9px clamp(10px, 2vw, 16px);
      border-radius: 12px;
      transition: all .18s;
      width: auto;
      max-width: 100%;
      flex: 0 1 auto;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-howto:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .avatar {
      width: 40px; height: 40px; border-radius: 12px;
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.85rem; color: #2a1a0b; cursor: pointer;
    }

    /* ── FILTER PANEL (compact, readable) ── */
    .filter-panel {
      background: var(--card); border-radius: 16px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 10px rgba(15,23,42,.04);
      padding: 14px 18px; margin-bottom: 16px;
    }
    .filter-panel-top {
      display: flex; flex-wrap: wrap; align-items: center;
      justify-content: space-between; gap: 10px; margin-bottom: 12px;
    }
    .filter-panel-actions {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
      align-items: center;
      min-width: 0;
    }
    .filter-panel .btn-outline {
      padding: 7px 12px;
      font-size: clamp(0.68rem, 0.12vw + 0.64rem, 0.74rem);
      border-radius: 10px;
      gap: 6px;
    }
    .filter-panel .btn-outline svg {
      width: 13px; height: 13px;
    }
    .filter-eyebrow {
      display: inline-flex; align-items: center; gap: 6px;
      font-family: 'DM Mono', monospace;
      font-size: 0.56rem; letter-spacing: 0.18em; text-transform: uppercase;
      color: var(--maroon); background: var(--maroon-light);
      border: 1px solid rgba(165,44,48,.18); padding: 0.22rem 0.65rem; border-radius: 16px;
    }
    .filter-eyebrow svg { width: 9px; height: 9px; flex-shrink: 0; }
    .filter-title {
      font-size: clamp(0.92rem, 0.3vw + 0.85rem, 1.02rem);
      font-weight: 800;
      letter-spacing: -.25px;
      margin-top: 3px;
      line-height: 1.22;
      overflow-wrap: break-word;
    }
    .filter-grid {
      display: grid;
      grid-template-columns: minmax(0, 2fr) repeat(3, minmax(0, 1fr));
      gap: 8px;
      align-items: end;
    }
    .field-wrap label {
      display: block; font-size: 0.6rem; font-weight: 700;
      letter-spacing: .07em; text-transform: uppercase;
      color: var(--muted); margin-bottom: 4px;
    }
    .field-input, .field-select {
      width: 100%; padding: 7px 11px;
      border: 1.5px solid var(--line);
      background: var(--bg); border-radius: 9px;
      font-family: inherit; font-size: 0.78rem; color: var(--ink);
      outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .field-input:focus, .field-select:focus {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light);
    }
    .filter-actions {
      display: flex;
      gap: 6px;
      flex-wrap: wrap;
      align-items: center;
      margin-top: 10px;
    }
    .filter-actions .btn-primary,
    .filter-actions .btn-outline {
      flex: 0 1 auto;
      padding: 7px 14px;
      font-size: clamp(0.7rem, 0.15vw + 0.66rem, 0.75rem);
      border-radius: 10px;
      gap: 6px;
    }
    .filter-actions .btn-primary svg,
    .filter-actions .btn-outline svg {
      width: 13px; height: 13px;
    }
    .filter-actions #resultHint,
    .filter-actions .result-hint {
      flex: 1 1 10rem;
      min-width: 0;
      margin-left: 0 !important;
    }
    .result-hint {
      font-size: 0.66rem;
      color: var(--muted);
      align-self: center;
      font-family: 'DM Mono', monospace;
      overflow-wrap: anywhere;
    }

    /* ── MAIN TABLE GRID ── */
    .records-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 300px);
      gap: 16px;
      align-items: start;
    }
    .records-table-full {
      width: 100%;
      min-width: 0;
    }

    /* ── TABLE CARD ── */
    .table-card {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden; display: flex; flex-direction: column;
      height: 68vh;
    }
    .table-card-header {
      padding: clamp(14px, 2vw, 18px) clamp(14px, 2.5vw, 22px);
      border-bottom: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px 12px;
      flex-shrink: 0;
    }
    .table-card-title {
      font-size: clamp(0.86rem, 0.25vw + 0.8rem, 0.95rem);
      font-weight: 800;
      letter-spacing: -.2px;
      overflow-wrap: break-word;
    }
    .table-card-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }
    .table-card--delayed { animation-delay: 0.08s; }
    .table-card-header-actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      align-items: center;
      flex: 0 1 auto;
      min-width: 0;
      justify-content: flex-end;
    }
    .table-footer-nav {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      align-items: center;
      flex: 0 1 auto;
    }
    .table-wrap { flex: 1; overflow: auto; }
    .table-wrap::-webkit-scrollbar { width: 6px; height: 6px; }
    .table-wrap::-webkit-scrollbar-thumb { background: rgba(165,44,48,.25); border-radius: 999px; }

    table { width: 100%; border-collapse: collapse; min-width: 900px; }
    thead th {
      padding: 11px 14px; text-align: left;
      font-size: 0.68rem; font-weight: 700; letter-spacing: .08em;
      text-transform: uppercase; color: var(--muted);
      background: var(--bg); white-space: nowrap;
      border-bottom: 1px solid var(--line);
      position: sticky; top: 0; z-index: 5;
    }
    thead th.sortable { cursor: pointer; transition: color .18s; }
    thead th.sortable:hover { color: var(--maroon); }
    tbody tr { border-bottom: 1px solid var(--line); transition: background .15s; }
    tbody tr:hover { background: rgba(165,44,48,.03); }
    tbody td { padding: 11px 14px; font-size: 0.8rem; vertical-align: top; }

    .record-id-cell { font-weight: 800; color: var(--maroon); white-space: nowrap; font-family: 'DM Mono', monospace; font-size: 0.75rem; }
    .record-title-cell .title-text {
      font-weight: 700;
      color: var(--ink);
      overflow-wrap: anywhere;
      line-height: 1.35;
    }
    .status-badge {
      display: inline-flex; align-items: center;
      padding: 3px 10px; border-radius: 20px;
      font-size: 0.68rem; font-weight: 700; white-space: nowrap;
      border: 1px solid transparent;
    }
    .status-registered { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .status-review     { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
    .status-filed      { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
    .status-attention  { background: #fffbeb; color: #92400e; border-color: #fde68a; }
    .status-returned   { background: #fff1f2; color: #9f1239; border-color: #fecdd3; }
    .status-default    { background: #f8fafc; color: #475569; border-color: #e2e8f0; }
    .action-btn {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 11px; border-radius: 8px;
      font-size: 0.72rem; font-weight: 700; cursor: pointer;
      border: 1.5px solid var(--line); background: var(--bg);
      color: var(--muted); transition: background .18s, box-shadow .18s, transform .15s, color .18s, border-color .18s;
      font-family: inherit; white-space: nowrap;
    }
    .action-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .action-btn:active { transform: translateY(0); box-shadow: none; }
    .action-btn.edit  { background: var(--maroon); color: #fff; border-color: var(--maroon); }
    .action-btn.edit:hover { background: var(--maroon2); box-shadow: 0 4px 14px rgba(165,44,48,.35); transform: translateY(-1px); }
    .action-btn.viewBtn { background: var(--maroon-light); color: var(--maroon); border-color: rgba(165,44,48,.2); }
    .action-btn.viewBtn:hover { background: var(--maroon); color: #fff; box-shadow: 0 4px 14px rgba(165,44,48,.35); transform: translateY(-1px); border-color: var(--maroon); }

    .table-footer {
      padding: 12px clamp(12px, 2.5vw, 20px);
      border-top: 1px solid var(--line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px 12px;
      flex-shrink: 0;
    }
    .page-info {
      flex: 1 1 auto;
      min-width: 0;
      overflow-wrap: break-word;
      font-size: 0.72rem;
      color: var(--muted);
      font-family: 'DM Mono', monospace;
    }
    .page-btn {
      padding: 6px 14px;
      border-radius: 9px;
      border: 1.5px solid var(--line);
      background: var(--bg);
      font-family: inherit;
      font-size: 0.75rem;
      font-weight: 700;
      cursor: pointer;
      color: var(--muted);
      transition: all .15s;
      flex: 0 0 auto;
      white-space: nowrap;
    }
    .page-btn:hover:not(:disabled) { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .page-btn:disabled { opacity: 0.38; cursor: default; }

    /* ── UPDATES SIDEBAR ── */
    .updates-card {
      background: var(--card); border-radius: 20px;
      border: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.05);
      overflow: hidden; display: flex; flex-direction: column;
      height: 68vh;
    }
    .updates-header {
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      padding: 16px 18px; flex-shrink: 0;
    }
    .updates-title { font-size: 0.9rem; font-weight: 800; color: #fff; }
    .updates-sub   { font-size: 0.7rem; color: rgba(255,255,255,.65); margin-top: 2px; }
    .updates-header-top { display: flex; align-items: center; justify-content: space-between; }
    .refresh-btn {
      width: 32px; height: 32px; border-radius: 9px;
      background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: #fff; font-size: 1rem; transition: background .15s;
    }
    .refresh-btn:hover { background: rgba(255,255,255,.25); }
    .updates-body { flex: 1; overflow-y: auto; padding: 14px; }
    .updates-body::-webkit-scrollbar { width: 4px; }
    .updates-body::-webkit-scrollbar-thumb { background: rgba(165,44,48,.3); border-radius: 999px; }
    .update-item {
      background: var(--bg); border-radius: 13px;
      border: 1px solid var(--line); padding: 12px;
      margin-bottom: 8px; transition: background .15s;
    }
    .update-item:hover { background: rgba(165,44,48,.04); }
    .update-record-id { font-family: 'DM Mono', monospace; font-size: 0.68rem; font-weight: 700; color: var(--maroon); }
    .update-title     { font-size: 0.75rem; font-weight: 700; color: var(--ink); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .update-type      { font-size: 0.68rem; color: var(--muted); margin-top: 1px; }
    .update-footer    { display: flex; align-items: center; gap: 6px; margin-top: 8px; flex-wrap: wrap; }
    .update-badge     { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 700; border: 1px solid transparent; }
    .update-badge.created  { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
    .update-badge.modified { background: #fffbeb; color: #92400e; border-color: #fde68a; }
    .update-badge.archived { background: #fff1f2; color: #9f1239; border-color: #fecdd3; }
    .update-badge.default  { background: var(--bg); color: var(--muted); border-color: var(--line); }
    .update-time      { font-size: 0.65rem; color: var(--muted); }
    .update-actions {
      display: flex;
      gap: 6px;
      margin-top: 8px;
      flex-wrap: wrap;
      justify-content: flex-start;
    }
    .upd-btn {
      flex: 0 1 auto;
      min-width: min(7rem, 48%);
      max-width: 100%;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.68rem;
      font-weight: 700;
      cursor: pointer;
      border: none;
      font-family: inherit;
      transition: opacity .15s;
      box-sizing: border-box;
    }
    .upd-btn.view   { background: var(--maroon); color: #fff; }
    .upd-btn.view:hover { opacity: .85; }
    .upd-btn.delete { background: #fee2e2; color: #b91c1c; }
    .upd-btn.delete:hover { background: #fecaca; }

    /* ── TOAST ── */
    .toast {
      position: fixed;
      top: max(20px, env(safe-area-inset-top));
      right: max(20px, env(safe-area-inset-right));
      z-index: 9999;
      min-width: min(300px, calc(100vw - 32px));
      max-width: calc(100vw - 32px);
      padding: 14px 18px;
      border-radius: 14px;
      font-weight: 700;
      font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.82rem);
      box-shadow: 0 10px 40px rgba(15,23,42,.15);
      animation: slideIn .3s ease-out;
      font-family: inherit;
      box-sizing: border-box;
      overflow-wrap: break-word;
    }
    .toast.success { background: linear-gradient(135deg, var(--gold), var(--gold2)); color: #2a1a0b; border-left: 4px solid var(--maroon); }
    .toast.error   { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border-left: 4px solid #b91c1c; }
    .toast.hiding  { animation: slideOut .3s ease-out; }
    @keyframes slideIn  { from { transform: translateX(400px); opacity: 0; } to { transform: none; opacity: 1; } }
    @keyframes slideOut { from { transform: none; opacity: 1; } to { transform: translateX(400px); opacity: 0; } }

    /* ── HOW TO USE MODAL ── */
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
    .howto-step-desc  { font-size: 0.76rem; color: var(--muted); line-height: 1.6; font-weight: 500; overflow-wrap: break-word; }
    .howto-step-tag {
      display: inline-flex; align-items: center; gap: 4px; margin-top: 7px;
      font-family: 'DM Mono', monospace; font-size: 0.6rem; font-weight: 700;
      color: var(--maroon); background: var(--maroon-light);
      border: 1px solid rgba(165,44,48,.18); padding: 2px 9px; border-radius: 20px;
      letter-spacing: .04em; text-transform: uppercase;
    }
    .howto-footer {
      padding: 16px clamp(16px, 3vw, 28px);
      border-top: 1px solid var(--line);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px 14px;
      background: linear-gradient(90deg, rgba(165,44,48,.03), rgba(240,200,96,.03));
    }
    .howto-footer-note {
      font-size: 0.72rem;
      color: var(--muted);
      font-weight: 500;
      flex: 1 1 12rem;
      min-width: 0;
      overflow-wrap: break-word;
    }
    .howto-footer .btn-primary-sm {
      flex: 0 1 auto;
      margin-left: auto;
    }
    .howto-footer-note strong { color: var(--maroon); }
    .btn-primary-sm {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 9px 18px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff;
      border: none;
      cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: .76rem;
      font-weight: 700;
      box-shadow: 0 4px 14px rgba(165,44,48,.28);
      transition: transform .18s, box-shadow .18s;
      max-width: 100%;
      box-sizing: border-box;
      white-space: nowrap;
      -webkit-tap-highlight-color: transparent;
    }
    .btn-primary-sm:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(165,44,48,.35); }

    /* ── MODALS ── */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: var(--card);
      border-radius: 20px;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      width: min(560px, calc(100vw - 2rem));
      max-width: 100%;
      max-height: 92vh;
      overflow-y: auto;
      animation: modalIn .3s cubic-bezier(.17,.67,.35,1.1);
      position: relative;
      box-sizing: border-box;
    }
    @keyframes modalIn { from { opacity: 0; transform: translateY(20px) scale(.97); } to { opacity: 1; transform: none; } }
    .modal-header {
      padding: 22px 26px 18px; border-bottom: 1px solid var(--line);
      display: flex; align-items: start; justify-content: space-between; gap: 12px;
      position: sticky; top: 0; background: var(--card); z-index: 5;
    }
    .modal-title-row { }
    .modal-label { font-family: 'DM Mono', monospace; font-size: 0.62rem; letter-spacing: .22em; text-transform: uppercase; color: var(--maroon); margin-bottom: 4px; }
    .modal-title { font-size: 1.2rem; font-weight: 800; letter-spacing: -.3px; }
    .modal-sub   { font-size: 0.78rem; color: var(--muted); margin-top: 3px; }
    .modal-close-btn {
      width: 34px; height: 34px; border-radius: 10px;
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted); font-size: 1rem;
      transition: all .15s; flex-shrink: 0;
    }
    .modal-close-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
    .modal-body { padding: 22px 26px; }
    .modal-footer {
      padding: 16px clamp(14px, 3vw, 26px);
      border-top: 1px solid var(--line);
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      flex-wrap: wrap;
    }
    .modal-footer .btn-primary,
    .modal-footer .btn-outline {
      flex: 0 1 auto;
      max-width: 100%;
    }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-grid .span-2 { grid-column: span 2; }
    .form-field label { display: block; font-size: 0.68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); margin-bottom: 5px; }
    .form-field input, .form-field select, .form-field textarea {
      width: 100%; padding: 10px 14px;
      border: 1.5px solid var(--line); background: var(--bg);
      border-radius: 11px; font-family: inherit; font-size: 0.82rem; color: var(--ink);
      outline: none; transition: border-color .2s, box-shadow .2s;
    }
    .form-field input:focus, .form-field select:focus, .form-field textarea:focus {
      border-color: var(--maroon); box-shadow: 0 0 0 3px var(--maroon-light); background: #fff;
    }
    .form-field input:disabled { opacity: .55; cursor: not-allowed; }

   

    /* edit search results */
    .search-result-item {
      background: var(--bg); border-radius: 11px; border: 1.5px solid var(--line);
      padding: 12px 14px; cursor: pointer; transition: all .15s;
    }
    .search-result-item:hover { background: var(--maroon-light); border-color: var(--maroon); }
    .search-result-id    { font-family: 'DM Mono', monospace; font-size: 0.7rem; font-weight: 700; color: var(--maroon); }
    .search-result-title { font-size: 0.78rem; font-weight: 700; color: var(--ink); margin-top: 2px; }
    .search-result-owner { font-size: 0.72rem; color: var(--muted); margin-top: 1px; }

    /* download modal */
    .radio-label { display: flex; align-items: center; gap: 9px; font-size: 0.82rem; font-weight: 600; cursor: pointer; }
    .radio-label input[type=radio] { accent-color: var(--maroon); width: 16px; height: 16px; }

    /* changes modal */
    .change-row { background: var(--bg); border-radius: 11px; border: 1px solid var(--line); padding: 12px 14px; margin-bottom: 8px; }
    .change-field  { font-family: 'DM Mono', monospace; font-size: 0.7rem; font-weight: 700; color: var(--maroon); margin-bottom: 6px; }
    .change-old    { font-size: 0.75rem; color: #b91c1c; }
    .change-new    { font-size: 0.75rem; color: #065f46; margin-top: 3px; }

    /* anim */
    @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
    .fade-up { animation: fadeUp .4s forwards; }

    .records-page-footer {
      margin-top: 20px;
      padding: 14px 0;
      border-top: 1px solid var(--line);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 8px 16px;
    }
    .records-page-footer .footer-meta {
      font-size: 0.72rem;
      color: var(--muted);
    }
    .records-page-footer .footer-version {
      font-size: 0.68rem;
      font-family: 'DM Mono', monospace;
      color: #94a3b8;
    }

    .logout-modal-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 22px;
      align-items: stretch;
    }
    .logout-modal-actions .btn-outline {
      flex: 1 1 120px;
      justify-content: center;
      min-width: 0;
    }
    .logout-modal-actions form {
      flex: 1 1 140px;
      min-width: 0;
      display: flex;
    }
    .logout-modal-actions form .btn-primary {
      width: 100%;
      justify-content: center;
    }

    /* scrollbar global */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 999px; }


    @media (max-width: 1100px) {
      .records-grid { grid-template-columns: minmax(0, 1fr); }
      .updates-card { height: auto; max-height: 400px; }
      .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
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
      .sidebar.mobile-open .sidebar-mobile-user { display: block !important; }
      .sidebar.mobile-open > .nav-item:first-child { display: none; }
      .sidebar-logo { margin-left: 4px; }
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
      .topbar { padding: 8px 16px; min-height: 60px; }
      .topbar-left { gap: 10px; }
      .topbar-search { display: none; }
      .page-sub { display: none; }

      .filter-panel-top { align-items: flex-start; }
      .filter-panel-actions {
        width: 100%;
        justify-content: flex-start;
      }
      .howto-footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }
      .howto-footer .btn-primary-sm { margin-left: 0; }
      .howto-box { width: min(96vw, 100% - 2rem); }
      .modal-box { width: min(520px, calc(100vw - 1.5rem)); }
    }
    @media (max-width: 640px) {
      .content { padding: 14px var(--pad-x); }
      .topbar { padding: 8px 14px; min-height: 56px; }
      .topbar-search { display: none; }
      .filter-grid { grid-template-columns: minmax(0, 1fr); }
      .form-grid { grid-template-columns: 1fr; }
      .form-grid .span-2 { grid-column: 1; }
      .btn-howto-label { display: none; }
      .btn-primary-text { display: none; }
      .update-title {
        white-space: normal;
        overflow-wrap: anywhere;
        line-height: 1.35;
      }
      footer.records-page-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }
    }
    @media (max-width: 480px) {
      .table-card { height: min(68vh, 70dvh); }
      .action-btn {
        white-space: normal;
        text-align: center;
        justify-content: center;
      }
      .filter-eyebrow {
        font-size: clamp(0.5rem, 1.4vw, 0.56rem);
        letter-spacing: 0.1em;
        padding: 0.2rem 0.55rem;
      }
      .logout-modal-actions {
        flex-direction: column;
      }
      .logout-modal-actions .btn-outline,
      .logout-modal-actions form {
        flex: 0 0 auto;
        width: 100%;
      }
    }
  </style>
</head>

<body>

@php
  $user       = $user       ?? (object)['name' => 'KTTM User', 'role' => 'Staff'];
  $recent     = $recent     ?? [];
  $allRecords = $allRecords ?? [];

  $campuses = $campuses ?? collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
  $types    = collect($allRecords)->pluck('type')->filter()->unique()
                ->merge(['Copyright', 'Industrial Design', 'Patent', 'Trademark', 'Utility Model'])
                ->unique()->sort()->values()->all();
  $statuses = collect($allRecords)->pluck('status')->filter()->unique()
                ->merge(['Registered', 'Unregistered', 'Recently Filed', 'Close to Expiry'])
                ->unique()->sort()->values()->all();

  $urlDashboard = url('/home');
  $urlRecords   = url('/records');
  $urlNew       = url('/ipassets/create');
  $urlLogout    = url('/logout');
  $urlInsights  = url('/insights');

  $initials = collect(explode(' ', $user->name))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->implode('');

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
@endphp

{{-- ══════════════ SIDEBAR ══════════════ --}}
<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
<aside class="sidebar" id="mainSidebar" aria-label="Main navigation">

  {{-- User avatar block — icon on desktop, full name+role on mobile --}}
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
    <button id="logoutBtn" class="nav-item" style="background:none;border:none;cursor:pointer;">
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
      <div>
        <div class="page-title">Records</div>
        <div class="page-sub">Browse & manage IP filings</div>
      </div>
    </div>
    
    <div class="topbar-right">
      <button id="howToUseBtn" class="btn-howto" title="How to Use">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span class="btn-howto-label">How to Use</span>
      </button>
      <div class="icon-btn" id="downloadBtn" title="Download Records">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
      </div>
      <a href="{{ $urlNew }}" class="btn-primary" title="Create a new IP record">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        <span class="btn-primary-text">New Record</span>
      </a>
      
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

    {{-- FILTER PANEL --}}
    <div class="filter-panel fade-up">
      <div class="filter-panel-top">
        <div>
          <div class="filter-eyebrow">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg>
            Records Workspace
          </div>
          <div class="filter-title">Browse & Filter IP Records</div>
        </div>
        <div class="filter-panel-actions">
          <button id="editRecordsBtn" class="btn-outline">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Edit Record
          </button>
         
            <!-- Full View button removed -->
        </div>
      </div>

      <div class="filter-grid">
        <div class="field-wrap">
          <label for="viewAllSearch">Search</label>
          <input id="viewAllSearch" class="field-input" type="search" placeholder="Search ID, title, owner…">
        </div>
        <div class="field-wrap">
          <label for="filterStatus">Status</label>
          <select id="filterStatus" class="field-select">
            <option value="">All status</option>
            @foreach($statuses as $st)
              <option value="{{ $st }}">{{ $st }}</option>
            @endforeach
          </select>
        </div>
        <div class="field-wrap">
          <label for="filterCampus">Campus</label>
          <select id="filterCampus" class="field-select">
            <option value="">All campus</option>
            @foreach($campuses as $c)
              <option value="{{ $c }}">{{ $c }}</option>
            @endforeach
          </select>
        </div>
        <div class="field-wrap">
          <label for="filterType">Category</label>
          <select id="filterType" class="field-select">
            <option value="">All categories</option>
            @foreach($types as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
        </div>
        <div class="field-wrap">
          <label for="filterCollege">College</label>
          <select id="filterCollege" class="field-select">
            <option value="">All colleges</option>
            @foreach($colleges ?? [] as $college)
              <option value="{{ $college }}">{{ $college }}</option>
            @endforeach
          </select>
        </div>
        <div class="field-wrap">
          <label for="filterProgram">Program</label>
          <select id="filterProgram" class="field-select">
            <option value="">All programs</option>
            @foreach($programs ?? [] as $program)
              <option value="{{ $program }}">{{ $program }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="filter-actions">
        <button id="applySearchBtn" class="btn-primary">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Search
        </button>
        <button id="resetFiltersBtn" class="btn-outline">Reset</button>
        <span id="resultHint" class="result-hint"></span>
      </div>
    </div>

    {{-- RECORDS GRID --}}
    <div class="records-table-full">
      <div class="table-card fade-up table-card--delayed" style="width:100%;">
        <div class="table-card-header">
          <div>
            <div class="table-card-title">All IP Records</div>
            <div class="table-card-sub">Client-side filtered · fast search</div>
          </div>
          <div class="table-card-header-actions">
            <a href="{{ $urlNew }}" class="btn-gold btn-gold-compact" title="Create a new IP record">+ New</a>
          </div>
        </div>

        <div class="table-wrap">
          <table id="recordsTable">
            <thead>
              <tr>
                <th class="sortable" id="mainSortBtn">ID <span id="mainSortIcon">⇅</span></th>
                <th data-col="title" style="min-width:180px;">IP Title</th>
                <th data-col="category">Category</th>
                <th data-col="owner" style="min-width:140px;">Owner / Inventor</th>
                <th data-col="campus">Campus</th>
                <th data-col="college" class="record-college">College</th>
                <th data-col="program" class="record-program">Program</th>
                <th data-col="classofwork" class="record-classofwork">Class of Work</th>
                <th data-col="status">Status</th>
                <th data-col="datecreated">Date Created</th>
                <th data-col="registered">Registered</th>
                <th data-col="nextdue">Next Due</th>
                <th data-col="validity">Validity</th>
                <th data-col="regnumber">Reg. Number</th>
                <th data-col="gdrive" style="min-width:160px;">GDrive</th>
                <th data-col="actions">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y" id="mainTableBody">
              @forelse($allRecords as $r)
                @php
                  $s = $r['status'] ?? '';
                  $badge = match($s) {
                    'Registered'      => 'status-registered',
                    'Under Review'    => 'status-review',
                    'Filed'           => 'status-filed',
                    'Needs Attention' => 'status-attention',
                    'Returned'        => 'status-returned',
                    default           => 'status-default',
                  };
                  $dr   = $r['registered'] ?? null;
                  $link = $r['gdrive_link'] ?? null;
                  $due  = '—'; $validity = '—';
                  if($dr){
                    $date = \Carbon\Carbon::parse($dr);
                    switch(strtolower(trim($r['type'] ?? ''))){
                      case 'patent':           $due = $date->copy()->addYears(20)->format('M d, Y'); $validity = '20 yrs'; break;
                      case 'copyright':        $due = $date->copy()->addYears(70)->format('M d, Y'); $validity = '70 yrs'; break;
                      case 'utility model':    $due = $date->copy()->addYears(10)->format('M d, Y'); $validity = '10 yrs'; break;
                      case 'industrial design': $due= $date->copy()->addYears(15)->format('M d, Y'); $validity = '15 yrs'; break;
                      case 'trademark':        $due = $date->copy()->addYears(10)->format('M d, Y'); $validity = '10 yrs'; break;
                    }
                  }
                @endphp
                <tr class="record-row" data-remarks="{{ addslashes($r['remarks'] ?? '') }}">
                  <td class="record-id-cell record-id">{{ $r['id'] ?? '—' }}</td>
                  <td data-col="title" class="record-title-cell record-title"><div class="title-text" title="{{ $r['title'] ?? '—' }}">{{ $r['title'] ?? '—' }}</div></td>
                  <td data-col="category" class="record-type" style="white-space:nowrap;">{{ $r['type'] ?? '—' }}</td>
                  <td data-col="owner" class="record-owner" title="{{ $r['owner_inventor'] ?? '—' }}">{!! implode('<br>', array_map('trim', explode(',', e($r['owner_inventor'] ?? '—')))) !!}</td>
                  <td data-col="campus" class="record-campus" style="white-space:nowrap;">{{ $r['campus'] ?? '—' }}</td>
                  <td data-col="college" class="record-college">{{ $r['college'] ?? '—' }}</td>
                  <td data-col="program" class="record-program">{{ $r['program'] ?? '—' }}</td>
                  <td data-col="classofwork" class="record-classofwork">{{ $r['class_of_work'] ?? '—' }}</td>
                  <td data-col="status" class="record-status" style="white-space:nowrap;">
                    <span class="status-badge {{ $badge }}">{{ $s ?: '—' }}</span>
                  </td>
                  <td data-col="datecreated" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">
                    {{ !empty($r['date_creation']) ? \Carbon\Carbon::parse($r['date_creation'])->format('M d, Y') : '—' }}
                  </td>
                  <td data-col="registered" class="record-registered" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">
                    {{ $dr ? \Carbon\Carbon::parse($dr)->format('M d, Y') : '—' }}
                  </td>
                  <td data-col="nextdue" class="record-due" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">{{ $due }}</td>
                  <td data-col="validity" class="record-validity" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">{{ $validity }}</td>
                  <td data-col="regnumber" class="record-ipophl" style="white-space:nowrap;font-size:.75rem;">{{ $r['registration_number'] ?? '—' }}</td>
                  <td data-col="gdrive" class="record-link">
                    @if($link)
                      <a href="{{ $link }}" target="_blank" style="color:var(--maroon);font-weight:700;font-size:.75rem;text-decoration:underline;">Open file</a>
                    @else
                      <span style="color:var(--muted);">—</span>
                    @endif
                  </td>
                  <td data-col="actions" style="white-space:nowrap;">
                    <div style="display:flex;gap:5px;">
                      <button type="button" class="action-btn printBtn" data-record-id="{{ $r['id'] ?? '' }}">Print</button>
                      <button type="button" class="action-btn viewBtn" data-record-id="{{ $r['id'] ?? '' }}">View</button>
                      <button type="button" class="action-btn edit editBtn" data-record-id="{{ $r['id'] ?? '' }}">Edit</button>
                      <button type="button" class="action-btn printBtn" data-record-id="{{ $r['id'] ?? '' }}">Archive</button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr><td colspan="16" style="text-align:center;padding:40px;color:var(--muted);">No records available.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="table-footer">
          <div class="page-info" id="pageInfo">Page 1</div>
          <div class="table-footer-nav">
            <button id="prevPageBtn" class="page-btn" disabled>← Prev</button>
            <button id="nextPageBtn" class="page-btn">Next →</button>
          </div>
        </div>
      </div>

      <!-- Recent Updates section removed. -->

    </div>

    <footer class="records-page-footer">
      <div class="footer-meta">© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="footer-version">Records v2.0</div>
    </footer>

  </div>
</div>

{{-- ══ EDIT SEARCH MODAL ══ --}}
<div class="modal-overlay" id="editSearchModal">
  <div class="modal-box" style="max-width:480px;">
    <div class="modal-header">
      <div class="modal-title-row">
        <div class="modal-label">Find & Edit</div>
        <div class="modal-title">Search Record</div>
        <div class="modal-sub">Search by ID, title, or owner name.</div>
      </div>
      <button class="modal-close-btn" data-close-editsearch>✕</button>
    </div>
    <div class="modal-body">
      <div class="field-wrap">
        <label for="editSearchInput" style="display:block;font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:5px;">Search</label>
        <input id="editSearchInput" class="field-input" type="text" placeholder="Search ID, title, or owner…">
      </div>
      <div id="editSearchResults" style="margin-top:14px;display:flex;flex-direction:column;gap:8px;max-height:360px;overflow-y:auto;display:none;"></div>
      <div id="editSearchNoResults" style="margin-top:14px;font-size:.78rem;color:var(--muted);text-align:center;display:none;">No records found. Try adjusting your search.</div>
    </div>
    <div class="modal-footer">
      <button class="btn-outline" data-close-editsearch>Cancel</button>
      <button class="btn-primary" id="editSearchBtn">Search</button>
    </div>
  </div>
</div>

{{-- ══ EDIT RECORD MODAL ══ --}}
<div class="modal-overlay" id="editRecordModal">
  <div class="modal-box" style="max-width:640px;">
    <div class="modal-header">
      <div class="modal-title-row">
        <div class="modal-label">Admin</div>
        <div class="modal-title">Edit Record</div>
        <div class="modal-sub">Update the record information below.</div>
      </div>
      <button class="modal-close-btn" data-close-editrecord>✕</button>
    </div>
    <div class="modal-body">
      <form id="editRecordForm">
        <div class="form-grid">
          <div class="form-field">
            <label for="editField_id">Record ID</label>
            <input id="editField_id" type="text" disabled>
          </div>
          <div class="form-field">
            <label for="editField_title">IP Title</label>
            <input id="editField_title" type="text">
          </div>
          <div class="form-field">
            <label for="editField_type">Category</label>
            <select id="editField_type">
              <option value="">Select category</option>
              @foreach($types as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
            </select>
          </div>
          <div class="form-field">
            <label for="editField_owner">Owner / Inventor</label>
            <input id="editField_owner" type="text">
          </div>
          <div class="form-field">
            <label for="editField_campus">Campus</label>
            <select id="editField_campus">
              <option value="">Select campus</option>
              @foreach($campuses as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
            </select>
          </div>
          <div class="form-field">
            <label for="editField_status">Status</label>
            <select id="editField_status">
              <option value="">Select status</option>
              @foreach($statuses as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
            </select>
          </div>
          <div class="form-field">
            <label for="editField_registered">Date Registered</label>
            <input id="editField_registered" type="date">
          </div>
          <div class="form-field">
            <label for="editField_ipophl_id">Registration Number</label>
            <input id="editField_ipophl_id" type="text">
          </div>
          <div class="form-field span-2">
            <label for="editField_gdrive_link">GDrive Link</label>
            <input id="editField_gdrive_link" type="url" placeholder="https://…">
          </div>
          <div class="form-field span-2">
            <label for="editField_remarks">Remarks</label>
            <textarea id="editField_remarks" rows="3" placeholder="Add any remarks or notes…"></textarea>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn-outline" data-close-editrecord>Cancel</button>
      <button class="btn-primary" id="saveEditBtn">Save Changes</button>
    </div>
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
          <div class="howto-eyebrow">Records Page · Guide</div>
          <div class="howto-title">How to Use This Page</div>
          <div class="howto-sub">Everything you can do on the Records workspace — step by step.</div>
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
          <div class="howto-step-title">Search Records</div>
          <div class="howto-step-desc">Type any keyword — record ID, IP title, or owner name — into the <strong>Search</strong> field. Results update live as you type, filtering the table instantly without a page reload.</div>
          <span class="howto-step-tag">Filter Panel → Search</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">02</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Filter by Status, Campus, Category & More</div>
          <div class="howto-step-desc">Use the dropdown filters to narrow down records by <strong>Status</strong>, <strong>Campus</strong>, <strong>Category</strong>, <strong>College</strong>, or <strong>Program</strong>. Filters can be combined — for example, show only Registered Copyrights from a specific campus.</div>
          <span class="howto-step-tag">Filter Panel → Dropdowns</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">03</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Sort the Table</div>
          <div class="howto-step-desc">Click the <strong>Record ID</strong> column header to toggle sorting ascending or descending. The sort icon (⇅) shows the current direction. This helps you quickly find the newest or oldest filings.</div>
          <span class="howto-step-tag">Table → Record ID Column</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">04</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Edit a Record</div>
          <div class="howto-step-desc">Click <strong>Edit Record</strong> in the filter panel header to open the edit search modal. Search for the record you want to edit, click it, update the fields, and hit <strong>Save Changes</strong>. All edits are logged automatically.</div>
          <span class="howto-step-tag">Filter Panel → Edit Record</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">05</div>
        <div class="howto-step-body">
          <div class="howto-step-title">View Change History</div>
          <div class="howto-step-desc">Each row in the table has a <strong>View</strong> button. Click it to open that record's full change history — every edit ever made, who made it, which fields changed, and when. Useful for auditing and tracking IP record activity.</div>
          <span class="howto-step-tag">Table → View Button</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">06</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Print a Record</div>
          <div class="howto-step-desc">Use the <strong>Print</strong> button on any table row to generate a formatted printout of that record's core details — title, type, owner, campus, status, and registration info.</div>
          <span class="howto-step-tag">Table → Print Button</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">07</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Download Records</div>
          <div class="howto-step-desc">Click the <strong>download icon</strong> in the topbar to export records as a CSV file. You can choose to download all records or apply a custom date range for the registered date.</div>
          <span class="howto-step-tag">Topbar → Download Icon</span>
        </div>
      </div>

      <div class="howto-step">
        <div class="howto-step-num">08</div>
        <div class="howto-step-body">
          <div class="howto-step-title">Recent Updates Sidebar</div>
          <div class="howto-step-desc">The right sidebar shows the <strong>20 most recent activity events</strong> — newly created records and edits. Click <strong>View</strong> on any update to jump to that record's change history. Use the refresh button to load the latest activity without reloading the page.</div>
          <span class="howto-step-tag">Right Sidebar → Recent Updates</span>
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
  <div class="modal-box" style="max-width:380px;">
    <div class="modal-body" style="padding:28px;">
      <div style="width:50px;height:50px;border-radius:14px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
      <div style="font-size:1.1rem;font-weight:800;color:var(--ink);">Sign out of KTTM</div>
      <div style="font-size:.82rem;color:var(--muted);margin-top:6px;line-height:1.6;">This will end your session and return you to the public portal.</div>
      <div class="logout-modal-actions">
        <button class="btn-outline" data-close-logout>Cancel</button>
        <form id="logoutForm" action="{{ $urlLogout }}" method="POST" data-simulate="true">
          @csrf
          <button type="submit" class="btn-primary">Sign Out</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ══ DOWNLOAD MODAL ══ --}}
<div class="modal-overlay" id="downloadModal">
  <div class="modal-box" style="max-width:420px;">
    <div class="modal-header">
      <div class="modal-title-row">
        <div class="modal-label">Export</div>
        <div class="modal-title">Download Records</div>
        <div class="modal-sub">Choose all records or a date range.</div>
      </div>
      <button class="modal-close-btn" data-close-download>✕</button>
    </div>
    <div class="modal-body">
      <form id="downloadForm" action="{{ url('/records/export') }}" method="GET">
        <div style="display:flex;flex-direction:column;gap:12px;">
          <label class="radio-label"><input type="radio" name="mode" value="all" id="modeAll" checked> All records</label>
          <label class="radio-label"><input type="radio" name="mode" value="range" id="modeRange"> Filter by date range</label>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:4px;">
            <div class="form-field"><label for="downloadStart">From</label><input type="date" name="start" id="downloadStart" disabled></div>
            <div class="form-field"><label for="downloadEnd">To</label><input type="date" name="end" id="downloadEnd" disabled></div>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn-outline" data-close-download>Cancel</button>
      <button class="btn-primary" id="submitDownloadBtn">Download</button>
    </div>
  </div>
</div>

{{-- ══ CHANGES MODAL ══ --}}
<div class="modal-overlay" id="changesModal">
  <div class="modal-box" style="max-width:460px;">
    <div class="modal-header">
      <div class="modal-title-row">
        <div class="modal-label">Audit</div>
        <div class="modal-title" id="changesModalTitle">Changes Details</div>
        <div class="modal-sub" id="changesRecordId">Record #—</div>
      </div>
      <button class="modal-close-btn" data-close-changes>✕</button>
    </div>
    <div class="modal-body">
      <div id="changesContainer"></div>
    </div>
    <div class="modal-footer">
      <button class="btn-primary" data-close-changes>Close</button>
    </div>
  </div>
</div>



<script>
(function(){
  // ── Toast ──
  function showToast(msg, type='success', dur=4500){
    const t = document.createElement('div');
    t.className = `toast ${type}`; t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(()=>{ t.classList.add('hiding'); setTimeout(()=>t.remove(),300); }, dur);
  }

  // ── Modal helpers ──
  const SCROLL_LOCK_MODAL_IDS = ['logoutModal','editSearchModal','editRecordModal','downloadModal','changesModal','howtoModal'];
  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const anyModal = SCROLL_LOCK_MODAL_IDS.some(id => document.getElementById(id)?.classList.contains('open'));
    document.body.style.overflow = (sidebarOpen || anyModal) ? 'hidden' : '';
  }
  function openModal(id){ document.getElementById(id)?.classList.add('open'); syncBodyScrollLock(); }
  function closeModal(id){ document.getElementById(id)?.classList.remove('open'); syncBodyScrollLock(); }

  // Wire close buttons
  document.querySelectorAll('[data-close-logout]').forEach(b=>b.addEventListener('click',()=>closeModal('logoutModal')));
  document.querySelectorAll('[data-close-fullpage]').forEach(b=>b.addEventListener('click',()=>closeModal('fullTableModal')));
  document.querySelectorAll('[data-close-editsearch]').forEach(b=>b.addEventListener('click',()=>closeModal('editSearchModal')));
  document.querySelectorAll('[data-close-editrecord]').forEach(b=>b.addEventListener('click',()=>closeModal('editRecordModal')));
  document.querySelectorAll('[data-close-download]').forEach(b=>b.addEventListener('click',()=>closeModal('downloadModal')));
  document.querySelectorAll('[data-close-changes]').forEach(b=>b.addEventListener('click',()=>closeModal('changesModal')));
  ['logoutModal','editSearchModal','editRecordModal','downloadModal','changesModal'].forEach(id=>{
    document.getElementById(id)?.addEventListener('click', e=>{ if(e.target===e.currentTarget) closeModal(id); });
  });
  document.addEventListener('keydown', e=>{
    if(e.key!=='Escape') return;
    ['logoutModal','editSearchModal','editRecordModal','downloadModal','changesModal','howtoModal'].forEach(closeModal);
    closeMobileSidebar();
  });

  // ── How to Use ──
  document.getElementById('howToUseBtn')?.addEventListener('click', ()=>openModal('howtoModal'));
  document.getElementById('howtoClose')?.addEventListener('click', ()=>closeModal('howtoModal'));
  document.getElementById('howtoCloseBtn')?.addEventListener('click', ()=>closeModal('howtoModal'));
  document.getElementById('howtoModal')?.addEventListener('click', e=>{ if(e.target.id==='howtoModal') closeModal('howtoModal'); });

  // ── Button wiring ──
  document.getElementById('logoutBtn')?.addEventListener('click', ()=>openModal('logoutModal'));
  document.getElementById('editRecordsBtn')?.addEventListener('click', ()=>openModal('editSearchModal'));
  // Full View button event removed
  document.getElementById('downloadBtn')?.addEventListener('click', ()=>openModal('downloadModal'));

  // ── Logout form ──
  const logoutForm = document.getElementById('logoutForm');
  if(logoutForm?.dataset.simulate==='true'){
    logoutForm.addEventListener('submit', e=>{ e.preventDefault(); closeModal('logoutModal'); setTimeout(()=>window.location.href='{{ url('/') }}',200); });
  }

  // ── Download modal ──
  const modeAll=document.getElementById('modeAll'), modeRange=document.getElementById('modeRange');
  const dlStart=document.getElementById('downloadStart'), dlEnd=document.getElementById('downloadEnd');
  [modeAll,modeRange].forEach(r=>r?.addEventListener('change',()=>{
    const range=modeRange.checked;
    dlStart.disabled=!range; dlEnd.disabled=!range;
    if(!range){ dlStart.value=''; dlEnd.value=''; }
    else dlStart.focus();
  }));
  document.getElementById('submitDownloadBtn')?.addEventListener('click',()=>{
    if(modeAll.checked){ dlStart.disabled=true; dlEnd.disabled=true; }
    document.getElementById('downloadForm').submit();
  });

  // ── Topbar search sync ──
  document.getElementById('topbarSearch')?.addEventListener('input', e=>{
    const v = document.getElementById('viewAllSearch');
    if(v) v.value = e.target.value;
    fetchRecords(1);
  });

  // ── Filtering / pagination ──
  const q      = document.getElementById('viewAllSearch');
  const campus = document.getElementById('filterCampus');
  const type   = document.getElementById('filterType');
  const status = document.getElementById('filterStatus');
  const hint   = document.getElementById('resultHint');
    const college = document.getElementById('filterCollege');
    const program = document.getElementById('filterProgram');

  (function(){ const p=new URLSearchParams(window.location.search); const t=p.get('q'); if(t&&q) q.value=t; })();

  const allRows = document.querySelectorAll('#mainTableBody tr.record-row');
  let currentPage=1, lastPage=1;

  async function fetchRecords(page=1){
    currentPage=page||1;
    const params=new URLSearchParams();
    if(q?.value.trim()) params.set('q',q.value.trim());
    if(type?.value) params.set('type',type.value);
    if(status?.value) params.set('status',status.value);
    if(campus?.value) params.set('campus',campus.value);
    if(college?.value) params.set('college',college.value);
    if(program?.value) params.set('program',program.value);
    params.set('page',currentPage); params.set('per_page',100);
    try{
      const resp=await fetch('/api/records?'+params,{headers:{'Accept':'application/json'}});
      if(!resp.ok) throw new Error();
      const data=await resp.json();
      const items=data.data||[]; lastPage=data.last_page||1;
      const tbody=document.getElementById('mainTableBody');
      if(tbody) tbody.innerHTML = items.length ? items.map(r=>buildRow(r)).join('') : '<tr><td colspan="16" style="text-align:center;padding:40px;color:var(--muted);">No records found.</td></tr>';
      document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${lastPage}`;
      document.getElementById('prevPageBtn').disabled = currentPage<=1;
      document.getElementById('nextPageBtn').disabled = currentPage>=lastPage;
      if(hint) hint.textContent = items.length ? `Showing ${items.length} record(s).` : 'No results.';
    }catch(e){ console.error(e); }
  }

  function buildRow(r){
    const rawReg = r.date_registered_deposited||r.registered||null;
    const reg = rawReg ? new Date(rawReg).toLocaleDateString(undefined,{year:'numeric',month:'short',day:'2-digit'}) : '—';
    const typeLow=(r.category||r.type||'').toLowerCase();
    let due='—',validity='—';
    try{ if(rawReg){ const d=new Date(rawReg);
      if(typeLow==='patent'){d.setFullYear(d.getFullYear()+20);due=d.toLocaleDateString();validity='20 yrs';}
      else if(typeLow==='copyright'){d.setFullYear(d.getFullYear()+70);due=d.toLocaleDateString();validity='70 yrs';}
      else if(typeLow==='utility model'){d.setFullYear(d.getFullYear()+10);due=d.toLocaleDateString();validity='10 yrs';}
      else if(typeLow==='industrial design'){d.setFullYear(d.getFullYear()+15);due=d.toLocaleDateString();validity='15 yrs';}
      else if(typeLow==='trademark'){d.setFullYear(d.getFullYear()+10);due=d.toLocaleDateString();validity='10 yrs';}
    }}catch(e){}
    const badgeMap={'Registered':'status-registered','Under Review':'status-review','Filed':'status-filed','Needs Attention':'status-attention','Returned':'status-returned'};
    const bc=badgeMap[r.status]||'status-default';
    const id=esc(r.record_id||r.id||''); const title=esc(r.ip_title||r.title||''); const ownerText=esc(r.owner_inventor||r.owner||'');
    const rawCreated=r.date_creation||null;
    const created=rawCreated?new Date(rawCreated).toLocaleDateString('en-US',{year:'numeric',month:'short',day:'2-digit'}):'—';
    return `<tr class="record-row" style="border-bottom:1px solid var(--line);">
      <td class="record-id-cell record-id">${id}</td>
      <td data-col="title" class="record-title-cell record-title"><div class="title-text" title="${title}">${title}</div></td>
      <td data-col="category" class="record-type" style="white-space:nowrap;font-size:.78rem;">${esc(r.category||r.type||'—')}</td>
      <td data-col="owner" class="record-owner" style="font-size:.78rem;" title="${ownerText}">${(r.owner_inventor||r.owner||'').split(',').map(s=>esc(s.trim())).join('<br>')}</td>
      <td data-col="campus" class="record-campus" style="white-space:nowrap;font-size:.78rem;">${esc(r.campus||'')}</td>
      <td data-col="college" style="white-space:nowrap;font-size:.75rem;color:var(--muted);">${esc(r.college||'—')}</td>
      <td data-col="program" style="white-space:nowrap;font-size:.75rem;color:var(--muted);">${esc(r.program||'—')}</td>
      <td data-col="classofwork" style="white-space:nowrap;font-size:.75rem;color:var(--muted);">${esc(r.class_of_work||'—')}</td>
      <td data-col="status" class="record-status" style="white-space:nowrap;"><span class="status-badge ${bc}">${esc(r.status||'')}</span></td>
      <td data-col="datecreated" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">${esc(created)}</td>
      <td data-col="registered" class="record-registered" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">${esc(reg)}</td>
      <td data-col="nextdue" class="record-due" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">${esc(due)}</td>
      <td data-col="validity" class="record-validity" style="white-space:nowrap;color:var(--muted);font-size:.75rem;">${esc(validity)}</td>
      <td data-col="regnumber" class="record-ipophl" style="white-space:nowrap;font-size:.75rem;">${esc(r.registration_number||'')}</td>
      <td data-col="gdrive" class="record-link">${r.gdrive_link?`<a href="${esc(r.gdrive_link)}" target="_blank" style="color:var(--maroon);font-weight:700;font-size:.75rem;text-decoration:underline;">Open file</a>`:'<span style="color:var(--muted);">—</span>'}</td>
      <td data-col="actions" style="white-space:nowrap;"><div style="display:flex;gap:5px;">
        <button type="button" class="action-btn printBtn" data-record-id="${id}">Print</button>
        <button type="button" class="action-btn viewBtn" data-record-id="${id}">View</button>
        <button type="button" class="action-btn edit editBtn" data-record-id="${id}">Edit</button>
        <button type="button" class="action-btn printBtn" data-record-id="${id}">Archive</button>
      </div></td>
    </tr>`;
  }

  function esc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;'); }

  document.getElementById('applySearchBtn')?.addEventListener('click',()=>fetchRecords(1));
  document.getElementById('prevPageBtn')?.addEventListener('click',()=>{ if(currentPage>1) fetchRecords(currentPage-1); });
  document.getElementById('nextPageBtn')?.addEventListener('click',()=>fetchRecords(currentPage+1));
  q?.addEventListener('keypress',e=>{ if(e.key==='Enter') fetchRecords(1); });
  [campus,type,status,college,program].forEach(el=>el?.addEventListener('change',()=>fetchRecords(1)));
  type?.addEventListener('change', () => applyColumnLayout(type.value));
  document.getElementById('resetFiltersBtn')?.addEventListener('click',()=>{
    if(q) q.value=''; if(campus) campus.value=''; if(type) type.value=''; if(status) status.value=''; if(college) college.value=''; if(program) program.value='';
    applyColumnLayout('');
    fetchRecords(1);
  });

  fetchRecords(1);

  /* ══════════════════════════════════════════════
     COLUMN LAYOUT SWAP — by IP Category filter
  ══════════════════════════════════════════════ */
  const COL_SCHEMAS = {
    // columns to SHOW and their display labels
    // all other columns are hidden
    // Column order MUST follow the HTML <td> order:
    // title → category → owner → campus → college → program → classofwork → status → datecreated → registered → nextdue → validity → regnumber → gdrive → actions
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
  // utility model and industrial design share patent schema
  COL_SCHEMAS['utility model']     = COL_SCHEMAS.patent;
  COL_SCHEMAS['industrial design'] = COL_SCHEMAS.patent;

  // All default columns shown with original labels
  const DEFAULT_LABELS = {
    title:       'IP Title',
    category:    'Category',
    owner:       'Owner / Inventor',
    campus:      'Campus',
    college:     'College',
    program:     'Program',
    classofwork: 'Class of Work',
    status:      'Status',
    datecreated: 'Date Created',
    registered:  'Registered',
    nextdue:     'Next Due',
    validity:    'Validity',
    regnumber:   'Reg. Number',
    gdrive:      'GDrive',
    actions:     'Actions',
  };

  function applyColumnLayout(selectedType) {
    const table   = document.getElementById('recordsTable');
    if (!table) return;

    const key     = selectedType.trim().toLowerCase();
    const schema  = COL_SCHEMAS[key] || null;
    const headers = table.querySelectorAll('thead th[data-col]');

    headers.forEach(th => {
      const col = th.getAttribute('data-col');
      if (!schema) {
        // default — show all, restore labels
        th.style.display = '';
        if (DEFAULT_LABELS[col]) th.childNodes[0].textContent = DEFAULT_LABELS[col];
      } else {
        if (schema[col] !== undefined) {
          th.style.display = '';
          // update label text — preserve any child elements (sort icon)
          const firstText = Array.from(th.childNodes).find(n => n.nodeType === 3);
          if (firstText) {
            firstText.textContent = schema[col] + ' ';
          } else {
            th.childNodes[0].textContent = schema[col];
          }
        } else {
          th.style.display = 'none';
        }
      }
    });

    // Apply to all body rows (server-rendered + dynamically loaded)
    function applyToRows() {
      table.querySelectorAll('tbody tr.record-row').forEach(row => {
        row.querySelectorAll('td[data-col]').forEach(td => {
          const col = td.getAttribute('data-col');
          if (!schema) {
            td.style.display = '';
          } else {
            td.style.display = schema[col] !== undefined ? '' : 'none';
          }
        });
      });
    }
    applyToRows();

    // Also re-apply after dynamic rows are injected by fetchRecords
    // Use a MutationObserver on tbody to catch API-loaded rows
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

  // ── Print helper ──
  function printRecord(id){
    const cleanId = String(id || '').trim();
    if(!cleanId){ showToast('Record ID not found.','error'); return; }

    const printUrl = `/records/${encodeURIComponent(cleanId)}/print`;
    const w = window.open(printUrl,'_blank');

    if(!w){ showToast('Pop-up blocked.','error'); return; }
  }

  // ── Row into edit modal ──
  function loadRowIntoEdit(row){
    if(!row) return;
    const isModal = row.classList.contains('modal-record-row');
    let id,title,type,owner,campus,status,reg,ipophl,gdrive,remarks='';
    if(isModal){
      const c=row.querySelectorAll('td');
      id=(c[0]?.textContent||'').trim(); title=(c[1]?.textContent||'').trim();
      type=(c[2]?.textContent||'').trim(); owner=(c[3]?.textContent||'').trim();
      campus=(c[4]?.textContent||'').trim(); status=(c[5]?.textContent||'').trim();
      reg=(c[6]?.textContent||'').trim(); ipophl=(c[9]?.textContent||'').trim();
      gdrive=(c[10]?.querySelector('a')?.href||'').trim();
    } else {
      id=(row.querySelector('.record-id')?.textContent||'').trim();
      title=(row.querySelector('.record-title .title-text')?.textContent||row.querySelector('.record-title')?.textContent||'').trim();
      type=(row.querySelector('.record-type')?.textContent||'').trim();
      owner=(row.querySelector('.record-owner')?.textContent||'').trim();
      campus=(row.querySelector('.record-campus')?.textContent||'').trim();
      status=(row.querySelector('.record-status span')?.textContent||'').trim();
      reg=(row.querySelector('.record-registered')?.textContent||'').trim();
      ipophl=(row.querySelector('.record-ipophl')?.textContent||'').trim();
      gdrive=(row.querySelector('.record-link a')?.href||'').trim();
    }
    document.getElementById('editField_id').value=id;
    document.getElementById('editField_title').value=title;
    document.getElementById('editField_type').value=type;
    document.getElementById('editField_owner').value=owner;
    document.getElementById('editField_campus').value=campus;
    document.getElementById('editField_status').value=status;
    document.getElementById('editField_remarks').value=remarks;
    document.getElementById('editField_ipophl_id').value=ipophl;
    document.getElementById('editField_gdrive_link').value=gdrive;
    if(reg && reg!=='—'){ const d=new Date(reg); if(!isNaN(d)){
      document.getElementById('editField_registered').value=`${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    }} else document.getElementById('editField_registered').value='';
    closeModal('editSearchModal'); closeModal('fullTableModal');
    setTimeout(()=>openModal('editRecordModal'),160);
  }

  // Delegated click on main table
  document.getElementById('recordsTable')?.addEventListener('click',e=>{
    const btn=e.target.closest('button'); if(!btn) return;
    const row=btn.closest('tr.record-row'); if(!row) return;
    if(btn.classList.contains('editBtn')){ loadRowIntoEdit(row); return; }
    if(btn.classList.contains('printBtn')){
      const id=(row.querySelector('.record-id')?.textContent||'').trim();
      printRecord(id);
      return;
    }
    if(btn.classList.contains('viewBtn')){
      const id=(row.querySelector('.record-id')?.textContent||'').trim();
      window.location.href = `/record-changes/${encodeURIComponent(id)}`;
      return;
    }
  });

  // ── Edit Search (API-powered — searches all records, not just visible rows) ──
  const editInput=document.getElementById('editSearchInput');
  const editResults=document.getElementById('editSearchResults');
  const editNoResults=document.getElementById('editSearchNoResults');

  function fillEditFields(r) {
    document.getElementById('editField_id').value          = (r.record_id || r.id || '').trim();
    document.getElementById('editField_title').value       = (r.ip_title  || r.title || '').trim();
    document.getElementById('editField_type').value        = (r.category  || r.type  || '').trim();
    document.getElementById('editField_owner').value       = (r.owner_inventor || r.owner || '').trim();
    document.getElementById('editField_campus').value      = (r.campus    || '').trim();
    document.getElementById('editField_status').value      = (r.status    || '').trim();
    document.getElementById('editField_ipophl_id').value   = (r.registration_number || r.ipophl_id || '').trim();
    document.getElementById('editField_gdrive_link').value = (r.gdrive_link || '').trim();
    document.getElementById('editField_remarks').value     = (r.remarks   || '').trim();
    const reg = r.date_registered_deposited || r.registered || '';
    if (reg && reg !== '—') {
      const d = new Date(reg);
      if (!isNaN(d)) {
        document.getElementById('editField_registered').value =
          `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
      } else {
        document.getElementById('editField_registered').value = '';
      }
    } else {
      document.getElementById('editField_registered').value = '';
    }
    closeModal('editSearchModal');
    setTimeout(() => openModal('editRecordModal'), 160);
  }

  async function doEditSearch() {
    const term = (editInput?.value || '').trim();
    if (!term) { showToast('Enter a search term.', 'error'); return; }

    // Show loading state
    editResults.style.display = 'none';
    editNoResults.style.display = 'none';
    editResults.innerHTML = '';

    try {
      const resp = await fetch(
        `/api/records?q=${encodeURIComponent(term)}&per_page=20`,
        { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }, credentials: 'same-origin' }
      );
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const data = await resp.json();
      const records = data.data || data.records || [];

      editResults.innerHTML = '';
      if (records.length) {
        editResults.style.display = 'flex';
        editNoResults.style.display = 'none';
        records.forEach(r => {
          const id  = (r.record_id || r.id    || '').trim();
          const tl  = (r.ip_title  || r.title || '').trim();
          const ow  = (r.owner_inventor || r.owner || '').trim();
          const el  = document.createElement('div');
          el.className = 'search-result-item';
          el.innerHTML = `<div class="search-result-id">${esc(id)}</div><div class="search-result-title">${esc(tl)}</div><div class="search-result-owner">${esc(ow)}</div>`;
          el.addEventListener('click', () => fillEditFields(r));
          editResults.appendChild(el);
        });
      } else {
        editResults.style.display = 'none';
        editNoResults.style.display = 'block';
      }
    } catch(err) {
      console.error('Edit search error:', err);
      showToast('Search failed. Please try again.', 'error');
    }
  }

  document.getElementById('editSearchBtn')?.addEventListener('click', doEditSearch);
  editInput?.addEventListener('keypress', e => { if (e.key === 'Enter') doEditSearch(); });

  // ── Save edit ──
  document.getElementById('saveEditBtn')?.addEventListener('click', async ()=>{
    const id=encodeURIComponent((document.getElementById('editField_id').value||'').trim());
    const payload={
      title:(document.getElementById('editField_title').value||'').trim(),
      type:(document.getElementById('editField_type').value||'').trim(),
      owner:(document.getElementById('editField_owner').value||'').trim(),
      campus:(document.getElementById('editField_campus').value||'').trim(),
      status:(document.getElementById('editField_status').value||'').trim(),
      registered:(document.getElementById('editField_registered').value||'').trim(),
      ipophl_id:(document.getElementById('editField_ipophl_id').value||'').trim(),
      gdrive_link:(document.getElementById('editField_gdrive_link').value||'').trim(),
      remarks:(document.getElementById('editField_remarks').value||'').trim(),
    };
    try{
      const resp=await fetch(`/records/${id}/update`,{
        method:'POST', credentials:'same-origin',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify(payload)
      });
      const ct=resp.headers.get('content-type')||'';
      const result=ct.includes('application/json')?await resp.json():{success:false,message:'Server did not return JSON.'};
      if(!resp.ok){ showToast(`Error ${resp.status}: ${result.message||'Request failed'}`, 'error'); return; }
      if(result.success){ showToast('✓ Record updated!','success'); closeModal('editRecordModal'); document.getElementById('editRecordForm').reset(); setTimeout(()=>window.location.reload(),400); }
      else showToast(`Error: ${result.message||'Unknown error'}`, 'error');
    }catch(e){ console.error(e); showToast('Failed to save. Check console.','error'); }
  });

  // ── Sorting ──
  let mainAsc=true;
  document.getElementById('mainSortBtn')?.addEventListener('click',()=>{
    const tbody=document.getElementById('mainTableBody');
    const rows=Array.from(tbody.querySelectorAll('tr.record-row'));
    rows.sort((a,b)=>{ const ai=(a.querySelector('.record-id')?.textContent||'').trim(); const bi=(b.querySelector('.record-id')?.textContent||'').trim(); return mainAsc?ai.localeCompare(bi):bi.localeCompare(ai); });
    rows.forEach(r=>tbody.appendChild(r)); mainAsc=!mainAsc;
    document.getElementById('mainSortIcon').textContent=mainAsc?'⬆':'⬇';
  });



  // ── Recent Updates ──
  const updCont=document.getElementById('updatesContainer');
  let updatesMap={};

  function getTimeAgo(date){
    const s=Math.floor((new Date()-date)/1000);
    if(s<60) return 'just now'; const m=Math.floor(s/60); if(m<60) return `${m}m ago`;
    const h=Math.floor(m/60); if(h<24) return `${h}h ago`; const d=Math.floor(h/24);
    if(d<7) return `${d}d ago`; return date.toLocaleDateString();
  }

  function renderUpdates(updates){
    if(!updates.length){ updCont.innerHTML='<div style="text-align:center;padding:30px;font-size:.75rem;color:var(--muted);">No recent changes.</div>'; return; }
    updatesMap={};
    const badgeMap={ created:'created', modified:'modified', archived:'archived' };
    const iconMap={ created:'✚', modified:'✎', archived:'○' };
    updCont.innerHTML=updates.map((u,i)=>{
      const uid=`u-${i}`; updatesMap[uid]=u;
      const k=(u.action||'').toLowerCase();
      const bc=badgeMap[k]||'default'; const ic=iconMap[k]||'○';
      return `<div class="update-item">
        <div class="update-record-id">Record #${u.record_id||'—'}</div>
        <div class="update-title">${u.record_title||'Untitled Record'}</div>
        <div class="update-type">${u.record_type||'—'}</div>
        <div class="update-footer">
          <span class="update-badge ${bc}">${ic} ${u.action||'—'}</span>
          <span class="update-time">· ${getTimeAgo(new Date(u.timestamp))}</span>
        </div>
        <div class="update-actions">
          <button class="upd-btn view" data-uid="${uid}">View</button>
          <button class="upd-btn delete" data-uid="${uid}">Delete</button>
        </div>
      </div>`;
    }).join('');

    updCont.querySelectorAll('.upd-btn.view').forEach(btn=>{
      btn.addEventListener('click',()=>{
        const u=updatesMap[btn.dataset.uid]; if(!u) return;
        window.location.href=`/record-changes/${encodeURIComponent(u.record_id)}`;
      });
    });
    updCont.querySelectorAll('.upd-btn.delete').forEach(btn=>{
      btn.addEventListener('click',()=>{ btn.closest('.update-item')?.remove(); });
    });
  }

  async function loadUpdates(){
    try{
      const resp=await fetch('{{ url('/api/recent-updates') }}',{headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content,'Accept':'application/json'}});
      if(!resp.ok) throw new Error();
      const data=await resp.json();
      renderUpdates(data.updates||[]);
    }catch(e){ updCont.innerHTML='<div style="text-align:center;padding:20px;font-size:.75rem;color:#b91c1c;">Failed to load. <button onclick="loadUpdates()" style="text-decoration:underline;background:none;border:none;cursor:pointer;color:var(--maroon);font-weight:700;">Retry</button></div>'; }
  }
  window.loadUpdates=loadUpdates;

  const refreshBtn=document.getElementById('refreshUpdatesBtn');
  refreshBtn?.addEventListener('click',()=>{
    refreshBtn.style.opacity='.5'; refreshBtn.style.pointerEvents='none';
    loadUpdates().finally(()=>{ refreshBtn.style.opacity='1'; refreshBtn.style.pointerEvents='auto'; });
  });

  loadUpdates();
  setInterval(loadUpdates, 30000);

  /* ══════════════════════════════════════
     AUTO-OPEN EDIT MODAL (?edit=RECORD_ID)
     Triggered when arriving from the
     Record Detail page Edit button.
  ══════════════════════════════════════ */
  (function autoOpenEdit() {
    const params = new URLSearchParams(window.location.search);
    const editId = (params.get('edit') || '').trim();
    if (!editId) return;

    // Clean URL so refresh does not re-trigger the modal
    window.history.replaceState({}, '', window.location.pathname);

    function tryFind(attempts) {
      const rows  = Array.from(document.querySelectorAll('#mainTableBody tr.record-row'));
      const match = rows.find(r =>
        (r.querySelector('.record-title .title-text')?.textContent || r.querySelector('.record-title')?.textContent || '').trim().toLowerCase() === editId.trim().toLowerCase()
      );

      if (match) {
        loadRowIntoEdit(match);
        return;
      }

      if (attempts > 0) {
        setTimeout(() => tryFind(attempts - 1), 200);
      } else {
        // Row not in visible DOM — fetch directly from API and fill edit fields
        fetch(`/api/records?q=${encodeURIComponent(editId)}&per_page=20`, {
          headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
          credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(data => {
          const records = data.data || data.records || [];
          const exact   = records.find(r =>
            (r.ip_title || r.title || '').trim().toLowerCase() === editId.trim().toLowerCase()
          ) || records[0]; // fallback to first result if no exact title match
          if (exact) {
            fillEditFields(exact);
          } else {
            // Last resort — open search modal pre-filled for manual selection
            const searchInput = document.getElementById('editSearchInput');
            if (searchInput) searchInput.value = editId;
            openModal('editSearchModal');
          }
        })
        .catch(() => {
          const searchInput = document.getElementById('editSearchInput');
          if (searchInput) searchInput.value = editId;
          openModal('editSearchModal');
        });
      }
    }

    setTimeout(() => tryFind(10), 300);
  })();

  /* Mobile sidebar toggle (same pattern as home.blade.php) */
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