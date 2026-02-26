<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — Dashboard</title>

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

    /* small utilities */
    .cardPad { padding: 18px; }
    @media (min-width:640px){ .cardPad { padding: 22px; } }

    /* nicer thin scrollbars (optional) */
    .thin-scroll::-webkit-scrollbar{ width: 10px; height: 10px; }
    .thin-scroll::-webkit-scrollbar-thumb{ background: rgba(15,23,42,.18); border-radius: 999px; border: 3px solid rgba(255,255,255,.65); }
    .thin-scroll::-webkit-scrollbar-track{ background: transparent; }
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
    // Safe fallback user
    $user = $user ?? (object)[ 'name' => 'KTTM User', 'role' => 'Staff' ];

    // KPIs passed from route
    $kpis = $kpis ?? [
      'my_open' => 0,
      'needs_attention' => 0,
      'due_soon' => 0,
      'total_records' => 0,
    ];

    // Recent list passed from route
    $recent = $recent ?? [];

    // All records passed from route (for analytics only)
    $allRecords = $allRecords ?? [];

    // ✅ Analytics
    $total = max(1, count($allRecords)); // avoid /0

    $statusCounts = collect($allRecords)->countBy('status')->sortDesc();
    $typeCounts   = collect($allRecords)->countBy('type')->sortDesc();
    $campusCounts = collect($allRecords)->countBy('campus')->sortDesc()->take(5);

    // Helper: percent
    $pct = function($n) use ($total){ return (int) round(($n / $total) * 100); };

    // ✅ clamp: show only top N statuses so it doesn't become too tall
    $statusCountsTop = $statusCounts->take(8);
    $statusOthers = $statusCounts->slice(8)->sum();
    if($statusOthers > 0){
      $statusCountsTop = $statusCountsTop->merge(['Others' => $statusOthers]);
    }

    // ✅ clamp categories too (optional)
    $typeCountsTop = $typeCounts->take(8);
    $typeOthers = $typeCounts->slice(8)->sum();
    if($typeOthers > 0){
      $typeCountsTop = $typeCountsTop->merge(['Others' => $typeOthers]);
    }

    // URLs
    $urlNew      = url('/ipassets/create');
    // staff link points to dedicated route so navbar stays on records view
    $urlRecords  = url('/records');
    $urlSupport  = url('/support');
    $urlLogout   = url('/logout'); // POST recommended
    $urlProfile  = url('/profile');
  @endphp

  <div class="mx-auto w-[min(1200px,94vw)] py-4 pb-16">

    {{-- ✅ NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_10px_24px_rgba(2,6,23,.12)] bg-white/60 backdrop-blur">
        <div class="relative h-[84px] sm:h-[100px]">
          <img src="{{ asset('images/bannerusb2.jpg') }}" alt="KTTM Header" class="absolute inset-0 h-full w-full object-cover"/>
          <div class="absolute inset-0 bg-black/20"></div>
          <div class="absolute bottom-0 left-0 right-0 h-px bg-white/20"></div>
        </div>

        <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3"
             style="background: linear-gradient(90deg, rgba(153, 34, 38, 0.96), rgba(171, 15, 20, 0.96));">

          <div class="flex items-center gap-3 min-w-[240px]">
            <div class="h-9 w-9 rounded-2xl grid place-items-center font-black"
                 style="background: linear-gradient(135deg, rgba(240,200,96,.95), rgba(232,184,87,.95)); color:#2a1a0b;">
              K
            </div>
            <div class="leading-tight">
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">KTTM Dashboard</div>
              <div class="text-white/75 text-xs">
                Welcome, <span class="font-bold text-white">{{ $user->name }}</span> • {{ $user->role }}
              </div>
            </div>
          </div>

          <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-white/90">
            <a href="#overview" class="px-3 py-2 rounded-xl bg-white/15 text-white ring-1 ring-white/20">Dashboard</a>
            <a href="{{ $urlRecords }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Records</a>
            <a href="{{ url('/insights') }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Insights</a>
          </nav>

          <div class="flex items-center gap-2">
            <a href="{{ $urlNew }}"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full font-extrabold text-sm
                      text-[#2a1a0b] border border-[rgba(240,200,96,.65)]
                      bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                      shadow-[0_12px_22px_rgba(199,156,59,.18)]
                      hover:brightness-105 hover:-translate-y-[1px] transition">
              + New Record
            </a>

            <button id="logoutBtn" type="button"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937]
                      font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-white transition">
              Log out
            </button>
          </div>
        </div>
      </div>
    </header>

    {{-- ✅ HERO / OVERVIEW --}}
    <section id="overview" class="mt-6 rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] p-7 sm:p-9">
      <div class="grid grid-cols-1 lg:grid-cols-[1fr_560px] gap-6 items-start">
        {{-- LEFT: intro --}}
        <div class="min-w-0">
          <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/55 text-xs font-extrabold">
            <span class="h-2.5 w-2.5 rounded-full" style="background:var(--gold); box-shadow:0 0 0 6px rgba(240,200,96,.18);"></span>
            Operations Snapshot
          </div>

          <h1 class="mt-4 text-[clamp(28px,3.2vw,40px)] leading-[1.05] font-black tracking-[-.8px]">
            Clean dashboard. Deep insights.
          </h1>

          <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed max-w-[70ch]">
            This page focuses on priorities and analytics. Full record management is now inside
            <span class="font-extrabold">Records</span>.
          </p>

          <div class="mt-5 flex flex-wrap gap-2">
            <a href="{{ $urlRecords }}"
               class="focusRing px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white text-sm font-extrabold hover:bg-[color:var(--maroon2)] transition">
              Go to Records →
            </a>
           
          </div>

          {{-- small info card so left side doesn't feel empty on wide screens --}}
          <div class="mt-6 rounded-2xl border border-black/10 bg-white/65 p-5">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Workflow Tip</div>
            <div class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed">
              Keep status labels consistent (e.g., Draft → Filed → Under Review → Registered) to make analytics accurate.
            </div>
          </div>
        </div>

        {{-- RIGHT: KPI cards (fixed width to remove right-side empty gap) --}}
        <div class="w-full grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="rounded-2xl border border-black/10 bg-white/65 p-5">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Open Records</div>
            <div class="mt-2 text-[34px] font-black text-[color:var(--maroon)] leading-none">{{ $kpis['my_open'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-[color:var(--muted)]">Not yet Registered</div>
          </div>

          <div class="rounded-2xl border border-black/10 bg-white/65 p-5">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Needs Attention</div>
            <div class="mt-2 text-[34px] font-black text-amber-600 leading-none">{{ $kpis['needs_attention'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-[color:var(--muted)]">Action required</div>
          </div>

          <div class="rounded-2xl border border-black/10 bg-white/65 p-5">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Due Soon</div>
            <div class="mt-2 text-[34px] font-black text-emerald-700 leading-none">{{ $kpis['due_soon'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-[color:var(--muted)]">Priority next</div>
          </div>

          <div class="rounded-2xl border border-black/10 bg-white/65 p-5">
            <div class="text-xs font-extrabold text-[color:var(--muted)]">Total Records</div>
            <div class="mt-2 text-[34px] font-black text-slate-900 leading-none">{{ $kpis['total_records'] ?? 0 }}</div>
            <div class="mt-2 text-xs text-[color:var(--muted)]">Archive</div>
          </div>
        </div>
      </div>
    </section>

    {{-- ✅ INSIGHTS (redesigned to prevent tall empty cards) --}}
    <section id="insights" class="mt-5 grid grid-cols-1 lg:grid-cols-[1.2fr_.8fr] gap-4">

      {{-- LEFT COLUMN: Status + Category stacked --}}
      <div class="grid grid-cols-1 gap-4">

        {{-- Status distribution --}}
        <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
          <div class="px-5 py-4 border-b border-[color:var(--line)]"
               style="background: linear-gradient(90deg, rgba(240,200,96,.22), rgba(165,44,48,.10));">
            <div class="flex items-end justify-between gap-3">
              <div>
                <h2 class="font-black text-base tracking-[-.2px]">Status Distribution</h2>
                <p class="mt-1 text-xs text-[color:var(--muted)]">Top statuses (auto groups others).</p>
              </div>
              <div class="text-xs font-extrabold text-[color:var(--muted)]">
                Total: {{ count($allRecords) }}
              </div>
            </div>
          </div>

          <div class="cardPad space-y-3 max-h-[320px] overflow-y-auto thin-scroll">
            @forelse($statusCountsTop as $label => $count)
              @php $percent = $pct($count); @endphp
              <div class="rounded-2xl border border-black/10 bg-white/65 p-4">
                <div class="flex items-center justify-between gap-3">
                  <div class="font-extrabold text-sm">{{ $label ?: '—' }}</div>
                  <div class="text-xs font-black text-[color:var(--muted)]">{{ $count }} • {{ $percent }}%</div>
                </div>
                <div class="mt-3 h-2.5 rounded-full bg-black/10 overflow-hidden">
                  <div class="h-full rounded-full"
                       style="width: {{ $percent }}%; background: linear-gradient(90deg, var(--maroon), var(--gold));"></div>
                </div>
              </div>
            @empty
              <div class="rounded-2xl border border-black/10 bg-white/70 p-5 text-sm text-[color:var(--muted)]">
                No data yet. Add records to see analytics.
              </div>
            @endforelse
          </div>
        </div>

        {{-- Category distribution --}}
        <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
          <div class="px-5 py-4 border-b border-[color:var(--line)]"
               style="background: linear-gradient(90deg, rgba(153, 34, 38, 0.10), rgba(240,200,96,.18));">
            <h2 class="font-black text-base tracking-[-.2px]">Category Mix</h2>
            <p class="mt-1 text-xs text-[color:var(--muted)]">Top categories (auto groups others).</p>
          </div>

          <div class="cardPad space-y-3">
            @forelse($typeCountsTop as $label => $count)
              @php $percent = $pct($count); @endphp
              <div class="rounded-2xl border border-black/10 bg-white/65 p-4">
                <div class="flex items-center justify-between gap-3">
                  <div class="min-w-0">
                    <div class="font-extrabold text-sm truncate">{{ $label ?: '—' }}</div>
                    <div class="mt-1 text-xs text-[color:var(--muted)]">{{ $percent }}%</div>
                  </div>
                  <div class="text-sm font-black text-[color:var(--maroon)]">{{ $count }}</div>
                </div>
              </div>
            @empty
              <div class="rounded-2xl border border-black/10 bg-white/70 p-5 text-sm text-[color:var(--muted)]">
                No categories yet.
              </div>
            @endforelse
          </div>
        </div>

      </div>

      {{-- RIGHT COLUMN: Top campuses + Recent activity --}}
      <div class="grid grid-cols-1 gap-4">

        {{-- Top campuses --}}
        <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
          <div class="px-5 py-4 border-b border-[color:var(--line)]"
               style="background: linear-gradient(90deg, rgba(153, 34, 38, 0.96), rgba(171, 15, 20, 0.86));">
            <h2 class="font-black text-base tracking-[-.2px] text-white">Top Campuses</h2>
            <p class="mt-1 text-xs text-white/90">Highest volume (top 5).</p>
          </div>

          <div class="cardPad space-y-3">
            @forelse($campusCounts as $campus => $count)
              <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
                <div class="flex items-center justify-between text-sm">
                  <div class="font-extrabold">{{ $campus ?: '—' }}</div>
                  <div class="font-black text-[color:var(--maroon)]">{{ $count }}</div>
                </div>
              </div>
            @empty
              <div class="rounded-2xl border border-black/10 bg-white/70 p-5 text-sm text-[color:var(--muted)]">
                No campus data yet.
              </div>
            @endforelse
          </div>
        </div>

        {{-- Calendar (replaces recent activity box) --}}
        <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
          <div class="px-5 py-4 border-b border-[color:var(--line)] bg-white/50">
            <div class="flex items-center justify-between gap-3">
              <div>
                <h2 class="font-black text-base tracking-[-.2px]">Calendar</h2>
                <p class="mt-1 text-xs text-[color:var(--muted)]">Current month view.</p>
              </div>
            </div>
          </div>

          <div class="cardPad space-y-3 max-h-[360px] overflow-y-auto thin-scroll">
            @php
              $calMonth = $calMonth ?? \Carbon\Carbon::now()->month;
              $calYear  = $calYear ?? \Carbon\Carbon::now()->year;
            @endphp

            <div class="flex items-center gap-2 mb-2">
              <select id="calendarMonth" class="focusRing rounded-xl border border-[color:var(--line)] bg-white/70 px-3 py-1 text-sm">
                @foreach(range(1,12) as $m)
                  <option value="{{ $m }}" {{ $m == $calMonth ? 'selected' : '' }}>{{ \Carbon\Carbon::create($calYear, $m, 1)->format('F') }}</option>
                @endforeach
              </select>
              <select id="calendarYear" class="focusRing rounded-xl border border-[color:var(--line)] bg-white/70 px-3 py-1 text-sm">
                @php
                  $currentYear = \Carbon\Carbon::now()->year;
                  $years = range($currentYear - 5, $currentYear + 5);
                @endphp
                @foreach($years as $y)
                  <option value="{{ $y }}" {{ $y == $calYear ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
              </select>
            </div>

            <div id="calendarContainer">
              @include('partials.home_calendar', ['calMonth' => $calMonth, 'calYear' => $calYear])
            </div>
          </div>
        </div>

      </div>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">Dashboard Theme • Maroon + Gold + Slate</div>
    </footer>

  </div>

  {{-- Logout modal --}}
  <div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="logoutModalLabel">
    <div id="logoutModalContent" class="relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-logout class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-6 flex gap-4 items-start">
        <div class="flex-shrink-0">
          <div class="h-12 w-12 rounded-full grid place-items-center text-white font-black" style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">!</div>
        </div>
        <div class="flex-1">
          <h3 id="logoutModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Sign out of KTTM</h3>
          <p class="mt-1 text-sm text-[color:var(--muted)]">This will end your session and return you to the public portal.</p>

          <div class="mt-5 grid grid-cols-2 gap-3">
            <button data-close-logout class="focusRing w-full px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">
              Cancel
            </button>

            <form id="logoutForm" action="{{ $urlLogout }}" method="POST" class="w-full" data-simulate="true">
              @csrf
              <button type="submit" class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
                Sign out
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Note modal for calendar entries --}}
  <div id="noteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="noteModalLabel">
    <div id="noteModalContent" class="relative max-w-sm w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-note class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-4">
        <h3 id="noteModalLabel" class="text-lg font-semibold text-[color:var(--maroon)]"></h3>
        <textarea id="noteModalTextarea" class="w-full h-24 border border-[color:var(--line)] rounded-lg p-2 focus:ring focus:outline-none" placeholder="Enter note..."></textarea>
        <div class="mt-2">
          <label for="noteModalColor" class="text-sm">Color:</label>
          <input id="noteModalColor" type="color" value="#bfdbfe" class="ml-2 align-middle" />
        </div>
      </div>
      <div class="flex justify-between items-center gap-2 p-4 border-t">
        <button id="noteDeleteBtn" class="focusRing px-4 py-2 rounded-2xl border border-red-300 text-red-600 font-extrabold hover:bg-red-50 transition">
          Delete
        </button>
        <div class="flex gap-2">
          <button id="noteCancelBtn" class="focusRing px-4 py-2 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">
            Cancel
          </button>
          <button id="noteSaveBtn" class="focusRing px-4 py-2 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
            Save
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      function showModal(modal, content, trigger){
        if(!modal || !content) return;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(()=>{ content.classList.remove('scale-95','opacity-0'); content.classList.add('scale-100','opacity-100'); });
        document.body.style.overflow='hidden';
        trigger?.setAttribute('aria-expanded','true');
      }
      function hideModal(modal, content, trigger){
        if(!modal || !content) return;
        content.classList.add('scale-95','opacity-0');
        setTimeout(()=>{ modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow=''; trigger?.setAttribute('aria-expanded','false'); },160);
      }

      const logoutBtn = document.getElementById('logoutBtn');
      const logoutModal = document.getElementById('logoutModal');
      const logoutContent = document.getElementById('logoutModalContent');
      const closes = logoutModal ? logoutModal.querySelectorAll('[data-close-logout]') : [];

      logoutBtn?.addEventListener('click', () => showModal(logoutModal, logoutContent, logoutBtn));
      closes.forEach(b => b.addEventListener('click', () => hideModal(logoutModal, logoutContent, logoutBtn)));
      logoutModal?.addEventListener('click', e => { if(e.target === logoutModal) hideModal(logoutModal, logoutContent, logoutBtn); });

      document.addEventListener('keydown', e => {
        if(e.key === 'Escape' && logoutModal && !logoutModal.classList.contains('hidden')){
          hideModal(logoutModal, logoutContent, logoutBtn);
        }
      });

      // Simulated logout (remove in production)
      const logoutForm = document.getElementById('logoutForm');
      if(logoutForm && logoutForm.dataset.simulate === 'true'){
        logoutForm.addEventListener('submit', function(ev){
          ev.preventDefault();
          hideModal(logoutModal, logoutContent, logoutBtn);
          setTimeout(()=>{ window.location.href = '{{ url('/') }}'; }, 220);
        });
      }
    })();
  </script>

  <script>
    (function() {
      // simple modal helpers (copied from logout script so they're available here)
      function showModal(modal, content, trigger){
        if(!modal || !content) return;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(()=>{ content.classList.remove('scale-95','opacity-0'); content.classList.add('scale-100','opacity-100'); });
        document.body.style.overflow='hidden';
        trigger?.setAttribute('aria-expanded','true');
      }
      function hideModal(modal, content, trigger){
        if(!modal || !content) return;
        content.classList.add('scale-95','opacity-0');
        setTimeout(()=>{ modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow=''; trigger?.setAttribute('aria-expanded','false'); },160);
      }

      const monthSel = document.getElementById('calendarMonth');
      const yearSel = document.getElementById('calendarYear');
      const container = document.getElementById('calendarContainer');

      // note modal elements
      const noteModal = document.getElementById('noteModal');
      const noteModalContent = document.getElementById('noteModalContent');
      const noteTextarea = document.getElementById('noteModalTextarea');
      const noteColorInput = document.getElementById('noteModalColor');
      const noteSaveBtn = document.getElementById('noteSaveBtn');
      const noteCancelBtn = document.getElementById('noteCancelBtn');
      const noteDeleteBtn = document.getElementById('noteDeleteBtn');
      let currentDate = null;

      function showNoteModal(date, existing) {
        currentDate = date;
        noteTextarea.value = existing.text || '';
        noteColorInput.value = existing.color || '#bfdbfe';
        document.getElementById('noteModalLabel').textContent = 'Note for ' + date;
        showModal(noteModal, noteModalContent);
        noteTextarea.focus();
        // update legend when modal opens
        setLegend(noteTextarea.value, noteColorInput.value);
      }

      async function updateCalendar() {
        const m = monthSel.value;
        const y = yearSel.value;
        try {
          const resp = await fetch(`/home/calendar?month=${encodeURIComponent(m)}&year=${encodeURIComponent(y)}`);
          if (!resp.ok) throw new Error('Network response was not ok');
          const html = await resp.text();
          container.innerHTML = html;
        } catch (err) {
          console.error('Calendar load error', err);
        }
      }

      // month/year listeners will be bound after we wrap updateCalendar below

      function updateLegendColors() {
        const notes = JSON.parse(localStorage.getItem('calendarNotes') || '{}');
        const colors = [...new Set(Object.values(notes).map(n => n.color || '#bfdbfe'))];
        const legend = document.getElementById('calendarLegend');
        if (legend) {
          legend.innerHTML = colors.map(c =>
            `<span class="inline-block w-3 h-3 rounded-full mr-1 align-middle" style="background:${c}"></span><span class="align-middle">has note</span>`
          ).join(' ');
        }
      }
      function setLegend(text) {
        const preview = document.getElementById('calendarNotePreview');
        if (preview) preview.textContent = text || '';
      }

      function loadNotes() {
        const notes = JSON.parse(localStorage.getItem('calendarNotes') || '{}');
        container.querySelectorAll('td[data-date]').forEach(td => {
          const d = td.getAttribute('data-date');
          if (notes[d]) {
            const color = notes[d].color || '#bfdbfe';
            td.style.backgroundColor = color;
            td.title = notes[d].text;
          } else {
            td.style.backgroundColor = '';
            td.title = '';
          }
        });
        updateLegendColors();
      }

      function saveNote(date, text, color) {
        const notes = JSON.parse(localStorage.getItem('calendarNotes') || '{}');
        if (text) notes[date] = { text, color };
        else delete notes[date];
        localStorage.setItem('calendarNotes', JSON.stringify(notes));
      }

      function attachDateHandlers() {
        container.querySelectorAll('td[data-date]').forEach(td => {
          td.addEventListener('click', () => {
            const d = td.getAttribute('data-date');
            const notes = JSON.parse(localStorage.getItem('calendarNotes') || '{}');
            const current = notes[d] || { text:'', color:'#bfdbfe' };
            setLegend(current.text);
            showNoteModal(d, current);
          });
        });
      }

      // note modal button behaviour
      noteSaveBtn?.addEventListener('click', () => {
        if (!currentDate) return;
        const note = noteTextarea.value.trim();
        const color = noteColorInput.value;
        saveNote(currentDate, note, color);
        loadNotes();
        setLegend(note, color);
        hideModal(noteModal, noteModalContent);
      });
      // live updates for legend when editing
      noteColorInput?.addEventListener('input', () => {
        setLegend(noteTextarea.value.trim(), noteColorInput.value);
      });
      noteTextarea?.addEventListener('input', () => {
        setLegend(noteTextarea.value.trim(), noteColorInput.value);
      });
      noteCancelBtn?.addEventListener('click', () => hideModal(noteModal, noteModalContent));
      const noteCloses = noteModal ? noteModal.querySelectorAll('[data-close-note]') : [];
      noteCloses.forEach(b => b.addEventListener('click', () => hideModal(noteModal, noteModalContent)));
      noteModal?.addEventListener('click', e => { if (e.target === noteModal) hideModal(noteModal, noteModalContent); });
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && noteModal && !noteModal.classList.contains('hidden')) {
          hideModal(noteModal, noteModalContent);
        }
      });
      noteDeleteBtn?.addEventListener('click', () => {
        if (!currentDate) return;
        saveNote(currentDate, '', '');
        loadNotes();
        setLegend('','');
        hideModal(noteModal, noteModalContent);
      });
      // wrap updateCalendar to reinitialize notes
      const origUpdate = updateCalendar;
      updateCalendar = async function() {
        await origUpdate();
        loadNotes();
        attachDateHandlers();
        setLegend('');
      };

      // now that updateCalendar is the wrapped version, attach listeners
      monthSel?.addEventListener('change', updateCalendar);
      yearSel?.addEventListener('change', updateCalendar);

      // initial run
      loadNotes();
      attachDateHandlers();
    })();
  </script>

</body>
</html>
  