{{-- resources/views/dev_profile.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Developer Profile</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon:     #A52C30;
      --maroon2:    #7E1F23;
      --gold:       #F0C860;
      --gold2:      #E8B857;

      --bg:         #080C14;
      --bg2:        #0D1220;
      --bg3:        #111827;
      --card:       #0F1724;
      --card2:      #141E2E;
      --line:       rgba(255,255,255,0.06);
      --line2:      rgba(255,255,255,0.10);
      --ink:        #F1F5F9;
      --ink2:       #CBD5E1;
      --muted:      #64748B;
      --muted2:     #94A3B8;

      --blue:       #3B82F6;
      --blue2:      #2563EB;
      --blue-dim:   rgba(59,130,246,0.12);
      --blue-mid:   rgba(59,130,246,0.22);
      --green:      #10B981;
      --green-dim:  rgba(16,185,129,0.12);
      --red:        #EF4444;
      --red-dim:    rgba(239,68,68,0.12);
      --amber:      #F59E0B;
      --amber-dim:  rgba(245,158,11,0.12);
      --purple:     #8B5CF6;
      --purple-dim: rgba(139,92,246,0.12);

      --sidebar-w:  72px;
      --pad-x:      clamp(0.75rem, 2.5vw, 1.75rem);
      --shell-max:  1600px;
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

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

    /* ══════════════════════════════════════
       SIDEBAR
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
      background: var(--blue); border-radius: 0 3px 3px 0;
    }
    .nav-tooltip {
      position: absolute; left: calc(100% + 12px); top: 50%;
      transform: translateY(-50%);
      background: var(--bg3); border: 1px solid var(--line2);
      color: var(--ink2); font-size: 0.7rem; font-weight: 600;
      padding: 5px 10px; border-radius: 8px; white-space: nowrap;
      pointer-events: none; opacity: 0; transition: opacity .15s;
      letter-spacing: .04em; z-index: 999;
    }
    .nav-item:hover .nav-tooltip { opacity: 1; }
    .sidebar-nav { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; width: 100%; }
    .sidebar-bottom { display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .nav-divider { width: 32px; height: 1px; background: rgba(59,130,246,.15); margin: 8px 0; }

    .hamburger-btn {
      display: none;
      width: 40px; height: 40px; border-radius: 12px;
      background: var(--bg2); border: 1px solid var(--line2);
      align-items: center; justify-content: center;
      cursor: pointer; color: var(--muted2);
      transition: all .18s; flex-shrink: 0;
      -webkit-tap-highlight-color: transparent;
    }
    .hamburger-btn:hover { background: var(--blue-dim); border-color: var(--blue-mid); color: var(--blue); }
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
      min-height: 64px;
      background: var(--card);
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
    .topbar-titles { min-width: 0; flex: 1 1 180px; }
    .page-title  {
      font-size: clamp(0.88rem, 0.35vw + 0.8rem, 1rem);
      font-weight: 800; letter-spacing: -.2px; color: var(--ink);
      line-height: 1.2;
      overflow-wrap: anywhere;
    }
    .page-sub    {
      font-size: clamp(0.65rem, 0.12vw + 0.62rem, 0.7rem);
      color: var(--muted); font-weight: 500; margin-top: 1px;
      overflow-wrap: anywhere;
    }
    .topbar-right {
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 8px 10px;
      flex: 0 1 auto; min-width: 0; max-width: 100%;
      justify-content: flex-end;
    }

    .status-chip {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 5px 12px; border-radius: 20px;
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.55rem, 0.08vw + 0.52rem, 0.6rem);
      font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
      flex: 0 1 auto; max-width: 100%;
    }
    .chip-online { background: var(--green-dim); border: 1px solid rgba(16,185,129,.25); color: var(--green); }
    .chip-dev    { background: var(--blue-dim);  border: 1px solid rgba(59,130,246,.25);  color: var(--blue); }
    .chip-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    .live-clock {
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.66rem, 0.12vw + 0.62rem, 0.72rem);
      font-weight: 700; color: var(--muted2); letter-spacing: .06em;
      flex-shrink: 0;
    }

    /* ══════════════════════════════════════
       CONTENT
    ══════════════════════════════════════ */
    .content {
      padding: clamp(14px, 2.5vw, 20px) var(--pad-x);
      flex: 1;
      width: 100%;
      max-width: var(--shell-max);
      margin: 0 auto;
      box-sizing: border-box;
    }

    /* ══════════════════════════════════════
       PROFILE HERO
    ══════════════════════════════════════ */
    .profile-hero {
      background: linear-gradient(135deg, #0a1628 0%, #0f1f3d 50%, #0d1829 100%);
      border: 1px solid rgba(59,130,246,.15);
      border-radius: 18px;
      padding: clamp(18px, 3vw, 28px) clamp(18px, 3vw, 32px);
      position: relative; overflow: hidden;
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 20px 24px;
      margin-bottom: 20px;
      box-shadow: 0 8px 40px rgba(0,0,0,.4), inset 0 1px 0 rgba(59,130,246,.1);
    }
    .profile-hero::before {
      content: ''; position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(59,130,246,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59,130,246,.04) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .profile-hero::after {
      content: ''; position: absolute; top: -80px; right: -80px;
      width: 280px; height: 280px; border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,.1), transparent 70%);
      pointer-events: none;
    }

    /* Big avatar in hero */
    .hero-avatar-wrap {
      position: relative; flex-shrink: 0; z-index: 2;
    }
    .hero-av {
      width: 80px; height: 80px; border-radius: 20px;
      background: linear-gradient(135deg, var(--blue2), var(--blue));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 1.6rem; color: #fff;
      overflow: hidden; border: 2px solid rgba(59,130,246,.3);
      box-shadow: 0 8px 28px rgba(59,130,246,.35);
      cursor: pointer; transition: box-shadow .2s, border-color .2s;
    }
    .hero-av:hover { border-color: var(--blue); box-shadow: 0 8px 32px rgba(59,130,246,.5); }
    .hero-av img { width:80px; height:80px; object-fit:cover; border-radius:18px; display:block; }
    .av-edit-badge {
      position: absolute; bottom: -4px; right: -4px;
      width: 24px; height: 24px; border-radius: 8px;
      background: var(--blue); border: 2px solid var(--bg2);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background .18s;
      z-index: 3;
    }
    .av-edit-badge:hover { background: var(--blue2); }
    .av-edit-badge svg { display: block; }

    .hero-info { flex: 1 1 240px; min-width: 0; position: relative; z-index: 2; }
    .hero-eyebrow {
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.5rem, 0.1vw + 0.46rem, 0.55rem);
      letter-spacing: .18em; text-transform: uppercase;
      color: var(--blue); opacity: .8; margin-bottom: 4px;
      overflow-wrap: anywhere;
      line-height: 1.4;
    }
    .hero-name {
      font-size: clamp(1.15rem, 2.2vw + 0.65rem, 1.5rem);
      font-weight: 800; color: #fff;
      letter-spacing: -.3px; margin-bottom: 8px;
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 10px;
    }
    .hero-name > span:first-child { min-width: 0; overflow-wrap: anywhere; }
    .hero-name-edit {
      width: 26px; height: 26px; border-radius: 8px;
      background: rgba(59,130,246,.15); border: 1px solid rgba(59,130,246,.25);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: background .18s;
      flex-shrink: 0;
    }
    .hero-name-edit:hover { background: rgba(59,130,246,.28); }
    .hero-pills { display: flex; gap: 7px; flex-wrap: wrap; }
    .hero-pill {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 10px; border-radius: 20px;
      font-family: 'DM Mono', monospace; font-size: 0.58rem;
      font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    }
    .pill-dev    { background: var(--blue-mid);  border: 1px solid rgba(59,130,246,.3);  color: #93C5FD; }
    .pill-online { background: var(--green-dim); border: 1px solid rgba(16,185,129,.25); color: var(--green); }

    /* ══════════════════════════════════════
       SECTION CARDS
    ══════════════════════════════════════ */
    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }
    .sec {
      background: var(--card); border: 1px solid var(--line);
      border-radius: 18px; overflow: hidden;
      box-shadow: 0 2px 16px rgba(0,0,0,.15);
    }
    .sec.full { grid-column: 1 / -1; }
    .sec-head {
      padding: clamp(14px, 2vw, 16px) clamp(14px, 2.5vw, 20px); border-bottom: 1px solid var(--line);
      display: flex; align-items: center; flex-wrap: wrap;
      gap: 12px;
    }
    .sec-head > div:last-of-type { min-width: 0; flex: 1 1 160px; }
    .sec-ico {
      width: 34px; height: 34px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .sec-ico.blue   { background: var(--blue-dim);   color: var(--blue); }
    .sec-ico.green  { background: var(--green-dim);  color: var(--green); }
    .sec-ico.red    { background: var(--red-dim);    color: var(--red); }
    .sec-ico.amber  { background: var(--amber-dim);  color: var(--amber); }
    .sec-ico.purple { background: var(--purple-dim); color: var(--purple); }
    .sec-title {
      font-size: clamp(0.8rem, 0.2vw + 0.74rem, 0.85rem);
      font-weight: 800; color: var(--ink);
      overflow-wrap: anywhere;
    }
    .sec-sub   { font-size: 0.65rem; color: var(--muted); margin-top: 1px; overflow-wrap: anywhere; }
    .sec-body  { padding: clamp(16px, 2.5vw, 20px); }

    /* ══════════════════════════════════════
       FORM FIELDS
    ══════════════════════════════════════ */
    .form-field { margin-bottom: 14px; }
    .form-field:last-of-type { margin-bottom: 0; }
    .form-label {
      font-family: 'DM Mono', monospace; font-size: 0.58rem;
      letter-spacing: .12em; text-transform: uppercase;
      color: var(--muted2); font-weight: 600; display: block; margin-bottom: 6px;
    }
    .form-input {
      width: 100%; max-width: 100%; min-width: 0;
      padding: .72rem 1rem; border-radius: 11px;
      border: 1px solid var(--line2); background: var(--bg2);
      font-family: inherit;
      font-size: clamp(0.8rem, 0.15vw + 0.76rem, 0.84rem);
      color: var(--ink);
      outline: none; transition: border-color .2s, box-shadow .2s;
      box-sizing: border-box;
    }
    .form-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-dim); }
    .form-input::placeholder { color: var(--muted); }

    /* Password strength bar */
    .pw-strength-wrap { margin-top: 6px; }
    .pw-strength-bar {
      height: 3px; border-radius: 3px; background: var(--line2);
      overflow: hidden; margin-bottom: 4px;
    }
    .pw-strength-fill {
      height: 100%; border-radius: 3px; width: 0%;
      transition: width .3s, background .3s;
    }
    .pw-strength-label {
      font-family: 'DM Mono', monospace; font-size: 0.58rem;
      font-weight: 700; letter-spacing: .08em; color: var(--muted);
      transition: color .3s;
    }

    /* Submit buttons */
    .btn-submit {
      width: 100%; max-width: 100%; padding: 10px; border-radius: 11px; margin-top: 16px;
      background: linear-gradient(135deg, var(--blue2), var(--blue));
      border: none; font-family: inherit;
      font-size: clamp(0.76rem, 0.15vw + 0.72rem, 0.8rem);
      font-weight: 700;
      color: #fff; cursor: pointer; transition: all .18s;
      box-shadow: 0 6px 16px rgba(59,130,246,.3);
      display: flex; align-items: center; justify-content: center; gap: 8px;
      flex-wrap: wrap;
      box-sizing: border-box;
      text-align: center;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(59,130,246,.38); }
    .btn-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }
    .btn-submit.red-btn {
      background: linear-gradient(135deg, #b91c1c, var(--red));
      box-shadow: 0 6px 16px rgba(239,68,68,.25);
    }
    .btn-submit.red-btn:hover { box-shadow: 0 10px 22px rgba(239,68,68,.35); }

    /* Alert */
    .form-alert {
      display: none; align-items: center; gap: 8px;
      border-radius: 10px; padding: 9px 13px; margin-top: 12px;
      font-size: clamp(0.72rem, 0.12vw + 0.68rem, 0.76rem);
      font-weight: 600;
      max-width: 100%; overflow-wrap: anywhere;
    }
    .form-alert.error   { background: var(--red-dim);   border: 1px solid rgba(239,68,68,.2);   color: var(--red);   display: flex; }
    .form-alert.success { background: var(--green-dim); border: 1px solid rgba(16,185,129,.2);  color: var(--green); display: flex; }
    .form-alert.hidden  { display: none !important; }

    /* ══════════════════════════════════════
       SESSION INFO ROWS
    ══════════════════════════════════════ */
    .info-rows { display: flex; flex-direction: column; }
    .info-row {
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap;
      gap: 6px 12px;
      padding: 11px 0; border-bottom: 1px solid var(--line);
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 0.76rem; font-weight: 600; color: var(--ink2); flex: 1 1 auto; min-width: 0; overflow-wrap: anywhere; }
    .info-val {
      font-family: 'DM Mono', monospace;
      font-size: clamp(0.66rem, 0.12vw + 0.62rem, 0.72rem);
      font-weight: 700;
      text-align: right;
      flex: 0 1 auto;
      min-width: 0;
      max-width: 100%;
      overflow-wrap: anywhere;
      word-break: break-word;
    }
    .iv-blue   { color: var(--blue); }
    .iv-green  { color: var(--green); }
    .iv-amber  { color: var(--amber); }
    .iv-muted  { color: var(--muted2); }
    .iv-purple { color: var(--purple); }

    /* ══════════════════════════════════════
       AVATAR UPLOAD ZONE
    ══════════════════════════════════════ */
    .avatar-upload-zone {
      border: 2px dashed var(--line2); border-radius: 14px;
      padding: 24px; text-align: center;
      cursor: pointer; transition: border-color .2s, background .2s;
      position: relative;
    }
    .avatar-upload-zone:hover,
    .avatar-upload-zone.drag-over {
      border-color: var(--blue);
      background: var(--blue-dim);
    }
    .avatar-upload-zone input[type="file"] {
      position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 2;
    }
    .upload-preview {
      width: 64px; height: 64px; border-radius: 16px;
      margin: 0 auto 12px;
      background: linear-gradient(135deg, var(--blue2), var(--blue));
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 1.2rem; color: #fff;
      overflow: hidden; border: 2px solid rgba(59,130,246,.3);
    }
    .upload-preview img { width:64px; height:64px; object-fit:cover; border-radius:14px; display:block; }
    .upload-hint {
      font-size: clamp(0.72rem, 0.12vw + 0.68rem, 0.76rem);
      color: var(--muted2); font-weight: 600; margin-bottom: 4px;
      overflow-wrap: anywhere; padding: 0 4px;
    }
    .upload-sub {
      font-family: 'DM Mono', monospace; font-size: 0.6rem;
      color: var(--muted); letter-spacing: .06em;
    }
    .upload-progress {
      height: 3px; border-radius: 3px; background: var(--line2);
      margin-top: 14px; overflow: hidden; display: none;
    }
    .upload-progress-fill {
      height: 100%; border-radius: 3px;
      background: linear-gradient(90deg, var(--blue2), var(--blue));
      width: 0%; transition: width .3s;
      animation: progressPulse 1.2s ease-in-out infinite;
    }
    @keyframes progressPulse { 0%,100%{opacity:1} 50%{opacity:.55} }

    /* ══════════════════════════════════════
       TOAST
    ══════════════════════════════════════ */
    .toast {
      position: fixed; top: 76px; right: 20px; z-index: 9999;
      min-width: 0; max-width: calc(100vw - 2rem); width: min(360px, calc(100vw - 2rem));
      padding: 12px 16px; border-radius: 12px;
      font-weight: 700;
      font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
      display: flex; align-items: center; gap: 10px;
      animation: toastIn .3s ease-out; font-family: 'Plus Jakarta Sans', sans-serif;
      border: 1px solid;
      box-sizing: border-box;
    }
    .toast.success { background: var(--bg3); border-color: rgba(16,185,129,.3); color: var(--green);  }
    .toast.error   { background: var(--bg3); border-color: rgba(239,68,68,.3);  color: var(--red); }
    .toast.hiding  { animation: toastOut .3s ease-out forwards; }
    @keyframes toastIn  { from{transform:translateX(400px);opacity:0} to{transform:translateX(0);opacity:1} }
    @keyframes toastOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(400px)} }

    .spinner {
      width: 13px; height: 13px; border-radius: 50%;
      border: 2px solid rgba(255,255,255,.25); border-top-color: #fff;
      animation: spin .7s linear infinite; display: inline-block;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

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
    .logout-box {
      background: var(--card); border: 1px solid var(--line2);
      border-radius: 22px; padding: clamp(20px, 4vw, 28px);
      width: min(440px, calc(100vw - 2rem)); max-width: 100%;
      box-shadow: 0 32px 80px rgba(0,0,0,.5);
      animation: fadeUp .25s forwards;
      box-sizing: border-box;
    }
    .logout-modal-title { font-size: clamp(1rem, 0.35vw + 0.92rem, 1.1rem); font-weight: 800; color: var(--ink); margin-bottom: 5px; overflow-wrap: anywhere; }
    .logout-modal-desc { font-size: 0.78rem; color: var(--muted2); line-height: 1.6; margin-bottom: 20px; overflow-wrap: anywhere; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:none} }
    .logout-ico {
      width: 48px; height: 48px; border-radius: 14px;
      background: rgba(165,44,48,.15); color: var(--maroon);
      display: flex; align-items: center; justify-content: center; margin-bottom: 14px;
    }
    .logout-modal-actions { display: flex; flex-wrap: wrap; gap: 10px; align-items: stretch; }
    .logout-modal-actions .btn-cancel { flex: 1 1 120px; min-width: 0; display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box; }
    .logout-modal-actions form { flex: 1 1 140px; min-width: 0; display: flex; }
    .logout-modal-actions form .btn-logout { width: 100%; justify-content: center; display: inline-flex; align-items: center; }
    .btn-cancel {
      padding: 10px; border-radius: 11px;
      background: var(--bg2); border: 1px solid var(--line2);
      font-family: inherit;
      font-size: clamp(0.76rem, 0.15vw + 0.72rem, 0.8rem);
      font-weight: 700;
      color: var(--muted2); cursor: pointer; transition: all .18s;
    }
    .btn-cancel:hover { border-color: var(--red); color: var(--red); }
    .btn-logout {
      padding: 10px; border-radius: 11px;
      background: linear-gradient(135deg, var(--maroon2), var(--maroon));
      border: none; font-family: inherit;
      font-size: clamp(0.76rem, 0.15vw + 0.72rem, 0.8rem);
      font-weight: 700;
      color: #fff; cursor: pointer; box-shadow: 0 6px 16px rgba(165,44,48,.28);
      transition: all .18s;
      box-sizing: border-box;
    }
    .btn-logout:hover { transform: translateY(-1px); box-shadow: 0 10px 22px rgba(165,44,48,.38); }

    .fade-up { opacity:0; transform:translateY(14px); animation: fadeUp .5s forwards; }
    .d1{animation-delay:.08s} .d2{animation-delay:.16s} .d3{animation-delay:.24s} .d4{animation-delay:.32s}

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
      .nav-divider { width: 100%; max-width: 100%; margin-left: 0; margin-right: 0; }
      .main-wrap { margin-left: 0; }
      .hamburger-btn { display: flex; }
      .topbar { min-height: 58px; }
      .page-sub { display: none; }
      .profile-grid { grid-template-columns: 1fr; }
      .sec.full { grid-column: 1; }
      .profile-hero { align-items: flex-start; }
    }
    @media (max-width: 480px) {
      .logout-modal-actions { flex-direction: column; }
      .logout-modal-actions .btn-cancel,
      .logout-modal-actions form { flex: 0 0 auto; width: 100%; }
      .hero-pill { font-size: clamp(0.52rem, 0.08vw + 0.5rem, 0.58rem); }
    }
  </style>
