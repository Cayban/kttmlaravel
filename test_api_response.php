<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simulate the API request
$recordId = 'PATENT-2026-001';
echo "Testing API endpoint for record: $recordId\n";
echo "=".str_repeat("=", 50)."\n";

$id = trim(urldecode($recordId));

$recordDb = \App\Models\IpRecord::whereRaw('TRIM(record_id) = ?', [$id])->first();
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
    echo 'Error fetching activity logs: ' . $e->getMessage() . "\n";
}

$response = [
    'record' => $recordData,
    'events' => $events,
];

echo "API Response:\n";
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
