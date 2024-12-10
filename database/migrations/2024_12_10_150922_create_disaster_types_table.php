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
        Schema::create('disaster_types', function (Blueprint $table) {
            $table->string('kode')->primary(); // Custom ID
            $table->string('jenis_bencana')->unique(); // Nama jenis bencana
            $table->timestamps(); // Timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disaster_types');
    }
};
