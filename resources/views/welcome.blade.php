<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KTTM — Intellectual Property Records System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --maroon:  #A52C30;
    --maroon2: #7E1F23;
    --maroon3: #C1363A;
    --maroon-light: rgba(165,44,48,0.10);
    --maroon-mid:   rgba(165,44,48,0.22);
    --gold:    #F0C860;
    --gold2:   #E8B857;
    --gold3:   #D4A030;
    --ink:     #0F172A;
    --muted:   #64748B;
    --line:    rgba(15,23,42,0.09);
    --card:    rgba(255,255,255,0.82);
    --bg:      #F1F4F9;
    --glass:   rgba(255,255,255,0.55);
    --glass-b: rgba(255,255,255,0.70);
    --shell-max: 1120px;
    --pad-x: clamp(1rem, 4vw, 2.5rem);
  }

  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  html {
    scroll-behavior: smooth;
    scroll-snap-type: y mandatory;
    -webkit-font-smoothing: antialiased;
  }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--ink);
    overflow-x: hidden;
    padding-left: env(safe-area-inset-left);
    padding-right: env(safe-area-inset-right);
  }

  @media (pointer: fine) {
    body,
    .nav-links a,
    .btn-primary,
    .btn-ghost,
    .modal-close,
    .role-btn,
    .field input,
    .btn-login,
    .service-card,
    .faq-item {
      cursor: none;
    }
  }

  /* ── CUSTOM CURSOR ── */
  .cursor {
    position: fixed; width: 10px; height: 10px;
    border-radius: 50%; background: var(--maroon);
    pointer-events: none; z-index: 9999;
    mix-blend-mode: multiply;
  }
  .cursor-ring {
    position: fixed; width: 34px; height: 34px;
    border-radius: 50%; border: 1.5px solid var(--gold);
    pointer-events: none; z-index: 9998;
    transition: left 0.28s cubic-bezier(.17,.67,.35,1.1),
                top  0.28s cubic-bezier(.17,.67,.35,1.1);
    transform: translate(-50%, -50%);
    opacity: 0.7;
  }
  @media (pointer: coarse) {
    .cursor, .cursor-ring { display: none !important; }
  }

  /* ── NAV ── */
  nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    padding: 0 var(--pad-x);
    height: 68px;
    display: flex; align-items: center; justify-content: space-between;
    background: rgba(241,244,249,0.75);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--line);
    box-shadow: 0 2px 16px rgba(15,23,42,0.06);
  }
  .nav-logo {
    display: flex; align-items: center; gap: 10px;
    text-decoration: none;
  }
  .nav-logo-badge {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.9rem; color: var(--gold);
    box-shadow: 0 4px 14px rgba(165,44,48,0.28);
    flex-shrink: 0;
  }
  .nav-logo-text {
    font-size: 0.95rem; font-weight: 800;
    color: var(--ink); letter-spacing: -0.2px;
  }
  .nav-logo-sub {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; letter-spacing: 0.16em;
    text-transform: uppercase; color: var(--muted);
  }
  .nav-toggle {
    display: none;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    margin-left: auto;
    border: 1.5px solid var(--line);
    border-radius: 12px;
    background: var(--glass-b);
    color: var(--ink);
    font: inherit;
    -webkit-tap-highlight-color: transparent;
  }
  .nav-toggle:focus-visible {
    outline: 2px solid var(--maroon);
    outline-offset: 2px;
  }
  .nav-toggle-bar {
    display: block;
    width: 20px;
    height: 2px;
    border-radius: 1px;
    background: currentColor;
    position: relative;
  }
  .nav-toggle-bar::before,
  .nav-toggle-bar::after {
    content: '';
    position: absolute;
    left: 0;
    width: 100%;
    height: 2px;
    border-radius: 1px;
    background: currentColor;
  }
  .nav-toggle-bar::before { top: -7px; }
  .nav-toggle-bar::after { top: 7px; }
  nav.nav-open .nav-toggle-bar { background: transparent; }
  nav.nav-open .nav-toggle-bar::before { top: 0; transform: rotate(45deg); }
  nav.nav-open .nav-toggle-bar::after { top: 0; transform: rotate(-45deg); }

  .nav-links { display: flex; gap: 0.3rem; align-items: center; flex-wrap: wrap; justify-content: flex-end; }
  .nav-links a {
    font-size: 0.78rem; font-weight: 600; letter-spacing: 0.01em;
    color: var(--muted); text-decoration: none;
    padding: 0.5rem 1rem; border-radius: 10px;
    flex: 0 0 auto;
    text-align: center;
    -webkit-tap-highlight-color: transparent;
    transition: background 0.18s, color 0.18s;
  }
  .nav-links a:hover { background: var(--maroon-light); color: var(--maroon); }
  .nav-cta {
    background: linear-gradient(135deg, var(--maroon2), var(--maroon)) !important;
    color: #fff !important;
    padding: 0.55rem 1.3rem !important;
    border-radius: 10px !important;
    box-shadow: 0 6px 16px rgba(165,44,48,0.26);
    font-weight: 700 !important;
  }
  .nav-cta:hover {
    background: linear-gradient(135deg, var(--maroon), var(--maroon3)) !important;
    color: #fff !important;
  }

  /* ── SECTIONS ── */
  section {
    min-height: 100vh;
    scroll-snap-align: start;
    position: relative;
    overflow: hidden;
  }

  /* BG shared */
  .bg-layer {
    position: absolute; inset: 0;
    background-image: url('images/abstractBGIMAGE7.png');
    background-size: cover; background-position: center;
    z-index: 0;
  }
  .bg-overlay {
    position: absolute; inset: 0; z-index: 1;
  }

  /* ── SECTION 1: HERO ── */
  #hero { display: flex; flex-direction: column; align-items: center; justify-content: center; }
  #hero .bg-overlay {
    background: linear-gradient(135deg,
      rgba(241,244,249,0.80) 0%,
      rgba(220,210,200,0.40) 45%,
      rgba(241,244,249,0.72) 100%);
  }

  /* Noise grain overlay */
  #hero::after {
    content: ''; position: absolute; inset: 0; z-index: 1;
    opacity: 0.025;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    pointer-events: none;
  }

  /* Floating orbs */
  .orb {
    position: absolute; border-radius: 50%;
    pointer-events: none; z-index: 1;
    filter: blur(80px);
  }
  .orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(165,44,48,0.14), transparent);
    top: -120px; left: -120px;
    animation: drift1 16s ease-in-out infinite;
  }
  .orb-2 {
    width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(240,200,96,0.18), transparent);
    bottom: -60px; right: -80px;
    animation: drift2 20s ease-in-out infinite;
  }
  .orb-3 {
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(165,44,48,0.08), transparent);
    top: 40%; right: 20%;
    animation: drift1 24s ease-in-out infinite reverse;
  }

  .hero-content {
    position: relative; z-index: 2;
    text-align: center;
    width: 100%;
    max-width: min(860px, 100%);
    margin: 0 auto;
    padding: 2rem var(--pad-x);
    padding-top: calc(68px + 2rem);
  }
  .hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    max-width: 100%;
    font-family: 'DM Mono', monospace;
    font-size: clamp(0.55rem, 2.8vw, 0.68rem);
    letter-spacing: clamp(0.12em, 0.06vw + 0.1em, 0.26em);
    text-transform: uppercase; color: var(--maroon);
    background: var(--maroon-light);
    border: 1px solid rgba(165,44,48,0.18);
    padding: 0.45rem 1rem; border-radius: 20px;
    margin-bottom: 2rem;
    opacity: 0; animation: fadeUp 0.9s 0.3s forwards;
  }
  .hero-eyebrow::before {
    content: ''; width: 7px; height: 7px; border-radius: 50%;
    background: var(--maroon);
    box-shadow: 0 0 0 4px rgba(165,44,48,0.18);
    flex-shrink: 0;
  }
  .hero-title {
    font-size: clamp(2rem, 5.5vw + 0.5rem, 5.5rem);
    font-weight: 800; line-height: 1.0;
    color: var(--ink); letter-spacing: -1.5px;
    text-wrap: balance;
    opacity: 0; animation: fadeUp 1s 0.5s forwards;
  }
  .hero-title .accent {
    color: var(--maroon);
    position: relative; display: inline-block;
  }
  .hero-title .accent::after {
    content: '';
    position: absolute; bottom: 4px; left: 0; right: 0;
    height: 4px; border-radius: 2px;
    background: linear-gradient(90deg, var(--gold), var(--gold2));
    opacity: 0.7;
  }
  .hero-title .light { font-weight: 300; color: var(--muted); display: block; font-size: 0.7em; letter-spacing: -0.5px; margin-top: 4px; }
  .hero-subtitle {
    margin-top: 2rem;
    font-size: clamp(0.9rem, 0.35vw + 0.82rem, 1.05rem);
    font-weight: 400;
    color: var(--muted); line-height: 1.75;
    max-width: min(36rem, 100%);
    margin-left: auto; margin-right: auto;
    overflow-wrap: break-word;
    opacity: 0; animation: fadeUp 1s 0.7s forwards;
  }
  .hero-actions {
    margin-top: 2.8rem;
    display: flex;
    gap: 0.75rem 0.9rem;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    opacity: 0; animation: fadeUp 1s 0.9s forwards;
  }
  .btn-primary {
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    color: #fff; font-family: inherit;
    font-size: clamp(0.78rem, 0.2vw + 0.74rem, 0.82rem);
    font-weight: 700;
    letter-spacing: 0.02em;
    padding: 0.85rem clamp(1.1rem, 3vw, 2.2rem);
    border: none; border-radius: 12px;
    text-decoration: none;
    box-shadow: 0 8px 22px rgba(165,44,48,0.30);
    transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: auto;
    max-width: 100%;
    flex: 0 1 auto;
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
  }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(165,44,48,0.38); }
  .btn-ghost {
    background: var(--glass-b);
    color: var(--ink);
    font-family: inherit;
    font-size: clamp(0.78rem, 0.2vw + 0.74rem, 0.82rem);
    font-weight: 600;
    padding: 0.85rem clamp(1.1rem, 3vw, 2.2rem);
    border: 1.5px solid var(--line);
    border-radius: 12px;
    text-decoration: none;
    backdrop-filter: blur(10px);
    transition: border-color 0.2s, background 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: auto;
    max-width: 100%;
    flex: 0 1 auto;
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
  }
  .btn-ghost:hover { border-color: var(--maroon); background: var(--maroon-light); color: var(--maroon); }

  /* Hero stat pills */
  .hero-stats {
    margin-top: 3rem;
    display: flex;
    gap: 0.65rem 0.85rem;
    justify-content: center;
    align-items: stretch;
    opacity: 0;
    animation: fadeUp 1s 1.1s forwards;
    flex-wrap: wrap;
  }
  /* filter at top of stats (month/year selectors) */
  .hero-filter {
    margin-top: 2rem;
    display: flex;
    gap: 0.5rem 0.65rem;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    width: 100%;
    max-width: 32rem;
    margin-left: auto;
    margin-right: auto;
  }
  .hero-filter select {
    padding: 0.5rem 0.8rem;
    border-radius: 6px;
    border: 1px solid var(--line);
    background: var(--bg);
    color: var(--ink);
    font-size: 0.85rem;
    flex: 1 1 8rem;
    min-width: 0;
    max-width: 14rem;
    width: auto;
  }
  .hero-filter .btn-primary {
    flex: 0 0 auto;
    padding: 0.55rem 1.15rem;
    white-space: nowrap;
  }
  .hero-filter button {
    padding: 0.5rem 1rem;
  }
  .hero-filter-info {
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: var(--muted);
    text-align: center;
    font-family: 'DM Mono', monospace;
  }
  .hero-stat {
    background: var(--glass-b); border: 1px solid var(--line);
    backdrop-filter: blur(12px); border-radius: 14px;
    padding: 0.85rem clamp(0.9rem, 2.5vw, 1.6rem);
    text-align: center;
    box-shadow: 0 4px 16px rgba(15,23,42,0.06);
    flex: 0 1 auto;
    min-width: min(148px, 47%);
    max-width: 100%;
    box-sizing: border-box;
  }
  .hero-stat-num {
    font-size: clamp(1.2rem, 2.5vw + 0.5rem, 1.5rem);
    font-weight: 800; color: var(--maroon); line-height: 1;
    letter-spacing: -0.5px;
  }
  .hero-stat-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; letter-spacing: 0.18em;
    text-transform: uppercase; color: var(--muted); margin-top: 4px;
  }

  /* Decorative seal */
  .hero-seal {
    position: absolute; right: 7%; bottom: 11%;
    width: 120px; height: 120px; z-index: 2;
    opacity: 0; animation: fadeIn 1.4s 1.3s forwards;
  }
  .seal-svg { width: 100%; height: 100%; animation: rotateSeal 30s linear infinite; }

  /* Scroll indicator */
  .scroll-indicator {
    position: absolute; bottom: 2rem; left: 50%;
    transform: translateX(-50%); z-index: 2;
    display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
    opacity: 0; animation: fadeIn 1s 1.6s forwards;
  }
  .scroll-indicator span {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; letter-spacing: 0.22em;
    text-transform: uppercase; color: var(--muted);
  }
  .scroll-line {
    width: 1px; height: 44px;
    background: linear-gradient(to bottom, var(--maroon), transparent);
    animation: scrollPulse 2s ease-in-out infinite;
  }

  /* ── LOGIN MODAL ── */
  .login-modal {
    display: none; position: fixed; inset: 0; z-index: 200;
    background: rgba(15,23,42,0.55);
    backdrop-filter: blur(14px);
    align-items: center; justify-content: center;
  }
  .login-modal.open { display: flex; }
  .modal-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 24px;
    width: min(420px, calc(100vw - 2rem));
    max-width: 100%;
    padding: clamp(1.75rem, 4vw, 2.8rem) clamp(1.35rem, 4vw, 2.6rem) clamp(1.5rem, 3vw, 2.4rem);
    position: relative;
    box-shadow: 0 32px 80px rgba(15,23,42,0.18);
    animation: modalIn 0.36s cubic-bezier(.17,.67,.35,1.1);
  }
  @keyframes modalIn {
    from { opacity: 0; transform: translateY(20px) scale(0.97); }
    to   { opacity: 1; transform: none; }
  }
  .modal-close {
    position: absolute; top: 1.2rem; right: 1.3rem;
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--bg); border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: var(--muted);
    transition: background 0.18s, color 0.18s;
  }
  .modal-close:hover { background: var(--maroon-light); color: var(--maroon); }
  .modal-badge {
    width: 48px; height: 48px; border-radius: 14px;
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.1rem; color: var(--gold);
    margin-bottom: 1.2rem;
    box-shadow: 0 6px 18px rgba(165,44,48,0.25);
  }
  .modal-eyebrow {
    font-family: 'DM Mono', monospace;
    font-size: 0.62rem; letter-spacing: 0.26em;
    text-transform: uppercase; color: var(--maroon);
    margin-bottom: 0.4rem;
  }
  .modal-title {
    font-size: 1.6rem; font-weight: 800; color: var(--ink);
    letter-spacing: -0.5px; margin-bottom: 0.3rem;
  }
  .modal-sub {
    font-size: 0.82rem; color: var(--muted); margin-bottom: 1.8rem;
    font-weight: 400;
  }
  .modal-role-toggle {
    display: flex;
    gap: 6px;
    margin-bottom: 1.5rem;
    background: var(--bg);
    border-radius: 12px;
    padding: 5px;
  }
  .role-btn {
    flex: 1 1 0;
    min-width: 0;
    padding: 0.62rem 0.65rem;
    background: transparent; border: none;
    font-family: inherit;
    font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
    font-weight: 600;
    color: var(--muted); border-radius: 9px;
    transition: background 0.18s, color 0.18s;
    text-align: center;
    line-height: 1.35;
    white-space: normal;
    overflow-wrap: break-word;
  }
  .role-btn.active {
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    color: #fff;
    box-shadow: 0 4px 12px rgba(165,44,48,0.22);
  }
  .field { margin-bottom: 1.1rem; }
  .field label {
    display: block; font-size: 0.7rem; letter-spacing: 0.1em;
    text-transform: uppercase; color: var(--muted); margin-bottom: 0.4rem;
    font-weight: 600;
  }
  .field input {
    width: 100%; padding: 0.82rem 1rem;
    border: 1.5px solid var(--line);
    background: var(--bg);
    font-family: inherit;
    font-size: 0.88rem; color: var(--ink);
    outline: none; border-radius: 12px;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .field input:focus {
    border-color: var(--maroon);
    box-shadow: 0 0 0 3px var(--maroon-light);
    background: #fff;
  }
  .field input::placeholder { color: #b0b8c8; }
  .btn-login {
    width: 100%;
    max-width: 100%;
    padding: 0.92rem;
    box-sizing: border-box;
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    color: #fff; border: none;
    font-family: inherit; font-size: 0.82rem; font-weight: 700;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(165,44,48,0.26);
    transition: opacity 0.2s, transform 0.2s;
    margin-top: 0.4rem;
  }
  .btn-login:hover { opacity: 0.88; transform: translateY(-1px); }

  /* hide login fields when guest role selected */
  .auth-fields {
    transition: max-height 0.3s ease, opacity 0.3s ease;
    overflow: hidden;
    max-height: 400px;
  }
  .modal-card.guest .auth-fields {
    max-height: 0;
    opacity: 0;
  }

  /* card animation on role toggle */
  .modal-card.animating {
    transition: transform 0.25s ease;
    transform: scale(0.96);
  }

  .modal-divider { border: none; border-top: 1px solid var(--line); margin: 1.3rem 0 0.9rem; }
  .modal-hint {
    font-size: 0.74rem; color: var(--muted); text-align: center; font-weight: 400;
  }
  .modal-hint strong { color: var(--maroon); }

  /* ── SECTION 2: SERVICES ── */
  #services {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: clamp(5rem, 12vw, 8rem) var(--pad-x) clamp(4rem, 8vw, 6rem);
  }
  #services > :not(.bg-layer):not(.bg-overlay) {
    width: 100%;
    max-width: var(--shell-max);
  }
  #services .bg-overlay {
    background: linear-gradient(150deg,
      rgba(241,244,249,0.92) 0%,
      rgba(220,210,190,0.45) 55%,
      rgba(241,244,249,0.88) 100%);
  }

  .section-header { position: relative; z-index: 2; margin-bottom: 3.5rem; }
  .section-label {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: 'DM Mono', monospace;
    font-size: 0.62rem; letter-spacing: 0.26em;
    text-transform: uppercase; color: var(--maroon);
    background: var(--maroon-light); border: 1px solid rgba(165,44,48,0.18);
    padding: 0.35rem 0.9rem; border-radius: 20px; margin-bottom: 1.2rem;
  }
  .section-title {
    font-size: clamp(1.75rem, 3.5vw + 0.5rem, 4rem);
    font-weight: 800; color: var(--ink);
    line-height: 1.08;
    letter-spacing: -1px;
    text-wrap: balance;
  }
  .section-title .accent { color: var(--maroon); }
  .section-desc {
    margin-top: 1rem;
    font-size: clamp(0.86rem, 0.25vw + 0.8rem, 0.93rem);
    font-weight: 400;
    color: var(--muted);
    max-width: min(42rem, 100%);
    line-height: 1.75;
    overflow-wrap: break-word;
  }

  .services-grid {
    position: relative; z-index: 2;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: clamp(12px, 2vw, 16px);
  }
  @media (max-width: 1000px) {
    .services-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }
  @media (max-width: 640px) {
    .services-grid { grid-template-columns: minmax(0, 1fr); }
  }
  .service-card {
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 20px;
    padding: 2rem 1.8rem;
    position: relative; overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 12px rgba(15,23,42,0.05);
  }
  .service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(15,23,42,0.10);
  }
  .service-card::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--maroon), var(--gold));
    transform: scaleX(0); transform-origin: left;
    transition: transform 0.4s ease; border-radius: 3px 3px 0 0;
  }
  .service-card:hover::before { transform: scaleX(1); }
  .service-num {
    font-family: 'DM Mono', monospace;
    font-size: 2.8rem; font-weight: 300;
    color: rgba(231, 24, 31, 0.41); line-height: 1;
    margin-bottom: 0.8rem;
  }
  .service-icon-wrap {
    width: 46px; height: 46px; border-radius: 14px;
    background: var(--maroon-light);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.1rem;
    transition: background 0.2s;
  }
  .service-card:hover .service-icon-wrap { background: var(--maroon); }
  .service-icon { width: 22px; height: 22px; color: var(--maroon); transition: color 0.2s; }
  .service-card:hover .service-icon { color: #fff; }
  .service-name {
    font-size: 1rem; font-weight: 700; color: var(--ink);
    margin-bottom: 0.6rem; letter-spacing: -0.2px;
  }
  .service-desc {
    font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.8rem);
    color: var(--muted);
    line-height: 1.7;
    font-weight: 400;
    overflow-wrap: break-word;
    hyphens: auto;
  }
  .service-tag {
    display: inline-block; margin-top: 1.1rem;
    font-family: 'DM Mono', monospace;
    font-size: 0.58rem; letter-spacing: 0.16em;
    text-transform: uppercase; color: var(--maroon);
    background: var(--maroon-light);
    border: 1px solid rgba(165,44,48,0.18);
    padding: 0.28rem 0.7rem; border-radius: 20px;
  }

  /* ── SECTION 3: FAQ ── */
  #faq {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: clamp(5rem, 12vw, 8rem) var(--pad-x) clamp(4rem, 8vw, 6rem);
  }
  #faq > :not(.bg-layer):not(.bg-overlay) {
    width: 100%;
    max-width: var(--shell-max);
  }
  #faq .bg-overlay {
    background: linear-gradient(160deg,
      rgba(241,244,249,0.94) 0%,
      rgba(210,205,195,0.40) 100%);
  }

  .faq-layout {
    position: relative; z-index: 2;
    display: grid; grid-template-columns: 1.4fr 1fr;
    gap: 5rem; align-items: start;
  }
  .faq-list { display: flex; flex-direction: column; }
  .faq-item {
    border-bottom: 1px solid var(--line);
    padding: 1.3rem 0;
  }
  .faq-question {
    display: flex; justify-content: space-between; align-items: start; gap: 1rem;
  }
  .faq-q-text {
    font-size: clamp(0.88rem, 0.35vw + 0.8rem, 0.97rem);
    font-weight: 600;
    color: var(--ink);
    line-height: 1.45;
    overflow-wrap: break-word;
    min-width: 0;
  }
  .faq-toggle {
    flex-shrink: 0; width: 26px; height: 26px; border-radius: 8px;
    border: 1.5px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    color: var(--muted); font-size: 1rem;
    transition: transform 0.3s, background 0.2s, border-color 0.2s;
    margin-top: 1px; background: var(--bg);
  }
  .faq-item.open .faq-toggle {
    transform: rotate(45deg);
    background: var(--maroon); color: #fff; border-color: var(--maroon);
  }
  .faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.3s;
    font-size: clamp(0.8rem, 0.2vw + 0.76rem, 0.84rem);
    color: var(--muted);
    line-height: 1.8;
    font-weight: 400;
    overflow-wrap: break-word;
  }
  .faq-item.open .faq-answer { max-height: min(28rem, 70vh); padding-top: 0.85rem; }

  /* ── FAQ ASIDE — dynamic panel ── */
  .faq-aside { position: sticky; top: 6rem; }

  .faq-panel-wrap {
    position: relative; min-height: 320px;
  }

  /* Each panel sits absolutely so they can cross-fade */
  .faq-panel {
    position: absolute; top: 0; left: 0; right: 0;
    opacity: 0; pointer-events: none;
    transform: translateY(14px);
    transition: opacity 0.32s ease, transform 0.32s ease;
  }
  .faq-panel.active {
    opacity: 1; pointer-events: auto;
    transform: translateY(0);
    position: relative;
  }
  .faq-panel.exit {
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 0.18s ease, transform 0.18s ease;
  }

  /* ── PANEL BASE CARD (dark maroon) ── */
  .faq-info-card {
    background: linear-gradient(135deg, var(--maroon2) 0%, var(--maroon3) 100%);
    border-radius: 24px; padding: 2.4rem;
    position: relative; overflow: hidden; margin-bottom: 16px;
    box-shadow: 0 12px 40px rgba(165,44,48,0.28);
  }
  .faq-info-card::before {
    content: ''; position: absolute; top: -40px; right: -40px;
    width: 180px; height: 180px; border-radius: 50%;
    background: rgba(255,255,255,0.05);
  }
  .faq-info-card::after {
    content: 'KTTM'; font-size: 6.5rem; font-weight: 800;
    color: rgba(255,255,255,0.04); position: absolute;
    bottom: -1.2rem; right: -0.8rem; line-height: 1; pointer-events: none;
    font-family: inherit; letter-spacing: -2px;
  }
  .fic-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; letter-spacing: 0.26em;
    text-transform: uppercase; color: var(--gold);
    opacity: 0.85; margin-bottom: 0.9rem;
  }
  .fic-title {
    font-size: clamp(1.25rem, 2vw + 0.6rem, 1.7rem);
    font-weight: 800; color: #fff;
    line-height: 1.15; margin-bottom: 0.9rem; letter-spacing: -0.5px;
    text-wrap: balance;
  }
  .fic-desc {
    font-size: 0.8rem; color: rgba(255,255,255,0.65);
    line-height: 1.75; margin-bottom: 1.6rem;
  }
  .fic-roles { display: flex; gap: 8px; flex-wrap: wrap; }
  .role-pill {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; letter-spacing: 0.1em; text-transform: uppercase;
    padding: 0.3rem 0.8rem; border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.18); color: rgba(255,255,255,0.65);
  }
  .role-pill.admin {
    background: rgba(240,200,96,0.22);
    border-color: rgba(240,200,96,0.45); color: var(--gold);
  }

  /* Stats bar */
  .stats-bar {
    background: var(--card); border: 1px solid var(--line);
    border-radius: 20px; padding: 1.4rem 1.8rem;
    display: flex; gap: 0; justify-content: space-around;
    box-shadow: 0 2px 12px rgba(15,23,42,0.05);
  }
  .stat { text-align: center; flex: 1 1 0; min-width: 0; }
  .stat + .stat { border-left: 1px solid var(--line); }
  .stat-num {
    font-size: 2rem; font-weight: 800; color: var(--maroon);
    line-height: 1; letter-spacing: -0.5px;
  }
  .stat-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.58rem; letter-spacing: 0.18em;
    text-transform: uppercase; color: var(--muted); margin-top: 4px;
  }

  /* ── PANEL: light white card (for non-maroon panels) ── */
  .faq-light-card {
    background: var(--card); border-radius: 24px; padding: 2rem;
    border: 1px solid var(--line);
    box-shadow: 0 4px 24px rgba(15,23,42,0.07);
    margin-bottom: 16px;
  }
  .flc-eyebrow {
    font-family: 'DM Mono', monospace; font-size: 0.6rem;
    letter-spacing: 0.22em; text-transform: uppercase;
    color: var(--maroon); margin-bottom: 0.7rem;
  }
  .flc-title {
    font-size: 1.25rem; font-weight: 800; color: var(--ink);
    line-height: 1.2; margin-bottom: 0.7rem; letter-spacing: -0.3px;
  }
  .flc-desc {
    font-size: 0.8rem; color: var(--muted); line-height: 1.75;
    overflow-wrap: break-word;
  }

  /* ── IP TYPE PILLS ── */
  .ip-type-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 8px; margin-top: 1.2rem;
  }
  .ip-type-pill {
    display: flex; align-items: center; gap: 8px;
    background: var(--bg); border: 1.5px solid var(--line);
    border-radius: 12px; padding: 10px 14px;
  }
  .ip-type-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
  }
  .ip-type-name { font-size: 0.75rem; font-weight: 700; color: var(--ink); }

  /* ── ANALYTICS MINI BARS ── */
  .analytics-list { display: flex; flex-direction: column; gap: 10px; margin-top: 1.2rem; }
  .analytics-row { display: flex; flex-direction: column; gap: 4px; }
  .analytics-row-top {
    display: flex; justify-content: space-between; align-items: center;
  }
  .analytics-label { font-size: 0.72rem; font-weight: 700; color: var(--ink); }
  .analytics-val   { font-family: 'DM Mono', monospace; font-size: 0.68rem; color: var(--maroon); font-weight: 700; }
  .analytics-bar-bg {
    height: 6px; background: var(--line); border-radius: 999px; overflow: hidden;
  }
  .analytics-bar-fill {
    height: 100%; border-radius: 999px;
    background: linear-gradient(90deg, var(--maroon), var(--maroon3));
    transition: width 0.8s cubic-bezier(.4,0,.2,1);
  }

  /* ── GUEST ACCESS PANEL ── */
  .access-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0; border-bottom: 1px solid var(--line);
  }
  .access-row:last-child { border-bottom: none; padding-bottom: 0; }
  .access-label { font-size: 0.78rem; font-weight: 600; color: var(--ink); }
  .access-badge-yes { font-size: 0.62rem; font-weight: 800; padding: 2px 9px; border-radius: 999px; background: rgba(16,185,129,.1); color: #059669; }
  .access-badge-no  { font-size: 0.62rem; font-weight: 800; padding: 2px 9px; border-radius: 999px; background: var(--maroon-light); color: var(--maroon); }
  .access-badge-if  { font-size: 0.62rem; font-weight: 800; padding: 2px 9px; border-radius: 999px; background: rgba(245,158,11,.1); color: #d97706; }

  /* ── FRAMEWORK PANEL ── */
  .tech-stack { display: flex; flex-direction: column; gap: 10px; margin-top: 1.2rem; }
  .tech-row {
    display: flex; align-items: center; gap: 12px;
    background: var(--bg); border: 1.5px solid var(--line);
    border-radius: 12px; padding: 11px 14px;
  }
  .tech-icon {
    width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: var(--maroon-light); color: var(--maroon);
  }
  .tech-name  { font-size: 0.78rem; font-weight: 800; color: var(--ink); }
  .tech-desc  { font-size: 0.68rem; color: var(--muted); margin-top: 1px; }

  /* ── FILE DOWNLOAD CARDS (FAQ #2) ── */
  .file-cards { display: flex; flex-direction: column; gap: 12px; }
  .file-card {
    background: var(--card); border-radius: 18px;
    border: 1.5px solid var(--line);
    box-shadow: 0 2px 12px rgba(15,23,42,0.06);
    padding: 16px 18px;
    display: flex;
    align-items: flex-start;
    gap: 12px 14px;
    flex-wrap: wrap;
    transition: border-color 0.18s, box-shadow 0.18s;
  }
  .file-card:hover {
    border-color: rgba(165,44,48,0.25);
    box-shadow: 0 6px 24px rgba(165,44,48,0.10);
  }
  .file-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--maroon), var(--maroon3));
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(165,44,48,0.22);
  }
  .file-icon svg { color: #fff; }
  .file-info {
    flex: 1 1 12rem;
    min-width: 0;
    max-width: 100%;
  }
  .file-name {
    font-size: 0.78rem; font-weight: 800; color: var(--ink);
    overflow-wrap: anywhere;
    line-height: 1.35;
  }
  .file-meta {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; color: var(--muted); margin-top: 3px; letter-spacing: 0.04em;
  }
  .file-actions {
    display: flex;
    gap: 7px;
    flex: 0 0 auto;
    flex-wrap: wrap;
    align-items: center;
  }
  .file-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 13px; border-radius: 9px;
    font-family: inherit; font-size: 0.68rem; font-weight: 700;
    cursor: pointer; border: 1.5px solid; transition: all 0.18s;
    text-decoration: none; white-space: nowrap;
  }
  .file-btn-preview {
    background: var(--maroon-light); color: var(--maroon);
    border-color: rgba(165,44,48,0.2);
  }
  .file-btn-preview:hover { background: var(--maroon); color: #fff; border-color: var(--maroon); }
  .file-btn-download {
    background: var(--bg); color: var(--muted);
    border-color: var(--line);
  }
  .file-btn-download:hover { background: var(--ink); color: #fff; border-color: var(--ink); }

  /* ── FILE PREVIEW MODAL ── */
  .faq-preview-modal {
    position: fixed; inset: 0; z-index: 200;
    background: rgba(15,23,42,0.60); backdrop-filter: blur(8px);
    display: none; align-items: center; justify-content: center; padding: 20px;
  }
  .faq-preview-modal.open { display: flex; }
  .faq-preview-box {
    background: var(--card); border-radius: 22px;
    box-shadow: 0 32px 80px rgba(15,23,42,0.22);
    width: 100%;
    max-width: min(820px, 100vw - 2rem);
    height: min(88vh, 88dvh);
    display: flex; flex-direction: column; overflow: hidden;
    animation: faqModalIn 0.28s cubic-bezier(.17,.67,.35,1.08);
  }
  @keyframes faqModalIn {
    from { opacity:0; transform: translateY(20px) scale(0.97); }
    to   { opacity:1; transform: none; }
  }
  .faq-preview-head {
    padding: 16px 22px;
    background: linear-gradient(135deg, var(--maroon2), var(--maroon3));
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
  }
  .faq-preview-title { font-size: 0.9rem; font-weight: 800; color: #fff; }
  .faq-preview-sub   { font-size: 0.68rem; color: rgba(255,255,255,0.55); margin-top: 2px; }
  .faq-preview-close {
    width: 32px; height: 32px; border-radius: 9px;
    background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: rgba(255,255,255,0.7); font-size: 1rem;
    transition: background 0.15s;
  }
  .faq-preview-close:hover { background: rgba(255,255,255,0.22); }
  .faq-preview-body { flex: 1; overflow: hidden; }
  .faq-preview-body iframe { width: 100%; height: 100%; border: none; display: block; }
  .faq-preview-foot {
    padding: 12px clamp(14px, 3vw, 22px);
    border-top: 1px solid var(--line);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    gap: 10px;
    flex-wrap: wrap;
  }

  /* ── FOOTER ── */
  footer {
    position: relative; z-index: 2;
    padding: 2rem var(--pad-x);
    display: flex; justify-content: space-between; align-items: center;
    border-top: 1px solid var(--line);
    background: rgba(241,244,249,0.8);
    backdrop-filter: blur(20px);
  }
  .footer-left { display: flex; align-items: center; gap: 10px; }
  .footer-badge {
    width: 28px; height: 28px; border-radius: 8px;
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.7rem; color: var(--gold);
  }
  .footer-name {
    font-size: clamp(0.78rem, 0.2vw + 0.74rem, 0.82rem);
    font-weight: 700;
    color: var(--ink);
    overflow-wrap: break-word;
    text-align: inherit;
  }
  .footer-copy {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem; letter-spacing: 0.16em;
    color: var(--muted); text-transform: uppercase;
  }

  /* ── KEYFRAMES ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: none; }
  }
  @keyframes fadeIn {
    from { opacity: 0; } to { opacity: 1; }
  }
  @keyframes drift1 {
    0%, 100% { transform: translate(0,0); }
    50%       { transform: translate(30px, 25px); }
  }
  @keyframes drift2 {
    0%, 100% { transform: translate(0,0); }
    50%       { transform: translate(-25px, -20px); }
  }
  @keyframes scrollPulse {
    0%, 100% { opacity: 1; transform: scaleY(1); }
    50%       { opacity: 0.25; transform: scaleY(0.55); }
  }
  @keyframes rotateSeal {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 1024px) {
    .faq-layout { grid-template-columns: 1fr; gap: 2.5rem; }
    .faq-aside { position: static; }
  }

  @media (max-width: 768px) {
    html { scroll-snap-type: none; }
    section { min-height: min(100vh, 100dvh); }
    .orb-1 { width: 260px; height: 260px; top: -80px; left: -80px; }
    .orb-2 { width: 220px; height: 220px; }
    .orb-3 { width: 160px; height: 160px; }
    .hero-seal { width: 80px; height: 80px; right: 5%; bottom: 10%; }
    .hero-stat { min-width: min(160px, 100%); }
    .stats-bar { flex-direction: column; align-items: stretch; }
    .stat + .stat { border-left: none; border-top: 1px solid var(--line); }
    .ip-type-grid { grid-template-columns: minmax(0, 1fr); }
    .faq-preview-foot { justify-content: center; text-align: center; }
    .faq-preview-foot a { margin: 0 auto; }
    .about-contact-grid { grid-template-columns: minmax(0, 1fr); }
    .about-dark-card { padding: clamp(1.35rem, 4vw, 2.2rem); }
    .faq-info-card { padding: clamp(1.5rem, 4vw, 2.4rem); }
    #plaqueCloseBtn { top: 10px !important; right: 10px !important; }
    .scroll-indicator { bottom: max(1rem, env(safe-area-inset-bottom)); }

    /* ── Contact section: prevent card elongation on mobile ── */
    .about-contact-grid {
      gap: 12px;
    }
    .about-dark-card {
      padding: 1.4rem 1.5rem;
      margin-bottom: 0;
    }
    .about-contact-grid .service-card {
      padding: 1.4rem 1.5rem;
    }
    .about-contact-grid .service-desc {
      font-size: 0.78rem;
      line-height: 1.6;
      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .about-contact-grid .service-icon-wrap {
      width: 38px; height: 38px;
      margin-bottom: 0.8rem;
    }
    .about-contact-grid .service-name {
      font-size: 0.92rem;
      margin-bottom: 0.4rem;
    }
    .about-contact-grid .service-tag {
      margin-top: 0.8rem;
    }
  }

  @media (max-width: 900px) {
    .nav-toggle { display: flex; }
    .nav-logo-sub { display: none; }
    .nav-links {
      position: fixed;
      left: 0;
      right: 0;
      top: 68px;
      flex-direction: column;
      align-items: stretch;
      gap: 0.35rem;
      padding: 1rem var(--pad-x) 1.15rem;
      background: rgba(241,244,249,0.97);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--line);
      box-shadow: 0 12px 32px rgba(15,23,42,0.12);
      transform: translateY(-120%);
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
      transition: transform 0.3s cubic-bezier(.17,.67,.35,1.05), opacity 0.22s ease, visibility 0.22s;
      z-index: 99;
    }
    body.has-maint-banner .nav-links { top: 117px; }
    nav.nav-open .nav-links {
      transform: translateY(0);
      opacity: 1;
      visibility: visible;
      pointer-events: auto;
    }
    .nav-links a {
      width: 100%;
      max-width: 16rem;
      margin-left: auto;
      margin-right: auto;
      box-sizing: border-box;
    }
    body.nav-open-menu { overflow: hidden; }
    footer { flex-direction: column; gap: 0.65rem; text-align: center; }
    .hero-stats { gap: 0.55rem 0.65rem; }
    .file-actions {
      width: 100%;
      justify-content: flex-start;
    }
  }

  /* ── ABOUT SECTION ── */
  #about {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: clamp(5rem, 12vw, 8rem) var(--pad-x) clamp(4rem, 8vw, 6rem);
  }
  #about > :not(.bg-layer):not(.bg-overlay) {
    width: 100%;
    max-width: var(--shell-max);
  }
  #about .bg-overlay {
    background: linear-gradient(160deg,
      rgba(241,244,249,0.94) 0%,
      rgba(220,210,200,0.40) 60%,
      rgba(241,244,249,0.90) 100%);
  }

  .about-dark-card {
    position: relative; z-index: 2;
    background: linear-gradient(135deg, var(--maroon2) 0%, var(--maroon3) 55%, var(--maroon) 100%);
    border-radius: 24px; padding: 2.2rem 2.4rem;
    box-shadow: 0 12px 40px rgba(165,44,48,0.28);
    overflow: hidden; margin-bottom: 16px;
  }
  .about-dark-card::before {
    content: ''; position: absolute; top: -40px; right: -40px;
    width: 200px; height: 200px; border-radius: 50%;
    background: rgba(255,255,255,0.04); pointer-events: none;
  }
  .about-dark-card::after {
    content: ''; position: absolute; bottom: -60px; left: 35%;
    width: 260px; height: 260px; border-radius: 50%;
    background: rgba(255,255,255,0.03); pointer-events: none;
  }
  .about-dark-inner { position: relative; z-index: 1; }

  .about-step-card {
    position: relative; z-index: 2;
    background: var(--card); border: 1px solid var(--line); border-radius: 20px;
    padding: 1.8rem; box-shadow: 0 2px 12px rgba(15,23,42,0.05);
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .about-step-card:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(15,23,42,0.09); }

  .about-step-num {
    width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-family: 'DM Mono', monospace; font-size: 0.7rem; font-weight: 500;
  }
  .about-step-num-maroon { background: linear-gradient(135deg, var(--maroon2), var(--maroon)); color: var(--gold); }
  .about-step-num-gold   { background: linear-gradient(135deg, var(--gold3), var(--gold)); color: #2a1a0b; }

  .about-panel {
    position: relative; z-index: 2;
    background: var(--card); border: 1px solid var(--line);
    border-radius: 20px; padding: 1.8rem;
    box-shadow: 0 2px 12px rgba(15,23,42,0.05);
  }

  .about-contact-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    position: relative;
    z-index: 2;
  }

  @media (max-width: 480px) {
    .about-contact-grid {
      gap: 10px;
    }
    .about-dark-card {
      padding: 1.2rem 1.3rem;
    }
    .about-contact-grid .service-card {
      padding: 1.2rem 1.3rem;
    }
    .about-contact-grid .service-desc {
      -webkit-line-clamp: 3;
    }
  }

  /* ── INVENTION DISCLOSURE STEPS ── */
  .idi-steps-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    position: relative; z-index: 2;
    margin-bottom: 16px;
  }
  .idi-steps-bottom {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    position: relative; z-index: 2;
    margin-bottom: 16px;
  }
  .idi-steps-col {
    display: flex; flex-direction: column; gap: 16px;
  }
  @media (max-width: 900px) {
    .idi-steps-row { grid-template-columns: minmax(0, 1fr); }
    .idi-steps-bottom { grid-template-columns: minmax(0, 1fr); }
  }
  @media (min-width: 601px) and (max-width: 900px) {
    .idi-steps-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
  }
  /* ── MAINTENANCE BANNER ── */
  .maint-banner {
    position: fixed; top: 0; left: 0; right: 0; z-index: 200;
    background: linear-gradient(90deg, #92400e 0%, #b45309 50%, #92400e 100%);
    border-bottom: 1px solid rgba(245,158,11,.35);
    display: flex; align-items: center; justify-content: center; gap: 12px;
    padding: 10px 20px;
    box-shadow: 0 4px 24px rgba(180,83,9,.28);
    animation: bannerSlideDown .5s cubic-bezier(.17,.67,.35,1.05) forwards;
  }
  @keyframes bannerSlideDown {
    from { opacity: 0; transform: translateY(-100%); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .maint-banner.hiding {
    animation: bannerSlideUp .3s ease-out forwards;
  }
  @keyframes bannerSlideUp {
    from { opacity: 1; transform: translateY(0); }
    to   { opacity: 0; transform: translateY(-100%); }
  }
  .maint-banner-icon {
    width: 28px; height: 28px; border-radius: 8px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; color: #fde68a;
  }
  .maint-banner-body {
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; justify-content: center;
  }
  .maint-banner-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem; font-weight: 800;
    color: #fef3c7; letter-spacing: .01em;
  }
  .maint-banner-sep {
    width: 1px; height: 14px;
    background: rgba(255,255,255,.25); flex-shrink: 0;
  }
  .maint-banner-time {
    font-size: 0.72rem; color: rgba(255,255,255,.75); font-weight: 500;
  }
  .maint-banner-countdown {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.15);
    border-radius: 20px; padding: 3px 12px;
    font-family: 'DM Mono', monospace;
    font-size: 0.78rem; font-weight: 800;
    color: #fde68a; letter-spacing: .06em;
  }
  .maint-banner-dismiss {
    position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
    width: 26px; height: 26px; border-radius: 7px;
    background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: rgba(255,255,255,.6);
    transition: background .15s, color .15s;
  }
  .maint-banner-dismiss:hover { background: rgba(0,0,0,.35); color: #fff; }
  body.has-maint-banner nav { top: 49px; }
  body.has-maint-banner { padding-top: 49px; }

</style>
</head>
<body>

<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>

@if(!empty($scheduledAt))
{{-- ── MAINTENANCE NOTICE BANNER ── --}}
<div class="maint-banner" id="maintBanner">
  <div class="maint-banner-icon">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
      <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
  </div>
  <div class="maint-banner-body">
    <span class="maint-banner-title">Scheduled Maintenance</span>
    <div class="maint-banner-sep"></div>
    <span class="maint-banner-time">
      System going down on {{ \Carbon\Carbon::parse($scheduledAt)->format('M d, Y \a\t g:i A') }}
    </span>
    <div class="maint-banner-sep"></div>
    <span class="maint-banner-countdown" id="maintCountdown">
      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="maintCountdownVal">--:--:--</span>
    </span>
  </div>
  <button class="maint-banner-dismiss" id="maintDismiss" title="Dismiss">✕</button>
</div>
<script>
  // Add body class immediately so nav shifts down
  document.body.classList.add('has-maint-banner');
</script>
@endif

<!-- NAV -->
<nav id="mainNav" aria-label="Primary">
  <a href="#hero" class="nav-logo">
    <div class="nav-logo-badge">K</div>
    <div>
      <div class="nav-logo-text">KTTM</div>
      <div class="nav-logo-sub">IP Records System</div>
    </div>
  </a>
  <button type="button" class="nav-toggle" id="navMenuToggle" aria-expanded="false" aria-controls="primaryNav" aria-label="Open menu">
    <span class="nav-toggle-bar" aria-hidden="true"></span>
  </button>
  <div class="nav-links" id="primaryNav" role="navigation">
    <a href="#hero">Home</a>
    <a href="#services">Services</a>
    <a href="#about">About</a>
    <a href="#faq">FAQ</a>
    <a href="#" class="nav-cta" id="openLogin">Sign In</a>
  </div>
</nav>

<!-- ==================== SECTION 1: HERO ==================== -->
<section id="hero">
  <div class="bg-layer"></div>
  <div class="bg-overlay"></div>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
  <div class="orb orb-3"></div>

  <div class="hero-content">
    <div class="hero-eyebrow">Intellectual Property Records System — v2.0</div>

    <h1 class="hero-title">
      <span class="accent">KTTM</span>
      <span class="light">IP Records Office</span>
    </h1>

    <p class="hero-subtitle">
      A centralized platform for managing, searching, and safeguarding intellectual property filings — built exclusively for the KTTM Office.
    </p>

    <div class="hero-actions">
      <a href="#" class="btn-primary" id="openLogin2">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        Access System
      </a>
      <button id="viewPlaqueBtn" class="btn-ghost">View Office Plaque →</button>
    </div>

    {{-- filter form for month/year — no reload --}}
  <div id="statsFilter" class="hero-filter">
    <select id="filterMonth">
      <option value="">All months</option>
      @foreach(range(1,12) as $m)
        <option value="{{ $m }}" {{ (isset($selectedMonth) && $selectedMonth == $m) ? 'selected' : '' }}>
          {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
        </option>
      @endforeach
    </select>
    <select id="filterYear">
      <option value="">All years</option>
      @foreach($years as $y)
        <option value="{{ $y }}" {{ (isset($selectedYear) && $selectedYear == $y) ? 'selected' : '' }}>{{ $y }}</option>
      @endforeach
    </select>
    <button type="button" id="filterBtn" class="btn-primary" onclick="applyStatsFilter()">Go</button>
  </div>

  <div class="hero-filter-info" id="filterInfo" style="{{ (isset($selectedYear) && $selectedYear) || (isset($selectedMonth) && $selectedMonth) ? '' : 'display:none;' }}">
    Showing <span id="filterInfoText">
      @if(isset($selectedMonth) && $selectedMonth){{ \Carbon\Carbon::createFromDate(null,$selectedMonth,1)->format('F') }}@endif
      @if(isset($selectedMonth) && $selectedMonth && isset($selectedYear) && $selectedYear) of @endif
      @if(isset($selectedYear) && $selectedYear){{ $selectedYear }}@endif
    </span>
  </div>

  <div class="hero-stats" id="heroStats">
    <div class="hero-stat">
      <div class="hero-stat-num" id="statTotal">{{ $stats['active'] }}</div>
      <div class="hero-stat-label">Total Records</div>
    </div>
    @foreach([ 'patent' => 'Patent', 'copyright' => 'Copyright', 'utility' => 'Utility Model', 'design' => 'Industrial Design' ] as $key => $label)
      <div class="hero-stat">
        <div class="hero-stat-num stat-count" data-key="{{ $key }}">{{ $stats[$key]['count'] }}</div>
        <div class="hero-stat-label stat-label" data-key="{{ $key }}">{{ $label }} &ndash; {{ $stats[$key]['percent'] }}%</div>
      </div>
    @endforeach
  </div>
  </div>

  <!-- Decorative seal -->
  <div class="hero-seal">
    <svg class="seal-svg" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="65" cy="65" r="60" stroke="#A52C30" stroke-width="0.8" stroke-dasharray="3 5" opacity="0.4"/>
      <circle cx="65" cy="65" r="50" stroke="#F0C860" stroke-width="0.5" opacity="0.3"/>
      <text font-family="DM Mono" font-size="7.5" fill="#A52C30" letter-spacing="4" opacity="0.6">
        <textPath href="#circlePath">KTTM  ·  INTELLECTUAL  PROPERTY  ·  RECORDS  ·  </textPath>
      </text>
      <defs>
        <path id="circlePath" d="M 65,65 m -44,0 a 44,44 0 1,1 88,0 a 44,44 0 1,1 -88,0"/>
      </defs>
      <text x="65" y="60" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="13" fill="#0F172A" font-weight="800" letter-spacing="-0.5">KTTM</text>
      <text x="65" y="74" text-anchor="middle" font-family="DM Mono, monospace" font-size="5.5" fill="#A52C30" letter-spacing="2.5">OFFICE</text>
    </svg>
  </div>

  <div class="scroll-indicator">
    <span>Scroll</span>
    <div class="scroll-line"></div>
  </div>
</section>

<!-- LOGIN MODAL -->
<div class="login-modal" id="loginModal">
  <div class="modal-card" id="modalCard">
    <button class="modal-close" id="closeLogin">✕</button>
    <div class="modal-badge">K</div>
    <p class="modal-eyebrow">Secure Access</p>
    <h2 class="modal-title">Sign In</h2>
    <p class="modal-sub">Access the KTTM IP Records System</p>

    {{-- Error alert --}}
    @if(session('login_error'))
    <div class="login-alert" id="loginAlert">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('login_error') }}
    </div>
    @endif
    <div class="login-alert" id="loginAlertJs" style="display:none;">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <span id="loginAlertText">Invalid email or password.</span>
    </div>

    <div class="auth-fields">
      <div class="field">
        <label>Email Address</label>
        <input type="email" id="loginEmail" placeholder="e.g. itso@g.batstate-u.edu.ph" autocomplete="email">
      </div>
      <div class="field">
        <label>Password</label>
        <div style="position:relative;">
          <input type="password" id="loginPassword" placeholder="••••••••••" autocomplete="current-password" style="padding-right:2.8rem;">
          <button type="button" id="loginPwToggle" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);display:flex;align-items:center;padding:0;">
            <svg id="loginEyeIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>
    </div>

    <button class="btn-login" id="loginBtn">
      <span id="loginBtnText">Sign In →</span>
      <span id="loginBtnSpinner" style="display:none;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 0.8s linear infinite;"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
      </span>
    </button>
    <hr class="modal-divider">
    <p class="modal-hint">Guest access available — <a href="/guest" style="color:var(--maroon);font-weight:700;text-decoration:none;">Browse without login →</a></p>
  </div>
