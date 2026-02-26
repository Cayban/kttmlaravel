<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();

echo "=".str_repeat("=", 70)."\n";
echo "COMPLETE WORKFLOW TEST: Edit Record → Create ActivityLog → Retrieve\n";
echo "=".str_repeat("=", 70)."\n";

$recordId = 'PATENT-2026-001';
echo "\n1. Getting original record: $recordId\n";
echo "   ".str_repeat("-", 68)."\n";

$record = \App\Models\IpRecord::whereRaw('TRIM(record_id) = ?', [$recordId])->first();
if (!$record) {
    echo "   ERROR: Record not found\n";
    exit(1);
}

echo "   Original Status: " . $record->status . "\n";
echo "   Original Campus: " . $record->campus . "\n";
echo "   Original Remarks: " . $record->remarks . "\n";

// Simulate the edit payload
echo "\n2. Simulating edit form submission\n";
echo "   ".str_repeat("-", 68)."\n";

$payload = [
    'title' => $record->ip_title,  // No change
    'type' => $record->category,   // No change
    'owner' => $record->owner_inventor_summary, // No change
    'campus' => 'Manila',  // CHANGED
    'status' => 'Registered',  // CHANGED
    'registered' => $record->date_registered,
    'ipophl_id' => $record->ipophl_id,
    'gdrive_link' => $record->gdrive_link,
    'remarks' => 'Updated remarks for testing',  // CHANGED
];

echo "   New Status: " . $payload['status'] . " (was: " . $record->status . ")\n";
echo "   New Campus: " . $payload['campus'] . " (was: " . $record->campus . ")\n";
echo "   New Remarks: " . $payload['remarks'] . "\n";

// Simulate the update logic
echo "\n3. Processing update (comparing changes)\n";
echo "   ".str_repeat("-", 68)."\n";

$changes = [];

if ($record->ip_title !== $payload['title']) {
    $changes['Title'] = ['old' => $record->ip_title, 'new' => $payload['title']];
}

if ($record->category !== $payload['type']) {
    $changes['Category'] = ['old' => $record->category, 'new' => $payload['type']];
}

if ($record->owner_inventor_summary !== $payload['owner']) {
    $changes['Owner'] = ['old' => $record->owner_inventor_summary, 'new' => $payload['owner']];
}

if ($record->campus !== $payload['campus']) {
    $changes['Campus'] = ['old' => $record->campus, 'new' => $payload['campus']];
}

if ($record->status !== $payload['status']) {
    $changes['Status'] = ['old' => $record->status, 'new' => $payload['status']];
}

if ($record->remarks !== $payload['remarks']) {
    $changes['Remarks'] = ['old' => $record->remarks, 'new' => $payload['remarks']];
}

echo "   Changes detected: " . count($changes) . "\n";
foreach ($changes as $field => $vals) {
    echo "   - $field: '" . $vals['old'] . "' → '" . $vals['new'] . "'\n";
}

// Actually update the record
echo "\n4. Updating record in database\n";
echo "   ".str_repeat("-", 68)."\n";

$record->ip_title = $payload['title'];
$record->category = $payload['type'];
$record->owner_inventor_summary = $payload['owner'];
$record->campus = $payload['campus'];
$record->status = $payload['status'];
$record->remarks = $payload['remarks'];
if (!empty($payload['registered'])) {
    $record->date_registered = $payload['registered'];
}
if (!empty($payload['ipophl_id'])) {
    $record->ipophl_id = $payload['ipophl_id'];
}
if (!empty($payload['gdrive_link'])) {
    $record->gdrive_link = $payload['gdrive_link'];
}

$record->save();
echo "   Record saved successfully\n";

// Create ActivityLog entry
if (!empty($changes)) {
    echo "\n5. Creating ActivityLog entry\n";
    echo "   ".str_repeat("-", 68)."\n";
    
    $logEntry = \App\Models\ActivityLog::create([
        'record_id' => $record->record_id,
        'record_title' => $record->ip_title,
        'action' => 'Modified',
        'changes' => $changes,
        'user_name' => 'KTTM User',
    ]);
    
    echo "   ActivityLog created with ID: " . $logEntry->id . "\n";
    echo "   Changes stored as JSON in database\n";
} else {
    echo "\n5. No changes detected, skipping ActivityLog\n";
}

// Now retrieve via the API
echo "\n6. Retrieving via /api/records/{$recordId}/changes API\n";
echo "   ".str_repeat("-", 68)."\n";

$logs = \App\Models\ActivityLog::where('record_id', $recordId)
    ->orderBy('created_at', 'desc')
    ->get(['action', 'changes', 'created_at', 'user_name', 'record_title']);

echo "   ActivityLog entries found: " . count($logs) . "\n";

$events = [];
foreach ($logs as $log) {
    $event = [
        'action'    => $log->action,
        'timestamp' => $log->created_at->toIso8601String(),
        'actor'     => $log->user_name,
        'changes'   => $log->changes,
        'summary'   => $log->record_title,
    ];
    $events[] = $event;
    
    echo "\n   Event:\n";
    echo "   - Action: " . $event['action'] . "\n";
    echo "   - Actor: " . $event['actor'] . "\n";
    echo "   - Timestamp: " . $event['timestamp'] . "\n";
    echo "   - Changes: " . count($event['changes']) . " field(s)\n";
    foreach ($event['changes'] as $field => $vals) {
        echo "     • $field: '" . $vals['old'] . "' → '" . $vals['new'] . "'\n";
    }
}

echo "\n7. JSON Response Format (what frontend receives)\n";
echo "   ".str_repeat("-", 68)."\n";
echo json_encode(['record' => null, 'events' => $events], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

echo "\n".str_repeat("=", 70)."\n";
echo "✓ Test Complete\n";
echo "=".str_repeat("=", 70)."\n";
