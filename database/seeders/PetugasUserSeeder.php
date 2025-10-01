<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PetugasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have units first
        $units = Unit::all();
        if ($units->isEmpty()) {
            $this->command->error('No units found. Please run the Unit seeder first.');
            return;
        }

        // Create test petugas users
        $petugasUsers = [
            [
                'name' => 'Petugas 1',
                'email' => 'petugas1@safepoint.id',
                'phone' => '081234567801',
                'password' => Hash::make('password123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->first()->id,
                'duty_status' => 'OFF_DUTY',
            ],
            [
                'name' => 'Petugas 2',
                'email' => 'petugas2@safepoint.id',
                'phone' => '081234567802',
                'password' => Hash::make('password123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->count() > 1 ? $units->skip(1)->first()->id : $units->first()->id,
                'duty_status' => 'OFF_DUTY',
            ],
            [
                'name' => 'Petugas Field Test',
                'email' => 'petugas.test@safepoint.id',
                'phone' => '081234567803',
                'password' => Hash::make('test123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->first()->id,
                'duty_status' => 'ON_DUTY',
                'duty_started_at' => now()->subHours(2),
                'last_latitude' => -6.2088,
                'last_longitude' => 106.8456,
                'last_location_update' => now()->subMinutes(10),
                'last_activity_at' => now()->subMinutes(5),
            ]
        ];

        foreach ($petugasUsers as $userData) {
            $existingUser = User::where('email', $userData['email'])->first();

            if (!$existingUser) {
                User::create($userData);
                $this->command->info("Created petugas user: {$userData['email']}");
            } else {
                // Update existing user with new data
                $existingUser->update($userData);
                $this->command->info("Updated petugas user: {$userData['email']}");
            }
        }

        $this->command->info('Petugas users seeder completed!');
        $this->command->info('Test credentials:');
        $this->command->info('Email: petugas.test@safepoint.id');
        $this->command->info('Password: test123');
    }
}
