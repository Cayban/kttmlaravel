<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — New Record</title>
  <link rel="icon" type="image/png" href="{{ asset('images/KTTMLOGOFAV-512.png') }}">

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
      overflow: hidden;
    }
    .sidebar-logo.has-image { background: transparent; }
    .sidebar-logo img { width: 42px; height: 42px; object-fit: cover; border-radius: 14px; display: block; }
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
  background-color: #EEE9E9;
  background-image: linear-gradient(rgba(165,44,48,.055) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(165,44,48,.055) 1px, transparent 1px);
  background-size: 28px 28px;
}
    @media (max-width: 640px) {
      .content {
        background-position: center top;
        background-size: auto 100%;
      }
    }

    /* ══════════ BATCH UPLOAD BUTTON ══════════ */
    .btn-batch {
      display: inline-flex; align-items: center; justify-content: center; gap: 7px;
      background: #fff; color: var(--maroon);
      border: 1.5px solid var(--maroon); cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem); font-weight: 700;
      padding: clamp(7px, 1.2vw, 9px) clamp(12px, 2vw, 16px);
      border-radius: 10px; transition: background .18s, color .18s, transform .18s;
      flex: 0 1 auto; white-space: nowrap;
    }
    .btn-batch:hover { background: var(--maroon-light); transform: translateY(-1px); }

    /* ══════════ BATCH MODAL ══════════ */
    .batch-backdrop {
      display: none; position: fixed; inset: 0; z-index: 200;
      background: rgba(15,23,42,.55); backdrop-filter: blur(4px);
      align-items: center; justify-content: center; padding: 20px;
    }
    .batch-backdrop.open { display: flex; }
    .batch-modal {
      background: #fff; border-radius: 24px;
      width: 100%; max-width: 820px; max-height: 88vh;
      display: flex; flex-direction: column;
      box-shadow: 0 24px 64px rgba(15,23,42,.22); overflow: hidden;
    }
    .bm-head {
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      padding: 20px 24px; display: flex; align-items: center; gap: 14px; flex-shrink: 0;
    }
    .bm-head-icon {
      width: 40px; height: 40px; border-radius: 12px;
      background: rgba(255,255,255,.12);
      display: flex; align-items: center; justify-content: center; color: var(--gold); flex-shrink: 0;
    }
    .bm-title { font-size: 1rem; font-weight: 800; color: #fff; }
    .bm-sub   { font-size: 0.7rem; color: rgba(255,255,255,.55); margin-top: 2px; }
    .bm-close {
      margin-left: auto; width: 34px; height: 34px; border-radius: 10px;
      background: rgba(255,255,255,.12); border: none; color: #fff;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background .15s; flex-shrink: 0;
    }
    .bm-close:hover { background: rgba(255,255,255,.22); }
    .bm-body { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 20px; }

    .bm-dropzone {
      border: 2px dashed rgba(165,44,48,.3); border-radius: 16px;
      padding: 40px 24px; text-align: center; cursor: pointer;
      transition: border-color .18s, background .18s; background: #fafafa; position: relative;
    }
    .bm-dropzone.dragover { border-color: var(--maroon); background: var(--maroon-light); }
    .bm-dropzone-icon {
      margin: 0 auto 12px; width: 48px; height: 48px; border-radius: 14px;
      background: var(--maroon-light); display: flex; align-items: center; justify-content: center; color: var(--maroon);
    }
    .bm-dropzone-title { font-size: 0.88rem; font-weight: 700; color: var(--ink); }
    .bm-dropzone-sub   { font-size: 0.72rem; color: var(--muted); margin-top: 5px; line-height: 1.5; }
    .bm-dropzone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .bm-file-chosen {
      align-items: center; gap: 10px; padding: 10px 14px;
      background: var(--maroon-light); border-radius: 10px; margin-top: 12px; display: none;
    }
    .bm-file-chosen.show { display: flex; }
    .bm-file-name { font-size: 0.75rem; font-weight: 600; color: var(--maroon); flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .bm-file-clear { background: none; border: none; cursor: pointer; color: var(--maroon); display: flex; align-items: center; padding: 0; }

    .bm-template-strip {
      background: #F8FAFF; border: 1px solid rgba(55,138,221,.18);
      border-radius: 12px; padding: 12px 16px;
      display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .bm-template-label { font-size: 0.72rem; color: #334155; flex: 1; line-height: 1.6; }
    .bm-template-label strong { color: var(--ink); font-weight: 700; }
    .bm-template-btn {
      font-size: 0.7rem; font-weight: 700; padding: 6px 14px;
      border-radius: 8px; border: 1.5px solid rgba(55,138,221,.4);
      background: #fff; color: #2563EB; cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif; white-space: nowrap; transition: background .15s;
    }
    .bm-template-btn:hover { background: #EFF6FF; }

    .bm-preview { display: none; flex-direction: column; gap: 12px; }
    .bm-preview.show { display: flex; }
    .bm-preview-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
    .bm-preview-title { font-size: 0.82rem; font-weight: 800; color: var(--ink); }
    .bm-stats { display: flex; gap: 8px; flex-wrap: wrap; }
    .bm-stat { font-size: 0.68rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
    .bm-stat.ok  { background: #ECFDF5; color: #065F46; }
    .bm-stat.err { background: #FEF2F2; color: #B91C1C; }
    .bm-table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid var(--line); }
    .bm-table { width: 100%; border-collapse: collapse; font-size: 0.72rem; min-width: 600px; }
    .bm-table thead { background: linear-gradient(135deg, var(--maroon2), var(--maroon3)); }
    .bm-table thead th { padding: 9px 12px; text-align: left; color: #fff; font-weight: 700; font-size: 0.63rem; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; }
    .bm-table tbody tr { border-bottom: 1px solid var(--line); transition: background .12s; }
    .bm-table tbody tr:last-child { border-bottom: none; }
    .bm-table tbody tr:hover { background: #fafafa; }
    .bm-table tbody tr.row-error { background: #FFF5F5; }
    .bm-table td { padding: 8px 12px; color: var(--ink); white-space: nowrap; max-width: 180px; overflow: hidden; text-overflow: ellipsis; vertical-align: middle; }
    .bm-table td.cell-error { color: #B91C1C; font-size: 0.65rem; font-weight: 600; }
    .bm-gender-select { font-size: 0.68rem; padding: 3px 6px; border: 1px solid var(--line); border-radius: 6px; background: #fff; color: var(--ink); cursor: pointer; width: 80px; }
    .bm-gender-select:focus { outline: 2px solid var(--maroon2); }
    .bm-gender-prefilled { border-color: #16a34a; background: #f0fdf4; }
    .bm-stat.warn { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; border-radius: 6px; padding: 2px 8px; font-size: 0.7rem; font-weight: 600; }
    .row-updated td { background: #f0fdf4 !important; }
    .row-badge.updated { background: #dcfce7; color: #15803d; border: 1px solid #86efac; border-radius: 5px; padding: 2px 7px; font-size: 0.65rem; font-weight: 700; white-space: nowrap; }
    .bm-remove-row-btn  { font-size: 0.65rem; padding: 2px 7px; border: 1px solid #fca5a5; background: #fff1f2; color: #b91c1c; border-radius: 5px; cursor: pointer; white-space: nowrap; flex-shrink: 0; }
    .bm-remove-row-btn:hover  { background: #fee2e2; }
    .bm-compare-btn     { font-size: 0.65rem; padding: 2px 7px; border: 1px solid var(--maroon); background: #fff8f8; color: var(--maroon); border-radius: 5px; cursor: pointer; white-space: nowrap; flex-shrink: 0; }
    .bm-compare-btn:hover     { background: var(--maroon); color: #fff; }
    .bm-dismiss-btn     { font-size: 0.65rem; padding: 2px 7px; border: 1px solid #86efac; background: #f0fdf4; color: #15803d; border-radius: 5px; cursor: pointer; white-space: nowrap; flex-shrink: 0; }
    .bm-dismiss-btn:hover     { background: #dcfce7; }
    .cmp-row { align-items: stretch; }
    .cmp-row-label { font-size: 0.62rem; font-weight: 700; letter-spacing: .06em; color: var(--muted); text-transform: uppercase; padding: 10px 12px; display: flex; align-items: center; border-right: 1px solid var(--line); }
    .cmp-row-csv { font-size: 0.8rem; color: var(--text); padding: 10px 12px; display: flex; align-items: center; border-right: 1px solid var(--line); word-break: break-word; }
    .cmp-row-csv.diff { color: #92400e; font-weight: 600; }
    .cmp-row-edit { padding: 6px 10px; display: flex; align-items: center; }
    .cmp-input { font-size: 0.8rem; color: var(--text); background: #fff; border: 1.5px solid var(--line); border-radius: 6px; padding: 5px 8px; width: 100%; box-sizing: border-box; font-family: inherit; }
    .cmp-input.diff { border-color: #fbbf24; background: #fef9c3; }
    .cmp-input:focus { outline: none; border-color: var(--maroon); }
    .cmp-input--copied { border-color: #16a34a !important; background: #f0fdf4 !important; transition: border-color .2s, background .2s; }
    .cmp-row-arrow { display: flex; align-items: center; justify-content: center; border-right: 1px solid var(--line); border-left: 1px solid var(--line); }
    .cmp-copy-btn { font-size: 0.75rem; font-weight: 700; width: 24px; height: 24px; border-radius: 50%; border: 1.5px solid var(--maroon); background: #fff; color: var(--maroon); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .15s, color .15s; line-height: 1; padding: 0; }
    .cmp-copy-btn:hover { background: var(--maroon); color: #fff; }
    .cmp-copy-btn--done { background: #16a34a !important; border-color: #16a34a !important; color: #fff !important; }
    .cmp-copy-btn--disabled { font-size: 0.7rem; color: #cbd5e1; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; }
    .bm-prefill-badge { font-size: 0.58rem; font-weight: 700; color: #16a34a; background: #dcfce7; border: 1px solid #86efac; border-radius: 4px; padding: 1px 4px; white-space: nowrap; flex-shrink: 0; }
    .bm-author-row { display: flex; align-items: center; gap: 6px; padding: 3px 0; border-bottom: 1px solid var(--line); }
    .bm-author-row:last-child { border-bottom: none; }
    .bm-author-name { font-size: 0.68rem; color: var(--ink); flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .row-badge { font-size: 0.6rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
    .row-badge.ok  { background: #ECFDF5; color: #065F46; }
    .row-badge.err { background: #FEF2F2; color: #B91C1C; }

    .bm-footer {
      padding: 16px 24px; border-top: 1px solid var(--line);
      display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-shrink: 0; flex-wrap: wrap;
    }
    .bm-footer-note { font-size: 0.68rem; color: var(--muted); }
    .bm-footer-note span { color: #B91C1C; font-weight: 600; }
    .bm-footer-actions { display: flex; gap: 8px; }
    .bm-btn-cancel {
      font-size: 0.75rem; font-weight: 700; padding: 8px 18px;
      border-radius: 10px; border: 1.5px solid var(--line);
      background: #fff; color: var(--muted); cursor: pointer;
      font-family: 'Plus Jakarta Sans', sans-serif; transition: border-color .15s, color .15s;
    }
    .bm-btn-cancel:hover { border-color: var(--maroon); color: var(--maroon); }
    .bm-btn-import {
      font-size: 0.75rem; font-weight: 700; padding: 8px 20px;
      border-radius: 10px; border: none;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
      color: #fff; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;
      display: inline-flex; align-items: center; gap: 7px;
      box-shadow: 0 4px 14px rgba(165,44,48,.22);
      transition: opacity .15s, transform .15s;
    }
    .bm-btn-import:hover:not(:disabled) { opacity: .88; transform: translateY(-1px); }
    .bm-btn-import:disabled { opacity: .4; cursor: not-allowed; transform: none; }

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
    .required-legend { font-size: 0.72rem; color: var(--muted); margin: 0 0 12px 0; }
    .required-legend .req { font-weight: 700; }
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
    .field-required-highlight { border-color: #c0392b !important; background: #fff8f8 !important; }

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
    $campuses     = $campuses     ?? ['Alangilan','ARASOF-Nasugbu','Balayan','Lemery','Lipa','Malvar','Pablo Borbon','Rosario','San Juan','N/A'];
    $types        = $types        ?? ['Patent','Utility Model','Industrial Design','Copyright','Trademark'];
    $statuses     = $statuses     ?? ['Registered','Filed','Unregistered','Close to Expiry'];
    $colleges     = $colleges     ?? [];
    $programs     = $programs     ?? [];
    $nextRecordId = $nextRecordId ?? '';
    $initials     = strtoupper(substr($user->name ?? 'K', 0, 1) . (strpos($user->name ?? '', ' ') !== false ? substr($user->name, strpos($user->name, ' ') + 1, 1) : 'T'));
    $sessionAvatarImage = session('user_avatar_image', null);

    $urlDashboard = url('/home');
    $urlRecords   = url('/records');
    $urlLogout    = url('/logout');
    $urlProfile   = url('/profile');
    $urlStore     = url('/ipassets');
  @endphp

  {{-- SIDEBAR --}}
  <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
  <aside class="sidebar" id="mainSidebar" aria-label="Main navigation">
    <div class="sidebar-logo {{ $sessionAvatarImage ? 'has-image' : '' }}">
      @if($sessionAvatarImage)
        <img src="{{ asset('storage/avatars/' . $sessionAvatarImage) }}" alt="{{ $initials ?: 'KT' }}">
      @else
        {{ $initials ?: 'KT' }}
      @endif
    </div>
    <nav class="sidebar-nav" id="tutorialSidebar">
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
        <button id="batchUploadBtn" type="button" class="btn-batch" title="Batch upload records via CSV">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
            <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/>
          </svg>
          <span>Batch Upload</span>
        </button>
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
          <input type="hidden" id="bypassDuplicateInput" name="bypass_duplicate" value="0" />

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
              <p class="required-legend"><span class="req">*</span> Indicates a required field</p>

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
                  <label for="title" class="field-label" id="titleLabel">IP Title <span class="req">*</span></label>
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
                      <option value="Filed"          @selected(old('status') == 'Filed')>Filed</option>
                      <option value="Unregistered"   @selected(old('status') == 'Unregistered')>Unregistered</option>
                      <option value="Close to Expiry" @selected(old('status') == 'Close to Expiry')>Close to Expiry</option>
                    </select>
                  </div>
                  @error('status')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Class of Work --}}
                <div class="span-2 field-group" id="classOfWorkGroup">
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
                    <span id="regNumLabel">Registration Number</span>
                    <span id="regNumRequired" class="req" style="display:none;"> *</span>
                    <span id="registrationHint" class="field-hint" style="display:none;">— Not applicable for this status</span>
                  </label>
                  <input id="registration_number" name="registration_number" type="text"
                    value="{{ old('registration_number') }}"
                    placeholder="e.g., 4-2026-000123"
                    class="field-input" />
                  <div id="registrationNumberError" class="field-error" style="display:none;"></div>
                  @error('registration_number')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                  <label for="registered" class="field-label">
                    <span id="dateRegisteredLabel">Date Registered</span>
                    <span id="registeredHint" class="field-hint" style="display:none;">— Not applicable for this status</span>
                  </label>
                  <input id="registered" name="registered" type="date"
                    value="{{ old('registered') }}"
                    class="field-input" />
                  <div id="registeredDateError" class="field-error" style="display:none;"></div>
                  @error('registered')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field-group">
                  <label for="date_creation" class="field-label"><span id="dateCreationLabel">Date of Creation</span></label>
                  <input id="date_creation" name="date_creation" type="date"
                    value="{{ old('date_creation') }}"
                    class="field-input" />
                  <div id="dateCreationError" class="field-error" style="display:none;"></div>
                  @error('date_creation')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field-group" id="dateOfFilingGroup" style="display:none;">
                  <label for="date_of_filing" class="field-label">Date of Filing</label>
                  <input id="date_of_filing" name="date_of_filing" type="date"
                    value="{{ old('date_of_filing') }}"
                    class="field-input" />
                  <div id="dateOfFilingError" class="field-error" style="display:none;"></div>
                  @error('date_of_filing')<div class="field-error">{{ $message }}</div>@enderror
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
              <div class="tips-item"><span class="tips-num">2</span><span class="tips-text"><strong>Unregistered</strong> will lock the Date Registered field.</span></div>
              <div class="tips-item"><span class="tips-num">3</span><span class="tips-text">Use a GDrive link with the correct share access.</span></div>
              <div class="tips-item"><span class="tips-num">4</span><span class="tips-text">Click completed steps to go back and edit.</span></div>
            </div>
          </div>

        </aside>

        {{-- Footer spans full width --}}
        <footer class="wizard-footer">
          <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
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
    document.getElementById('closeDuplicateBtn')?.addEventListener('click', () => {
      bypassDuplicate = false;
      document.getElementById('bypassDuplicateInput').value = '0';
      closeModal('duplicateModal');
    });
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
    // Expose to window so the tutorial IIFE can switch panels without name collision
    window.__kttmShowFormStep = showStep;

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
    const registeredDateError = document.getElementById('registeredDateError');
    const dateCreationInput  = document.getElementById('date_creation');
    const dateCreationError  = document.getElementById('dateCreationError');
    const dateOfFilingInput  = document.getElementById('date_of_filing');
    const dateOfFilingError  = document.getElementById('dateOfFilingError');
    const regNumInput        = document.getElementById('registration_number');
    const regNumError        = document.getElementById('registrationNumberError');
    const regNumHint         = document.getElementById('registrationHint');

    const LOCKED_STATUSES  = ['unregistered'];
    const FILED_STATUSES   = ['filed'];
    const regNumRequired   = document.getElementById('regNumRequired');

    function normalise(val) { return String(val ?? '').trim().toLowerCase().replace(/\s+/g,' '); }
    function shouldLock(val)  { return LOCKED_STATUSES.includes(normalise(val)); }
    function isFiled(val)     { return FILED_STATUSES.includes(normalise(val)); }

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
      if(regNumHint)     regNumHint.style.display = 'inline';
      if(regNumRequired) regNumRequired.style.display = 'none';
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
    function applyFiledState(val) {
      if(isFiled(val)) {
        // Filed: Registration Number becomes required
        if(regNumInput) {
          regNumInput.required = true;
          regNumInput.classList.add('field-required-highlight');
        }
        if(regNumRequired) regNumRequired.style.display = 'inline';
      } else {
        if(regNumInput) {
          regNumInput.required = false;
          regNumInput.classList.remove('field-required-highlight');
        }
        if(regNumRequired) regNumRequired.style.display = 'none';
      }
    }
    function applyStatusState(val) {
      if(shouldLock(val)) {
        lockRegistered();
        applyFiledState(''); // clear filed state when locked
      } else {
        unlockRegistered();
        applyFiledState(val);
      }
    }
    statusSelect?.addEventListener('change', () => applyStatusState(statusSelect.value));
    applyStatusState(statusSelect?.value);

    /* ── Category / IP Type layout — adapts labels & fields per type ── */
    const typeSelect        = document.getElementById('type');
    const titleLabel        = document.getElementById('titleLabel');
    const classOfWorkGroup  = document.getElementById('classOfWorkGroup');
    const dateOfFilingGroup = document.getElementById('dateOfFilingGroup');
    const regNumLabel       = document.getElementById('regNumLabel');
    const dateRegLabel      = document.getElementById('dateRegisteredLabel');
    const dateCreLabel      = document.getElementById('dateCreationLabel');

    const TYPE_SCHEMAS = {
      copyright: {
        titleLabel:       'IP Title (Title of Work)',
        showClassWork:    true,
        showDateOfFiling: true,
        regNumLabel:      'Registration Number',
        dateRegLabel:     'Date Registered / Deposited',
        dateCreLabel:     'Date of Creation',
        regPlaceholder:   'e.g., 4-2026-000123',
      },
      patent: {
        titleLabel:       'Title of Application',
        showClassWork:    false,
        showDateOfFiling: false,
        regNumLabel:      'IPOPHL Application No.',
        dateRegLabel:     'Registration Date',
        dateCreLabel:     'Date Patented / Submitted',
        regPlaceholder:   'e.g., 2-2024-000456',
      },
      trademark: {
        titleLabel:       'Mark (Title)',
        showClassWork:    false,
        showDateOfFiling: false,
        regNumLabel:      'IPOPHL Reg. Number',
        dateRegLabel:     'Registration Date',
        dateCreLabel:     'Filing Date',
        regPlaceholder:   'e.g., 4-2023-012345',
      },
      'utility model': {
        titleLabel:       'Title of Application',
        showClassWork:    false,
        showDateOfFiling: false,
        regNumLabel:      'IPOPHL Application No.',
        dateRegLabel:     'Registration Date',
        dateCreLabel:     'Date Filed / Submitted',
        regPlaceholder:   'e.g., 2-2024-000456',
      },
      'industrial design': {
        titleLabel:       'Title of Application',
        showClassWork:    false,
        showDateOfFiling: false,
        regNumLabel:      'IPOPHL Application No.',
        dateRegLabel:     'Registration Date',
        dateCreLabel:     'Date Filed / Submitted',
        regPlaceholder:   'e.g., 2-2024-000456',
      },
    };

    function applyTypeLayout(val) {
      const key    = (val || '').trim().toLowerCase();
      const schema = TYPE_SCHEMAS[key] || null;
      const regInput = document.getElementById('registration_number');

      if (!schema) {
        // Default / nothing selected
        if (titleLabel)        titleLabel.textContent        = 'IP Title';
        if (classOfWorkGroup)  classOfWorkGroup.style.display  = '';
        if (dateOfFilingGroup) dateOfFilingGroup.style.display = 'none';
        if (regNumLabel)       regNumLabel.textContent       = 'Registration Number';
        if (dateRegLabel)      dateRegLabel.textContent      = 'Date Registered';
        if (dateCreLabel)      dateCreLabel.textContent      = 'Date of Creation';
        if (regInput)          regInput.placeholder          = 'e.g., 4-2026-000123';
        // Clear date_of_filing when not applicable
        const dof = document.getElementById('date_of_filing');
        if (dof) dof.value = '';
      } else {
        if (titleLabel)        titleLabel.textContent        = schema.titleLabel;
        if (classOfWorkGroup)  classOfWorkGroup.style.display  = schema.showClassWork    ? '' : 'none';
        if (dateOfFilingGroup) dateOfFilingGroup.style.display = schema.showDateOfFiling ? '' : 'none';
        if (regNumLabel)       regNumLabel.textContent       = schema.regNumLabel;
        if (dateRegLabel)      dateRegLabel.textContent      = schema.dateRegLabel;
        if (dateCreLabel)      dateCreLabel.textContent      = schema.dateCreLabel;
        if (regInput)          regInput.placeholder          = schema.regPlaceholder;
        // Clear date_of_filing if not applicable to this type
        if (!schema.showDateOfFiling) {
          const dof = document.getElementById('date_of_filing');
          if (dof) dof.value = '';
        }
      }
    }

    typeSelect?.addEventListener('change', () => applyTypeLayout(typeSelect.value));
    applyTypeLayout(typeSelect?.value);

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
      set('status',              'Filed');
      set('class_of_work',       'Literary Work');
      set('campus',              '{{ $campuses[0] ?? "Alangilan" }}');
      set('registration_number', 'REG-2026-000001');
      set('gdrive_link',         'https://drive.google.com/');
      set('remarks',             'Sample remarks for the new record.');
      inventors = [{ name: 'Juan Dela Cruz', gender: 'Male' }, { name: 'Maria Santos', gender: 'Female' }];
      renderInventors();
      shouldLock(statusSelect?.value) ? lockRegistered() : unlockRegistered();
      applyTypeLayout(document.getElementById('type')?.value);
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

    function setRegistrationNumberError(message = '') {
      if (!regNumError || !regNumInput) return;
      regNumError.textContent = message;
      regNumError.style.display = message ? 'block' : 'none';
      regNumInput.setCustomValidity(message);
    }

    regNumInput?.addEventListener('input', () => setRegistrationNumberError());

    function setDateFieldError(input, errorEl, message = '') {
      if (!input || !errorEl) return;
      errorEl.textContent = message;
      errorEl.style.display = message ? 'block' : 'none';
      input.setCustomValidity(message);
    }

    function validateDateOrder() {
      setDateFieldError(dateCreationInput, dateCreationError);
      setDateFieldError(dateOfFilingInput, dateOfFilingError);
      setDateFieldError(registeredInput, registeredDateError);

      const registered = registeredInput?.value || '';
      if (!registered) return true;

      let ok = true;
      if (dateCreationInput?.value && dateCreationInput.value > registered) {
        setDateFieldError(dateCreationInput, dateCreationError, 'Date of creation cannot be after the registration date.');
        ok = false;
      }
      if (dateOfFilingInput?.value && dateOfFilingInput.value > registered) {
        setDateFieldError(dateOfFilingInput, dateOfFilingError, 'Date of filing cannot be after the registration date.');
        ok = false;
      }
      return ok;
    }

    [registeredInput, dateCreationInput, dateOfFilingInput].forEach(input => {
      input?.addEventListener('input', validateDateOrder);
      input?.addEventListener('change', validateDateOrder);
    });

    document.getElementById('createAnywayBtn')?.addEventListener('click', () => {
      bypassDuplicate = true;
      document.getElementById('bypassDuplicateInput').value = '1';
      closeModal('duplicateModal');
      document.getElementById('createRecordForm')?.submit();
    });
    document.getElementById('viewExistingBtn')?.addEventListener('click', () => {
      const titleVal = document.getElementById('title')?.value.trim() ?? '';
      window.location.href = '{{ $urlRecords }}' + (titleVal ? '?q=' + encodeURIComponent(titleVal) : '');
    });

    /* ── Form submit ── */
    document.getElementById('createRecordForm')?.addEventListener('submit', async (e) => {
      e.preventDefault();
      inventorsError.style.display = 'none';
      setRegistrationNumberError();
      if(shouldLock(statusSelect?.value)) { registeredInput.value = ''; if(regNumInput) regNumInput.value = ''; }
      if(!validateDateOrder()) { showToast('Please fix the date order before saving.', 'error'); return; }
      if(!e.target.checkValidity()) { showToast('Please complete all required fields.', 'error'); return; }

      const registrationVal = regNumInput?.value.trim() ?? '';
      if(registrationVal) {
        try {
          const resp = await fetch("{{ url('/ipassets/check-registration-number') }}?registration_number=" + encodeURIComponent(registrationVal), {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
          });
          if(resp.ok) {
            const data = await resp.json();
            if(data.exists) {
              const match = data.record || {};
              const message = `Registration number already used by ${match.record_id || 'another record'}${match.ip_title ? ' — ' + match.ip_title : ''}.`;
              setRegistrationNumberError(message);
              showToast('Registration number already exists. Each record must have a unique registration number.', 'error');
              regNumInput?.focus();
              return;
            }
          }
        } catch(err) { /* server validation still enforces uniqueness */ }
      }

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

@if(isset($showTutorial) && $showTutorial)
<style>
  #kttmTutOverlay { position:fixed;inset:0;z-index:9000;pointer-events:all; }
  #kttmTutSvg     { position:fixed;inset:0;width:100%;height:100%;z-index:9001;pointer-events:none; }
  #kttmTutCard {
    position:fixed;z-index:9002;background:#fff;border-radius:18px;
    padding:22px 24px 18px;width:min(360px,calc(100vw - 32px));
    box-shadow:0 24px 64px rgba(0,0,0,.22),0 0 0 1px rgba(0,0,0,.06);
    transition:top .32s cubic-bezier(.4,0,.2,1),left .32s cubic-bezier(.4,0,.2,1),opacity .22s ease;
  }
  #kttmTutCard.tut-hidden { opacity:0;pointer-events:none; }
  .tut-step-label { font-size:.62rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#A52C30;margin-bottom:6px;font-family:'DM Mono',monospace; }
  .tut-title { font-size:1rem;font-weight:800;color:#0F172A;margin-bottom:6px;line-height:1.3; }
  .tut-desc  { font-size:.82rem;color:#64748B;line-height:1.6;margin-bottom:16px; }
  .tut-demo-badge {
    display:inline-flex;align-items:center;gap:5px;
    background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);
    color:#059669;border-radius:20px;padding:3px 10px;
    font-size:.68rem;font-weight:800;font-family:'DM Mono',monospace;
    margin-bottom:10px;
  }
  .tut-footer { display:flex;align-items:center;justify-content:space-between;gap:10px; }
  .tut-dots   { display:flex;gap:5px;align-items:center; }
  .tut-dot    { width:6px;height:6px;border-radius:50%;background:#e2e8f0;transition:background .2s,width .2s; }
  .tut-dot.active { background:#A52C30;width:18px;border-radius:3px; }
  .tut-actions { display:flex;gap:8px; }
  .tut-btn-skip {
    padding:8px 14px;border-radius:10px;border:1.5px solid #e2e8f0;background:none;
    font-family:inherit;font-size:.75rem;font-weight:700;color:#94a3b8;cursor:pointer;transition:all .15s;
  }
  .tut-btn-skip:hover { border-color:#A52C30;color:#A52C30; }
  .tut-btn-back {
    padding:8px 14px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;
    font-family:inherit;font-size:.75rem;font-weight:700;color:#64748B;cursor:pointer;transition:all .15s;
  }
  .tut-btn-back:hover:not(:disabled) { border-color:#A52C30;color:#A52C30; }
  .tut-btn-back:disabled { opacity:.45;cursor:not-allowed; }
  .tut-btn-next {
    padding:8px 20px;border-radius:10px;border:none;
    background:linear-gradient(135deg,#A52C30,#7E1F23);
    font-family:inherit;font-size:.75rem;font-weight:800;color:#fff;cursor:pointer;
    box-shadow:0 4px 12px rgba(165,44,48,.3);transition:transform .15s,box-shadow .15s;
  }
  .tut-btn-next:hover { transform:translateY(-1px);box-shadow:0 6px 16px rgba(165,44,48,.4); }
  #kttmTutPulse {
    position:fixed;z-index:9003;border-radius:14px;
    border:2.5px solid #F0C860;pointer-events:none;
    animation:tutPulse 1.8s ease-out infinite;
    transition:all .32s cubic-bezier(.4,0,.2,1);
  }
  @keyframes tutPulse {
    0%   { box-shadow:0 0 0 0 rgba(240,200,96,.55); }
    70%  { box-shadow:0 0 0 10px rgba(240,200,96,0); }
    100% { box-shadow:0 0 0 0 rgba(240,200,96,0); }
  }
  .tut-field-highlight {
    outline:2.5px solid #F0C860 !important;
    box-shadow:0 0 0 4px rgba(240,200,96,.25) !important;
    transition:outline .2s,box-shadow .2s;
  }
</style>

<div id="kttmTutOverlay"></div>
<svg id="kttmTutSvg" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <mask id="kttmTutMask">
      <rect width="100%" height="100%" fill="white"/>
      <rect id="kttmTutHole" x="0" y="0" width="0" height="0" rx="14" fill="black"/>
    </mask>
  </defs>
  <rect width="100%" height="100%" fill="rgba(10,14,26,0.72)" mask="url(#kttmTutMask)"/>
</svg>
<div id="kttmTutPulse"></div>
<div id="kttmTutCard" class="tut-hidden">
  <div class="tut-demo-badge" id="kttmDemoBadge" style="display:none;">
    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    Demo fill active
  </div>
  <div class="tut-step-label" id="kttmTutLabel">Step 1 of 8</div>
  <div class="tut-title"      id="kttmTutTitle"></div>
  <div class="tut-desc"       id="kttmTutDesc"></div>
  <div class="tut-footer">
    <div class="tut-dots"  id="kttmTutDots"></div>
    <div class="tut-actions">
      <button class="tut-btn-skip" id="kttmTutSkip">Skip tutorial</button>
      <button class="tut-btn-back" id="kttmTutBack" disabled>Back</button>
      <button class="tut-btn-next" id="kttmTutNext">Next</button>
    </div>
  </div>
</div>

<script>
(function() {

  /* ── Demo data ── */
  const DEMO = {
    title:               'Smart Sensor-Based Pediatric Screening Kiosk',
    type:                'Patent',
    status:              'Filed',
    class_of_work:       'Technological Invention',
    inventorName:        'Juan dela Cruz',
    inventorGender:      'Male',
    campus:              null, // will use first available option
    college:             'College of Engineering',
    program:             'BS Computer Engineering',
    registration_number: '4-2024-000123',
    date_creation:       '2024-03-15',
    gdrive_link:         'https://drive.google.com/drive/folders/demo',
    remarks:             'Demo entry created during tutorial. Please delete after review.',
  };

  /* ── Steps definition ── */
  const STEPS = [
    {
      target:  'stepsBar',
      title:   '3-Step Form',
      desc:    'This form is split into 3 steps: Identification, Ownership, and Filing Details. The tutorial will demo-fill each field so you can see how it works.',
      demo:    false,
    },
    {
      target:  'panel1',
      title:   'Step 1 — Identification',
      desc:    'We are filling in the IP Title, Category, Status, and Class of Work with sample data. These are the core fields that identify the record.',
      demo:    true,
      onShow:  function() { fillStep1(); },
    },
    {
      target:  'next1Btn',
      title:   'Proceed to Ownership',
      desc:    'Step 1 is complete. Click Next below to proceed — the form will advance to Step 2 automatically.',
      demo:    true,
      onShow:  function() { advanceFormTo(2); },
    },
    {
      target:  'panel2',
      title:   'Step 2 — Ownership',
      desc:    'Now we are adding an inventor and filling in the campus, college, and program fields with sample data.',
      demo:    true,
      onShow:  function() { fillStep2(); },
    },
    {
      target:  'next2Btn',
      title:   'Proceed to Filing Details',
      desc:    'Step 2 is complete. Click Next below to proceed — the form will advance to Step 3 automatically.',
      demo:    true,
      onShow:  function() { advanceFormTo(3); },
    },
    {
      target:  'panel3',
      title:   'Step 3 — Filing Details',
      desc:    'Here you fill in the registration number, dates, GDrive link, and any remarks. Optional fields can be left blank.',
      demo:    true,
      onShow:  function() { fillStep3(); },
    },
    {
      target:  'reviewGrid',
      title:   'Review Summary',
      desc:    'Before saving, review all the details here. Everything you filled in is summarised in this grid. Check it carefully before submitting.',
      demo:    true,
    },
    {
      target:  'saveBtn',
      title:   'Save the Record',
      desc:    'When everything looks correct, click Save Record to submit. The record will be saved and you will be redirected back to the Records page.',
      demo:    true,
      onShow:  function() { clearDemoData(); },
    },
  ];

  let current = 0;
  const TOTAL  = STEPS.length;
  const PAD    = 12;

  const overlay  = document.getElementById('kttmTutOverlay');
  const hole     = document.getElementById('kttmTutHole');
  const pulse    = document.getElementById('kttmTutPulse');
  const card     = document.getElementById('kttmTutCard');
  const labelEl  = document.getElementById('kttmTutLabel');
  const titleEl  = document.getElementById('kttmTutTitle');
  const descEl   = document.getElementById('kttmTutDesc');
  const dotsEl   = document.getElementById('kttmTutDots');
  const skipBtn  = document.getElementById('kttmTutSkip');
  const backBtn  = document.getElementById('kttmTutBack');
  const nextBtn  = document.getElementById('kttmTutNext');
  const demoBadge = document.getElementById('kttmDemoBadge');

  function syncNavButtons() {
    backBtn.disabled = current === 0;
  }

  /* ── Helpers ── */
  function setVal(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    el.value = val;
    el.dispatchEvent(new Event('input', { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
    el.classList.add('tut-field-highlight');
  }

  function removeHighlights() {
    document.querySelectorAll('.tut-field-highlight').forEach(el => el.classList.remove('tut-field-highlight'));
  }

  function advanceFormTo(step) {
    // Call via window to avoid colliding with the tutorial's own showStep function
    if (typeof window.__kttmShowFormStep === 'function') window.__kttmShowFormStep(step);
  }

  function fillStep1() {
    setVal('title',         DEMO.title);
    setVal('type',          DEMO.type);
    setVal('status',        DEMO.status);
    setVal('class_of_work', DEMO.class_of_work);
  }

  function fillStep2() {
    // Add one inventor via the existing inventors array
    if (typeof inventors !== 'undefined') {
      inventors.length = 0; // clear any existing
      inventors.push({ name: DEMO.inventorName, gender: DEMO.inventorGender });
      if (typeof renderInventors === 'function') renderInventors();
      if (typeof updateAsidePreview === 'function') updateAsidePreview();
    }
    // Campus: pick the first available option if DEMO.campus is null
    const campusSel = document.getElementById('campus');
    if (campusSel) {
      const firstOpt = campusSel.querySelector('option:not([value=""])');
      campusSel.value = DEMO.campus || (firstOpt ? firstOpt.value : '');
      campusSel.dispatchEvent(new Event('change', { bubbles: true }));
      campusSel.classList.add('tut-field-highlight');
    }
    setVal('college', DEMO.college);
    setVal('program', DEMO.program);
  }

  function fillStep3() {
    setVal('registration_number', DEMO.registration_number);
    setVal('date_creation',       DEMO.date_creation);
    setVal('gdrive_link',         DEMO.gdrive_link);
    setVal('remarks',             DEMO.remarks);
    // Trigger review update
    if (typeof updateReview === 'function') updateReview();
  }

  function clearDemoData() {
    // Clear all demo-filled fields so the user starts fresh if they want to submit
    ['title','type','status','class_of_work','registration_number','date_creation','gdrive_link','remarks'].forEach(id => {
      const el = document.getElementById(id); if (el) { el.value = ''; el.classList.remove('tut-field-highlight'); }
    });
    const campusSel = document.getElementById('campus');
    if (campusSel) { campusSel.value = ''; campusSel.classList.remove('tut-field-highlight'); }
    if (typeof inventors !== 'undefined') { inventors.length = 0; }
    if (typeof renderInventors === 'function') renderInventors();
    if (typeof updateReview === 'function') updateReview();
    removeHighlights();
    // NOTE: do NOT reset form step here — panel3 must stay visible so saveBtn can be highlighted.
    // The form is reset to step 1 only after the tutorial fully ends (see goNext / dismiss).
  }

  /* ── Build dots ── */
  function buildDots() {
    dotsEl.innerHTML = STEPS.map((_, i) =>
      `<div class="tut-dot${i === current ? ' active' : ''}" id="tut-dot-${i}"></div>`
    ).join('');
  }

  /* ── Show step ── */
  function showStep(idx) {
    removeHighlights();
    const step = STEPS[idx];

    // Run onShow hook before positioning
    if (typeof step.onShow === 'function') step.onShow();

    // Small delay to let DOM settle after form step transitions
    setTimeout(() => {
      const el = document.getElementById(step.target);
      if (!el) { goNext(); return; }

      const r = el.getBoundingClientRect();
      // Scroll element into view if needed
      if (r.top < 0 || r.bottom > window.innerHeight) {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => positionCard(el, idx), 380);
      } else {
        positionCard(el, idx);
      }
    }, 120);
  }

  function positionCard(el, idx) {
    const step = STEPS[idx];
    const r    = el.getBoundingClientRect();
    const x    = Math.floor(r.left  - PAD);
    const y    = Math.floor(r.top   - PAD);
    const w    = Math.ceil(r.width  + PAD * 2);
    const h    = Math.ceil(r.height + PAD * 2);

    hole.setAttribute('x', x); hole.setAttribute('y', y);
    hole.setAttribute('width', w); hole.setAttribute('height', h);
    pulse.style.cssText = `left:${x}px;top:${y}px;width:${w}px;height:${h}px;`;

    labelEl.textContent  = `Step ${idx + 1} of ${TOTAL}`;
    titleEl.textContent  = step.title;
    descEl.textContent   = step.desc;
    nextBtn.textContent  = (idx === TOTAL - 1) ? 'Done' : 'Next';
    syncNavButtons();
    demoBadge.style.display = step.demo ? 'inline-flex' : 'none';

    document.querySelectorAll('.tut-dot').forEach((d, i) => d.classList.toggle('active', i === idx));

    const cardW = Math.min(360, window.innerWidth - 32);
    const cardH = card.offsetHeight || 230;
    const gap   = 16;
    let left = x, top = y + h + gap;
    if (left + cardW > window.innerWidth  - gap) left = window.innerWidth  - cardW - gap;
    if (left < gap)                              left = gap;
    if (top  + cardH > window.innerHeight - gap) top  = y - cardH - gap;
    if (top  < gap)                              top  = gap;
    card.style.cssText += `left:${left}px;top:${top}px;width:${cardW}px;`;
    card.classList.remove('tut-hidden');
  }

  /* ── Advance ── */
  function goNext() {
    current++;
    if (current >= TOTAL) {
      hideOverlay();
      // Reset form to step 1 now that tutorial is fully done and we're navigating away
      if (typeof window.__kttmShowFormStep === 'function') window.__kttmShowFormStep(1);
      sessionStorage.setItem('kttm_tut_page', 'done_newrecord');
      window.location.href = '{{ url('/records') }}';
    } else {
      showStep(current);
    }
  }

  function goBack() {
    if (current === 0) return;
    current--;
    showStep(current);
  }

  function hideOverlay() {
    removeHighlights();
    card.classList.add('tut-hidden');
    overlay.style.display = 'none';
    document.getElementById('kttmTutSvg').style.display = 'none';
    pulse.style.display = 'none';
  }

  async function dismiss() {
    clearDemoData();
    // Reset form to step 1 on skip too
    if (typeof window.__kttmShowFormStep === 'function') window.__kttmShowFormStep(1);
    hideOverlay();
    // Clear the tutorial page flag so records tutorial doesn't re-trigger
    sessionStorage.removeItem('kttm_tut_page');
    try {
      await fetch('/tutorial/dismiss', {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type':'application/json','Accept':'application/json',
                   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      });
    } catch(e) {}
    // Navigate back to records — same as finishing normally
    window.location.href = '{{ url('/records') }}';
  }

  skipBtn.addEventListener('click', dismiss);
  backBtn.addEventListener('click', goBack);
  nextBtn.addEventListener('click', goNext);

  let rt;
  window.addEventListener('resize', function () {
    clearTimeout(rt);
    rt = setTimeout(function () {
      const overlay = document.getElementById('kttmOverlay');
      if (!overlay || overlay.style.display === 'none' || overlay.style.display === '') return;
      showStep(current);
    }, 120);
  });

  function boot() {
    // Only run if we arrived here from the records tutorial
    if (sessionStorage.getItem('kttm_tut_page') !== 'newrecord') return;
    buildDots(); showStep(0);
  }
  if (document.readyState === 'complete') setTimeout(boot, 700);
  else window.addEventListener('load', () => setTimeout(boot, 700));

})();
</script>
@endif

{{-- ═══════════════════════════════════════════
     BATCH UPLOAD MODAL
════════════════════════════════════════════ --}}

  {{-- COMPARE & UPDATE MODAL --}}
  <div class="modal-overlay" id="compareModal" style="z-index:2100;">
    <div class="modal-box" style="max-width:880px;width:96vw;max-height:90vh;overflow:hidden;display:flex;flex-direction:column;padding:0;">
      <div class="modal-head" style="background:linear-gradient(135deg,var(--maroon2),var(--maroon3));flex-shrink:0;">
        <div>
          <div class="modal-eyebrow">Possible Duplicate</div>
          <div class="modal-title" id="compareModalTitle">Compare Records</div>
        </div>
        <button type="button" class="modal-close" id="closeCompareBtn">✕</button>
      </div>
      <div style="overflow-y:auto;flex:1;">
        {{-- Column headers --}}
        <div style="display:grid;grid-template-columns:130px 1fr 36px 1fr;gap:0;position:sticky;top:0;z-index:1;background:#f8f7f7;border-bottom:2px solid var(--line);padding:8px 20px;">
          <div></div>
          <div style="font-size:0.62rem;font-weight:800;letter-spacing:.08em;color:var(--muted);padding:0 8px;">FROM CSV <span style="font-weight:500;color:#94a3b8;">(new)</span></div>
          <div></div>
          <div style="font-size:0.62rem;font-weight:800;letter-spacing:.08em;color:var(--muted);padding:0 8px;">
            EXISTING &nbsp;<span id="compareRecordId" style="color:var(--maroon);font-size:0.7rem;"></span>
            <span style="float:right;font-weight:500;color:#94a3b8;font-size:0.58rem;">editable</span>
          </div>
        </div>
        {{-- Rows injected here --}}
        <div id="compareRows" style="padding:0 20px 16px;"></div>
      </div>
      <div style="flex-shrink:0;border-top:1px solid var(--line);padding:14px 24px;display:flex;gap:8px;justify-content:flex-end;background:#fff;">
        <button type="button" class="bm-btn-cancel" id="cancelCompareBtn">Cancel</button>
        <button type="button" class="bm-btn-cancel" id="copyAllCsvBtn" style="border-color:var(--maroon);color:var(--maroon);" title="Copy all available CSV values into the existing record fields">→ Copy All from CSV</button>
        <button type="button" class="bm-btn-import" id="saveCompareBtn">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          Update Existing Record
        </button>
      </div>
    </div>
  </div>

<div class="batch-backdrop" id="batchBackdrop">
  <div class="batch-modal" role="dialog" aria-modal="true" aria-labelledby="batchModalTitle">

    {{-- Header --}}
    <div class="bm-head">
      <div class="bm-head-icon">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
          <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/>
        </svg>
      </div>
      <div>
        <div class="bm-title" id="batchModalTitle">Batch Upload Records</div>
        <div class="bm-sub">Upload a CSV file to import multiple IP records at once</div>
      </div>
      <button class="bm-close" id="batchClose" aria-label="Close">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    {{-- Body --}}
    <div class="bm-body">

      {{-- Template hint --}}
      <div class="bm-template-strip">
        <svg width="16" height="16" fill="none" stroke="#2563EB" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div class="bm-template-label">
          <strong>Required columns:</strong> TITLE OF WORK, TYPE, Status — all others optional.
          <em>DATE OF FILING</em> only applies to Copyright type. Download the template to get started.
        </div>
        <button type="button" class="bm-template-btn" id="downloadTemplateBtn">
          ↓ Download Template
        </button>
      </div>

      {{-- Dropzone --}}
      <div class="bm-dropzone" id="batchDropzone">
        <input type="file" id="batchFileInput" accept=".csv" />
        <div class="bm-dropzone-icon">
          <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
            <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/>
          </svg>
        </div>
        <div class="bm-dropzone-title">Drop your CSV here or click to browse</div>
        <div class="bm-dropzone-sub">Only .csv files are supported · Max 5 MB</div>
      </div>

      {{-- Chosen file pill --}}
      <div class="bm-file-chosen" id="batchFileChosen">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;color:var(--maroon)">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
        </svg>
        <span class="bm-file-name" id="batchFileName">—</span>
        <button type="button" class="bm-file-clear" id="batchFileClear" title="Remove file">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      {{-- Preview table --}}
      <div class="bm-preview" id="batchPreview">
        <div class="bm-preview-head">
          <div class="bm-preview-title">Preview</div>
          <div class="bm-stats">
            <span class="bm-stat ok" id="statOk">0 valid</span>
            <span class="bm-stat err" id="statErr">0 errors</span>
            <span class="bm-stat warn" id="statWarn" style="display:none;">0 possible duplicates</span>
          </div>
        </div>
        <div class="bm-table-wrap">
          <table class="bm-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Status</th>
                <th>Title of Work</th>
                <th>Type</th>
                <th>Record Status</th>
                <th colspan="2">Authors &amp; Gender</th>
                <th>Campus</th>
                <th>Creation / Filing Date</th>
                <th>Date Registered</th>
                <th>Issue / Note</th>
              </tr>
            </thead>
            <tbody id="batchTableBody">
            </tbody>
          </table>
        </div>
      </div>

    </div>{{-- end bm-body --}}

    {{-- Footer --}}
    <div class="bm-footer">
      <div class="bm-footer-note" id="batchFooterNote">Select a CSV file to preview records before importing.</div>
      <div class="bm-footer-actions">
        <button type="button" class="bm-btn-cancel" id="removeAllDupsBtn" style="display:none;">Remove All Duplicates</button>
        <button type="button" class="bm-btn-cancel" id="batchCancel">Cancel</button>
        <button type="button" class="bm-btn-import" id="batchImportBtn" disabled>
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          Import Valid Rows
        </button>
      </div>
    </div>

  </div>
</div>

<script>
(function () {
  'use strict';

  const REQUIRED_COLS  = ['title_of_work', 'type', 'status'];
  const ALL_COLS       = ['record_id','registration_number','title_of_work','type','class_of_work','date_of_creation','date_of_filing','date_registered_deposited','campus','college','program','authors','hyperlink','remarks','status'];
  const VALID_TYPES    = ['Patent','Utility Model','Industrial Design','Trademark','Copyright'];
  const VALID_STATUSES = ['Registered','Filed','Unregistered','Close to Expiry'];

  const backdrop      = document.getElementById('batchBackdrop');
  const openBtn       = document.getElementById('batchUploadBtn');
  const closeBtn      = document.getElementById('batchClose');
  const cancelBtn     = document.getElementById('batchCancel');
  const dropzone      = document.getElementById('batchDropzone');
  const fileInput     = document.getElementById('batchFileInput');
  const fileChosen    = document.getElementById('batchFileChosen');
  const fileName      = document.getElementById('batchFileName');
  const fileClear     = document.getElementById('batchFileClear');
  const preview       = document.getElementById('batchPreview');
  const tableBody     = document.getElementById('batchTableBody');
  const statOk        = document.getElementById('statOk');
  const statErr       = document.getElementById('statErr');
  const importBtn     = document.getElementById('batchImportBtn');
  const footerNote    = document.getElementById('batchFooterNote');
  const templateBtn   = document.getElementById('downloadTemplateBtn');

  let parsedRows = [];

  // ── Open / Close ──
  openBtn.addEventListener('click', () => backdrop.classList.add('open'));
  [closeBtn, cancelBtn].forEach(b => b.addEventListener('click', closeModal));
  backdrop.addEventListener('click', e => { if (e.target === backdrop) closeModal(); });

  function closeModal() {
    backdrop.classList.remove('open');
    resetModal();
  }

  function resetModal() {
    fileInput.value = '';
    fileName.textContent = '—';
    fileChosen.classList.remove('show');
    preview.classList.remove('show');
    tableBody.innerHTML = '';
    statOk.textContent  = '0 valid';
    statErr.textContent = '0 errors';
    importBtn.disabled  = true;
    footerNote.textContent = 'Select a CSV file to preview records before importing.';
    parsedRows = [];
  }

  // ── Drag & Drop ──
  dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
  dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
  dropzone.addEventListener('drop', e => {
    e.preventDefault(); dropzone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) handleFile(file);
  });
  fileInput.addEventListener('change', () => {
    if (fileInput.files[0]) handleFile(fileInput.files[0]);
  });

  // ── Clear file ──
  fileClear.addEventListener('click', (e) => {
    e.stopPropagation();
    resetModal();
  });

  // ── Parse CSV ──
  function handleFile(file) {
    if (!file.name.endsWith('.csv')) {
      alert('Please upload a .csv file only.');
      return;
    }
    if (file.size > 5 * 1024 * 1024) {
      alert('File is too large. Maximum size is 5 MB.');
      return;
    }
    fileName.textContent = file.name;
    fileChosen.classList.add('show');

    const reader = new FileReader();
    reader.onload = e => parseCSV(e.target.result);
    reader.readAsText(file);
  }

  function parseCSV(text) {
    const lines = text.trim().split(/\r?\n/);
    if (lines.length < 2) {
      footerNote.innerHTML = '<span>CSV has no data rows.</span>';
      return;
    }

    // Normalise headers: lowercase, collapse spaces/special chars → underscore
    // Then map pgAdmin-style names to internal keys used throughout the JS/backend
    const COL_MAP = {
      'record_id':                  'record_id',
      'registration_number':        'registration_number',
      'registration_no':            'registration_number',
      'title_of_work':              'title_of_work',
      'title':                      'title_of_work',
      'type':                       'type',
      'class_of_work':              'class_of_work',
      'date_of_creation':           'date_of_creation',
      'date_created':               'date_of_creation',
      'date_creation':              'date_of_creation',
      // Option A merged smart column — any variation of the long label maps to date_of_creation
      // The backend then routes it to date_of_filing for Copyright, date_creation for others
      'date_of_creation_patent_um_design_filing_date_copyright_creation_date': 'date_smart',
      'date_of_creation_filing_date': 'date_smart',
      'date_creation_filing_date':    'date_smart',
      'date_of_filing':             'date_of_filing',
      'date_filing':                'date_of_filing',
      'date_registered_deposited':  'date_registered_deposited',
      'date_registereddeposited':   'date_registered_deposited',
      'date_registered':            'date_registered_deposited',
      'campus':                     'campus',
      'college':                    'college',
      'program':                    'program',
      'authors':                    'authors',
      'inventor_name':              'authors',
      'hyperlink':                  'hyperlink',
      'gdrive_link':                'hyperlink',
      'remarks':                    'remarks',
      'status':                     'status',
    };

    const rawHeaders = splitCSVLine(lines[0]).map(h => h.trim().toLowerCase().replace(/\s+/g,'_').replace(/[^a-z0-9_]/g,''));
    const headers    = rawHeaders.map(h => COL_MAP[h] || h);

    const missing = REQUIRED_COLS.filter(c => !headers.includes(c));
    if (missing.length) {
      footerNote.innerHTML = `<span>Missing required columns: ${missing.join(', ')}. Check your header row matches the template.</span>`;
      return;
    }

    parsedRows = [];
    for (let i = 1; i < lines.length; i++) {
      if (!lines[i].trim()) continue;
      const cells = splitCSVLine(lines[i]);
      const row   = {};
      headers.forEach((h, idx) => { row[h] = (cells[idx] || '').trim(); });
      row._rowNum   = i;
      row._errors   = validateRow(row);
      row._warnings = [];
      // Parse authors string into [{name, gender}] — split on ; or semicolons
      const rawAuthors = (row.authors || '').split(/\s*;\s*|\s*,\s*(?=[A-Z])/).map(n => n.trim()).filter(Boolean);
      row.authors_list = rawAuthors.map(name => ({ name, gender: '' }));
      parsedRows.push(row);
    }

    // Collect all unique author names and titles across all rows
    const allNames = [...new Set(
      parsedRows.flatMap(r => (r.authors_list || []).map(a => a.name))
    )].filter(Boolean);

    const allTitles = [...new Set(
      parsedRows.map(r => (r.title_of_work || '').trim()).filter(Boolean)
    )];

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const fetchHeaders = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrf,
    };

    // Run gender lookup and duplicate title check in parallel
    const genderPromise = allNames.length
      ? fetch('/api/contributor-genders', { method: 'POST', credentials: 'same-origin', headers: fetchHeaders, body: JSON.stringify({ names: allNames }) })
          .then(r => r.json()).catch(() => ({ genders: {} }))
      : Promise.resolve({ genders: {} });

    const dupPromise = allTitles.length
      ? fetch('/api/check-titles-bulk', { method: 'POST', credentials: 'same-origin', headers: fetchHeaders, body: JSON.stringify({ titles: allTitles }) })
          .then(r => r.json()).catch(() => ({ duplicates: {} }))
      : Promise.resolve({ duplicates: {} });

    Promise.all([genderPromise, dupPromise]).then(([genderData, dupData]) => {
      // ── Gender prefill ──
      const rawGenders   = genderData.genders || {};
      const knownGenders = {};
      Object.keys(rawGenders).forEach(k => {
        knownGenders[k.trim().toLowerCase()] = rawGenders[k];
      });
      parsedRows.forEach(row => {
        (row.authors_list || []).forEach(author => {
          const key = (author.name || '').trim().toLowerCase();
          if (!author.gender && knownGenders[key]) {
            author.gender    = knownGenders[key];
            author.prefilled = true;
          }
        });
      });

      // ── Duplicate title detection ──
      const rawDups = dupData.duplicates || {};
      // Build lowercase-keyed map
      const knownDups = {};
      Object.keys(rawDups).forEach(k => {
        knownDups[k.trim().toLowerCase()] = rawDups[k];
      });
      parsedRows.forEach(row => {
        const titleKey = (row.title_of_work || '').trim().toLowerCase();
        if (titleKey && knownDups[titleKey]) {
          const matches = knownDups[titleKey];
          const ids = matches.map(m => m.record_id).join(', ');
          row._warnings = [`Possible duplicate of existing record(s): ${ids}`];
        }
      });
    }).finally(() => renderPreview());
  }

  function splitCSVLine(line) {
    const result = []; let cur = ''; let inQ = false;
    for (let i = 0; i < line.length; i++) {
      const ch = line[i];
      if (ch === '"') { inQ = !inQ; continue; }
      if (ch === ',' && !inQ) { result.push(cur); cur = ''; continue; }
      cur += ch;
    }
    result.push(cur);
    return result;
  }

  function validateRow(row) {
    const errs = [];
    const type = (row.type || '').trim();
    const isCopyright = type.toLowerCase() === 'copyright';

    if (!row.title_of_work)  errs.push('Title of Work required');
    if (!type)               errs.push('Type required');
    else if (!VALID_TYPES.map(t => t.toLowerCase()).includes(type.toLowerCase()))
      errs.push(`Unknown type "${type}"`);
    if (!row.status)         errs.push('Status required');
    else if (!VALID_STATUSES.map(s => s.toLowerCase()).includes(row.status.toLowerCase()))
      errs.push(`Unknown status "${row.status}"`);

    // date_smart is the merged Option A column — resolve it here for preview
    const smartDate = row.date_smart || '';
    if (smartDate && isNaN(Date.parse(smartDate)))
      errs.push('Invalid date in DATE OF CREATION / FILING DATE column');

    if (row.date_registered_deposited && isNaN(Date.parse(row.date_registered_deposited)))
      errs.push('Invalid DATE REGISTERED/DEPOSITED');

    return errs;
  }

  function renderPreview() {
    const okRows  = parsedRows.filter(r => r._errors.length === 0);
    const errRows = parsedRows.filter(r => r._errors.length > 0);

    statOk.textContent  = `${okRows.length} valid`;
    statErr.textContent = `${errRows.length} errors`;

    tableBody.innerHTML = parsedRows.map((row, parsedRowIdx) => {
      const isErr     = row._errors.length > 0;
      const isUpdated = !isErr && !!row._updated;
      const isWarn    = !isErr && !isUpdated && row._warnings && row._warnings.length > 0;
      const badge  = isErr
        ? `<span class="row-badge err">Error</span>`
        : isUpdated
          ? `<span class="row-badge updated">Updated</span>`
          : isWarn
            ? `<span class="row-badge warn">Possible Duplicate</span>`
            : `<span class="row-badge ok">Valid</span>`;
      const issue  = isErr
        ? `<td class="cell-error">${row._errors.join(' · ')}</td>`
        : isUpdated
          ? `<td style="color:#16a34a;font-size:0.65rem;">✓ Dismissed / reviewed</td>`
          : isWarn
            ? `<td class="cell-warn">⚠ ${row._warnings.join(' · ')}</td>`
            : `<td style="color:var(--muted);font-size:0.65rem;">—</td>`;

      const rowIdx = parsedRowIdx; // actual array index, not CSV line number

      // Build per-author gender rows
      const authorRows = (row.authors_list || []).map((a, ai) => {
        const prefillBadge = a.prefilled
          ? `<span class="bm-prefill-badge" title="Gender pre-filled from existing records">✓ DB</span>`
          : '';
        return `
        <div class="bm-author-row">
          <span class="bm-author-name" title="${esc(a.name)}">${esc(a.name)}</span>
          ${prefillBadge}
          <select class="bm-gender-select${a.prefilled ? ' bm-gender-prefilled' : ''}" data-rowidx="${rowIdx}" data-authoridx="${ai}" onchange="setBatchGender(this)">
            <option value=""       ${!a.gender              ? 'selected' : ''}>—</option>
            <option value="Male"   ${a.gender === 'Male'   ? 'selected' : ''}>Male</option>
            <option value="Female" ${a.gender === 'Female' ? 'selected' : ''}>Female</option>
            <option value="Other"  ${a.gender === 'Other'  ? 'selected' : ''}>Other</option>
          </select>
        </div>`;
      }).join('');

      const removeBtn  = isWarn
        ? `<button type="button" class="bm-remove-row-btn"  onclick="removeParsedRow(${rowIdx})">✕ Remove</button>`
        : '';
      const compareBtn = isWarn
        ? `<button type="button" class="bm-compare-btn"     onclick="openCompareModal(${rowIdx})">⇄ Compare</button>`
        : '';
      const dismissBtn = isWarn
        ? `<button type="button" class="bm-dismiss-btn"     onclick="dismissDuplicate(${rowIdx})">✓ Dismiss</button>`
        : '';

      return `<tr class="${isErr ? 'row-error' : isWarn ? 'row-warn' : isUpdated ? 'row-updated' : ''}" data-parsedidx="${rowIdx}">
        <td style="color:var(--muted);font-size:0.65rem;">${row._rowNum}</td>
        <td>${badge}${(removeBtn||compareBtn||dismissBtn) ? `<div style="margin-top:4px;display:flex;flex-direction:column;gap:3px;">${compareBtn}${dismissBtn}${removeBtn}</div>` : ''}</td>
        <td title="${esc(row.title_of_work)}">${esc(row.title_of_work) || '<span style="color:#94A3B8">—</span>'}</td>
        <td>${esc(row.type) || '<span style="color:#94A3B8">—</span>'}</td>
        <td>${esc(row.status) || '<span style="color:#94A3B8">—</span>'}</td>
        <td colspan="2" style="padding:6px 12px;">${authorRows || '<span style="color:#94A3B8">—</span>'}</td>
        <td>${esc(row.campus) || '<span style="color:#94A3B8">—</span>'}</td>
        <td>${esc(row.date_smart || row.date_of_creation) || '<span style="color:#94A3B8">—</span>'}</td>
        <td>${esc(row.date_registered_deposited) || '<span style="color:#94A3B8">—</span>'}</td>
        ${issue}
      </tr>`;
    }).join('');

    const warnRows = parsedRows.filter(r => r._errors.length === 0 && r._warnings && r._warnings.length > 0);
    const cleanRows = parsedRows.filter(r => r._errors.length === 0 && (!r._warnings || r._warnings.length === 0));

    const statWarn       = document.getElementById('statWarn');
    const removeAllBtn   = document.getElementById('removeAllDupsBtn');

    if (statWarn) {
      if (warnRows.length > 0) {
        statWarn.textContent = `${warnRows.length} possible duplicate${warnRows.length > 1 ? 's' : ''}`;
        statWarn.style.display = '';
      } else {
        statWarn.style.display = 'none';
      }
    }
    if (removeAllBtn) {
      removeAllBtn.style.display = warnRows.length > 0 ? '' : 'none';
    }

    preview.classList.add('show');
    importBtn.disabled = cleanRows.length === 0 && warnRows.length === 0;

    if (errRows.length > 0 && okRows.length > 0) {
      footerNote.innerHTML = `<span>${errRows.length} row(s) with errors will be skipped.</span>`;
    } else if (errRows.length > 0 && okRows.length === 0) {
      footerNote.innerHTML = `<span>All rows have errors — fix the CSV and re-upload.</span>`;
    } else if (warnRows.length > 0) {
      footerNote.innerHTML = `<span style="color:#92400e;">${warnRows.length} possible duplicate(s) — remove them or they will be skipped on import.</span>`;
    } else {
      footerNote.textContent = `All ${okRows.length} row(s) are valid and ready to import.`;
    }
  }

  function esc(str) {
    if (!str) return '';
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  // ── Compare modal state ──
  let compareRowIdx    = null;
  let compareRecordId  = null;
  let compareOriginal  = null; // snapshot of DB record at open time

  window.openCompareModal = async function(idx) {
    const row = parsedRows[idx];
    if (!row) return;
    compareRowIdx = idx;

    // The warning contains the record IDs — grab the first one
    const warnText = (row._warnings || [''])[0];
    const idMatch  = warnText.match(/KTTM-[\w-]+/);
    if (!idMatch) return;
    compareRecordId = idMatch[0];

    document.getElementById('compareModalTitle').textContent = `Compare: "${(row.title_of_work || '').slice(0, 50)}"`;
    document.getElementById('compareRecordId').textContent   = compareRecordId;

    // Fetch existing record from API using exact record_id match
    let existing = null;
    compareOriginal = null;
    try {
      const res = await fetch(`/api/records?record_id=${encodeURIComponent(compareRecordId)}&per_page=1`, { credentials: 'same-origin' });
      const data = await res.json();
      existing = (data.data || data)[0] || null;
      compareOriginal = existing ? JSON.parse(JSON.stringify(existing)) : null;
    } catch(e) { existing = null; }

    const fields = [
      { label: 'Title',            csvKey: 'title_of_work',             dbKey: 'ip_title',                   inputName: 'title',               type: 'text' },
      { label: 'Category',         csvKey: 'type',                      dbKey: 'category',                   inputName: 'type',                type: 'text' },
      { label: 'Status',           csvKey: 'status',                    dbKey: 'status',                     inputName: 'status',              type: 'text' },
      { label: 'Campus',           csvKey: 'campus',                    dbKey: 'campus',                     inputName: 'campus',              type: 'text' },
      { label: 'Registration No.', csvKey: 'registration_number',       dbKey: 'registration_number',        inputName: 'registration_number', type: 'text' },
      { label: 'Date Registered',  csvKey: 'date_registered_deposited', dbKey: 'date_registered_deposited',  inputName: 'registered',          type: 'date' },
      { label: 'Date of Creation', csvKey: 'date_of_creation',          dbKey: 'date_creation',              inputName: 'date_creation',       type: 'date' },
      { label: 'GDrive Link',      csvKey: 'hyperlink',                 dbKey: 'gdrive_link',                inputName: 'gdrive_link',         type: 'text' },
      { label: 'Remarks',          csvKey: 'remarks',                   dbKey: 'remarks',                    inputName: 'remarks',             type: 'text' },
    ];

    const fmt = v => (v && v !== 'null' && v !== 'undefined') ? String(v).trim() : '—';
    const toDateInput = v => {
      if (!v || v === '—') return '';
      const s = String(v).trim();
      // If already YYYY-MM-DD return as-is — no Date object, no timezone shift
      if (/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test(s)) return s;
      // Try to extract YYYY-MM-DD from a longer string
      const iso = /([0-9]{4}-[0-9]{2}-[0-9]{2})/.exec(s);
      if (iso) return iso[1];
      // Last resort: parse with Date but use local parts
      const d = new Date(s);
      if (isNaN(d)) return '';
      const y = d.getFullYear();
      const m = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      return `${y}-${m}-${day}`;
    };
    const fmtDate = v => {
      if (!v || v === '—') return '—';
      // Parse YYYY-MM-DD directly to avoid UTC midnight timezone shift
      const iso = /^\d{4}-\d{2}-\d{2}/.exec(String(v).trim());
      if (iso) {
        const [y, m, d] = iso[0].split('-').map(Number);
        return new Date(y, m - 1, d).toLocaleDateString('en-US', {year:'numeric', month:'short', day:'numeric'});
      }
      const d = new Date(v);
      return isNaN(d) ? v : new Date(d.getFullYear(), d.getMonth(), d.getDate())
        .toLocaleDateString('en-US', {year:'numeric', month:'short', day:'numeric'});
    };

    let rowsHtml = '';
    fields.forEach((f, i) => {
      const csvRaw = row[f.csvKey];
      const dbRaw  = existing ? existing[f.dbKey] : null;
      const csvVal = f.type === 'date' ? fmtDate(csvRaw) : fmt(csvRaw);
      const dbVal  = f.type === 'date' ? fmtDate(dbRaw)  : fmt(dbRaw);
      const isDiff = csvVal !== dbVal && csvVal !== '—' && dbVal !== '—';

      const inputVal = f.type === 'date' ? toDateInput(dbRaw) : ((dbRaw||'').toString().replace(/"/g,'&quot;'));
      const inputEl  = f.type === 'date'
        ? `<input class="cmp-input${isDiff ? ' diff' : ''}" type="date" name="${f.inputName}" value="${inputVal}">`
        : `<input class="cmp-input${isDiff ? ' diff' : ''}" type="text" name="${f.inputName}" value="${inputVal}">`;

      const rowBg = i % 2 === 0 ? '#fafafa' : '#fff';
      const canCopy = csvVal !== '—';
      const rawForCopy = f.type === 'date' ? toDateInput(csvRaw) : String(csvRaw || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;');
      const copyBtn = canCopy
        ? `<button type="button" class="cmp-copy-btn" title="Copy CSV value to existing record"
             data-copyval="${rawForCopy}" data-copytype="${f.type}"
             onclick="cmpCopyRow(this)">→</button>`
        : `<span class="cmp-copy-btn cmp-copy-btn--disabled">—</span>`;

      rowsHtml += `
        <div class="cmp-row" style="display:grid;grid-template-columns:130px 1fr 36px 1fr;gap:0;border-bottom:1px solid var(--line);background:${isDiff ? '#fffbeb' : rowBg};">
          <div class="cmp-row-label">${f.label}</div>
          <div class="cmp-row-csv${isDiff ? ' diff' : ''}">${csvVal}</div>
          <div class="cmp-row-arrow">${copyBtn}</div>
          <div class="cmp-row-edit">${inputEl}</div>
        </div>`;
    });

    document.getElementById('compareRows').innerHTML = rowsHtml;
    document.getElementById('compareModal').classList.add('open');
  };

  window.cmpCopyRow = function(btn) {
    const inp = btn.closest('.cmp-row').querySelector('.cmp-input');
    if (!inp) return;
    inp.value = btn.dataset.copyval || '';
    inp.classList.add('cmp-input--copied');
    btn.textContent = '✓';
    btn.classList.add('cmp-copy-btn--done');
    setTimeout(() => {
      btn.textContent = '→';
      btn.classList.remove('cmp-copy-btn--done');
    }, 1500);
  };

  document.getElementById('copyAllCsvBtn')?.addEventListener('click', () => {
    document.getElementById('compareRows').querySelectorAll('.cmp-copy-btn:not(.cmp-copy-btn--disabled)').forEach(btn => btn.click());
  });

  document.getElementById('closeCompareBtn')?.addEventListener('click',  () => document.getElementById('compareModal').classList.remove('open'));
  document.getElementById('cancelCompareBtn')?.addEventListener('click', () => document.getElementById('compareModal').classList.remove('open'));
  document.getElementById('compareModal')?.addEventListener('click', e => { if (e.target.id === 'compareModal') document.getElementById('compareModal').classList.remove('open'); });

  document.getElementById('saveCompareBtn')?.addEventListener('click', async () => {
    if (!compareRecordId) return;
    const inputs = document.querySelectorAll('#compareRows .cmp-input');
    const payload = {};
    inputs.forEach(inp => { if (inp.name) payload[inp.name] = inp.value; });

    const saveBtn = document.getElementById('saveCompareBtn');
    saveBtn.disabled = true; saveBtn.textContent = 'Saving…';

    // Map input names to DB field keys so we can detect real changes
    const inputToDb = {
      title: 'ip_title', type: 'category', status: 'status', campus: 'campus',
      registration_number: 'registration_number', registered: 'date_registered_deposited',
      date_creation: 'date_creation', gdrive_link: 'gdrive_link', remarks: 'remarks',
    };
    // Check if any submitted value actually differs from the original DB snapshot
    const hasRealChanges = !compareOriginal || Object.entries(payload).some(([key, val]) => {
      const dbKey = inputToDb[key];
      if (!dbKey) return false;
      const orig = (compareOriginal[dbKey] || '').toString().trim();
      const submitted = (val || '').toString().trim();
      // For dates, normalise to YYYY-MM-DD for comparison
      const normDate = s => { const m = /([0-9]{4}-[0-9]{2}-[0-9]{2})/.exec(s); return m ? m[1] : s; };
      return normDate(orig) !== normDate(submitted);
    });

    try {
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const res  = await fetch(`/records/${encodeURIComponent(compareRecordId)}/update`, {
        method: 'POST', credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify(payload),
      });
      const data = await res.json();
      if (data.success) {
        document.getElementById('compareModal').classList.remove('open');
        const targetRow = compareRowIdx !== null && parsedRows[compareRowIdx]
          ? parsedRows[compareRowIdx]
          : parsedRows.find(r => (r._warnings||[]).some(w => w.includes(compareRecordId)));
        if (targetRow) {
          targetRow._warnings = [];
          if (hasRealChanges) {
            targetRow._updated = true;
            showToast('Record updated successfully.', 'success');
          } else {
            // No real changes — just dismiss the duplicate flag silently
            showToast('No changes made — duplicate flag cleared.', 'success');
          }
        }
        renderPreview();
      } else {
        showToast(data.message || 'Update failed.', 'error');
      }
    } catch(e) {
      showToast('Network error — could not update record.', 'error');
    } finally {
      saveBtn.disabled = false;
      saveBtn.innerHTML = '<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Update Existing Record';
    }
  });

  // ── Dismiss duplicate flag without removing the row ──
  window.dismissDuplicate = function(idx) {
    if (parsedRows[idx]) {
      parsedRows[idx]._warnings = [];
      parsedRows[idx]._updated  = true;
      renderPreview();
    }
  };

  // ── Remove a single row from parsedRows by array index ──
  window.removeParsedRow = function(idx) {
    parsedRows.splice(idx, 1);
    renderPreview();
  };

  // ── Remove all duplicate-warned rows ──
  document.getElementById('removeAllDupsBtn')?.addEventListener('click', () => {
    parsedRows = parsedRows.filter(r => !(r._warnings && r._warnings.length > 0));
    renderPreview();
  });

  // ── Per-author gender setter ──
  window.setBatchGender = function(select) {
    const rowIdx    = parseInt(select.getAttribute('data-rowidx'),    10);
    const authorIdx = parseInt(select.getAttribute('data-authoridx'), 10);
    if (!isNaN(rowIdx) && !isNaN(authorIdx) && parsedRows[rowIdx] && parsedRows[rowIdx].authors_list[authorIdx]) {
      parsedRows[rowIdx].authors_list[authorIdx].gender = select.value;
    }
  };

  // ── Download template ──
  templateBtn.addEventListener('click', () => {
    // Option A — single smart date column with inline note so office workers know what to enter
    const header   = 'REGISTRATION NUMBER,TITLE OF WORK,TYPE,CLASS OF WORK,DATE OF CREATION,DATE OF FILING,DATE REGISTERED DEPOSITED,CAMPUS,COLLEGE,PROGRAM,AUTHORS,HYPERLINK,REMARKS,Status';
    const example  = 'REG-2024-001,Smart Sensor-Based Pediatric Screening Kiosk,Patent,,2024-01-15,,2024-03-20,Main Campus,College of Engineering,Computer Engineering,Juan Dela Cruz; Alvin Santos,https://drive.google.com/...,Sample remark,Registered';
    const example2 = '4-2024-000123,The Chronicles of KTTM,Copyright,Literary,2023-06-01,2023-06-01,2024-01-10,South Campus,College of Arts,Communication,Maria Santos; Irish Ann Ba,https://drive.google.com/...,Published work,Filed';
    const example3 = 'UM-2024-005,Foldable Water Filtration Device,Utility Model,,2023-11-20,,2024-02-14,East Campus,College of Science,Environmental Science,Pedro Reyes; Mark N,https://drive.google.com/...,Utility model filed,Registered';
    const blob = new Blob([header + '\n' + example + '\n' + example2 + '\n' + example3], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url; a.download = 'kttm_batch_template.csv';
    a.click(); URL.revokeObjectURL(url);
  });

  // ── Import (wired up — backend route to be added to web.php) ──
  importBtn.addEventListener('click', () => {
    const validRows = parsedRows.filter(r => r._errors.length === 0);
    if (!validRows.length) return;

    importBtn.disabled = true;
    importBtn.innerHTML = `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" opacity=".25"/><path d="M21 12A9 9 0 0112 3"/></svg> Importing…`;

    fetch('{{ route("records.batch-import") }}', {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({ rows: validRows }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        closeModal();
        showImportSuccess({
          inserted: data.inserted  || 0,
          skipped:  data.skipped   || 0,
          failed:   data.failed?.length || 0,
        });
        setTimeout(() => window.location.reload(), 4200);
      } else {
        showToast('Import failed: ' + (data.message || 'Unknown error'), 'error', 6000);
        importBtn.disabled = false;
        importBtn.innerHTML = `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Import Valid Rows`;
      }
    })
    .catch(() => {
      showToast('Network error — please try again.', 'error', 5000);
      importBtn.disabled = false;
      importBtn.innerHTML = `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Import Valid Rows`;
    });
  });

  // ── Batch import success overlay ──
  function showImportSuccess({ inserted, skipped, failed }) {
    const overlay = document.createElement('div');
    overlay.id = 'importSuccessOverlay';
    overlay.innerHTML = `
      <div class="is-card">
        <div class="is-icon-wrap">
          <svg width="38" height="38" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
        </div>
        <div class="is-title">Batch Import Complete</div>
        <div class="is-subtitle">Records have been added to the system.</div>
        <div class="is-stats">
          <div class="is-stat is-stat-ok">
            <span class="is-stat-num">${inserted}</span>
            <span class="is-stat-label">Imported</span>
          </div>
          ${skipped > 0 ? `
          <div class="is-stat is-stat-warn">
            <span class="is-stat-num">${skipped}</span>
            <span class="is-stat-label">Skipped</span>
          </div>` : ''}
          ${failed > 0 ? `
          <div class="is-stat is-stat-err">
            <span class="is-stat-num">${failed}</span>
            <span class="is-stat-label">Failed</span>
          </div>` : ''}
        </div>
        <div class="is-note">Page refreshing in a moment… Click anywhere to refresh now.</div>
        <div class="is-bar-wrap"><div class="is-bar" id="isProgressBar"></div></div>
      </div>`;
    document.body.appendChild(overlay);
    requestAnimationFrame(() => {
      const bar = document.getElementById('isProgressBar');
      if (bar) { bar.style.transition = 'width 4s linear'; bar.style.width = '100%'; }
    });
    overlay.addEventListener('click', () => { overlay.remove(); window.location.reload(); });
  }

})();
</script>
<style>
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── Batch import success overlay ── */
  #importSuccessOverlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(10,10,20,0.72); backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    animation: fadeInOverlay .35s ease;
    cursor: pointer;
  }
  @keyframes fadeInOverlay { from { opacity:0; } to { opacity:1; } }

  .is-card {
    background: #fff; border-radius: 24px;
    padding: 40px 48px; text-align: center;
    min-width: 320px; max-width: 440px; width: 90%;
    box-shadow: 0 32px 80px rgba(0,0,0,.28);
    animation: popIn .4s cubic-bezier(.34,1.56,.64,1);
    cursor: default;
  }
  @keyframes popIn { from { opacity:0; transform:scale(.88); } to { opacity:1; transform:scale(1); } }

  .is-icon-wrap {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #16a34a, #15803d);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 8px 24px rgba(22,163,74,.35);
    animation: iconPop .5s .15s cubic-bezier(.34,1.56,.64,1) both;
  }
  @keyframes iconPop { from { transform:scale(0); opacity:0; } to { transform:scale(1); opacity:1; } }

  .is-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.35rem; font-weight: 800; color: #0f172a;
    margin-bottom: 6px;
  }
  .is-subtitle {
    font-size: 0.82rem; color: #64748b;
    margin-bottom: 28px;
  }
  .is-stats {
    display: flex; justify-content: center; gap: 16px;
    margin-bottom: 28px; flex-wrap: wrap;
  }
  .is-stat {
    display: flex; flex-direction: column; align-items: center;
    padding: 14px 22px; border-radius: 14px; min-width: 80px;
  }
  .is-stat-ok   { background: #f0fdf4; border: 1.5px solid #86efac; }
  .is-stat-warn { background: #fffbeb; border: 1.5px solid #fcd34d; }
  .is-stat-err  { background: #fef2f2; border: 1.5px solid #fca5a5; }
  .is-stat-num {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2rem; font-weight: 800; line-height: 1;
    margin-bottom: 4px;
  }
  .is-stat-ok   .is-stat-num  { color: #15803d; }
  .is-stat-warn .is-stat-num  { color: #92400e; }
  .is-stat-err  .is-stat-num  { color: #b91c1c; }
  .is-stat-label {
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em;
  }
  .is-stat-ok   .is-stat-label { color: #16a34a; }
  .is-stat-warn .is-stat-label { color: #d97706; }
  .is-stat-err  .is-stat-label { color: #ef4444; }

  .is-note {
    font-size: 0.72rem; color: #94a3b8;
    margin-bottom: 16px;
  }
  .is-bar-wrap {
    height: 4px; background: #e2e8f0; border-radius: 99px; overflow: hidden;
  }
  .is-bar {
    height: 100%; width: 0; border-radius: 99px;
    background: linear-gradient(90deg, #16a34a, #4ade80);
  }
</style>


</body>
</html>
