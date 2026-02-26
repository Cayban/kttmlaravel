<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>KTTM — Analytics</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <style>
    :root{
      --maroon:#A52C30;
      --maroon2:#8B2E32;
      --gold:#D2B750;  /* slightly darker gold */
      --gold2:#C0A645;  /* shift secondary as well */
      --ink:#0F172A;
      --muted:#475569;
      --line:rgba(15,23,42,.10);
      --card:rgba(255,255,255,.78);
      --shadow: 0 18px 50px rgba(2,6,23,.10);
      --radius: 22px;
    }
    html { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    .focusRing:focus{ outline:none; box-shadow:0 0 0 4px rgba(165,44,48,.22); }

    /* tighter vertical rhythm */
    .cardPad { padding: 16px; }
    @media (min-width:640px){ .cardPad { padding: 18px; } }
    @media (min-width:1024px){ .cardPad { padding: 20px; } }

    /* charts: reduce height so cards don’t become “too tall” */
    .chartWrap{ position:relative; width:100%; }
    .hChartSm{ height: 220px; }
    .hChartMd{ height: 250px; }
    .hChartLg{ height: 280px; }
    .hChartXL{ height: 340px; }
    @media(min-width:1024px){
      .hChartSm{ height: 210px; }
      .hChartMd{ height: 240px; }
      .hChartLg{ height: 270px; }
      .hChartXL{ height: 320px; }
    }

    .gridveil{
      background-image: radial-gradient(circle at 1px 1px, rgba(15,23,42,.06) 1px, transparent 0);
      background-size: 18px 18px;
    }

    /* dropdown */
    .dlDropdown{ position: relative; display: inline-block; }
    .dlBtn{
      display:inline-flex; align-items:center; justify-content:center; gap:6px;
      padding:.45rem .75rem; border-radius:999px;
      font-weight:900; font-size:.75rem;
      border:1px solid rgba(15,23,42,.12);
      background: rgba(255,255,255,.78);
      transition: .15s;
      cursor: pointer;
      user-select:none;
      white-space: nowrap;
    }
    .dlBtn:hover{ background:white; transform: translateY(-1px); }
    .dlMenu{
      position: absolute; top: 100%; right: 0; z-index: 100;
      min-width: 170px;
      background: white;
      border: 1px solid rgba(15,23,42,.15);
      border-radius: 14px;
      box-shadow: 0 14px 40px rgba(2,6,23,.18);
      display: none;
      flex-direction: column;
      overflow: hidden;
      margin-top: 8px;
    }
    .dlMenu.show{ display: flex; }
    .dlMenu button{
      padding: .65rem 1rem;
      border: none;
      background: white;
      text-align: left;
      font-size: .85rem;
      font-weight: 700;
      color: var(--ink);
      cursor: pointer;
      transition: .15s;
      border-bottom: 1px solid rgba(15,23,42,.06);
      white-space: nowrap;
    }
    .dlMenu button:last-child{ border-bottom: none; }
    .dlMenu button:hover{ background: rgba(240,200,96,.15); }

    /* compact chip */
    .chip{
      display:inline-flex; align-items:center; gap:.5rem;
      padding:.45rem .75rem;
      border-radius:999px;
      border:1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.60);
      font-weight:900;
      font-size:.75rem;
      color: rgba(15,23,42,.78);
    }
    .chipDot{
      width:10px; height:10px; border-radius:999px;
      background: var(--gold);
      box-shadow:0 0 0 6px rgba(240,200,96,.18);
    }

    /* header rows */
    .cardHead{
      display:flex;
      align-items:flex-end;
      justify-content:space-between;
      gap:10px;
    }

    /* consistent card title spacing */
    .cardTitle{ line-height: 1.1; }
    .cardSub{ margin-top: 2px; }

    /* small “hint” blocks should not add too much height */
    .hint{ padding: 12px; border-radius: 16px; }

    /* unify card rounding */
    .cardShell{ border-radius: 24px; }

    /* footer tighter */
    .foot{ padding: 10px 0; }
  </style>
</head>

