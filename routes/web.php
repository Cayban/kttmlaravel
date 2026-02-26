<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IpRecord;

/**
 * ✅ LANDING PAGE
 */
Route::get('/', function (Request $request) {
    // Optional year filter via query string: ?year=2025
    $year = $request->query('year');

    $baseQuery = function ($q) use ($year) {
        if ($year) {
            $q->whereYear('date_registered', $year);
        }
        return $q;
    };

    // Get IP type counts from database (optionally filtered by year)
    $patentCount    = $baseQuery(IpRecord::where('category', 'Patent'))->count();
    $copyrightCount = $baseQuery(IpRecord::where('category', 'Copyright'))->count();
    $utilityCount   = $baseQuery(IpRecord::where('category', 'Utility Model'))->count();
    $designCount    = $baseQuery(IpRecord::where('category', 'Industrial Design'))->count();
    $totalCount     = $baseQuery(IpRecord::query())->count();

    // Calculate percentages
    $total = $patentCount + $copyrightCount + $utilityCount + $designCount;
    $patentPercent    = $total > 0 ? round(($patentCount / $total) * 100) : 0;
    $copyrightPercent = $total > 0 ? round(($copyrightCount / $total) * 100) : 0;
    $utilityPercent   = $total > 0 ? round(($utilityCount / $total) * 100) : 0;
    $designPercent    = $total > 0 ? round(($designCount / $total) * 100) : 0;

    // Build available years list for selector (Postgres-compatible)
    $years = IpRecord::whereNotNull('date_registered')
        ->selectRaw("EXTRACT(YEAR FROM date_registered)::int as y")
        ->distinct()
        ->orderByDesc('y')
        ->pluck('y')
        ->map(fn($y) => (int)$y)
        ->toArray();

    $stats = [
        'active'    => $totalCount,
        'patent'    => ['count' => $patentCount, 'percent' => $patentPercent],
        'copyright' => ['count' => $copyrightCount, 'percent' => $copyrightPercent],
        'utility'   => ['count' => $utilityCount, 'percent' => $utilityPercent],
        'design'    => ['count' => $designCount, 'percent' => $designPercent],
    ];

    return view('welcome', ['stats' => $stats, 'years' => $years, 'selectedYear' => $year]);
});

/**
 * ✅ HOME / DASHBOARD
 */
Route::get('/home', function (
    \Illuminate\Http\Request $request
) {

    // allow optional calendar month/year via query string
    $now = \Carbon\Carbon::now();
    $calMonth = (int) $request->query('month', $now->month);
    $calYear  = (int) $request->query('year',  $now->year);

    // clamp values to reasonable range
    if ($calMonth < 1 || $calMonth > 12) {
        $calMonth = $now->month;
    }
    if ($calYear < 1900 || $calYear > ($now->year + 10)) {
        $calYear = $now->year;
    }

    $kpis = [
        'my_open'         => IpRecord::where('status', '!=', 'Registered')->count(),
        'needs_attention' => IpRecord::where('status', 'Close to Expiration')->count(),
        'due_soon'        => IpRecord::where('status', 'Close to Expiration')->count(),
        'total_records'   => IpRecord::count(),
    ];

    $recent = IpRecord::orderByDesc('date_registered')
        ->limit(10)
        ->get()
        ->map(function ($r) {
            return [
                'id'      => $r->record_id,
                'title'   => $r->ip_title,
                'type'    => $r->category,
                'status'  => $r->status,
                'updated' => $r->date_registered,
            ];
        })
        ->toArray();

    $allRecords = IpRecord::orderByDesc('date_registered')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor_summary,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered,
                'ipophl_id'  => $r->ipophl_id,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $user = (object)[
        'name' => 'KTTM User',
        'role' => 'Staff',
    ];

    return view('home', compact('user', 'kpis', 'recent', 'allRecords', 'calMonth', 'calYear'));
})->name('home');

