{{-- resources/views/guestrecorddetail.blade.php --}}
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
      --maroon-light: rgba(165,44,48,0.12);
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

    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { -webkit-font-smoothing: antialiased; scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--bg); color: var(--ink);
      min-height: 100vh; overflow-x: hidden;
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
      flex-wrap: wrap;
      min-width: 0; flex: 1 1 auto;
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
      background: var(--bg); border: 1.5px solid var(--line);
      display: flex; align-items: center; justify-content: center;
      color: var(--muted); text-decoration: none; transition: all .18s;
      flex-shrink: 0;
    }
    .back-btn:hover { background: var(--maroon-light); border-color: var(--maroon); color: var(--maroon); }
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
    .record-id-pill {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--maroon-light); border: 1.5px solid rgba(165,44,48,.2);
      color: var(--maroon); padding: 5px 12px; border-radius: 12px;
      font-family: 'DM Mono', monospace; font-size: clamp(0.62rem, 0.12vw + 0.6rem, 0.7rem); font-weight: 700;
      flex: 0 1 auto; min-width: 0; max-width: 100%;
    }
    .record-id-pill svg { flex-shrink: 0; }
    .record-id-text { min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
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
      flex: 1; display: flex; flex-direction: column; min-height: 0;
      width: 100%; max-width: var(--shell-max); margin: 0 auto;
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
    .hero-inner { position: relative; z-index: 1; }
    .hero-eyebrow {
      font-family: 'DM Mono', monospace;
      font-size: 0.68rem; font-weight: 800; letter-spacing: .1em;
      text-transform: uppercase; color: var(--gold); margin-bottom: 6px;
    }
    .hero-title {
      font-size: clamp(1.05rem, 2.2vw + 0.55rem, 1.25rem);
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
    .hb-type   { background: rgba(240,200,96,.15); color: var(--gold); border-color: rgba(240,200,96,.25); }
    .hb-campus { background: rgba(96,200,240,.10); color: #7dd3fc;     border-color: rgba(96,200,240,.2); }
    .hb-status { background: rgba(52,211,153,.12); color: #34d399;     border-color: rgba(52,211,153,.22); }
    .hb-status.st-attention { background: rgba(251,146,60,.12); color: #fb923c; border-color: rgba(251,146,60,.22); }
    .hb-status.st-default   { background: rgba(148,163,184,.10); color: #94a3b8; border-color: rgba(148,163,184,.2); }

    /* Hero fields strip */
    .hero-fields {
      display: grid; grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1px; background: rgba(255,255,255,.10);
      border-radius: 16px; overflow: hidden;
      border: 1px solid rgba(255,255,255,.12); position: relative; z-index: 2; margin-bottom: 10px;
    }
    .hero-field.span2 { grid-column: span 2; }
    .hero-field { background: rgba(0,0,0,.15); padding: 13px 16px; transition: background .2s; }
    .hero-field:hover { background: rgba(0,0,0,.07); }
    .hf-label {
      font-family: 'DM Mono', monospace;
      font-size: 0.56rem; font-weight: 700; letter-spacing: .16em;
      text-transform: uppercase; color: rgba(255,255,255,.38); margin-bottom: 5px;
    }
    .hf-value { font-size: 0.82rem; font-weight: 700; color: rgba(255,255,255,.9); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .hf-value.empty { color: rgba(255,255,255,.22); font-style: italic; font-weight: 400; }
    .hf-value a { color: var(--gold); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
    .hf-value a:hover { text-decoration: underline; }
    .hero-owner-row {
      background: rgba(0,0,0,.15); border-radius: 12px; padding: 11px 16px;
      border: 1px solid rgba(255,255,255,.10); position: relative; z-index: 2;
    }
    .hero-owner-row .hf-value { white-space: normal; }

    /* ── MAIN GRID ── */
    .main-grid { display: grid; grid-template-columns: minmax(0, 260px) minmax(0, 1fr); gap: 16px; align-items: stretch; flex: 1; min-height: 0; }

    /* ── LEFT PANEL ── */
    .info-panel { display: flex; flex-direction: column; gap: 16px; position: sticky; top: 88px; align-self: start; }
    .panel-card {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line); box-shadow: 0 2px 12px rgba(15,23,42,.05); overflow: hidden;
    }
    .panel-card-header {
      padding: 16px 18px; border-bottom: 1px solid var(--line);
      background: linear-gradient(90deg, rgba(240,200,96,.08), rgba(165,44,48,.06));
    }
    .panel-card-title { font-size: 0.85rem; font-weight: 800; color: var(--ink); }
    .panel-card-sub   { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
    .panel-body { padding: 14px 16px; display: flex; flex-direction: column; }
    .stat-row {
      display: flex; align-items: center; justify-content: space-between;
      padding: 9px 0; border-bottom: 1px solid var(--line);
    }
    .stat-row:last-child { border-bottom: none; }
    .stat-label { font-size: 0.76rem; font-weight: 600; color: var(--ink); }
    .stat-val   { font-family: 'DM Mono', monospace; font-size: 0.72rem; font-weight: 700; color: var(--maroon); text-align: right; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .stat-val.empty { color: #cbd5e1; font-style: italic; }
    .access-row {
      display: flex; align-items: center; justify-content: space-between;
      padding: 8px 0; border-bottom: 1px solid var(--line);
    }
    .access-row:last-child { border-bottom: none; }
    .access-yes  { font-size: .65rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: rgba(16,185,129,.1); color: #059669; }
    .access-cond { font-size: .65rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: rgba(245,158,11,.1); color: #d97706; }
    .access-no   { font-size: .65rem; font-weight: 800; padding: 2px 8px; border-radius: 999px; background: var(--maroon-light); color: var(--maroon); }

    /* ── DETAILS CARD ── */
    .details-card {
      background: var(--card); border-radius: 22px;
      border: 1px solid var(--line); box-shadow: 0 2px 12px rgba(15,23,42,.05); overflow: hidden;
      display: flex; flex-direction: column; align-self: stretch;
    }
    .dc-body { padding: 22px; display: flex; flex-direction: column; gap: 18px; flex: 1; }
    .dc-header {
      padding: 20px 24px; border-bottom: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between; gap: 12px;
      flex-wrap: wrap;
      background: linear-gradient(90deg, rgba(165,44,48,.04), rgba(240,200,96,.04));
    }
    .dc-header > div:first-child { min-width: 0; flex: 1 1 200px; }
    .dc-title { font-size: clamp(0.92rem, 0.35vw + 0.82rem, 1rem); font-weight: 800; color: var(--ink); overflow-wrap: anywhere; }
    .dc-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 2px; overflow-wrap: anywhere; }
    .dc-badge {
      font-family: 'DM Mono', monospace; font-size: 0.68rem; font-weight: 700;
      color: var(--maroon); background: var(--maroon-light);
      border: 1.5px solid rgba(165,44,48,.2); padding: 4px 12px; border-radius: 20px;
      flex: 0 1 auto; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .detail-section { display: flex; flex-direction: column; gap: 10px; }
    .detail-section-title {
      font-family: 'DM Mono', monospace; font-size: 0.62rem; font-weight: 700;
      letter-spacing: .12em; text-transform: uppercase; color: var(--maroon);
      padding-bottom: 7px; border-bottom: 2px solid var(--maroon-light);
    }
    .detail-grid      { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 10px; }
    .detail-grid.full { grid-template-columns: 1fr; }
    .detail-item {
      background: var(--bg); border-radius: 14px;
      border: 1.5px solid var(--line); padding: 13px 15px;
      transition: border-color .18s, box-shadow .18s;
    }
    .detail-item:hover { border-color: rgba(165,44,48,.2); box-shadow: 0 3px 14px rgba(165,44,48,.07); }
    .di-label {
      font-family: 'DM Mono', monospace; font-size: 0.58rem; font-weight: 700;
      letter-spacing: .12em; text-transform: uppercase; color: var(--muted); margin-bottom: 5px;
    }
    .di-value { font-size: 0.84rem; font-weight: 700; color: var(--ink); overflow-wrap: anywhere; word-break: break-word; }
    .di-value.empty { color: #cbd5e1; font-style: italic; font-weight: 400; font-size: .8rem; }
    .di-value a { color: var(--maroon); text-decoration: underline; font-weight: 700; }
    .di-value a:hover { opacity: .8; }
    .ip-info-box {
      background: linear-gradient(135deg, rgba(165,44,48,.04), rgba(240,200,96,.04));
      border: 1.5px solid rgba(165,44,48,.12); border-radius: 16px;
      padding: 18px 20px; display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-start;
    }
    .ip-info-icon {
      width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
      background: var(--maroon-light);
      display: flex; align-items: center; justify-content: center; color: var(--maroon);
    }
    .ip-info-body { flex: 1; min-width: 0; }
    .ip-info-type { font-size: 0.88rem; font-weight: 800; color: var(--maroon); margin-bottom: 4px; }
    .ip-info-desc { font-size: 0.78rem; color: var(--muted); line-height: 1.6; overflow-wrap: anywhere; }
    .ip-info-tags { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 10px; }
    .ip-tag {
      font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 20px;
      background: var(--maroon-light); color: var(--maroon); border: 1px solid rgba(165,44,48,.18);
    }
    .remarks-box { background: var(--bg); border: 1.5px solid var(--line); border-radius: 14px; padding: 14px 16px; }
    .remarks-text  { font-size: 0.82rem; color: var(--ink); line-height: 1.7; overflow-wrap: anywhere; }
    .remarks-empty { font-size: 0.78rem; color: #cbd5e1; font-style: italic; }

    /* ── MODAL ── */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(15,23,42,.55); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center;
      padding: max(16px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(16px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left));
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
      background: #fff; border-radius: 24px; padding: clamp(20px, 4vw, 32px);
      width: min(440px, calc(100vw - 2rem)); max-width: 100%; position: relative;
      box-shadow: 0 32px 80px rgba(15,23,42,.18);
      animation: fadeSlideUp .3s forwards;
      box-sizing: border-box;
    }
    .modal-icon {
      width: 52px; height: 52px; border-radius: 16px;
      background: var(--maroon-light); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; margin-bottom: 16px;
    }
    .modal-title { font-size: clamp(1rem, 0.35vw + 0.92rem, 1.1rem); font-weight: 800; color: var(--ink); overflow-wrap: anywhere; }
    .modal-desc  { font-size: 0.82rem; color: var(--muted); margin-top: 6px; line-height: 1.6; overflow-wrap: anywhere; }
    .modal-btns  {
      display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px;
      align-items: stretch;
    }
    .modal-btns .btn-cancel {
      flex: 1 1 120px;
      justify-content: center;
      min-width: 0;
    }
    .modal-btns form {
      flex: 1 1 140px;
      min-width: 0;
      display: flex;
    }
    .modal-btns form .btn-confirm { width: 100%; justify-content: center; }
    .btn-cancel {
      padding: 12px; border-radius: 12px;
      border: 1.5px solid var(--line); background: none;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem); font-weight: 700;
      color: var(--muted); cursor: pointer; transition: all .18s;
      display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;
    }
    .btn-cancel:hover { background: var(--bg); }
    .btn-confirm {
      padding: 12px; border-radius: 12px;
      background: linear-gradient(135deg, var(--maroon), var(--maroon2));
      color: #fff; border: none;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(0.78rem, 0.15vw + 0.74rem, 0.82rem); font-weight: 700;
      cursor: pointer; box-shadow: 0 4px 14px rgba(165,44,48,.25); transition: all .18s;
      display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;
    }
    .btn-confirm:hover { box-shadow: 0 8px 20px rgba(165,44,48,.35); }

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

    /* ── ANIMATIONS ── */
    @keyframes fadeSlideUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .anim { opacity: 0; animation: fadeSlideUp .5s forwards; }
    .anim-1 { animation-delay: .05s; }
    .anim-2 { animation-delay: .12s; }
    .anim-3 { animation-delay: .19s; }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-thumb { background: rgba(15,23,42,.15); border-radius: 999px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 960px) {
      .main-grid { grid-template-columns: 1fr; }
      .info-panel { position: static; }
      .hero-fields { grid-template-columns: repeat(4, minmax(0, 1fr)); }
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
      .hero-fields { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .detail-grid { grid-template-columns: 1fr; }
      .dc-header, .dc-body { padding-left: clamp(14px, 4vw, 20px); padding-right: clamp(14px, 4vw, 20px); }
      .stat-row, .access-row {
        flex-wrap: wrap; gap: 6px 10px;
        align-items: flex-start;
      }
      .stat-val { max-width: 100%; text-align: left; white-space: normal; }
    }
    @media (max-width: 580px) {
      .guest-pill-long { display: none; }
      .hero-fields { grid-template-columns: minmax(0, 1fr); }
      .hf-value { white-space: normal; overflow: visible; text-overflow: unset; }
    }
    @media (max-width: 480px) {
      .modal-btns { flex-direction: column; }
      .modal-btns .btn-cancel,
      .modal-btns form { flex: 0 0 auto; width: 100%; }
    }
  </style>
</head>
<body>

@php
  $user   = $user   ?? (object)['name' => 'Guest Viewer', 'role' => 'Guest'];
  $record = $record ?? null;

  $urlGuest    = url('/guest');
  $urlRecords  = url('/ip-records');
  $urlHowTo    = url('/how-to-file');
  $urlLogout   = url('/logout');

  $recordId = $recordId
      ?? request()->route('recordId')
      ?? request()->route('record_id')
      ?? request()->route('id')
      ?? request()->query('record_id');

  $initials = collect(preg_split('/\s+/', trim($user->name ?? ''), -1, PREG_SPLIT_NO_EMPTY))
      ->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
  if ($initials === '') {
    $initials = 'G';
  }

  $statusClass = match(strtolower($record['status'] ?? '')) {
    'registered'      => 'hb-status',
    'under review'    => 'hb-status st-attention',
    'filed'           => 'hb-status st-attention',
    'needs attention' => 'hb-status st-attention',
    default           => 'hb-status st-default',
  };

  // IP type descriptions for guest education panel
  $ipDescriptions = [
    'patent'            => ['desc' => 'A patent grants an inventor exclusive rights to their invention for a limited period. It covers new and useful processes, machines, manufactures, or compositions of matter.', 'validity' => '20 years', 'tags' => ['Exclusive Rights', '20-Year Term', 'IPOPHL Filing']],
    'copyright'         => ['desc' => 'Copyright protects original works of authorship including literary, artistic, musical, and other creative works from the moment of creation.', 'validity' => '70 years', 'tags' => ['Automatic Protection', '70-Year Term', 'Creative Works']],
    'utility model'     => ['desc' => 'A utility model (petty patent) protects technical innovations that may not meet the full novelty requirements of a standard patent. Faster to obtain.', 'validity' => '10 years', 'tags' => ['Technical Innovation', '10-Year Term', 'Faster Process']],
    'industrial design' => ['desc' => 'An industrial design right protects the visual or ornamental aspects of a product — its shape, color, texture, or pattern.', 'validity' => '15 years', 'tags' => ['Visual Design', '15-Year Term', 'Aesthetic Protection']],
  ];
  $typeLower = strtolower(trim($record['type'] ?? ''));
  $ipInfo    = $ipDescriptions[$typeLower] ?? null;

  // Computed due date — validity derived from type alone, due date only needs registered
  $dueDate  = '—';
  $validity = '—';

  $validityMap = [
    'patent'            => '20 years',
    'copyright'         => '70 years',
    'utility model'     => '10 years',
    'industrial design' => '15 years',
  ];
  $yearsMap = [
    'patent'            => 20,
    'copyright'         => 70,
    'utility model'     => 10,
    'industrial design' => 15,
  ];

  // Validity always shows as long as we know the type
  if (!empty($record['type'])) {
    $validity = $validityMap[$typeLower] ?? '—';
  }

  // Due date only needs a valid registered date
  $rawReg = $record['registered'] ?? null;
  // Handle both Carbon objects and plain strings safely
  if (!empty($rawReg)) {
    try {
      $d = ($rawReg instanceof \Carbon\Carbon)
        ? $rawReg->copy()
        : \Carbon\Carbon::parse((string) $rawReg);
      $years = $yearsMap[$typeLower] ?? null;
      if ($years) {
        $dueDate = $d->addYears($years)->format('M d, Y');
      }
    } catch (\Exception $e) {
      $dueDate = '—';
    }
  }

  // Safe registered date string for display
  $registeredFormatted = '—';
  if (!empty($rawReg)) {
    try {
      $registeredFormatted = ($rawReg instanceof \Carbon\Carbon)
        ? $rawReg->format('M d, Y')
        : \Carbon\Carbon::parse((string) $rawReg)->format('M d, Y');
    } catch (\Exception $e) {
      $registeredFormatted = '—';
    }
  }
  $registeredFormattedLong = '—';
  if (!empty($rawReg)) {
    try {
      $registeredFormattedLong = ($rawReg instanceof \Carbon\Carbon)
        ? $rawReg->format('F d, Y')
        : \Carbon\Carbon::parse((string) $rawReg)->format('F d, Y');
    } catch (\Exception $e) {
      $registeredFormattedLong = '—';
    }
  }

  // Date Created formatted
  $dateCreationFormatted = '—';
  if (!empty($record['date_creation'])) {
    try {
      $dateCreationFormatted = \Carbon\Carbon::parse((string) $record['date_creation'])->format('M d, Y');
    } catch (\Exception $e) {
      $dateCreationFormatted = '—';
    }
  }
  $dateCreationFormattedLong = '—';
  if (!empty($record['date_creation'])) {
    try {
      $dateCreationFormattedLong = \Carbon\Carbon::parse((string) $record['date_creation'])->format('F d, Y');
    } catch (\Exception $e) {
      $dateCreationFormattedLong = '—';
    }
  }
@endphp

{{-- ══════════════ SIDEBAR ══════════════ --}}
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
    <button type="button" id="logoutBtn" class="nav-item" style="background:none;border:none;cursor:pointer;">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
        <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
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
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </a>
      <div class="topbar-titles">
        <div class="page-title">Record Detail</div>
        <div class="page-subtitle">IP record — public information</div>
      </div>
      @if($recordId)
        <div class="record-id-pill" title="{{ $recordId }}">
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="3"/>
            <line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="15" x2="13" y2="15"/>
          </svg>
          <span class="record-id-text">{{ $recordId }}</span>
        </div>
      @endif
    </div>
    <div class="topbar-right">
      <div class="guest-pill" title="{{ $user->name }} · Guest">
        <span class="guest-pill-dot"></span>
        <span class="guest-pill-text">
          <span class="guest-pill-long">{{ $user->name }} · </span>Guest
        </span>
      </div>
      <div class="avatar">{{ $initials }}</div>
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

  {{-- HERO RECORD CARD --}}
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

    {{-- Fields grid --}}
    <div class="hero-fields">
      <div class="hero-field">
        <div class="hf-label">Registration Number</div>
        <div class="hf-value @if(empty($record['registration_number'])) empty @endif">
          {{ $record['registration_number'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Campus</div>
        <div class="hf-value @if(empty($record['campus'])) empty @endif">
          {{ $record['campus'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">College</div>
        <div class="hf-value @if(empty($record['college'])) empty @endif">
          {{ $record['college'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Program</div>
        <div class="hf-value @if(empty($record['program'])) empty @endif">
          {{ $record['program'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field">
        <div class="hf-label">Date Created</div>
        <div class="hf-value @if($dateCreationFormatted === '—') empty @endif">
          {{ $dateCreationFormatted }}
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
      <div class="hero-field">
        <div class="hf-label">Class of Work</div>
        <div class="hf-value @if(empty($record['class_of_work'])) empty @endif">
          {{ $record['class_of_work'] ?? '—' }}
        </div>
      </div>
      <div class="hero-field span2">
        <div class="hf-label">Remarks</div>
        <div class="hf-value @if(empty($record['remarks'])) empty @endif" style="white-space:normal;font-size:.78rem;">
          {{ $record['remarks'] ?? '—' }}
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

    <div class="main-grid anim anim-2">

      {{-- LEFT: info panels --}}
      <aside class="info-panel anim anim-2">

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
              <span class="stat-val {{ empty($record['type']) ? 'empty' : '' }}">
                {{ $record['type'] ?? '—' }}
              </span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Campus</span>
              <span class="stat-val {{ empty($record['campus']) ? 'empty' : '' }}">
                {{ $record['campus'] ?? '—' }}
              </span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Status</span>
              <span class="stat-val {{ empty($record['status']) ? 'empty' : '' }}">
                {{ $record['status'] ?? '—' }}
              </span>
            </div>
            <div class="stat-row">
              <span class="stat-label">Validity Period</span>
              <span class="stat-val {{ $validity === '—' ? 'empty' : '' }}">{{ $validity }}</span>
            </div>
          </div>
        </div>

        {{-- Guest Access Info --}}
        <div class="panel-card">
          <div class="panel-card-header">
            <div class="panel-card-title">Your Access Level</div>
            <div class="panel-card-sub">Guest permissions</div>
          </div>
          <div class="panel-body">
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">View record details</span>
              <span class="access-yes">Yes</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">Open GDrive file</span>
              <span class="access-cond">If enabled</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">Edit this record</span>
              <span class="access-no">No</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">View change history</span>
              <span class="access-no">No</span>
            </div>
            <div class="access-row">
              <span style="font-size:.76rem;font-weight:600;color:var(--ink);">Create new records</span>
              <span class="access-no">No</span>
            </div>
          </div>
        </div>

      </aside>

      {{-- RIGHT: full details --}}
      <div class="details-card anim anim-3">
      <div class="dc-header">
        <div>
            <div class="dc-title">Record Information</div>
            <div class="dc-sub">Displayed fields for this IP record</div>
          </div>
          <div class="dc-badge"># {{ $recordId ?? '—' }}</div>
        </div>

        <div class="dc-body">

          <div class="detail-section">
            <div class="detail-section-title">Displayed Record Data</div>
            <div class="detail-grid">
              <div class="detail-item">
                <div class="di-label">Registration Number</div>
                <div class="di-value {{ empty($record['registration_number']) ? 'empty' : '' }}">
                  {{ $record['registration_number'] ?? '—' }}
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">Campus</div>
                <div class="di-value {{ empty($record['campus']) ? 'empty' : '' }}">
                  {{ $record['campus'] ?? '—' }}
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">College</div>
                <div class="di-value {{ empty($record['college']) ? 'empty' : '' }}">
                  {{ $record['college'] ?? '—' }}
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">Program</div>
                <div class="di-value {{ empty($record['program']) ? 'empty' : '' }}">
                  {{ $record['program'] ?? '—' }}
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">Date Created</div>
                <div class="di-value {{ $dateCreationFormattedLong === '—' ? 'empty' : '' }}">
                  {{ $dateCreationFormattedLong }}
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">Date Registered</div>
                <div class="di-value {{ $registeredFormattedLong === '—' ? 'empty' : '' }}">
                  {{ $registeredFormattedLong }}
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">Next Due</div>
                <div class="di-value {{ $dueDate === '—' ? 'empty' : '' }}">{{ $dueDate }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Validity</div>
                <div class="di-value {{ $validity === '—' ? 'empty' : '' }}">{{ $validity }}</div>
              </div>
              <div class="detail-item">
                <div class="di-label">Class of Work</div>
                <div class="di-value {{ empty($record['class_of_work']) ? 'empty' : '' }}">
                  {{ $record['class_of_work'] ?? '—' }}
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">GDrive</div>
                <div class="di-value {{ empty($record['gdrive_link']) ? 'empty' : '' }}">
                  @if(!empty($record['gdrive_link']))
                    <a href="{{ $record['gdrive_link'] }}" target="_blank" rel="noopener">
                      Open attached file ↗
                    </a>
                  @else
                    No document attached
                  @endif
                </div>
              </div>
              <div class="detail-item">
                <div class="di-label">Remarks</div>
                <div class="di-value {{ empty($record['remarks']) ? 'empty' : '' }}" style="white-space:normal;line-height:1.6;">
                  {{ $record['remarks'] ?? '—' }}
                </div>
              </div>
              <div class="detail-item" style="grid-column: 1 / -1;">
                <div class="di-label">Owner / Inventor</div>
                <div class="di-value {{ empty($record['owner']) ? 'empty' : '' }}" style="white-space:normal;line-height:1.6;">
                  {{ !empty($record['owner']) ? $record['owner'] : 'No owner information available for this record' }}
                </div>
              </div>
            </div>
          </div>

        </div>{{-- /dc-body --}}
      </div>{{-- /details-card --}}

    </div>{{-- /main-grid --}}

    <footer class="page-footer">
      <div class="page-footer-left">© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="page-footer-right">Record Detail · Guest View</div>
    </footer>

  </div>{{-- /content --}}
</div>{{-- /main-wrap --}}

{{-- LOGOUT MODAL --}}
<div class="modal-overlay" id="logoutModal">
  <div class="modal-box">
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

<script>
(function(){
  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const modalOpen = document.getElementById('logoutModal')?.classList.contains('open');
    document.body.style.overflow = (sidebarOpen || modalOpen) ? 'hidden' : '';
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
  document.querySelectorAll('[data-close-logout]').forEach(b => b.addEventListener('click', () => closeModal('logoutModal')));
  document.getElementById('logoutModal')?.addEventListener('click', e => { if (e.target === e.currentTarget) closeModal('logoutModal'); });

  const logoutForm = document.getElementById('logoutForm');
  if (logoutForm?.dataset.simulate === 'true') {
    logoutForm.addEventListener('submit', ev => {
      ev.preventDefault();
      closeModal('logoutModal');
      setTimeout(() => window.location.href = '{{ url('/') }}', 220);
    });
  }

  const hamburgerBtn    = document.getElementById('hamburgerBtn');
  const mainSidebar   = document.getElementById('mainSidebar');
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

  mainSidebar?.querySelectorAll('button.nav-item').forEach(btn => {
    btn.addEventListener('click', () => { if (window.innerWidth <= 768) closeMobileSidebar(); });
  });

  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeMobileSidebar();
  });

  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (document.getElementById('logoutModal')?.classList.contains('open')) closeModal('logoutModal');
    else if (mainSidebar?.classList.contains('mobile-open')) closeMobileSidebar();
  });
})();
</script>

</body>
</html>