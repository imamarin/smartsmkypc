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
        Schema::create('siswas', function (Blueprint $table) {
            $table->string('nisn', 20)->primary();
            $table->string('nis');
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nik')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('no_hp_siswa')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('no_hp_ortu')->nullable();
            $table->text('alamat_ortu')->nullable();
            $table->text('alamat_siswa')->nullable();
            $table->string('diterima_tanggal')->nullable();
            $table->integer('status')->default(1);
            $table->foreignId('idtahunajaran')->references('id')->on('tahun_ajarans');
            $table->foreignId('iduser')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