<body class="min-h-screen scroll-smooth text-[color:var(--ink)] overflow-x-hidden bg-[#f6f3ec]">

  {{-- Background --}}
  <div class="fixed inset-0 -z-20 bg-cover bg-center" style="background-image:url('{{ asset('images/bsuBG.jpg') }}');"></div>
  <div class="fixed inset-0 -z-10"
       style="background:
         radial-gradient(900px 420px at 12% 0%, rgba(240,200,96,.10), transparent 62%),
         radial-gradient(900px 420px at 88% 10%, rgba(165,44,48,.12), transparent 60%),
         linear-gradient(180deg, rgba(250,249,246,.36) 0%, rgba(248,246,241,.44) 55%, rgba(250,249,246,.52) 100%);">
  </div>

  @php
    use App\Models\IpRecord;

    $user = $user ?? (object)[ 'name' => 'KTTM User', 'role' => 'Staff' ];
    
    // Get all records from database if not provided
    if(empty($allRecords)){
      $allRecords = IpRecord::all()->map(function ($r) {
        return [
          'id'         => $r->record_id,
          'title'      => $r->ip_title,
          'category'   => $r->category,
          'owner'      => $r->owner_inventor_summary,
          'campus'     => $r->campus,
          'status'     => $r->status,
          'registered' => $r->date_registered,
          'ipophl_id'  => $r->ipophl_id,
          'gdrive_link'=> $r->gdrive_link,
        ];
      })->toArray();
    }

    $total = max(1, count($allRecords));
    $norm = function($v){
      $v = is_string($v) ? trim($v) : $v;
      return ($v === null || $v === '') ? '—' : $v;
    };

    // Data from database
    $statusCounts = collect($allRecords)->map(fn($r)=> $norm($r['status'] ?? null))->countBy()->sortDesc();
    $typeCounts   = collect($allRecords)->map(fn($r)=> $norm($r['category'] ?? null))->countBy()->sortDesc();
    $campusCounts = collect($allRecords)->map(fn($r)=> $norm($r['campus'] ?? null))->countBy()->sortDesc();

    // Fetch distinct IP types from database directly for dropdown
    $distinctIpTypes = IpRecord::distinct('category')
      ->whereNotNull('category')
      ->pluck('category')
      ->filter(fn($v) => trim($v) !== '')
      ->sort()
      ->values();
    
    // Fetch distinct statuses from database directly for dropdown
    $distinctStatuses = IpRecord::distinct('status')
      ->whereNotNull('status')
      ->pluck('status')
      ->filter(fn($v) => trim($v) !== '')
      ->sort()
      ->values();
    
    // Fetch distinct campuses from database directly for dropdown
    $distinctCampuses = IpRecord::distinct('campus')
      ->whereNotNull('campus')
      ->pluck('campus')
      ->filter(fn($v) => trim($v) !== '')
      ->sort()
      ->values();

    // Group by month registered
    $byMonthRegistered = collect($allRecords)->map(function($r){
      $dt = $r['registered'] ?? null;
      return $dt ? \Carbon\Carbon::parse($dt)->format('Y-m') : null;
    })->filter()->countBy()->sortKeys();

    // Group by year registered
    $byYearRegistered = collect($allRecords)->map(function($r){
      $dt = $r['registered'] ?? null;
      return $dt ? \Carbon\Carbon::parse($dt)->format('Y') : null;
    })->filter()->countBy()->sortKeys();

    // Category by Campus matrix
    $campusLabels = $campusCounts->keys()->filter(fn($k) => $k !== '—')->values()->take(6);
    $categoryLabels = $typeCounts->keys()->filter(fn($k) => $k !== '—')->values()->take(6);

    $catCampusMatrix = [];
    foreach($categoryLabels as $cat){
      $catCampusMatrix[$cat] = [];
      foreach($campusLabels as $camp){
        $catCampusMatrix[$cat][$camp] = collect($allRecords)->filter(function($r) use ($cat,$camp,$norm){
          $rcat = $norm($r['category'] ?? null);
          $rcamp = $norm($r['campus'] ?? null);
          return $rcat === $cat && $rcamp === $camp;
        })->count();
      }
    }

    // Top 8 statuses + others
    $statusTop = $statusCounts->take(8);
    $statusOthers = $statusCounts->slice(8)->sum();
    if($statusOthers > 0) $statusTop = $statusTop->merge(['Others' => $statusOthers]);

    // Top 8 categories + others
    $typeTop = $typeCounts->take(8);
    $typeOthers = $typeCounts->slice(8)->sum();
    if($typeOthers > 0) $typeTop = $typeTop->merge(['Others' => $typeOthers]);

    // Top 6 campuses + others
    $campusTop = $campusCounts->take(6);
    $campusOthers = $campusCounts->slice(6)->sum();
    if($campusOthers > 0) $campusTop = $campusTop->merge(['Others' => $campusOthers]);

    // Gender counts come from ip_contributors table (joined by record id)
    use Illuminate\Support\Facades\DB;

    $rawGender = DB::table('ip_contributors')
      ->join('ip_records', 'ip_contributors.record_id', '=', 'ip_records.record_id')
      ->selectRaw("COALESCE(NULLIF(TRIM(ip_contributors.role),''),'Unknown') as role_clean, count(*) as cnt")
      ->groupBy('role_clean')
      ->pluck('cnt','role_clean');

    $genderCounts = collect(['Male'=>0,'Female'=>0,'Other'=>0,'Unknown'=>0]);
    foreach($rawGender as $role => $cnt){
      $r = ucfirst(strtolower($role));
      if(!in_array($r, ['Male','Female','Other','Unknown'])) $r = 'Other';
      $genderCounts[$r] = $cnt;
    }

    // Gender by category (category + counts per role)
    $genderByCategory = [];
    // first grab the aggregated counts for the initial view (existing behaviour)
    $rows = DB::table('ip_contributors')
      ->join('ip_records', 'ip_contributors.record_id', '=', 'ip_records.record_id')
      ->selectRaw("COALESCE(NULLIF(TRIM(ip_records.category),''),'—') as category, COALESCE(NULLIF(TRIM(ip_contributors.role),''),'Unknown') as role, count(*) as cnt")
      ->groupBy('category','role')
      ->get();

    $grouped = [];
    foreach($rows as $r){
      $cat = $r->category;
      $role = ucfirst(strtolower($r->role));
      if(!in_array($role, ['Male','Female','Other','Unknown'])) $role = 'Other';
      if(!isset($grouped[$cat])) $grouped[$cat] = ['category'=>$cat, 'Male'=>0,'Female'=>0,'Other'=>0,'Unknown'=>0];
      $grouped[$cat][$role] = intval($r->cnt);
    }

    // Convert to indexed array preserving order of categories we computed earlier
    foreach($categoryLabels as $cat){
      if(isset($grouped[$cat])) $genderByCategory[] = $grouped[$cat];
    }
    // append any remaining categories
    foreach($grouped as $cat => $vals){ if(!in_array($cat, $categoryLabels->toArray())) $genderByCategory[] = $vals; }

    // also fetch a raw contributor list (with type/status/campus) so the client
    // can re‑aggregate when filters are applied without needing another request.
    // NOTE: there is no "type" column on ip_records; earlier code
    // treated category/type interchangeably, but selecting `ip_records.type`
    // will throw a QueryException.  Only fetch category and rename it if the
    // client still expects a `type` property.
    $genderByCategoryRaw = DB::table('ip_contributors')
      ->join('ip_records', 'ip_contributors.record_id', '=', 'ip_records.record_id')
      ->selectRaw(
         "COALESCE(NULLIF(TRIM(ip_records.category),''),'—') as category, 
          COALESCE(NULLIF(TRIM(ip_records.status),''),'') as status,
          COALESCE(NULLIF(TRIM(ip_records.campus),''),'') as campus,
          COALESCE(NULLIF(TRIM(ip_contributors.role),''),'Unknown') as role"
      )
      ->get()
      ->map(function($r){
         $role = ucfirst(strtolower($r->role));
         if(!in_array($role, ['Male','Female','Other','Unknown'])) $role = 'Other';
         return [
            'category' => $r->category,
            // keep a `type` alias for backwards compatibility
            'type'     => $r->category,
            'status'   => $r->status,
            'campus'   => $r->campus,
            'role'     => $role,
         ];
      });

    // Compute top inventors/contributors from records (initial dataset)
    $invMap = [];
    foreach($allRecords as $r){
      $name = trim($r['owner'] ?? '') ?: '—';
      $cat  = trim($r['category'] ?? $r['type'] ?? '') ?: '—';
      if(!isset($invMap[$name])){
        $invMap[$name] = ['Patent'=>0,'Utility Model'=>0,'Industrial Design'=>0,'Copyright'=>0,'total'=>0];
      }
      if(array_key_exists($cat, $invMap[$name])){
        $invMap[$name][$cat]++;
      }
      $invMap[$name]['total']++;
    }

    $entries = collect($invMap)->sortByDesc('total');
    $topInventors = [];
    foreach($entries->take(8) as $name => $data){
      $topInventors[] = array_merge(['name' => $name], $data);
    }

    $urlDashboard = url('/home');
    // use staff-only route
    $urlRecords   = url('/records');
    $urlSupport   = url('/support');
    $urlProfile   = url('/profile');
    $urlLogout    = url('/logout');

    // Get unique filter options
    $filterStatuses = $statusCounts->keys()->filter(fn($k) => $k !== '—')->values();
    $filterCategories = $typeCounts->keys()->filter(fn($k) => $k !== '—')->values();
    $filterCampuses = $campusCounts->keys()->filter(fn($k) => $k !== '—')->values();

    $js = [
      'total' => count($allRecords),
      'allRecords' => $allRecords,
      'filterStatuses' => $filterStatuses->toArray(),
      'filterCategories' => $filterCategories->toArray(),
      'filterCampuses' => $filterCampuses->toArray(),
      'status' => ['labels' => $statusTop->keys()->values(), 'values' => $statusTop->values()],
      'types'  => ['labels' => $typeTop->keys()->values(),   'values' => $typeTop->values()],
      'campus' => ['labels' => $campusTop->keys()->values(), 'values' => $campusTop->values()],
      'monthsRegistered' => ['labels' => $byMonthRegistered->keys()->values(), 'values' => $byMonthRegistered->values()],
      'yearsRegistered'  => ['labels' => $byYearRegistered->keys()->values(),  'values' => $byYearRegistered->values()],
      'gender' => ['labels' => $genderCounts->keys()->values(), 'values' => $genderCounts->values()],
      'catCampus' => [
        'campusLabels' => $campusLabels,
        'categoryLabels' => $categoryLabels,
        'matrix' => $catCampusMatrix,
      ],
      'topInventors' => [
        'labels' => collect($topInventors)->pluck('name')->values(),
        'series' => [
          'Patent' => collect($topInventors)->pluck('Patent')->values(),
          'Utility Model' => collect($topInventors)->pluck('Utility Model')->values(),
          'Industrial Design' => collect($topInventors)->pluck('Industrial Design')->values(),
          'Copyright' => collect($topInventors)->pluck('Copyright')->values(),
        ],
        'total' => collect($topInventors)->pluck('total')->values(),
      ],
      'genderByCategory' => [
        'labels' => collect($genderByCategory)->pluck('category')->values(),
        'series' => [
          'Male' => collect($genderByCategory)->pluck('Male')->values(),
          'Female' => collect($genderByCategory)->pluck('Female')->values(),
          'Other' => collect($genderByCategory)->pluck('Other')->values(),
          'Unknown' => collect($genderByCategory)->pluck('Unknown')->values(),
        ]
      ],
      'genderByCategoryRaw' => $genderByCategoryRaw,
    ];
  @endphp

  <div class="mx-auto w-[min(1220px,94vw)] py-3 pb-12">

    {{-- NAVBAR --}}
    <header class="sticky top-3 z-40">
      <div class="rounded-[22px] overflow-hidden border border-[color:var(--line)] shadow-[0_10px_24px_rgba(2,6,23,.12)] bg-white/60 backdrop-blur">
        <div class="relative h-[78px] sm:h-[92px]">
          <img src="{{ asset('images/bannerusb2.jpg') }}" alt="KTTM Header" class="absolute inset-0 h-full w-full object-cover"/>
          <div class="absolute inset-0 bg-black/20"></div>
          <div class="absolute bottom-0 left-0 right-0 h-px bg-white/20"></div>
        </div>

        <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3"
             style="background: linear-gradient(90deg, rgba(153, 34, 38, 0.96), rgba(171, 15, 20, 0.96));">

          <div class="flex items-center gap-3 min-w-[220px]">
            <div class="h-9 w-9 rounded-2xl grid place-items-center font-black"
                 style="background: linear-gradient(135deg, rgba(220,180,80,.95), rgba(232,184,87,.95)); color:#2a1a0b;">
              A
            </div>
            <div class="leading-tight">
              <div class="text-white font-extrabold text-sm tracking-[-.2px]">KTTM Analytics</div>
              <div class="text-white/75 text-xs">{{ $user->role }} • <span class="font-bold text-white">{{ $user->name }}</span></div>
            </div>
          </div>

          <nav class="hidden md:flex items-center gap-1 text-sm font-semibold text-white/90">
            <a href="{{ $urlDashboard }}" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Dashboard</a>
            <a href="{{ $urlRecords }}" class="px-3 py-2 rounded-xl hover:bg-white/10 transition">Records</a>
            <a href="#charts" class="px-3 py-2 rounded-xl bg-white/15 text-white ring-1 ring-white/20">Analytics</a>
          </nav>

          <div class="flex items-center gap-2">
            
            <button id="logoutBtn" type="button" class="focusRing inline-flex items-center justify-center px-4 py-2 rounded-full bg-white/90 text-[#1f2937] font-extrabold text-sm hover:bg-[color:var(--gold)] hover:text-white transition">
              Log out
            </button>
          </div>
        </div>
      </div>
    </header>

    {{-- HERO (tight + aligned) --}}
    <section class="mt-4 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] p-6 sm:p-7 relative overflow-hidden">
      <div class="absolute inset-0 -z-10 gridveil opacity-[.55]"></div>

      <div class="grid grid-cols-1 lg:grid-cols-[1.15fr_.85fr] gap-4 items-start">
        <div class="min-w-0">
          <div class="chip"><span class="chipDot"></span> Reporting & Insights</div>

          <h1 class="mt-3 text-[clamp(26px,3vw,40px)] leading-[1.04] font-black tracking-[-.8px]">
            Analytics dashboard
          </h1>

          <p class="mt-2 text-sm text-[color:var(--muted)] leading-relaxed max-w-[72ch]">
            Export charts as PNG / CSV / PDF. (Data comes from <b>ip_records</b> + related tables.)
          </p>

          <div class="mt-4 flex flex-wrap gap-2">
            <a href="#charts" class="focusRing px-4 py-2.5 rounded-2xl bg-[color:var(--maroon)] text-white text-sm font-extrabold hover:bg-[color:var(--maroon2)] transition">
              View Charts →
            </a>
            <a href="{{ $urlRecords }}" class="focusRing px-4 py-2.5 rounded-2xl border border-[color:var(--line)] bg-white/80 text-sm font-extrabold hover:bg-white transition">
              Records
            </a>
          </div>
        </div>

        {{-- KPI row (no tall cards) --}}
        <div class="grid grid-cols-2 gap-3">
          <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
            <div class="text-[11px] font-extrabold text-[color:var(--muted)]">Total Records</div>
            <div class="mt-1 text-3xl font-black leading-none text-slate-900">{{ count($allRecords) }}</div>
          </div>
          <div class="rounded-2xl border border-black/10 bg-white/70 p-4">
            <div class="text-[11px] font-extrabold text-[color:var(--muted)]">Campuses</div>
            <div class="mt-1 text-3xl font-black leading-none text-[color:var(--maroon)]">{{ $campusCounts->count() }}</div>
          </div>
          <div class="rounded-2xl border border-black/10 bg-white/70 p-4 col-span-2">
            <div class="flex items-center justify-between gap-3">
              <div class="min-w-0">
                <div class="text-[11px] font-extrabold text-[color:var(--muted)]">Top Category</div>
                <div class="mt-1 font-black truncate">{{ $typeCounts->keys()->first() ?? '—' }}</div>
              </div>
              <div class="text-sm font-black text-[color:var(--muted)]">
                {{ $typeCounts->values()->first() ? $typeCounts->values()->first().' rec' : '—' }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- CHARTS (consistent heights & less gaps) --}}
    <section id="charts" class="mt-4 grid grid-cols-1 lg:grid-cols-12 gap-3 items-stretch">

      {{-- Trend --}}
      <div class="lg:col-span-8 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(220,180,80,.22), rgba(165,44,48,.10));">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Records per Month</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">date_registered</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-trend">📥 Download <span>▼</span></button>
              <div id="dlMenu-trend" class="dlMenu">
                <button data-dl-img="chartTrend" data-fn="records_per_month.png">PNG Image</button>
                <button data-dl-csv="trend" data-fn="records_per_month.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartTrend" data-fn="records_per_month.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="mt-3 flex flex-wrap gap-2">
            <select id="trendFilterIpType" class="px-3 py-1.5 text-xs border border-black/10 rounded-lg bg-white">
              <option value="">All IP Types</option>
              @foreach($distinctIpTypes as $ipType)
                <option value="{{ $ipType }}">{{ $ipType }}</option>
              @endforeach
            </select>
            <select id="trendFilterStatus" class="px-3 py-1.5 text-xs border border-black/10 rounded-lg bg-white">
              <option value="">All Status</option>
              @foreach($distinctStatuses as $status)
                <option value="{{ $status }}">{{ $status }}</option>
              @endforeach
            </select>
            <select id="trendFilterCampus" class="px-3 py-1.5 text-xs border border-black/10 rounded-lg bg-white">
              <option value="">All Campuses</option>
              @foreach($distinctCampuses as $campus)
                <option value="{{ $campus }}">{{ $campus }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartLg"><canvas id="chartTrend"></canvas></div>
        </div>
      </div>

      {{-- Year --}}
      <div class="lg:col-span-4 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)] bg-white/50">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Records per Year</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">date_registered</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-year">📥 Download <span>▼</span></button>
              <div id="dlMenu-year" class="dlMenu">
                <button data-dl-img="chartYear" data-fn="records_per_year.png">PNG Image</button>
                <button data-dl-csv="yearsRegistered" data-fn="records_per_year.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartYear" data-fn="records_per_year.pdf">PDF</button>
              </div>
            </div>
          </div>
          <div class="mt-3 flex flex-wrap gap-2">
            <select id="yearFilterIpType" class="px-2 py-1 text-xs border border-black/10 rounded-lg bg-white">
              <option value="">All IP Types</option>
              @foreach($distinctIpTypes as $ipType)
                <option value="{{ $ipType }}">{{ $ipType }}</option>
              @endforeach
            </select>
            <select id="yearFilterStatus" class="px-2 py-1 text-xs border border-black/10 rounded-lg bg-white">
              <option value="">All Status</option>
              @foreach($distinctStatuses as $status)
                <option value="{{ $status }}">{{ $status }}</option>
              @endforeach
            </select>
            <select id="yearFilterCampus" class="px-2 py-1 text-xs border border-black/10 rounded-lg bg-white">
              <option value="">All Campuses</option>
              @foreach($distinctCampuses as $campus)
                <option value="{{ $campus }}">{{ $campus }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartMd"><canvas id="chartYear"></canvas></div>
        </div>
      </div>

      {{-- Row 2 --}}
      <div class="lg:col-span-4 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)] bg-white/50">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Gender Distribution</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">contributors</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-gender">📥 Download <span>▼</span></button>
              <div id="dlMenu-gender" class="dlMenu">
                <button data-dl-img="chartGender" data-fn="gender_distribution.png">PNG Image</button>
                <button data-dl-csv="gender" data-fn="gender_distribution.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartGender" data-fn="gender_distribution.pdf">PDF</button>
              </div>
            </div>
          </div>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartMd"><canvas id="chartGender"></canvas></div>
          <div class="mt-3 hint border border-black/10 bg-white/75 text-sm text-[color:var(--muted)]">
            Counts contributors (not only records).
          </div>
        </div>
      </div>

      <div class="lg:col-span-4 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)] bg-white/50">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Status Breakdown</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">top 8 + others</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-status">📥 Download <span>▼</span></button>
              <div id="dlMenu-status" class="dlMenu">
                <button data-dl-img="chartStatus" data-fn="status_breakdown.png">PNG Image</button>
                <button data-dl-csv="status" data-fn="status_breakdown.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartStatus" data-fn="status_breakdown.pdf">PDF</button>
              </div>
            </div>
          </div>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartMd"><canvas id="chartStatus"></canvas></div>
        </div>
      </div>

      <div class="lg:col-span-4 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(153,34,38,.10), rgba(220,180,80,.18));">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Category / IP Type</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">top 8 + others</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-types">📥 Download <span>▼</span></button>
              <div id="dlMenu-types" class="dlMenu">
                <button data-dl-img="chartTypes" data-fn="category_mix.png">PNG Image</button>
                <button data-dl-csv="types" data-fn="category_mix.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartTypes" data-fn="category_mix.pdf">PDF</button>
              </div>
            </div>
          </div>
        </div>
        <!-- filters for category/ip type -->
        <div class="mt-3 flex flex-wrap gap-2 px-5">
          <select id="typesFilterIpType" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All IP Types</option>
            @foreach($distinctIpTypes as $ipType)
              <option value="{{ $ipType }}">{{ $ipType }}</option>
            @endforeach
          </select>
          <select id="typesFilterStatus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Status</option>
            @foreach($distinctStatuses as $status)
              <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
          </select>
          <select id="typesFilterCampus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Campuses</option>
            @foreach($distinctCampuses as $campus)
              <option value="{{ $campus }}">{{ $campus }}</option>
            @endforeach
          </select>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartMd"><canvas id="chartTypes"></canvas></div>
        </div>
      </div>

      {{-- Row 3 --}}
      <div class="lg:col-span-4 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)] bg-white/50">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Campus Distribution</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">top 6 + others</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-campus">📥 Download <span>▼</span></button>
              <div id="dlMenu-campus" class="dlMenu">
                <button data-dl-img="chartCampus" data-fn="campus_distribution.png">PNG Image</button>
                <button data-dl-csv="campus" data-fn="campus_distribution.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartCampus" data-fn="campus_distribution.pdf">PDF</button>
              </div>
            </div>
          </div>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartMd"><canvas id="chartCampus"></canvas></div>
        </div>
      </div>

      <div class="lg:col-span-8 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(220,180,80,.18), rgba(15,23,42,.06));">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Category by Campus</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">stacked</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-catcampus">📥 Download <span>▼</span></button>
              <div id="dlMenu-catcampus" class="dlMenu">
                <button data-dl-img="chartCatCampus" data-fn="category_by_campus.png">PNG Image</button>
                <button data-dl-csv="catCampus" data-fn="category_by_campus.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartCatCampus" data-fn="category_by_campus.pdf">PDF</button>
              </div>
            </div>
          </div>
        </div>
        <!-- filters for category-by-campus -->
        <div class="mt-3 flex flex-wrap gap-2 px-5">
          <select id="catCampusFilterIpType" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All IP Types</option>
            @foreach($distinctIpTypes as $ipType)
              <option value="{{ $ipType }}">{{ $ipType }}</option>
            @endforeach
          </select>
          <select id="catCampusFilterStatus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Status</option>
            @foreach($distinctStatuses as $status)
              <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
          </select>
          <select id="catCampusFilterCampus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Campuses</option>
            @foreach($distinctCampuses as $campus)
              <option value="{{ $campus }}">{{ $campus }}</option>
            @endforeach
          </select>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartLg"><canvas id="chartCatCampus"></canvas></div>
        </div>
      </div>

      {{-- Top Inventors + Gender by Category (balanced same row) --}}
      <div class="lg:col-span-7 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)] bg-white/50">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Top Contributors / Inventors</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">stacked by category</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-topinv">📥 Download <span>▼</span></button>
              <div id="dlMenu-topinv" class="dlMenu">
                <button data-dl-img="chartTopInventors" data-fn="top_inventors.png">PNG Image</button>
                <button data-dl-csv="topInventors" data-fn="top_inventors.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartTopInventors" data-fn="top_inventors.pdf">PDF</button>
              </div>
            </div>
          </div>
        </div>
        <!-- filters for top contributors -->
        <div class="mt-3 flex flex-wrap gap-2 px-5">
          <select id="topInvFilterIpType" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All IP Types</option>
            @foreach($distinctIpTypes as $ipType)
              <option value="{{ $ipType }}">{{ $ipType }}</option>
            @endforeach
          </select>
          <select id="topInvFilterStatus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Status</option>
            @foreach($distinctStatuses as $status)
              <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
          </select>
          <select id="topInvFilterCampus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Campuses</option>
            @foreach($distinctCampuses as $campus)
              <option value="{{ $campus }}">{{ $campus }}</option>
            @endforeach
          </select>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartXL"><canvas id="chartTopInventors"></canvas></div>
        </div>
      </div>

      <div class="lg:col-span-5 cardShell border border-[color:var(--line)] bg-[color:var(--card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[color:var(--line)]"
             style="background: linear-gradient(90deg, rgba(153,34,38,.10), rgba(220,180,80,.16));">
          <div class="cardHead">
            <div>
              <h2 class="cardTitle font-black text-base tracking-[-.2px]">Gender by Category</h2>
              <p class="cardSub text-xs text-[color:var(--muted)]">contributors</p>
            </div>
            <div class="dlDropdown">
              <button class="dlBtn" data-toggle="dlMenu-gendercat">📥 Download <span>▼</span></button>
              <div id="dlMenu-gendercat" class="dlMenu">
                <button data-dl-img="chartGenderCategory" data-fn="gender_by_category.png">PNG Image</button>
                <button data-dl-csv="genderByCategory" data-fn="gender_by_category.csv">Excel (CSV)</button>
                <button data-dl-pdf="chartGenderCategory" data-fn="gender_by_category.pdf">PDF</button>
              </div>
            </div>
          </div>
        </div>
        <!-- filters for gender-by-category -->
        <div class="mt-3 flex flex-wrap gap-2 px-5">
          <select id="genderCatFilterIpType" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All IP Types</option>
            @foreach($distinctIpTypes as $ipType)
              <option value="{{ $ipType }}">{{ $ipType }}</option>
            @endforeach
          </select>
          <select id="genderCatFilterStatus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Status</option>
            @foreach($distinctStatuses as $status)
              <option value="{{ $status }}">{{ $status }}</option>
            @endforeach
          </select>
          <select id="genderCatFilterCampus" class="px-3 py-1 text-xs border border-black/10 rounded-lg bg-white">
            <option value="">All Campuses</option>
            @foreach($distinctCampuses as $campus)
              <option value="{{ $campus }}">{{ $campus }}</option>
            @endforeach
          </select>
        </div>
        <div class="cardPad">
          <div class="chartWrap hChartLg"><canvas id="chartGenderCategory"></canvas></div>
        </div>
      </div>

    </section>

    <footer class="foot mt-3 flex flex-wrap items-center justify-between gap-2 text-xs text-[color:var(--muted)]">
      <div>© {{ now()->year }} • KTTM Intellectual Property Services</div>
      <div class="opacity-90">Analytics • Maroon + Gold + Slate</div>
    </footer>
  </div>

  <script>
    const KTTM_DATA = @json($js);

    // Download helpers
    function downloadBlob(filename, content, mime){
      const blob = new Blob([content], {type: mime});
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url; a.download = filename;
      document.body.appendChild(a); a.click();
      a.remove();
      URL.revokeObjectURL(url);
    }
    function toCSV(headers, rows){
      const esc = (v) => {
        const s = String(v ?? "");
        if(/[",\n]/.test(s)) return `"${s.replace(/"/g,'""')}"`;
        return s;
      };
      return headers.map(esc).join(",") + "\n" + rows.map(r => r.map(esc).join(",")).join("\n");
    }
    function downloadChartPNG(chart, filename){
      const a = document.createElement("a");
      a.href = chart.toBase64Image("image/png", 1);
      a.download = filename || "chart.png";
      a.click();
    }
    function downloadSimpleCSV(labels, values, filename, col1="Label", col2="Value"){
      const rows = labels.map((l,i)=> [l, values[i] ?? 0]);
      downloadBlob(filename || "data.csv", toCSV([col1,col2], rows), "text/csv;charset=utf-8");
    }
    function downloadCatCampusCSV(payload, filename){
      const campuses = payload.campusLabels || [];
      const cats = payload.categoryLabels || [];
      const rows = cats.map(cat => [cat, ...campuses.map(c => payload.matrix?.[cat]?.[c] ?? 0)]);
      downloadBlob(filename || "category_by_campus.csv", toCSV(["Category", ...campuses], rows), "text/csv;charset=utf-8");
    }
    function downloadTopInventorsCSV(payload, filename){
      const labels = payload.labels || [];
      const s = payload.series || {};
      const cols = ["Name", "Patent", "Utility Model", "Industrial Design", "Copyright", "Total"];
      const rows = labels.map((name, i) => [
        name,
        (s["Patent"]?.[i] ?? 0),
        (s["Utility Model"]?.[i] ?? 0),
        (s["Industrial Design"]?.[i] ?? 0),
        (s["Copyright"]?.[i] ?? 0),
        (payload.total?.[i] ?? 0),
      ]);
      downloadBlob(filename || "top_inventors.csv", toCSV(cols, rows), "text/csv;charset=utf-8");
    }
    function downloadGenderByCategoryCSV(payload, filename){
      const labels = payload.labels || [];
      const s = payload.series || {};
      const cols = ["Category", "Male", "Female", "Other", "Unknown"];
      const rows = labels.map((cat, i) => [
        cat,
        (s["Male"]?.[i] ?? 0),
        (s["Female"]?.[i] ?? 0),
        (s["Other"]?.[i] ?? 0),
        (s["Unknown"]?.[i] ?? 0),
      ]);
      downloadBlob(filename || "gender_by_category.csv", toCSV(cols, rows), "text/csv;charset=utf-8");
    }
    function downloadChartPDF(chart, filename){
      const { jsPDF } = window.jspdf || {};
      if(!jsPDF) return;

      const imgData = chart.toBase64Image("image/png", 1);
      const pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });

      const pageW = 297, pageH = 210;
      const margin = 10;
      const maxW = pageW - margin*2;
      const maxH = pageH - margin*2;

      const canvasW = chart.canvas.width;
      const canvasH = chart.canvas.height;
      const ratio = Math.min(maxW / canvasW, maxH / canvasH);

      const w = canvasW * ratio;
      const h = canvasH * ratio;

      pdf.addImage(imgData, 'PNG', margin, margin, w, h);
      pdf.save(filename || "chart.pdf");
    }

    // Chart helpers
    // generate colors or gradient fills for charts
    // if a CanvasRenderingContext2D is provided, returns gradients based on the
    // base palette; otherwise returns solid strings as before.
    // n: number of colours required
    // ctx: optional canvas context for gradient creation
    // palette: optional array of base colour strings to use instead of the
    //           default set.  This allows callers to supply their own colour
    //           schemes (for example to drop the built‑in green/gray entries).
    function makeColors(n, ctx, palette){
      const base = palette || [
        "rgba(165,44,48,.85)",
        "rgba(240,200,96,.90)",
        "rgba(15,23,42,.65)",
        "rgba(16,185,129,.65)",
        "rgba(245,158,11,.70)",
        "rgba(59,130,246,.60)",
        "rgba(99,102,241,.55)",
        "rgba(236,72,153,.55)",
        "rgba(34,197,94,.55)",
      ];
      const out = [];
      for(let i=0;i<n;i++){
        const col = base[i % base.length];
        if(ctx && ctx.canvas){
          const g = ctx.createLinearGradient(0,0,0,ctx.canvas.height);
          // use the same colour at both ends so opacity doesn’t fade out
          g.addColorStop(0, col);
          g.addColorStop(1, col);
          out.push(g);
        } else {
          out.push(col);
        }
      }
      return out;
    }
    function formatMonthLabel(yyyyMm){
      if(!yyyyMm) return "";
      const parts = String(yyyyMm).split('-');
      if(parts.length < 2) return yyyyMm;
      const y = parts[0];
      const m = parseInt(parts[1],10);
      const names = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
      return `${names[(m-1)]} ${y}`;
    }
    function baseOptions(){
      return {
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
          legend:{ display:true, position:"bottom", labels:{ boxWidth:12, boxHeight:12 } },
          tooltip:{ enabled:true }
        }
      };
    }

    const charts = {};

    // Trend
    {
      const el = document.getElementById("chartTrend");
      if(el){
        const ctxTrend = el.getContext('2d');
        const trendGradient = ctxTrend.createLinearGradient(0,0,0,el.height);
        trendGradient.addColorStop(0,'rgba(165,44,48,.85)');
        trendGradient.addColorStop(1,'rgba(220,180,80,.25)');
        charts.chartTrend = new Chart(el, {
          type:"line",
          data:{
            labels: (KTTM_DATA.monthsRegistered.labels || []).map(formatMonthLabel),
            datasets:[{
              label:"Records",
              data: KTTM_DATA.monthsRegistered.values,
              tension:.32,
              fill:true,
              borderColor:trendGradient,
              backgroundColor:trendGradient,
              borderWidth:2,
              pointRadius:2,
              pointBackgroundColor:"rgba(165,44,48,.85)"
            }]
          },
          options:{ ...baseOptions(), scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } }
        });
      }
    }

    // Year
    {
      const el = document.getElementById("chartYear");
      if(el){
        const ctxYear = el.getContext('2d');
        const colorsYear = makeColors(KTTM_DATA.yearsRegistered.labels.length, ctxYear);
        charts.chartYear = new Chart(el, {
          type:"bar",
          data:{
            labels: KTTM_DATA.yearsRegistered.labels,
            datasets:[{ label:"Records", data: KTTM_DATA.yearsRegistered.values, backgroundColor: colorsYear, borderWidth:1 }]
          },
          options:{
            ...baseOptions(),
            plugins:{ ...baseOptions().plugins, legend:{ display:false } },
            scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } }
          }
        });
      }
    }

    // Gender
    {
      const el = document.getElementById("chartGender");
      if(el){
        const ctxGender = el.getContext('2d');
        charts.chartGender = new Chart(el, {
          type:"doughnut",
          data:{
            labels: KTTM_DATA.gender.labels,
            datasets:[{
              data: KTTM_DATA.gender.values,
              backgroundColor: makeColors(KTTM_DATA.gender.labels.length, ctxGender),
              borderWidth:1
            }]
          },
          options:{ ...baseOptions(), cutout:"62%" }
        });
      }
    }

    // Status
    {
      const el = document.getElementById("chartStatus");
      if(el){
        const ctxStatus = el.getContext('2d');
        // avoid green and replace the previous gray with the requested brownish tone
        const statusPalette = [
          "rgba(165,44,48,.85)",  // maroon
          "rgba(240,200,96,.90)", // gold
          "rgba(184, 123, 58, .60)", // new colour replacing grey
          "rgba(139,46,50,.70)", // dark maroon (replacing blue)
          "rgba(245,158,11,.70)", // orange
          "rgba(99,102,241,.55)"  // purple
        ];
        charts.chartStatus = new Chart(el, {
          type:"doughnut",
          data:{
            labels: KTTM_DATA.status.labels,
            datasets:[{
              data: KTTM_DATA.status.values,
              backgroundColor: makeColors(KTTM_DATA.status.labels.length, ctxStatus, statusPalette),
              borderWidth:1
            }]
          },
          options:{ ...baseOptions(), cutout:"62%" }
        });
      }
    }

    // Types
    {
      const el = document.getElementById("chartTypes");
      if(el){
        const ctxTypes = el.getContext('2d');
        // provide a palette that skips the green entry and adds some blues
        const typePalette = [
          "rgba(165,44,48,.85)",
          "rgba(240,200,96,.90)",
          "rgba(59,130,246,.60)",
          "rgba(245,158,11,.70)",
          "rgba(99,102,241,.55)",
          "rgba(236,72,153,.55)",
        ];
        // compute colours and force 'Patent' entries to gold
        const typeLabelsInitial = KTTM_DATA.types.labels || [];
        const colorsTypes = makeColors(typeLabelsInitial.length, ctxTypes, typePalette);
        typeLabelsInitial.forEach((lbl,i)=>{
          if(lbl.toLowerCase() === 'patent'){
            colorsTypes[i] = 'rgba(220,180,80,.90)';
          }
        });
        charts.chartTypes = new Chart(el, {
          type:"bar",
          data:{
            labels: typeLabelsInitial,
            datasets:[{ label:"Records", data: KTTM_DATA.types.values, backgroundColor: colorsTypes, borderWidth:1 }]
          },
          options:{
            ...baseOptions(),
            plugins:{ ...baseOptions().plugins, legend:{ display:false } },
            scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } }
          }
        });
        // initial refresh to respect any default filters
        updateTypeChart();
      }
    }

    // Campus
    {
      const el = document.getElementById("chartCampus");
      if(el){
        const ctxCampus = el.getContext('2d');
        // generate distinct colours for each campus using same palette as catCampus
        const campusPalette = [
          "rgba(165,44,48,.85)",
          "rgba(220,180,80,.90)", // dark gold rather than blue
          "rgba(245,158,11,.70)",
          "rgba(99,102,241,.55)",
          "rgba(236,72,153,.55)",
        ];
        const campusColors = makeColors(KTTM_DATA.campus.labels.length, ctxCampus, campusPalette);
        charts.chartCampus = new Chart(el, {
          type:"bar",
          data:{
            labels: KTTM_DATA.campus.labels,
            datasets:[{ label:"Records", data: KTTM_DATA.campus.values, backgroundColor: campusColors, borderWidth:1 }]
          },
          options:{
            ...baseOptions(),
            plugins:{ ...baseOptions().plugins, legend:{ display:false } },
            scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } }
          }
        });
      }
    }

    // Category by Campus (stacked)
    {
      const el = document.getElementById("chartCatCampus");
      if(el){
        // build using same orientation as updateCatCampus: categories on x-axis,
        // campuses as the stacked series. This prevents a blank chart on page load
        const campuses = KTTM_DATA.catCampus.campusLabels || [];
        const cats = KTTM_DATA.catCampus.categoryLabels || [];
        // create a palette excluding the gray/green slots
        const campusPalette = [
          "rgba(165,44,48,.85)",
          "rgba(220,180,80,.90)", // dark gold replacing blue
          "rgba(245,158,11,.70)",
          "rgba(99,102,241,.55)",
          "rgba(236,72,153,.55)",
        ];
        const colors = makeColors(campuses.length, el.getContext('2d'), campusPalette);
        const datasets = campuses.map((camp, i) => ({
          label: camp,
          data: cats.map(cat => KTTM_DATA.catCampus.matrix?.[cat]?.[camp] ?? 0),
          backgroundColor: colors[i],
          borderWidth: 1
        }));

        charts.chartCatCampus = new Chart(el, {
          type: "bar",
          data: { labels: cats, datasets },
          options: {
            ...baseOptions(),
            scales: {
              // grouped bars (side by side) rather than stacked
              x: { stacked: false },
              y: { stacked: false, beginAtZero:true, ticks:{ precision:0 } }
            }
          }
        });
        // ensure it reflects filters (no-op initially)
        updateCatCampus();
      }
    }

    // Top Inventors (grouped)
    {
      const el = document.getElementById("chartTopInventors");
      const payload = KTTM_DATA.topInventors;
      const hasData = (payload?.labels?.length || 0) > 0;
      if(el && hasData){
        const s = payload.series || {};
        // define palette avoiding green/gray/blue; use dark gold, orange, purple options
        const invPalette = [
  "rgba(165,44,48,.90)",   // Patent - Strong Maroon
  "rgba(240,200,96,.95)",  // Utility Model - Bright Gold
  "rgba(245,158,11,.85)",  // Industrial Design - Orange
  "rgba(15,23,42,.75)"     // Copyright - Deep Slate
];
        const datasets = [
          { label: "Patent", data: s["Patent"] || [], backgroundColor: invPalette[0], borderWidth: 1 },
          { label: "Utility Model", data: s["Utility Model"] || [], backgroundColor: invPalette[1], borderWidth: 1 },
          { label: "Industrial Design", data: s["Industrial Design"] || [], backgroundColor: invPalette[2], borderWidth: 1 },
          { label: "Copyright", data: s["Copyright"] || [], backgroundColor: invPalette[3], borderWidth: 1 },
        ];

        charts.chartTopInventors = new Chart(el, {
          type: "bar",
          data: { labels: payload.labels, datasets },
          options: {
            ...baseOptions(),
            scales: {
              x: { stacked: false },
              y: { stacked: false, beginAtZero:true, ticks:{ precision:0 } }
            }
          }
        });
        // refresh with any filter values (defaults to original payload)
        updateTopInventors();
      }
    }

    // Gender by Category (stacked)
    {
      const el = document.getElementById("chartGenderCategory");
      const payload = KTTM_DATA.genderByCategory;
      const hasData = (payload?.labels?.length || 0) > 0;
      if(el && hasData){
        const ctxCat = el.getContext('2d');
        // reuse the same two colours that the gender-distribution doughnut uses
        // so male/female are instantly recognisable.  Others/Unknown can stay as
        // before (or be assigned additional palette entries if you prefer).
        const genderColors = makeColors(2, ctxCat);
        const s = payload.series || {};
        const datasets = [
          { label: "Male", data: s["Male"] || [], backgroundColor: genderColors[0], borderWidth: 1 },
          { label: "Female", data: s["Female"] || [], backgroundColor: genderColors[1], borderWidth: 1 },
          { label: "Other", data: s["Other"] || [], backgroundColor:"rgba(34,197,94,.55)", borderWidth: 1 },
          { label: "Unknown", data: s["Unknown"] || [], backgroundColor:"rgba(15,23,42,.40)", borderWidth: 1 },
        ];

        charts.chartGenderCategory = new Chart(el, {
          type: "bar",
          data: { labels: payload.labels, datasets },
          options: {
            ...baseOptions(),
            scales: {
              // grouped bars instead of stacked
              x: { stacked: false },
              y: { stacked: false, beginAtZero:true, ticks:{ precision:0 } }
            }
          }
        });
        // refresh with any filter values (defaults to original payload)
        updateGenderByCategory();
      }
    }

    // Filtering functions for Month/Year charts
   function updateMonthYearCharts(){

  // 🔵 TREND FILTERS
  const trendCat = document.getElementById("trendFilterIpType")?.value || "";
  const trendStatus = document.getElementById("trendFilterStatus")?.value || "";
  const trendCampus = document.getElementById("trendFilterCampus")?.value || "";

  // 🔴 YEAR FILTERS
  const yearCat = document.getElementById("yearFilterIpType")?.value || "";
  const yearStatus = document.getElementById("yearFilterStatus")?.value || "";
  const yearCampus = document.getElementById("yearFilterCampus")?.value || "";

  // 🔵 FILTER FOR TREND
  const trendFiltered = KTTM_DATA.allRecords.filter(r => {
    const rCat = ((r.category ?? r.type) || "").toString().trim();
    const rStatus = ((r.status) || "").toString().trim();
    const rCampus = ((r.campus) || "").toString().trim();

    const catMatch = !trendCat || rCat === trendCat.trim();
    const statusMatch = !trendStatus || rStatus === trendStatus.trim();
    const campusMatch = !trendCampus || rCampus === trendCampus.trim();
    return catMatch && statusMatch && campusMatch;
  });

  // 🔴 FILTER FOR YEAR
  const yearFiltered = KTTM_DATA.allRecords.filter(r => {
    const rCat = ((r.category ?? r.type) || "").toString().trim();
    const rStatus = ((r.status) || "").toString().trim();
    const rCampus = ((r.campus) || "").toString().trim();

    const catMatch = !yearCat || rCat === yearCat.trim();
    const statusMatch = !yearStatus || rStatus === yearStatus.trim();
    const campusMatch = !yearCampus || rCampus === yearCampus.trim();
    return catMatch && statusMatch && campusMatch;
  });

  // ---- MONTH GROUPING ----
  const byMonth = {};
  trendFiltered.forEach(r => {
    if(!r.registered) return;
    const key = r.registered.substring(0,7); // safer than new Date()
    byMonth[key] = (byMonth[key] || 0) + 1;
  });

  const monthLabels = Object.keys(byMonth).sort();
  const monthValues = monthLabels.map(k => byMonth[k]);

  if(charts.chartTrend){
    charts.chartTrend.data.labels = monthLabels.map(formatMonthLabel);
    charts.chartTrend.data.datasets[0].data = monthValues;
    charts.chartTrend.update();
  }

  // ---- YEAR GROUPING ----
  const byYear = {};
  yearFiltered.forEach(r => {
    if(!r.registered) return;
    const key = r.registered.substring(0,4);
    byYear[key] = (byYear[key] || 0) + 1;
  });

  const yearLabels = Object.keys(byYear).sort();
  const yearValues = yearLabels.map(k => byYear[k]);

  if(charts.chartYear){
    charts.chartYear.data.labels = yearLabels;
    charts.chartYear.data.datasets[0].data = yearValues;
    charts.chartYear.update();
  }
}

    // Filtering function for Category/IP Type chart
    function updateTypeChart(){
      const fType = document.getElementById("typesFilterIpType")?.value || "";
      const fStatus = document.getElementById("typesFilterStatus")?.value || "";
      const fCampus = document.getElementById("typesFilterCampus")?.value || "";
      const filtered = KTTM_DATA.allRecords.filter(r=>{
        const rc = ((r.category||r.type)||"").toString().trim();
        const rs = ((r.status)||"").toString().trim();
        const rcamp = ((r.campus)||"").toString().trim();
        if(fType && rc !== fType.trim()) return false;
        if(fStatus && rs !== fStatus.trim()) return false;
        if(fCampus && rcamp !== fCampus.trim()) return false;
        return true;
      });
      const counts = {};
      filtered.forEach(r=>{
        let key = ((r.category||r.type)||"").toString().trim() || "—";
        counts[key] = (counts[key]||0)+1;
      });
      const entries = Object.entries(counts).sort((a,b)=>b[1]-a[1]);
      const top = entries.slice(0,8);
      const others = entries.slice(8).reduce((sum,[,v])=>sum+v,0);
      if(others>0) top.push(["Others",others]);
      const labels = top.map(e=>e[0]);
      const values = top.map(e=>e[1]);
      if(charts.chartTypes){
        const ctxT = charts.chartTypes.ctx;
        charts.chartTypes.data.labels = labels;
        charts.chartTypes.data.datasets[0].data = values;
        // rebuild colours and force Patent to gold
        const typePalette = [
          "rgba(165,44,48,.85)",
          "rgba(240,200,96,.90)",
          "rgba(59,130,246,.60)",
          "rgba(245,158,11,.70)",
          "rgba(99,102,241,.55)",
          "rgba(236,72,153,.55)",
        ];
        let newColors = makeColors(labels.length, ctxT, typePalette);
        labels.forEach((lbl,i)=>{
          if(lbl.toLowerCase() === 'patent'){
            newColors[i] = 'rgba(220,180,80,.90)';
          }
        });
        charts.chartTypes.data.datasets[0].backgroundColor = newColors;
        charts.chartTypes.update();
      }
    }

    // Filtering function for Category-by-Campus chart
    function updateCatCampus(){
      const fType = document.getElementById("catCampusFilterIpType")?.value || "";
      const fStatus = document.getElementById("catCampusFilterStatus")?.value || "";
      const fCampus = document.getElementById("catCampusFilterCampus")?.value || "";
      const filtered = KTTM_DATA.allRecords.filter(r=>{
        const rc = ((r.category||r.type)||"").toString().trim();
        const rs = ((r.status)||"").toString().trim();
        const rcamp = ((r.campus)||"").toString().trim();
        if(fType && rc !== fType.trim()) return false;
        if(fStatus && rs !== fStatus.trim()) return false;
        if(fCampus && rcamp !== fCampus.trim()) return false;
        return true;
      });
      const catCounts = {};
      const campusCounts = {};
      filtered.forEach(r=>{
        const cat = ((r.category||r.type)||"").toString().trim()||"—";
        const camp = ((r.campus)||"").toString().trim()||"—";
        catCounts[cat] = (catCounts[cat]||0)+1;
        campusCounts[camp] = (campusCounts[camp]||0)+1;
      });
      const sortedCats = Object.entries(catCounts).sort((a,b)=>b[1]-a[1]).map(e=>e[0]);
      const sortedCamps = Object.entries(campusCounts).sort((a,b)=>b[1]-a[1]).map(e=>e[0]);
      const catLabels = sortedCats.slice(0,6);
      const campusLabels = sortedCamps.slice(0,6);
      const matrix = {};
      catLabels.forEach(cat=>{ matrix[cat] = {}; campusLabels.forEach(c=>{ matrix[cat][c]=0; }); });
      filtered.forEach(r=>{
        const cat = ((r.category||r.type)||"").toString().trim()||"—";
        const camp = ((r.campus)||"").toString().trim()||"—";
        if(catLabels.includes(cat) && campusLabels.includes(camp)){
          matrix[cat][camp] = (matrix[cat][camp]||0)+1;
        }
      });
      if(charts.chartCatCampus){
        const ctxCC = charts.chartCatCampus.ctx;
        // custom palette avoids gray/blue by swapping in dark gold
        const palette = [
          "rgba(165,44,48,.85)",
          "rgba(220,180,80,.90)",
          "rgba(245,158,11,.70)",
          "rgba(99,102,241,.55)",
          "rgba(236,72,153,.55)",
        ];
        const colorArr = makeColors(campusLabels.length, ctxCC, palette);
        charts.chartCatCampus.data.labels = catLabels;
        charts.chartCatCampus.data.datasets = campusLabels.map((camp,i)=>({
          label: camp,
          data: catLabels.map(cat=>matrix[cat][camp]||0),
          backgroundColor: colorArr[i],
          borderWidth:1
        }));
        charts.chartCatCampus.update();
      }
    }

    // Filtering function for Top Inventors chart
    function updateTopInventors(){
      const fType = document.getElementById("topInvFilterIpType")?.value || "";
      const fStatus = document.getElementById("topInvFilterStatus")?.value || "";
      const fCampus = document.getElementById("topInvFilterCampus")?.value || "";
      const filtered = KTTM_DATA.allRecords.filter(r=>{
        const rc = ((r.category||r.type)||"").toString().trim();
        const rs = ((r.status)||"").toString().trim();
        const rcamp = ((r.campus)||"").toString().trim();
        if(fType && rc !== fType.trim()) return false;
        if(fStatus && rs !== fStatus.trim()) return false;
        if(fCampus && rcamp !== fCampus.trim()) return false;
        return true;
      });
      const invMap = {};
      filtered.forEach(r=>{
        const name = (r.owner || "").toString().trim() || "—";
        const cat = ((r.category||r.type)||"").toString().trim()||"—";
        if(!invMap[name]) invMap[name] = { Patent:0, "Utility Model":0, "Industrial Design":0, Copyright:0, total:0 };
        if(invMap[name][cat] !== undefined) invMap[name][cat]++;
        invMap[name].total++;
      });
      const entries = Object.entries(invMap).sort(([,a],[,b])=>b.total - a.total);
      const top = entries.slice(0,8);
      const labels = top.map(e=>e[0]);
      const series = { Patent:[], "Utility Model":[], "Industrial Design":[], Copyright:[] };
      const totals = [];
      top.forEach(([,data])=>{
        series.Patent.push(data.Patent);
        series["Utility Model"].push(data["Utility Model"]);
        series["Industrial Design"].push(data["Industrial Design"]);
        series.Copyright.push(data.Copyright);
        totals.push(data.total);
      });
      const payload = { labels, series, total: totals };
      KTTM_DATA.topInventors = payload; // update for downloads
      if(charts.chartTopInventors){
        charts.chartTopInventors.data.labels = labels;
        charts.chartTopInventors.data.datasets[0].data = series.Patent;
        charts.chartTopInventors.data.datasets[1].data = series["Utility Model"];
        charts.chartTopInventors.data.datasets[2].data = series["Industrial Design"];
        charts.chartTopInventors.data.datasets[3].data = series.Copyright;
        // ensure colours remain the defined palette (in case order changes)
        const invPalette = [
          "rgba(165,44,48,.85)",
          "rgba(220,180,80,.90)",
          "rgba(245,158,11,.70)",
          "rgba(220,180,80,.90)",
        ];
        charts.chartTopInventors.data.datasets.forEach((ds,i)=>{
          ds.backgroundColor = invPalette[i] || ds.backgroundColor;
        });
        charts.chartTopInventors.update();
      }
    }

    // Filtering function for Gender by Category chart
    function updateGenderByCategory(){
      const fType = document.getElementById("genderCatFilterIpType")?.value || "";
      const fStatus = document.getElementById("genderCatFilterStatus")?.value || "";
      const fCampus = document.getElementById("genderCatFilterCampus")?.value || "";
      const raw = KTTM_DATA.genderByCategoryRaw || [];
      const filtered = raw.filter(r=>{
        const rc = (r.category||"").toString().trim();
        const rs = (r.status||"").toString().trim();
        const rcamp = (r.campus||"").toString().trim();
        if(fType && rc !== fType.trim()) return false;
        if(fStatus && rs !== fStatus.trim()) return false;
        if(fCampus && rcamp !== fCampus.trim()) return false;
        return true;
      });
      const counts = {};
      filtered.forEach(r=>{
        const cat = ((r.category||r.type)||"").toString().trim()||"—";
        let role = (r.role||"Unknown").toString().trim();
        if(role === "") role = "Unknown";
        role = role.charAt(0).toUpperCase() + role.slice(1).toLowerCase();
        if(!['Male','Female','Other','Unknown'].includes(role)) role = 'Other';
        if(!counts[cat]) counts[cat] = {Male:0,Female:0,Other:0,Unknown:0};
        counts[cat][role] = (counts[cat][role]||0) + 1;
      });
      const labels = KTTM_DATA.genderByCategory.labels || [];
      const series = {Male:[],Female:[],Other:[],Unknown:[]};
      labels.forEach(cat=>{
        const row = counts[cat] || {Male:0,Female:0,Other:0,Unknown:0};
        series.Male.push(row.Male);
        series.Female.push(row.Female);
        series.Other.push(row.Other);
        series.Unknown.push(row.Unknown);
      });
      KTTM_DATA.genderByCategory = { labels, series };
      if(charts.chartGenderCategory){
        charts.chartGenderCategory.data.labels = labels;
        charts.chartGenderCategory.data.datasets[0].data = series.Male;
        charts.chartGenderCategory.data.datasets[1].data = series.Female;
        charts.chartGenderCategory.data.datasets[2].data = series.Other;
        charts.chartGenderCategory.data.datasets[3].data = series.Unknown;
        // ensure grouped configuration persists (options remain but reaffirm)
        charts.chartGenderCategory.options.scales.x.stacked = false;
        charts.chartGenderCategory.options.scales.y.stacked = false;
        charts.chartGenderCategory.update();
      }
    }

    // Attach filter listeners
    // Attach filter listeners
    document.getElementById("trendFilterIpType")?.addEventListener("change", updateMonthYearCharts);
    document.getElementById("trendFilterStatus")?.addEventListener("change", updateMonthYearCharts);
    document.getElementById("trendFilterCampus")?.addEventListener("change", updateMonthYearCharts);
    document.getElementById("yearFilterIpType")?.addEventListener("change", updateMonthYearCharts);
    document.getElementById("yearFilterStatus")?.addEventListener("change", updateMonthYearCharts);
    document.getElementById("yearFilterCampus")?.addEventListener("change", updateMonthYearCharts);
    document.getElementById("typesFilterIpType")?.addEventListener("change", updateTypeChart);
    document.getElementById("typesFilterStatus")?.addEventListener("change", updateTypeChart);
    document.getElementById("typesFilterCampus")?.addEventListener("change", updateTypeChart);
  document.getElementById("catCampusFilterIpType")?.addEventListener("change", updateCatCampus);
  document.getElementById("catCampusFilterStatus")?.addEventListener("change", updateCatCampus);
  document.getElementById("catCampusFilterCampus")?.addEventListener("change", updateCatCampus);
  document.getElementById("topInvFilterIpType")?.addEventListener("change", updateTopInventors);
  document.getElementById("topInvFilterStatus")?.addEventListener("change", updateTopInventors);
  document.getElementById("topInvFilterCampus")?.addEventListener("change", updateTopInventors);
  // gender-by-category filters
  document.getElementById("genderCatFilterIpType")?.addEventListener("change", updateGenderByCategory);
  document.getElementById("genderCatFilterStatus")?.addEventListener("change", updateGenderByCategory);
  document.getElementById("genderCatFilterCampus")?.addEventListener("change", updateGenderByCategory);

    // Dropdown toggles
    function closeAllMenus(except){
      document.querySelectorAll(".dlMenu").forEach(m => {
        if(except && m === except) return;
        m.classList.remove("show");
      });
    }
    document.querySelectorAll("[data-toggle]").forEach(btn => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const menuId = btn.getAttribute("data-toggle");
        const menu = document.getElementById(menuId);
        if(!menu) return;
        const willOpen = !menu.classList.contains("show");
        closeAllMenus();
        if(willOpen) menu.classList.add("show");
      });
    });
    document.addEventListener("click", () => closeAllMenus());
    document.addEventListener("keydown", (e) => { if(e.key === "Escape") closeAllMenus(); });

    // Wire download buttons
    document.querySelectorAll("[data-dl-img]").forEach(btn => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const chartKey = btn.getAttribute("data-dl-img");
        const fn = btn.getAttribute("data-fn") || "chart.png";
        const chart = charts[chartKey];
        if(chart) downloadChartPNG(chart, fn);
        closeAllMenus();
      });
    });

    document.querySelectorAll("[data-dl-pdf]").forEach(btn => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const chartKey = btn.getAttribute("data-dl-pdf");
        const fn = btn.getAttribute("data-fn") || "chart.pdf";
        const chart = charts[chartKey];
        if(chart) downloadChartPDF(chart, fn);
        closeAllMenus();
      });
    });

    document.querySelectorAll("[data-dl-csv]").forEach(btn => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const key = btn.getAttribute("data-dl-csv");
        const fn = btn.getAttribute("data-fn") || "data.csv";

        if(key === "trend"){
          downloadSimpleCSV(KTTM_DATA.monthsRegistered.labels, KTTM_DATA.monthsRegistered.values, fn, "Month", "Records");
          closeAllMenus(); return;
        }
        if(key === "yearsRegistered"){
          downloadSimpleCSV(KTTM_DATA.yearsRegistered.labels, KTTM_DATA.yearsRegistered.values, fn, "Year", "Records");
          closeAllMenus(); return;
        }
        if(key === "catCampus"){
          downloadCatCampusCSV(KTTM_DATA.catCampus, fn);
          closeAllMenus(); return;
        }
        if(key === "topInventors"){
          downloadTopInventorsCSV(KTTM_DATA.topInventors, fn);
          closeAllMenus(); return;
        }
        if(key === "genderByCategory"){
          downloadGenderByCategoryCSV(KTTM_DATA.genderByCategory, fn);
          closeAllMenus(); return;
        }

        const map = {
          status: KTTM_DATA.status,
          types: KTTM_DATA.types,
          campus: KTTM_DATA.campus,
          gender: KTTM_DATA.gender
        }[key];

        if(map) downloadSimpleCSV(map.labels, map.values, fn, "Label", "Value");
        closeAllMenus();
      });
    });
  </script>

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

</body>
</html>