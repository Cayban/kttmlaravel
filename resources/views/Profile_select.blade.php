<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>KTTM — Select Profile</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --maroon:       #A52C30;
    --maroon2:      #7E1F23;
    --maroon3:      #C1363A;
    --gold:         #F0C860;
    --gold2:        #E8B857;
    --ink:          #0F172A;
    --muted:        #64748B;
    --line:         rgba(255,255,255,0.08);
    --card:         rgba(255,255,255,0.04);
    --card-hover:   rgba(255,255,255,0.08);
    --bg-deep:      #0D0608;
    --pad-x:        clamp(0.75rem, 4vw, 1.75rem);
    --card-max:     220px;
  }

  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
  html { -webkit-font-smoothing: antialiased; height: 100%; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg-deep);
    color: #fff;
    min-height: 100vh;
    min-height: 100dvh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow-x: hidden;
    overflow-y: auto;
    position: relative;
    padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
    -webkit-tap-highlight-color: transparent;
  }

  /* ── BACKGROUND ── */
  .bg-scene {
    position: fixed; inset: 0; z-index: 0;
    overflow: hidden;
  }
  .bg-gradient {
    position: absolute; inset: 0;
    background:
      radial-gradient(ellipse 80% 60% at 50% -10%, rgba(165,44,48,0.45) 0%, transparent 65%),
      radial-gradient(ellipse 50% 40% at 90% 90%,  rgba(126,31,35,0.30) 0%, transparent 60%),
      radial-gradient(ellipse 40% 50% at 10% 80%,  rgba(165,44,48,0.18) 0%, transparent 55%),
      linear-gradient(180deg, #150608 0%, #0D0608 40%, #080305 100%);
  }
  .bg-grid {
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(165,44,48,0.06) 1px, transparent 1px),
      linear-gradient(90deg, rgba(165,44,48,0.06) 1px, transparent 1px);
    background-size: 60px 60px;
    mask-image: radial-gradient(ellipse 90% 80% at 50% 50%, black 30%, transparent 80%);
  }
  .bg-noise {
    position: absolute; inset: 0;
    opacity: 0.035;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  }

  /* Floating orbs */
  .orb {
    position: absolute; border-radius: 50%;
    pointer-events: none; filter: blur(100px);
  }
  .orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(165,44,48,0.22), transparent 70%);
    top: -200px; left: -200px;
    animation: orbFloat1 18s ease-in-out infinite;
  }
  .orb-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(240,200,96,0.10), transparent 70%);
    bottom: -100px; right: -100px;
    animation: orbFloat2 22s ease-in-out infinite;
  }
  @keyframes orbFloat1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(60px,40px)} }
  @keyframes orbFloat2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-40px,-60px)} }

  /* ── MAIN CONTENT ── */
  .page-wrap {
    position: relative; z-index: 2;
    width: 100%;
    max-width: min(900px, 100%);
    padding: clamp(1rem, 4vw, 2rem) var(--pad-x);
    box-sizing: border-box;
    display: flex; flex-direction: column; align-items: center;
    gap: 0;
  }

  /* ── HEADER ── */
  .page-header {
    text-align: center;
    margin-bottom: clamp(1.5rem, 5vw, 3rem);
    max-width: 100%;
    opacity: 0; animation: fadeUp 0.8s 0.1s forwards;
  }
  .kttm-badge {
    display: inline-flex; align-items: center; justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: clamp(1rem, 3vw, 1.8rem);
    max-width: 100%;
  }
  .kttm-logo {
    width: 44px; height: 44px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(165,44,48,0.45), 0 0 0 1px rgba(165,44,48,0.3);
  }
  .kttm-logo img {
    width: 100%; height: 100%; object-fit: contain; display: block;
  }
  .kttm-name {
    font-family: 'DM Mono', monospace;
    font-size: clamp(0.58rem, 0.12vw + 0.55rem, 0.72rem);
    font-weight: 500;
    letter-spacing: clamp(0.12em, 0.5vw, 0.22em);
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
    text-align: center;
    overflow-wrap: anywhere;
  }

  .page-eyebrow {
    font-family: 'DM Mono', monospace;
    font-size: clamp(0.52rem, 0.15vw + 0.48rem, 0.62rem);
    letter-spacing: clamp(0.14em, 0.6vw, 0.28em);
    text-transform: uppercase;
    color: #fff;
    margin-bottom: 0.75rem;
    max-width: 100%;
    overflow-wrap: anywhere;
    line-height: 1.35;
  }
  .page-title {
    font-size: clamp(1.35rem, 4.5vw + 0.5rem, 2rem);
    font-weight: 800;
    color: #fff; letter-spacing: -0.5px;
    line-height: 1.12; margin-bottom: 0.6rem;
    max-width: 100%;
    overflow-wrap: anywhere;
  }
  .page-subtitle {
    font-size: clamp(0.76rem, 0.2vw + 0.72rem, 0.85rem);
    color: rgba(255,255,255,0.38);
    font-weight: 400; letter-spacing: 0.01em;
    max-width: 100%;
    line-height: 1.55;
    overflow-wrap: anywhere;
  }
  .page-subtitle strong {
    color: rgba(255,255,255,0.65); font-weight: 600;
  }

  /* ── PROFILE GRID ── */
  .profiles-grid {
    display: flex;
    gap: clamp(12px, 2.5vw, 16px);
    justify-content: center;
    align-items: stretch;
    flex-wrap: wrap;
    width: 100%;
    max-width: 100%;
    opacity: 0; animation: fadeUp 0.8s 0.3s forwards;
  }

  /* ── PROFILE CARD ── */
  .profile-card {
    position: relative;
    flex: 0 1 var(--card-max);
    width: 100%;
    max-width: min(var(--card-max), 100%);
    min-width: 0;
    box-sizing: border-box;
    background: var(--card);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 22px;
    padding: clamp(1.25rem, 3.5vw, 2rem) clamp(1.1rem, 3vw, 1.6rem) clamp(1.1rem, 3vw, 1.6rem);
    cursor: pointer;
    transition: transform 0.25s cubic-bezier(.17,.67,.35,1.1),
                background 0.2s, border-color 0.2s, box-shadow 0.25s;
    display: flex; flex-direction: column; align-items: center;
    gap: 0;
    overflow: hidden;
    backdrop-filter: blur(20px);
  }
  .profile-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 2px; border-radius: 22px 22px 0 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
    transition: opacity 0.2s;
  }
  .profile-card:hover {
    transform: translateY(-6px) scale(1.02);
    background: var(--card-hover);
    border-color: rgba(255,255,255,0.14);
    box-shadow: 0 24px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.06);
  }
  .profile-card:hover::before { opacity: 0; }

  /* glow on hover driven by avatar color */
  .profile-card:hover .card-glow { opacity: 1; }
  .card-glow {
    position: absolute; inset: 0; border-radius: 22px;
    pointer-events: none; opacity: 0;
    transition: opacity 0.3s;
  }

  /* ── AVATAR ── */
  .profile-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.3rem; color: #fff;
    margin-bottom: 1.1rem; flex-shrink: 0; position: relative;
    box-shadow: 0 8px 28px rgba(0,0,0,0.4);
    transition: transform 0.25s cubic-bezier(.17,.67,.35,1.1), box-shadow 0.25s;
  }
  .profile-card:hover .profile-avatar {
    transform: scale(1.08);
    box-shadow: 0 12px 36px rgba(0,0,0,0.5);
  }
  .avatar-ring {
    position: absolute; inset: -4px; border-radius: 50%;
    border: 1.5px solid rgba(255,255,255,0.15);
    transition: border-color 0.2s;
  }
  .profile-card:hover .avatar-ring { border-color: rgba(255,255,255,0.3); }

  /* Photo avatar — colored glow ring on hover */
  .profile-avatar--photo {
    width: 72px; height: 72px; border-radius: 50%;
    flex-shrink: 0; position: relative;
    margin-bottom: 1.1rem;
    transition: transform 0.25s cubic-bezier(.17,.67,.35,1.1),
                box-shadow 0.25s, filter 0.25s;
  }
  .profile-avatar--photo img {
    width: 72px; height: 72px;
    object-fit: contain;
    border-radius: 50%;
    display: block;
  }
  .profile-card:hover .profile-avatar--photo {
    transform: scale(1.08);
    filter: brightness(1.06);
  }
  .profile-card:hover .profile-avatar--photo .avatar-ring {
    border-width: 2.5px;
    border-color: rgba(255,255,255,0.55);
    box-shadow: 0 0 0 3px rgba(255,255,255,0.10);
  }

  /* ── NAME & ROLE ── */
  .profile-name {
    font-size: clamp(0.82rem, 0.25vw + 0.76rem, 0.9rem);
    font-weight: 700;
    color: rgba(255,255,255,0.9);
    text-align: center; line-height: 1.3;
    margin-bottom: 0.5rem;
    max-width: 100%;
    overflow-wrap: anywhere;
  }
  .profile-role {
    display: inline-flex; align-items: center; justify-content: center;
    flex-wrap: wrap;
    gap: 5px;
    font-family: 'DM Mono', monospace;
    font-size: clamp(0.52rem, 0.1vw + 0.5rem, 0.58rem);
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 10px; border-radius: 20px;
    margin-bottom: 1.4rem;
    max-width: 100%;
    text-align: center;
  }
  .role-admin     { background: rgba(165,44,48,0.2);  color: #f87171; border: 1px solid rgba(165,44,48,0.3); }
  .role-developer { background: rgba(240,200,96,0.15); color: var(--gold); border: 1px solid rgba(240,200,96,0.25); }
  .role-staff     { background: rgba(99,102,241,0.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.25); }

  /* ── DESIGNATION ── */
  .profile-designation {
    font-size: clamp(0.62rem, 0.12vw + 0.58rem, 0.68rem);
    font-weight: 500;
    color: rgba(255,255,255,0.38);
    text-align: center; line-height: 1.35;
    margin-top: -0.3rem;
    margin-bottom: 0.55rem;
    letter-spacing: 0.01em;
    font-style: italic;
    max-width: 100%;
    overflow-wrap: anywhere;
  }

  /* ── SELECT BUTTON ── */
  .profile-select-btn {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: clamp(0.55rem, 1.5vw, 0.65rem) 0.5rem;
    border-radius: 11px; border: 1px solid rgba(255,255,255,0.10);
    background: rgba(255,255,255,0.06);
    color: rgba(255,255,255,0.55);
    font-family: inherit;
    font-size: clamp(0.7rem, 0.15vw + 0.66rem, 0.75rem);
    font-weight: 600;
    cursor: pointer; letter-spacing: 0.03em;
    transition: background 0.18s, color 0.18s, border-color 0.18s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    white-space: normal;
    text-align: center;
    line-height: 1.25;
  }
  .profile-card:hover .profile-select-btn {
    background: rgba(255,255,255,0.10);
    color: rgba(255,255,255,0.85);
    border-color: rgba(255,255,255,0.18);
  }

  /* ── PASSWORD MODAL ── */
  .pw-modal-overlay {
    position: fixed; inset: 0; z-index: 100;
    background: rgba(5,2,3,0.75);
    backdrop-filter: blur(16px);
    display: none; align-items: center; justify-content: center;
    padding: var(--pad-x);
    box-sizing: border-box;
    overflow-y: auto;
  }
  .pw-modal-overlay.open { display: flex; }

  .pw-modal {
    background: #160609;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 24px;
    width: min(400px, calc(100vw - 2rem));
    max-width: 100%;
    margin: auto;
    box-sizing: border-box;
    padding: clamp(1.35rem, 4vw, 2.4rem) clamp(1.15rem, 3.5vw, 2.2rem) clamp(1.15rem, 3vw, 2rem);
    box-shadow: 0 40px 100px rgba(0,0,0,0.6), 0 0 0 1px rgba(165,44,48,0.15);
    animation: modalIn 0.3s cubic-bezier(.17,.67,.35,1.1);
    position: relative; overflow: hidden;
  }
  .pw-modal::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(165,44,48,0.6), rgba(240,200,96,0.4), transparent);
  }
  @keyframes modalIn {
    from { opacity: 0; transform: translateY(24px) scale(0.96); }
    to   { opacity: 1; transform: none; }
  }

  .pw-modal-avatar {
    width: 56px; height: 56px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1.1rem; color: #fff;
    margin: 0 auto 1.2rem;
    box-shadow: 0 8px 28px rgba(0,0,0,0.5);
  }
  .pw-modal-name {
    font-size: clamp(0.98rem, 0.35vw + 0.88rem, 1.15rem);
    font-weight: 800;
    color: #fff; text-align: center;
    letter-spacing: -0.3px; margin-bottom: 0.3rem;
    overflow-wrap: anywhere;
    line-height: 1.25;
    max-width: 100%;
  }
  .pw-modal-hint {
    font-size: clamp(0.68rem, 0.15vw + 0.64rem, 0.75rem);
    color: rgba(255,255,255,0.35);
    text-align: center;
    margin-bottom: 0.45rem;
    font-family: 'DM Mono', monospace;
    letter-spacing: 0.05em;
    max-width: 100%;
    line-height: 1.45;
    overflow-wrap: anywhere;
  }
  .pw-modal > .pw-field:first-of-type { margin-top: 1.25rem; }

  .pw-field {
    position: relative; margin-bottom: 1.2rem;
  }
  .pw-field label {
    display: block; font-size: 0.62rem; font-weight: 700;
    letter-spacing: 0.14em; text-transform: uppercase;
    color: rgba(255,255,255,0.35); margin-bottom: 0.5rem;
  }
  .pw-input-wrap { position: relative; }
  .pw-input {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 0.85rem 3rem 0.85rem 1rem;
    background: rgba(255,255,255,0.05);
    border: 1.5px solid rgba(255,255,255,0.09);
    border-radius: 12px;
    font-family: inherit;
    font-size: clamp(0.85rem, 0.2vw + 0.8rem, 0.9rem);
    color: #fff; outline: none; letter-spacing: 0.04em;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  }
  .pw-input::placeholder { color: rgba(255,255,255,0.2); letter-spacing: 0.04em; }
  .pw-input:focus {
    border-color: rgba(165,44,48,0.6);
    background: rgba(255,255,255,0.07);
    box-shadow: 0 0 0 3px rgba(165,44,48,0.15);
  }
  .pw-toggle {
    position: absolute; right: 0.9rem; top: 50%;
    transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: rgba(255,255,255,0.3); padding: 4px;
    transition: color 0.15s;
  }
  .pw-toggle:hover { color: rgba(255,255,255,0.6); }

  .pw-error {
    font-size: 0.72rem; color: #f87171;
    margin-top: -0.8rem; margin-bottom: 1rem;
    display: none; font-weight: 500;
    animation: shake 0.35s ease;
  }
  .pw-error.show { display: block; }
  @keyframes shake {
    0%,100%{transform:translateX(0)} 25%{transform:translateX(-6px)} 75%{transform:translateX(6px)}
  }

  .pw-submit {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 0.9rem 0.75rem;
    background: linear-gradient(135deg, var(--maroon2), var(--maroon));
    color: #fff; border: none; cursor: pointer;
    font-family: inherit;
    font-size: clamp(0.76rem, 0.18vw + 0.72rem, 0.82rem);
    font-weight: 700;
    border-radius: 12px; letter-spacing: 0.04em;
    box-shadow: 0 8px 24px rgba(165,44,48,0.35);
    transition: opacity 0.18s, transform 0.18s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 0.9rem;
    white-space: normal;
    text-align: center;
    line-height: 1.3;
  }
  .pw-submit:hover { opacity: 0.88; transform: translateY(-1px); }
  .pw-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

  .pw-cancel {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 0.7rem 0.5rem;
    background: none; border: none; cursor: pointer;
    font-family: inherit;
    font-size: clamp(0.72rem, 0.15vw + 0.68rem, 0.78rem);
    font-weight: 600;
    color: rgba(255,255,255,0.3); letter-spacing: 0.03em;
    transition: color 0.15s;
    overflow-wrap: anywhere;
    line-height: 1.35;
  }
  .pw-cancel:hover { color: rgba(255,255,255,0.6); }

  /* ── FOOTER ── */
  .page-footer {
    margin-top: clamp(1.5rem, 4vw, 2.8rem);
    text-align: center;
    max-width: 100%;
    opacity: 0; animation: fadeUp 0.8s 0.5s forwards;
  }
  .footer-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 7px;
    max-width: 100%;
    font-size: clamp(0.7rem, 0.15vw + 0.66rem, 0.75rem);
    font-weight: 600;
    color: rgba(255,255,255,0.25);
    text-decoration: none; cursor: pointer;
    transition: color 0.18s; background: none; border: none;
    font-family: inherit;
    overflow-wrap: anywhere;
    line-height: 1.4;
  }
  .footer-back:hover { color: rgba(255,255,255,0.55); }
  .footer-copy {
    margin-top: 1rem;
    font-family: 'DM Mono', monospace;
    font-size: clamp(0.5rem, 0.1vw + 0.48rem, 0.58rem);
    letter-spacing: clamp(0.1em, 0.4vw, 0.18em);
    text-transform: uppercase;
    color: rgba(255,255,255,0.12);
    max-width: 100%;
    line-height: 1.5;
    overflow-wrap: anywhere;
  }

  /* ── ANIMATIONS ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Loading spinner */
  .spinner {
    width: 16px; height: 16px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    animation: spin 0.7s linear infinite;
    display: none;
  }
  .spinner.show { display: block; }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── RESPONSIVE ── */
  @media (max-width: 640px) {
    .profiles-grid {
      flex-direction: column;
      align-items: stretch;
    }
    .profile-card {
      flex: 0 1 auto;
      max-width: min(320px, 100%);
      margin-left: auto;
      margin-right: auto;
    }
    .orb-1 { width: min(600px, 140vw); height: min(600px, 140vw); }
    .orb-2 { width: min(400px, 100vw); height: min(400px, 100vw); }
  }
  @media (max-width: 380px) {
    .profile-card {
      flex: 1 1 100%;
      max-width: 100%;
    }
    .kttm-badge { flex-direction: column; }
  }