</div>

<style>
  .login-alert {
    display: flex; align-items: center; gap: 8px;
    background: rgba(165,44,48,0.08);
    border: 1px solid rgba(165,44,48,0.22);
    color: var(--maroon); border-radius: 10px;
    padding: 0.65rem 1rem; font-size: 0.78rem; font-weight: 600;
    margin-bottom: 1.2rem;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>

<!-- ==================== SECTION 2: SERVICES ==================== -->
<section id="services">
  <div class="bg-layer" style="opacity:0.35;"></div>
  <div class="bg-overlay"></div>

  <div class="section-header">
    <span class="section-label">What We Offer</span>
    <h2 class="section-title">System <span class="accent">Services</span></h2>
    <p class="section-desc">A complete digital infrastructure for intellectual property records — built for accuracy, access, and accountability.</p>
  </div>

  <div class="services-grid">

    <div class="service-card">
      <div class="service-num">01</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
        </svg>
      </div>
      <h3 class="service-name">IP Record Management</h3>
      <p class="service-desc">Create, update, and delete intellectual property filings — patents, trademarks, copyrights — with full audit trails.</p>
      <span class="service-tag">Admin Only</span>
    </div>

    <div class="service-card">
      <div class="service-num">02</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </div>
      <h3 class="service-name">Search & Browse</h3>
      <p class="service-desc">Advanced filtering and full-text search across all registered IP records. Available to both admins and guest users for transparency.</p>
      <span class="service-tag">All Users</span>
    </div>

    <div class="service-card">
      <div class="service-num">03</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
        </svg>
      </div>
      <h3 class="service-name">Analytics Dashboard</h3>
      <p class="service-desc">Visual graphs and trend analysis on IP filings — by category, date, status, and ownership. Exclusive to administrators.</p>
      <span class="service-tag">Admin Only</span>
    </div>

    <div class="service-card">
      <div class="service-num">04</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>
        </svg>
      </div>
      <h3 class="service-name">Access Control</h3>
      <p class="service-desc">Two-tier access model — Administrator with full CRUD privileges, and Guest with read-only search. No registration needed for guests.</p>
      <span class="service-tag">System Level</span>
    </div>

    <div class="service-card">
      <div class="service-num">05</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
      </div>
      <h3 class="service-name">Secure Digitalization</h3>
      <p class="service-desc">Migrate physical IP records into a structured, searchable digital format — reducing paper dependency and streamlining office operations.</p>
      <span class="service-tag">Core Feature</span>
    </div>

    <div class="service-card">
      <div class="service-num">06</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
        </svg>
      </div>
      <h3 class="service-name">Built on Laravel</h3>
      <p class="service-desc">Powered by Laravel with Blade templating — scalable, maintainable, and designed for the KTTM Office infrastructure.</p>
      <span class="service-tag">Technology</span>
    </div>

  </div>
</section>

<!-- ==================== SECTION 2.5: ABOUT KTTM ==================== -->
<section id="about">
  <div class="bg-layer" style="opacity:0.28;"></div>
  <div class="bg-overlay"></div>

  <!-- ── What KTTM Does ── -->
  <div class="section-header">
    <span class="section-label">About KTTM</span>
    <h2 class="section-title">What <span class="accent">KTTM</span> Does</h2>
    <p class="section-desc">Core responsibilities and support services for intellectual property management at BatStateU.</p>
  </div>

  <div class="services-grid">
    <div class="service-card">
      <div class="service-num">01</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>
        </svg>
      </div>
      <h3 class="service-name">Implement Policies</h3>
      <p class="service-desc">Approved policies on Intellectual Property, Technology Transfer Protocol, and Spin-off of the University.</p>
      <span class="service-tag">Policy</span>
    </div>
    <div class="service-card">
      <div class="service-num">02</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </div>
      <h3 class="service-name">Evaluate Technologies</h3>
      <p class="service-desc">Technology assessment of funded projects for potential IP protection and commercialization.</p>
      <span class="service-tag">Assessment</span>
    </div>
    <div class="service-card">
      <div class="service-num">03</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
        </svg>
      </div>
      <h3 class="service-name">Provide Training</h3>
      <p class="service-desc">Technical training and assistance in IP protection, licensing, and commercialization for the university community.</p>
      <span class="service-tag">Training</span>
    </div>
    <div class="service-card">
      <div class="service-num">04</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
        </svg>
      </div>
      <h3 class="service-name">Facilitate Filing</h3>
      <p class="service-desc">Filing potential IP to IPOPHL and the National Library of the Philippines (NLP) on behalf of inventors and authors.</p>
      <span class="service-tag">Filing</span>
    </div>
    <div class="service-card">
      <div class="service-num">05</div>
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7H14a3.5 3.5 0 010 7H6"/>
        </svg>
      </div>
      <h3 class="service-name">Process Claims</h3>
      <p class="service-desc">Handles IP incentive claims properly applied or granted in accordance with the BatStateU Technology Transfer Protocol.</p>
      <span class="service-tag">Claims</span>
    </div>
  </div>

  <!-- ── Invention Disclosure Incentive header ── -->
  <div class="section-header" style="margin-top:5rem;">
    <span class="section-label">Incentives</span>
    <h2 class="section-title">Invention Disclosure <span class="accent">Incentive</span></h2>
    <p class="section-desc">Updated flow — consultation first, then formal request and internal routing through to university approval.</p>
  </div>

  <!-- Steps 1–3 row -->
  <div class="idi-steps-row">
    <div class="about-step-card" style="border-top:3px solid var(--maroon);">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
        <div class="about-step-num about-step-num-maroon">01</div>
        <span style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.18em;text-transform:uppercase;color:var(--maroon);">Step 1</span>
      </div>
      <h3 class="service-name">Consultation</h3>
      <p class="service-desc">The inventor/author consults KTTM for guidance on the appropriate IP route, requirements, and preparation of the request and attachments.</p>
    </div>
    <div class="about-step-card" style="border-top:3px solid var(--maroon);">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
        <div class="about-step-num about-step-num-maroon">02</div>
        <span style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.18em;text-transform:uppercase;color:var(--maroon);">Step 2</span>
      </div>
      <h3 class="service-name">Request</h3>
      <p class="service-desc">The request letter for IP incentive and necessary attachments — Certificate of Registration from IPOPHL and Authority to Collect — are prepared and received from the inventor/author.</p>
    </div>
    <div class="about-step-card" style="border-top:3px solid var(--maroon);">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
        <div class="about-step-num about-step-num-maroon">03</div>
        <span style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.18em;text-transform:uppercase;color:var(--maroon);">Step 3</span>
      </div>
      <h3 class="service-name">Submission & Verification</h3>
      <p class="service-desc">The submitted letter and documents are reviewed and verified. If there is missing information, the inventor/author will be informed immediately to comply with requirements.</p>
    </div>
  </div>

  <!-- Eligibility banner (dark maroon) -->
  <div class="about-dark-card">
    <div class="about-dark-inner" style="display:flex;align-items:start;gap:16px;flex-wrap:wrap;">
      <div style="width:44px;height:44px;border-radius:13px;background:rgba(240,200,96,.18);border:1px solid rgba(240,200,96,.35);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <div>
        <div style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:.45rem;">Eligibility</div>
        <div style="font-size:.9rem;font-weight:700;color:#fff;line-height:1.65;max-width:76ch;">All full-time researchers, faculty, students, and employees affiliated with BatStateU The NEU during the time of the application, and who served as the inventor/author of the invention, may file the claim.</div>
      </div>
    </div>
  </div>

  <!-- Steps 4–5 + timeline overview -->
  <div class="idi-steps-bottom">

    <div class="idi-steps-col">
      <div class="about-step-card" style="border-top:3px solid var(--gold2);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
          <div class="about-step-num about-step-num-gold">04</div>
          <span style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold3);">Step 4</span>
        </div>
        <h3 class="service-name">Endorsement</h3>
        <p class="service-desc">The Assistant Director of Knowledge and Technology Transfer Management prepares an endorsement letter indicating the request for IP incentive.</p>
      </div>
      <div class="about-step-card" style="border-top:3px solid var(--gold2);">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
          <div class="about-step-num about-step-num-gold">05</div>
          <span style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.18em;text-transform:uppercase;color:var(--gold3);">Step 5</span>
        </div>
        <h3 class="service-name">Sent for Review & Approval</h3>
        <p class="service-desc">The endorsement letter and supporting documents are forwarded to the RMS Director for review, then routed to the Office of the University President for final approval.</p>
      </div>
    </div>

    <!-- Timeline overview card (dark maroon, matches faq-info-card) -->
    <div class="about-dark-card" style="margin-bottom:0;">
      <div class="about-dark-inner">
        <div style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:.7rem;">Flow Overview</div>
        <div style="font-size:1.15rem;font-weight:800;color:#fff;letter-spacing:-.3px;margin-bottom:.5rem;">Consultation first,<br>then formal routing.</div>
        <div style="font-size:.78rem;color:rgba(255,255,255,.62);margin-bottom:1.8rem;line-height:1.65;">From inventor to KTTM to the University President — a clear, structured path.</div>
        <!-- Timeline -->
        <div style="position:relative;padding-left:1.8rem;">
          <div style="position:absolute;left:.55rem;top:6px;bottom:6px;width:2px;background:linear-gradient(180deg,var(--gold),rgba(240,200,96,.15));border-radius:2px;"></div>
          <div style="margin-bottom:1.1rem;position:relative;">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--gold);box-shadow:0 0 0 3px rgba(240,200,96,.25);position:absolute;left:-1.25rem;top:5px;"></div>
            <div style="font-size:.8rem;font-weight:700;color:#fff;padding-left:.1rem;">Step 1 · Consultation</div>
            <div style="font-size:.7rem;color:rgba(255,255,255,.52);margin-top:1px;">Inventor/author consults KTTM.</div>
          </div>
          <div style="margin-bottom:1.1rem;position:relative;">
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(240,200,96,.55);box-shadow:0 0 0 3px rgba(240,200,96,.18);position:absolute;left:-1.25rem;top:5px;"></div>
            <div style="font-size:.8rem;font-weight:700;color:#fff;">Steps 2–3 · Request + Verification</div>
            <div style="font-size:.7rem;color:rgba(255,255,255,.52);margin-top:1px;">Submit and verify all documents.</div>
          </div>
          <div style="margin-bottom:1.1rem;position:relative;">
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.35);box-shadow:0 0 0 3px rgba(255,255,255,.12);position:absolute;left:-1.25rem;top:5px;"></div>
            <div style="font-size:.8rem;font-weight:700;color:#fff;">Step 4 · Endorsement</div>
            <div style="font-size:.7rem;color:rgba(255,255,255,.52);margin-top:1px;">Prepared by KTTM Assistant Director.</div>
          </div>
          <div style="position:relative;">
            <div style="width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.18);box-shadow:0 0 0 3px rgba(255,255,255,.08);position:absolute;left:-1.25rem;top:5px;"></div>
            <div style="font-size:.8rem;font-weight:700;color:#fff;">Step 5 · Review → Approval</div>
            <div style="font-size:.7rem;color:rgba(255,255,255,.52);margin-top:1px;">RMS Director to University President.</div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Coverage note -->
  <div class="about-panel" style="margin-bottom:16px;display:flex;align-items:start;gap:14px;">
    <div style="width:44px;height:44px;border-radius:13px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    </div>
    <div>
      <div style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.2em;text-transform:uppercase;color:var(--maroon);margin-bottom:.4rem;">Coverage</div>
      <p class="service-desc" style="max-width:none;">The claim applies to every invention, utility model, and industrial design applied or granted with BatStateU The NEU as the assignee of the invention. For copyright and trademark, the university handles the logistics and shoulders the filing fee only.</p>
    </div>
  </div>

  <!-- ── Contact ── -->
  <div class="section-header" style="margin-top:4.5rem;">
    <span class="section-label">Contact</span>
    <h2 class="section-title">About Us / <span class="accent">Contacts</span></h2>
    <p class="section-desc">For inquiries, coordination, and support related to IP protection and incentives.</p>
  </div>

  <div class="about-contact-grid">
    <!-- Email (dark maroon card, matching faq-info-card style) -->
    <div class="about-dark-card" style="margin-bottom:0;">
      <div class="about-dark-inner">
        <div style="width:44px;height:44px;border-radius:13px;background:rgba(240,200,96,.18);border:1px solid rgba(240,200,96,.35);display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
          <svg width="20" height="20" fill="none" stroke="var(--gold)" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
        </div>
        <div style="font-family:'DM Mono',monospace;font-size:.6rem;letter-spacing:.22em;text-transform:uppercase;color:var(--gold);margin-bottom:.5rem;">Email</div>
        <a href="mailto:itso@g.batstate-u.edu.ph" style="font-size:1.05rem;font-weight:800;color:#fff;text-decoration:none;letter-spacing:-.2px;display:block;margin-bottom:.4rem;">itso@g.batstate-u.edu.ph</a>
        <div style="font-size:.75rem;color:rgba(255,255,255,.52);">Click to open in your mail client.</div>
      </div>
    </div>
    <!-- Quick tip (white service-card style) -->
    <div class="service-card" style="cursor:default;">
      <div class="service-icon-wrap">
        <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
      </div>
      <h3 class="service-name">Using This Guide</h3>
      <p class="service-desc">Refer back to this page whenever you need a refresher on KTTM services, the IDI flow, or how to start an IP request. Use the navigation links to jump to each major section. For additional assistance, send us an email via the contact details on this page.</p>
      <span class="service-tag">Quick Tip</span>
    </div>
  </div>

