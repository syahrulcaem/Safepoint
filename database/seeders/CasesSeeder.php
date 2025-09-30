<?php

namespace Database\Seeders;

use App\Models\Cases;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CasesSeeder extends Seeder
{
    public function run()
    {
        // Get a user to assign as reporter (or create one if none exists)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'id' => Str::ulid(),
                'name' => 'Test Reporter',
                'email' => 'reporter@test.com',
                'password' => bcrypt('password'),
                'role' => 'WARGA',
                'phone' => '081234567890'
            ]);
        }

        // Sample emergency cases around Jakarta
        $cases = [
            [
                'category' => 'KEBAKARAN',
                'description' => 'Kebakaran rumah di Jl. Sudirman',
                'location' => 'Jl. Jend. Sudirman No. 123, Jakarta Pusat',
                'lat' => -6.208763,
                'lon' => 106.845599,
                'status' => 'NEW',
                'phone' => '081234567890'
            ],
            [
                'category' => 'KECELAKAAN',
                'description' => 'Kecelakaan motor vs mobil',
                'location' => 'Jl. Thamrin, Jakarta Pusat',
                'lat' => -6.195396,
                'lon' => 106.822754,
                'status' => 'VERIFIED',
                'phone' => '081234567891'
            ],
            [
                'category' => 'BANJIR',
                'description' => 'Banjir di perumahan',
                'location' => 'Perumahan Kelapa Gading, Jakarta Utara',
                'lat' => -6.162459,
                'lon' => 106.909103,
                'status' => 'DISPATCHED',
                'phone' => '081234567892'
            ],
            [
                'category' => 'KEBOCORAN_GAS',
                'description' => 'Kebocoran gas LPG di warung',
                'location' => 'Jl. Mangga Besar, Jakarta Barat',
                'lat' => -6.146935,
                'lon' => 106.813423,
                'status' => 'ON_THE_WAY',
                'phone' => '081234567893'
            ],
            [
                'category' => 'POHON_TUMBANG',
                'description' => 'Pohon tumbang menutupi jalan',
                'location' => 'Jl. Raya Bogor, Jakarta Timur',
                'lat' => -6.314992,
                'lon' => 106.858238,
                'status' => 'ON_SCENE',
                'phone' => '081234567894'
            ],
            [
                'category' => 'KEBAKARAN',
                'description' => 'Kebakaran kantor',
                'location' => 'Gedung BCA Tower, Jakarta Pusat',
                'lat' => -6.224588,
                'lon' => 106.800713,
                'status' => 'CLOSED',
                'phone' => '081234567895'
            ],
            [
                'category' => 'KECELAKAAN',
                'description' => 'Kecelakaan bus TransJakarta',
                'location' => 'Halte Blok M, Jakarta Selatan',
                'lat' => -6.244851,
                'lon' => 106.798676,
                'status' => 'CANCELLED',
                'phone' => '081234567896'
            ],
            [
                'category' => 'BENCANA_ALAM',
                'description' => 'Tanah longsor kecil',
                'location' => 'Jl. Puncak, Bogor (masuk Jakarta)',
                'lat' => -6.594440,
                'lon' => 106.798676,
                'status' => 'NEW',
                'phone' => '081234567897'
            ]
        ];

        foreach ($cases as $caseData) {
            Cases::create([
                'id' => Str::ulid(),
                'short_id' => 'SP' . strtoupper(Str::random(8)),
                'reporter_user_id' => $user->id,
                'viewer_token_hash' => hash('sha256', Str::random(64)),
                'category' => $caseData['category'],
                'description' => $caseData['description'],
                'location' => $caseData['location'],
                'lat' => $caseData['lat'],
                'lon' => $caseData['lon'],
                'status' => $caseData['status'],
                'phone' => $caseData['phone'],
                'locator_text' => $caseData['location']
            ]);
        }
    }
}