<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>KTTM — New Record</title>

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

    /* ✅ HARD lock for date input (prevents opening the picker) */
    .dateLocked{
      pointer-events:none;      /* blocks mouse click */
      user-select:none;
      opacity:.72;
    }

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
      to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(400px); opacity: 0; }
    }
    .toast.hiding { animation: slideOut 0.3s ease-out; }
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
    /**
     * ✅ NEW RECORD PAGE (Add)
     * Expect: $user, $campuses, $types, $statuses (or provide defaults)
     */
    $user = $user ?? (object)[ 'name' => 'KTTM User', 'role' => 'Staff' ];

    $campuses = $campuses ?? ['Alangilan', 'ARASOF-Nasugbu', 'Balayan', 'Lemery', 'Lipa', 'Malvar', 'Pablo Borbon', 'Rosario', 'San Juan'];
    $types    = $types    ?? ['Patent', 'Utility Model', 'Industrial Design', 'Copyright', 'Trademark'];

    // ✅ MUST match your option value exactly (we'll normalize too)
    $statuses = $statuses ?? ['Recently Filed', 'Filed', 'Under Review', 'Registered', 'Needs Attention', 'Returned'];

    // nextRecordId may be passed when generating the form; otherwise show placeholder
    $nextRecordId = $nextRecordId ?? '';

    $urlDashboard = url('/home');
    $urlRecords   = url('/records');
    $urlSupport   = url('/support');
    $urlLogout    = url('/logout');
    $urlProfile   = url('/profile');
    $urlStore     = url('/ipassets');
  @endphp

  <div class="mx-auto w-[min(1200px,94vw)] py-4 pb-16">

    {{-- ✅ NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_10px_24px_rgba(2,6,23,.12)] bg-white/60 backdrop-blur">
        <div class="relative h-[84px] sm:h-[100px]">
          <img
            src="{{ asset('images/bannerusb2.jpg') }}"
            alt="KTTM Header"
            class="absolute inset-0 h-full w-full object-cover"
          />
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
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">KTTM New Record</div>
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
            <a href="{{ $urlRecords }}"
               class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full font-extrabold text-sm
                      text-[#2a1a0b] border border-[rgba(240,200,96,.65)]
                      bg-gradient-to-br from-[color:var(--gold)] to-[color:var(--gold2)]
                      shadow-[0_12px_22px_rgba(199,156,59,.18)]
                      hover:brightness-105 hover:-translate-y-[1px] transition">
              ← Back to Records
            </a>

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

    {{-- ✅ HERO --}}
    <section class="mt-6">
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] p-7 sm:p-9">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div class="min-w-[260px]">
            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 bg-white/55 text-xs font-extrabold">
              <span class="h-2.5 w-2.5 rounded-full" style="background:var(--gold); box-shadow:0 0 0 6px rgba(240,200,96,.18);"></span>
              New Record
            </div>

            <h1 class="mt-4 text-[clamp(28px,3.2vw,40px)] leading-[1.05] font-black tracking-[-.8px]">
              Add a new IP record.
            </h1>
            <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed max-w-[70ch]">
              Fill in the details, then save. You can still edit the record later.
            </p>
          </div>

          <div class="w-full sm:w-[460px]">
            <div class="rounded-2xl border border-black/10 bg-white/65 p-4">
              <div class="text-xs font-extrabold text-[color:var(--muted)]">Quick Tips</div>
              <ul class="mt-2 text-sm text-[color:var(--muted)] space-y-1">
                <li>• Keep the title specific and searchable.</li>
                <li>• Set status to <span class="font-extrabold text-[color:var(--maroon)]">Recently Filed</span> if newly submitted.</li>
                <li>• Use a valid Google Drive link (share access as needed).</li>
              </ul>
              <div class="mt-3 flex flex-wrap gap-2">
                <a href="{{ $urlSupport }}"
                   class="focusRing inline-flex items-center justify-center px-3 py-2 rounded-xl border border-[color:var(--line)]
                          bg-white/70 font-extrabold text-xs hover:bg-[color:var(--gold)] hover:text-white transition">
                  Support
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    {{-- ✅ FORM --}}
    <section class="mt-5">
      <div class="rounded-[26px] border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-[color:var(--line)] flex flex-wrap items-center justify-between gap-3">
          <div>
            <h2 class="text-xl font-black tracking-[-.4px]">Record Details</h2>
            <p class="mt-1 text-sm text-[color:var(--muted)]">Fields with <span class="text-[color:var(--maroon)] font-extrabold">*</span> are required.</p>
          </div>

          <div class="flex gap-2">
            <button id="fillDemoBtn" type="button"
              class="focusRing px-4 py-2 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
              Fill Demo
            </button>
          </div>
        </div>

        <div class="p-6 sm:p-8">
          <form id="createRecordForm" action="{{ $urlStore }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

              {{-- record id is generated on the server; show it read‑only for reference --}}
              <div class="md:col-span-2">
                <label for="record_id" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  Record ID
                </label>
                <input id="record_id" type="text" disabled
                  value="{{ $nextRecordId ?? '' }}"
                  class="w-full rounded-2xl border border-[color:var(--line)] bg-gray-100 px-4 py-3 text-sm text-[color:var(--muted)]" />
              </div>

              <div class="md:col-span-2">
                <label for="title" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  IP Title <span class="text-[color:var(--maroon)]">*</span>
                </label>
                <input id="title" name="title" type="text" required
                  value="{{ old('title') }}"
                  placeholder="e.g., Smart Sensor-Based Pediatric Screening Kiosk"
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm" />
                @error('title')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

              <div>
                <label for="type" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  Category <span class="text-[color:var(--maroon)]">*</span>
                </label>
                <select id="type" name="type" required
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                  <option value="">Select category</option>
                  @foreach($types as $t)
                    <option value="{{ $t }}" @selected(old('type') == $t)>{{ $t }}</option>
                  @endforeach
                </select>
                @error('type')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

              <div>
                <label for="status" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  Status <span class="text-[color:var(--maroon)]">*</span>
                </label>
                <select id="status" name="status" required
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                  <option value="">Select status</option>
                  @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected(old('status') == $s)>{{ $s }}</option>
                  @endforeach
                </select>
                @error('status')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

              <div class="md:col-span-2">
                <div class="flex items-center justify-between mb-3">
                  <label class="block text-xs font-extrabold text-[color:var(--muted)]">
                    Owner / Inventors <span class="text-[color:var(--maroon)]">*</span>
                  </label>
                  <button id="addInventorBtn" type="button"
                    class="text-xs font-extrabold px-3 py-1 rounded-lg bg-[color:var(--gold)] text-[#2a1a0b] hover:bg-[color:var(--gold2)] transition">
                    + Add Inventor
                  </button>
                </div>

                <div id="inventorsList" class="space-y-3 mb-2"></div>

                <input type="hidden" id="inventorsData" name="inventors" value="[]" />
                <div id="inventorsError" class="mt-2 text-sm font-bold text-rose-700 hidden"></div>
              </div>

              <div>
                <label for="campus" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  Campus <span class="text-[color:var(--maroon)]">*</span>
                </label>
                <select id="campus" name="campus" required
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">
                  <option value="">Select campus</option>
                  @foreach($campuses as $c)
                    <option value="{{ $c }}" @selected(old('campus') == $c)>{{ $c }}</option>
                  @endforeach
                </select>
                @error('campus')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

              <div>
                <label for="registered" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  Date Registered
                  <span id="registeredHint" class="text-[color:var(--gold)] font-normal text-[10px] ml-1" style="display:none;">
                    (Disabled for Recently Filed)
                  </span>
                </label>
                <input id="registered" name="registered" type="date"
                  value="{{ old('registered') }}"
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-200" />
                @error('registered')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

              <div>
                <label for="ipophl_id" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  IPOPHL ID
                </label>
                <input id="ipophl_id" name="ipophl_id" type="text"
                  value="{{ old('ipophl_id') }}"
                  placeholder="e.g., IPOPHL-2026-000123"
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm" />
                @error('ipophl_id')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

              <div class="md:col-span-2">
                <label for="gdrive_link" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  GDrive Link
                </label>
                <input id="gdrive_link" name="gdrive_link" type="url"
                  value="{{ old('gdrive_link') }}"
                  placeholder="https://drive.google.com/..."
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm" />
                @error('gdrive_link')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

              <!-- ✅ IMPORTANT FIX: align name with DB column = remarks -->
              <div class="md:col-span-2">
                <label for="remarks" class="block text-xs font-extrabold text-[color:var(--muted)] mb-2">
                  Remarks (optional)
                </label>
                <textarea id="remarks" name="remarks" rows="4"
                  placeholder="Any extra remarks..."
                  class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm">{{ old('remarks') }}</textarea>
                @error('remarks')
                  <div class="mt-2 text-sm font-bold text-rose-700">{{ $message }}</div>
                @enderror
              </div>

            </div>

            <div class="pt-2 flex flex-wrap items-center gap-2">
              <button id="saveBtn" type="submit"
                class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
                Save Record
              </button>

              <button id="resetBtn" type="button"
                class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-[color:var(--line)] bg-white/85 text-[color:var(--muted)] font-extrabold hover:bg-white transition">
                Clear
              </button>

              <a href="{{ $urlRecords }}"
                 class="focusRing inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-[color:var(--line)] bg-white/85 text-[color:var(--muted)] font-extrabold hover:bg-white transition">
                Cancel
              </a>
            </div>

            <div class="text-xs text-[color:var(--muted)]">
              By saving, you confirm the information is accurate to the best of your knowledge.
            </div>

          </form>
        </div>
      </div>
    </section>

    <footer class="mt-5 px-2 py-4 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">New Record • Maroon + Gold + Slate</div>
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

  {{-- Duplicate detection modal --}}
  <div id="duplicateModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true" aria-labelledby="duplicateModalLabel">
    <div id="duplicateModalContent" class="relative max-w-lg w-full bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-180 scale-95 opacity-0 max-h-[80vh] overflow-y-auto">
      <button type="button" data-close-duplicate class="absolute top-3 right-3 p-2 rounded-md text-gray-600 hover:bg-gray-100">✕</button>
      <div class="p-6">
        <h3 id="duplicateModalLabel" class="text-xl font-black text-[color:var(--maroon)]">Possible duplicate found</h3>
        <p class="mt-1 text-sm text-[color:var(--muted)]">We found existing records with similar titles. Are you sure this is a new record?</p>

        <div id="duplicateList" class="mt-4 space-y-2 text-sm text-[color:var(--muted)]"></div>

        <div class="mt-6 flex gap-3">
          <button id="createAnywayBtn" class="focusRing px-4 py-3 rounded-2xl bg-[color:var(--maroon)] text-white font-extrabold hover:bg-[color:var(--maroon2)] transition">
            Create new record
          </button>
          <button id="viewExistingBtn" class="focusRing px-4 py-3 rounded-2xl border border-[color:var(--line)] bg-white text-[color:var(--muted)] font-extrabold hover:bg-gray-50 transition">
            View existing records
          </button>
        </div>
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

      // ---- Logout modal wiring ----
      function showModal(modal, content, trigger){
        if(!modal || !content) return;
        modal.classList.remove('hidden'); modal.classList.add('flex');
        requestAnimationFrame(()=>{
          content.classList.remove('scale-95','opacity-0');
          content.classList.add('scale-100','opacity-100');
        });
        document.body.style.overflow='hidden';
        trigger?.setAttribute('aria-expanded','true');
        setTimeout(()=> modal.querySelector('[data-close-logout],button')?.focus(), 120);
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

      const logoutBtn = document.getElementById('logoutBtn');
      const logoutModal = document.getElementById('logoutModal');
      const logoutContent = document.getElementById('logoutModalContent');
      const logoutCloseButtons = logoutModal ? logoutModal.querySelectorAll('[data-close-logout]') : [];

      logoutBtn?.addEventListener('click', () => showModal(logoutModal, logoutContent, logoutBtn));
      logoutCloseButtons.forEach(b => b.addEventListener('click', () => hideModal(logoutModal, logoutContent, logoutBtn)));
      logoutModal?.addEventListener('click', e => { if(e.target === logoutModal) hideModal(logoutModal, logoutContent, logoutBtn); });

      // Simulated logout (remove in production)
      const logoutForm = document.getElementById('logoutForm');
      if(logoutForm && logoutForm.dataset.simulate === 'true'){
        logoutForm.addEventListener('submit', function(ev){
          ev.preventDefault();
          hideModal(logoutModal, logoutContent, logoutBtn);
          setTimeout(()=>{ window.location.href = '{{ url('/') }}'; }, 220);
        });
      }

      // ---- Elements ----
      const form = document.getElementById('createRecordForm');
      const resetBtn = document.getElementById('resetBtn');
      const fillDemoBtn = document.getElementById('fillDemoBtn');

      const statusSelect = document.getElementById('status');
      const registeredInput = document.getElementById('registered');
      const registeredHint = document.getElementById('registeredHint');

      // duplicate-check modal elements
      const duplicateModal = document.getElementById('duplicateModal');
      const duplicateContent = document.getElementById('duplicateModalContent');
      const duplicateCloseButtons = duplicateModal ? duplicateModal.querySelectorAll('[data-close-duplicate]') : [];
      const duplicateList = document.getElementById('duplicateList');
      const createAnywayBtn = document.getElementById('createAnywayBtn');
      const viewExistingBtn = document.getElementById('viewExistingBtn');

      let bypassDuplicate = false;

      function showDuplicateModal(matches) {
        if (!duplicateModal || !duplicateList) return;
        duplicateList.innerHTML = '';
        matches.forEach(m => {
          const el = document.createElement('div');
          el.textContent = `${m.record_id} — ${m.ip_title}`;
          duplicateList.appendChild(el);
        });
        showModal(duplicateModal, duplicateContent);
      }

      function hideDuplicateModal() {
        hideModal(duplicateModal, duplicateContent);
      }

      duplicateCloseButtons.forEach(b => b.addEventListener('click', () => hideDuplicateModal()));
      viewExistingBtn?.addEventListener('click', () => {
        const titleEl = document.getElementById('title');
        const titleVal = titleEl ? titleEl.value.trim() : '';
        let url = '{{ $urlRecords }}';
        if(titleVal) {
          url += '?q=' + encodeURIComponent(titleVal);
        }
        window.location.href = url;
      });
      createAnywayBtn?.addEventListener('click', () => {
        bypassDuplicate = true;
        hideDuplicateModal();
        form.submit();
      });

      // ---- Dynamic Inventors Management ----
      let inventors = [];
      const inventorsList = document.getElementById('inventorsList');
      const addInventorBtn = document.getElementById('addInventorBtn');
      const inventorsData = document.getElementById('inventorsData');
      const inventorsError = document.getElementById('inventorsError');

      function escapeHtml(str){
        return String(str ?? '')
          .replaceAll('&','&amp;')
          .replaceAll('<','&lt;')
          .replaceAll('>','&gt;')
          .replaceAll('"','&quot;')
          .replaceAll("'",'&#039;');
      }

      function renderInventors() {
        inventorsList.innerHTML = '';
        inventors.forEach((inv, idx) => {
          const row = document.createElement('div');
          row.className = 'flex gap-2 items-end';
          row.innerHTML = `
            <div class="flex-1">
              <input type="text" placeholder="Inventor name" value="${escapeHtml(inv.name || '')}"
                class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
                onchange="updateInventor(${idx}, 'name', this.value)" />
            </div>
            <div class="w-[140px]">
              <select class="focusRing w-full rounded-2xl border border-[color:var(--line)] bg-white/70 px-4 py-3 text-sm"
                onchange="updateInventor(${idx}, 'gender', this.value)">
                <option value="" ${!inv.gender ? 'selected' : ''}>Gender</option>
                <option value="Male" ${inv.gender === 'Male' ? 'selected' : ''}>Male</option>
                <option value="Female" ${inv.gender === 'Female' ? 'selected' : ''}>Female</option>
                <option value="Other" ${inv.gender === 'Other' ? 'selected' : ''}>Other</option>
              </select>
            </div>
            <button type="button" onclick="removeInventor(${idx})"
              class="px-4 py-3 rounded-2xl bg-rose-100 text-rose-700 font-extrabold hover:bg-rose-200 transition">
              ✕
            </button>
          `;
          inventorsList.appendChild(row);
        });
        inventorsData.value = JSON.stringify(inventors);
      }

      window.updateInventor = function(idx, field, value) {
        if (inventors[idx]) {
          inventors[idx][field] = value;
          inventorsData.value = JSON.stringify(inventors);
        }
      };

      window.removeInventor = function(idx) {
        inventors.splice(idx, 1);
        renderInventors();
        showToast('Inventor removed.', 'success', 1800);
      };

      addInventorBtn?.addEventListener('click', () => {
        inventors.push({ name: '', gender: '' });
        renderInventors();
        setTimeout(() => {
          const inputs = inventorsList.querySelectorAll('input[type="text"]');
          inputs[inputs.length - 1]?.focus();
        }, 50);
      });

      // ---- ✅ Registration Date Logic (FIXED) ----
      // ✅ IMPORTANT: some browsers still show the picker if the input is already focused.
      // We: (1) blur it, (2) disable, (3) add pointer-events none, (4) block focus/mousedown/click.
      function lockRegistered() {
        if (!registeredInput) return;

        registeredInput.blur();      // stop picker if opened
        registeredInput.value = '';  // keep empty for Recently Filed

        registeredInput.disabled = true;
        registeredInput.readOnly = true;
        registeredInput.classList.add('dateLocked');

        registeredInput.setAttribute('tabindex', '-1');
        registeredInput.setAttribute('aria-disabled', 'true');

        registeredInput.title = 'Date Registered is disabled for Recently Filed';
        if (registeredHint) registeredHint.style.display = 'inline';
      }

      function unlockRegistered() {
        if (!registeredInput) return;

        registeredInput.disabled = false;
        registeredInput.readOnly = false;
        registeredInput.classList.remove('dateLocked');

        registeredInput.removeAttribute('tabindex');
        registeredInput.removeAttribute('aria-disabled');

        registeredInput.title = '';
        if (registeredHint) registeredHint.style.display = 'none';
      }

      // Normalize value so it matches even if spacing/case differs (e.g., "Recently Filed ")
      function isRecentlyFiledValue(val){
        return String(val ?? '')
          .trim()
          .toLowerCase()
          .replace(/\s+/g,' ') === 'recently filed';
      }

      function updateRegisteredState() {
        const statusVal = statusSelect?.value;
        if (isRecentlyFiledValue(statusVal)) lockRegistered();
        else unlockRegistered();
      }

      statusSelect?.addEventListener('change', updateRegisteredState);

      // ✅ Block any attempts to open picker while locked
      // Use capture=true so this runs before browser opens native date picker.
      ['pointerdown','mousedown','click','focus','keydown'].forEach(evt => {
        registeredInput?.addEventListener(evt, (e) => {
          if (registeredInput.disabled || registeredInput.classList.contains('dateLocked')) {
            // allow tab/shift+tab to continue navigation
            if (evt === 'keydown') {
              const k = e.key;
              if (k === 'Tab' || k === 'Shift') return;
            }
            e.preventDefault();
            e.stopPropagation();
            registeredInput.blur();
            return false;
          }
        }, true);
      });

      // Run once on load AFTER listeners are set
      updateRegisteredState();

      // ---- Clear form button ----
      resetBtn?.addEventListener('click', () => {
        form?.reset();

        inventors = [];
        renderInventors();

        updateRegisteredState();
        showToast('Form cleared.', 'success', 1800);
      });

      // ---- Form submission validation + duplicate check ----
      form?.addEventListener('submit', async (e) => {
        // always prevent default; we'll submit manually when ready
        e.preventDefault();

        if (inventors.length === 0) {
          inventorsError.textContent = 'Please add at least one inventor.';
          inventorsError.classList.remove('hidden');
          showToast('Please add at least one inventor.', 'error');
          return;
        }
        inventorsError.classList.add('hidden');

        // ✅ If Recently Filed, force blank value (server can treat as NULL)
        if (isRecentlyFiledValue(statusSelect?.value)) {
          registeredInput.value = '';
        }

        // do client-side validity check before making network call
        if (!form.checkValidity()){
          showToast('Please complete required fields.', 'error');
          return;
        }

        // perform duplicate title lookup unless bypassed
        if (!bypassDuplicate) {
          const titleEl = document.getElementById('title');
          const titleVal = titleEl ? titleEl.value.trim() : '';
          if (titleVal !== '') {
            try {
              const resp = await fetch("{{ url('/ipassets/check-title') }}?title=" + encodeURIComponent(titleVal));
              if (resp.ok) {
                const items = await resp.json();
                if (Array.isArray(items) && items.length > 0) {
                  showDuplicateModal(items);
                  return; // don't submit yet
                }
              }
            } catch (err) {
              console.error('duplicate check failed', err);
              // proceed with submission on error
            }
          }
        }

        // if we reach here, either bypassDuplicate is true or no matches found
        form.submit();
      });

      // ---- Demo fill ----
      fillDemoBtn?.addEventListener('click', () => {
        const set = (id, val) => { const el = document.getElementById(id); if(el) el.value = val; };

        set('title', 'Sample IP Record Title');
        set('type', '{{ $types[0] ?? "Patent" }}');
        set('status', 'Recently Filed');
        set('campus', '{{ $campuses[0] ?? "Alangilan" }}');
        set('registered', '');
        set('ipophl_id', 'IPOPHL-2026-000001');
        set('gdrive_link', 'https://drive.google.com/');
        set('remarks', 'Sample remarks for the new record.');

        inventors = [
          { name: 'Juan Dela Cruz', gender: 'Male' },
          { name: 'Maria Santos', gender: 'Female' }
        ];
        renderInventors();

        updateRegisteredState();
        showToast('Demo data filled.', 'success', 1800);
      });

      // Ensure inventors UI renders on load
      renderInventors();
    })();
  </script>

</body>
</html>