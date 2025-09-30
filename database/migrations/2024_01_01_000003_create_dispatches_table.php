<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispatches', function (Blueprint $table) {
            $table->id();
            $table->char('case_id', 26);
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('assigned_by');
            $table->text('notes')->nullable();
            $table->timestamp('ack_at')->nullable();
            $table->timestamps();

            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('assigned_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
