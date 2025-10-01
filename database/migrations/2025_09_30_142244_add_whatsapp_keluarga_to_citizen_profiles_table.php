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
            $table->string('whatsapp_keluarga', 20)->nullable()->after('nomor_keluarga')->comment('Nomor WhatsApp keluarga untuk komunikasi darurat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citizen_profiles', function (Blueprint $table) {
            $table->dropColumn('whatsapp_keluarga');
        });
    }
};
