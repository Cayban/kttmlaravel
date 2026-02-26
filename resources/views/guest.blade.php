<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — Guest Home</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    :root{
      --maroon:#A52C30;
      --maroon2:#8B2E32;
      --gold:#F0C860;
      --gold2:#E8B857;
      --ink:#0F172A;
      --muted:#475569;
      --line:rgba(15,23,42,.10);
      --shadow: 0 18px 50px rgba(2,6,23,.10);
      --radius: 22px;
    }
    html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .focusRing:focus{ outline:none; box-shadow:0 0 0 4px rgba(165,44,48,.22); }

    /* new “panel” look */
    .panel{
      border:1px solid var(--line);
      border-radius: 26px;
      background: rgba(255,255,255,.72);
      backdrop-filter: blur(10px);
      box-shadow: var(--shadow);
      overflow:hidden;
    }
    .panelPad{ padding: 18px 20px; }
    @media (min-width:640px){ .panelPad{ padding: 22px 26px; } }

    /* subtle grid background overlay inside cards */
    .gridveil{
      background-image:
        radial-gradient(circle at 1px 1px, rgba(15,23,42,.06) 1px, transparent 0);
      background-size: 18px 18px;
    }
  </style>
</head>

<body class="min-h-screen scroll-smooth text-[color:var(--ink)] overflow-x-hidden bg-[#f6f3ec]">

  {{-- Background image --}}
  <div class="fixed inset-0 -z-30 bg-cover bg-center"
       style="background-image:url('{{ asset('images/bsuBG.jpg') }}');"></div>

  {{-- Overlay --}}
  <div class="fixed inset-0 -z-20"
       style="background:
         radial-gradient(1000px 520px at 12% 0%, rgba(240,200,96,.12), transparent 62%),
         radial-gradient(1000px 520px at 88% 10%, rgba(165,44,48,.14), transparent 60%),
         linear-gradient(180deg, rgba(250,249,246,.40) 0%, rgba(248,246,241,.50) 55%, rgba(250,249,246,.60) 100%);">
  </div>

  {{-- extra veil for readability --}}
  <div class="fixed inset-0 -z-10 bg-white/10 backdrop-blur-[1px]"></div>

  @php
    $guest = $guest ?? (object)[ 'name' => 'Guest Viewer', 'role' => 'Guest' ];

    $urlHome      = url('/');
    $urlGuestHome = url('/guest');
    $urlGuestRec  = url('/guest/records');
    $urlSupport   = url('/support');
    $urlHowTo     = url('/how-to-file');
    // email used by contact popup (same as about page)
    $kttmEmail    = $kttmEmail ?? 'kttm@batstateu.edu.ph';
  @endphp

  <div class="mx-auto w-[min(1180px,94vw)] py-4 pb-16">

    {{-- TOPBAR (shared with records) --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_10px_24px_rgba(2,6,23,.12)] bg-white/60 backdrop-blur">
        {{-- Top image strip --}}
        <div class="relative h-[84px] sm:h-[100px]">
          <img src="{{ asset('images/bannerusb2.jpg') }}" alt="KTTM Header" class="absolute inset-0 h-full w-full object-cover"/>
          <div class="absolute inset-0 bg-black/20"></div>
          <div class="absolute bottom-0 left-0 right-0 h-px bg-white/20"></div>
        </div>

        {{-- Link bar --}}
        <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3"
             style="background: linear-gradient(90deg, rgba(153, 34, 38, 0.96), rgba(171, 15, 20, 0.96));">

          {{-- Left identity --}}
          <div class="flex items-center gap-3 min-w-[240px]">
            <div class="h-9 w-9 rounded-2xl grid place-items-center font-black"
                 style="background: linear-gradient(135deg, rgba(240,200,96,.95), rgba(232,184,87,.95)); color:#2a1a0b;">
              G
            </div>
            <div class="leading-tight">
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">Guest Access</div>
              <div class="text-white/75 text-xs">
                Welcome, <span class="font-bold text-white">{{ $guest->name }}</span> • View-only
              </div>
            </div>
          </div>

          {{-- Center nav --}}
          <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-white/90">
            <a href="{{ $urlGuestHome }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Home</a>
            <a href="{{ $urlGuestRec }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Records</a>
            <a href="{{ $urlHowTo }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">How to File?</a>
           
          </nav>

          {{-- Right actions --}}
          <div class="flex items-center gap-2">
            <button id="openRules"
              class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937]
                     font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-white transition">
              Guest Rules
            </button>

            <a href="{{ $urlHome }}"
              class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937]
                     font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-white transition">
              Logout
            </a>

            <button id="menuBtn" type="button"
              class="md:hidden focusRing inline-flex items-center justify-center px-3 py-2 rounded-full bg-white/90 text-[#1f2937]
                     font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-white transition"
              aria-controls="mobileMenu" aria-expanded="false">
              ☰
            </button>
          </div>
        </div>
      </div>
    </header>

    {{-- Mobile menu --}}
    <div id="mobileMenu" class="hidden md:hidden border-t border-white/15"
         style="background: linear-gradient(90deg, rgba(153, 34, 38, 0.96), rgba(171, 15, 20, 0.96));">
      <nav class="px-4 py-3 grid gap-1 text-sm font-extrabold text-white/90">
        <a href="{{ $urlGuestHome }}" class="px-3 py-2 rounded-xl">Home</a>
        <a href="{{ $urlGuestRec }}" class="px-3 py-2 rounded-xl">Records</a>
        <a href="{{ $urlHowTo }}" class="px-3 py-2 rounded-xl">How to File</a>
   
      </nav>
    </div>

    {{-- MAIN HERO (new design: split highlight + access badge) --}}
    <section class="mt-6 panel">
      <div class="panelPad relative">
        <div class="absolute inset-0 -z-10 gridveil opacity-[.55]"></div>

        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full blur-3xl opacity-70"
             style="background: radial-gradient(circle, rgba(240,200,96,.38), transparent 60%);"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full blur-3xl opacity-70"
             style="background: radial-gradient(circle, rgba(165,44,48,.28), transparent 60%);"></div>

        <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_.8fr] gap-5 items-start">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/70 text-xs font-extrabold">
              <span class="h-2.5 w-2.5 rounded-full" style="background:var(--maroon); box-shadow:0 0 0 6px rgba(165,44,48,.16);"></span>
              View-only access
            </div>

            <h1 class="mt-4 text-[clamp(30px,3.6vw,50px)] leading-[1.06] font-black tracking-[-1px]">
              Explore intellectual property records — safely and clearly.
            </h1>

            <p class="mt-3 text-sm sm:text-base text-[color:var(--muted)] leading-relaxed max-w-[78ch]">
              As a guest, you can browse records and view details. Any filing, edits, or corrections should be requested through KTTM staff.
              (So yes — announcements are usually not needed for guests, unless you want a “public notice” area.)
            </p>

            <div class="mt-6 flex flex-wrap gap-2">
              <a href="{{ $urlGuestRec }}"
                 class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl
                        bg-[color:var(--maroon)] text-white font-extrabold text-sm hover:bg-[color:var(--maroon2)] transition">
                Go to Records →
              </a>

              <a href="{{ $urlHowTo }}"
                 class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl
                        border border-[color:var(--line)] bg-white/85 font-extrabold text-sm hover:bg-white transition">
                Read How to File
              </a>

              <a href="#" data-open-contact
                 class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl
                        border border-[color:var(--line)] bg-white/85 font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-[#2a1a0b] transition">
                Contact Support
              </a>
            </div>
          </div>

          {{-- Right: Access Summary --}}
          <aside class="rounded-[26px] border border-black/10 bg-white/75 p-6 sm:p-7">
            <div class="text-[11px] font-extrabold text-[color:var(--muted)]">ACCESS SUMMARY</div>
            <div class="mt-2 text-lg font-black text-[color:var(--maroon)]">Guest Viewer</div>
            <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
              You can view information but cannot submit changes.
            </p>

            <div class="mt-4 grid gap-3">
              <div class="rounded-2xl border border-black/10 bg-white/80 p-4">
                <div class="text-xs font-black">✅ Allowed</div>
                <ul class="mt-2 text-sm text-[color:var(--muted)] space-y-1">
                  <li>• Search &amp; filter records</li>
                  <li>• Open record details</li>
                  <li>• Download/view attachments (if enabled)</li>
                </ul>
              </div>

              <div class="rounded-2xl border border-black/10 bg-white/80 p-4">
                <div class="text-xs font-black">⛔ Restricted</div>
                <ul class="mt-2 text-sm text-[color:var(--muted)] space-y-1">
                  <li>• Create / file requests</li>
                  <li>• Edit / update / delete</li>
                  <li>• Approve / verify / route</li>
                </ul>
              </div>
            </div>

            <div class="mt-4">
              <button id="openRules2"
                class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--gold)] text-[#2a1a0b] font-extrabold hover:bg-[color:var(--gold2)] transition">
                View Guest Rules
              </button>
            </div>
          </aside>
        </div>
      </div>
    </section>

    {{-- ACTION CARDS (replace announcements with purpose-built guest actions) --}}
    <section class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4 items-stretch">

      {{-- Records Tips --}}
      <div class="panel">
        <div class="panelPad">
          <div class="text-[11px] font-extrabold text-[color:var(--muted)]">TIP</div>
          <h2 class="mt-2 text-base font-black tracking-[-.2px]">Search faster in Records</h2>
          <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
            Use keywords (title, author, campus) then narrow down using filters like IP type and status.
          </p>

          <div class="mt-4 rounded-2xl border border-black/10 bg-white/75 p-4">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Suggested filters</div>
            <div class="mt-2 flex flex-wrap gap-2">
              <span class="px-3 py-1.5 rounded-full text-xs font-extrabold border border-black/10 bg-white/80">Patent</span>
              <span class="px-3 py-1.5 rounded-full text-xs font-extrabold border border-black/10 bg-white/80">Trademark</span>
              <span class="px-3 py-1.5 rounded-full text-xs font-extrabold border border-black/10 bg-white/80">Registered</span>
              <span class="px-3 py-1.5 rounded-full text-xs font-extrabold border border-black/10 bg-white/80">Needs Attention</span>
            </div>
          </div>

          <div class="mt-4">
            <a href="{{ $urlGuestRec }}"
               class="focusRing inline-flex w-full items-center justify-center px-4 py-3 rounded-2xl bg-[color:var(--maroon)]
                      text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
              Open Records →
            </a>
          </div>
        </div>
      </div>

      {{-- How to File --}}
      <div class="panel">
        <div class="panelPad">
          <div class="text-[11px] font-extrabold text-[color:var(--muted)]">GUIDE</div>
          <h2 class="mt-2 text-base font-black tracking-[-.2px]">How to File (read-only)</h2>
          <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
            Learn required documents and the step-by-step process. Guests typically read this for reference,
            then coordinate with staff to submit.
          </p>

          <div class="mt-4 rounded-2xl border border-black/10 bg-white/75 p-4">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Includes</div>
            <ul class="mt-2 text-sm text-[color:var(--muted)] space-y-1">
              <li>• Requirements checklist</li>
              <li>• IP type selection</li>
              <li>• Routing/approval overview</li>
            </ul>
          </div>

          <div class="mt-4">
            <a href="{{ $urlHowTo }}"
               class="focusRing inline-flex w-full items-center justify-center px-4 py-3 rounded-2xl
                      border border-[color:var(--line)] bg-white/85 font-extrabold hover:bg-white transition">
              Open Filing Guide →
            </a>
          </div>
        </div>
      </div>

      {{-- Support / Request change --}}
      <div class="panel">
        <div class="panelPad">
          <div class="text-[11px] font-extrabold text-[color:var(--muted)]">HELP</div>
          <h2 class="mt-2 text-base font-black tracking-[-.2px]">Request corrections</h2>
          <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
            If you found an error in a record or need assistance filing, contact KTTM Support and include the record ID.
          </p>

          <div class="mt-4 rounded-2xl border border-black/10 bg-white/75 p-4">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">What to include</div>
            <ul class="mt-2 text-sm text-[color:var(--muted)] space-y-1">
              <li>• Record ID / Title</li>
              <li>• Requested change</li>
              <li>• Supporting document (if any)</li>
            </ul>
          </div>

          <div class="mt-4">
            <a href="#" data-open-contact
               class="focusRing inline-flex w-full items-center justify-center px-4 py-3 rounded-2xl
                      bg-[color:var(--gold)] text-[#2a1a0b] font-extrabold hover:bg-[color:var(--gold2)] transition">
              Contact Support
            </a>
          </div>
        </div>
      </div>

    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">Guest View • Read-only Access</div>
    </footer>

  </div>

  {{-- Guest Rules modal --}}
  <div id="rulesModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="rulesLabel">
    <div id="rulesContent" class="relative max-w-lg w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-rules class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>

      <div class="p-6 flex gap-4 items-start">
        <div class="flex-shrink-0">
          <div class="h-12 w-12 rounded-full grid place-items-center text-white font-black"
               style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">i</div>
        </div>

        <div class="flex-1">
          <h3 id="rulesLabel" class="text-xl font-black text-[color:var(--maroon)]">Guest Rules</h3>
          <ul class="mt-3 space-y-2 text-sm text-[color:var(--muted)]">
            <li>• Guests can browse and view record details.</li>
            <li>• Guests cannot create, edit, or delete records.</li>
            <li>• For filing and corrections, contact KTTM via Support.</li>
            <li>• Some attachments may be hidden depending on access policy.</li>
          </ul>

          <div class="mt-5">
            <button data-close-rules
              class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
              Okay
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- contact support modal -->
  <div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="contactLabel">
    <div id="contactContent" class="relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-contact class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>

      <div class="p-6 flex flex-col items-start">
        <div class="text-xl font-black text-[color:var(--maroon)]" id="contactLabel">Contact KTTM</div>
        <p class="mt-3 text-sm text-[color:var(--muted)]">
          For questions or assistance, please e-mail us at
          <a href="mailto:{{ $kttmEmail }}" class="font-extrabold text-[color:var(--maroon)] hover:text-[color:var(--gold)]">
            {{ $kttmEmail }}
          </a>.
        </p>
        <div class="mt-5 w-full">
          <button data-close-contact
            class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      // Mobile menu
      const btn = document.getElementById('menuBtn');
      const menu = document.getElementById('mobileMenu');
      if(btn && menu){
        function toggle(){
          const isOpen = !menu.classList.contains('hidden');
          menu.classList.toggle('hidden');
          btn.setAttribute('aria-expanded', String(!isOpen));
        }
        btn.addEventListener('click', toggle);
        menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
          menu.classList.add('hidden');
          btn.setAttribute('aria-expanded','false');
        }));
      }

      // Modal
      function showModal(modal, content){
        if(!modal || !content) return;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(()=>{ content.classList.remove('scale-95','opacity-0'); content.classList.add('scale-100','opacity-100'); });
        document.body.style.overflow='hidden';
      }
      function hideModal(modal, content){
        if(!modal || !content) return;
        content.classList.add('scale-95','opacity-0');
        setTimeout(()=>{ modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow=''; },160);
      }

      const rulesModal = document.getElementById('rulesModal');
      const rulesContent = document.getElementById('rulesContent');

      const open1 = document.getElementById('openRules');
      const open2 = document.getElementById('openRules2');
      const closeBtns = rulesModal ? rulesModal.querySelectorAll('[data-close-rules]') : [];

      open1?.addEventListener('click', () => showModal(rulesModal, rulesContent));
      open2?.addEventListener('click', () => showModal(rulesModal, rulesContent));
      closeBtns.forEach(b => b.addEventListener('click', () => hideModal(rulesModal, rulesContent)));
      rulesModal?.addEventListener('click', e => { if(e.target === rulesModal) hideModal(rulesModal, rulesContent); });

      // contact modal logic
      const contactModal = document.getElementById('contactModal');
      const contactContent = document.getElementById('contactContent');
      const contactTriggers = document.querySelectorAll('[data-open-contact]');
      const contactCloseBtns = contactModal ? contactModal.querySelectorAll('[data-close-contact]') : [];

      contactTriggers.forEach(el => el.addEventListener('click', e => {
        e.preventDefault();
        showModal(contactModal, contactContent);
      }));
      contactCloseBtns.forEach(b => b.addEventListener('click', () => hideModal(contactModal, contactContent)));
      contactModal?.addEventListener('click', e => { if(e.target === contactModal) hideModal(contactModal, contactContent); });

      document.addEventListener('keydown', e => {
        if(e.key === 'Escape'){
          if(contactModal && !contactModal.classList.contains('hidden')){
            hideModal(contactModal, contactContent);
          }
          if(rulesModal && !rulesModal.classList.contains('hidden')){
            hideModal(rulesModal, rulesContent);
          }
        }
      });
    })();
  </script>

</body>
</html>