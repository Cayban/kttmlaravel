<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Verify that statistics appear and respond to month/year filters.
     */
    public function test_stats_filtering_by_month_and_year(): void
    {
        // create a few records with different categories and dates
        \App\Models\IpRecord::create([
            'record_id' => 'KTTM-001',
            'ip_title' => 'Foo Patent',
            'category' => 'Patent',
            'date_registered_deposited' => '2025-03-15',
        ]);
        \App\Models\IpRecord::create([
            'record_id' => 'KTTM-002',
            'ip_title' => 'Bar Copyright',
            'category' => 'Copyright',
            'date_registered_deposited' => '2025-03-20',
        ]);
        \App\Models\IpRecord::create([
            'record_id' => 'KTTM-003',
            'ip_title' => 'Baz Utility',
            'category' => 'Utility Model',
            'date_registered_deposited' => '2025-04-05',
        ]);

        // request without filters should show total 3
        $res1 = $this->get('/');
        $res1->assertSee('Total Records');
        $res1->assertSee('3');

        // filter by March 2025 should only count first two
        $res2 = $this->get('/?year=2025&month=3');
        $res2->assertSee('Total Records');
        $res2->assertSee('2');
        $res2->assertDontSee('3');
    }
}
