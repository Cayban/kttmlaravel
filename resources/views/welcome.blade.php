<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — IP Services Portal</title>

  <!-- Tailwind CDN (kept as-is). For stricter CSP later, compile assets locally. -->
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
      --card:rgba(255,255,255,.80);
      --shadow: 0 18px 50px rgba(2,6,23,.10);
      --radius: 22px;
    }
    html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .focusRing:focus{ outline:none; box-shadow:0 0 0 4px rgba(165,44,48,.22); }

    /* small helper for visually hidden text */
    .sr-only{
      position:absolute; width:1px; height:1px; padding:0; margin:-1px;
      overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0;
    }

    /* Modal animation states */
    .modal-card{ transform: scale(.97); opacity: 0; transition: transform .18s ease, opacity .18s ease; }
    .modal-open .modal-card{ transform: scale(1); opacity: 1; }

    /* Disable body scroll without forcing inline styles */
    body.modal-lock{ overflow:hidden; }
  </style>
</head>

<body class="min-h-screen scroll-smooth text-[color:var(--ink)] overflow-x-hidden bg-[#f6f3ec]">

  {{-- Background --}}
  <div class="fixed inset-0 -z-30 bg-cover bg-center"
       style="background-image:url('{{ asset('images/bsuBG.jpg') }}');"></div>

  {{-- Readability overlay --}}
  <div class="fixed inset-0 -z-20"
       style="background:
        radial-gradient(1100px 520px at 12% 0%, rgba(240,200,96,.10), transparent 60%),
        radial-gradient(1000px 520px at 88% 10%, rgba(165,44,48,.12), transparent 60%),
        linear-gradient(180deg, rgba(246,243,236,.40) 0%, rgba(246,243,236,.52) 55%, rgba(246,243,236,.62) 100%);">
  </div>

  {{-- Extra veil --}}
  <div class="fixed inset-0 -z-10 bg-white/10 backdrop-blur-[1px]"></div>

  @php
    /**
     * ✅ SAFE FALLBACKS (so page works even before DB route is ready)
     * Later, you will pass $stats from route/controller.
     */

    $stats = $stats ?? [
      'active' => 0,
      'patent' => ['count' => 0, 'percent' => 0],
      'copyright' => ['count' => 0, 'percent' => 0],
      'utility' => ['count' => 0, 'percent' => 0],
      'design' => ['count' => 0, 'percent' => 0],
      'recent' => 0,
      'attention' => 0,
    ];

    $active = (int)($stats['active'] ?? 0);

    $patentData = $stats['patent'] ?? ['count' => 0, 'percent' => 0];
    $copyrightData = $stats['copyright'] ?? ['count' => 0, 'percent' => 0];
    $utilityData = $stats['utility'] ?? ['count' => 0, 'percent' => 0];
    $designData = $stats['design'] ?? ['count' => 0, 'percent' => 0];

    $recent = (int)($stats['recent'] ?? 0);
    $attention = (int)($stats['attention'] ?? 0);

    // ✅ Clamp percent (avoid UI breaking)
    $patentPct = min(100, max(0, (int)($patentData['percent'] ?? 0)));
    $copyrightPct = min(100, max(0, (int)($copyrightData['percent'] ?? 0)));
    $utilityPct = min(100, max(0, (int)($utilityData['percent'] ?? 0)));
    $designPct = min(100, max(0, (int)($designData['percent'] ?? 0)));

    // URLs
    $urlIpAssets = url('/ipassets');
    $urlSupport  = url('/about');
    $urlHome     = url('/home');
  @endphp

  <div class="mx-auto w-[min(1200px,94vw)] py-4 pb-16">

    {{-- NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_14px_30px_rgba(2,6,23,.14)] bg-white/60 backdrop-blur">

        {{-- TOP IMAGE STRIP --}}
        <div class="relative h-[92px] sm:h-[112px]">
          <img
            src="{{ asset('images/bannerusb2.jpg') }}"
            alt="KTTM Header"
            class="absolute inset-0 h-full w-full object-cover"
          />

          <div class="absolute inset-0 bg-gradient-to-r from-black/35 via-black/20 to-black/25"></div>
          <div class="absolute inset-0 opacity-70"
               style="background: radial-gradient(800px 240px at 20% 0%, rgba(240,200,96,.22), transparent 60%);"></div>

          <div class="absolute bottom-0 left-0 right-0 h-[2px]" style="background:rgba(240,200,96,.55)"></div>

          <div class="absolute left-4 sm:left-6 bottom-3">
            <div class="text-white font-black tracking-[-.3px] text-base sm:text-lg drop-shadow">
              Intellectual Property Services
            </div>
            <div class="text-white/80 text-xs sm:text-sm font-semibold drop-shadow">
              BatStateU • Knowledge Technology Transfer Management
            </div>
          </div>
        </div>

        {{-- LINK BAR --}}
        <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3"
             style="background: linear-gradient(90deg, rgba(153,34,38,.97), rgba(171,15,20,.97));">

          <a href="#home" class="flex items-center gap-3 min-w-[220px]">
            <div class="h-9 w-9 rounded-2xl grid place-items-center font-black"
                 style="background: linear-gradient(135deg, rgba(240,200,96,.95), rgba(232,184,87,.95)); color:#2a1a0b;">
              K
            </div>
            <div class="leading-tight">
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">KTTM IP Portal</div>
              <div class="text-white/75 text-xs">Submission • Tracking • Records</div>
            </div>
          </a>

          <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-white/90">
            <a href="#home" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Home</a>
            <a href="#services" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Services</a>
            <a href="#process" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Process</a>
            <a href="#faq" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">FAQ</a>
            <a href="/about" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">About KTTM</a>
          </nav>

          <div class="flex items-center gap-2">
            <button id="loginBtn" type="button"
              class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937]
                     font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-[#2a1a0b] transition"
              aria-controls="loginModal" aria-expanded="false">
              Log in
            </button>

            <button id="menuBtn" type="button"
              class="md:hidden focusRing inline-flex items-center justify-center px-3 py-2 rounded-full bg-white/15 text-white font-extrabold text-sm hover:bg-white/25 transition"
              aria-controls="mobileMenu" aria-expanded="false">
              ☰
            </button>
          </div>
        </div>

        {{-- Mobile dropdown menu --}}
        <div id="mobileMenu" class="hidden md:hidden border-t border-white/15"
             style="background: linear-gradient(90deg, rgba(153,34,38,.97), rgba(171,15,20,.97));">
          <nav class="px-4 py-3 grid gap-1 text-sm font-semibold text-white/90">
            <a href="#home" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Home</a>
            <a href="#services" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Services</a>
            <a href="#process" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Process</a>
            <a href="#faq" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">FAQ</a>
            <a href="/about" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">About KTTM</a>
          </nav>
        </div>

      </div>
    </header>

    {{-- HERO --}}
    <section id="home" class="mt-6 scroll-mt-32 sm:scroll-mt-40 grid grid-cols-1 lg:grid-cols-[1.2fr_.8fr] gap-4">

      {{-- Main hero --}}
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)]
                  p-7 sm:p-9 relative overflow-hidden">

        <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full blur-3xl opacity-70"
             style="background: radial-gradient(circle, rgba(240,200,96,.40), transparent 60%);"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full blur-3xl opacity-70"
             style="background: radial-gradient(circle, rgba(165,44,48,.28), transparent 60%);"></div>

        <div class="relative">
          <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/60 text-xs font-extrabold">
            <span class="h-2.5 w-2.5 rounded-full" style="background:var(--gold); box-shadow:0 0 0 6px rgba(240,200,96,.18);"></span>
            Official IP Services Portal
          </div>

          <h1 class="mt-4 text-[clamp(38px,4.8vw,56px)] leading-[1.03] font-black tracking-[-1px]">
            File, track, and protect<br class="hidden sm:block"/>
            <span class="text-[color:var(--maroon)]">Intellectual Property</span>
          </h1>

          <p class="mt-3 text-base text-[color:var(--muted)] leading-relaxed max-w-[68ch]">
            Submit IP requests, attach documents, track status, and maintain a centralized record archive—built for KTTM workflows.
          </p>

          <div class="mt-6 flex flex-wrap gap-2">
            <button id="openPlaqueBtn" type="button"
              class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-[color:var(--line)]
                     bg-white/75 shadow-sm font-bold text-sm hover:bg-[color:var(--gold)] hover:text-[#2a1a0b] transition"
              aria-controls="plaqueModal" aria-expanded="false">
              View Plaque
            </button>

            <a href="{{ $urlSupport }}"
              class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-[color:var(--line)]
                     bg-white/75 shadow-sm font-bold text-sm hover:bg-white hover:-translate-y-[1px] transition">
              Get Support
            </a>
          </div>

          <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
            <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
              <div class="font-extrabold">Transparent Tracking</div>
              <div class="mt-1 text-xs text-[color:var(--muted)]">Status updates and history per record.</div>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
              <div class="font-extrabold">Document Ready</div>
              <div class="mt-1 text-xs text-[color:var(--muted)]">Upload forms, drafts, and proof files.</div>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
              <div class="font-extrabold">Central Archive</div>
              <div class="mt-1 text-xs text-[color:var(--muted)]">One place for all institutional IP records.</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Snapshot --}}
      <aside class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="p-6 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(153,34,38,.97), rgba(171,15,20,.97));">
          <h3 class="font-black text-base tracking-[-.2px] text-white">Portal Snapshot</h3>
          <p class="mt-1 text-xs text-white/90">Replace with live data anytime.</p>
        </div>

        <div class="p-6 grid gap-3">
          <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
            <div class="flex items-center justify-between mb-3">
              <div class="text-xs font-extrabold text-[color:var(--muted)]">IP Submissions by Type</div>

              <div class="flex items-center gap-3">
                <div class="text-xs font-bold text-[color:var(--muted)]">
                  Total: <span class="text-[color:var(--maroon)]">{{ $active }}</span>
                </div>

                {{-- Year selector: reloads page with ?year=YYYY or clears for all years --}}
                <div class="text-xs text-[color:var(--muted)]">
                  <label for="yearSelect" class="sr-only">Year</label>
                  <select id="yearSelect" class="rounded-md border px-2 py-1 text-sm bg-white/80">
                    <option value="">All years</option>
                    @if(!empty($years ?? []))
                      @foreach($years as $y)
                        <option value="{{ $y }}" @if(isset($selectedYear) && (string)$selectedYear === (string)$y) selected @endif>{{ $y }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
            </div>

            <div class="space-y-3">
              {{-- Patent --}}
              <div class="group cursor-pointer transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between text-xs font-bold mb-1 group-hover:text-[color:var(--maroon)]">
                  <span class="text-[color:var(--muted)]">Patent</span>
                  <span class="text-[color:var(--maroon)] font-extrabold">{{ (int)($patentData['count'] ?? 0) }}</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden group-hover:shadow-[0_0_12px_rgba(165,44,48,.35)] transition-all duration-300">
                  <div class="h-full rounded-full transition-all duration-500"
                       style="width:{{ $patentPct }}%; background:linear-gradient(90deg,var(--maroon),var(--maroon2));"></div>
                </div>
              </div>

              {{-- Copyright --}}
              <div class="group cursor-pointer transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between text-xs font-bold mb-1 group-hover:text-[color:var(--gold)]">
                  <span class="text-[color:var(--muted)]">Copyright</span>
                  <span class="text-[color:var(--gold)] font-extrabold">{{ (int)($copyrightData['count'] ?? 0) }}</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden group-hover:shadow-[0_0_12px_rgba(240,200,96,.45)] transition-all duration-300">
                  <div class="h-full rounded-full transition-all duration-500"
                       style="width:{{ $copyrightPct }}%; background:linear-gradient(90deg,var(--gold),var(--gold2));"></div>
                </div>
              </div>

              {{-- Utility Model --}}
              <div class="group cursor-pointer transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between text-xs font-bold mb-1 group-hover:text-emerald-600">
                  <span class="text-[color:var(--muted)]">Utility Model</span>
                  <span class="text-emerald-600 font-extrabold">{{ (int)($utilityData['count'] ?? 0) }}</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden group-hover:shadow-[0_0_12px_rgba(5,150,105,.35)] transition-all duration-300">
                  <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-500"
                       style="width:{{ $utilityPct }}%;"></div>
                </div>
              </div>

              {{-- Industrial Design --}}
              <div class="group cursor-pointer transition-all duration-300 hover:scale-105">
                <div class="flex items-center justify-between text-xs font-bold mb-1 group-hover:text-amber-600">
                  <span class="text-[color:var(--muted)]">Industrial Design</span>
                  <span class="text-amber-600 font-extrabold">{{ (int)($designData['count'] ?? 0) }}</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden group-hover:shadow-[0_0_12px_rgba(217,119,6,.35)] transition-all duration-300">
                  <div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-amber-600 transition-all duration-500"
                       style="width:{{ $designPct }}%;"></div>
                </div>
              </div>
            </div>
          </div>

          {{-- Open Dashboard (no inline JS; CSP-friendly) --}}
          <a href="#" id="dashboardBtn"
             class="focusRing relative overflow-hidden mt-1 inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl
                    border border-[color:var(--line)] bg-white/85 shadow-sm font-extrabold text-sm
                    transition-all duration-300 ease-out
                    hover:-translate-y-[2px] hover:shadow-[0_14px_30px_rgba(165,44,48,.22)]
                    hover:border-[color:var(--maroon)] hover:text-white
                    active:scale-[0.98] dashboardBtn">
            <span class="relative z-10">Open Dashboard →</span>
          </a>

          <div class="grid grid-cols-3 gap-2">
            <div class="rounded-2xl border border-black/10 bg-white/70 p-3">
              <div class="text-[11px] font-extrabold text-[color:var(--muted)]">Active</div>
              <div class="text-lg font-black text-[color:var(--maroon)] leading-none">{{ $active }}</div>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/70 p-3">
              <div class="text-[11px] font-extrabold text-[color:var(--muted)]">Recent</div>
              <div class="text-lg font-black text-emerald-600 leading-none">{{ $recent }}</div>
            </div>
            <div class="rounded-2xl border border-black/10 bg-white/70 p-3">
              <div class="text-[11px] font-extrabold text-[color:var(--muted)]">Attention</div>
              <div class="text-lg font-black text-amber-600 leading-none">{{ $attention }}</div>
            </div>
          </div>
        </div>
      </aside>
    </section>

    {{-- SERVICES --}}
    <section id="services" class="mt-5 rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
      <div class="p-7 sm:p-9">
        <div class="flex items-end justify-between gap-3">
          <div>
            <h2 class="text-xl font-black tracking-[-.4px]">Services</h2>
            <p class="mt-1 text-sm text-[color:var(--muted)]">Select the IP category you need.</p>
          </div>
          <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-[color:var(--muted)]">
            <span class="px-3 py-2 rounded-full border border-black/10 bg-white/60 hover:bg-[color:var(--gold)] hover:text-[#2a1a0b] transition cursor-pointer">Secure</span>
            <span class="px-3 py-2 rounded-full border border-black/10 bg-white/60 hover:bg-[color:var(--maroon)] hover:text-white transition cursor-pointer">Traceable</span>
            <span class="px-3 py-2 rounded-full border border-black/10 bg-white/60 hover:bg-[color:var(--gold)] hover:text-[#2a1a0b] transition cursor-pointer">Organized</span>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
          <div class="group rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-[color:var(--maroon)] transition shadow-sm hover:shadow-lg hover:-translate-y-1">
            <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-white"
                 style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">P</div>
            <h3 class="mt-3 font-extrabold text-[color:var(--maroon)] group-hover:text-white">Patent Support</h3>
            <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed group-hover:text-white">
              Requirements, documentation checks, evaluation flow, and status tracking.
            </p>
          </div>

          <div class="group rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-[color:var(--gold)] transition shadow-sm hover:shadow-lg hover:-translate-y-1">
            <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-[#2a1a0b]"
                 style="background:linear-gradient(135deg,var(--gold),var(--gold2));">C</div>
            <h3 class="mt-3 font-extrabold text-[color:var(--gold)] group-hover:text-white">Copyright</h3>
            <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed group-hover:text-white">
              Creative works filing support, proof-of-filing, record management, archiving.
            </p>
          </div>

          <div class="group rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-emerald-700 transition shadow-sm hover:shadow-lg hover:-translate-y-1">
            <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-white bg-gradient-to-br from-emerald-600 to-emerald-700">U</div>
            <h3 class="mt-3 font-extrabold text-emerald-700 group-hover:text-white">Utility Model</h3>
            <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed group-hover:text-white">
              Utility model handling, uploads, references, and institutional records.
            </p>
          </div>

          <div class="group rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-amber-700 transition shadow-sm hover:shadow-lg hover:-translate-y-1">
            <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-white bg-gradient-to-br from-amber-600 to-amber-700">ID</div>
            <h3 class="mt-3 font-extrabold text-amber-700 group-hover:text-white">Industrial Design</h3>
            <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed group-hover:text-white">
              Design registration support, aesthetic innovation protection, and documentation management.
            </p>
          </div>
        </div>
      </div>
    </section>

    {{-- PROCESS --}}
    <section id="process" class="mt-5 rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
      <div class="p-7 sm:p-9">
        <h2 class="text-xl font-black tracking-[-.4px]">Process</h2>
        <p class="mt-1 text-sm text-[color:var(--muted)]">From submission to registration, with clear status steps.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-3">
          <div class="rounded-2xl border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition cursor-pointer">
            <div class="text-xs font-extrabold text-[color:var(--gold)]">STEP 1</div>
            <div class="mt-1 font-black text-lg text-[color:var(--maroon)]">Submit</div>
            <div class="mt-2 text-sm text-[color:var(--muted)]">Create a record and upload required docs.</div>
          </div>
          <div class="rounded-2xl border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition cursor-pointer">
            <div class="text-xs font-extrabold text-[color:var(--gold)]">STEP 2</div>
            <div class="mt-1 font-black text-lg text-[color:var(--maroon)]">Review</div>
            <div class="mt-2 text-sm text-[color:var(--muted)]">KTTM checks completeness and updates status.</div>
          </div>
          <div class="rounded-2xl border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition cursor-pointer">
            <div class="text-xs font-extrabold text-[color:var(--gold)]">STEP 3</div>
            <div class="mt-1 font-black text-lg text-[color:var(--maroon)]">Track</div>
            <div class="mt-2 text-sm text-[color:var(--muted)]">Monitor milestones and download acknowledgements.</div>
          </div>
        </div>
      </div>
    </section>

    {{-- FAQ --}}
    <section id="faq" class="mt-5">
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="p-7 sm:p-9">
          <h2 class="text-xl font-black tracking-[-.4px]">FAQ</h2>
          <div class="mt-5 grid gap-3">
            <details class="rounded-2xl border border-black/10 bg-white/72 p-5 hover:bg-white transition hover:shadow-sm">
              <summary class="cursor-pointer font-extrabold text-[color:var(--maroon)] hover:text-[color:var(--gold)]">
                What files do I usually need?
              </summary>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                Forms, draft/specification, proof of authorship, supporting documents, references.
              </p>
            </details>
            <details class="rounded-2xl border border-black/10 bg-white/72 p-5 hover:bg-white transition hover:shadow-sm">
              <summary class="cursor-pointer font-extrabold text-[color:var(--maroon)] hover:text-[color:var(--gold)]">
                Can I track status online?
              </summary>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                Yes — each record has a status and activity history.
              </p>
            </details>
          </div>

          {{-- downloadable forms section --}}
          <div class="mt-6">
            <h3 class="text-lg font-black">Downloadable Forms</h3>
            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <div class="rounded-2xl border border-[color:var(--line)] bg-white/80 shadow-sm p-4 hover:shadow-md transition">
                <button type="button"
                        class="formLink focusRing flex items-center gap-2 w-full text-left px-3 py-2 rounded-xl bg-white text-sm font-extrabold hover:bg-gray-50 transition"
                        data-file="/forms/BatStateU-FO-RMS-05_Intellectual_Property_Evaluation_Form_Rev._02.docx"
                        data-preview="/forms/BatStateU-FO-RMS-05_Intellectual_Property_Evaluation_Form_Rev._02.pdf"
                        data-title="BatState-FO-RMS-05 IP Evaluation Form">
                  <span class="text-xl">📄</span>
                  BatState-FO-RMS-05 IP Evaluation Form
                </button>
              </div>

              <div class="rounded-2xl border border-[color:var(--line)] bg-white/80 shadow-sm p-4 hover:shadow-md transition">
                <button type="button"
                        class="formLink focusRing flex items-center gap-2 w-full text-left px-3 py-2 rounded-xl bg-white text-sm font-extrabold hover:bg-gray-50 transition"
                        data-file="/forms/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.docx"
                        data-preview="/forms/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.pdf"
                        data-title="BatState-FO-RMS-08 Invention Disclosure Form">
                  <span class="text-xl">📄</span>
                  BatState-FO-RMS-08 Invention Disclosure Form
                </button>
              </div>

              <div class="rounded-2xl border border-[color:var(--line)] bg-white/80 shadow-sm p-4 hover:shadow-md transition">
                <button type="button"
                        class="formLink focusRing flex items-center gap-2 w-full text-left px-3 py-2 rounded-xl bg-white text-sm font-extrabold hover:bg-gray-50 transition"
                        data-file="/forms/Copyright_Forms.docx"
                        data-preview="/forms/Copyright_Forms.pdf"
                        data-title="Copyright Form (single/multiple authors)">
                  <span class="text-xl">📄</span>
                  Copyright Form (single/multiple authors)
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">Palette: <b style="color:var(--maroon)">#A52C30</b> · <b style="color:var(--gold2)">#E8B857</b> · Slate neutrals</div>
    </footer>

    {{-- LOGIN MODAL --}}
    <div id="loginModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
         role="dialog" aria-modal="true" aria-labelledby="loginModalLabel">
      <div class="modal-card relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden" data-modal-card>
        <button type="button" data-close-login class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100" aria-label="Close login modal">✕</button>
        <div class="p-6">
          <h3 id="loginModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Sign in to KTTM</h3>
          <p class="mt-1 text-sm text-[color:var(--muted)]">Enter your credentials to access the dashboard.</p>

          <!-- simulated login kept -->
          <form id="loginForm" data-simulate="true" action="#" method="POST" onsubmit="return false;" class="mt-4 space-y-4">
            @csrf
            <div>
              <label class="text-xs font-semibold text-[color:var(--muted)]">Email</label>
              <input id="loginEmail" name="email" type="email" required
                     class="mt-1 w-full rounded-lg border border-[color:var(--line)] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />
            </div>
            <div>
              <label class="text-xs font-semibold text-[color:var(--muted)]">Password</label>
              <input id="loginPassword" name="password" type="password" required
                     class="mt-1 w-full rounded-lg border border-[color:var(--line)] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />
            </div>

            <div class="flex items-center justify-between gap-2">
              <label class="inline-flex items-center text-sm gap-2">
                <input name="remember" type="checkbox" class="h-4 w-4 text-[color:var(--maroon)]" />
                <span class="text-sm text-[color:var(--muted)]">Remember me</span>
              </label>
              <a href="/about" class="text-sm text-[color:var(--maroon)] hover:text-[color:var(--gold)]">Need help?</a>
            </div>

            <div class="pt-2">
              <a id="simulateSignIn" href="{{ $urlHome }}"
                 class="w-full inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
                Sign in
              </a>
              <div class="mt-2 text-[11px] text-[color:var(--muted)]">
                *Simulated sign-in: make sure <b>/home</b> is protected by server auth middleware in production.
              </div>
            </div>
          </form>

          <form action="{{ url('/guest') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="w-full px-4 py-2 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-bold hover:bg-[color:var(--gold)] hover:text-[#2a1a0b] transition">
              Continue as guest
            </button>
            <div class="mt-2 text-xs text-[color:var(--muted)] text-center">Limited access — temporary guest session.</div>
          </form>
        </div>

        <div class="p-3 flex justify-end">
          <button data-close-login class="px-4 py-2 rounded-2xl border border-[color:var(--line)] bg-white/70 text-sm hover:bg-gray-50">Close</button>
        </div>
      </div>
    </div>

    {{-- PLAQUE MODAL (now closes on backdrop + ESC, and focus trap) --}}
    <div id="plaqueModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
         role="dialog" aria-modal="true" aria-label="Plaque preview">
      <div class="modal-card relative max-w-[920px] w-full bg-white rounded-2xl shadow-lg overflow-hidden" data-modal-card>
        <button type="button" data-close-plaque class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100" aria-label="Close plaque modal">✕</button>
        <div class="max-h-[80vh] overflow-auto bg-black/5 grid place-items-center p-4">
          <img src="{{ asset('images/KTTM.jpg') }}" alt="Plaque" class="w-full h-auto object-contain rounded-md" />
        </div>
        <div class="p-3 flex justify-end">
          <button data-close-plaque class="px-4 py-2 rounded-2xl bg-[color:var(--maroon)] text-white hover:bg-[color:var(--maroon2)]">Close</button>
        </div>
      </div>
    </div>

    {{-- Form preview modal --}}
    <div id="formPreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
         role="dialog" aria-modal="true" aria-labelledby="formPreviewLabel">
      <div class="modal-card relative max-w-3xl w-full bg-white rounded-2xl shadow-lg overflow-hidden" data-modal-card>
        <button type="button" data-close-form class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100" aria-label="Close form preview modal">✕</button>
        <div class="p-4">
          <h3 id="formPreviewLabel" class="text-lg font-black text-[color:var(--maroon)]"></h3>
        </div>
        <div class="p-4">
          <div class="w-full h-[60vh]">
            <iframe id="formPreviewObject" src="" width="100%" height="100%" class="border-none" referrerpolicy="no-referrer">
              <p class="text-sm text-[color:var(--muted)]">Preview not available. <a id="formDownloadLinkFallback" href="#" class="underline">Download the file</a>.</p>
            </iframe>
          </div>
        </div>
        <div class="p-3 flex justify-end gap-2 border-t border-[color:var(--line)]">
          <a id="formDownloadLink" href="#" download
             class="focusRing px-4 py-2 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
            Download
          </a>
          <button data-close-form class="px-4 py-2 rounded-2xl border border-[color:var(--line)] bg-white/70 text-sm hover:bg-gray-50">Close</button>
        </div>
      </div>
    </div>

    <script>
      // ---------------------------
      // Helpers: modal open/close + focus trap + restore focus
      // ---------------------------
      function getFocusable(container){
        return Array.from(container.querySelectorAll(
          'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
        )).filter(el => el.offsetParent !== null);
      }

      function openModal(modal, openerBtn){
        if(!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex','modal-open');
        document.body.classList.add('modal-lock');

        if(openerBtn) openerBtn.setAttribute('aria-expanded','true');

        // focus trap
        modal.__opener = openerBtn || null;
        const card = modal.querySelector('[data-modal-card]') || modal;
        const focusables = getFocusable(card);
        (focusables[0] || card).focus({preventScroll:true});
      }

      function closeModal(modal){
        if(!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex','modal-open');
        document.body.classList.remove('modal-lock');

        // restore focus
        const opener = modal.__opener;
        if(opener && typeof opener.focus === 'function'){
          opener.setAttribute('aria-expanded','false');
          opener.focus({preventScroll:true});
        }
      }

      function trapFocus(modal, e){
        if(!modal || modal.classList.contains('hidden')) return;
        if(e.key !== 'Tab') return;

        const card = modal.querySelector('[data-modal-card]') || modal;
        const focusables = getFocusable(card);
        if(!focusables.length) return;

        const first = focusables[0];
        const last = focusables[focusables.length - 1];

        if(e.shiftKey && document.activeElement === first){
          e.preventDefault();
          last.focus();
        } else if(!e.shiftKey && document.activeElement === last){
          e.preventDefault();
          first.focus();
        }
      }

      // ---------------------------
      // Mobile menu toggle
      // ---------------------------
      (function(){
        const btn = document.getElementById('menuBtn');
        const menu = document.getElementById('mobileMenu');
        if(!btn || !menu) return;

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
      })();

      // ---------------------------
      // Year selector: reload page with selected year or clear for all years
      // NOTE: server MUST validate year.
      // ---------------------------
      (function(){
        const sel = document.getElementById('yearSelect');
        if(!sel) return;

        sel.addEventListener('change', function(){
          const v = sel.value;
          const params = new URLSearchParams(window.location.search);

          // client-side sanity: only allow 4-digit year or empty
          const ok = !v || /^\d{4}$/.test(v);
          if(!ok){
            params.delete('year');
          } else {
            if(!v) params.delete('year'); else params.set('year', v);
          }

          const q = params.toString();
          const base = window.location.pathname || '/';
          window.location.href = q ? `${base}?${q}` : base;
        });
      })();

      // ---------------------------
      // Dashboard hover style without inline JS (CSP-friendlier)
      // ---------------------------
      (function(){
        const dash = document.getElementById('dashboardBtn');
        if(!dash) return;

        function setHover(on){
          dash.style.background = on
            ? 'linear-gradient(90deg, rgba(165,44,48,.96), rgba(139,46,50,.96))'
            : 'rgba(255,255,255,.85)';
        }
        setHover(false);

        dash.addEventListener('mouseenter', ()=>setHover(true));
        dash.addEventListener('mouseleave', ()=>setHover(false));
      })();

      // ---------------------------
      // Login modal (simulated login kept)
      // ---------------------------
      (function(){
        const openLoginBtn = document.getElementById('loginBtn');
        const loginModal = document.getElementById('loginModal');
        if(!loginModal) return;

        const closeBtns = loginModal.querySelectorAll('[data-close-login]');

        openLoginBtn?.addEventListener('click', () => openModal(loginModal, openLoginBtn));
        closeBtns.forEach(b => b.addEventListener('click', () => closeModal(loginModal)));

        // close on backdrop click
        loginModal.addEventListener('click', e => { if(e.target === loginModal) closeModal(loginModal); });

        // key handling: ESC + focus trap
        document.addEventListener('keydown', e => {
          if(e.key === 'Escape' && !loginModal.classList.contains('hidden')) closeModal(loginModal);
          trapFocus(loginModal, e);
        });
      })();

      // intercept "Open Dashboard" and show login modal first
      (function(){
        const dash = document.getElementById('dashboardBtn');
        const loginBtn = document.getElementById('loginBtn');
        if(dash && loginBtn){
          dash.addEventListener('click', e => {
            e.preventDefault();
            loginBtn.click();
          });
        }
      })();

      // ---------------------------
      // Plaque modal: close on backdrop + ESC + focus trap
      // ---------------------------
      (function(){
        const openBtn = document.getElementById('openPlaqueBtn');
        const modal = document.getElementById('plaqueModal');
        if(!modal) return;

        const closeBtns = modal.querySelectorAll('[data-close-plaque]');

        openBtn?.addEventListener('click', () => openModal(modal, openBtn));
        closeBtns.forEach(b => b.addEventListener('click', () => closeModal(modal)));

        // close on backdrop click
        modal.addEventListener('click', e => { if(e.target === modal) closeModal(modal); });

        document.addEventListener('keydown', e => {
          if(e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(modal);
          trapFocus(modal, e);
        });
      })();

      // ---------------------------
      // Form preview modal wiring: close on backdrop + ESC + focus trap
      // ---------------------------
      (function(){
        const links = document.querySelectorAll('.formLink');
        const modal = document.getElementById('formPreviewModal');
        if(!modal) return;

        const titleEl = document.getElementById('formPreviewLabel');
        const frame = document.getElementById('formPreviewObject');
        const download = document.getElementById('formDownloadLink');
        const fallback = document.getElementById('formDownloadLinkFallback');
        const closeBtns = modal.querySelectorAll('[data-close-form]');

        // tiny allowlist: only allow /forms/... paths (prevents accidental JS: or external)
        function safePath(p){
          if(!p) return '';
          if(p.startsWith('/forms/')) return p;
          return '';
        }

        function showForm(previewFile, title, downloadFile, opener){
          const safePreview = safePath(previewFile) || safePath(downloadFile);
          const safeDownload = safePath(downloadFile) || safePreview;

          if(titleEl) titleEl.textContent = title || '';
          if(frame) frame.setAttribute('src', safePreview || '');
          if(download) download.setAttribute('href', safeDownload || '#');
          if(fallback) fallback.setAttribute('href', safeDownload || '#');

          openModal(modal, opener || null);
        }

        function hideForm(){
          // clear iframe for privacy / stop loading
          if(frame) frame.setAttribute('src','');
          closeModal(modal);
        }

        links.forEach(btn=>{
          btn.addEventListener('click', ()=>{
            const file = btn.getAttribute('data-file');
            const preview = btn.getAttribute('data-preview') || file;
            const title = btn.getAttribute('data-title') || '';
            showForm(preview, title, file, btn);
          });
        });

        closeBtns.forEach(b=>b.addEventListener('click', hideForm));
        modal.addEventListener('click', e=>{ if(e.target===modal) hideForm(); });

        document.addEventListener('keydown', e=>{
          if(e.key==='Escape' && !modal.classList.contains('hidden')) hideForm();
          trapFocus(modal, e);
        });
      })();
    </script>

  </div>

  <!-- Note: Real security must be enforced server-side (auth middleware, policies, throttling). -->
</body>
</html>