// AJAX endpoint for calendar partial – returns rendered table only
Route::get('/home/calendar', function (\Illuminate\Http\Request $request) {
    $now = \Carbon\Carbon::now();
    $calMonth = (int) $request->query('month', $now->month);
    $calYear  = (int) $request->query('year',  $now->year);
    if ($calMonth < 1 || $calMonth > 12) {
        $calMonth = $now->month;
    }
    if ($calYear < 1900 || $calYear > ($now->year + 10)) {
        $calYear = $now->year;
    }
    return view('partials.home_calendar', compact('calMonth','calYear'));
});

/**
 * ✅ RECORDS PAGE (shared URI for guests & authenticated users)
 */
Route::get('/ip-records', function () {

    $allRecords = IpRecord::orderByDesc('date_registered')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor_summary,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered,
                'ipophl_id'  => $r->ipophl_id,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();

    // if the visitor is authenticated we show the full records page
    if (\Illuminate\Support\Facades\Auth::check()) {
        $user = (object)[
            'name' => 'KTTM User',
            'role' => 'Staff',
        ];
        return view('records', compact('user', 'allRecords', 'campuses', 'types', 'statuses'));
    }

    // guest experience: render guest-specific view; the blade itself provides defaults for missing values
    return view('guestrecords', compact('allRecords', 'campuses', 'types', 'statuses'));
})->name('records');


/**
 * ✅ STAFF-ONLY RECORDS ROUTE (use in navbars for authenticated users)
 * This ensures that clicking "Records" while already on the page
 * keeps the user within the staff view rather than bouncing to the
 * shared guest route.
 */
Route::get('/records', function () {
    // simply reuse the same logic as the authenticated branch above
    $allRecords = IpRecord::orderByDesc('date_registered')
        ->get()
        ->map(fn($r) => [
            'id'         => $r->record_id,
            'title'      => $r->ip_title,
            'type'       => $r->category,
            'owner'      => $r->owner_inventor_summary,
            'campus'     => $r->campus,
            'status'     => $r->status,
            'registered' => $r->date_registered,
            'ipophl_id'  => $r->ipophl_id,
            'gdrive_link'=> $r->gdrive_link,
            'remarks'    => $r->remarks,
        ])
        ->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();

    $user = (object)[
        'name' => 'KTTM User',
        'role' => 'Staff',
    ];

    return view('records', compact('user', 'allRecords', 'campuses', 'types', 'statuses'));
})->name('records.staff'); // auth middleware removed so nav link works for everyone

// export records as CSV (optional date filter)
Route::get('/records/export', function (Request $request) {
    $query = App\Models\IpRecord::orderByDesc('date_registered');
    $hasDateFilter = false;
    $start = null;
    $end = null;
    
    if ($request->filled('start') && $request->filled('end')) {
        $start = $request->input('start');
        $end   = $request->input('end');
        $hasDateFilter = true;
        try {
            $query->whereDate('date_registered', '>=', $start)
                  ->whereDate('date_registered', '<=', $end);
        } catch (\Exception $e) {
            // ignore invalid dates, fallback to no filter
            $hasDateFilter = false;
        }
    }
    $records = $query->get();

    $columns = ['Record ID','IP Title','Category','Owner','Campus','Status','Date Registered','IPOPHL ID','GDrive Link','Remarks'];
    $callback = function () use ($records, $columns) {
        $out = fopen('php://output', 'w');
        fputcsv($out, $columns);
        foreach ($records as $r) {
            fputcsv($out, [
                $r->record_id,
                $r->ip_title,
                $r->category,
                $r->owner_inventor_summary,
                $r->campus,
                $r->status,
                $r->date_registered,
                $r->ipophl_id,
                $r->gdrive_link,
                $r->remarks,
            ]);
        }
        fclose($out);
    };
    
    // Generate filename based on filter
    if ($hasDateFilter && $start && $end) {
        $filename = 'KTTM_Records_' . $start . '_to_' . $end . '.csv';
    } else {
        $filename = 'KTTM_Full_Records.csv';
    }
    
    return response()->stream($callback, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"{$filename}\"",
    ]);
})->name('records.export');

