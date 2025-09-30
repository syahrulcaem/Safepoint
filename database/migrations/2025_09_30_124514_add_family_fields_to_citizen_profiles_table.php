<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('citizen_profiles', function (Blueprint $table) {
            $table->string('nomor_keluarga', 50)->nullable()->after('nik');
            $table->enum('hubungan', [
                'KEPALA_KELUARGA',
                'ISTRI',
                'SUAMI',
                'ANAK',
                'AYAH',
                'IBU',
                'KAKEK',
                'NENEK',
                'CUCU',
                'SAUDARA',
                'LAINNYA'
            ])->nullable()->after('nomor_keluarga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_profiles', function (Blueprint $table) {
            $table->dropColumn(['nomor_keluarga', 'hubungan']);
        });
    }
};
