<?php

namespace Database\Seeders;

use App\Models\CitizenProfile;
use Illuminate\Database\Seeder;

class UpdateWhatsappKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing citizen profiles with WhatsApp family numbers
        $updates = [
            'KK001' => '081223344556', // WhatsApp keluarga untuk KK001
            'KK002' => '081556677889', // WhatsApp keluarga untuk KK002
        ];

        foreach ($updates as $nomor_kk => $whatsapp_keluarga) {
            CitizenProfile::where('nomor_keluarga', $nomor_kk)
                ->update(['whatsapp_keluarga' => $whatsapp_keluarga]);
        }

        $this->command->info('WhatsApp keluarga berhasil ditambahkan ke citizen profiles.');
    }
}
