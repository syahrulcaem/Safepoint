<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = Unit::all();

        if ($units->isEmpty()) {
            $this->command->warn('No units found. Please run UnitsSeeder first.');
            return;
        }

        $petugas = [
            [
                'name' => 'Ahmad Sujono',
                'email' => 'ahmad.polisi1@safepoint.com',
                'phone' => '082234567890',
                'password' => Hash::make('password123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->where('type', 'POLISI')->first()?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Budi Setiawan',
                'email' => 'budi.polisi2@safepoint.com',
                'phone' => '082234567891',
                'password' => Hash::make('password123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->where('type', 'POLISI')->skip(1)->first()?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Candra Wijaya',
                'email' => 'candra.damkar1@safepoint.com',
                'phone' => '082234567892',
                'password' => Hash::make('password123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->where('type', 'DAMKAR')->first()?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dewi Kartika',
                'email' => 'dewi.ambulance1@safepoint.com',
                'phone' => '082234567893',
                'password' => Hash::make('password123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->where('type', 'AMBULANCE')->first()?->id,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko.damkar2@safepoint.com',
                'phone' => '082234567894',
                'password' => Hash::make('password123'),
                'role' => 'PETUGAS',
                'unit_id' => $units->where('type', 'DAMKAR')->skip(1)->first()?->id,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($petugas as $petugasData) {
            if ($petugasData['unit_id']) {
                User::create($petugasData);
            }
        }

        $this->command->info('Created ' . count($petugas) . ' petugas users');
    }
}
