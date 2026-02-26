<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Records for Guest</title>

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
    
    /* Toast notification styles */
    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      min-width: 300px;
      padding: 16px 20px;
      border-radius: 12px;
      box-shadow: 0 10px 40px rgba(2, 6, 23, 0.15);
      font-weight: 600;
      font-size: 14px;
      animation: slideIn 0.3s ease-out;
    }
    
    .toast.success {
      background: linear-gradient(135deg, var(--gold), var(--gold2));
      color: #2a1a0b;
      border-left: 4px solid var(--maroon);
    }
    
    .toast.error {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
      border-left: 4px solid #b91c1c;
    }
    
    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    
    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(400px);
        opacity: 0;
      }
    }
    
    .toast.hiding {
      animation: slideOut 0.3s ease-out;
    }
  </style>
</head>

<body class="min-h-screen scroll-smooth text-[color:var(--ink)] overflow-x-hidden bg-[#f6f3ec]">

  {{-- Background image --}}
  <div class="fixed inset-0 -z-20 bg-cover bg-center"
       style="background-image:url('{{ asset('images/bsuBG.jpg') }}');"></div>

  {{-- Overlay (keeps bg visible but readable) --}}
  <div class="fixed inset-0 -z-10"
       style="background:
         radial-gradient(900px 420px at 12% 0%, rgba(240,200,96,.10), transparent 62%),
         radial-gradient(900px 420px at 88% 10%, rgba(165,44,48,.12), transparent 60%),
         linear-gradient(180deg, rgba(250,249,246,.36) 0%, rgba(248,246,241,.44) 55%, rgba(250,249,246,.52) 100%);">
  </div>

  @php
    /**
     * ✅ DATABASE-CONNECTED MODE (Records page)
     * Expect: $user, $recent, $allRecords
     */

    $user = $user ?? (object)[ 'name' => 'KTTM User', 'role' => 'Staff' ];

    $recent = $recent ?? [];
    $allRecords = $allRecords ?? [];

    // derive distinct values for client-side filters
    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();

    // ✅ URLs (match YOUR routes)
    $urlDashboard = url('/home');
    // add guest landing URL for Home link
    $urlGuest     = url('/guest');

    // ✅ Your actual records page route is /ip-records (NOT /records)
    $urlRecords   = url('/ip-records');

    $urlNew       = url('/ipassets/create');
    $urlSupport   = url('/support');
    $urlLogout    = url('/logout');           // POST recommended
    $urlProfile   = url('/profile');
    $urlRecordsListPage = url('/ip-records'); // same page (kept for your "Recent" card)
  @endphp

  <div class="mx-auto w-[min(1200px,94vw)] py-4 pb-16">

    {{-- ✅ NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_10px_24px_rgba(2,6,23,.12)] bg-white/60 backdrop-blur">
        {{-- Top image strip --}}
        <div class="relative h-[84px] sm:h-[100px]">
          <img
            src="{{ asset('images/bannerusb2.jpg') }}"
            alt="KTTM Header"
            class="absolute inset-0 h-full w-full object-cover"
          />
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
              K
            </div>
            <div class="leading-tight">
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">KTTM Records</div>
              <div class="text-white/75 text-xs">
                Welcome, <span class="font-bold text-white">{{ $user->name }}</span> • {{ $user->role }}
              </div>
            </div>
          </div>

          {{-- Center nav --}}
          <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-white/90">
            <a href="{{ $urlGuest }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Home</a>
            <a href="{{ $urlRecords }}" class="px-3 py-2 rounded-xl bg-white/15 text-white ring-1 ring-white/20">Records</a>
            <a href="{{ url('/how-to-file') }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">How to File?</a>
          </nav>

          {{-- Right actions --}}
          <div class="flex items-center gap-2">
            {{-- guests cannot create records --}}

            <a href="{{ $urlProfile }}"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937]
                      font-extrabold text-sm hover:bg-white transition">
              Profile
            </a>

            <button id="logoutBtn" type="button" aria-controls="logoutModal" aria-expanded="false"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937]
                      font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-white transition">
              Log out
            </button>
          </div>
        </div>
      </div>
    </header>

    {{-- ✅ HEADER + CONTROLS --}}
    <section class="mt-6">
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] p-7 sm:p-9">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div class="min-w-[260px]">
            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/55 text-xs font-extrabold">
              <span class="h-2.5 w-2.5 rounded-full" style="background:var(--gold); box-shadow:0 0 0 6px rgba(240,200,96,.18);"></span>
              Records Workspace
            </div>

            <h1 class="mt-4 text-[clamp(28px,3.2vw,40px)] leading-[1.05] font-black tracking-[-.8px]">
              Browse & filter IP records.
            </h1>
            <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed max-w-[70ch]">
              Search quickly, then open the full table when you need complete details.
            </p>
          </div>

          <div class="w-full sm:w-[460px]">
            <label class="text-xs font-extrabold text-[color:var(--muted)]">Search + Filters</label>

            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
              <input
                id="viewAllSearch"
                type="search"
                placeholder="Search any column (ID, title, owner...)"
                class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
              />

              <select id="filterStatus" class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="">All statuses</option>
                @foreach($statuses as $st)
                  <option value="{{ $st }}">{{ $st }}</option>
                @endforeach
              </select>

              <select id="filterCampus" class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="">All campuses</option>
                @foreach($campuses as $c)
                  <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
              </select>

              <select id="filterType" class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="">All categories</option>
                @foreach($types as $t)
                  <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
              </select>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
              {{-- edit button removed for guest view --}}

              <button id="applySearchBtn"
                class="focusRing px-4 py-3 rounded-2xl bg-[color:var(--gold)] text-[#2a1a0b] text-sm font-extrabold hover:bg-[color:var(--gold2)] transition">
                Search
              </button>

              <button id="resetFiltersBtn"
                class="focusRing px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
                Reset
              </button>

              <a href="{{ $urlSupport }}"
                 class="focusRing inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
                Support
              </a>

              <button id="openFullPageBtn" type="button" aria-controls="fullTableModal" aria-expanded="false"
                 class="focusRing inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
                View All Records
              </button>
            </div>

            <div class="mt-3 text-xs text-[color:var(--muted)]">
              Tip: Press <span class="font-extrabold">Enter</span> or click <span class="font-extrabold">Search</span> to apply.
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- ✅ TABLES --}}
    <section class="mt-5 grid grid-cols-1 lg:grid-cols-[1.25fr_.75fr] gap-4">

      {{-- Full Records (primary) --}}
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-[color:var(--line)] flex flex-wrap items-center justify-between gap-3">
          <div>
            <h2 class="text-xl font-black tracking-[-.4px]">All IP Records</h2>
            <p class="mt-1 text-sm text-[color:var(--muted)]">Filtered client-side (fast).</p>
          </div>

          {{-- new record link hidden for guest --}}
        </div>

        <div class="p-6 sm:p-8">
          <div class="overflow-auto max-h-[70vh] rounded-2xl border border-black/10 bg-white/65">
            <table id="recordsTable" class="min-w-full text-sm">
              <thead class="bg-white/70 sticky top-0 z-10">
                <tr class="text-left text-xs font-extrabold text-[color:var(--muted)]">
                  <th class="px-4 py-3 whitespace-nowrap cursor-pointer hover:text-[color:var(--maroon)] transition" id="mainSortBtn">Record ID <span id="mainSortIcon">⇅</span></th>
                  <th class="px-4 py-3 min-w-[280px]">IP Title</th>
                  <th class="px-4 py-3 whitespace-nowrap">Category</th>
                  <th class="px-4 py-3 min-w-[240px]">Owner / Inventor</th>
                  <th class="px-4 py-3 whitespace-nowrap">Campus</th>
                  <th class="px-4 py-3 whitespace-nowrap">Status</th>
                  <th class="px-4 py-3 whitespace-nowrap">Date Registered</th>
                  <th class="px-4 py-3 whitespace-nowrap">IPOPhl ID</th>
                  <th class="px-4 py-3 min-w-[220px]">GDrive Link</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-black/10">
                @forelse($allRecords as $r)
                  @php
                    $s = $r['status'] ?? '';
                    $badge =
                      $s === 'Registered'       ? 'bg-emerald-50 text-emerald-800 border-emerald-200' :
                      ($s === 'Under Review'    ? 'bg-blue-50 text-blue-800 border-blue-200' :
                      ($s === 'Filed'           ? 'bg-slate-50 text-slate-800 border-slate-200' :
                      ($s === 'Needs Attention' ? 'bg-amber-50 text-amber-800 border-amber-200' :
                      ($s === 'Returned'        ? 'bg-rose-50 text-rose-800 border-rose-200' :
                                                  'bg-white/70 text-slate-700 border-black/10'))));
                    $dr = $r['registered'] ?? null;
                    $link = $r['gdrive_link'] ?? null;
                  @endphp

                  <tr class="hover:bg-white/60 transition align-top record-row">
                    <td class="px-4 py-3 font-extrabold text-[color:var(--maroon)] whitespace-nowrap record-id">
                      {{ $r['id'] ?? '—' }}
                    </td>

                    <td class="px-4 py-3 record-title">
                      <div class="font-bold">{{ $r['title'] ?? '—' }}</div>
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap record-type">
                      {{ $r['type'] ?? '—' }}
                    </td>

                    <td class="px-4 py-3 record-owner">
                      {{ $r['owner'] ?? '—' }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap record-campus">
                      {{ $r['campus'] ?? '—' }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap record-status">
                      <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-extrabold border {{ $badge }}">
                        {{ $s ?: '—' }}
                      </span>
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap text-[color:var(--muted)] font-bold record-registered">
                      {{ $dr ? \Carbon\Carbon::parse($dr)->format('M d, Y') : '—' }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap record-ipophl">
                      {{ $r['ipophl_id'] ?? '—' }}
                    </td>

                    <td class="px-4 py-3 record-link">
                      @if($link)
                        <a href="{{ $link }}" target="_blank" class="font-extrabold text-[color:var(--maroon)] underline break-all">
                          Open file
                        </a>
                      @else
                        <span class="text-[color:var(--muted)]">—</span>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="px-4 py-10 text-center text-sm text-[color:var(--muted)]">
                      No records available.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div id="resultHint" class="mt-4 text-xs text-[color:var(--muted)]">
            Showing all records.
          </div>
        </div>
      </div>

      {{-- Add-on panel (secondary) --}}
      <aside class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="p-6 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(240,200,96,.22), rgba(165,44,48,.10));">
          <h2 class="font-black text-base tracking-[-.2px]">Add‑On</h2>
          <p class="mt-1 text-xs text-[color:var(--muted)]">A powerful extra tool for guests.</p>
        </div>

        <div class="p-6">
          <h3 class="text-sm font-bold flex items-center gap-1">
            <span>🔎</span> Recently Viewed Records
          </h3>
          <p class="mt-1 text-xs text-[color:var(--muted)]">
            Track the last five records you opened and get quick access without logging in.
          </p>

          <div id="recentlyViewed" class="mt-3 space-y-2">
            <p class="text-sm text-[color:var(--muted)]">No recently viewed records.</p>
          </div>

          {{-- placeholder for additional add-on features --}}
        </div>
      </aside>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">Records Workspace • Maroon + Gold + Slate</div>
    </footer>

  </div>

  {{-- edit search modal removed (guest read-only) --}}

  {{-- edit record modal removed for guest view --}}

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
            <button data-close-logout
                    class="focusRing w-full px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">
              Cancel
            </button>

            <form id="logoutForm" action="{{ $urlLogout }}" method="POST" class="w-full" data-simulate="true">
              @csrf
              <button type="submit"
                      class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
                Sign out
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Full Table Modal --}}
  <div id="fullTableModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="fullTableModalLabel">
    <div id="fullTableModalContent" class="relative max-w-[1200px] w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-fullpage class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100 z-10">✕</button>
      <div class="p-6">
        <div class="flex items-center justify-between gap-4 flex-wrap mb-4">
          <div>
            <h3 id="fullTableModalLabel" class="text-xl font-black text-[color:var(--maroon)]">All IP Records</h3>
            <p class="mt-1 text-sm text-[color:var(--muted)]">Complete list with full details.</p>
          </div>
          {{-- creation disabled for guests --}}
        </div>

        {{-- Filter Controls for Modal --}}
        <div class="mb-4 flex flex-wrap gap-2">
          <input
            id="modalSearch"
            type="search"
            placeholder="Search any column..."
            class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-2 text-sm flex-1 min-w-[200px]"
          />

          <select id="modalFilterStatus" class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-2 text-sm">
            <option value="">All statuses</option>
            @foreach($statuses as $st)
              <option value="{{ $st }}">{{ $st }}</option>
            @endforeach
          </select>

          <select id="modalFilterCampus" class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-2 text-sm">
            <option value="">All campuses</option>
            @foreach($campuses as $c)
              <option value="{{ $c }}">{{ $c }}</option>
            @endforeach
          </select>

          <select id="modalFilterType" class="focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-2 text-sm">
            <option value="">All categories</option>
            @foreach($types as $t)
              <option value="{{ $t }}">{{ $t }}</option>
            @endforeach
          </select>
        </div>

        <div class="overflow-auto max-h-[75vh] rounded-lg border border-black/10 bg-white/60">
          <table class="min-w-full text-sm">
            <thead class="bg-white/70 sticky top-0 z-10">
              <tr class="text-left text-xs font-extrabold text-[color:var(--muted)]">
                <th class="px-4 py-3 whitespace-nowrap cursor-pointer hover:text-[color:var(--maroon)] transition" id="modalSortBtn">Record ID <span id="modalSortIcon">⇅</span></th>
                <th class="px-4 py-3 min-w-[280px]">IP Title</th>
                <th class="px-4 py-3 whitespace-nowrap">Category</th>
                <th class="px-4 py-3 min-w-[240px]">Owner / Inventor</th>
                <th class="px-4 py-3 whitespace-nowrap">Campus</th>
                <th class="px-4 py-3 whitespace-nowrap">Status</th>
                <th class="px-4 py-3 whitespace-nowrap">Date Registered</th>
                <th class="px-4 py-3 whitespace-nowrap">IPOPhl ID</th>
                <th class="px-4 py-3 min-w-[220px]">GDrive Link</th>
              </tr>
            </thead>

            <tbody id="modalTableBody" class="divide-y divide-black/10">
              @forelse($allRecords as $r)
                @php
                  $s = $r['status'] ?? '';
                  $badge =
                    $s === 'Registered'       ? 'bg-emerald-50 text-emerald-800 border-emerald-200' :
                    ($s === 'Under Review'    ? 'bg-blue-50 text-blue-800 border-blue-200' :
                    ($s === 'Filed'           ? 'bg-slate-50 text-slate-800 border-slate-200' :
                    ($s === 'Needs Attention' ? 'bg-amber-50 text-amber-800 border-amber-200' :
                    ($s === 'Returned'        ? 'bg-rose-50 text-rose-800 border-rose-200' :
                    ($s === 'Close to Expiration' ? 'bg-orange-50 text-orange-800 border-orange-200' :
                                                'bg-white/70 text-slate-700 border-black/10')))));
                  $dr = $r['registered'] ?? null;
                  $link = $r['gdrive_link'] ?? null;
                @endphp

                <tr class="hover:bg-white/60 transition align-top modal-record-row" data-status="{{ $s }}" data-campus="{{ $r['campus'] ?? '' }}" data-type="{{ $r['type'] ?? '' }}">
                  <td class="px-4 py-3 font-extrabold text-[color:var(--maroon)] whitespace-nowrap">
                    {{ $r['id'] ?? '—' }}
                  </td>

                  <td class="px-4 py-3">
                    <div class="font-bold">{{ $r['title'] ?? '—' }}</div>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    {{ $r['type'] ?? '—' }}
                  </td>

                  <td class="px-4 py-3">
                    {{ $r['owner'] ?? '—' }}
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    {{ $r['campus'] ?? '—' }}
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-extrabold border {{ $badge }}">
                      {{ $s ?: '—' }}
                    </span>
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap text-[color:var(--muted)] font-bold">
                    {{ $dr ? \Carbon\Carbon::parse($dr)->format('M d, Y') : '—' }}
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap">
                    {{ $r['ipophl_id'] ?? '—' }}
                  </td>

                  <td class="px-4 py-3">
                    @if($link)
                      <a href="{{ $link }}" target="_blank" class="font-extrabold text-[color:var(--maroon)] underline break-all">
                        Open file
                      </a>
                    @else
                      <span class="text-[color:var(--muted)]">—</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="px-4 py-10 text-center text-sm text-[color:var(--muted)]">No records available.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-4 text-xs text-[color:var(--muted)]">Tip: Use the filters above to narrow down your search.</div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      // ---- Toast notification helper ----
      function showToast(message, type = 'success', duration = 4500) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
          toast.classList.add('hiding');
          setTimeout(() => toast.remove(), 300);
        }, duration);
      }

      // ---- Modal helpers ----
      function showModal(modal, content, trigger){
        if(!modal || !content) return;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(()=>{
          content.classList.remove('scale-95','opacity-0');
          content.classList.add('scale-100','opacity-100');
        });
        document.body.style.overflow='hidden';
        trigger?.setAttribute('aria-expanded','true');

        // ✅ focus first close button in whichever modal is opened
        setTimeout(()=>{
          modal.querySelector('[data-close-logout],[data-close-fullpage],[data-close-editsearch],[data-close-editrecord],button')?.focus();
        },120);
      }
      function hideModal(modal, content, trigger){
        if(!modal || !content) return;
        content.classList.add('scale-95','opacity-0');
        setTimeout(()=>{
          modal.classList.add('hidden'); modal.classList.remove('flex');
          document.body.style.overflow='';
          trigger?.setAttribute('aria-expanded','false');
        },160);
      }

      // ---- Logout modal wiring ----
      const logoutBtn = document.getElementById('logoutBtn');
      const logoutModal = document.getElementById('logoutModal');
      const logoutContent = document.getElementById('logoutModalContent');
      const logoutCloseButtons = logoutModal ? logoutModal.querySelectorAll('[data-close-logout]') : [];

      logoutBtn?.addEventListener('click', () => showModal(logoutModal, logoutContent, logoutBtn));
      logoutCloseButtons.forEach(b => b.addEventListener('click', () => hideModal(logoutModal, logoutContent, logoutBtn)));
      logoutModal?.addEventListener('click', e => { if(e.target === logoutModal) hideModal(logoutModal, logoutContent, logoutBtn); });

      // ---- Full page modal wiring ----
      const openFullPageBtn = document.getElementById('openFullPageBtn');
      const fullTableModal = document.getElementById('fullTableModal');
      const fullTableContent = document.getElementById('fullTableModalContent');
      const fullPageCloseButtons = fullTableModal ? fullTableModal.querySelectorAll('[data-close-fullpage]') : [];

      openFullPageBtn?.addEventListener('click', () => showModal(fullTableModal, fullTableContent, openFullPageBtn));
      fullPageCloseButtons.forEach(b => b.addEventListener('click', () => hideModal(fullTableModal, fullTableContent, openFullPageBtn)));
      fullTableModal?.addEventListener('click', e => { if(e.target === fullTableModal) hideModal(fullTableModal, fullTableContent, openFullPageBtn); });

      // edit-related modal wiring removed for read-only guest view

      // ---- Filtering ----
      const q = document.getElementById('viewAllSearch');
      const campus = document.getElementById('filterCampus');
      const type = document.getElementById('filterType');
      const status = document.getElementById('filterStatus');
      const resetBtn = document.getElementById('resetFiltersBtn');
      const searchBtn = document.getElementById('applySearchBtn');
      const resultHint = document.getElementById('resultHint');

      const table = document.getElementById('recordsTable');
      const rows = table ? Array.from(table.querySelectorAll('tbody tr.record-row')) : [];

      function getText(el){ return (el?.textContent || '').trim().toLowerCase(); }

      function filterRows(){
        const needle = (q?.value || '').trim().toLowerCase();
        const campusVal = (campus?.value || '').trim().toLowerCase();
        const typeVal = (type?.value || '').trim().toLowerCase();
        const statusVal = (status?.value || '').trim().toLowerCase();

        let shown = 0;
        rows.forEach(r=>{
          let ok = true;

          if(needle){
            const text = r.textContent.toLowerCase();
            if(!text.includes(needle)) ok = false;
          }

          if(campusVal){
            const v = getText(r.querySelector('.record-campus'));
            if(v !== campusVal) ok = false;
          }

          if(typeVal){
            const v = getText(r.querySelector('.record-type'));
            if(v !== typeVal) ok = false;
          }

          if(statusVal){
            const v = getText(r.querySelector('.record-status'));
            // use exact match to prevent "registered" matching "unregistered"
            if(v !== statusVal) ok = false;
          }

          r.style.display = ok ? '' : 'none';
          if(ok) shown++;
        });

        if(resultHint){
          if(!needle && !campusVal && !typeVal && !statusVal){
            resultHint.textContent = 'Showing all records.';
          }else{
            resultHint.textContent = `Showing ${shown} matching record(s).`;
          }
        }
      }

      q?.addEventListener('keypress', (e)=>{ if(e.key === 'Enter') filterRows(); });

      searchBtn?.addEventListener('click', filterRows);

      resetBtn?.addEventListener('click', ()=>{
        if(q) q.value = '';
        if(campus) campus.value = '';
        if(type) type.value = '';
        if(status) status.value = '';
        filterRows();
      });

      // edit search functionality removed for read-only guest view

      // edit submission logic removed for guest

      // ---- Modal table filtering ----
      const modalSearch = document.getElementById('modalSearch');
      const modalCampus = document.getElementById('modalFilterCampus');
      const modalType = document.getElementById('modalFilterType');
      const modalStatus = document.getElementById('modalFilterStatus');
      const modalBody = document.getElementById('modalTableBody');
      const modalRows = modalBody ? Array.from(modalBody.querySelectorAll('.modal-record-row')) : [];

      function filterModalRows(){
        const needle = (modalSearch?.value || '').trim().toLowerCase();
        const campusVal = (modalCampus?.value || '').trim().toLowerCase();
        const typeVal = (modalType?.value || '').trim().toLowerCase();
        const statusVal = (modalStatus?.value || '').trim().toLowerCase();

        modalRows.forEach(r => {
          let show = true;
          const text = r.textContent.toLowerCase();

          if(needle && !text.includes(needle)) show = false;
          if(campusVal && r.dataset.campus.toLowerCase() !== campusVal) show = false;
          if(typeVal && r.dataset.type.toLowerCase() !== typeVal) show = false;
          if(statusVal && r.dataset.status.toLowerCase() !== statusVal) show = false;

          r.style.display = show ? '' : 'none';
        });
      }

      modalSearch?.addEventListener('input', filterModalRows);
      modalCampus?.addEventListener('change', filterModalRows);
      modalType?.addEventListener('change', filterModalRows);
      modalStatus?.addEventListener('change', filterModalRows);

      // ---- Main table sorting ----
      let mainSortAsc = true;
      const mainSortBtn = document.getElementById('mainSortBtn');
      const mainSortIcon = document.getElementById('mainSortIcon');

      mainSortBtn?.addEventListener('click', () => {
        const visibleRows = rows.filter(r => r.style.display !== 'none');
        visibleRows.sort((a, b) => {
          const aId = (a.querySelector('.record-id')?.textContent || '').trim();
          const bId = (b.querySelector('.record-id')?.textContent || '').trim();
          return mainSortAsc ? aId.localeCompare(bId) : bId.localeCompare(aId);
        });

        visibleRows.forEach(row => table.querySelector('tbody').appendChild(row));
        mainSortAsc = !mainSortAsc;

        if(mainSortIcon){
          mainSortIcon.textContent = mainSortAsc ? '⬆' : '⬇';
        }
      });

      // ---- Modal table sorting ----
      let modalSortAsc = true;
      const modalSortBtn = document.getElementById('modalSortBtn');
      const modalSortIcon = document.getElementById('modalSortIcon');

      modalSortBtn?.addEventListener('click', () => {
        const visibleModalRows = modalRows.filter(r => r.style.display !== 'none');
        visibleModalRows.sort((a, b) => {
          const aId = (a.querySelector('td:first-child')?.textContent || '').trim();
          const bId = (b.querySelector('td:first-child')?.textContent || '').trim();
          return modalSortAsc ? aId.localeCompare(bId) : bId.localeCompare(aId);
        });

        visibleModalRows.forEach(row => modalBody.appendChild(row));
        modalSortAsc = !modalSortAsc;

        if(modalSortIcon){
          modalSortIcon.textContent = modalSortAsc ? '⬆' : '⬇';
        }
      });

      // Escape closes modals
      document.addEventListener('keydown', e => {
        if(e.key === 'Escape'){
          if(logoutModal && !logoutModal.classList.contains('hidden')) hideModal(logoutModal, logoutContent, logoutBtn);
          if(fullTableModal && !fullTableModal.classList.contains('hidden')) hideModal(fullTableModal, fullTableContent, openFullPageBtn);
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

      // ---- Recently viewed records (client-side) ----
      const rvContainer = document.getElementById('recentlyViewed');

      function renderRecentlyViewed(){
        const items = JSON.parse(localStorage.getItem('rv_records')||'[]');
        if(!rvContainer) return;
        if(items.length === 0){
          rvContainer.innerHTML = '<p class="text-sm text-[color:var(--muted)]">No recently viewed records.</p>';
          return;
        }
        rvContainer.innerHTML = items.map(i=>`
          <a href="${urlRecordsListPage}?highlight=${encodeURIComponent(i.id)}" class="block text-[color:var(--maroon)] font-bold underline truncate">
            ${i.title}
          </a>
        `).join('');
      }

      function addRecentlyViewed(entry){
        let arr = JSON.parse(localStorage.getItem('rv_records')||'[]');
        arr.unshift(entry);
        arr = arr.filter((v,i,a)=>a.findIndex(x=>x.id===v.id)===i).slice(0,5);
        localStorage.setItem('rv_records', JSON.stringify(arr));
        renderRecentlyViewed();
      }

      rows.forEach(r=>{
        r.addEventListener('click', ()=>{
          const id = r.querySelector('.record-id')?.textContent.trim();
          const title = r.querySelector('.record-title')?.textContent.trim();
          if(id){ addRecentlyViewed({id,title}); }
        });
      });

      // render stored list on page load
      renderRecentlyViewed();

      // Initial hint
      filterRows();
    })();
  </script>

</body>
</html>
