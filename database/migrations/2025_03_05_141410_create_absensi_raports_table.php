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
        Schema::create('rpt_absensi_raports', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20);
            $table->foreign('nisn')->references('nisn')->on('siswas');
            $table->integer('izin');
            $table->integer('sakit');
            $table->integer('alfa');
            $table->string('semester');
            $table->foreignId('idtahunajaran')->references('id')->on('tahun_ajarans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpt_absensi_raports');
    }
};