</style>
</head>
<body>

<!-- Background -->
<div class="bg-scene">
  <div class="bg-gradient"></div>
  <div class="bg-grid"></div>
  <div class="bg-noise"></div>
  <div class="orb orb-1"></div>
  <div class="orb orb-2"></div>
</div>

<!-- Main -->
<div class="page-wrap">

  <!-- Header -->
  <div class="page-header">
    <div class="kttm-badge">
      <div class="kttm-logo">
        <img src="{{ asset('images/rmslogo.png') }}" alt="RMS Logo">
      </div>
      <div class="kttm-name">KTTM · RMS</div>
    </div>
    <div class="page-eyebrow">Intellectual Property Records System</div>
    <h1 class="page-title">Who's accessing today?</h1>
    <p class="page-subtitle">
      Signed in as <strong>{{ session('account_email') }}</strong> &nbsp;·&nbsp; Select your profile to continue
    </p>
  </div>

  <!-- Profile Cards -->
  <div class="profiles-grid">
    @foreach($profiles as $profile)
    @php
      $initials = collect(explode(' ', $profile->name))
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->take(2)->implode('');
      $roleClass = match($profile->role) {
        'developer' => 'role-developer',
        'admin'     => 'role-admin',
        default     => 'role-staff',
      };
      $roleLabel = match($profile->role) {
        'developer' => 'Developer',
        'admin'     => 'Admin',
        default     => 'Staff',
      };
      // Glow color based on avatar
      $glowColor   = $profile->avatar_color;
      $avatarImage = $profile->avatar_image ?? null;
      $designation = $profile->custom_role ?? '';
    @endphp
    <div class="profile-card" onclick="openPasswordModal({{ $profile->id }}, '{{ addslashes($profile->name) }}', '{{ $initials }}', '{{ $profile->avatar_color }}', '{{ $avatarImage ? asset('storage/avatars/' . $avatarImage) : '' }}', '{{ addslashes($designation) }}')">
      <!-- Glow -->
      <div class="card-glow" style="background: radial-gradient(ellipse 80% 60% at 50% 0%, {{ $glowColor }}22 0%, transparent 70%);"></div>

      <!-- Avatar -->
      @if($avatarImage)
        <div class="profile-avatar profile-avatar--photo" style="box-shadow:0 8px 28px rgba(0,0,0,0.4);" data-color="{{ $profile->avatar_color }}">
          <div class="avatar-ring"></div>
          <img src="{{ asset('storage/avatars/' . $avatarImage) }}" alt="{{ $initials }}">
        </div>
      @else
        <div class="profile-avatar" style="background: linear-gradient(135deg, {{ $profile->avatar_color }}cc, {{ $profile->avatar_color }});" data-color="{{ $profile->avatar_color }}">
          <div class="avatar-ring"></div>
          {{ $initials }}
        </div>
      @endif

      <!-- Info -->
      <div class="profile-name">{{ $profile->name }}</div>
      @if($designation)
      <div class="profile-designation">{{ $designation }}</div>
      @endif
      <div class="profile-role {{ $roleClass }}">
        <span style="width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>
        {{ $roleLabel }}
      </div>

      <!-- Button -->
      <button type="button" class="profile-select-btn">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
        Select Profile
      </button>
    </div>
    @endforeach
  </div>

  <!-- Footer -->
  <div class="page-footer">
    <a href="{{ url('/') }}" class="footer-back">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      Back to login
    </a>
    <div class="footer-copy">© {{ date('Y') }} · KTTM Records Management System</div>
  </div>

