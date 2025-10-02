<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, modify the enum column to include all values
        DB::statement("ALTER TABLE units MODIFY COLUMN type ENUM('AMBULANS', 'DAMKAR', 'POLISI', 'BPBD', 'AMBULANCE')");

        // Update existing AMBULANS to AMBULANCE
        DB::table('units')->where('type', 'AMBULANS')->update(['type' => 'AMBULANCE']);

        // Now remove AMBULANS from enum
        DB::statement("ALTER TABLE units MODIFY COLUMN type ENUM('POLISI', 'DAMKAR', 'AMBULANCE', 'BPBD')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, add AMBULANS back to enum
        DB::statement("ALTER TABLE units MODIFY COLUMN type ENUM('AMBULANS', 'DAMKAR', 'POLISI', 'BPBD', 'AMBULANCE')");

        // Update AMBULANCE back to AMBULANS
        DB::table('units')->where('type', 'AMBULANCE')->update(['type' => 'AMBULANS']);

        // Remove AMBULANCE from enum
        DB::statement("ALTER TABLE units MODIFY COLUMN type ENUM('AMBULANS', 'DAMKAR', 'POLISI', 'BPBD')");
    }
};
