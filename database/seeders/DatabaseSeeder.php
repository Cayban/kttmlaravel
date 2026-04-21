<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\IpRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create test IP records
        IpRecord::create([
            'record_id' => 'PATENT-2026-001',
            'ip_title' => 'Test Patent Application - Lorem Ipsum Dolor',
            'category' => 'Patent',
            'owner_inventor_summary' => 'Dr. Juan dela Cruz',
            'campus' => 'Los Baños',
            'status' => 'For Filing',
            'ipophl_id' => 'IPOPHL-001-2026',
            'gdrive_link' => 'https://drive.google.com/file/d/test123',
            'remarks' => 'This is a test patent record for testing the change timeline system.',
            'date_registered_deposited' => now(),
        ]);

        IpRecord::create([
            'record_id' => 'COPYRIGHT-2026-001',
            'ip_title' => 'Test Copyright Registration - Sample Work',
            'category' => 'Copyright',
            'owner_inventor_summary' => 'Maria Santos',
            'campus' => 'Diliman',
            'status' => 'Registered',
            'ipophl_id' => 'IPOPHL-002-2026',
            'gdrive_link' => 'https://drive.google.com/file/d/test456',
            'remarks' => 'Original literary work - fictional narrative.',
            'date_registered_deposited' => now(),
        ]);
    }
}