</section>

<!-- ==================== SECTION 3: FAQ ==================== -->
<section id="faq">
  <div class="bg-layer" style="opacity:0.28; transform: scaleX(-1);"></div>
  <div class="bg-overlay"></div>

  <div class="section-header">
    <span class="section-label">Help & Guidance</span>
    <h2 class="section-title">Frequently <span class="accent">Asked</span></h2>
  </div>

  <div class="faq-layout">

    {{-- LEFT: question list --}}
    <div class="faq-list">
      <div class="faq-item open" data-faq="1">
        <div class="faq-question" onclick="selectFaq(this, 1)">
          <span class="faq-q-text">Who can access this system?</span>
          <div class="faq-toggle">+</div>
        </div>
      </div>
      <div class="faq-item" data-faq="2">
        <div class="faq-question" onclick="selectFaq(this, 2)">
          <span class="faq-q-text">What documents do I need to fill up when filing for registration?</span>
          <div class="faq-toggle">+</div>
        </div>
      </div>
      <div class="faq-item" data-faq="3">
        <div class="faq-question" onclick="selectFaq(this, 3)">
          <span class="faq-q-text">What types of intellectual property are recorded?</span>
          <div class="faq-toggle">+</div>
        </div>
      </div>
      <div class="faq-item" data-faq="4">
        <div class="faq-question" onclick="selectFaq(this, 4)">
          <span class="faq-q-text">What analytics are available to admins?</span>
          <div class="faq-toggle">+</div>
        </div>
      </div>
      <div class="faq-item" data-faq="5">
        <div class="faq-question" onclick="selectFaq(this, 5)">
          <span class="faq-q-text">Where is KTTM Office Located?</span>
          <div class="faq-toggle">+</div>
        </div>
      </div>
    </div>

    {{-- RIGHT: dynamic panel --}}
    <div class="faq-aside">
      <div class="faq-panel-wrap">

        {{-- Panel 1: Who can access --}}
        <div class="faq-panel active" id="faq-panel-1">
          <div class="faq-info-card">
            <p class="fic-label">System Access</p>
            <h3 class="fic-title">Two Roles.<br>One Platform.</h3>
            <p class="fic-desc">The KTTM IP Records System is designed around a clear, secure access model — powerful tools for administrators, transparent access for the public.</p>
            <div class="fic-roles">
              <span class="role-pill admin">Administrator</span>
              <span class="role-pill">Guest / Public</span>
            </div>
          </div>
          <div class="stats-bar">
            <div class="stat"><div class="stat-num">2</div><div class="stat-label">Roles</div></div>
            <div class="stat"><div class="stat-num">∞</div><div class="stat-label">Records</div></div>
            <div class="stat"><div class="stat-num">1</div><div class="stat-label">Office</div></div>
          </div>
        </div>

        {{-- Panel 2: Documents / downloadable forms --}}
        <div class="faq-panel" id="faq-panel-2">
          <div class="faq-light-card" style="margin-bottom:14px;">
            <div class="flc-eyebrow">Required Forms</div>
            <div class="flc-title">Download &amp; Fill Up</div>
            <div class="flc-desc">Click <strong>Preview</strong> to view the form, or <strong>Download</strong> to save the file to your device.</div>
          </div>
          <div class="file-cards">

            {{-- Form 1 --}}
            <div class="file-card">
              <div class="file-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div class="file-info">
                <div class="file-name">IP Evaluation Form</div>
                <div class="file-meta">BatStateU-FO-RMS-05 · Rev. 02 · .docx</div>
              </div>
              <div class="file-actions">
                <button class="file-btn file-btn-preview faq-preview-btn"
                  data-preview="/forms/BatStateU-FO-RMS-05_Intellectual_Property_Evaluation_Form_Rev._02.pdf"
                  data-title="IP Evaluation Form — BatStateU-FO-RMS-05">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Preview
                </button>
                <a class="file-btn file-btn-download"
                  href="/images/BatStateU-FO-RMS-05_Intellectual Property Evaluation Form_Rev. 02.doc"
                  download>
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Download
                </a>
              </div>
            </div>

            {{-- Form 2 --}}
            <div class="file-card">
              <div class="file-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div class="file-info">
                <div class="file-name">Invention Disclosure Form</div>
                <div class="file-meta">BatStateU-FO-RMS-08 · .docx</div>
              </div>
              <div class="file-actions">
                <button class="file-btn file-btn-preview faq-preview-btn"
                  data-preview="/forms/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.pdf"
                  data-title="Invention Disclosure Form — BatStateU-FO-RMS-08">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Preview
                </button>
                <a class="file-btn file-btn-download"
                  href="/images/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.docx"
                  download>
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Download
                </a>
              </div>
            </div>

            {{-- Form 3 --}}
            <div class="file-card">
              <div class="file-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                  <polyline points="14 2 14 8 20 8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
              </div>
              <div class="file-info">
                <div class="file-name">Copyright Forms</div>
                <div class="file-meta">Copyright Registration · .docx</div>
              </div>
              <div class="file-actions">
                <button class="file-btn file-btn-preview faq-preview-btn"
                  data-preview="/forms/Copyright_Forms.pdf"
                  data-title="Copyright Forms">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  Preview
                </button>
                <a class="file-btn file-btn-download"
                  href="/images/Copyright Forms.docx"
                  download>
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  Download
                </a>
              </div>
            </div>

          </div>
        </div>

        {{-- Panel 3: IP Types --}}
        <div class="faq-panel" id="faq-panel-3">
          <div class="faq-light-card">
            <div class="flc-eyebrow">IP Categories</div>
            <div class="flc-title">5 Types of IP<br>Recorded.</div>
            <div class="flc-desc">KTTM tracks all major categories of intellectual property registered under BatStateU.</div>
            <div class="ip-type-grid">
              <div class="ip-type-pill"><div class="ip-type-dot" style="background:#A52C30;"></div><span class="ip-type-name">Patent</span></div>
              <div class="ip-type-pill"><div class="ip-type-dot" style="background:#2563EB;"></div><span class="ip-type-name">Utility Model</span></div>
              <div class="ip-type-pill"><div class="ip-type-dot" style="background:#D97706;"></div><span class="ip-type-name">Trademark</span></div>
              <div class="ip-type-pill"><div class="ip-type-dot" style="background:#059669;"></div><span class="ip-type-name">Copyright</span></div>
              <div class="ip-type-pill" style="grid-column:span 2;"><div class="ip-type-dot" style="background:#7C3AED;"></div><span class="ip-type-name">Industrial Design</span></div>
            </div>
          </div>
          <div class="stats-bar">
            <div class="stat"><div class="stat-num">5</div><div class="stat-label">IP Types</div></div>
            <div class="stat"><div class="stat-num">1</div><div class="stat-label">Registry</div></div>
            <div class="stat"><div class="stat-num">∞</div><div class="stat-label">Records</div></div>
          </div>
        </div>

        {{-- Panel 4: Analytics --}}
        <div class="faq-panel" id="faq-panel-4">
          <div class="faq-info-card">
            <p class="fic-label">Admin Dashboard</p>
            <h3 class="fic-title">Live Data.<br>Smart Charts.</h3>
            <p class="fic-desc">Administrators get a real-time analytics dashboard with interactive charts, filters, and trend data pulled directly from the records database.</p>
          </div>
          <div class="faq-light-card">
            <div class="flc-eyebrow">Available Metrics</div>
            <div class="analytics-list">
              <div class="analytics-row">
                <div class="analytics-row-top"><span class="analytics-label">Filings by Category</span><span class="analytics-val">Charts</span></div>
                <div class="analytics-bar-bg"><div class="analytics-bar-fill" style="width:90%;"></div></div>
              </div>
              <div class="analytics-row">
                <div class="analytics-row-top"><span class="analytics-label">Registration Trends</span><span class="analytics-val">Timeline</span></div>
                <div class="analytics-bar-bg"><div class="analytics-bar-fill" style="width:75%;"></div></div>
              </div>
              <div class="analytics-row">
                <div class="analytics-row-top"><span class="analytics-label">Status Breakdown</span><span class="analytics-val">Live</span></div>
                <div class="analytics-bar-bg"><div class="analytics-bar-fill" style="width:85%;"></div></div>
              </div>
              <div class="analytics-row">
                <div class="analytics-row-top"><span class="analytics-label">Ownership Distribution</span><span class="analytics-val">Campus</span></div>
                <div class="analytics-bar-bg"><div class="analytics-bar-fill" style="width:65%;"></div></div>
              </div>
            </div>
          </div>
        </div>

        {{-- Panel 5: Office Location --}}
        <div class="faq-panel" id="faq-panel-5">
          <div class="faq-info-card" style="margin-bottom:14px;">
            <p class="fic-label">Office Location</p>
            <h3 class="fic-title">Find Us<br>On Campus.</h3>
            <p class="fic-desc">Visit us in person for IP filing assistance, consultations, and document submission.</p>
          </div>
          <div class="faq-light-card" style="padding:0;overflow:hidden;">

            {{-- Map placeholder header --}}
            <div style="background:linear-gradient(135deg,#e8edf5 0%,#dde4ef 100%);height:130px;position:relative;display:flex;align-items:center;justify-content:center;overflow:hidden;">
              {{-- Grid lines decoration --}}
              <svg style="position:absolute;inset:0;width:100%;height:100%;opacity:.18;" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <pattern id="locgrid" width="24" height="24" patternUnits="userSpaceOnUse">
                    <path d="M 24 0 L 0 0 0 24" fill="none" stroke="#A52C30" stroke-width="0.8"/>
                  </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#locgrid)"/>
              </svg>
              {{-- Building icon --}}
              <div style="position:relative;z-index:2;display:flex;flex-direction:column;align-items:center;gap:8px;">
                <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,var(--maroon),var(--maroon3));display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(165,44,48,.32);">
                  <svg width="26" height="26" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 24 24">
                    <rect x="2" y="7" width="20" height="15" rx="1"/>
                    <path d="M16 22V12H8v10"/>
                    <path d="M2 7l10-5 10 5"/>
                    <line x1="12" y1="2" x2="12" y2="7"/>
                  </svg>
                </div>
                <div style="font-family:'DM Mono',monospace;font-size:.6rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--maroon);background:rgba(255,255,255,.85);padding:3px 10px;border-radius:20px;">Jose Rizal Building</div>
              </div>
            </div>

            {{-- Location details --}}
            <div style="padding:18px 20px;display:flex;flex-direction:column;gap:12px;">

              <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:34px;height:34px;border-radius:10px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                  <div style="font-size:.7rem;font-family:'DM Mono',monospace;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Office</div>
                  <div style="font-size:.88rem;font-weight:800;color:var(--ink);">RMS Assistant Director's Office</div>
                </div>
              </div>

              <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:34px;height:34px;border-radius:10px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </div>
                <div>
                  <div style="font-size:.7rem;font-family:'DM Mono',monospace;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">Building & Floor</div>
                  <div style="font-size:.88rem;font-weight:800;color:var(--ink);">Jose Rizal Building</div>
                  <div style="font-size:.78rem;font-weight:600;color:var(--muted);margin-top:1px;">2nd Floor</div>
                </div>
              </div>

              <div style="display:flex;align-items:flex-start;gap:12px;">
                <div style="width:34px;height:34px;border-radius:10px;background:var(--maroon-light);color:var(--maroon);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div>
                  <div style="font-size:.7rem;font-family:'DM Mono',monospace;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:3px;">University</div>
                  <div style="font-size:.88rem;font-weight:800;color:var(--ink);">Batangas State University</div>
                  <div style="font-size:.78rem;font-weight:600;color:var(--muted);margin-top:1px;">KTTM · IP Records System</div>
                </div>
              </div>

              <div style="border-top:1px solid var(--line);padding-top:12px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.72rem;color:var(--muted);font-weight:600;">Walk-ins welcome during office hours.</span>
                <span style="font-family:'DM Mono',monospace;font-size:.6rem;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,.1);color:#059669;">Open</span>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>

