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
            $table->string('nis')->unique();
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nik')->unique();
            $table->string('asal_sekolah');
            $table->string('no_hp_siswa')->nullable();
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ayah');
            $table->string('pekerjaan_ibu');
            $table->string('no_hp_ortu')->nullable();
            $table->text('alamat_ortu');
            $table->text('alamat_siswa');
            $table->string('diterima_tanggal');
            $table->integer('status')->default(1);
            $table->string('kelas');
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
