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
        Schema::create('matpel_pengampus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matpel', 20);
            $table->string('kode_guru', 20);
            $table->foreign('kode_matpel')->references('kode_matpel')->on('matpels');
            $table->foreign('kode_guru')->references('kode_guru')->on('gurus');
            $table->foreignId('idtahunajaran')->references('id')->on('tahun_ajarans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matpel_pengampus');
    }
};
