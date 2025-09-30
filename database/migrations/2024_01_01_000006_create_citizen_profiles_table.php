<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citizen_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->string('nik', 32)->unique();
            $table->string('ktp_image_url')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('blood_type', ['A', 'B', 'AB', 'O', 'UNKNOWN'])->default('UNKNOWN');
            $table->text('chronic_conditions')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizen_profiles');
    }
};
