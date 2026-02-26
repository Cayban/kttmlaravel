<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Test ActivityLog query
$recordId = 'PATENT-2026-001';
echo "Testing ActivityLog for record: $recordId\n";
echo "=".str_repeat("=", 50)."\n";

$logs = \App\Models\ActivityLog::where('record_id', $recordId)->get();
echo "ActivityLog count: " . count($logs) . "\n";
echo "ActivityLog collection: " . json_encode($logs->toArray(), JSON_PRETTY_PRINT) . "\n";

// Test IpRecord query
echo "\nTesting IpRecord query\n";
echo "=".str_repeat("=", 50)."\n";
$record = \App\Models\IpRecord::whereRaw('TRIM(record_id) = ?', [$recordId])->first();
if ($record) {
    echo "Record found: " . json_encode($record->toArray(), JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Record not found\n";
}

$kernel->terminate($request, $response);