{{-- FILE PREVIEW MODAL --}}
<div class="faq-preview-modal" id="faqPreviewModal">
  <div class="faq-preview-box">
    <div class="faq-preview-head">
      <div>
        <div class="faq-preview-title" id="faqPreviewTitle">Document Preview</div>
        <div class="faq-preview-sub">PDF preview · scroll to read</div>
      </div>
      <button class="faq-preview-close" id="faqPreviewClose">✕</button>
    </div>
    <div class="faq-preview-body">
      <iframe id="faqPreviewFrame" src="" title="Document preview" referrerpolicy="no-referrer"></iframe>
    </div>
    <div class="faq-preview-foot">
      <span style="font-size:.72rem;color:var(--muted);">Preview only — download for the editable .docx</span>
      <a id="faqPreviewDownload" href="#" download
        style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:10px;background:linear-gradient(135deg,var(--maroon),var(--maroon2));color:#fff;font-size:.76rem;font-weight:700;text-decoration:none;box-shadow:0 4px 14px rgba(165,44,48,.28);">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Download .docx
      </a>
    </div>
  </div>
</div>

{{-- OFFICE PLAQUE MODAL --}}
<div id="plaqueModal" style="
  position:fixed;inset:0;z-index:300;
  background:rgba(15,23,42,.75);backdrop-filter:blur(10px);
  display:none;align-items:center;justify-content:center;padding:20px;
