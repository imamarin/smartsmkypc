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
        Schema::create('presensi_harian_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20);
            $table->foreign('nisn')->references('nisn')->on('siswas');
            $table->enum('keterangan', ['h', 's', 'i', 'a']);
            $table->enum('semester', ['ganjil', 'genap']);
            $table->foreignId('idtahunajaran')->references('id')->on('tahun_ajarans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_harian_siswas');
    }
};
