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
        Schema::create('rpt_nilai_cps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idnilairaport')->references('id')->on('rpt_nilai_raports');
            $table->string('nisn');
            $table->foreign('nisn')->references('nisn')->on('siswas');
            $table->string('kode_cp', 20);
            $table->foreign('kode_cp')->references('kode_cp')->on('capaian_pembelajarans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpt_nilai_cps');
    }
};
