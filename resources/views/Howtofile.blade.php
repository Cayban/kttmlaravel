<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — How to File</title>

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
      --card:rgba(255,255,255,.78);
      --shadow: 0 18px 50px rgba(2,6,23,.10);
      --radius: 22px;
    }
    html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .focusRing:focus{ outline:none; box-shadow:0 0 0 4px rgba(165,44,48,.22); }
    .glassHover { transition: transform .18s ease, filter .18s ease; }
    .glassHover:hover { transform: translateY(-2px); filter: brightness(1.02); }

    /* nicer scrollbars */
    .thin-scroll::-webkit-scrollbar{ width: 10px; height: 10px; }
    .thin-scroll::-webkit-scrollbar-thumb{ background: rgba(15,23,42,.18); border-radius: 999px; border: 3px solid rgba(255,255,255,.65); }
    .thin-scroll::-webkit-scrollbar-track{ background: transparent; }

    /* smooth height for collapsible areas */
    .collapse{ transition: max-height .25s ease; overflow:hidden; }
  </style>
</head>

<body class="min-h-screen scroll-smooth text-[color:var(--ink)] overflow-x-hidden bg-[#f6f3ec]">

  {{-- Background image --}}
  <div class="fixed inset-0 -z-20 bg-cover bg-center"
       style="background-image:url('{{ asset('images/bsuBG.jpg') }}');"></div>

  {{-- Overlay --}}
  <div class="fixed inset-0 -z-10"
       style="background:
         radial-gradient(900px 420px at 12% 0%, rgba(240,200,96,.10), transparent 62%),
         radial-gradient(900px 420px at 88% 10%, rgba(165,44,48,.12), transparent 60%),
         linear-gradient(180deg, rgba(250,249,246,.36) 0%, rgba(248,246,241,.44) 55%, rgba(250,249,246,.52) 100%);">
  </div>

  @php
    $guest = $guest ?? (object)[ 'name' => 'Guest Viewer', 'role' => 'Guest' ];

    $urlHome      = url('/');
    $urlGuestHome = url('/guest');
    $urlGuestRec  = url('/guest/records');
    $urlSupport   = url('/support');
    $urlLogin     = url('/login');
    $urlHowTo     = url('/how-to-file');

    $urlForms     = url('/support');
  @endphp

  <div class="mx-auto w-[min(1240px,94vw)] py-4 pb-14">

    {{-- NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_10px_24px_rgba(2,6,23,.12)] bg-white/60 backdrop-blur">
        <div class="relative h-[78px] sm:h-[92px]">
          <img src="{{ asset('images/bannerusb2.jpg') }}" alt="KTTM Header" class="absolute inset-0 h-full w-full object-cover"/>
          <div class="absolute inset-0 bg-black/20"></div>
          <div class="absolute inset-0 opacity-60"
               style="background:
               radial-gradient(600px 220px at 18% 20%, rgba(240,200,96,.25), transparent 62%),
               radial-gradient(600px 220px at 88% 10%, rgba(165,44,48,.20), transparent 60%);"></div>
          <div class="absolute bottom-0 left-0 right-0 h-px bg-white/20"></div>
        </div>

        <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3"
             style="background: linear-gradient(90deg, rgba(153, 34, 38, 0.96), rgba(171, 15, 20, 0.96));">

          <div class="flex items-center gap-3 min-w-[240px]">
            <div class="h-9 w-9 rounded-2xl grid place-items-center font-black"
                 style="background: linear-gradient(135deg, rgba(240,200,96,.95), rgba(232,184,87,.95)); color:#2a1a0b;">
              G
            </div>
            <div class="leading-tight">
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">KTTM Guest Viewer</div>
              <div class="text-white/75 text-xs">
                Welcome • <span class="font-bold text-white">{{ $guest->name }}</span> • View-only
              </div>
            </div>
          </div>

          <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-white/90">
            <a href="{{ $urlGuestHome }}" class="px-3 py-2 rounded-xl hover:bg-white/15 hover:text-white transition">Home</a>
            <a href="{{ $urlGuestRec }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Records</a>
            <a href="{{ $urlHowTo }}" class="px-3 py-2 rounded-xl bg-white/15 text-white ring-1 ring-white/20">How to File?</a>
          </nav>

          <div class="flex items-center gap-2">
           

            <a href="{{ $urlHome }}"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937]
                      font-extrabold text-sm hover:bg-white transition">
              Logout
            </a>
          </div>

        </div>
      </div>
    </header>

    {{-- COMPACT HERO (less tall, less whitespace) --}}
    <section class="mt-5 rounded-[26px] border border-[color:var(--line)] shadow-[var(--shadow)] overflow-hidden">
      <div class="relative p-6 sm:p-7 bg-[color:var(--card)]">
        <div class="absolute inset-0 -z-10"
             style="background:
               radial-gradient(840px 360px at 12% 22%, rgba(240,200,96,.20), transparent 60%),
               radial-gradient(840px 360px at 92% 8%, rgba(165,44,48,.16), transparent 58%);"></div>

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
          <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
              <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/60 text-xs font-extrabold">
                <span class="h-2.5 w-2.5 rounded-full" style="background:var(--gold); box-shadow:0 0 0 6px rgba(240,200,96,.18);"></span>
                How to File (IP Type Guide)
              </div>
              <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/55 text-xs font-extrabold text-[color:var(--muted)]">
                IPOPHL + KTTM evaluation checklist
              </div>
            </div>

            <h1 class="mt-3 text-[clamp(22px,2.7vw,36px)] leading-[1.12] font-black tracking-[-.7px]">
              Choose an IP type — view Steps + Requirements in one clean layout.
            </h1>

            <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed max-w-[92ch]">
              We reduced scrolling by using tabs and collapsible panels. Steps stay visible, while requirements expand only when needed.
            </p>
          </div>

          <div class="flex flex-wrap gap-2">
            <button id="openContactBtn"
              class="focusRing inline-flex items-center gap-2 justify-center px-4 py-2.5 rounded-2xl border border-[color:var(--line)]
                     bg-white/85 text-sm font-extrabold hover:bg-white transition">
              Need help? Contact KTTM
            </button>
            
          </div>
        </div>
      </div>
    </section>

    {{-- MAIN: selector + content (cleaner whitespace, less vertical stacking) --}}
    <section class="mt-4 grid grid-cols-1 lg:grid-cols-[360px_1fr] gap-4 items-start">

      {{-- LEFT: selector (more compact) --}}
      <aside class="lg:sticky lg:top-[118px] rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-6 py-5 border-b border-[color:var(--line)] bg-white/55">
          <div class="flex items-start justify-between gap-3">
            <div>
              <h2 class="font-black text-base tracking-[-.2px]">Select IP Type</h2>
              <p class="mt-1 text-xs text-[color:var(--muted)]">Switch type; content updates instantly.</p>
            </div>
            <span class="rounded-full px-3 py-1.5 text-[11px] font-extrabold border border-black/10 bg-white/70 text-[color:var(--muted)]">
              View-only
            </span>
          </div>
        </div>

        <div class="p-5 sm:p-6 space-y-4">
          <label class="text-xs font-extrabold text-[color:var(--muted)]">IP Type</label>

          <select id="ipTypeSelect"
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/85 px-4 py-3 text-sm font-semibold">
            <option value="patent" selected>Invention Patent</option>
            <option value="utility">Utility Model</option>
            <option value="design">Industrial Design</option>
            <option value="trademark">Trademark</option>
            <option value="copyright">Copyright</option>
          </select>

          {{-- quick action strip --}}
          <div class="grid grid-cols-2 gap-2">
            <button id="jumpSteps"
              class="focusRing inline-flex items-center justify-center px-3 py-2.5 rounded-2xl bg-white/85 border border-black/10 text-xs font-extrabold text-[color:var(--muted)] hover:bg-white transition"
              type="button">
              Jump: Steps
            </button>
            <button id="jumpReq"
              class="focusRing inline-flex items-center justify-center px-3 py-2.5 rounded-2xl bg-white/85 border border-black/10 text-xs font-extrabold text-[color:var(--muted)] hover:bg-white transition"
              type="button">
              Jump: Requirements
            </button>
          </div>

          <!-- downloadable forms section in sidebar -->
          <div class="mt-4">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Forms</div>
            <div class="mt-2 space-y-2">
              <a class="formLink block rounded-2xl border border-[color:var(--line)] bg-white/80 p-3 shadow-sm hover:shadow-md transition" data-file="/forms/BatStateU-FO-RMS-05_Intellectual_Property_Evaluation_Form_Rev._02.docx" data-preview="/forms/BatStateU-FO-RMS-05_Intellectual_Property_Evaluation_Form_Rev._02.pdf" data-title="IP Evaluation Form">
                <div class="flex items-center gap-2">
                  <span class="text-xl">📄</span>
                  <span class="text-xs font-extrabold">IP Evaluation</span>
                </div>
              </a>
              <a class="formLink block rounded-2xl border border-[color:var(--line)] bg-white/80 p-3 shadow-sm hover:shadow-md transition" data-file="/forms/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.docx" data-preview="/forms/BatStateU-FO-RMS-08_Invention-Disclosure-Form_Rev.pdf" data-title="Invention Disclosure Form">
                <div class="flex items-center gap-2">
                  <span class="text-xl">📄</span>
                  <span class="text-xs font-extrabold">Invention Disclosure</span>
                </div>
              </a>
              <a class="formLink block rounded-2xl border border-[color:var(--line)] bg-white/80 p-3 shadow-sm hover:shadow-md transition" data-file="/forms/Copyright_Forms.docx" data-preview="/forms/Copyright_Forms.pdf" data-title="Copyright Form">
                <div class="flex items-center gap-2">
                  <span class="text-xl">📄</span>
                  <span class="text-xs font-extrabold">Copyright</span>
                </div>
              </a>
            </div>
          </div>

          <div class="rounded-2xl border border-black/10 bg-white/70 p-4 text-xs text-[color:var(--muted)] leading-relaxed">
            <span class="font-extrabold text-[color:var(--ink)]">Tip:</span>
            Use <span class="font-extrabold">tabs</span> on the right to reduce scrolling.
          </div>
        </div>
      </aside>

      {{-- RIGHT: redesigned content with tabs + compact cards --}}
      <main class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-6 py-5 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(240,200,96,.18), rgba(165,44,48,.08));">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="min-w-0">
              <h2 class="font-black text-base tracking-[-.2px]">Filing Guide</h2>
              <p id="ipSubtitle" class="mt-1 text-xs text-[color:var(--muted)]">Invention Patent steps and notes.</p>
            </div>

            <div class="flex items-center gap-2">
              
              
            </div>
          </div>
        </div>

        <div class="p-5 sm:p-6">
          <div class="grid grid-cols-1 xl:grid-cols-[1fr_340px] gap-4 items-start">

            {{-- LEFT: Tabs + Panels --}}
            <div class="min-w-0">

              {{-- Header card (compact) --}}
              <div class="rounded-2xl border border-black/10 bg-white/70 p-5">
                <div class="flex items-start justify-between gap-4">
                  <div class="min-w-0">
                    <div class="text-xs font-extrabold text-[color:var(--muted)]">Selected IP Type</div>
                    <div id="ipTitle" class="mt-2 text-lg font-black tracking-[-.2px]">Invention Patent</div>
                    <div class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                      Use tabs below to avoid long scrolling.
                    </div>
                  </div>

                  <div class="hidden sm:flex flex-col gap-2 items-end">
                    <span class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl border border-black/10 bg-white/80 text-xs font-extrabold text-[color:var(--muted)]">
                      <span class="h-2.5 w-2.5 rounded-full" style="background:rgba(165,44,48,.85)"></span>
                      IPOPHL
                    </span>
                    <span class="inline-flex items-center gap-2 px-3 py-2 rounded-2xl border border-black/10 bg-white/80 text-xs font-extrabold text-[color:var(--muted)]">
                      <span class="h-2.5 w-2.5 rounded-full" style="background:rgba(240,200,96,.95)"></span>
                      BatStateU
                    </span>
                  </div>
                </div>

                {{-- Tabs --}}
                <div class="mt-4 flex flex-wrap gap-2">
                  <button data-tab="steps" class="tabBtn focusRing px-4 py-2.5 rounded-2xl text-sm font-extrabold border border-black/10 bg-white/85 hover:bg-white transition">
                    Steps
                  </button>
                  <button data-tab="requirements" class="tabBtn focusRing px-4 py-2.5 rounded-2xl text-sm font-extrabold border border-black/10 bg-white/85 hover:bg-white transition">
                    KTTM Requirements
                  </button>
                  <button data-tab="claim" class="tabBtn focusRing px-4 py-2.5 rounded-2xl text-sm font-extrabold border border-black/10 bg-white/85 hover:bg-white transition">
                    Incentive Claim
                  </button>
                </div>
              </div>

              {{-- Panels --}}
              <div class="mt-3 space-y-3">

                {{-- Steps Panel --}}
                <section id="panel-steps" class="panel" data-panel="steps">
                  <div id="stepsAnchor" class="sr-only"></div>
                  <div id="ipSteps" class="space-y-3">
                    {{-- injected by JS --}}
                  </div>
                </section>

                {{-- Requirements Panel --}}
                <section id="panel-requirements" class="panel hidden" data-panel="requirements">
                  <div id="reqAnchor" class="sr-only"></div>
                  <div id="ipRequirements" class="rounded-2xl border border-black/10 bg-white/72 p-5">
                    {{-- injected by JS --}}
                  </div>
                </section>

                {{-- Incentive claim panel (moved here to reduce right-column height) --}}
                <section id="panel-claim" class="panel hidden" data-panel="claim">
                  <div class="rounded-2xl border border-black/10 bg-white/72 p-5">
                    <div class="flex items-start justify-between gap-3">
                      <div>
                        <div class="text-xs font-extrabold text-[color:var(--muted)]">BatStateU Incentive Claim (From Guide)</div>
                        <div class="mt-2 text-sm font-black">Basic flow</div>
                      </div>
                      <span class="h-10 w-10 rounded-2xl grid place-items-center border border-black/10 bg-white/85">
                        <svg class="h-5 w-5 text-[color:var(--maroon)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <path d="M12 1v22"></path>
                          <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                      </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                      <div class="rounded-2xl border border-black/10 bg-white/80 p-4">
                        <div class="flex items-center gap-2 text-xs font-extrabold text-[color:var(--muted)]">
                          <span class="h-7 w-7 rounded-xl grid place-items-center border border-black/10 bg-white/90">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M4 4h16v12H5.2L4 17.2z"></path>
                            </svg>
                          </span>
                          Step 1: Request
                        </div>
                        <div class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                          Submit request letter for IP incentive and attach:
                          <span class="font-extrabold">Certificate of Registration (IPOPHL)</span> and
                          <span class="font-extrabold">Authority to Collect</span>.
                        </div>
                      </div>

                      <div class="rounded-2xl border border-black/10 bg-white/80 p-4">
                        <div class="flex items-center gap-2 text-xs font-extrabold text-[color:var(--muted)]">
                          <span class="h-7 w-7 rounded-xl grid place-items-center border border-black/10 bg-white/90">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                              <path d="M9 12l2 2 4-4"></path>
                              <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                            </svg>
                          </span>
                          Step 2: Verification
                        </div>
                        <div class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
                          Documents are reviewed and verified. If something is missing, the inventor/author is informed to comply.
                        </div>
                      </div>
                    </div>

                    <div class="mt-3 rounded-2xl border border-black/10 bg-white/85 p-4 text-xs text-[color:var(--muted)]">
                      <span class="font-extrabold text-[color:var(--ink)]">Eligibility:</span>
                      Full-time researchers, faculty, students, and employees affiliated with BatStateU/NEU at the time of application,
                      and who served as inventor/author, may file the claim.
                    </div>
                  </div>
                </section>

              </div>
            </div>

            {{-- RIGHT: Sticky summary only (short) --}}
            <aside class="xl:sticky xl:top-[118px] space-y-3">
              <div class="rounded-2xl border border-black/10 bg-white/72 p-5" id="ipSummaryCard">
                {{-- injected by JS --}}
              </div>

              <div class="rounded-2xl border border-black/10 bg-white/70 p-4 text-xs text-[color:var(--muted)] leading-relaxed">
                <span class="font-extrabold text-[color:var(--ink)]">Note:</span>
                Guests can view only. For official submission, coordinate with KTTM (Step 0).
              </div>
            </aside>

          </div>
        </div>
      </main>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">How to File • Maroon + Gold + Slate</div>
    </footer>

  </div>

  {{-- Contact modal --}}

  {{-- Form preview modal (used by downloadable forms) --}}
  <div id="formPreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="formPreviewLabel">
    <div class="relative max-w-3xl w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-form class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-4">
        <h3 id="formPreviewLabel" class="text-lg font-black text-[color:var(--maroon)]"></h3>
      </div>
      <div class="p-4">
        <div class="w-full h-[60vh]">
          <iframe id="formPreviewObject" src="" width="100%" height="100%" class="border-none">
            <p class="text-sm text-[color:var(--muted)]">Preview not available. <a id="formDownloadLinkFallback" href="#" class="underline">Download the file</a>.</p>
          </iframe>
        </div>
      </div>
      <div class="p-3 flex justify-end gap-2 border-t border-[color:var(--line)]">
        <a id="formDownloadLink" href="#" download class="focusRing px-4 py-2 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">Download</a>
        <button data-close-form class="px-4 py-2 rounded-2xl border border-[color:var(--line)] bg-white/70 text-sm hover:bg-gray-50">Close</button>
      </div>
    </div>
  </div>

  {{-- Contact modal --}}
  <div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="contactModalLabel">
    <div class="relative max-w-sm w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-contact class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-6">
        <h3 id="contactModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Contact KTTM</h3>
        <p class="mt-3 text-sm text-[color:var(--muted)]">Please email us at:</p>
        <div class="mt-2 text-lg font-bold text-[color:var(--maroon)]">itso@g.batstate-u.edu.ph</div>
        <div class="mt-5 text-xs text-[color:var(--muted)]">Copy and paste the address into your mail client.</div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const KTTM_EMAIL = "itso@g.batstate-u.edu.ph";

      const DATA = {
        patent: {
          icon: "beaker",
          title: "Invention Patent",
          subtitle: "For new inventions (product/process/technical improvement).",
          term: "20 years (maintenance fees yearly starting from the 5th year, per guide).",
          benefits: "Exclusive right to exclude others from making/using/selling the invention during the life of the patent.",
          kttm_eval: {
            title: "Send files to KTTM for evaluation",
            note: `All files are sent to <span class="font-extrabold">${KTTM_EMAIL}</span> for evaluation.`,
            blocks: [
              { h:"Required Forms", items:["IP Evaluation Form (BatStateU-FO-RMS-05)","Invention Disclosure Form (BatStateU-FO-RMS-08)"] },
              { h:"Description of the invention / patent", items:["Title","Brief statement of nature and purpose","Short explanation of the drawing (if any)","Detailed enabling description","Abstract of the invention"] },
              { h:"Drawing (if any)", items:["Place drawings in A4 size paper"] }
            ]
          },
          steps: [
            { icon:"edit", h:"Prepare technical documents", p:"Draft description, claims, abstract, and drawings (if applicable). Ensure novelty and inventiveness." },
            { icon:"send", h:"File at IPOPHL", p:"Submit the patent application and pay filing fees (online or via IPOPHL channels)." },
            { icon:"search", h:"Examinations", p:"Formality checks + substantive examination (technical review)." },
            { icon:"award", h:"Receive certificate / proof", p:"Keep the Certificate of Registration and related papers for incentive claim." },
            { icon:"coins", h:"Claim incentive (BatStateU)", p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification." }
          ],
          checklist: [
            "Description, claims, abstract, drawings (if needed)",
            "Inventor/owner details, campus/unit",
            "Certificate of Registration (for incentive claim)",
            "Authority to Collect"
          ]
        },

        utility: {
          icon: "wrench",
          title: "Utility Model",
          subtitle: "For practical improvements (generally faster; no inventive-step requirement like patents).",
          term: "7 years (non-renewable, per guide).",
          benefits: "Exclusive right granted for an invention without requiring inventive step (as described in the guide).",
          kttm_eval: {
            title: "Send files to KTTM for evaluation",
            note: `All files are sent to <span class="font-extrabold">${KTTM_EMAIL}</span> for evaluation.`,
            blocks: [
              { h:"Required Forms", items:["IP Evaluation Form (BatStateU-FO-RMS-05)","Invention Disclosure Form (BatStateU-FO-RMS-08)"] },
              { h:"Description of the utility model", items:["Title","Brief statement of nature and purpose","Short explanation of the drawing (if any)","Detailed enabling description","Abstract"] },
              { h:"Drawing (if any)", items:["Place drawings in A4 size paper"] }
            ]
          },
          steps: [
            { icon:"edit", h:"Prepare documents", p:"Write the technical description and claims. Include drawings if needed." },
            { icon:"send", h:"File at IPOPHL", p:"Submit the utility model application and pay fees." },
            { icon:"clock", h:"Review / processing", p:"Processing depends on IPOPHL rules; utility models typically move faster." },
            { icon:"award", h:"Receive certificate / proof", p:"Keep the Certificate of Registration or proof for your records." },
            { icon:"coins", h:"Claim incentive (BatStateU)", p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification." }
          ],
          checklist: [
            "Description, claims, drawings (if needed)",
            "Inventor/owner details, campus/unit",
            "Certificate of Registration (for incentive claim)",
            "Authority to Collect"
          ]
        },

        design: {
          icon: "palette",
          title: "Industrial Design",
          subtitle: "Protects the appearance (shape/pattern/ornamentation), not the function.",
          term: "5 years, renewable twice (two consecutive periods of 5 years each), per guide.",
          benefits: "Prevents others from making/selling/importing articles bearing a substantially similar copy of the protected design.",
          kttm_eval: {
            title: "Send files to KTTM for evaluation",
            note: `All files are sent to <span class="font-extrabold">${KTTM_EMAIL}</span> for evaluation.`,
            blocks: [
              { h:"Required Form", items:["IP Evaluation Form (BatStateU-FO-RMS-05)"] },
              { h:"Design drawing specification", items:["Draw in A4 size paper with different views:","Top, Bottom, Left, Right, Front, Back, Isometric, Perspective"] }
            ]
          },
          steps: [
            { icon:"image", h:"Prepare design materials", p:"Prepare design drawings/images showing all views; add short design description." },
            { icon:"send", h:"File at IPOPHL", p:"Submit industrial design application with the design representations and pay fees." },
            { icon:"search", h:"Review / publication", p:"Design applications are examined according to IPOPHL procedures." },
            { icon:"award", h:"Receive certificate / proof", p:"Once registered, keep the Certificate of Registration." },
            { icon:"coins", h:"Claim incentive (BatStateU)", p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification." }
          ],
          checklist: [
            "Design drawings/images (required views)",
            "Owner/creator details, campus/unit",
            "Certificate of Registration (for incentive claim)",
            "Authority to Collect"
          ]
        },

        trademark: {
          icon: "badge",
          title: "Trademark",
          subtitle: "Protects your brand name, logo, sign, symbol, or slogan.",
          term: "10 years, renewable for 10 years at a time (per guide).",
          benefits: "Gives the owner the exclusive right to prevent others from using/exploiting the mark.",
          kttm_eval: {
            title: "Send files to KTTM for evaluation",
            note: `All files are sent to <span class="font-extrabold">${KTTM_EMAIL}</span> for evaluation.`,
            blocks: [
              { h:"Required Form", items:["IP Evaluation Form (BatStateU-FO-RMS-06)"] },
              { h:"Digital image of the trademark (Specification)", items:["JPEG format, not exceeding 1MB","2 in × 3 in (50.8 mm × 76.2 mm)","Black & white unless claiming color(s)"] }
            ]
          },
          extraGuidelines: `
            <p class="mt-3 text-sm text-[color:var(--muted)]">
              To facilitate the filing of Trademark registrations, kindly refer to the following guidelines and requirements:
            </p>
            <p class="mt-2 font-bold">For Trademark Filing:</p>
            <ul class="list-disc list-inside text-sm text-[color:var(--muted)]">
              <li>Image file of your logo, program name, or tagline to be protected.</li>
              <li>Identify the class index where your IP belongs by referring to <a href="https://www.wipo.int/classifications/nice/" target="_blank" class="underline text-[color:var(--maroon)]">WIPO Nice Classification</a>.</li>
            </ul>
          `,
          steps: [
            { icon:"scan", h:"Check availability (recommended)", p:"Do a trademark search to see if a similar mark already exists." },
            { icon:"package", h:"Prepare filing details", p:"Prepare mark/logo, owner info, and goods/services classification." },
            { icon:"send", h:"File at IPOPHL", p:"Submit the trademark application and pay fees." },
            { icon:"shield", h:"Publication / opposition", p:"Application may be published; third parties can oppose depending on the process." },
            { icon:"award", h:"Receive certificate / proof", p:"Once registered, keep the Certificate of Registration." },
            { icon:"coins", h:"Claim incentive (BatStateU)", p:"Submit Request Letter + Certificate of Registration (IPOPHL) + Authority to Collect for verification." }
          ],
          checklist: [
            "Mark/logo file + short description",
            "Goods/services classification",
            "Owner details, campus/unit",
            "Certificate of Registration (for incentive claim)",
            "Authority to Collect"
          ]
        },

        copyright: {
          icon: "book",
          title: "Copyright",
          subtitle: "Protects literary, artistic works (including software).",
          term: "Lifetime of the author plus 50 years (per guide).",
          benefits: "Creator holds the exclusive right to use/authorize others to use the work on agreed terms.",
          kttm_eval: {
            title: "Send files to KTTM for evaluation",
            note: `All files are sent to <span class="font-extrabold">${KTTM_EMAIL}</span> for evaluation.`,
            blocks: [
              { h:"Copies and forms", items:[
                "Three (3) hard copies + one (1) soft copy of duly accomplished and notarized Application for Copyright Registration Form (Typewritten)",
                "Three (3) hard copies + one (1) soft copy of Affidavit (Notarized) and Copyright Forms for Multiple or Single Authorship",
                "Two (2) soft copies of the work in CD with label of Title and Authors",
                `One (1) soft copy of the work to be sent to ITSO email address (${KTTM_EMAIL})`
              ]},
              { h:"Packaging", items:[
                "Secure all requirements in one long brown envelope",
                "Important: Ensure correct title on notarized application, NLP on affidavit, and co-ownership forms are matched"
              ]}
            ]
          },
          extraGuidelines: `
            <p class="mt-3 text-sm text-[color:var(--muted)]">
              To facilitate the filing of Copyright registrations with the National Library of the Philippines (NLP), kindly prepare and submit the following requirements:
            </p>
            <ul class="list-disc list-inside text-sm text-[color:var(--muted)]">
              <li>Three (3) hard copies and one (1) soft copy of the duly accomplished and notarized Application for Copyright Registration Form (typewritten).</li>
              <li>Three (3) hard copies and one (1) soft copy of the Affidavit (notarized).</li>
              <li>Two (2) soft copies of the work/thesis being registered, saved in CD format with label indicating the Title and Authors.</li>
              <li>One (1) soft copy of the work/thesis to be sent via email to: <span class="font-bold">ltso@g.batstate-u.edu.ph</span>.</li>
            </ul>
            <p class="mt-2 text-xs text-[color:var(--muted)]">
              Important Note: Please ensure that the title indicated on the notarized Application for Copyright Registration Form, NLP Affidavit, and Affidavit on Copyright Co-ownership are consistent and exactly matched.
            </p>
            <p class="mt-2 text-sm text-[color:var(--muted)]">
              We appreciate it if you can send us a copy of the materials to be copyrighted. Should you have any questions or need clarification, please don’t hesitate to reach out. Thank you.
            </p>
          `,
          steps: [
            { icon:"folder", h:"Prepare your work and details", p:"Prepare copies of the work and author/ownership details." },
            { icon:"info", h:"Registration (optional but helpful)", p:"Copyright exists upon creation; registration helps strengthen evidence." },
            { icon:"send", h:"Submit requirements", p:"Submit the required forms, copies, and any email/CD requirements based on your institution workflow." },
            { icon:"award", h:"Receive proof (if registered)", p:"Keep proof/certificate if registration is completed." },
            { icon:"coins", h:"Claim incentive (BatStateU)", p:"Submit Request Letter + Certificate of Registration (if any) + Authority to Collect for verification." }
          ],
          checklist: [
            "Copy of work + required forms/affidavit",
            "Author/owner details, campus/unit",
            "Certificate/proof (if registered for incentive claim)",
            "Authority to Collect"
          ]
        }
      };

      function iconSvg(name){
        const common = `fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"`;
        const map = {
          edit: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>`,
          send: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4Z"/></svg>`,
          search: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>`,
          award: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>`,
          coins: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6"/><path d="M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/></svg>`,
          clock: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>`,
          image: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m8 13 2-2 4 4 2-2 3 3"/><path d="M8.5 9.5h.01"/></svg>`,
          scan: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M4 7V5a2 2 0 0 1 2-2h2"/><path d="M20 7V5a2 2 0 0 0-2-2h-2"/><path d="M4 17v2a2 2 0 0 0 2 2h2"/><path d="M20 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 12h10"/></svg>`,
          package: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M16.5 9.4 7.5 4.2"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4a2 2 0 0 0 1-1.73Z"/><path d="M3.3 7 12 12l8.7-5"/><path d="M12 22V12"/></svg>`,
          shield: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M12 2 4 5v6c0 5 3.5 9.4 8 11 4.5-1.6 8-6 8-11V5Z"/><path d="M9 12l2 2 4-4"/></svg>`,
          folder: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M3 7a2 2 0 0 1 2-2h5l2 2h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg>`,
          info: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>`,
          beaker: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M9 3v3l-5 9a4 4 0 0 0 3.5 6h9A4 4 0 0 0 20 15l-5-9V3"/><path d="M8 12h8"/></svg>`,
          wrench: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M14.7 6.3a4 4 0 0 0-5.7 5.7L3 18l3 3 6-6a4 4 0 0 0 5.7-5.7l-3 3-2-2 3-3Z"/></svg>`,
          palette: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M12 22a10 10 0 1 1 0-20 6 6 0 0 1 0 12h-1a2 2 0 0 0 0 4h1a2 2 0 0 1 0 4Z"/><path d="M7.5 10.5h.01"/><path d="M9.5 6.5h.01"/><path d="M14.5 6.5h.01"/><path d="M16.5 10.5h.01"/></svg>`,
          badge: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M12 2 4 5v6c0 5 3.5 9.4 8 11 4.5-1.6 8-6 8-11V5Z"/></svg>`,
          book: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg>`,
          check: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M20 6 9 17l-5-5"/></svg>`,
          file: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>`,
          mail: `<svg class="h-5 w-5" viewBox="0 0 24 24" ${common}><path d="M4 4h16v16H4z"/><path d="m4 6 8 7 8-7"/></svg>`
        };
        return map[name] || map.info;
      }

      const selectEl   = document.getElementById("ipTypeSelect");
      const subtitleEl = document.getElementById("ipSubtitle");
      const titleEl    = document.getElementById("ipTitle");
      // contact modal elements
      const openContactBtn = document.getElementById('openContactBtn');
      const contactModal = document.getElementById('contactModal');
      const contactClose = contactModal ? contactModal.querySelector('[data-close-contact]') : null;
      const stepsEl    = document.getElementById("ipSteps");
      const reqEl      = document.getElementById("ipRequirements");

      // contact modal helpers
      function hideContact(){
        if(contactModal){
          const content = contactModal.querySelector('div');
          content.classList.add('scale-95','opacity-0');
          setTimeout(()=>{ contactModal.classList.add('hidden'); },160);
        }
      }

      openContactBtn?.addEventListener('click', ()=>{
        if(contactModal){
          contactModal.classList.remove('hidden');
          requestAnimationFrame(()=>{
            const content = contactModal.querySelector('div');
            content.classList.remove('scale-95','opacity-0');
            content.classList.add('scale-100','opacity-100');
          });
        }
      });
      contactClose?.addEventListener('click', hideContact);
      contactModal?.addEventListener('click', e=>{ if(e.target === contactModal) hideContact(); });
      const summaryEl  = document.getElementById("ipSummaryCard");
      const printBtn   = document.getElementById("printBtn");

      const jumpSteps = document.getElementById("jumpSteps");
      const jumpReq   = document.getElementById("jumpReq");

      // Tabs
      const tabBtns = Array.from(document.querySelectorAll(".tabBtn"));
      const panels  = Array.from(document.querySelectorAll(".panel"));

      function setTab(key){
        tabBtns.forEach(b=>{
          const active = b.getAttribute("data-tab") === key;
          b.classList.toggle("bg-[color:var(--maroon)]", active);
          b.classList.toggle("text-white", active);
          b.classList.toggle("border-transparent", active);
          if(active){
            b.classList.remove("bg-white/85","text-[color:var(--ink)]");
          }else{
            b.classList.add("bg-white/85");
            b.classList.remove("text-white");
            b.classList.add("text-[color:var(--ink)]");
            b.classList.add("border-black/10");
          }
        });
        panels.forEach(p=>{
          p.classList.toggle("hidden", p.getAttribute("data-panel") !== key);
        });
      }

      function stepCard(i, icon, h, p){
        // ✅ More compact + better whitespace + optional two-column grid later
        return `
          <div class="rounded-2xl border border-black/10 bg-white/72 p-4 glassHover">
            <div class="flex items-start gap-3">
              <div class="shrink-0">
                <div class="h-10 w-10 rounded-2xl grid place-items-center border border-black/10 bg-white/85 text-[color:var(--maroon)]">
                  ${iconSvg(icon)}
                </div>
              </div>
              <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-3">
                  <div class="text-xs font-extrabold text-[color:var(--muted)]">Step ${i}</div>
                </div>
                <div class="mt-1 text-sm font-black">${h}</div>
                <div class="mt-1.5 text-sm text-[color:var(--muted)] leading-relaxed">${p}</div>
              </div>
            </div>
          </div>
        `;
      }

      function reqBlock(h, items){
        return `
          <div class="rounded-2xl border border-black/10 bg-white/80 p-4">
            <div class="flex items-center gap-2 text-xs font-extrabold text-[color:var(--muted)]">
              <span class="h-7 w-7 rounded-xl grid place-items-center border border-black/10 bg-white/90 text-[color:var(--maroon)]">
                ${iconSvg("file")}
              </span>
              ${h}
            </div>
            <ul class="mt-2 space-y-1 text-sm text-[color:var(--muted)]">
              ${items.map(x => `
                <li class="flex items-start gap-2">
                  <span class="mt-1 h-2 w-2 rounded-full" style="background:rgba(165,44,48,.75)"></span>
                  <span>${x}</span>
                </li>
              `).join("")}
            </ul>
          </div>
        `;
      }

      function render(typeKey){
        const d = DATA[typeKey] || DATA.patent;

        subtitleEl.textContent = d.subtitle;
        titleEl.textContent = d.title;

        // Steps: use a grid on large screens to reduce vertical scroll
        const stepsGrid = `
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
            ${d.steps.map((s, idx) => stepCard(idx + 1, s.icon || "info", s.h, s.p)).join("")}
          </div>
        `;
        stepsEl.innerHTML = stepsGrid;

        // Requirements panel content (compact + grouped)
        if(d.kttm_eval){
          reqEl.innerHTML = `
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <div class="text-xs font-extrabold text-[color:var(--muted)]">${d.kttm_eval.title}</div>
                <div class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">${d.kttm_eval.note}</div>
              </div>
              <div class="h-10 w-10 rounded-2xl grid place-items-center border border-black/10 bg-white/90 text-[color:var(--maroon)]">
                ${iconSvg("mail")}
              </div>
            </div>

            <div class="mt-3 grid grid-cols-1 gap-3">
              ${(d.kttm_eval.blocks || []).map(b => reqBlock(b.h, b.items || [])).join("")}
            </div>

            <div class="mt-3 rounded-2xl border border-black/10 bg-white/85 p-3 text-xs text-[color:var(--muted)]">
              <span class="font-extrabold text-[color:var(--ink)]">Send to:</span>
              <span class="font-extrabold">${KTTM_EMAIL}</span>
            </div>
          `;
          // append any extra guidelines defined for this IP type
          if(d.extraGuidelines){
            reqEl.innerHTML += d.extraGuidelines;
          }
        }else{
          reqEl.innerHTML = `
            <div class="text-sm text-[color:var(--muted)]">
              No requirements data found for this type.
            </div>
          `;
        }

        // Summary card: keep short to avoid long right-column scroll
        summaryEl.innerHTML = `
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <div class="text-xs font-extrabold text-[color:var(--muted)]">At a glance</div>
              <div class="mt-2 text-sm font-black">${d.title}</div>
            </div>
            <div class="h-10 w-10 rounded-2xl grid place-items-center border border-black/10 bg-white/85 text-[color:var(--maroon)]">
              ${iconSvg(d.icon || "info")}
            </div>
          </div>

          <div class="mt-3 rounded-2xl border border-black/10 bg-white/80 p-4">
            <div class="flex items-center gap-2 text-xs font-extrabold text-[color:var(--muted)]">
              <span class="h-7 w-7 rounded-xl grid place-items-center border border-black/10 bg-white/90 text-[color:var(--maroon)]">
                ${iconSvg("badge")}
              </span>
              Benefits
            </div>
            <div class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">${d.benefits}</div>
          </div>

          <div class="mt-3 rounded-2xl border border-black/10 bg-white/80 p-4">
            <div class="flex items-center gap-2 text-xs font-extrabold text-[color:var(--muted)]">
              <span class="h-7 w-7 rounded-xl grid place-items-center border border-black/10 bg-white/90 text-[color:var(--maroon)]">
                ${iconSvg("clock")}
              </span>
              Term of Protection
            </div>
            <div class="mt-2 text-sm font-black text-[color:var(--maroon)]">${d.term}</div>
          </div>

          <div class="mt-3 rounded-2xl border border-black/10 bg-white/80 p-4">
            <div class="flex items-center gap-2 text-xs font-extrabold text-[color:var(--muted)]">
              <span class="h-7 w-7 rounded-xl grid place-items-center border border-black/10 bg-white/90 text-[color:var(--maroon)]">
                ${iconSvg("check")}
              </span>
              Quick Checklist
            </div>

            <ul class="mt-2 space-y-1 text-sm text-[color:var(--muted)]">
              ${d.checklist.map(x => `
                <li class="flex items-start gap-2">
                  <span class="mt-1 h-2 w-2 rounded-full" style="background:rgba(165,44,48,.75)"></span>
                  <span>${x}</span>
                </li>
              `).join("")}
            </ul>
          </div>
        `;
      }

      // Events
      selectEl?.addEventListener("change", () => render(selectEl.value));
      printBtn?.addEventListener("click", () => window.print());

      tabBtns.forEach(btn=>{
        btn.addEventListener("click", ()=>{
          const key = btn.getAttribute("data-tab");
          setTab(key);
        });
      });

      jumpSteps?.addEventListener("click", ()=>{
        setTab("steps");
        document.getElementById("stepsAnchor")?.scrollIntoView({behavior:"smooth", block:"start"});
      });

      jumpReq?.addEventListener("click", ()=>{
        setTab("requirements");
        document.getElementById("reqAnchor")?.scrollIntoView({behavior:"smooth", block:"start"});
      });

      // form preview modal wiring (same as welcome page)
      (function(){
        const links = document.querySelectorAll('.formLink');
        const modal = document.getElementById('formPreviewModal');
        const content = modal ? modal.querySelector('div.relative') : null;
        const titleEl = document.getElementById('formPreviewLabel');
        const obj = document.getElementById('formPreviewObject');
        const download = document.getElementById('formDownloadLink');
        const fallback = document.getElementById('formDownloadLinkFallback');
        const closeButtons = modal ? modal.querySelectorAll('[data-close-form]') : [];

        function showForm(previewFile, title, downloadFile){
          if(titleEl) titleEl.textContent = title;
          if(obj) obj.setAttribute('src', previewFile);
          if(download) download.setAttribute('href', downloadFile || previewFile);
          if(fallback) fallback.setAttribute('href', downloadFile || previewFile);
          if(modal && content){
            modal.classList.remove('hidden'); modal.classList.add('flex');
            requestAnimationFrame(()=>{ content.classList.remove('scale-95','opacity-0'); content.classList.add('scale-100','opacity-100'); });
            document.body.style.overflow='hidden';
          }
        }
        function hideForm(){
          if(modal && content){
            content.classList.add('scale-95','opacity-0');
            if(obj) obj.setAttribute('src','');
            setTimeout(()=>{ modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow=''; },160);
          }
        }
        links.forEach(btn=>{
          btn.addEventListener('click', ()=>{
            const file = btn.getAttribute('data-file');
            const preview = btn.getAttribute('data-preview') || file;
            const title = btn.getAttribute('data-title') || '';
            showForm(preview, title, file);
          });
        });

        closeButtons.forEach(b=>b.addEventListener('click', hideForm));
        modal?.addEventListener('click', e=>{ if(e.target===modal) hideForm(); });
        document.addEventListener('keydown', e=>{
          if(e.key==='Escape' && modal && !modal.classList.contains('hidden')) hideForm();
        });
      })();

      // Initial
      setTab("steps");
      render(selectEl?.value || "patent");
    })();
  </script>

</body>
</html>