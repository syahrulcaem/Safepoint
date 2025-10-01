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
        Schema::table('users', function (Blueprint $table) {
            // Add unit_id if it doesn't exist
            if (!Schema::hasColumn('users', 'unit_id')) {
                $table->string('unit_id', 26)->nullable()->after('role');
                $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            }

            // Add location tracking fields
            $table->decimal('last_latitude', 10, 8)->nullable()->after('unit_id');
            $table->decimal('last_longitude', 11, 8)->nullable()->after('last_latitude');
            $table->timestamp('last_location_update')->nullable()->after('last_longitude');
            $table->string('duty_status')->default('OFF_DUTY')->after('last_location_update');
            $table->timestamp('duty_started_at')->nullable()->after('duty_status');
            $table->timestamp('last_activity_at')->nullable()->after('duty_started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_latitude',
                'last_longitude',
                'last_location_update',
                'duty_status',
                'duty_started_at',
                'last_activity_at'
            ]);

            // Only drop unit_id if it exists and this migration created it
            if (Schema::hasColumn('users', 'unit_id')) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
            }
        });
    }
};
