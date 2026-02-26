<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateRecordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posting a new IP asset should redirect to the staff records route
     * and show the regular records blade (not the guest view).
     */
    public function test_store_redirects_to_staff_records()
    {
        // prepare some minimal valid payload
        $payload = [
            'title'      => 'Unique Title ' . Str::random(8),
            'type'       => 'Patent',
            'status'     => 'Recently Filed',
            'campus'     => 'Lipa',
            'registered' => now()->toDateString(),
            'ipophl_id'  => 'IP123',
            'gdrive_link'=> 'https://example.com',
            'remarks'    => 'testing',
            'inventors'  => json_encode(['Alice', 'Bob']),
        ];

        $response = $this->post('/ipassets', $payload);

        // ensure redirect to named route 'records.staff'
        $response->assertRedirect(route('records.staff'));

        // follow the redirect and assert we land on the records blade by
        // checking that the response does NOT contain the guest copy text
        $follow = $this->get(route('records.staff'));
        $follow->assertStatus(200)
               ->assertSee('KTTM Records') // heading from the staff view
               ->assertDontSee('Welcome to the public IP records portal');

        // record should exist in database
        $this->assertDatabaseHas('ip_records', ['ip_title' => $payload['title']]);
    }
}