/**
 * ✅ NEW RECORD FORM PAGE
 */
Route::get('/ipassets/create', function () {

    $allRecords = IpRecord::orderByDesc('date_registered')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor_summary,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered,
                'ipophl_id'  => $r->ipophl_id,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();

    $user = (object)[
        'name' => 'KTTM User',
        'role' => 'Staff',
    ];

    // compute next available record id so the form can display it (read‑only)
    $nextNumber = 1;
    $allForId = IpRecord::where('record_id', 'like', 'KTTM-%')->get();
    foreach ($allForId as $record) {
        if (preg_match('/KTTM-(\d+)$/', $record->record_id, $m)) {
            $num = (int)$m[1];
            if ($num >= $nextNumber) {
                $nextNumber = $num + 1;
            }
        }
    }
    $nextRecordId = 'KTTM-' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);

    return view('Newrecord', compact('user', 'campuses', 'types', 'statuses', 'nextRecordId'));
})->name('ipassets.create');

// API: search existing records by title substring (used by Newrecord page for duplicate warning)
Route::get('/ipassets/check-title', function (Request $request) {
    $q = trim($request->query('title', ''));
    if ($q === '') {
        return response()->json([]);
    }

    $matches = IpRecord::where('ip_title', 'like', '%' . $q . '%')
        ->orderBy('date_registered', 'desc')
        ->limit(5)
        ->get(['record_id', 'ip_title'])
        ->map(function ($r) {
            return [
                'record_id' => $r->record_id,
                'ip_title'   => $r->ip_title,
            ];
        });

    return response()->json($matches);
});

/**
 * ✅ STORE NEW RECORD
 */
Route::post('/ipassets', function (Request $request) {

    $data = $request->validate([
        'title'       => 'required|string|max:1024',
        'type'        => 'required|string|max:255',
        'status'      => 'required|string|max:255',
        'campus'      => 'required|string|max:255',
        'registered'  => 'nullable|date',
        'ipophl_id'   => 'nullable|string|max:255',
        'gdrive_link' => 'nullable|url|max:2048',
        'remarks'     => 'nullable|string',
        'inventors'   => 'nullable|json',
    ]);

    // server-side duplicate guard (exact match)
    if (!empty($data['title'])) {
        $exists = IpRecord::whereRaw('LOWER(ip_title) = ?', [strtolower($data['title'])])->exists();
        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['title' => 'A record with this title already exists. Please verify or edit the existing record.']);
        }
    }

    return DB::transaction(function () use ($request, $data) {

        // ✅ Next KTTM-### record id - Get ALL KTTM records and find max
        $allRecords = IpRecord::where('record_id', 'like', 'KTTM-%')
            ->lockForUpdate()
            ->get();

        $nextNumber = 1;
        foreach ($allRecords as $record) {
            if (preg_match('/KTTM-(\d+)$/', $record->record_id, $m)) {
                $num = (int)$m[1];
                if ($num >= $nextNumber) {
                    $nextNumber = $num + 1;
                }
            }
        }
        $recordId = 'KTTM-' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);

        // ✅ Build owner summary from inventors JSON
        $ownerSummary = null;
        $invArr = [];

        if (!empty($data['inventors'])) {
            $invArr = json_decode($data['inventors'], true);
            if (is_array($invArr)) {
                $names = [];
                foreach ($invArr as $item) {
                    $n = is_array($item) ? trim($item['name'] ?? '') : trim((string)$item);
                    if ($n !== '') $names[] = $n;
                }
                $ownerSummary = implode('; ', $names);
            } else {
                $invArr = [];
            }
        }

        // ✅ Save record
        $record = new IpRecord();
        $record->record_id              = $recordId;
        $record->ip_title               = $data['title'];
        $record->category               = $data['type'];
        $record->owner_inventor_summary = $ownerSummary ?? ($request->input('owner') ?? '');
        $record->campus                 = $data['campus'];
        $record->status                 = $data['status'];
        $record->date_registered        = $data['registered'] ?? null;
        $record->ipophl_id              = $data['ipophl_id'] ?? null;
        $record->gdrive_link            = $data['gdrive_link'] ?? null;
        $record->remarks                = $data['remarks'] ?? null;
        $record->save();

        // ✅ Insert contributors (FIXED columns)
        if (!empty($invArr) && is_array($invArr)) {

            // Find latest contributor_id like A### - Get ALL and find max
            $allContribs = DB::table('ip_contributors')
                ->where('contributor_id', 'like', 'A%')
                ->lockForUpdate()
                ->get();

            $nextC = 1;
            foreach ($allContribs as $contrib) {
                if (preg_match('/A(\d+)$/', $contrib->contributor_id ?? '', $m2)) {
                    $num = (int)$m2[1];
                    if ($num >= $nextC) {
                        $nextC = $num + 1;
                    }
                }
            }

            $rows = [];
            foreach ($invArr as $item) {
                $name = is_array($item) ? trim($item['name'] ?? '') : trim((string)$item);
                $role = is_array($item) ? trim($item['gender'] ?? $item['role'] ?? '') : '';

                if ($name === '') continue;

                $contribId = 'A' . str_pad((string)$nextC, 3, '0', STR_PAD_LEFT);
                $nextC++;

                $rows[] = [
                    'contributor_id'   => $contribId,
                    'record_id'        => $recordId,
                    'contributor_name' => $name, // ✅ correct column
                    'role'             => $role !== '' ? ucfirst(strtolower($role)) : 'Unknown',
                ];
            }

            if (!empty($rows)) {
                DB::table('ip_contributors')->insert($rows);
            }
        }

        // always redirect to the staff-only view so authenticated users
        // don’t accidentally land on the guest template after submission.
        return redirect()->route('records.staff')
            ->with('success', "Record created successfully: {$recordId}");
    });
})->name('ipassets.store');

