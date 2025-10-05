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
        Schema::create('case_dispatches', function (Blueprint $table) {
            $table->id();
            $table->char('case_id', 26); // ULID from cases table
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('assigned_petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dispatcher_id')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('dispatched_at')->useCurrent();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            // Foreign key for case_id (ULID)
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');

            // Indexes for better query performance
            $table->index('case_id');
            $table->index('unit_id');
            $table->index('assigned_petugas_id');
            $table->index('dispatcher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_dispatches');
    }
};
