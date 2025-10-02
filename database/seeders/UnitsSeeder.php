<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'Unit Polisi Sektor 1',
                'type' => 'POLISI',
                'is_active' => true,
            ],
            [
                'name' => 'Unit Polisi Sektor 2',
                'type' => 'POLISI',
                'is_active' => true,
            ],
            [
                'name' => 'Pemadam Kebakaran Pusat',
                'type' => 'DAMKAR',
                'is_active' => true,
            ],
            [
                'name' => 'Pemadam Kebakaran Utara',
                'type' => 'DAMKAR',
                'is_active' => true,
            ],
            [
                'name' => 'Ambulance Unit 1',
                'type' => 'AMBULANCE',
                'is_active' => true,
            ],
            [
                'name' => 'Ambulance Unit 2',
                'type' => 'AMBULANCE',
                'is_active' => true,
            ],
            [
                'name' => 'Unit Polisi Cadangan',
                'type' => 'POLISI',
                'is_active' => false,
            ],
        ];

        foreach ($units as $unitData) {
            Unit::create($unitData);
        }
    }
}
