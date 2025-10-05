<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitsWithPimpinan = [
            [
                'unit' => [
                    'name' => 'Unit Polisi Sektor 1',
                    'type' => 'POLISI',
                    'is_active' => true,
                ],
                'pimpinan' => [
                    'name' => 'Kompol Budi Santoso',
                    'email' => 'pimpinan.polisi1@safepoint.id',
                    'phone' => '081234567801',
                    'password' => Hash::make('password123'),
                    'role' => 'PIMPINAN',
                ],
                'petugas' => [
                    [
                        'name' => 'Bripka Ahmad',
                        'email' => 'ahmad.polisi1@safepoint.id',
                        'phone' => '081234567811',
                    ],
                    [
                        'name' => 'Bripka Deni',
                        'email' => 'deni.polisi1@safepoint.id',
                        'phone' => '081234567812',
                    ],
                ],
            ],
            [
                'unit' => [
                    'name' => 'Unit Polisi Sektor 2',
                    'type' => 'POLISI',
                    'is_active' => true,
                ],
                'pimpinan' => [
                    'name' => 'Kompol Andi Wijaya',
                    'email' => 'pimpinan.polisi2@safepoint.id',
                    'phone' => '081234567802',
                    'password' => Hash::make('password123'),
                    'role' => 'PIMPINAN',
                ],
                'petugas' => [
                    [
                        'name' => 'Bripka Eko',
                        'email' => 'eko.polisi2@safepoint.id',
                        'phone' => '081234567821',
                    ],
                    [
                        'name' => 'Bripka Fajar',
                        'email' => 'fajar.polisi2@safepoint.id',
                        'phone' => '081234567822',
                    ],
                ],
            ],
            [
                'unit' => [
                    'name' => 'Pemadam Kebakaran Pusat',
                    'type' => 'DAMKAR',
                    'is_active' => true,
                ],
                'pimpinan' => [
                    'name' => 'Kepala Damkar Hadi Kusuma',
                    'email' => 'pimpinan.damkar1@safepoint.id',
                    'phone' => '081234567803',
                    'password' => Hash::make('password123'),
                    'role' => 'PIMPINAN',
                ],
                'petugas' => [
                    [
                        'name' => 'Fireman Agus',
                        'email' => 'agus.damkar1@safepoint.id',
                        'phone' => '081234567831',
                    ],
                    [
                        'name' => 'Fireman Bambang',
                        'email' => 'bambang.damkar1@safepoint.id',
                        'phone' => '081234567832',
                    ],
                    [
                        'name' => 'Fireman Candra',
                        'email' => 'candra.damkar1@safepoint.id',
                        'phone' => '081234567833',
                    ],
                ],
            ],
            [
                'unit' => [
                    'name' => 'Pemadam Kebakaran Utara',
                    'type' => 'DAMKAR',
                    'is_active' => true,
                ],
                'pimpinan' => [
                    'name' => 'Kepala Damkar Irwan Setiawan',
                    'email' => 'pimpinan.damkar2@safepoint.id',
                    'phone' => '081234567804',
                    'password' => Hash::make('password123'),
                    'role' => 'PIMPINAN',
                ],
                'petugas' => [
                    [
                        'name' => 'Fireman Dodo',
                        'email' => 'dodo.damkar2@safepoint.id',
                        'phone' => '081234567841',
                    ],
                    [
                        'name' => 'Fireman Edi',
                        'email' => 'edi.damkar2@safepoint.id',
                        'phone' => '081234567842',
                    ],
                ],
            ],
            [
                'unit' => [
                    'name' => 'Ambulance Unit 1',
                    'type' => 'AMBULANCE',
                    'is_active' => true,
                ],
                'pimpinan' => [
                    'name' => 'Dr. Siti Nurhaliza',
                    'email' => 'pimpinan.ambulance1@safepoint.id',
                    'phone' => '081234567805',
                    'password' => Hash::make('password123'),
                    'role' => 'PIMPINAN',
                ],
                'petugas' => [
                    [
                        'name' => 'Paramedis Fikri',
                        'email' => 'fikri.ambulance1@safepoint.id',
                        'phone' => '081234567851',
                    ],
                    [
                        'name' => 'Paramedis Gita',
                        'email' => 'gita.ambulance1@safepoint.id',
                        'phone' => '081234567852',
                    ],
                ],
            ],
            [
                'unit' => [
                    'name' => 'Ambulance Unit 2',
                    'type' => 'AMBULANCE',
                    'is_active' => true,
                ],
                'pimpinan' => [
                    'name' => 'Dr. Bambang Surya',
                    'email' => 'pimpinan.ambulance2@safepoint.id',
                    'phone' => '081234567806',
                    'password' => Hash::make('password123'),
                    'role' => 'PIMPINAN',
                ],
                'petugas' => [
                    [
                        'name' => 'Paramedis Hana',
                        'email' => 'hana.ambulance2@safepoint.id',
                        'phone' => '081234567861',
                    ],
                    [
                        'name' => 'Paramedis Indra',
                        'email' => 'indra.ambulance2@safepoint.id',
                        'phone' => '081234567862',
                    ],
                ],
            ],
        ];

        foreach ($unitsWithPimpinan as $data) {
            // Create the unit first (without pimpinan_id)
            $unit = Unit::create($data['unit']);

            // Create pimpinan for this unit
            $pimpinan = User::create(array_merge(
                $data['pimpinan'],
                [
                    'unit_id' => $unit->id, // Pimpinan also belongs to the unit
                    'email_verified_at' => now(),
                    'duty_status' => 'ON_DUTY',
                ]
            ));

            // Update unit with pimpinan_id
            $unit->update(['pimpinan_id' => $pimpinan->id]);

            // Create petugas for this unit
            foreach ($data['petugas'] as $petugasData) {
                User::create(array_merge(
                    $petugasData,
                    [
                        'password' => Hash::make('password123'),
                        'role' => 'PETUGAS',
                        'unit_id' => $unit->id,
                        'email_verified_at' => now(),
                        'duty_status' => 'ON_DUTY',
                    ]
                ));
            }
        }

        // Create an inactive unit without pimpinan
        Unit::create([
            'name' => 'Unit Polisi Cadangan',
            'type' => 'POLISI',
            'is_active' => false,
        ]);
    }
}
