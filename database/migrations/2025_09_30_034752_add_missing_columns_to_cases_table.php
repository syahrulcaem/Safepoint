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
            $table->string('short_id', 20)->nullable()->after('id');
            $table->text('description')->nullable()->after('category');
            $table->string('location', 255)->nullable()->after('description');
        });

        // Update existing records to have short_id
        DB::table('cases')->whereNull('short_id')->update([
            'short_id' => DB::raw("CONCAT('SP', UPPER(SUBSTRING(REPLACE(UUID(), '-', ''), 1, 8)))")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn(['short_id', 'description', 'location']);
        });
    }
};
