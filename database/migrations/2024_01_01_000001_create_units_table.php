<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['AMBULANS', 'DAMKAR', 'POLISI', 'BPBD']);
            $table->boolean('is_active')->default(true);
            $table->decimal('last_lat', 10, 7)->nullable();
            $table->decimal('last_lon', 10, 7)->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
