<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'operator@safepoint.id'],
            [
                'name' => 'Operator SafePoint',
                'email' => 'operator@safepoint.id',
                'password' => Hash::make('password123'),
                'role' => 'OPERATOR',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@safepoint.id'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@safepoint.id',
                'password' => Hash::make('password123'),
                'role' => 'SUPERADMIN',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@safepoint.id'],
            [
                'name' => 'Pimpinan SafePoint',
                'email' => 'pimpinan@safepoint.id',
                'password' => Hash::make('password123'),
                'role' => 'PIMPINAN',
                'email_verified_at' => now(),
            ]
        );
    }
}
