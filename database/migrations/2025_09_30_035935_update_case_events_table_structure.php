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
        Schema::table('case_events', function (Blueprint $table) {
            // Rename columns to match new structure
            $table->renameColumn('event', 'action');
            $table->renameColumn('meta', 'metadata');

            // Add notes column
            $table->text('notes')->nullable()->after('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_events', function (Blueprint $table) {
            $table->renameColumn('action', 'event');
            $table->renameColumn('metadata', 'meta');
            $table->dropColumn('notes');
        });
    }
};
