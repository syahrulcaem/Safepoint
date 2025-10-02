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
        Schema::table('cases', function (Blueprint $table) {
            $table->string('assigned_petugas_id', 26)->nullable()->after('assigned_unit_id');
            $table->foreign('assigned_petugas_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['assigned_unit_id', 'assigned_petugas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropForeign(['assigned_petugas_id']);
            $table->dropIndex(['assigned_unit_id', 'assigned_petugas_id']);
            $table->dropColumn('assigned_petugas_id');
        });
    }
};
