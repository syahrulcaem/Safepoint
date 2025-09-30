<?php

namespace Database\Seeders;

use App\Models\Cases;
use App\Models\CaseEvent;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleCaseSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $operators = User::where('role', 'OPERATOR')->get();

        if ($units->isEmpty() || $operators->isEmpty()) {
            $this->command->warn('Please ensure units and operators exist before seeding cases.');
            return;
        }

        // Sample case 1 - New case
        $case1 = Cases::create([
            'id' => Str::ulid(),
            'viewer_token_hash' => hash('sha256', Str::random(32)),
            'phone' => '+628123456789',
            'lat' => -6.200000,
            'lon' => 106.816666,
            'accuracy' => 10,
            'locator_text' => 'Monas, Jakarta Pusat',
            'locator_provider' => 'pluscode',
            'category' => 'MEDIS',
            'status' => 'NEW',
        ]);

        CaseEvent::create([
            'case_id' => $case1->id,
            'actor_type' => 'SYSTEM',
            'event' => 'CASE_CREATED',
            'meta' => ['source' => 'mobile_app'],
        ]);

        // Sample case 2 - Dispatched case
        $case2 = Cases::create([
            'id' => Str::ulid(),
            'viewer_token_hash' => hash('sha256', Str::random(32)),
            'phone' => '+628987654321',
            'lat' => -6.175110,
            'lon' => 106.865036,
            'accuracy' => 15,
            'locator_text' => 'Grand Indonesia, Jakarta',
            'locator_provider' => 'pluscode',
            'category' => 'KEBAKARAN',
            'status' => 'DISPATCHED',
            'assigned_unit_id' => $units->where('type', 'DAMKAR')->first()?->id,
            'verified_at' => now()->subMinutes(30),
            'dispatched_at' => now()->subMinutes(20),
        ]);

        CaseEvent::create([
            'case_id' => $case2->id,
            'actor_type' => 'SYSTEM',
            'event' => 'CASE_CREATED',
            'meta' => ['source' => 'web_report'],
            'created_at' => now()->subMinutes(30),
        ]);

        CaseEvent::create([
            'case_id' => $case2->id,
            'actor_type' => 'OPERATOR',
            'actor_id' => $operators->first()?->id,
            'event' => 'VERIFIED',
            'meta' => ['verified_by' => $operators->first()?->name],
            'created_at' => now()->subMinutes(25),
        ]);

        CaseEvent::create([
            'case_id' => $case2->id,
            'actor_type' => 'OPERATOR',
            'actor_id' => $operators->first()?->id,
            'event' => 'DISPATCHED',
            'meta' => [
                'unit_name' => $units->where('type', 'DAMKAR')->first()?->name,
                'unit_type' => 'DAMKAR',
            ],
            'created_at' => now()->subMinutes(20),
        ]);

        // Sample case 3 - Closed case
        $case3 = Cases::create([
            'id' => Str::ulid(),
            'viewer_token_hash' => hash('sha256', Str::random(32)),
            'phone' => '+628111222333',
            'lat' => -6.208763,
            'lon' => 106.845599,
            'accuracy' => 5,
            'locator_text' => 'Bundaran HI, Jakarta',
            'locator_provider' => 'w3w',
            'category' => 'KEAMANAN',
            'status' => 'CLOSED',
            'assigned_unit_id' => $units->where('type', 'POLISI')->first()?->id,
            'verified_at' => now()->subHours(2),
            'dispatched_at' => now()->subHours(1)->subMinutes(45),
            'on_scene_at' => now()->subHours(1)->subMinutes(30),
            'closed_at' => now()->subHour(),
        ]);

        CaseEvent::create([
            'case_id' => $case3->id,
            'actor_type' => 'WARGA',
            'event' => 'CASE_CREATED',
            'meta' => ['source' => 'panic_button'],
            'created_at' => now()->subHours(2),
        ]);

        CaseEvent::create([
            'case_id' => $case3->id,
            'actor_type' => 'OPERATOR',
            'actor_id' => $operators->first()?->id,
            'event' => 'VERIFIED',
            'meta' => ['verified_by' => $operators->first()?->name],
            'created_at' => now()->subHours(1)->subMinutes(50),
        ]);

        CaseEvent::create([
            'case_id' => $case3->id,
            'actor_type' => 'OPERATOR',
            'actor_id' => $operators->first()?->id,
            'event' => 'DISPATCHED',
            'meta' => [
                'unit_name' => $units->where('type', 'POLISI')->first()?->name,
                'unit_type' => 'POLISI',
            ],
            'created_at' => now()->subHours(1)->subMinutes(45),
        ]);

        CaseEvent::create([
            'case_id' => $case3->id,
            'actor_type' => 'PETUGAS',
            'event' => 'ON_SCENE',
            'meta' => ['arrived_at' => now()->subHours(1)->subMinutes(30)->toISOString()],
            'created_at' => now()->subHours(1)->subMinutes(30),
        ]);

        CaseEvent::create([
            'case_id' => $case3->id,
            'actor_type' => 'OPERATOR',
            'actor_id' => $operators->first()?->id,
            'event' => 'CLOSED',
            'meta' => ['closed_by' => $operators->first()?->name],
            'created_at' => now()->subHour(),
        ]);
    }
}
