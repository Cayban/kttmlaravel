{{-- resources/views/record-changes.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — Record Changes</title>

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
      from { transform: translateX(400px); opacity: 0; }
      to   { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
      from { transform: translateX(0); opacity: 1; }
      to   { transform: translateX(400px); opacity: 0; }
    }
    .toast.hiding { animation: slideOut 0.3s ease-out; }

    /* Custom scrollbar for timeline */
    #timeline {
      scrollbar-width: thin;
      scrollbar-color: rgba(165,44,48,0.6) rgba(165,44,48,0.1);
    }
    #timeline::-webkit-scrollbar { width: 8px; }
    #timeline::-webkit-scrollbar-track { background: rgba(165,44,48,0.08); border-radius: 10px; }
    #timeline::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, var(--gold), var(--maroon));
      border-radius: 10px;
      border: 2px solid rgba(165,44,48,0.1);
    }
    #timeline::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(180deg, var(--gold2), var(--maroon2));
    }
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
    // Expect from controller: $user, $record (basic info), $recordId
    $user = $user ?? (object)[ 'name' => 'KTTM User', 'role' => 'Staff' ];
    $record = $record ?? null;

    $urlDashboard = url('/home');
    $urlRecords   = url('/records');
    $urlNew       = url('/ipassets/create');
    $urlLogout    = url('/logout');

    // ✅ FIX: robust route param detection
    $recordId = $recordId
        ?? request()->route('recordId')
        ?? request()->route('record_id')
        ?? request()->route('id')
        ?? request()->query('record_id');
  @endphp

  <div class="mx-auto w-[min(1200px,94vw)] py-4 pb-16">

    {{-- NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_10px_24px_rgba(2,6,23,.12)] bg-white/60 backdrop-blur">
        <div class="relative h-[84px] sm:h-[100px]">
          <img src="{{ asset('images/bannerusb2.jpg') }}" alt="KTTM Header" class="absolute inset-0 h-full w-full object-cover" />
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
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">Record Changes</div>
              <div class="text-white/75 text-xs">
                Welcome, <span class="font-bold text-white">{{ $user->name }}</span> • {{ $user->role }}
              </div>
            </div>
          </div>

          <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-white/90">
            <a href="{{ $urlDashboard }}" class="px-3 py-2 rounded-xl hover:bg-[color:var(--gold)] hover:text-white transition">Dashboard</a>
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

    {{-- HEADER --}}
    <section class="mt-6">
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] p-7 sm:p-9">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div class="min-w-[260px]">
            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/55 text-xs font-extrabold">
              <span class="h-2.5 w-2.5 rounded-full" style="background:var(--gold); box-shadow:0 0 0 6px rgba(240,200,96,.18);"></span>
              Audit Trail
            </div>

            <h1 class="mt-4 text-[clamp(28px,3.2vw,40px)] leading-[1.05] font-black tracking-[-.8px]">
              Changes for Record #{{ $recordId ?? '—' }}
            </h1>

            <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed max-w-[70ch]">
              Shows every create / edit / archive event with field-by-field differences (old vs new).
            </p>

            <div class="mt-4 flex flex-wrap gap-2">
              <a href="{{ $urlRecords }}"
                 class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
                ← Back to Records
              </a>

              <button id="refreshBtn" type="button"
                 class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-2xl font-extrabold text-sm
                        text-[#2a1a0b] border border-[rgba(240,200,96,.65)]
                        bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                        hover:brightness-105 transition">
                ↻ Refresh
              </button>
            </div>
          </div>

          {{-- record quick card --}}
          <div class="w-full lg:w-[480px]">
            <div class="rounded-2xl border border-black/10 bg-white/70 p-5">
              <div class="text-xs font-extrabold text-[color:var(--muted)]">Record Snapshot</div>

              <div class="mt-3 grid grid-cols-1 gap-2 text-sm">
                <div class="flex items-start justify-between gap-3">
                  <div class="text-[color:var(--muted)] font-bold">Title</div>
                  <div id="snapTitle" class="font-extrabold text-right max-w-[70%] truncate">{{ $record['title'] ?? '—' }}</div>
                </div>
                <div class="flex items-start justify-between gap-3">
                  <div class="text-[color:var(--muted)] font-bold">Category</div>
                  <div id="snapType" class="font-extrabold text-right max-w-[70%] truncate">{{ $record['type'] ?? '—' }}</div>
                </div>
                <div class="flex items-start justify-between gap-3">
                  <div class="text-[color:var(--muted)] font-bold">Owner</div>
                  <div id="snapOwner" class="font-extrabold text-right max-w-[70%] truncate">{{ $record['owner'] ?? '—' }}</div>
                </div>
                <div class="flex items-start justify-between gap-3">
                  <div class="text-[color:var(--muted)] font-bold">Campus</div>
                  <div id="snapCampus" class="font-extrabold text-right max-w-[70%] truncate">{{ $record['campus'] ?? '—' }}</div>
                </div>
                <div class="flex items-start justify-between gap-3">
                  <div class="text-[color:var(--muted)] font-bold">Status</div>
                  <div id="snapStatus" class="font-extrabold text-right max-w-[70%] truncate">{{ $record['status'] ?? '—' }}</div>
                </div>
              </div>

              <div class="mt-4 text-xs text-[color:var(--muted)]">
                Tip: Click a timeline item to expand its field changes.
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- BODY --}}
    <section class="mt-5 grid grid-cols-1 lg:grid-cols-[.9fr_1.1fr] gap-4">

      {{-- Left: filters --}}
      <aside class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="p-6 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(240,200,96,.22), rgba(165,44,48,.10));">
          <h2 class="font-black text-base tracking-[-.2px]">Filter Timeline</h2>
          <p class="mt-1 text-xs text-[color:var(--muted)]">Narrow down by action or search fields.</p>
        </div>

        <div class="p-6 space-y-4">
          <div>
            <label class="text-xs font-extrabold text-[color:var(--muted)]">Search (field/value)</label>
            <input id="searchInput" type="search" placeholder="e.g. status, campus, ipophl..."
              class="focusRing mt-2 w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm" />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <div>
              <label class="text-xs font-extrabold text-[color:var(--muted)]">Action</label>
              <select id="actionFilter" class="focusRing mt-2 w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="">All actions</option>
                <option value="created">Created</option>
                <option value="modified">Modified</option>
                <option value="archived">Archived</option>
              </select>
            </div>

            <div>
              <label class="text-xs font-extrabold text-[color:var(--muted)]">Sort</label>
              <select id="sortFilter" class="focusRing mt-2 w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                <option value="desc">Newest first</option>
                <option value="asc">Oldest first</option>
              </select>
            </div>
          </div>

          <div class="flex gap-2">
            <button id="applyBtn"
              class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--gold)] text-[#2a1a0b] text-sm font-extrabold hover:bg-[color:var(--gold2)] transition">
              Apply
            </button>
            <button id="resetBtn"
              class="focusRing w-full px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
              Reset
            </button>
          </div>

          <div id="countHint" class="text-xs text-[color:var(--muted)]">—</div>

          <div class="rounded-2xl border border-black/10 bg-white/65 p-4">
            <div class="text-xs font-extrabold text-[color:var(--maroon)]">API expectation</div>
            <div class="mt-1 text-xs text-[color:var(--muted)] leading-relaxed">
              This page calls <span class="font-extrabold">/api/records/{id}/changes</span> and expects:
              <span class="font-extrabold">events[]</span> with <span class="font-extrabold">action, timestamp, actor, changes</span>.
            </div>
          </div>
        </div>
      </aside>

      {{-- Right: timeline --}}
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden flex flex-col h-[70vh]">
        <div class="p-6 sm:p-8 border-b border-[color:var(--line)] flex items-center justify-between gap-3">
          <div>
            <h2 class="text-xl font-black tracking-[-.4px]">Change Timeline</h2>
            <p class="mt-1 text-sm text-[color:var(--muted)]">Click an event to expand details.</p>
          </div>
          <div class="text-xs font-extrabold text-[color:var(--muted)]">
            Record #<span class="text-[color:var(--maroon)]">{{ $recordId ?? '—' }}</span>
          </div>
        </div>

        <div class="p-6 sm:p-8 flex-1 overflow-hidden">
          <div id="timeline" class="space-y-3 overflow-y-auto pr-2 flex-1">
            <div class="text-center py-8 text-xs text-[color:var(--muted)]">
              Loading changes...
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">Record Changes • Audit Trail</div>
    </footer>

  </div>

  {{-- Logout modal --}}
  <div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true">
    <div id="logoutModalContent" class="relative max-w-md w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0">
      <button type="button" data-close-logout class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-6 flex gap-4 items-start">
        <div class="flex-shrink-0">
          <div class="h-12 w-12 rounded-full grid place-items-center text-white font-black" style="background:linear-gradient(135deg,var(--maroon),var(--maroon2));">!</div>
        </div>
        <div class="flex-1">
          <h3 class="text-xl font-black text-[color:var(--maroon)]">Sign out of KTTM</h3>
          <p class="mt-1 text-sm text-[color:var(--muted)]">This will end your session.</p>

          <div class="mt-5 grid grid-cols-2 gap-3">
            <button data-close-logout
                    class="focusRing w-full px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">
              Cancel
            </button>

            <form id="logoutForm" action="{{ $urlLogout }}" method="POST">
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

  {{-- Event details modal --}}
  <div id="eventModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true">
    <div id="eventModalContent" class="relative max-w-xl w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0 max-h-[85vh] overflow-y-auto">
      <button type="button" data-close-event class="sticky top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100 float-right">✕</button>
      <div class="p-6">
        <div class="text-xs font-extrabold text-[color:var(--muted)]" id="eventMeta">—</div>
        <h3 class="mt-2 text-xl font-black text-[color:var(--maroon)]" id="eventTitle">Event</h3>

        <div id="eventChanges" class="mt-5 space-y-3">
          <!-- filled by JS -->
        </div>

        <div class="mt-6">
          <button type="button" data-close-event
                  class="focusRing w-full px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      // ✅ FIX: always use string so it won't become null in JS
      const RECORD_ID = @json((string) $recordId);
      console.log("RECORD_ID =", RECORD_ID);

      // ---- Toast helper ----
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
      logoutBtn?.addEventListener('click', () => showModal(logoutModal, logoutContent, logoutBtn));
      logoutModal?.querySelectorAll('[data-close-logout]')?.forEach(b => b.addEventListener('click', () => hideModal(logoutModal, logoutContent, logoutBtn)));
      logoutModal?.addEventListener('click', e => { if(e.target === logoutModal) hideModal(logoutModal, logoutContent, logoutBtn); });

      // ---- Event details modal wiring ----
      const eventModal = document.getElementById('eventModal');
      const eventContent = document.getElementById('eventModalContent');
      const closeEventBtns = eventModal ? eventModal.querySelectorAll('[data-close-event]') : [];
      closeEventBtns.forEach(b => b.addEventListener('click', () => hideModal(eventModal, eventContent)));
      eventModal?.addEventListener('click', e => { if(e.target === eventModal) hideModal(eventModal, eventContent); });

      // ---- UI refs ----
      const timeline = document.getElementById('timeline');
      const refreshBtn = document.getElementById('refreshBtn');

      const searchInput = document.getElementById('searchInput');
      const actionFilter = document.getElementById('actionFilter');
      const sortFilter = document.getElementById('sortFilter');
      const applyBtn = document.getElementById('applyBtn');
      const resetBtn = document.getElementById('resetBtn');
      const countHint = document.getElementById('countHint');

      // ---- Data store ----
      let allEvents = [];

      // ---- Helpers ----
      function escapeHtml(str){
        return String(str ?? '')
          .replace(/&/g,'&amp;')
          .replace(/</g,'&lt;')
          .replace(/>/g,'&gt;')
          .replace(/"/g,'&quot;')
          .replace(/'/g,'&#039;');
      }

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

      function actionBadge(action){
        const a = String(action || '').toLowerCase();
        if(a === 'created')  return { cls: 'bg-emerald-50 text-emerald-800 border-emerald-200', icon: '✚', label: 'Created' };
        if(a === 'modified' || a === 'updated') return { cls: 'bg-amber-50 text-amber-800 border-amber-200', icon: '✎', label: 'Modified' };
        if(a === 'archived') return { cls: 'bg-rose-50 text-rose-800 border-rose-200', icon: '⦸', label: 'Archived' };
        return { cls: 'bg-slate-50 text-slate-800 border-slate-200', icon: '•', label: action || 'Event' };
      }

      function normalizeChanges(changes){
        if(!changes) return [];

        // ✅ FIX: handle JSON string changes (common when stored as text)
        if(typeof changes === 'string'){
          try { changes = JSON.parse(changes); }
          catch(e){ return []; }
        }

        if(typeof changes !== 'object') return [];

        return Object.entries(changes).map(([field, v]) => {
          const oldVal = (v && typeof v === 'object') ? v.old : undefined;
          const newVal = (v && typeof v === 'object') ? v.new : undefined;
          return { field, old: oldVal, new: newVal };
        });
      }

      function matchesSearch(evt, needle){
        if(!needle) return true;
        const n = needle.toLowerCase();

        const base = `${evt.action || ''} ${evt.actor || ''} ${evt.note || ''}`.toLowerCase();
        if(base.includes(n)) return true;

        const ch = normalizeChanges(evt.changes);
        for(const c of ch){
          const blob = `${c.field} ${c.old ?? ''} ${c.new ?? ''}`.toLowerCase();
          if(blob.includes(n)) return true;
        }
        return false;
      }

      function applyFilters(){
        const needle = (searchInput?.value || '').trim();
        const action = (actionFilter?.value || '').trim().toLowerCase();
        const sortDir = (sortFilter?.value || 'desc').trim().toLowerCase();

        let filtered = allEvents.slice();

        if(action){
          filtered = filtered.filter(e =>
            String(e.action || '').toLowerCase() === action ||
            (action === 'modified' && String(e.action || '').toLowerCase() === 'updated')
          );
        }
        if(needle){
          filtered = filtered.filter(e => matchesSearch(e, needle));
        }

        filtered.sort((a,b)=>{
          const da = new Date(a.timestamp || a.created_at || 0).getTime();
          const db = new Date(b.timestamp || b.created_at || 0).getTime();
          return sortDir === 'asc' ? (da - db) : (db - da);
        });

        renderTimeline(filtered);
      }

      function renderTimeline(events){
        if(!timeline) return;

        if(!events || events.length === 0){
          timeline.innerHTML = `
            <div class="text-center py-8 text-xs text-[color:var(--muted)]">
              No matching events.
            </div>
          `;
          if(countHint) countHint.textContent = '0 event(s).';
          return;
        }

        if(countHint) countHint.textContent = `${events.length} event(s) shown.`;

        timeline.innerHTML = events.map((evt, idx) => {
          const ts = new Date(evt.timestamp || evt.created_at);
          const timeAgo = isNaN(ts.getTime()) ? '—' : getTimeAgo(ts);
          const pretty = isNaN(ts.getTime()) ? 'Unknown time' : ts.toLocaleString();

          const badge = actionBadge(evt.action);
          const changes = normalizeChanges(evt.changes);

          let changesHTML = '';
          if(changes.length > 0){
            changesHTML = changes.map(c => {
              const oldV = (c.old === null || c.old === undefined || c.old === '') ? '(empty)' : String(c.old);
              const newV = (c.new === null || c.new === undefined || c.new === '') ? '(empty)' : String(c.new);
              return `
                <div class="rounded-lg border border-black/10 bg-slate-50 p-3 mt-2">
                  <div class="text-xs font-extrabold text-[color:var(--maroon)] mb-1">${escapeHtml(c.field)}</div>
                  <div class="text-xs space-y-0.5">
                    <div class="text-red-600"><strong>Old:</strong> ${escapeHtml(oldV)}</div>
                    <div class="text-emerald-700"><strong>New:</strong> ${escapeHtml(newV)}</div>
                  </div>
                </div>
              `;
            }).join('');
          }

          return `
            <div class="event-item rounded-2xl border border-black/10 bg-white/70 p-4 hover:bg-white/85 transition cursor-pointer"
                 data-idx="${idx}">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-extrabold border ${badge.cls}">
                      ${badge.icon} ${escapeHtml(badge.label)}
                    </span>
                    <span class="text-xs text-[color:var(--muted)]">• ${escapeHtml(timeAgo)} • ${escapeHtml(pretty)}</span>
                  </div>
                  <div class="mt-2 text-sm font-extrabold text-[color:var(--maroon)] truncate">
                    ${escapeHtml(evt.summary || evt.note || 'Change event')}
                  </div>
                  <div class="mt-1 text-xs text-[color:var(--muted)]">
                    ${evt.actor ? `By <span class="font-extrabold">${escapeHtml(evt.actor)}</span>` : '' }
                  </div>
                  ${changesHTML ? `
                    <div class="mt-3">
                      ${changesHTML}
                    </div>
                  ` : '<div class="mt-2 text-xs text-[color:var(--muted)]">No changes recorded</div>'}
                </div>

                <div class="shrink-0 text-xs font-extrabold text-[color:var(--muted)]">Details →</div>
              </div>
            </div>
          `;
        }).join('');

        timeline.querySelectorAll('.event-item').forEach(card => {
          card.addEventListener('click', () => {
            const i = Number(card.getAttribute('data-idx'));
            const evt = events[i];
            if(evt) openEvent(evt);
          });
        });
      }

      function openEvent(evt){
        const eventMeta = document.getElementById('eventMeta');
        const eventTitle = document.getElementById('eventTitle');
        const eventChanges = document.getElementById('eventChanges');

        const ts = new Date(evt.timestamp || evt.created_at);
        const pretty = isNaN(ts.getTime()) ? 'Unknown time' : ts.toLocaleString();
        const badge = actionBadge(evt.action);

        if(eventMeta){
          eventMeta.innerHTML = `Record #${escapeHtml(RECORD_ID)} • ${escapeHtml(pretty)} ${evt.actor ? `• ${escapeHtml(evt.actor)}` : ''}`;
        }
        if(eventTitle){
          eventTitle.innerHTML = `<span class="inline-flex items-center gap-2">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-extrabold border ${badge.cls}">
              ${badge.icon} ${escapeHtml(badge.label)}
            </span>
            <span>${escapeHtml(evt.summary || evt.note || 'Event details')}</span>
          </span>`;
        }

        const changes = normalizeChanges(evt.changes);
        if(!eventChanges) return;

        if(changes.length === 0){
          eventChanges.innerHTML = `
            <div class="rounded-2xl border border-black/10 bg-slate-50 p-4 text-sm text-[color:var(--muted)]">
              No change details available for this event.
            </div>
          `;
        } else {
          eventChanges.innerHTML = changes.map(c => {
            const oldV = (c.old === null || c.old === undefined || c.old === '') ? '(empty)' : String(c.old);
            const newV = (c.new === null || c.new === undefined || c.new === '') ? '(empty)' : String(c.new);

            return `
              <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
                <div class="text-xs font-extrabold text-[color:var(--maroon)] mb-2">${escapeHtml(c.field)}</div>
                <div class="space-y-1 text-sm">
                  <div class="text-red-600"><span class="font-extrabold">Old:</span> ${escapeHtml(oldV)}</div>
                  <div class="text-emerald-700"><span class="font-extrabold">New:</span> ${escapeHtml(newV)}</div>
                </div>
              </div>
            `;
          }).join('');
        }

        showModal(eventModal, eventContent);
      }

      // ---- API load ----
      async function loadRecordChanges(){
        if(!RECORD_ID){
          timeline.innerHTML = `<div class="text-center py-8 text-xs text-red-600">Missing record id.</div>`;
          return;
        }

        try{
          const url = `/api/records/${encodeURIComponent(String(RECORD_ID))}/changes`;

          const res = await fetch(url, {
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            credentials: 'same-origin',
          });

          if(!res.ok) throw new Error(`HTTP ${res.status}`);

          const data = await res.json();

          allEvents = Array.isArray(data.events) ? data.events : [];
          applyFilters();

          if(data.record){
            const r = data.record;
            const elTitle = document.getElementById('snapTitle');
            const elType  = document.getElementById('snapType');
            const elOwner = document.getElementById('snapOwner');
            const elCampus= document.getElementById('snapCampus');
            const elStatus= document.getElementById('snapStatus');

            if(elTitle) elTitle.textContent = r.title || '—';
            if(elType) elType.textContent = r.type || '—';
            if(elOwner) elOwner.textContent = r.owner || '—';
            if(elCampus) elCampus.textContent = r.campus || '—';
            if(elStatus) elStatus.textContent = r.status || '—';
          }

        }catch(err){
          console.error(err);
          timeline.innerHTML = `
            <div class="text-center py-8 text-xs text-red-600">
              Failed to load changes. <button id="retryBtn" class="underline font-bold">Retry</button>
            </div>
          `;
          document.getElementById('retryBtn')?.addEventListener('click', loadRecordChanges);
          showToast('Failed to load record changes.', 'error');
        }
      }

      // ---- Buttons ----
      refreshBtn?.addEventListener('click', async () => {
        refreshBtn.style.opacity = '0.6';
        refreshBtn.style.pointerEvents = 'none';
        showToast('Refreshing changes...', 'success', 2000);
        await loadRecordChanges();
        setTimeout(() => {
          refreshBtn.style.opacity = '1';
          refreshBtn.style.pointerEvents = 'auto';
          showToast('Updated!', 'success', 1500);
        }, 450);
      });

      applyBtn?.addEventListener('click', applyFilters);

      resetBtn?.addEventListener('click', () => {
        if(searchInput) searchInput.value = '';
        if(actionFilter) actionFilter.value = '';
        if(sortFilter) sortFilter.value = 'desc';
        applyFilters();
      });

      searchInput?.addEventListener('keypress', (e) => { if(e.key === 'Enter') applyFilters(); });

      document.addEventListener('keydown', e => {
        if(e.key === 'Escape'){
          if(logoutModal && !logoutModal.classList.contains('hidden')) hideModal(logoutModal, logoutContent, logoutBtn);
          if(eventModal && !eventModal.classList.contains('hidden')) hideModal(eventModal, eventContent);
        }
      });

      loadRecordChanges();

      // Auto-refresh every 10 seconds to catch new changes
      setInterval(loadRecordChanges, 10000);
    })();
  </script>
</body>
</html>