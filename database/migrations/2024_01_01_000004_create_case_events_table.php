<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_events', function (Blueprint $table) {
            $table->id();
            $table->char('case_id', 26);
            $table->enum('actor_type', ['SYSTEM', 'WARGA', 'PETUGAS', 'OPERATOR', 'SUPERADMIN']);
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('event', 50);
            $table->json('meta')->nullable();
            $table->timestamp('created_at');

            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('actor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_events');
    }
};
