<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::updateOrCreate(
            ['name' => 'Ambulans 01'],
            [
                'name' => 'Ambulans 01',
                'type' => 'AMBULANS',
                'is_active' => true,
            ]
        );

        Unit::updateOrCreate(
            ['name' => 'Damkar Sektor A'],
            [
                'name' => 'Damkar Sektor A',
                'type' => 'DAMKAR',
                'is_active' => true,
            ]
        );

        Unit::updateOrCreate(
            ['name' => 'Ambulans 02'],
            [
                'name' => 'Ambulans 02',
                'type' => 'AMBULANS',
                'is_active' => true,
            ]
        );

        Unit::updateOrCreate(
            ['name' => 'Polisi Sektor B'],
            [
                'name' => 'Polisi Sektor B',
                'type' => 'POLISI',
                'is_active' => true,
            ]
        );

        Unit::updateOrCreate(
            ['name' => 'BPBD Unit 1'],
            [
                'name' => 'BPBD Unit 1',
                'type' => 'BPBD',
                'is_active' => true,
            ]
        );
    }
}