</div>

<!-- Password Modal -->
<div class="pw-modal-overlay" id="pwModal">
  <div class="pw-modal" id="pwModalCard">
    <div class="pw-modal-avatar" id="pwAvatar"></div>
    <div class="pw-modal-name" id="pwName"></div>
    <div class="pw-modal-hint" id="pwDesignation" style="display:none;"></div>
    <div class="pw-modal-hint">Enter your profile password to continue</div>

    <div class="pw-field">
      <label>Profile Password</label>
      <div class="pw-input-wrap">
        <input type="password" class="pw-input" id="pwInput" placeholder="••••••••" autocomplete="current-password">
        <button class="pw-toggle" type="button" id="pwToggle" tabindex="-1">
          <svg id="eyeIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
          </svg>
        </button>
      </div>
    </div>

    <div class="pw-error" id="pwError">Incorrect password. Please try again.</div>

    <button class="pw-submit" id="pwSubmit">
      <div class="spinner" id="pwSpinner"></div>
      <span id="pwSubmitText">Continue</span>
      <svg id="pwArrow" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <button class="pw-cancel" id="pwCancel">Cancel</button>
  </div>
</div>

<script>
  let selectedProfileId = null;

  function openPasswordModal(id, name, initials, color, photoUrl, designation) {
    selectedProfileId = id;

    const avatarEl = document.getElementById('pwAvatar');
    if (photoUrl) {
      avatarEl.innerHTML = `<img src="${photoUrl}" alt="${initials}"
        style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">`;
      avatarEl.style.background = 'transparent';
      avatarEl.style.boxShadow  = `0 8px 28px ${color}55`;
    } else {
      avatarEl.innerHTML = initials;
      avatarEl.style.background = `linear-gradient(135deg, ${color}cc, ${color})`;
      avatarEl.style.boxShadow  = `0 8px 28px ${color}55`;
    }
    document.getElementById('pwName').textContent = name;

    const designationEl = document.getElementById('pwDesignation');
    if (designation) {
      designationEl.textContent = designation;
      designationEl.style.display = '';
    } else {
      designationEl.textContent = '';
      designationEl.style.display = 'none';
    }

    document.getElementById('pwInput').value = '';
    document.getElementById('pwError').classList.remove('show');

    document.getElementById('pwModal').classList.add('open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('pwInput').focus(), 300);
  }

  function closePasswordModal() {
    document.getElementById('pwModal').classList.remove('open');
    document.body.style.overflow = '';
    selectedProfileId = null;
  }

  async function submitPassword() {
    const password = document.getElementById('pwInput').value;
    if (!password) return;

    const btn      = document.getElementById('pwSubmit');
    const spinner  = document.getElementById('pwSpinner');
    const text     = document.getElementById('pwSubmitText');
    const arrow    = document.getElementById('pwArrow');
    const errEl    = document.getElementById('pwError');

    // Loading state
    btn.disabled = true;
    spinner.classList.add('show');
    text.textContent = 'Verifying…';
    arrow.style.display = 'none';
    errEl.classList.remove('show');

    try {
      const resp = await fetch('{{ url('/profile/login') }}', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept':       'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          profile_id: selectedProfileId,
          password:   password,
        }),
      });

      const data = await resp.json();

      if (resp.ok && data.success) {
        text.textContent = 'Redirecting…';
        window.location.href = data.redirect || '/home';
      } else {
        errEl.classList.add('show');
        document.getElementById('pwInput').value = '';
        document.getElementById('pwInput').focus();
      }
    } catch(e) {
      errEl.textContent = 'Something went wrong. Please try again.';
      errEl.classList.add('show');
    } finally {
      btn.disabled = false;
      spinner.classList.remove('show');
      if (document.getElementById('pwSubmitText').textContent !== 'Redirecting…') {
        text.textContent = 'Continue';
        arrow.style.display = '';
      }
    }
  }

  // Events
  document.getElementById('pwCancel').addEventListener('click', closePasswordModal);
  document.getElementById('pwModal').addEventListener('click', e => {
    if (e.target === document.getElementById('pwModal')) closePasswordModal();
  });
  document.getElementById('pwSubmit').addEventListener('click', submitPassword);
  document.getElementById('pwInput').addEventListener('keypress', e => {
    if (e.key === 'Enter') submitPassword();
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closePasswordModal();
  });

  // Password visibility toggle
  document.getElementById('pwToggle').addEventListener('click', () => {
    const input = document.getElementById('pwInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
    } else {
      input.type = 'password';
      icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    }
  });
</script>
</body>
</html>