</head>

<body>
@php
  $userName        = session('user_name',         'Developer');
  $userColor       = session('user_avatar_color', '#3B82F6');
  $userAvatarImage = session('user_avatar_image', null);
  $userEmail       = session('account_email',     '—');
  $sessionStart    = session('session_started_at','—');
  $userInitials    = collect(explode(' ', $userName))
    ->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');

  // Fetch fresh user data from DB for accurate display
  $dbUser = null;
  try {
    $dbUser = DB::table('users')->where('id', session('user_id'))->first();
  } catch(\Exception $e) {}

  $lastLogin = $dbUser && $dbUser->last_login_at
    ? \Carbon\Carbon::parse($dbUser->last_login_at)->format('M d, Y · h:i A')
    : 'No record';
@endphp

<div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

{{-- ══════════════ SIDEBAR ══════════════ --}}
<aside class="sidebar" id="mainSidebar" aria-label="Main navigation">

  {{-- User avatar --}}
  <div class="nav-item" style="margin-bottom:18px;width:42px;height:42px;border-radius:14px;
    {{ $userAvatarImage ? 'background:transparent;' : 'background:linear-gradient(135deg,#2563EB,#3B82F6);' }}
    font-weight:800;font-size:0.78rem;color:#fff;
    box-shadow:0 6px 18px rgba(59,130,246,.35);cursor:default;flex-shrink:0;overflow:hidden;padding:0;">
    @if($userAvatarImage)
      <img src="{{ asset('storage/avatars/' . $userAvatarImage) }}" alt="{{ $userInitials }}"
           style="width:42px;height:42px;object-fit:cover;border-radius:14px;display:block;" id="sidebarAvatar">
    @else
      <span id="sidebarAvatarInitials">{{ $userInitials }}</span>
    @endif
    <span class="nav-tooltip" style="min-width:140px;line-height:1.5;">
      {{ $userName }}<br>
      <span style="opacity:.6;font-weight:500;letter-spacing:.06em;text-transform:uppercase;font-size:.58rem;">Developer</span>
    </span>
  </div>

  <nav class="sidebar-nav">
    <a href="{{ url('/dev') }}" class="nav-item">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
      </svg>
      <span class="nav-tooltip">Dashboard</span>
    </a>
    <a href="{{ url('/dev/records') }}" class="nav-item">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      <span class="nav-tooltip">Records</span>
    </a>
    <div class="nav-divider"></div>
    <a href="{{ url('/dev') }}#logs" class="nav-item">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 3l7 3v6c0 5-3.5 8-7 9-3.5-1-7-4-7-9V6l7-3z"/>
        <path d="M9.5 12.5l1.7 1.7 3.3-3.7"/>
      </svg>
      <span class="nav-tooltip">Audit Logs</span>
    </a>
  </nav>

  <div class="sidebar-bottom">
    {{-- Profile — active on this page --}}
    <a href="{{ url('/dev/profile') }}" class="nav-item active">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
      </svg>
      <span class="nav-tooltip">Profile</span>
    </a>
    <button type="button" id="logoutBtn" class="nav-item" style="background:none;border:none;cursor:pointer;">
      <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
        <div class="page-title">My Profile</div>
        <div class="page-sub">Manage your developer account settings</div>
      </div>
    </div>
    <div class="topbar-right">
      <div class="live-clock" id="liveClock"></div>
      <span class="status-chip chip-online">
        <span class="chip-dot"></span> Online
      </span>
      <span class="status-chip chip-dev">
        <span class="chip-dot"></span> Dev Mode
      </span>
    </div>
  </header>

  {{-- CONTENT --}}
  <div class="content">

    {{-- ── PROFILE HERO ── --}}
    <div class="profile-hero fade-up">
      <div class="hero-avatar-wrap">
        <div class="hero-av" id="heroAvatar" onclick="document.getElementById('avatarFileInput').click()">
          @if($userAvatarImage)
            <img src="{{ asset('storage/avatars/' . $userAvatarImage) }}" alt="{{ $userInitials }}" id="heroAvatarImg">
          @else
            <span id="heroAvatarInitials">{{ $userInitials }}</span>
          @endif
        </div>
        <div class="av-edit-badge" onclick="document.getElementById('avatarFileInput').click()">
          <svg width="11" height="11" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 24 24">
            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
        </div>
      </div>

      <div class="hero-info">
        <div class="hero-eyebrow">KTTM · IP Records System · Developer Console</div>
        <div class="hero-name">
          <span id="heroDisplayName">{{ $userName }}</span>
          <div class="hero-name-edit" onclick="focusNameField()" title="Edit display name">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
          </div>
        </div>
        <div class="hero-pills">
          <span class="hero-pill pill-dev">
            <span style="width:4px;height:4px;border-radius:50%;background:currentColor;"></span>
            Developer
          </span>
          <span class="hero-pill pill-online">
            <span style="width:4px;height:4px;border-radius:50%;background:currentColor;"></span>
            Active Session
          </span>
        </div>
      </div>
    </div>

    {{-- ── HIDDEN FILE INPUT ── --}}
    <input type="file" id="avatarFileInput" accept="image/jpeg,image/png,image/webp" style="display:none;">

    {{-- ── MAIN GRID ── --}}
    <div class="profile-grid">

      {{-- ── SESSION INFO ── --}}
      <div class="sec fade-up d1">
        <div class="sec-head">
          <div class="sec-ico blue">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
          </div>
          <div>
            <div class="sec-title">Session Info</div>
            <div class="sec-sub">Current login details &amp; role</div>
          </div>
        </div>
        <div class="sec-body">
          <div class="info-rows">
            <div class="info-row">
              <span class="info-label">Role</span>
              <span class="info-val iv-blue">Developer</span>
            </div>
            <div class="info-row">
              <span class="info-label">Email</span>
              <span class="info-val iv-muted">{{ $userEmail }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Display Name</span>
              <span class="info-val iv-muted" id="infoDisplayName">{{ $userName }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Last Login</span>
              <span class="info-val iv-muted">{{ $lastLogin }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Session Started</span>
              <span class="info-val iv-muted">{{ $sessionStart }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">User ID</span>
              <span class="info-val iv-purple">#{{ session('user_id', '—') }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Status</span>
              <span class="info-val iv-green">Active</span>
            </div>
          </div>
        </div>
      </div>

      {{-- ── CHANGE DISPLAY NAME ── --}}
      <div class="sec fade-up d2" id="nameSection">
        <div class="sec-head">
          <div class="sec-ico purple">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
          <div>
            <div class="sec-title">Display Name</div>
            <div class="sec-sub">Update your visible name</div>
          </div>
        </div>
        <div class="sec-body">
          <div class="form-field">
            <label class="form-label">Current Name</label>
            <input class="form-input" type="text" value="{{ $userName }}" disabled
              style="color:var(--muted);cursor:not-allowed;">
          </div>
          <div class="form-field">
            <label class="form-label" for="newNameInput">New Display Name</label>
            <input class="form-input" type="text" id="newNameInput"
              placeholder="Enter new name" autocomplete="off">
          </div>
          <div class="form-alert hidden" id="nameAlert"></div>
          <button type="button" class="btn-submit" id="nameBtn" onclick="submitName()">
            <span id="nameBtnText">Update Name</span>
            <span id="nameBtnSpinner" style="display:none;"><span class="spinner"></span></span>
          </button>
        </div>
      </div>

      {{-- ── PROFILE PICTURE ── --}}
      <div class="sec fade-up d3">
        <div class="sec-head">
          <div class="sec-ico amber">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
          </div>
          <div>
            <div class="sec-title">Profile Picture</div>
            <div class="sec-sub">JPG, PNG or WEBP · Max 2MB</div>
          </div>
        </div>
        <div class="sec-body">
          <div class="avatar-upload-zone" id="uploadZone">
            <input type="file" id="avatarFileInput2" accept="image/jpeg,image/png,image/webp">
            <div class="upload-preview" id="uploadPreview">
              @if($userAvatarImage)
                <img src="{{ asset('storage/avatars/' . $userAvatarImage) }}" alt="{{ $userInitials }}" id="uploadPreviewImg">
              @else
                <span id="uploadPreviewInitials">{{ $userInitials }}</span>
              @endif
            </div>
            <div class="upload-hint">Click or drag &amp; drop to upload</div>
            <div class="upload-sub">JPG · PNG · WEBP · Max 2MB</div>
            <div class="upload-progress" id="uploadProgress">
              <div class="upload-progress-fill" id="uploadProgressFill"></div>
            </div>
          </div>
          <div class="form-alert hidden" id="avatarAlert"></div>
          <button type="button" class="btn-submit" id="avatarBtn" onclick="submitAvatar()" style="margin-top:14px;" disabled>
            <span id="avatarBtnText">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/></svg>
              Upload Picture
            </span>
            <span id="avatarBtnSpinner" style="display:none;"><span class="spinner"></span></span>
          </button>
        </div>
      </div>

      {{-- ── CHANGE PASSWORD ── --}}
      <div class="sec fade-up d4">
        <div class="sec-head">
          <div class="sec-ico red">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
          </div>
          <div>
            <div class="sec-title">Change Password</div>
            <div class="sec-sub">Update your developer account password</div>
          </div>
        </div>
        <div class="sec-body">
          <div class="form-field">
            <label class="form-label" for="currentPw">Current Password</label>
            <input class="form-input" type="password" id="currentPw"
              placeholder="Enter current password" autocomplete="current-password">
          </div>
          <div class="form-field">
            <label class="form-label" for="newPw">New Password</label>
            <input class="form-input" type="password" id="newPw"
              placeholder="Min. 8 characters" autocomplete="new-password"
              oninput="checkStrength(this.value)">
            <div class="pw-strength-wrap">
              <div class="pw-strength-bar">
                <div class="pw-strength-fill" id="pwStrengthFill"></div>
              </div>
              <span class="pw-strength-label" id="pwStrengthLabel"></span>
            </div>
          </div>
          <div class="form-field">
            <label class="form-label" for="confirmPw">Confirm New Password</label>
            <input class="form-input" type="password" id="confirmPw"
              placeholder="Re-enter new password" autocomplete="new-password">
          </div>
          <div class="form-alert hidden" id="pwAlert"></div>
          <button type="button" class="btn-submit red-btn" id="pwBtn" onclick="submitPassword()">
            <span id="pwBtnText">Update Password</span>
            <span id="pwBtnSpinner" style="display:none;"><span class="spinner"></span></span>
          </button>
        </div>
      </div>

    </div>{{-- end grid --}}
  </div>{{-- end content --}}
</div>{{-- end main-wrap --}}

{{-- ══════════════ LOGOUT MODAL ══════════════ --}}
<div class="modal-overlay" id="logoutModal">
  <div class="logout-box">
    <div class="logout-ico">
      <svg width="20" height="20" fill="none" stroke="var(--maroon)" stroke-width="2" viewBox="0 0 24 24">
        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>
    <div class="logout-modal-title">Sign Out?</div>
    <p class="logout-modal-desc">You'll be returned to the login page.</p>
    <div class="logout-modal-actions">
      <button type="button" class="btn-cancel" id="cancelLogout">Cancel</button>
      <form method="POST" action="{{ url('/logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Sign Out</button>
      </form>
    </div>
  </div>
</div>

<script>
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  const SCROLL_LOCK_MODAL_IDS = ['logoutModal'];

  function syncBodyScrollLock() {
    const sidebarOpen = document.getElementById('mainSidebar')?.classList.contains('mobile-open');
    const anyModal = SCROLL_LOCK_MODAL_IDS.some(id => document.getElementById(id)?.classList.contains('open'));
    document.body.style.overflow = (sidebarOpen || anyModal) ? 'hidden' : '';
  }

  function openLogoutModal() {
    document.getElementById('logoutModal')?.classList.add('open');
    syncBodyScrollLock();
  }
  function closeLogoutModal() {
    document.getElementById('logoutModal')?.classList.remove('open');
    syncBodyScrollLock();
  }

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

  /* ── Live Clock ── */
  (function tick() {
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    document.getElementById('liveClock').textContent =
      pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
    setTimeout(tick, 1000);
  })();

  /* ── Toast ── */
  function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = `toast ${type}`;
    t.innerHTML = `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">${type === 'success' ? '<polyline points="20 6 9 17 4 12"/>' : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}</svg>${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.classList.add('hiding'); setTimeout(() => t.remove(), 300); }, 4000);
  }

  /* ── Alert helper ── */
  function setAlert(id, msg, type) {
    const el = document.getElementById(id);
    el.className = `form-alert ${type}`;
    el.innerHTML = `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">${type === 'success' ? '<polyline points="20 6 9 17 4 12"/>' : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}</svg>${msg}`;
  }
  function clearAlert(id) {
    document.getElementById(id).className = 'form-alert hidden';
    document.getElementById(id).textContent = '';
  }

  /* ════════════════════════════════════════
     CHANGE DISPLAY NAME
  ════════════════════════════════════════ */
  function focusNameField() {
    document.getElementById('newNameInput').focus();
    document.getElementById('nameSection').scrollIntoView({ behavior:'smooth', block:'center' });
  }

  async function submitName() {
    const val  = document.getElementById('newNameInput').value.trim();
    const btn  = document.getElementById('nameBtn');
    const text = document.getElementById('nameBtnText');
    const spin = document.getElementById('nameBtnSpinner');
    clearAlert('nameAlert');

    if (!val) { setAlert('nameAlert','Please enter a new display name.','error'); return; }
    if (val.length < 2) { setAlert('nameAlert','Name must be at least 2 characters.','error'); return; }

    btn.disabled = true; text.style.display = 'none'; spin.style.display = '';
    try {
      const resp = await fetch('/profile/change-name', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF },
        body: JSON.stringify({ name: val }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        // Update all name displays on the page
        document.getElementById('heroDisplayName').textContent  = data.name;
        document.getElementById('infoDisplayName').textContent  = data.name;
        document.getElementById('newNameInput').value           = '';
        // Update initials if no avatar
        const initials = data.name.split(' ').map(w=>w.charAt(0).toUpperCase()).slice(0,2).join('');
        const heroInit = document.getElementById('heroAvatarInitials');
        const sideInit = document.getElementById('sidebarAvatarInitials');
        if (heroInit) heroInit.textContent = initials;
        if (sideInit) sideInit.textContent = initials;
        setAlert('nameAlert', 'Display name updated successfully.', 'success');
        showToast('Display name updated.');
      } else {
        setAlert('nameAlert', data.message || 'Failed to update name.', 'error');
      }
    } catch(e) { setAlert('nameAlert','Something went wrong. Please try again.','error'); }
    finally { btn.disabled = false; text.style.display = ''; spin.style.display = 'none'; }
  }

  /* ════════════════════════════════════════
     CHANGE PASSWORD
  ════════════════════════════════════════ */
  function checkStrength(pw) {
    const fill  = document.getElementById('pwStrengthFill');
    const label = document.getElementById('pwStrengthLabel');
    if (!pw) { fill.style.width = '0%'; label.textContent = ''; return; }

    let score = 0;
    if (pw.length >= 8)                         score++;
    if (pw.length >= 12)                        score++;
    if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
    if (/\d/.test(pw))                          score++;
    if (/[^A-Za-z0-9]/.test(pw))               score++;

    const levels = [
      { w:'20%',  bg:'var(--red)',   lbl:'Weak',   col:'var(--red)' },
      { w:'40%',  bg:'var(--red)',   lbl:'Weak',   col:'var(--red)' },
      { w:'60%',  bg:'var(--amber)', lbl:'Fair',   col:'var(--amber)' },
      { w:'80%',  bg:'var(--amber)', lbl:'Good',   col:'var(--amber)' },
      { w:'100%', bg:'var(--green)', lbl:'Strong', col:'var(--green)' },
    ];
    const lvl = levels[Math.min(score, 4)];
    fill.style.width      = lvl.w;
    fill.style.background = lvl.bg;
    label.textContent     = lvl.lbl;
    label.style.color     = lvl.col;
  }

  async function submitPassword() {
    const current = document.getElementById('currentPw').value;
    const newPw   = document.getElementById('newPw').value;
    const confirm = document.getElementById('confirmPw').value;
    const btn     = document.getElementById('pwBtn');
    const text    = document.getElementById('pwBtnText');
    const spin    = document.getElementById('pwBtnSpinner');
    clearAlert('pwAlert');

    if (!current)           { setAlert('pwAlert','Please enter your current password.','error'); return; }
    if (!newPw)             { setAlert('pwAlert','Please enter a new password.','error'); return; }
    if (newPw.length < 8)   { setAlert('pwAlert','New password must be at least 8 characters.','error'); return; }
    if (newPw !== confirm)  { setAlert('pwAlert','Passwords do not match.','error'); return; }
    if (current === newPw)  { setAlert('pwAlert','New password must differ from current password.','error'); return; }

    btn.disabled = true; text.style.display = 'none'; spin.style.display = '';
    try {
      const resp = await fetch('/profile/change-password', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF },
        body: JSON.stringify({ current_password: current, new_password: newPw }),
      });
      const data = await resp.json();
      if (resp.ok && data.success) {
        document.getElementById('currentPw').value = '';
        document.getElementById('newPw').value     = '';
        document.getElementById('confirmPw').value = '';
        document.getElementById('pwStrengthFill').style.width = '0%';
        document.getElementById('pwStrengthLabel').textContent = '';
        setAlert('pwAlert','Password updated successfully.','success');
        showToast('Password updated.');
      } else {
        setAlert('pwAlert', data.message || 'Failed to update password.','error');
      }
    } catch(e) { setAlert('pwAlert','Something went wrong. Please try again.','error'); }
    finally { btn.disabled = false; text.style.display = ''; spin.style.display = 'none'; }
  }

  /* ════════════════════════════════════════
     AVATAR UPLOAD
     Both file inputs (hero badge + upload zone)
     feed into the same selectedFile variable.
  ════════════════════════════════════════ */
  let selectedFile = null;

  function onFileSelected(file) {
    if (!file) return;
    if (!['image/jpeg','image/jpg','image/png','image/webp'].includes(file.type)) {
      showToast('Only JPG, PNG, or WEBP images are allowed.','error'); return;
    }
    if (file.size > 2 * 1024 * 1024) {
      showToast('File exceeds 2MB limit.','error'); return;
    }
    selectedFile = file;
    document.getElementById('avatarBtn').disabled = false;
    clearAlert('avatarAlert');

    // Show preview
    const reader = new FileReader();
    reader.onload = e => {
      updateAllAvatarPreviews(e.target.result);
    };
    reader.readAsDataURL(file);
  }

  function updateAllAvatarPreviews(src) {
    // Hero avatar
    const heroImg = document.getElementById('heroAvatarImg');
    const heroInit = document.getElementById('heroAvatarInitials');
    if (heroImg) {
      heroImg.src = src;
    } else {
      const av = document.getElementById('heroAvatar');
      av.innerHTML = `<img src="${src}" id="heroAvatarImg" style="width:80px;height:80px;object-fit:cover;border-radius:18px;display:block;">`;
    }
    // Upload zone preview
    const prevImg  = document.getElementById('uploadPreviewImg');
    const prevInit = document.getElementById('uploadPreviewInitials');
    const prevBox  = document.getElementById('uploadPreview');
    if (prevImg) {
      prevImg.src = src;
    } else {
      prevBox.innerHTML = `<img src="${src}" id="uploadPreviewImg" style="width:64px;height:64px;object-fit:cover;border-radius:14px;display:block;">`;
    }
    // Sidebar avatar
    const sideAv  = document.getElementById('sidebarAvatar');
    const sideInit = document.getElementById('sidebarAvatarInitials');
    if (sideAv) {
      sideAv.src = src;
    } else if (sideInit) {
      const wrap = sideInit.parentElement;
      wrap.innerHTML = `<img src="${src}" id="sidebarAvatar" style="width:42px;height:42px;object-fit:cover;border-radius:14px;display:block;">`;
    }
  }

  // Hero avatar file input (hidden, triggered by badge click)
  document.getElementById('avatarFileInput').addEventListener('change', e => {
    onFileSelected(e.target.files[0]);
    // Sync to zone input too
    document.getElementById('avatarFileInput2').files = e.target.files;
  });

  // Upload zone file input
  document.getElementById('avatarFileInput2').addEventListener('change', e => {
    onFileSelected(e.target.files[0]);
  });

  // Drag & drop on upload zone
  const zone = document.getElementById('uploadZone');
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
  zone.addEventListener('drop', e => {
    e.preventDefault(); zone.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file) onFileSelected(file);
  });

  async function submitAvatar() {
    if (!selectedFile) return;
    const btn  = document.getElementById('avatarBtn');
    const text = document.getElementById('avatarBtnText');
    const spin = document.getElementById('avatarBtnSpinner');
    const prog = document.getElementById('uploadProgress');
    const fill = document.getElementById('uploadProgressFill');
    clearAlert('avatarAlert');

    btn.disabled = true; text.style.display = 'none'; spin.style.display = '';
    prog.style.display = 'block'; fill.style.width = '30%';

    const formData = new FormData();
    formData.append('avatar', selectedFile);
    formData.append('_token', CSRF);

    try {
      fill.style.width = '60%';
      const resp = await fetch('/profile/upload-avatar', {
        method: 'POST',
        credentials: 'same-origin',
        body: formData,
      });
      fill.style.width = '100%';
      const data = await resp.json();

      if (resp.ok && data.success) {
        // Replace previews with the real stored URL
        updateAllAvatarPreviews(data.url);
        selectedFile = null;
        btn.disabled = true;
        setAlert('avatarAlert','Profile picture updated successfully.','success');
        showToast('Profile picture updated.');
      } else {
        setAlert('avatarAlert', data.message || 'Upload failed.','error');
      }
    } catch(e) { setAlert('avatarAlert','Something went wrong. Please try again.','error'); }
    finally {
      btn.disabled = !selectedFile;
      text.style.display = ''; spin.style.display = 'none';
      setTimeout(() => { prog.style.display = 'none'; fill.style.width = '0%'; }, 600);
    }
  }

  /* ── Logout ── */
  document.getElementById('logoutBtn').addEventListener('click', openLogoutModal);
  document.getElementById('cancelLogout').addEventListener('click', closeLogoutModal);
  document.getElementById('logoutModal').addEventListener('click', e => {
    if (e.target === document.getElementById('logoutModal')) closeLogoutModal();
  });
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    const lo = document.getElementById('logoutModal');
    if (lo?.classList.contains('open')) { closeLogoutModal(); return; }
    if (mainSidebar?.classList.contains('mobile-open')) closeMobileSidebar();
  });

  /* ── Enter key shortcuts ── */
  document.getElementById('newNameInput').addEventListener('keypress', e => { if(e.key==='Enter') submitName(); });
  document.getElementById('confirmPw').addEventListener('keypress',  e => { if(e.key==='Enter') submitPassword(); });
</script>
</body>
</html>
