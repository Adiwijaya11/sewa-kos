<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Facilities (Filters)
        $facilitiesData = [
            ['name' => 'WiFi Cepat', 'icon' => 'wifi'],
            ['name' => 'AC Hemat Energi', 'icon' => 'wind'],
            ['name' => 'Kamar Mandi Dalam', 'icon' => 'bath'],
            ['name' => 'Kasur Premium', 'icon' => 'bed'],
            ['name' => 'Lemari Pakaian', 'icon' => 'folder'],
            ['name' => 'Parkir Motor Luas', 'icon' => 'motorcycle'],
            ['name' => 'Dapur Bersama', 'icon' => 'coffee'],
            ['name' => 'CCTV & Keamanan 24 Jam', 'icon' => 'shield'],
            ['name' => 'Water Heater / Air Hangat', 'icon' => 'droplet'],
            ['name' => 'Parkir Mobil Aman', 'icon' => 'car'],
            ['name' => 'Mesin Cuci & Laundry', 'icon' => 'soap'],
            ['name' => 'Smart TV / TV Kabel', 'icon' => 'tv'],
            ['name' => 'Kulkas Kamar', 'icon' => 'snowflake'],
            ['name' => 'Balkon Pribadi', 'icon' => 'door-open'],
            ['name' => 'Meja & Kursi Kerja', 'icon' => 'chair'],
            ['name' => 'Akses Kunci 24 Jam', 'icon' => 'key'],
            ['name' => 'Cleaning Service', 'icon' => 'broom'],
            ['name' => 'Boleh Bawa Hewan (Pet Friendly)', 'icon' => 'paw'],
        ];

        foreach ($facilitiesData as $item) {
            Facility::create($item);
        }

        // 2. Create Default Production-ready Admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@kosinaja.com',
            'phone' => '083114592416',
            'role' => 'admin',
            'is_verified' => true,
            'password' => Hash::make('adminkos24'),
        ]);

        // 3. Seed Default System Policies and Filters (Settings)
        Setting::set('platform_commission_fee', 5);
        Setting::set('max_pending_bookings', 1);
        Setting::set('max_daily_failures', 3);
        Setting::set('max_total_failures', 10);
        Setting::set('midtrans_sandbox_mode', 1);
        Setting::set('gender_types', json_encode(['putra', 'putri', 'campur']));
    }
}