">
  <div style="
    position:relative;
    max-width:420px;width:100%;
    animation:faqModalIn .28s cubic-bezier(.17,.67,.35,1.08);
  ">
    {{-- Close button --}}
    <button id="plaqueCloseBtn" style="
      position:absolute;top:-14px;right:-14px;z-index:10;
      width:34px;height:34px;border-radius:50%;
      background:var(--card);border:1.5px solid var(--line);
      display:flex;align-items:center;justify-content:center;
      cursor:pointer;color:var(--muted);font-size:.85rem;
      box-shadow:0 4px 14px rgba(15,23,42,.15);
      transition:background .15s,color .15s,border-color .15s;
    "
    onmouseover="this.style.background='var(--maroon)';this.style.color='#fff';this.style.borderColor='var(--maroon)';"
    onmouseout="this.style.background='var(--card)';this.style.color='var(--muted)';this.style.borderColor='var(--line)';">
      ✕
    </button>

    {{-- Eyebrow --}}
    <div style="
      text-align:center;margin-bottom:12px;
      font-family:'DM Mono',monospace;font-size:.58rem;font-weight:700;
      letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.5);
    ">Office Plaque · KTTM</div>

    {{-- Image --}}
    <div style="
      border-radius:18px;overflow:hidden;
      box-shadow:0 24px 60px rgba(15,23,42,.45);
      border:2px solid rgba(255,255,255,.08);
      background:#111;line-height:0;
    ">
      <img
        src="/images/KTTM.jpg"
        alt="KTTM Office Plaque"
        style="width:100%;height:auto;display:block;max-height:60vh;object-fit:contain;"
      >
    </div>

    {{-- Caption --}}
    <div style="
      text-align:center;margin-top:12px;
      font-size:.7rem;color:rgba(255,255,255,.4);font-weight:500;
    ">RMS Assistant Director's Office &nbsp;·&nbsp; Jose Rizal Building, 2nd Floor</div>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-left">
    <div class="footer-badge">K</div>
    <div class="footer-name">KTTM Intellectual Property Office</div>
  </div>
  <div class="footer-copy">© {{ date('Y') }} KTTM · All Rights Reserved</div>
