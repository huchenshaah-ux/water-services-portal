<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@waterservices.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $supervisor = User::create([
            'name' => 'Supervisor User',
            'email' => 'supervisor@waterservices.local',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@waterservices.local',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@waterservices.local',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        $categories = Application::SERVICE_CATEGORIES;
        $statuses = Application::STATUSES;

        for ($i = 1; $i <= 50; $i++) {
            Application::create([
                'entry_no' => 'WS-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'application_date' => now()->subDays(rand(0, 180)),
                'applicant_name' => "Applicant {$i}",
                'id_number' => 'A'.rand(100000, 999999),
                'contact_number' => '7'.rand(100000, 999999),
                'address' => "Island Address {$i}",
                'service_address' => "Service Location {$i}",
                'billing_address' => "Billing Location {$i}",
                'service_category' => $categories[array_rand($categories)],
                'status' => $statuses[array_rand($statuses)],
                'supervised_by' => rand(0, 1) ? $supervisor->id : $admin->id,
                'fenaka_id' => 'FNK-'.$i,
                'remarks' => 'Sample application record',
            ]);
        }
    }
}
