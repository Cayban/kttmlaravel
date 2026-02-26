<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Create a test ActivityLog entry
$recordId = 'PATENT-2026-001';
echo "Creating test ActivityLog entry for record: $recordId\n";
echo "=".str_repeat("=", 50)."\n";

$logEntry = \App\Models\ActivityLog::create([
    'record_id' => $recordId,
    'record_title' => 'Test Patent Application - Lorem Ipsum Dolor',
    'action' => 'Modified',
    'changes' => [
        'Title' => ['old' => 'Old Title', 'new' => 'New Title'],
        'Status' => ['old' => 'Pending', 'new' => 'For Filing'],
        'Campus' => ['old' => 'Diliman', 'new' => 'Los Baños'],
    ],
    'user_name' => 'KTTM User',
]);

echo "ActivityLog entry created:\n";
echo json_encode($logEntry->toArray(), JSON_PRETTY_PRINT) . "\n";

// Verify we can retrieve it
echo "\nVerifying retrieval:\n";
echo "=".str_repeat("=", 50)."\n";
$logs = \App\Models\ActivityLog::where('record_id', $recordId)->get();
echo "ActivityLog count: " . count($logs) . "\n";
foreach($logs as $log) {
    echo "Entry: " . json_encode($log->toArray(), JSON_PRETTY_PRINT) . "\n";
}

$kernel->terminate($request, $response);
