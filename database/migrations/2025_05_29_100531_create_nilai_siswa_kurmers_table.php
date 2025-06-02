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
        Schema::create('nilai_siswa_kurmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idnilaisiswa')->references('id')->on('nilai_siswas');
            $table->foreignId('idtujuanpembelajaran')->references('id')->on('tujuan_pembelajarans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_siswa_kurmers');
    }
};
