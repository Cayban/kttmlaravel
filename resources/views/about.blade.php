<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — About</title>

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
      --card:rgba(255,255,255,.82);
      --shadow: 0 18px 50px rgba(2,6,23,.10);
      --radius: 22px;
    }
    html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .focusRing:focus{ outline:none; box-shadow:0 0 0 4px rgba(165,44,48,.22); }
    .glass{ background: rgba(255,255,255,.62); backdrop-filter: blur(10px); }
  </style>
</head>

<body class="min-h-screen scroll-smooth text-[color:var(--ink)] overflow-x-hidden bg-[#f6f3ec]">

  {{-- Background --}}
  <div class="fixed inset-0 -z-30 bg-cover bg-center"
       style="background-image:url('{{ asset('images/bsuBG.jpg') }}');"></div>

  {{-- Readability overlay --}}
  <div class="fixed inset-0 -z-20"
       style="background:
        radial-gradient(1100px 520px at 12% 0%, rgba(240,200,96,.12), transparent 60%),
        radial-gradient(1000px 520px at 88% 10%, rgba(165,44,48,.14), transparent 60%),
        linear-gradient(180deg, rgba(246,243,236,.38) 0%, rgba(246,243,236,.54) 55%, rgba(246,243,236,.66) 100%);">
  </div>

  {{-- Extra veil --}}
  <div class="fixed inset-0 -z-10 bg-white/10 backdrop-blur-[1px]"></div>

  @php
    $kttmEmail = $kttmEmail ?? 'itso@g.batstate-u.edu.ph';

    $urlLanding = url('/');
    $urlSupport = url('/support');
    $urlIpAssets = url('/ipassets');
    $urlAbout = url('/about-kttm');
  @endphp

  <div class="mx-auto w-[min(1200px,94vw)] py-4 pb-16">

    {{-- NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_14px_30px_rgba(2,6,23,.14)] glass">

        {{-- TOP IMAGE STRIP --}}
        <div class="relative h-[92px] sm:h-[112px]">
          <img
            src="{{ asset('images/bannerusb2.jpg') }}"
            alt="KTTM Header"
            class="absolute inset-0 h-full w-full object-cover"
          />
          <div class="absolute inset-0 bg-gradient-to-r from-black/40 via-black/15 to-black/30"></div>
          <div class="absolute inset-0 opacity-70"
               style="background: radial-gradient(800px 240px at 20% 0%, rgba(240,200,96,.22), transparent 60%);"></div>

          <div class="absolute bottom-0 left-0 right-0 h-[2px]" style="background:rgba(240,200,96,.55)"></div>

          <div class="absolute left-4 sm:left-6 bottom-3">
            <div class="text-white font-black tracking-[-.3px] text-base sm:text-lg drop-shadow">
              About KTTM
            </div>
            <div class="text-white/80 text-xs sm:text-sm font-semibold drop-shadow">
              BatStateU • Knowledge and Technology Transfer Management
            </div>
          </div>
        </div>

        {{-- LINK BAR --}}
        <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3"
             style="background: linear-gradient(90deg, rgba(153,34,38,.97), rgba(171,15,20,.97));">

          <a href="{{ $urlLanding }}" class="flex items-center gap-3 min-w-[220px]">
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
            <a href="{{ $urlLanding }}#home" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Home</a>
            <a href="{{ $urlLanding }}#services" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Services</a>
            <a href="{{ $urlLanding }}#process" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Process</a>
            <a href="{{ $urlLanding }}#faq" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">FAQ</a>
           

            <a href="{{ $urlAbout }}" class="px-3 py-2 rounded-xl bg-white/15 hover:bg-white/20 transition">
              About KTTM
            </a>
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
            <a href="{{ $urlLanding }}#home" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Home</a>
            <a href="{{ $urlLanding }}#services" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Services</a>
            <a href="{{ $urlLanding }}#process" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Process</a>
            <a href="{{ $urlLanding }}#faq" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">FAQ</a>
            <a href="{{ $urlLanding }}#contact" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Contact</a>
            <a href="{{ $urlAbout }}" class="px-3 py-2 rounded-xl bg-white/15 hover:bg-white/20 transition">About KTTM</a>
          </nav>
        </div>
      </div>
    </header>

    {{-- HERO --}}
    <section class="mt-6 scroll-mt-32 sm:scroll-mt-40 rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)]
                    shadow-[var(--shadow)] p-7 sm:p-9 relative overflow-hidden">
      <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full blur-3xl opacity-70"
           style="background: radial-gradient(circle, rgba(240,200,96,.40), transparent 60%);"></div>
      <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full blur-3xl opacity-70"
           style="background: radial-gradient(circle, rgba(165,44,48,.28), transparent 60%);"></div>

      <div class="relative grid grid-cols-1 lg:grid-cols-[1.15fr_.85fr] gap-5 items-start">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/60 text-xs font-extrabold">
            <span class="h-2.5 w-2.5 rounded-full" style="background:var(--maroon); box-shadow:0 0 0 6px rgba(165,44,48,.18);"></span>
            Knowledge &amp; Technology Transfer Management
          </div>

          <h1 class="mt-4 text-[clamp(34px,4.2vw,54px)] leading-[1.05] font-black tracking-[-1px]">
            Protecting ideas.
            <span class="text-[color:var(--maroon)]">Enabling transfer.</span>
            Supporting <span class="text-[color:var(--maroon)]">commercialization.</span>
          </h1>

          <p class="mt-3 text-base text-[color:var(--muted)] leading-relaxed max-w-[76ch]">
            Learn what KTTM does and understand the IDI workflow—starting from Consultation (Step 1),
            then Request &amp; Submission (Steps 2–3), through Endorsement and Approval (Steps 4–5).
          </p>

          <div class="mt-6 flex flex-wrap gap-2">
            <a href="#roles"
              class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl font-extrabold text-sm
                     text-[#2a1a0b] border border-[rgba(240,200,96,.60)]
                     bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                     shadow-[0_14px_24px_rgba(199,156,59,.20)]
                     hover:brightness-105 hover:-translate-y-[1px] transition">
              KTTM Roles →
            </a>

            <a href="#howtoapply"
              class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-[color:var(--line)]
                     bg-white/75 shadow-sm font-bold text-sm hover:bg-white hover:-translate-y-[1px] transition">
              How to Apply (Step 1–3)
            </a>

            <a href="#idiFlow"
              class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-[color:var(--line)]
                     bg-white/75 shadow-sm font-bold text-sm hover:bg-[color:var(--maroon)] hover:text-white transition">
              IDI Flow (Step 1–5)
            </a>
          </div>
        </div>

        {{-- Contact card --}}
        <aside class="rounded-[26px] border border-[color:var(--line)] bg-white/75 p-6 shadow-sm">
          <div class="text-xs font-extrabold text-[color:var(--muted)]">CONTACT</div>
          <div class="mt-1 text-lg font-black text-[color:var(--maroon)]">KTTM Office</div>
          <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
            For IP consultation, protection, licensing, commercialization, and IDI concerns.
          </p>

          <div class="mt-4 rounded-2xl border border-black/10 bg-white/70 p-4">
            <div class="text-[11px] font-extrabold text-[color:var(--muted)]">Email</div>
            <a class="mt-1 inline-flex items-center gap-2 font-extrabold text-[color:var(--maroon)] hover:text-[color:var(--gold)]"
               href="mailto:{{ $kttmEmail }}">
              {{ $kttmEmail }}
            </a>
            
          </div>

          <div class="mt-4 flex flex-wrap gap-2">
            
          </div>
        </aside>
      </div>
    </section>

    {{-- ROLES --}}
    <section id="roles" class="mt-5 scroll-mt-32 rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
      <div class="p-7 sm:p-9">
        <div class="flex items-end justify-between gap-3">
          <div>
            <h2 class="text-xl font-black tracking-[-.4px]">What KTTM Does</h2>
            <p class="mt-1 text-sm text-[color:var(--muted)]">Core responsibilities and support services.</p>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
          <div class="rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition">
            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-white"
                   style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">✓</div>
              <div>
                <div class="font-black text-[color:var(--maroon)]">IMPLEMENT</div>
                <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed">
                  Approved policies on Intellectual Property, Technology Transfer Protocol, and Spin-off of the University.
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition">
            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-[#2a1a0b]"
                   style="background:linear-gradient(135deg,var(--gold),var(--gold2));">★</div>
              <div>
                <div class="font-black text-[color:var(--maroon)]">EVALUATE</div>
                <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed">
                  Technology assessment of funded projects for potential IP protection and commercialization.
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition">
            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-white bg-emerald-600">✦</div>
              <div>
                <div class="font-black text-[color:var(--maroon)]">PROVIDE</div>
                <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed">
                  Technical training and assistance in IP protection, licensing, and commercialization.
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition">
            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-white bg-amber-600">⇄</div>
              <div>
                <div class="font-black text-[color:var(--maroon)]">FACILITATE</div>
                <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed">
                  Filing potential IP to IPOPHL and the National Library of the Philippines (NLP).
                </p>
              </div>
            </div>
          </div>

          <div class="rounded-[24px] border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition md:col-span-2">
            <div class="flex items-start gap-4">
              <div class="h-12 w-12 rounded-2xl grid place-items-center font-black text-white"
                   style="background:linear-gradient(135deg,#111827,#334155);">⌁</div>
              <div>
                <div class="font-black text-[color:var(--maroon)]">REVIEWS &amp; PROCESS</div>
                <p class="mt-1 text-sm text-[color:var(--muted)] leading-relaxed">
                  Handles claims for IP properly applied/granted in accordance with the BatStateU Technology Transfer Protocol.
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>

    {{-- IDI --}}
    <section id="idi" class="mt-5 scroll-mt-32 rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
      <div class="p-7 sm:p-9">
        <div class="flex items-end justify-between gap-3">
          <div>
            <h2 class="text-xl font-black tracking-[-.4px]">Invention Disclosure Incentive (IDI)</h2>
            <p class="mt-1 text-sm text-[color:var(--muted)] max-w-[85ch]">
              Updated flow: Consultation first, then request submission and review, followed by endorsement and approval.
            </p>
          </div>
        </div>

        {{-- HOW TO APPLY (1–3) --}}
        <div id="howtoapply" class="mt-6 scroll-mt-32 rounded-2xl border border-black/10 bg-white/72 overflow-hidden">
          <div class="px-6 py-4"
               style="background: linear-gradient(90deg, rgba(240,200,96,.95), rgba(232,184,87,.95));">
            <div class="text-[#2a1a0b] font-black tracking-[-.2px] text-base">HOW TO APPLY</div>
            <div class="text-[#2a1a0b]/80 text-xs font-semibold">
              Steps 1–3 (Consultation → Request → Submission)
            </div>
          </div>

          <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-3">
            {{-- STEP 1: Consultation --}}
            <div class="rounded-2xl border border-black/10 bg-white/75 p-6 hover:bg-white hover:shadow-md transition">
              <div class="flex items-center justify-between">
                <div class="text-xs font-extrabold" style="color:var(--maroon)">STEP 1</div>
                <div class="h-9 w-9 rounded-2xl grid place-items-center text-white font-black"
                     style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">1</div>
              </div>
              <div class="mt-2 font-black text-lg text-[color:var(--maroon)]">Consultation</div>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                The inventor/author consults KTTM for guidance on the appropriate IP route, requirements,
                and preparation of the request and attachments.
              </p>
            </div>

            {{-- STEP 2: Request --}}
            <div class="rounded-2xl border border-black/10 bg-white/75 p-6 hover:bg-white hover:shadow-md transition">
              <div class="flex items-center justify-between">
                <div class="text-xs font-extrabold" style="color:var(--maroon)">STEP 2</div>
                <div class="h-9 w-9 rounded-2xl grid place-items-center text-white font-black"
                     style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">2</div>
              </div>
              <div class="mt-2 font-black text-lg text-[color:var(--maroon)]">Request</div>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                The request letter for intellectual property incentive and necessary attachments
                (<span class="font-semibold">Certificate of Registration from IPOPHL</span> and
                <span class="font-semibold">Authority to Collect</span>) are prepared and received from the inventor/author.
              </p>
            </div>

            {{-- STEP 3: Submission / Verification --}}
            <div class="rounded-2xl border border-black/10 bg-white/75 p-6 hover:bg-white hover:shadow-md transition">
              <div class="flex items-center justify-between">
                <div class="text-xs font-extrabold" style="color:var(--maroon)">STEP 3</div>
                <div class="h-9 w-9 rounded-2xl grid place-items-center text-white font-black"
                     style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">3</div>
              </div>
              <div class="mt-2 font-black text-lg text-[color:var(--maroon)]">Submission &amp; Verification</div>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                The submitted letter and documents are <span class="font-semibold">reviewed and verified</span>.
                If there is missing information, the inventor/author will be informed immediately to comply with the requirements.
              </p>
            </div>
          </div>

          {{-- eligibility (compact) --}}
          <div class="px-6 pb-6">
            <div class="rounded-2xl border border-black/10 bg-white/70 p-5">
              <div class="text-xs font-extrabold text-[color:var(--muted)]">ELIGIBILITY</div>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                All full-time researchers, faculty, students, and employees affiliated to BatStateU The NEU during the time of the application
                and who served as the inventor/author of the invention may file the claim.
              </p>
            </div>
          </div>
        </div>

        {{-- FLOW + STEPS 4–5 --}}
        <div id="idiFlow" class="mt-6 scroll-mt-32 grid grid-cols-1 lg:grid-cols-[.95fr_1.05fr] gap-4 items-start">

          {{-- Step cards 4–5 --}}
          <div class="grid gap-3">
            <div class="rounded-2xl border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition cursor-pointer">
              <div class="flex items-center justify-between">
                <div class="text-xs font-extrabold" style="color:var(--gold)">STEP 4</div>
                <div class="h-9 w-9 rounded-2xl grid place-items-center text-white font-black"
                     style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">4</div>
              </div>
              <div class="mt-2 font-black text-lg text-[color:var(--maroon)]">Endorsement</div>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                The Assistant Director of Knowledge and Technology Transfer Management prepares an endorsement letter indicating the request for IP incentive.
              </p>
            </div>

            <div class="rounded-2xl border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition cursor-pointer">
              <div class="flex items-center justify-between">
                <div class="text-xs font-extrabold" style="color:var(--gold)">STEP 5</div>
                <div class="h-9 w-9 rounded-2xl grid place-items-center text-white font-black"
                     style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">5</div>
              </div>
              <div class="mt-2 font-black text-lg text-[color:var(--maroon)]">Sent for Review &amp; Approval</div>
              <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                The endorsement letter and supporting documents are forwarded to the RMS Director for review and approval,
                then routed to the designated authorities for evaluation until it reaches the Office of the University President for final approval.
              </p>
            </div>
          </div>

          {{-- Visual overview --}}
          <div class="rounded-2xl border border-black/10 bg-white/72 p-6 overflow-hidden relative">
            <div class="absolute -top-16 -right-16 h-56 w-56 rounded-full blur-3xl opacity-70"
                 style="background: radial-gradient(circle, rgba(165,44,48,.25), transparent 60%);"></div>

            <div class="relative">
              <div class="text-xs font-extrabold text-[color:var(--muted)]">FLOW OVERVIEW</div>
              <div class="mt-2 font-black text-lg">IDI Request Routing (1–5)</div>
              <p class="mt-1 text-sm text-[color:var(--muted)]">
                Consultation first, then formal request and internal routing.
              </p>

              <div class="mt-5 relative">
                <div class="absolute left-6 top-4 bottom-4 w-[3px] rounded-full"
                     style="background: linear-gradient(180deg, rgba(165,44,48,.85), rgba(240,200,96,.85));"></div>

                <div class="grid gap-4">
                  <div class="pl-16 relative">
                    <div class="absolute left-3 top-1.5 h-6 w-6 rounded-full border-4 border-white shadow"
                         style="background:var(--maroon)"></div>
                    <div class="rounded-2xl border border-black/10 bg-white/70 p-4 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition cursor-pointer">
                      <div class="text-xs font-extrabold text-[color:var(--maroon)]">STEP 1</div>
                      <div class="mt-1 font-black">Consultation</div>
                      <div class="mt-1 text-sm text-[color:var(--muted)]">Inventor/author consults KTTM for guidance and requirements.</div>
                    </div>
                  </div>

                  <div class="pl-16 relative">
                    <div class="absolute left-3 top-1.5 h-6 w-6 rounded-full border-4 border-white shadow"
                         style="background:var(--gold)"></div>
                    <div class="rounded-2xl border border-black/10 bg-white/70 p-4 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition cursor-pointer">
                      <div class="text-xs font-extrabold" style="color:var(--gold2)">STEP 2–3</div>
                      <div class="mt-1 font-black">Request + Verification</div>
                      <div class="mt-1 text-sm text-[color:var(--muted)]">Request letter submitted; KTTM verifies completeness.</div>
                    </div>
                  </div>

                  <div class="pl-16 relative">
                    <div class="absolute left-3 top-1.5 h-6 w-6 rounded-full border-4 border-white shadow"
                         style="background:var(--maroon2)"></div>
                    <div class="rounded-2xl border border-black/10 bg-white/70 p-4 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition cursor-pointer">
                      <div class="text-xs font-extrabold text-[color:var(--muted)]">STEP 4</div>
                      <div class="mt-1 font-black">Endorsement Letter</div>
                      <div class="mt-1 text-sm text-[color:var(--muted)]">Prepared by KTTM for routing and approvals.</div>
                    </div>
                  </div>

                  <div class="pl-16 relative">
                    <div class="absolute left-3 top-1.5 h-6 w-6 rounded-full border-4 border-white shadow"
                         style="background:#111827"></div>
                    <div class="rounded-2xl border border-black/10 bg-white/70 p-4 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition cursor-pointer">
                      <div class="text-xs font-extrabold text-[color:var(--muted)]">STEP 5</div>
                      <div class="mt-1 font-black">Review → Final Approval</div>
                      <div class="mt-1 text-sm text-[color:var(--muted)]">RMS Director → designated authorities → University President.</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-6 text-xs text-[color:var(--muted)]">
                Note: Detailed info about IP types will be added here later.
              </div>
            </div>
          </div>
        </div>

        {{-- COVERAGE --}}
        <div class="mt-6 rounded-2xl border border-black/10 bg-white/72 overflow-hidden">
          <div class="px-6 py-4"
               style="background: linear-gradient(90deg, rgba(165,44,48,.96), rgba(139,46,50,.96));">
            <div class="text-white font-black tracking-[-.2px] text-base">COVERAGE</div>
            <div class="text-white/85 text-xs font-semibold">What the claim applies to</div>
          </div>
          <div class="p-6">
            <p class="text-sm text-[color:var(--muted)] leading-relaxed">
              The claim applies to every invention, utility model, and industrial design applied/granted with BatStateU The NEU as the assignee of the invention.
              For copyright and trademark, the university handles the logistics and shoulders the filing fee only.
            </p>
          </div>
        </div>

      </div>
    </section>

    {{-- CONTACT --}}
    <section id="contactKttm" class="mt-5 scroll-mt-32 rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
      <div class="p-7 sm:p-9">
        <h2 class="text-xl font-black tracking-[-.4px]">About Us / Contacts</h2>
        <p class="mt-1 text-sm text-[color:var(--muted)]">
          For inquiries, coordination, and support related to IP protection and incentives.
        </p>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-4">
          <div class="rounded-2xl border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">EMAIL</div>
            <a href="mailto:{{ $kttmEmail }}"
               class="mt-2 inline-flex items-center gap-2 text-lg font-black text-[color:var(--maroon)] hover:text-[color:var(--gold)]">
              {{ $kttmEmail }}
            </a>
            

            <div class="mt-4 flex flex-wrap gap-2">
              
            </div>
          </div>

          <div class="rounded-2xl border border-black/10 bg-white/72 p-6 hover:bg-white hover:shadow-md transition">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Quick Tip</div>
            <div class="mt-2 font-black text-[color:var(--maroon)]">Using This Guide</div>
            <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
              Refer back to this page whenever you need a refresher on KTTM services, the IDI flow,
              or how to start an IP request. Use the links above to jump to each major section.
            </p>
            <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
              For additional assistance click the "Support" button or send us an email via the
              contact details listed on this page.
            </p>
          </div>
        </div>
      </div>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM • About</div>
      <div class="opacity-90">Palette: <b style="color:var(--maroon)">#A52C30</b> · <b style="color:var(--gold2)">#E8B857</b> · Slate neutrals</div>
    </footer>

    {{-- LOGIN MODAL --}}
    <div id="loginModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="loginModalLabel">
      <div class="relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden">
        <button type="button" data-close-login class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
        <div class="p-6">
          <h3 id="loginModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Sign in to KTTM</h3>
          <p class="mt-1 text-sm text-[color:var(--muted)]">Enter your credentials to access the dashboard.</p>

          <form id="loginForm" data-simulate="true" action="#" method="POST" onsubmit="return false;" class="mt-4 space-y-4">
            @csrf
            <div>
              <label class="text-xs font-semibold text-[color:var(--muted)]">Email</label>
              <input name="email" type="email" required class="mt-1 w-full rounded-lg border border-[color:var(--line)] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />
            </div>
            <div>
              <label class="text-xs font-semibold text-[color:var(--muted)]">Password</label>
              <input name="password" type="password" required class="mt-1 w-full rounded-lg border border-[color:var(--line)] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[color:var(--gold)]" />
            </div>

            <div class="flex items-center justify-between gap-2">
              <label class="inline-flex items-center text-sm gap-2">
                <input name="remember" type="checkbox" class="h-4 w-4 text-[color:var(--maroon)]" />
                <span class="text-sm text-[color:var(--muted)]">Remember me</span>
              </label>
              <a href="{{ $urlSupport }}" class="text-sm text-[color:var(--maroon)] hover:text-[color:var(--gold)]">Need help?</a>
            </div>

            <div class="pt-2">
              <a href="{{ url('/login') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">Sign in</a>
            </div>
          </form>

          <!-- guest login option copied from welcome page -->
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

    <script>
      // Login modal
      (function(){
        const openLoginBtn = document.getElementById('loginBtn');
        const loginModal = document.getElementById('loginModal');
        const closeLogin = loginModal ? loginModal.querySelectorAll('[data-close-login]') : [];

        function showLogin(){
          if(!loginModal) return;
          loginModal.classList.remove('hidden'); loginModal.classList.add('flex');
          document.body.style.overflow='hidden';
          openLoginBtn?.setAttribute('aria-expanded','true');
        }
        function hideLogin(){
          if(!loginModal) return;
          loginModal.classList.add('hidden'); loginModal.classList.remove('flex');
          document.body.style.overflow='';
          openLoginBtn?.setAttribute('aria-expanded','false');
        }

        openLoginBtn?.addEventListener('click', showLogin);
        closeLogin.forEach(b=> b.addEventListener('click', hideLogin));
        loginModal?.addEventListener('click', e=> { if(e.target === loginModal) hideLogin(); });
        document.addEventListener('keydown', e => {
          if(e.key === 'Escape' && loginModal && !loginModal.classList.contains('hidden')) hideLogin();
        });
      })();

      // Mobile menu toggle
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
    </script>

  </div>
</body>
</html>