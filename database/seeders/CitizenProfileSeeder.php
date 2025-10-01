<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CitizenProfile;
use Illuminate\Database\Seeder;

class CitizenProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample citizen users with profiles
        $familyMembers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone' => '081345567890',
                'nik' => '3271010101850001',
                'whatsapp_keluarga' => '081223344556', // WhatsApp keluarga untuk komunikasi
                'hubungan' => 'KEPALA_KELUARGA',
                'birth_date' => '1985-01-01',
                'blood_type' => 'A'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'phone' => '081345567891',
                'nik' => '3271010101870002',
                'whatsapp_keluarga' => '081223344556', // WhatsApp keluarga sama (satu keluarga)
                'hubungan' => 'ISTRI',
                'birth_date' => '1987-01-01',
                'blood_type' => 'B'
            ],
            [
                'name' => 'Andi Santoso',
                'email' => 'andi.santoso@example.com',
                'phone' => '081345567892',
                'nik' => '3271010101100003',
                'whatsapp_keluarga' => '081223344556', // WhatsApp keluarga sama (satu keluarga)
                'hubungan' => 'ANAK',
                'birth_date' => '2010-01-01',
                'blood_type' => 'A'
            ],
            [
                'name' => 'Ahmad Rahman',
                'email' => 'ahmad.rahman@example.com',
                'phone' => '081345567893',
                'nik' => '3271010101800004',
                'whatsapp_keluarga' => '081556677889', // WhatsApp keluarga berbeda (keluarga lain)
                'hubungan' => 'KEPALA_KELUARGA',
                'birth_date' => '1980-01-01',
                'blood_type' => 'O'
            ],
            [
                'name' => 'Fatimah Ahmad',
                'email' => 'fatimah.ahmad@example.com',
                'phone' => '081345567894',
                'nik' => '3271010101820005',
                'whatsapp_keluarga' => '081556677889', // WhatsApp keluarga sama (satu keluarga)
                'hubungan' => 'ISTRI',
                'birth_date' => '1982-01-01',
                'blood_type' => 'AB'
            ]
        ];

        foreach ($familyMembers as $member) {
            $user = User::create([
                'name' => $member['name'],
                'email' => $member['email'],
                'phone' => $member['phone'],
                'password' => bcrypt('password123'),
                'role' => 'WARGA',
                'email_verified_at' => now(),
            ]);

            CitizenProfile::create([
                'user_id' => $user->id,
                'nik' => $member['nik'],
                'whatsapp_keluarga' => $member['whatsapp_keluarga'],
                'hubungan' => $member['hubungan'],
                'birth_date' => $member['birth_date'],
                'blood_type' => $member['blood_type'],
            ]);
        }
    }
}
