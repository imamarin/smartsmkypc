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
        Schema::create('rpt_identitasi_raports', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->integer('nss_sekolah');
            $table->string('alamat');
            $table->string('website');
            $table->string('email');
            $table->string('kepala_sekolah');
            $table->string('nip_kepala_sekolah');
            $table->date('tanggal_terima_raport');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->foreignId('idtahunajaran')->references('id')->on('tahun_ajarans');
            $table->boolean('status_raport');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpt_identitasi_raports');
    }
};