</footer>

<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
  // ── Custom cursor (fine pointer only) ──
  const cursor = document.getElementById('cursor');
  const ring   = document.getElementById('cursorRing');
  let mx = 0, my = 0, rx = 0, ry = 0;
  if (cursor && ring && window.matchMedia('(pointer: fine)').matches) {
    document.addEventListener('mousemove', e => {
      mx = e.clientX; my = e.clientY;
      cursor.style.left = mx - 5 + 'px';
      cursor.style.top  = my - 5 + 'px';
    });
    (function animRing() {
      rx += (mx - rx) * 0.13;
      ry += (my - ry) * 0.13;
      ring.style.left = rx + 'px';
      ring.style.top  = ry + 'px';
      requestAnimationFrame(animRing);
    })();
  }

  // ── Mobile nav ──
  const mainNav = document.getElementById('mainNav');
  const navToggle = document.getElementById('navMenuToggle');
  function setNavOpen(open) {
    if (!mainNav || !navToggle) return;
    mainNav.classList.toggle('nav-open', open);
    document.body.classList.toggle('nav-open-menu', open);
    navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    navToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  }
  if (navToggle && mainNav) {
    navToggle.addEventListener('click', () => setNavOpen(!mainNav.classList.contains('nav-open')));
    mainNav.querySelectorAll('#primaryNav a').forEach(a => {
      a.addEventListener('click', () => {
        if (a.id === 'openLogin') { setNavOpen(false); return; }
        const h = a.getAttribute('href') || '';
        if (h.startsWith('#') && h.length > 1) setNavOpen(false);
      });
    });
    window.addEventListener('resize', () => {
      if (window.innerWidth > 900) setNavOpen(false);
    });
  }

  // ── Modal ──
  function openModal()  {
    document.getElementById('loginModal').classList.add('open');
    setTimeout(() => document.getElementById('loginEmail').focus(), 200);
  }
  function closeModal() { document.getElementById('loginModal').classList.remove('open'); }
  document.getElementById('openLogin').addEventListener('click',  e => { e.preventDefault(); openModal(); });
  document.getElementById('openLogin2').addEventListener('click', e => { e.preventDefault(); openModal(); });
  document.getElementById('closeLogin').addEventListener('click', closeModal);
  document.getElementById('loginModal').addEventListener('click', e => { if(e.target === e.currentTarget) closeModal(); });
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    closeModal();
    if (typeof setNavOpen === 'function') setNavOpen(false);
  });

  // ── Password visibility toggle ──
  document.getElementById('loginPwToggle').addEventListener('click', () => {
    const input = document.getElementById('loginPassword');
    const icon  = document.getElementById('loginEyeIcon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
      input.type = 'password';
      icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
  });

  // ── Real Login via AJAX ──
  async function doLogin() {
    const email    = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;
    const btn      = document.getElementById('loginBtn');
    const btnText  = document.getElementById('loginBtnText');
    const spinner  = document.getElementById('loginBtnSpinner');
    const alertEl  = document.getElementById('loginAlertJs');
    const alertTxt = document.getElementById('loginAlertText');

    if (!email || !password) {
      alertTxt.textContent = 'Please enter your email and password.';
      alertEl.style.display = 'flex';
      return;
    }

    // Loading state
    btn.disabled = true;
    btnText.style.display = 'none';
    spinner.style.display = 'inline-flex';
    alertEl.style.display = 'none';

    try {
      const resp = await fetch('{{ url('/login') }}', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept':       'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ email, password }),
      });

      const data = await resp.json();

      if (resp.ok && data.success) {
        btnText.textContent = 'Redirecting…';
        btnText.style.display = '';
        spinner.style.display = 'none';
        window.location.href = data.redirect || '/profile/select';
      } else {
        alertTxt.textContent = data.message || 'Invalid email or password.';
        alertEl.style.display = 'flex';
        document.getElementById('loginPassword').value = '';
        document.getElementById('loginPassword').focus();
      }
    } catch(e) {
      alertTxt.textContent = 'Something went wrong. Please try again.';
      alertEl.style.display = 'flex';
    } finally {
      btn.disabled = false;
      if (spinner.style.display !== 'none') {
        spinner.style.display = 'none';
        btnText.textContent = 'Sign In →';
        btnText.style.display = '';
      }
    }
  }

  document.getElementById('loginBtn').addEventListener('click', doLogin);
  document.getElementById('loginPassword').addEventListener('keypress', e => {
    if (e.key === 'Enter') doLogin();
  });
  document.getElementById('loginEmail').addEventListener('keypress', e => {
    if (e.key === 'Enter') document.getElementById('loginPassword').focus();
  });

  // Auto-open modal if there was a login error from server
  @if(session('login_error'))
    openModal();
  @endif

  // ── FAQ accordion ──
  function selectFaq(questionEl, num) {
    // Update active item on left list
    document.querySelectorAll('.faq-item').forEach(i => {
      i.classList.remove('open');
    });
    questionEl.closest('.faq-item').classList.add('open');

    // Find currently active panel
    const current = document.querySelector('.faq-panel.active');
    const next    = document.getElementById('faq-panel-' + num);
    if (!next || current === next) return;

    // Exit current
    current.classList.add('exit');
    current.classList.remove('active');
    setTimeout(() => {
      current.classList.remove('exit');
    }, 200);

    // Enter next
    next.classList.add('active');
  }

  // ── FAQ File Preview Modal ──
  const faqModal     = document.getElementById('faqPreviewModal');
  const faqFrame     = document.getElementById('faqPreviewFrame');
  const faqTitle     = document.getElementById('faqPreviewTitle');
  const faqDlLink    = document.getElementById('faqPreviewDownload');
  const faqCloseBtn  = document.getElementById('faqPreviewClose');

  document.querySelectorAll('.faq-preview-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const previewSrc  = btn.getAttribute('data-preview');
      const title       = btn.getAttribute('data-title') || 'Document Preview';
      const downloadHref = btn.closest('.file-card')
        .querySelector('.file-btn-download')?.getAttribute('href') || '#';

      faqTitle.textContent = title;
      faqFrame.setAttribute('src', previewSrc);
      faqDlLink.setAttribute('href', downloadHref);
      faqModal.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  });

  function closeFaqPreview() {
    faqModal.classList.remove('open');
    faqFrame.setAttribute('src', '');
    document.body.style.overflow = '';
  }

  faqCloseBtn?.addEventListener('click', closeFaqPreview);
  faqModal?.addEventListener('click', e => {
    if (e.target === faqModal) closeFaqPreview();
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeFaqPreview(); closePlaqueModal(); }
  });

  // ── Office Plaque Modal ──
  const plaqueModal = document.getElementById('plaqueModal');

  function openPlaqueModal()  {
    plaqueModal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
  function closePlaqueModal() {
    plaqueModal.style.display = 'none';
    document.body.style.overflow = '';
  }

  document.getElementById('viewPlaqueBtn')?.addEventListener('click', openPlaqueModal);
  document.getElementById('plaqueCloseBtn')?.addEventListener('click', closePlaqueModal);
  plaqueModal?.addEventListener('click', e => {
    if (e.target === plaqueModal) closePlaqueModal();
  });

  // ── Scroll-reveal ──
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.service-card, .faq-item, .hero-stat, .about-step-card, .about-panel, .about-dark-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(16px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(el);
  });

  // ── Stats filter (no reload) ──
  const MONTH_NAMES = ['','January','February','March','April','May','June','July','August','September','October','November','December'];

  function applyStatsFilter() {
    const month = document.getElementById('filterMonth').value;
    const year  = document.getElementById('filterYear').value;
    const btn   = document.getElementById('filterBtn');

    btn.textContent = '…';
    btn.disabled = true;

    const params = new URLSearchParams();
    if (month) params.set('month', month);
    if (year)  params.set('year',  year);
    params.set('ajax', '1');

    fetch('{{ url('/') }}?' + params.toString(), {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      // update total
      document.getElementById('statTotal').textContent = data.active;

      // update per-type counts & labels
      ['patent','copyright','utility','design'].forEach(key => {
        const countEl = document.querySelector(`.stat-count[data-key="${key}"]`);
        const labelEl = document.querySelector(`.stat-label[data-key="${key}"]`);
        if (countEl) countEl.textContent = data[key].count;
        if (labelEl) {
          const names = { patent:'Patent', copyright:'Copyright', utility:'Utility Model', design:'Industrial Design' };
          labelEl.textContent = names[key] + ' \u2013 ' + data[key].percent + '%';
        }
      });

      // update filter info
      const infoBox  = document.getElementById('filterInfo');
      const infoText = document.getElementById('filterInfoText');
      if (month || year) {
        let label = '';
        if (month) label += MONTH_NAMES[parseInt(month)];
        if (month && year) label += ' of ';
        if (year) label += year;
        infoText.textContent = label;
        infoBox.style.display = '';
      } else {
        infoBox.style.display = 'none';
      }

      // flash animation on stats
      document.querySelectorAll('.hero-stat').forEach(s => {
        s.style.transition = 'opacity 0.25s';
        s.style.opacity = '0.4';
        setTimeout(() => { s.style.opacity = '1'; }, 250);
      });
    })
    .catch(() => {
      // fallback: navigate if AJAX fails
      const base = '{{ url('/') }}';
      const qs = params.toString().replace('&ajax=1','').replace('ajax=1','');
      window.location.href = base + (qs ? '?' + qs : '');
    })
    .finally(() => {
      btn.textContent = 'Go';
      btn.disabled = false;
    });
  }

  // also trigger on Enter key in selects
  document.getElementById('filterMonth').addEventListener('change', () => {});
  document.getElementById('filterYear').addEventListener('change', () => {});

  /* ── Maintenance Banner Countdown ── */
  @if(!empty($scheduledAt))
  (function() {
    const target   = new Date('{{ $scheduledAt }}');
    const valEl    = document.getElementById('maintCountdownVal');
    const banner   = document.getElementById('maintBanner');
    const dismiss  = document.getElementById('maintDismiss');

    // Check if already dismissed this session
    if (sessionStorage.getItem('kttm_maint_dismissed') === '{{ $scheduledAt }}') {
      if (banner) {
        banner.style.display = 'none';
        document.body.classList.remove('has-maint-banner');
      }
      return;
    }

    function updateMaintCountdown() {
      if (!valEl) return;
      const diff = target - new Date();
      if (diff <= 0) {
        valEl.textContent = 'Starting now…';
        return;
      }
      const d   = Math.floor(diff / 86400000);
      const h   = Math.floor((diff % 86400000) / 3600000);
      const m   = Math.floor((diff % 3600000)  / 60000);
      const s   = Math.floor((diff % 60000)    / 1000);
      const pad = n => String(n).padStart(2, '0');
      valEl.textContent = (d > 0 ? d + 'd ' : '') + pad(h) + ':' + pad(m) + ':' + pad(s);
    }

    updateMaintCountdown();
    setInterval(updateMaintCountdown, 1000);

    // Dismiss button
    dismiss?.addEventListener('click', () => {
      banner.classList.add('hiding');
      sessionStorage.setItem('kttm_maint_dismissed', '{{ $scheduledAt }}');
      setTimeout(() => {
        banner.style.display = 'none';
        document.body.classList.remove('has-maint-banner');
      }, 320);
    });
  })();
  @endif

</script>
</body>
</html>