@php
  $sessionTimeoutMinutes = (int) config('session.lifetime', 120);
@endphp

<div id="sessionExpiredModal" class="session-expired-overlay" aria-hidden="true">
  <div class="session-expired-card" role="dialog" aria-modal="true" aria-labelledby="sessionExpiredTitle">
    <div class="session-expired-icon">
      <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 8v5l3 2"/>
        <circle cx="12" cy="12" r="9"/>
      </svg>
    </div>
    <h2 id="sessionExpiredTitle">You have been logged out</h2>
    <p>Your session expired for security. Please sign in again to continue using the system.</p>
    <button type="button" id="sessionExpiredLoginBtn">Go to Login</button>
  </div>
</div>

<style>
  .session-expired-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 18px;
    background: rgba(15,23,42,.62);
    backdrop-filter: blur(7px);
  }
  .session-expired-overlay.open { display: flex; }
  .session-expired-card {
    width: min(420px, calc(100vw - 36px));
    border-radius: 20px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 28px 72px rgba(15,23,42,.28);
    padding: 28px;
    text-align: center;
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    color: #0F172A;
  }
  .session-expired-icon {
    width: 54px;
    height: 54px;
    margin: 0 auto 16px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(165,44,48,.10);
    color: #A52C30;
  }
  .session-expired-card h2 {
    margin: 0;
    font-size: 1.12rem;
    font-weight: 800;
    letter-spacing: -.02em;
  }
  .session-expired-card p {
    margin: 8px 0 22px;
    color: #64748B;
    font-size: .86rem;
    line-height: 1.55;
    font-weight: 500;
  }
  #sessionExpiredLoginBtn {
    width: 100%;
    border: 0;
    border-radius: 12px;
    padding: 12px 18px;
    background: linear-gradient(135deg, #A52C30, #7E1F23);
    color: #fff;
    font: inherit;
    font-size: .86rem;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 8px 22px rgba(165,44,48,.28);
  }
</style>

<script>
(function(){
  const timeoutMs = Math.max(1, {{ $sessionTimeoutMinutes }}) * 60 * 1000;
  const modal = document.getElementById('sessionExpiredModal');
  const loginBtn = document.getElementById('sessionExpiredLoginBtn');
  let timer = null;
  let expiredShown = false;

  function showExpired() {
    if (expiredShown || !modal) return;
    expiredShown = true;
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function resetTimer() {
    if (expiredShown) return;
    clearTimeout(timer);
    timer = setTimeout(showExpired, timeoutMs + 1000);
  }

  loginBtn?.addEventListener('click', function(){
    window.location.href = '{{ url('/') }}';
  });

  const originalFetch = window.fetch;
  if (typeof originalFetch === 'function') {
    window.fetch = async function(input, init) {
      const response = await originalFetch.apply(this, arguments);
      try {
        const target = new URL(typeof input === 'string' ? input : input.url, window.location.href);
        if (target.origin === window.location.origin) {
          if (response.status === 401 || response.status === 419) showExpired();
          else if (response.ok) resetTimer();
        }
      } catch (e) {}
      return response;
    };
  }

  resetTimer();
})();
</script>
