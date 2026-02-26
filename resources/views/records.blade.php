<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Records</title>

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

    /* Custom scrollbar for updates container */
    #updatesContainer {
      scrollbar-width: thin;
      scrollbar-color: rgba(165,44,48,0.6) rgba(165,44,48,0.1);
    }

    #updatesContainer::-webkit-scrollbar {
      width: 8px;
    }

    #updatesContainer::-webkit-scrollbar-track {
      background: rgba(165,44,48,0.08);
      border-radius: 10px;
    }

    #updatesContainer::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, var(--gold), var(--maroon));
      border-radius: 10px;
      border: 2px solid rgba(165,44,48,0.1);
    }

    #updatesContainer::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(180deg, var(--gold2), var(--maroon2));
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

    // ✅ Staff page uses dedicated /records route (public guests hit /ip-records)
    $urlRecords   = url('/records');

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
            <a href="{{ $urlDashboard }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Dashboard</a>
            <a href="{{ $urlRecords }}" class="px-3 py-2 rounded-xl bg-white/15 text-white ring-1 ring-white/20">Records</a>
            <a href="{{ url('/insights') }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Insights</a>
          </nav>

          {{-- Right actions --}}
          <div class="flex items-center gap-2">
            <a href="{{ $urlNew }}"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full font-extrabold text-sm
                      text-[#2a1a0b] border border-[rgba(240,200,96,.65)]
                      bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                      shadow-[0_12px_22px_rgba(199,156,59,.18)]
                      hover:brightness-105 hover:-translate-y-[1px] transition">
              + New Record
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
              <button id="editRecordsBtn"
                class="focusRing px-4 py-3 rounded-2xl bg-[color:var(--gold)] text-[#2a1a0b] text-sm font-extrabold hover:bg-[color:var(--gold2)] transition">
                Edit
              </button>

              <button id="applySearchBtn"
                class="focusRing px-4 py-3 rounded-2xl bg-[color:var(--gold)] text-[#2a1a0b] text-sm font-extrabold hover:bg-[color:var(--gold2)] transition">
                Search
              </button>

              <button id="resetFiltersBtn"
                class="focusRing px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
                Reset
              </button>

              <button id="openFullPageBtn" type="button" aria-controls="fullTableModal" aria-expanded="false"
                 class="focusRing inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
                Open Full Page
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
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden flex flex-col h-[70vh]">
        <div class="p-6 sm:p-8 border-b border-[color:var(--line)] flex flex-wrap items-center justify-between gap-3">
          <div>
            <h2 class="text-xl font-black tracking-[-.4px]">All IP Records</h2>
            <p class="mt-1 text-sm text-[color:var(--muted)]">Filtered client-side (fast).</p>
          </div>

          <div class="flex gap-2">
            <a href="{{ $urlNew }}"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-2xl font-extrabold text-sm
                      text-[#2a1a0b] border border-[rgba(240,200,96,.65)]
                      bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                      hover:brightness-105 hover:-translate-y-[1px] transition">
              + New
            </a>

            <button id="downloadBtn" type="button" aria-controls="downloadModal" aria-expanded="false"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-2xl font-extrabold text-sm
                      text-[#2a1a0b] border border-[rgba(15,23,42,.15)]
                      bg-white/80 hover:bg-white transition">
              Download
            </button>
          </div>
        </div>

        <div class="p-6 sm:p-8 flex flex-col flex-1 overflow-hidden">
          <div class="overflow-auto flex-1 rounded-2xl border border-black/10 bg-white/65">
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
                  <th class="px-4 py-3 whitespace-nowrap">Next Due</th>
                  <th class="px-4 py-3 whitespace-nowrap">Validity</th>
                  <th class="px-4 py-3 whitespace-nowrap">National Library ID</th>
                  <th class="px-4 py-3 min-w-[220px]">GDrive Link</th>
                  <th class="px-4 py-3 whitespace-nowrap">Actions</th>
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

                    // calculate due date and validity based on type and registration
                    $due = '—';
                    $validity = '—';
                    if($dr){
                        $date = \Carbon\Carbon::parse($dr);
                        switch(strtolower(trim($r['type'] ?? ''))){
                            case 'patent':
                                $due = $date->copy()->addYears(20)->format('M d, Y');
                                $validity = '20 yrs';
                                break;
                            case 'copyright':
                                // copyright term varies, using 70 years as placeholder
                                $due = $date->copy()->addYears(70)->format('M d, Y');
                                $validity = '70 yrs';
                                break;
                            case 'utility model':
                                $due = $date->copy()->addYears(10)->format('M d, Y');
                                $validity = '10 yrs';
                                break;
                            case 'industrial design':
                                $due = $date->copy()->addYears(15)->format('M d, Y');
                                $validity = '15 yrs';
                                break;
                            default:
                                $due = '—';
                                $validity = '—';
                        }
                    }
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

                    <td class="px-4 py-3 whitespace-nowrap text-[color:var(--muted)] font-bold record-due">
                      {{ $due }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap text-[color:var(--muted)] font-bold record-validity">
                      {{ $validity }}
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

                    <td class="px-4 py-3 whitespace-nowrap flex gap-2">
                      
                      <button type="button" class="printBtn focusRing inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border text-sm font-extrabold hover:bg-gray-50"
                              data-record-id="{{ $r['id'] ?? '' }}">
                        Print
                      </button>
                      <button type="button" class="editBtn focusRing inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-[color:var(--maroon)] text-white text-sm font-extrabold hover:bg-[color:var(--maroon2)] transition"
                              data-record-id="{{ $r['id'] ?? '' }}">
                        Edit
                      </button>
                      <button type="button" class="printBtn focusRing inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border text-sm font-extrabold hover:bg-gray-50"
                              data-record-id="{{ $r['id'] ?? '' }}">
                        Archive
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="11" class="px-4 py-10 text-center text-sm text-[color:var(--muted)]">
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

      {{-- Recent Updates (secondary) --}}
      <aside class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden flex flex-col h-[70vh]">
        <div class="p-6 border-b border-[color:var(--line)] flex items-center justify-between gap-3"
             style="background: linear-gradient(90deg, rgba(240,200,96,.22), rgba(165,44,48,.10));">
          <div>
            <h2 class="font-black text-base tracking-[-.2px]">Recent Updates</h2>
            <p class="mt-1 text-xs text-[color:var(--muted)]">Latest changes.</p>
          </div>
          <button id="refreshUpdatesBtn" type="button"
               class="focusRing inline-flex items-center justify-center px-3 py-2 rounded-xl font-extrabold text-xs
                      text-[#2a1a0b] border border-[rgba(240,200,96,.65)]
                      bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                      hover:brightness-105 transition">
            ↻
          </button>
        </div>

        <div class="p-6 flex-1 flex flex-col overflow-hidden">
          <div id="updatesContainer" class="space-y-2 overflow-y-auto pr-2 flex-1">
            <div class="text-center py-8 text-xs text-[color:var(--muted)]">
              Loading updates...
            </div>
          </div>
        </div>
      </aside>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">Records Workspace • Maroon + Gold + Slate</div>
    </footer>

  </div>

  {{-- Edit Search Modal --}}
  <div id="editSearchModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="editSearchModalLabel">
    <div id="editSearchModalContent" class="relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-editsearch class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-6">
        <h3 id="editSearchModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Find Record to Edit</h3>
        <p class="mt-1 text-sm text-[color:var(--muted)]">Search by Record ID, Title, or Owner name.</p>

        <div class="mt-5">
          <input
            id="editSearchInput"
            type="text"
            placeholder="Search ID, title, or owner..."
            class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
          />
        </div>

        <div id="editSearchResults" class="mt-5 max-h-[400px] overflow-y-auto space-y-2 hidden"></div>

        <div id="editSearchNoResults" class="mt-5 text-sm text-[color:var(--muted)] text-center hidden">
          No records found. Try adjusting your search.
        </div>

        <div class="mt-6 grid grid-cols-2 gap-3">
          <button data-close-editsearch
                  class="focusRing w-full px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">
            Cancel
          </button>

          <button id="editSearchBtn"
                  class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
            Search
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Edit Record Modal --}}
  <div id="editRecordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="editRecordModalLabel">
    <div id="editRecordModalContent" class="relative max-w-2xl w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">
      <button type="button" data-close-editrecord class="sticky top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100 float-right">✕</button>
      <div class="p-6">
        <h3 id="editRecordModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Edit Record</h3>
        <p class="mt-1 text-sm text-[color:var(--muted)]">Update the record information below.</p>

        <form id="editRecordForm" class="mt-6 space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="editField_id" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">Record ID</label>
              <input
                id="editField_id"
                type="text"
                disabled
                class="w-full rounded-2xl border border-[color:var(--line)] bg-gray-100 px-4 py-3 text-sm text-[color:var(--muted)]"
              />
            </div>

            <div>
              <label for="editField_title" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">IP Title</label>
              <input
                id="editField_title"
                type="text"
                class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
              />
            </div>

            <div>
              <label for="editField_type" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">Category</label>
              <select id="editField_type" class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="">Select category</option>
                @foreach($types as $t)
                  <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="editField_owner" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">Owner / Inventor</label>
              <input
                id="editField_owner"
                type="text"
                class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
              />
            </div>

            <div>
              <label for="editField_campus" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">Campus</label>
              <select id="editField_campus" class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="">Select campus</option>
                @foreach($campuses as $c)
                  <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="editField_status" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">Status</label>
              <select id="editField_status" class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="">Select status</option>
                @foreach($statuses as $s)
                  <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="editField_registered" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">Date Registered</label>
              <input
                id="editField_registered"
                type="date"
                class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
              />
            </div>

            <div>
              <label for="editField_ipophl_id" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">IPOPhl ID</label>
              <input
                id="editField_ipophl_id"
                type="text"
                class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
              />
            </div>

            <div class="sm:col-span-2">
              <label for="editField_gdrive_link" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">GDrive Link</label>
              <input
                id="editField_gdrive_link"
                type="url"
                placeholder="https://..."
                class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
              />
            </div>

            <div class="sm:col-span-2">
              <label for="editField_remarks" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">Remarks</label>
              <textarea
                id="editField_remarks"
                class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
                rows="3"
                placeholder="Add any remarks or notes..."
              ></textarea>
            </div>
          </div>

          <div class="mt-8 flex gap-3">
            <button type="button" data-close-editrecord
                    class="focusRing px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">
              Cancel
            </button>

            <button type="submit"
                    class="focusRing px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Logout modal --}}  <div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="logoutModalLabel">
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

  {{-- Download modal --}}
  <div id="downloadModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="downloadModalLabel">
    <div id="downloadModalContent" class="relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-download class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-6">
        <h3 id="downloadModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Download Records</h3>
        <p class="mt-1 text-sm text-[color:var(--muted)]">Choose to download all records or filter by registration date.</p>

        <form id="downloadForm" action="{{ url('/records/export') }}" method="GET" class="mt-4 space-y-4">
          <div class="flex items-center gap-2">
            <input type="radio" name="mode" value="all" id="modeAll" checked>
            <label for="modeAll" class="text-sm font-medium">All records</label>
          </div>
          <div class="flex items-center gap-2">
            <input type="radio" name="mode" value="range" id="modeRange">
            <label for="modeRange" class="text-sm font-medium">Filter by date range</label>
          </div>
          <div class="grid grid-cols-2 gap-2 mt-2">
            <div class="flex items-center gap-2">
              <label for="downloadStart" class="text-sm">From</label>
              <input type="date" name="start" id="downloadStart" class="ml-2 focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-3 py-2 text-sm" disabled>
            </div>
            <div class="flex items-center gap-2">
              <label for="downloadEnd" class="text-sm">To</label>
              <input type="date" name="end" id="downloadEnd" class="ml-2 focusRing rounded-2xl border border-[color:var(--line)] bg-white/70 px-3 py-2 text-sm" disabled>
            </div>
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button type="button" data-close-download class="focusRing px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">Cancel</button>
            <button type="submit" class="focusRing px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">Download</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Changes Details Modal --}}
  <div id="changesModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="changesModalLabel">
    <div id="changesModalContent" class="relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-changes class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-6">
        <h3 id="changesModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Changes Details</h3>
        <p class="mt-1 text-sm text-[color:var(--muted)]" id="changesRecordId">Record #—</p>

        <div id="changesContainer" class="mt-5 space-y-3 max-h-[400px] overflow-y-auto">
          <!-- Changes will be populated here -->
        </div>

        <div class="mt-6">
          <button type="button" data-close-changes
                  class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
            Close
          </button>
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
          <a href="{{ $urlNew }}"
             class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-2xl font-extrabold text-sm
                    text-[#2a1a0b] border border-[rgba(240,200,96,.65)]
                    bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                    hover:brightness-105 hover:-translate-y-[1px] transition">
            + New Record
          </a>
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
                <th class="px-4 py-3 whitespace-nowrap">Next Due</th>
                <th class="px-4 py-3 whitespace-nowrap">Validity</th>
                <th class="px-4 py-3 whitespace-nowrap">IPOPhl ID</th>
                <th class="px-4 py-3 min-w-[220px]">GDrive Link</th>
                <th class="px-4 py-3 whitespace-nowrap">Actions</th>
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

                  // calculate due date and validity
                  $due = '—';
                  $validity = '—';
                  if($dr){
                      $date = \Carbon\Carbon::parse($dr);
                      switch(strtolower(trim($r['type'] ?? ''))){
                          case 'patent':
                              $due = $date->copy()->addYears(20)->format('M d, Y');
                              $validity = '20 yrs';
                              break;
                          case 'copyright':
                              $due = $date->copy()->addYears(70)->format('M d, Y');
                              $validity = '70 yrs';
                              break;
                          case 'utility model':
                              $due = $date->copy()->addYears(10)->format('M d, Y');
                              $validity = '10 yrs';
                              break;
                          case 'industrial design':
                              $due = $date->copy()->addYears(15)->format('M d, Y');
                              $validity = '15 yrs';
                              break;
                          default:
                              $due = '—';
                              $validity = '—';
                      }
                  }
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

                  <td class="px-4 py-3 whitespace-nowrap text-[color:var(--muted)] font-bold">
                    {{ $due }}
                  </td>

                  <td class="px-4 py-3 whitespace-nowrap text-[color:var(--muted)] font-bold">
                    {{ $validity }}
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

                  <td class="px-4 py-3 whitespace-nowrap flex gap-2">
                    <button type="button" class="printBtn focusRing inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white border text-sm font-extrabold hover:bg-gray-50"
                            data-record-id="{{ $r['id'] ?? '' }}">
                      Print
                    </button>
                    <button type="button" class="editBtn focusRing inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-[color:var(--maroon)] text-white text-sm font-extrabold hover:bg-[color:var(--maroon2)] transition"
                            data-record-id="{{ $r['id'] ?? '' }}">
                      Edit
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="12" class="px-4 py-10 text-center text-sm text-[color:var(--muted)]">No records available.</td>
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

      // ---- Edit search modal wiring ----
      const editRecordsBtn = document.getElementById('editRecordsBtn');
      const editSearchModal = document.getElementById('editSearchModal');
      const editSearchContent = document.getElementById('editSearchModalContent');
      const editSearchCloseButtons = editSearchModal ? editSearchModal.querySelectorAll('[data-close-editsearch]') : [];

      editRecordsBtn?.addEventListener('click', () => showModal(editSearchModal, editSearchContent, editRecordsBtn));
      editSearchCloseButtons.forEach(b => b.addEventListener('click', () => hideModal(editSearchModal, editSearchContent, editRecordsBtn)));
      editSearchModal?.addEventListener('click', e => { if(e.target === editSearchModal) hideModal(editSearchModal, editSearchContent, editRecordsBtn); });

      // ---- Edit record modal wiring ----
      const editRecordModal = document.getElementById('editRecordModal');
      const editRecordContent = document.getElementById('editRecordModalContent');
      const editRecordCloseButtons = editRecordModal ? editRecordModal.querySelectorAll('[data-close-editrecord]') : [];

      editRecordCloseButtons.forEach(b => b.addEventListener('click', () => hideModal(editRecordModal, editRecordContent, editRecordsBtn)));
      editRecordModal?.addEventListener('click', e => { if(e.target === editRecordModal) hideModal(editRecordModal, editRecordContent, editRecordsBtn); });

      // ---- Download modal wiring ----
      const downloadBtn = document.getElementById('downloadBtn');
      const downloadModal = document.getElementById('downloadModal');
      const downloadContent = document.getElementById('downloadModalContent');
      const downloadCloseButtons = downloadModal ? downloadModal.querySelectorAll('[data-close-download]') : [];
      const modeAll = document.getElementById('modeAll');
      const modeRange = document.getElementById('modeRange');
      const downloadStart = document.getElementById('downloadStart');
      const downloadEnd = document.getElementById('downloadEnd');
      const downloadForm = document.getElementById('downloadForm');

      downloadBtn?.addEventListener('click', () => showModal(downloadModal, downloadContent, downloadBtn));
      downloadCloseButtons.forEach(b => b.addEventListener('click', () => hideModal(downloadModal, downloadContent, downloadBtn)));
      downloadModal?.addEventListener('click', e => { if(e.target === downloadModal) hideModal(downloadModal, downloadContent, downloadBtn); });

      [modeAll, modeRange].forEach(r => r?.addEventListener('change', () => {
        if(modeRange.checked) {
          downloadStart.disabled = false;
          downloadEnd.disabled = false;
          downloadStart.focus();
        } else {
          downloadStart.disabled = true;
          downloadEnd.disabled = true;
          downloadStart.value = '';
          downloadEnd.value = '';
        }
      }));

      downloadForm?.addEventListener('submit', () => {
        if(modeAll.checked) {
          // disable both to keep URL clean
          downloadStart.disabled = true;
          downloadEnd.disabled = true;
        }
      });

      // ---- Filtering ----
      const q = document.getElementById('viewAllSearch');
      const campus = document.getElementById('filterCampus');
      const type = document.getElementById('filterType');
      const status = document.getElementById('filterStatus');
      const resetBtn = document.getElementById('resetFiltersBtn');
      const searchBtn = document.getElementById('applySearchBtn');
      const resultHint = document.getElementById('resultHint');

      // read query param (q) to prefill search box on load
      (function(){
        const params = new URLSearchParams(window.location.search);
        const term = params.get('q');
        if(term && q) {
          q.value = term;
        }
      })();

      const table = document.getElementById('recordsTable');
      const rows = table ? Array.from(table.querySelectorAll('tbody tr.record-row')) : [];

      // Print/edit button handler (delegated)
      table?.addEventListener('click', (ev) => {
        const btn = ev.target.closest('button');
        if(!btn) return;
        const row = btn.closest('tr.record-row');
        if(!row) return;

        if(btn.classList.contains('printBtn')){
          const id = btn.dataset.recordId || (row.querySelector('.record-id')?.textContent || '').trim();
          const title = (row.querySelector('.record-title')?.textContent || '').trim();
          const owner = (row.querySelector('.record-owner')?.textContent || '').trim();
          const type = (row.querySelector('.record-type')?.textContent || '').trim();
          const campus = (row.querySelector('.record-campus')?.textContent || '').trim();
          const status = (row.querySelector('.record-status')?.textContent || '').trim();
          const registered = (row.querySelector('.record-registered')?.textContent || '').trim();
          const ipophl = (row.querySelector('.record-ipophl')?.textContent || '').trim();

          const win = window.open('', '_blank');
          if(!win) { showToast('Unable to open print window. Check popup blocker.', 'error'); return; }

          const html = `<!doctype html><html><head><meta charset="utf-8"><title>Print ${escapeHtml(title)}</title>
            <style>body{font-family:Arial,Helvetica,sans-serif;padding:24px;color:#111} h1{font-size:20px;margin-bottom:6px} .meta{margin:8px 0;padding:8px;background:#f7f7f7;border-radius:6px} .row{margin:10px 0}</style>
            </head><body>
            <h1>${escapeHtml(title)}</h1>
            <div class="meta"><div><strong>Record ID:</strong> ${escapeHtml(id)}</div>
            <div><strong>Category:</strong> ${escapeHtml(type)}</div>
            <div><strong>Owner/Inventor:</strong> ${escapeHtml(owner)}</div>
            <div><strong>Campus:</strong> ${escapeHtml(campus)}</div>
            <div><strong>Status:</strong> ${escapeHtml(status)}</div>
            <div><strong>Date Registered:</strong> ${escapeHtml(registered)}</div>
            <div><strong>IPOPHL ID:</strong> ${escapeHtml(ipophl)}</div>
            </div>
            <div class="row">Printed: ${new Date().toLocaleString()}</div>
            <script>window.onload=function(){window.print();}<\/script>
            </body></html>`;

          // write and focus
          win.document.open();
          win.document.write(html);
          win.document.close();
        } else if(btn.classList.contains('editBtn')){
          loadRowIntoEdit(row);
        }
      });

      // small helper used by print popup
      function escapeHtml(str){
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
      }

      // helper to populate and open edit modal from a table row
      function loadRowIntoEdit(row){
        if(!row) return;
        let id='', title='', owner='', type='', campus='', status='', registered='', ipophl='', gdrive='', remarks='';
        if(row.classList.contains('modal-record-row')){
            const cells = row.querySelectorAll('td');
            id = (cells[0]?.textContent||'').trim();
            title = (cells[1]?.textContent||'').trim();
            type = (cells[2]?.textContent||'').trim();
            owner = (cells[3]?.textContent||'').trim();
            campus = (cells[4]?.textContent||'').trim();
            status = (cells[5]?.textContent||'').trim();
            registered = (cells[6]?.textContent||'').trim();
            ipophl = (cells[9]?.textContent||'').trim();
            gdrive = (cells[10]?.querySelector('a')?.href||'').trim();
        } else {
            id = (row.querySelector('.record-id')?.textContent||'').trim();
            title = (row.querySelector('.record-title .font-bold')?.textContent||'').trim();
            type = (row.querySelector('.record-type')?.textContent||'').trim();
            owner = (row.querySelector('.record-owner')?.textContent||'').trim();
            campus = (row.querySelector('.record-campus')?.textContent||'').trim();
            status = (row.querySelector('.record-status span')?.textContent||'').trim();
            registered = (row.querySelector('.record-registered')?.textContent||'').trim();
            ipophl = (row.querySelector('.record-ipophl')?.textContent||'').trim();
            gdrive = (row.querySelector('.record-link a')?.href||'').trim();
            remarks = (row.querySelector('.record-remarks')?.textContent||'').trim();
        }

        document.getElementById('editField_id').value = id;
        document.getElementById('editField_title').value = title;
        document.getElementById('editField_type').value = type;
        document.getElementById('editField_owner').value = owner;
        document.getElementById('editField_campus').value = campus;
        document.getElementById('editField_status').value = status;
        document.getElementById('editField_remarks').value = remarks;

        if(registered && registered !== '—'){
            const d = new Date(registered);
            if(!isNaN(d.getTime())){
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth()+1).padStart(2,'0');
                const dd = String(d.getDate()).padStart(2,'0');
                document.getElementById('editField_registered').value = `${yyyy}-${mm}-${dd}`;
            } else {
                document.getElementById('editField_registered').value = '';
            }
        } else {
            document.getElementById('editField_registered').value = '';
        }

        document.getElementById('editField_ipophl_id').value = ipophl;
        document.getElementById('editField_gdrive_link').value = gdrive;

        if(editSearchModal && !editSearchModal.classList.contains('hidden')) hideModal(editSearchModal, editSearchContent, editRecordsBtn);
        // also hide full table modal when editing from within it
        if(fullTableModal && !fullTableModal.classList.contains('hidden')) hideModal(fullTableModal, fullTableContent, openFullPageBtn);
        setTimeout(() => showModal(editRecordModal, editRecordContent, editRecordsBtn), 160);
      }

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

      // apply initial filter (handles q from URL)
      if(q && q.value.trim() !== ''){
        filterRows();
      }

      resetBtn?.addEventListener('click', ()=>{
        if(q) q.value = '';
        if(campus) campus.value = '';
        if(type) type.value = '';
        if(status) status.value = '';
        filterRows();
      });

      // ---- Edit search functionality ----
      const editSearchInput = document.getElementById('editSearchInput');
      const editSearchBtn = document.getElementById('editSearchBtn');
      const editSearchResults = document.getElementById('editSearchResults');
      const editSearchNoResults = document.getElementById('editSearchNoResults');

      function performEditSearch(){
        const searchTerm = (editSearchInput?.value || '').trim().toLowerCase();

        if(!searchTerm){
          showToast('Please enter a search term.', 'error');
          return;
        }

        const results = rows.filter(r => {
          const id = (r.querySelector('.record-id')?.textContent || '').trim().toLowerCase();
          const title = (r.querySelector('.record-title')?.textContent || '').trim().toLowerCase();
          const owner = (r.querySelector('.record-owner')?.textContent || '').trim().toLowerCase();
          return id.includes(searchTerm) || title.includes(searchTerm) || owner.includes(searchTerm);
        });

        editSearchResults.innerHTML = '';
        if(results.length > 0){
          editSearchResults.classList.remove('hidden');
          editSearchNoResults.classList.add('hidden');

          results.forEach(r => {
            const id = (r.querySelector('.record-id')?.textContent || '').trim();
            const title = (r.querySelector('.record-title')?.textContent || '').trim();
            const owner = (r.querySelector('.record-owner')?.textContent || '').trim();

            const resultEl = document.createElement('div');
            resultEl.className = 'rounded-lg border border-[color:var(--line)] bg-white/70 p-3 cursor-pointer hover:bg-[color:var(--gold)] hover:text-white transition';
            resultEl.innerHTML = `
              <div class="font-extrabold text-sm">${id}</div>
              <div class="text-xs mt-1">${title}</div>
              <div class="text-xs text-[color:var(--muted)] mt-1">${owner}</div>
            `;

            resultEl.addEventListener('click', () => {
              const recordRow = rows.find(x => (x.querySelector('.record-id')?.textContent || '').trim() === id);
              if(!recordRow) return;

              document.getElementById('editField_id').value = (recordRow.querySelector('.record-id')?.textContent || '').trim();

              // ✅ title is wrapped inside <div class="font-bold">...</div> so get the inner div text cleanly
              const titleDiv = recordRow.querySelector('.record-title .font-bold');
              document.getElementById('editField_title').value = (titleDiv?.textContent || '').trim();

              document.getElementById('editField_type').value = (recordRow.querySelector('.record-type')?.textContent || '').trim();
              document.getElementById('editField_owner').value = (recordRow.querySelector('.record-owner')?.textContent || '').trim();
              document.getElementById('editField_campus').value = (recordRow.querySelector('.record-campus')?.textContent || '').trim();

              const statusBadge = recordRow.querySelector('.record-status span');
              document.getElementById('editField_status').value = (statusBadge?.textContent || '').trim();

              const dateText = (recordRow.querySelector('.record-registered')?.textContent || '').trim();
              if(dateText && dateText !== '—'){
                const date = new Date(dateText);
                if(!isNaN(date.getTime())){
                  const yyyy = date.getFullYear();
                  const mm = String(date.getMonth() + 1).padStart(2, '0');
                  const dd = String(date.getDate()).padStart(2, '0');
                  document.getElementById('editField_registered').value = `${yyyy}-${mm}-${dd}`;
                }
              } else {
                document.getElementById('editField_registered').value = '';
              }

              document.getElementById('editField_ipophl_id').value = (recordRow.querySelector('.record-ipophl')?.textContent || '').trim();
              document.getElementById('editField_gdrive_link').value = (recordRow.querySelector('.record-link a')?.href || '').trim();

              hideModal(editSearchModal, editSearchContent, editRecordsBtn);
              if(editSearchInput) editSearchInput.value = '';
              editSearchResults.innerHTML = '';
              editSearchResults.classList.add('hidden');

              setTimeout(() => showModal(editRecordModal, editRecordContent, editRecordsBtn), 160);
            });

            editSearchResults.appendChild(resultEl);
          });

        }else{
          editSearchResults.classList.add('hidden');
          editSearchNoResults.classList.remove('hidden');
        }
      }

      editSearchBtn?.addEventListener('click', performEditSearch);
      editSearchInput?.addEventListener('keypress', (e) => { if(e.key === 'Enter') performEditSearch(); });

      // ---- Edit record form submission ----
      const editRecordForm = document.getElementById('editRecordForm');
      editRecordForm?.addEventListener('submit', async (e) => {
        e.preventDefault();

        // ✅ IMPORTANT: trim + encode the ID so the URL matches your record_id exactly
        const rawId = document.getElementById('editField_id').value || '';
        const id = encodeURIComponent(rawId.trim());

        const payload = {
          title: (document.getElementById('editField_title').value || '').trim(),
          type: (document.getElementById('editField_type').value || '').trim(),
          owner: (document.getElementById('editField_owner').value || '').trim(),
          campus: (document.getElementById('editField_campus').value || '').trim(),
          status: (document.getElementById('editField_status').value || '').trim(),
          registered: (document.getElementById('editField_registered').value || '').trim(),
          ipophl_id: (document.getElementById('editField_ipophl_id').value || '').trim(),
          gdrive_link: (document.getElementById('editField_gdrive_link').value || '').trim(),
          remarks: (document.getElementById('editField_remarks')?.value || '').trim(),
        };

        try {
          const response = await fetch(`/records/${id}/update`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify(payload)
          });

          // ✅ if Laravel returns an HTML error page, .json() will throw
          const ct = response.headers.get('content-type') || '';
          const result = ct.includes('application/json')
            ? await response.json()
            : { success: false, message: 'Server did not return JSON. Check Network tab for the response.' };

          if (!response.ok) {
            showToast(`Error (${response.status}): ${result.message || 'Request failed'}`, 'error');
            return;
          }

          if (result.success) {
            showToast(`✓ Record updated successfully!`, 'success');
            hideModal(editRecordModal, editRecordContent, editRecordsBtn);
            editRecordForm.reset();
            setTimeout(() => window.location.reload(), 400);
          } else {
            showToast(`Error: ${result.message || 'Unknown error'}`, 'error');
          }
        } catch (error) {
          console.error('Save Error:', error);
          showToast('Failed to save record. Check the browser console for details.', 'error');
        }
      });

      // ---- Modal table filtering ----
      const modalSearch = document.getElementById('modalSearch');
      const modalCampus = document.getElementById('modalFilterCampus');
      const modalType = document.getElementById('modalFilterType');
      const modalStatus = document.getElementById('modalFilterStatus');
      const modalBody = document.getElementById('modalTableBody');
      const modalRows = modalBody ? Array.from(modalBody.querySelectorAll('.modal-record-row')) : [];

      // handle actions inside modal (print / edit)
      modalBody?.addEventListener('click', (ev) => {
        const btn = ev.target.closest('button');
        if(!btn) return;
        const row = btn.closest('tr.modal-record-row');
        if(!row) return;

        if(btn.classList.contains('printBtn')){
          const cells = row.querySelectorAll('td');
          const id = btn.dataset.recordId || (cells[0]?.textContent||'').trim();
          const title = (cells[1]?.textContent||'').trim();
          const owner = (cells[3]?.textContent||'').trim();
          const type = (cells[2]?.textContent||'').trim();
          const campus = (cells[4]?.textContent||'').trim();
          const status = (cells[5]?.textContent||'').trim();
          const registered = (cells[6]?.textContent||'').trim();
          const ipophl = (cells[9]?.textContent||'').trim();

          const win = window.open('', '_blank');
          if(!win) { showToast('Unable to open print window. Check popup blocker.', 'error'); return; }

          const html = `<!doctype html><html><head><meta charset="utf-8"><title>Print ${escapeHtml(title)}</title>
            <style>body{font-family:Arial,Helvetica,sans-serif;padding:24px;color:#111} h1{font-size:20px;margin-bottom:6px} .meta{margin:8px 0;padding:8px;background:#f7f7f7;border-radius:6px} .row{margin:10px 0}</style>
            </head><body>
            <h1>${escapeHtml(title)}</h1>
            <div class="meta"><div><strong>Record ID:</strong> ${escapeHtml(id)}</div>
            <div><strong>Category:</strong> ${escapeHtml(type)}</div>
            <div><strong>Owner/Inventor:</strong> ${escapeHtml(owner)}</div>
            <div><strong>Campus:</strong> ${escapeHtml(campus)}</div>
            <div><strong>Status:</strong> ${escapeHtml(status)}</div>
            <div><strong>Date Registered:</strong> ${escapeHtml(registered)}</div>
            <div><strong>IPOPHL ID:</strong> ${escapeHtml(ipophl)}</div>
            </div>
            <div class="row">Printed: ${new Date().toLocaleString()}</div>
            <script>window.onload=function(){window.print();}<\/script>
            </body></html>`;

          win.document.open();
          win.document.write(html);
          win.document.close();
        } else if(btn.classList.contains('editBtn')){
          loadRowIntoEdit(row);
        }
      });

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
          if(editSearchModal && !editSearchModal.classList.contains('hidden')) hideModal(editSearchModal, editSearchContent, editRecordsBtn);
          if(editRecordModal && !editRecordModal.classList.contains('hidden')) hideModal(editRecordModal, editRecordContent, editRecordsBtn);
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

      // Initial hint
      filterRows();

      // ========== RECENT UPDATES SYSTEM ==========
      const updatesContainer = document.getElementById('updatesContainer');
      const refreshUpdatesBtn = document.getElementById('refreshUpdatesBtn');

      // Load recent updates from server
      async function loadRecentUpdates() {
        try {
          const response = await fetch('{{ url('/api/recent-updates') }}', {
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Accept': 'application/json'
            }
          });

          if(!response.ok) throw new Error('Failed to fetch updates');

          const data = await response.json();
          renderUpdates(data.updates || []);
        } catch(error) {
          console.error('Error loading updates:', error);
          updatesContainer.innerHTML = `
            <div class="text-center py-6 text-xs text-red-600">
              Failed to load. <button onclick="loadRecentUpdates()" class="underline font-bold">Retry</button>
            </div>
          `;
        }
      }

      // Store updates globally for click handling
      let recentUpdatesMap = {};

      // Render updates to the container
      function renderUpdates(updates) {
        if(updates.length === 0) {
          updatesContainer.innerHTML = `
            <div class="text-center py-6 text-xs text-[color:var(--muted)]">
              No recent changes.
            </div>
          `;
          return;
        }

        // Store updates for click handling
        recentUpdatesMap = {};
        
        const html = updates.map((update, idx) => {
          const updateId = `update-${idx}`;
          recentUpdatesMap[updateId] = update;
          
          const timestamp = new Date(update.timestamp);
          const timeAgo = getTimeAgo(timestamp);
          
          let actionBadge = '';
          let actionIcon = '';
          let isClickable = false;
          
          switch(update.action?.toLowerCase()) {
            case 'created':
              actionBadge = 'bg-emerald-50 text-emerald-800 border-emerald-200';
              actionIcon = '✚';
              break;
            case 'modified':
              actionBadge = 'bg-amber-50 text-amber-800 border-amber-200';
              actionIcon = '✎';
              isClickable = true;
              break;
            case 'archived':
              actionBadge = 'bg-rose-50 text-rose-800 border-rose-200';
              actionIcon = '○';
              break;
            default:
              actionBadge = 'bg-slate-50 text-slate-800 border-slate-200';
              actionIcon = '○';
          }

          const clickClass = isClickable ? 'update-item cursor-pointer' : '';
          const clickAttr = isClickable ? `data-update-id="${updateId}"` : '';

          return `
            <div class="rounded-2xl border border-black/10 bg-white/70 p-3 hover:bg-white/85 transition">
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                  <div class="text-xs font-extrabold text-[color:var(--maroon)]">Record #${update.record_id || '—'}</div>
                  <div class="mt-1 font-bold text-xs truncate">${update.record_title || 'Untitled Record'}</div>
                  <div class="mt-1 text-xs text-[color:var(--muted)]">${update.record_type || '—'}</div>
                  <div class="mt-2 flex items-center gap-2">
                    <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-extrabold border ${actionBadge}">
                      ${actionIcon} ${update.action || '—'}
                    </span>
                    <span class="text-xs text-[color:var(--muted)]">• ${timeAgo}</span>
                  </div>
                </div>
                <button class="update-item-btn shrink-0 px-2.5 py-1 rounded-lg text-xs font-extrabold text-white bg-[color:var(--maroon)] hover:bg-[color:var(--maroon2)] transition" data-update-id="${updateId}">
                  View
                </button>
                <button class="delete-update-btn shrink-0 px-2.5 py-1 rounded-lg text-xs font-extrabold text-white bg-red-500 hover:bg-red-600 transition" data-update-id="${updateId}" data-log-id="${update.log_id || ''}" data-record-id="${update.record_id}">Delete</button>
              </div>
            </div>
          `;
        }).join('');
        
        // Set innerHTML first
        updatesContainer.innerHTML = html;
        
        // Then attach click handlers to all View buttons
        updatesContainer.querySelectorAll('.update-item-btn').forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.preventDefault();
            const updateId = this.getAttribute('data-update-id');
            const updateData = recentUpdatesMap[updateId];
            if (updateData) {
              // All records go to the record changes page
              window.location.href = `/record-changes/${encodeURIComponent(updateData.record_id)}`;
            }
          });
        });

        // attach delete handlers
        updatesContainer.querySelectorAll('.delete-update-btn').forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Remove the update item from the list (client-side only)
            const updateCard = this.closest('.rounded-2xl');
            if (updateCard) {
              updateCard.remove();
            }
          });
        });
      }

      // Show changes modal
      function showChangesModal(update) {
        const changesModal = document.getElementById('changesModal');
        const changesModalContent = document.getElementById('changesModalContent');
        const changesModalLabel = document.getElementById('changesModalLabel');
        const changesRecordId = document.getElementById('changesRecordId');
        const changesContainer = document.getElementById('changesContainer');

        changesModalLabel.textContent = `Changes to "${update.record_title}"`;
        changesRecordId.textContent = `Record #${update.record_id}`;

        // Build changes HTML
        let changesHTML = '';
        if (typeof update.changes === 'object' && Object.keys(update.changes).length > 0) {
          for (const [field, values] of Object.entries(update.changes)) {
            changesHTML += `
              <div class="rounded-lg border border-black/10 bg-slate-50 p-3">
                <div class="text-xs font-extrabold text-[color:var(--maroon)] mb-2">${field}</div>
                <div class="space-y-1">
                  <div class="text-xs text-red-600"><strong>Old:</strong> ${values.old || '(empty)'}</div>
                  <div class="text-xs text-green-600"><strong>New:</strong> ${values.new || '(empty)'}</div>
                </div>
              </div>
            `;
          }
        } else {
          changesHTML = '<div class="text-xs text-[color:var(--muted)]">No change details available.</div>';
        }

        changesContainer.innerHTML = changesHTML;

        // Show modal
        changesModal?.classList.remove('hidden');
        changesModal?.classList.add('flex');
        setTimeout(() => {
          changesModalContent?.classList.remove('scale-95', 'opacity-0');
          changesModalContent?.classList.add('scale-100', 'opacity-100');
        }, 10);
      }

      // Changes modal close handlers
      const changesModal = document.getElementById('changesModal');
      const changesModalContent = document.getElementById('changesModalContent');
      document.querySelectorAll('[data-close-changes]').forEach(btn => {
        btn.addEventListener('click', () => {
          changesModalContent?.classList.add('scale-95', 'opacity-0');
          changesModalContent?.classList.remove('scale-100', 'opacity-100');
          setTimeout(() => {
            changesModal?.classList.add('hidden');
            changesModal?.classList.remove('flex');
          }, 180);
        });
      });


      // Helper to format time ago
      function getTimeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        
        if(seconds < 60) return 'just now';
        const minutes = Math.floor(seconds / 60);
        if(minutes < 60) return `${minutes}m ago`;
        const hours = Math.floor(minutes / 60);
        if(hours < 24) return `${hours}h ago`;
        const days = Math.floor(hours / 24);
        if(days < 7) return `${days}d ago`;
        
        return date.toLocaleDateString();
      }

      // Refresh button
      refreshUpdatesBtn?.addEventListener('click', () => {
        refreshUpdatesBtn.style.opacity = '0.6';
        refreshUpdatesBtn.style.pointerEvents = 'none';
        
        loadRecentUpdates().then(() => {
          setTimeout(() => {
            refreshUpdatesBtn.style.opacity = '1';
            refreshUpdatesBtn.style.pointerEvents = 'auto';
          }, 500);
        });
      });

      // Auto-refresh updates every 30 seconds
      loadRecentUpdates();
      setInterval(loadRecentUpdates, 30000);
    })();
  </script>

</body>
</html>
