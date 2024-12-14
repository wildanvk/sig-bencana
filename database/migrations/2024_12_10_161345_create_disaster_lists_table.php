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
        Schema::create('disaster_lists', function (Blueprint $table) {
            $table->string('kode')->primary(); // Custom ID sebagai primary key
            $table->date('tanggal_kejadian');
            // Relasi ke tabel jenis bencana
            $table->string('kode_jenis_bencana');
            $table->foreign('kode_jenis_bencana') // Menentukan foreign key
                ->references('kode') // Kolom yang dirujuk di tabel disaster_types
                ->on('disaster_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Relasi ke tabel kecamatan
            $table->string('kode_kecamatan');
            $table->foreign('kode_kecamatan') // Menentukan foreign key
                ->references('kode') // Kolom yang dirujuk di tabel subdistricts
                ->on('subdistricts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('desa')->nullable();
            $table->string('penyebab')->nullable();
            $table->text('dampak')->nullable();
            $table->integer('kk')->nullable();
            $table->integer('jiwa')->nullable();
            $table->integer('sakit')->nullable();
            $table->integer('hilang')->nullable();
            $table->integer('meninggal')->nullable();
            $table->decimal('nilai_kerusakan', 15, 2)->nullable();
            $table->text('upaya')->nullable();
            $table->string('foto')->nullable();
            $table->string('latitude')->nullable(); // Kolom untuk lokasi berbasis latitude
            $table->string('longitude')->nullable(); // Kolom untuk lokasi berbasis longitude
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disaster_lists');
    }
};
