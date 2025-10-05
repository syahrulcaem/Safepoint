<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UnitsSeeder::class, // Create units with pimpinan and petugas
            CitizenProfileSeeder::class,
            SampleCaseSeeder::class,
            CasesSeeder::class,
            LocationSeeder::class, // Add location data to petugas
        ]);
    }
}
