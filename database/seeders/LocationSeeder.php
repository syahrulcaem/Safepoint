<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample locations around Jakarta, Indonesia
        $locations = [
            // Central Jakarta area
            ['lat' => -6.2088, 'lon' => 106.8456],
            ['lat' => -6.1751, 'lon' => 106.8650],
            ['lat' => -6.2297, 'lon' => 106.8219],
            ['lat' => -6.1862, 'lon' => 106.8063],
            ['lat' => -6.2114, 'lon' => 106.8446],
            
            // South Jakarta area
            ['lat' => -6.2615, 'lon' => 106.7810],
            ['lat' => -6.2659, 'lon' => 106.8106],
            ['lat' => -6.3014, 'lon' => 106.8130],
            
            // North Jakarta area
            ['lat' => -6.1381, 'lon' => 106.8631],
            ['lat' => -6.1164, 'lon' => 106.8990],
            
            // East Jakarta area
            ['lat' => -6.2250, 'lon' => 106.9004],
            ['lat' => -6.2741, 'lon' => 106.8996],
            
            // West Jakarta area
            ['lat' => -6.1683, 'lon' => 106.7598],
            ['lat' => -6.2001, 'lon' => 106.7631],
        ];

        // Get all petugas users
        $petugasList = User::where('role', 'PETUGAS')->get();

        if ($petugasList->isEmpty()) {
            $this->command->warn('No petugas found! Please run UnitsSeeder first.');
            return;
        }

        $this->command->info('Adding location data to ' . $petugasList->count() . ' petugas...');

        // Assign random locations to petugas
        foreach ($petugasList as $index => $petugas) {
            // Use modulo to cycle through locations if more petugas than locations
            $location = $locations[$index % count($locations)];
            
            // Add some randomness (within ~500 meters)
            $latOffset = (rand(-50, 50) / 10000); // ~500m
            $lonOffset = (rand(-50, 50) / 10000); // ~500m
            
            $petugas->update([
                'last_latitude' => $location['lat'] + $latOffset,
                'last_longitude' => $location['lon'] + $lonOffset,
                'last_location_update' => now()->subMinutes(rand(0, 10)), // Random time in last 10 minutes
            ]);

            $this->command->info("✓ {$petugas->name}: ({$petugas->last_latitude}, {$petugas->last_longitude})");
        }

        $this->command->info('Location data seeded successfully!');
    }
}