/**
 * ✅ UPDATE RECORD
 */
Route::post('/records/{id}/update', function (Request $request, $id) {

    $id = trim(urldecode($id));
    $record = IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();

    if (!$record) {
        return response()->json([
            'success' => false,
            'message' => "Record not found: {$id}",
        ], 404);
    }

    // Track what changed
    $changes = [];
    
    if ($request->has('title')) {
        $old = $record->ip_title;
        $new = $request->input('title');
        if ($old !== $new) $changes['Title'] = ['old' => $old, 'new' => $new];
        $record->ip_title = $new;
    }
    
    if ($request->has('type')) {
        $old = $record->category;
        $new = $request->input('type');
        if ($old !== $new) $changes['Category'] = ['old' => $old, 'new' => $new];
        $record->category = $new;
    }
    
    if ($request->has('owner')) {
        $old = $record->owner_inventor_summary;
        $new = $request->input('owner');
        if ($old !== $new) $changes['Owner'] = ['old' => $old, 'new' => $new];
        $record->owner_inventor_summary = $new;
    }
    
    if ($request->has('campus')) {
        $old = $record->campus;
        $new = $request->input('campus');
        if ($old !== $new) $changes['Campus'] = ['old' => $old, 'new' => $new];
        $record->campus = $new;
    }
    
    if ($request->has('status')) {
        $old = $record->status;
        $new = $request->input('status');
        if ($old !== $new) $changes['Status'] = ['old' => $old, 'new' => $new];
        $record->status = $new;
    }

    if ($request->has('ipophl_id')) {
        $old = $record->ipophl_id;
        $new = $request->input('ipophl_id');
        if ($old !== $new) $changes['IPOPhl ID'] = ['old' => $old, 'new' => $new];
        $record->ipophl_id = $new;
    }
    
    if ($request->has('gdrive_link')) {
        $old = $record->gdrive_link;
        $new = $request->input('gdrive_link');
        if ($old !== $new) $changes['GDrive Link'] = ['old' => $old, 'new' => $new];
        $record->gdrive_link = $new;
    }
    
    if ($request->has('remarks')) {
        $old = $record->remarks;
        $new = $request->input('remarks');
        if ($old !== $new) $changes['Remarks'] = ['old' => $old, 'new' => $new];
        $record->remarks = $new;
    }

    if ($request->filled('registered')) {
        $old = $record->date_registered;
        $new = $request->input('registered');
        if ($old !== $new) $changes['Date Registered'] = ['old' => $old, 'new' => $new];
        $record->date_registered = $new;
    }

    $record->save();
    
    // Log the update activity only if something changed
    if (!empty($changes)) {
        try {
            \App\Models\ActivityLog::create([
                'record_id' => $record->record_id,
                'record_title' => $record->ip_title,
                'action' => 'Modified',
                'changes' => $changes,
                'user_name' => 'KTTM User',
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to log activity: ' . $e->getMessage());
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Record updated successfully',
        'id'      => $record->record_id,
    ]);
})->name('records.update');


/**
 * ✅ RECORD CHANGES PAGE
 * Clicking a modified update in the sidebar should redirect here.  The view
 * `Modifiedrecords.blade.php` (aka “record‑changes”) will show an audit trail.
 */
Route::get('/record-changes/{id}', function (Request $request, $id) {
    $id = trim(urldecode($id));

    $recordDb = IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();
    $recordArr = null;
    if ($recordDb) {
        $recordArr = [
            'id'     => $recordDb->record_id,
            'title'  => $recordDb->ip_title,
            'type'   => $recordDb->category,
            'owner'  => $recordDb->owner_inventor_summary,
            'campus' => $recordDb->campus,
            'status' => $recordDb->status,
        ];
    }

    $user = (object)[
        'name' => 'KTTM User',
        'role' => 'Staff',
    ];

    return view('Modifiedrecords', [
        'user'     => $user,
        'record'   => $recordArr,
        'recordId' => $id,
    ]);
});


/**
 * ✅ API: record change history for a specific record id
 */
Route::get('/api/records/{id}/changes', function (Request $request, $id) {
    $id = trim(urldecode($id));

    $recordDb = IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();
    $recordData = null;
    if ($recordDb) {
        $recordData = [
            'id'     => $recordDb->record_id,
            'title'  => $recordDb->ip_title,
            'type'   => $recordDb->category,
            'owner'  => $recordDb->owner_inventor_summary,
            'campus' => $recordDb->campus,
            'status' => $recordDb->status,
        ];
    }

    $events = collect();
    try {
        $events = \App\Models\ActivityLog::where('record_id', $id)
            ->orderBy('created_at', 'desc')
            ->get(['action', 'changes', 'created_at', 'user_name', 'record_title'])
            ->map(function ($log) {
                return [
                    'action'    => $log->action,
                    'timestamp' => $log->created_at->toIso8601String(),
                    'actor'     => $log->user_name ?? null,
                    'changes'   => $log->changes ?? [],
                    'summary'   => $log->record_title ?? null,
                ];
            });
    } catch (\Exception $e) {
        \Log::info('Error fetching activity logs for changes API: ' . $e->getMessage());
    }

    return response()->json([
        'record' => $recordData,
        'events' => $events,
    ]);
});

/**
 * ✅ GUEST ACCESS
 */
Route::match(['get', 'post'], '/guest', function () {
    $records = IpRecord::orderByDesc('date_registered')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor_summary,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered,
                'ipophl_id'  => $r->ipophl_id,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $guest = (object)[
        'name' => 'Guest Viewer',
        'role' => 'Guest',
    ];

    return view('guest', compact('guest', 'records'));
});

Route::get('/how-to-file', function () { return view('Howtofile'); });
Route::get('/about', function () { return view('about'); });

Route::get('/guest/records', function () {
    $allRecords = IpRecord::orderByDesc('date_registered')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor_summary,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered,
                'ipophl_id'  => $r->ipophl_id,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $campuses = collect($allRecords)->pluck('campus')->filter()->unique()->sort()->values()->all();
    $types    = collect($allRecords)->pluck('type')->filter()->unique()->sort()->values()->all();
    $statuses = collect($allRecords)->pluck('status')->filter()->unique()->sort()->values()->all();

    $user = (object)[
        'name' => 'Guest Viewer',
        'role' => 'Guest',
    ];

    $recent = IpRecord::orderByDesc('date_registered')
        ->limit(10)
        ->get()
        ->map(function ($r) {
            return [
                'id'      => $r->record_id,
                'title'   => $r->ip_title,
                'type'    => $r->category,
                'status'  => $r->status,
                'updated' => $r->date_registered,
            ];
        })
        ->toArray();

    return view('guestrecords', compact('user', 'allRecords', 'campuses', 'types', 'statuses', 'recent'));
})->name('guest.records');

Route::get('/insights', function () {
    $allRecords = IpRecord::orderByDesc('date_registered')
        ->get()
        ->map(function ($r) {
            return [
                'id'         => $r->record_id,
                'title'      => $r->ip_title,
                'type'       => $r->category,
                'owner'      => $r->owner_inventor_summary,
                'campus'     => $r->campus,
                'status'     => $r->status,
                'registered' => $r->date_registered,
                'ipophl_id'  => $r->ipophl_id,
                'gdrive_link'=> $r->gdrive_link,
                'remarks'    => $r->remarks,
            ];
        })
        ->toArray();

    $user = (object)[
        'name' => 'KTTM User',
        'role' => 'Staff',
    ];

    return view('insights', compact('user', 'allRecords'));
})->name('insights');

/**
 * ✅ API: RECENT UPDATES
 * Returns the 20 most recent changes: Created, Modified, or Archived records
 */
Route::get('/api/recent-updates', function (Request $request) {
    try {
        $activityUpdates = collect();
        
        // Try to get activity logs if table exists
        try {
            $activityUpdates = \App\Models\ActivityLog::orderBy('created_at', 'desc')
                ->limit(50)
                ->get(['id','record_id', 'record_title', 'action', 'changes', 'created_at'])
                ->map(function ($log) {
                    return [
                        'log_id'         => $log->id,        // include id so we can delete later
                        'record_id'      => $log->record_id,
                        'record_title'   => $log->record_title,
                        'action'         => $log->action,
                        'changes'        => $log->changes ?? [],
                        'timestamp'      => $log->created_at->toIso8601String(),
                    ];
                });
        } catch (\Exception $e) {
            // Table might not exist yet - that's ok
            \Log::info('ActivityLog table not ready: ' . $e->getMessage());
        }
        
        // Get recently created/registered records
        $createdRecords = IpRecord::whereNotNull('date_registered')
            ->orderBy('date_registered', 'desc')
            ->limit(50)
            ->get(['record_id', 'ip_title', 'category', 'date_registered'])
            ->map(function ($r) {
                $date = \Carbon\Carbon::parse($r->date_registered);
                return [
                    'log_id'         => null,  // null for IpRecord entries (deletion handled differently)
                    'record_id'      => $r->record_id,
                    'record_title'   => $r->ip_title,
                    'record_type'    => $r->category,
                    'action'         => 'Created',
                    'changes'        => [],
                    'timestamp'      => $date->toIso8601String(),
                ];
            });
        
        // Merge both, sort by timestamp (newest first), and take top 20
        $allUpdates = $activityUpdates->merge($createdRecords)
            ->sortByDesc('timestamp')
            ->take(20)
            ->values();

        return response()->json(['updates' => $allUpdates]);
    } catch (\Exception $e) {
        \Log::error('Recent updates API error: ' . $e->getMessage());
        return response()->json(['updates' => [], 'error' => $e->getMessage()], 500);
    }
});