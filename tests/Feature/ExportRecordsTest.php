<?php

namespace Tests\Feature;

use App\Models\IpRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportRecordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_all_records_returns_csv()
    {
        IpRecord::factory()->create([
            'record_id' => 'KTTM-001',
            'ip_title' => 'Title One',
            'category' => 'Patent',
            'owner_inventor_summary' => 'Alice',
            'campus' => 'Lipa',
            'status' => 'Filed',
            'date_registered' => now()->toDateString(),
        ]);

        $response = $this->get('/records/export');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $content = $response->getContent();
        $this->assertStringContainsString('Record ID,IP Title,Category', $content);
        $this->assertStringContainsString('KTTM-001', $content);
    }

    public function test_export_date_filter_applies()
    {
        IpRecord::factory()->create([
            'record_id' => 'KTTM-002',
            'ip_title' => 'Title Two',
            'category' => 'Trademark',
            'owner_inventor_summary' => 'Bob',
            'campus' => 'Lipa',
            'status' => 'Registered',
            'date_registered' => '2025-01-01',
        ]);
        IpRecord::factory()->create([
            'record_id' => 'KTTM-003',
            'ip_title' => 'Title Three',
            'category' => 'Copyright',
            'owner_inventor_summary' => 'Carol',
            'campus' => 'Lipa',
            'status' => 'Filed',
            'date_registered' => '2026-01-01',
        ]);

        $response = $this->get('/records/export?start=2025-01-01&end=2025-01-01');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('KTTM-002', $content);
        $this->assertStringNotContainsString('KTTM-003', $content);

        // test a broader range
        $response2 = $this->get('/records/export?start=2025-01-01&end=2026-12-31');
        $content2 = $response2->getContent();
        $this->assertStringContainsString('KTTM-002', $content2);
        $this->assertStringContainsString('KTTM-003', $content2);
    }
}
