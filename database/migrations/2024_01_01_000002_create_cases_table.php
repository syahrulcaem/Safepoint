<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->char('id', 26)->primary(); // ULID
            $table->unsignedBigInteger('reporter_user_id')->nullable();
            $table->char('device_id', 36)->nullable();
            $table->string('viewer_token_hash', 128);
            $table->string('phone', 32)->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lon', 10, 7);
            $table->smallInteger('accuracy')->nullable();
            $table->string('locator_text', 64);
            $table->enum('locator_provider', ['w3w', 'pluscode'])->default('pluscode');
            $table->string('category', 20)->default('UMUM');
            $table->enum('status', ['NEW', 'VERIFIED', 'DISPATCHED', 'ON_THE_WAY', 'ON_SCENE', 'CLOSED', 'CANCELLED'])->default('NEW');
            $table->unsignedBigInteger('assigned_unit_id')->nullable();
            $table->json('contacts_snapshot')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('on_scene_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('reporter_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_unit_id')->references('id')->on('units')->onDelete('set null');

            $table->index(['status']);
            $table->index(['assigned_unit_id', 'status']);
            $table->index(['created_at']);
            $table->index(['locator_text']);
            $table->index(['reporter_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
