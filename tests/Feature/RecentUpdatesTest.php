<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecentUpdatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ensure the recent-updates API returns activity entries with a log_id
     * and that the deletion endpoint removes the corresponding record.
     */
    public function test_recent_updates_include_log_id_and_can_be_deleted()
    {
        // seed the database with a log entry
        $log = ActivityLog::create([
            'record_id'    => 'TEST123',
            'record_title' => 'Test Title',
            'action'       => 'Modified',
            'changes'      => ['field' => 'value'],
            'user_name'    => 'Tester',
        ]);

        $response = $this->getJson('/api/recent-updates');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'updates' => [[
                         'log_id',
                         'record_id',
                         'record_title',
                         'action',
                         'timestamp'
                     ]]
                 ])
                 ->assertJsonFragment(['log_id' => $log->id]);

        // perform deletion
        $delete = $this->postJson('/api/recent-updates/delete', ['log_id' => $log->id]);
        $delete->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseMissing('activity_logs', ['id' => $log->id]);
    }

    public function test_delete_endpoint_returns_error_on_missing_id()
    {
        $resp = $this->postJson('/api/recent-updates/delete', []);
        $resp->assertStatus(400)->assertJson(['success' => false]);
    }
}
