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
        Schema::create('nilai_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20);
            $table->foreign('nip')->references('nip')->on('stafs');
            $table->enum('kategori', ['tugas', 'sumatif', 'uts', 'uas']);
            $table->foreignId('idkelas')->references('id')->on('kelas');
            $table->string('kode_matpel', 20);
            $table->foreign('kode_matpel')->references('kode_matpel')->on('matpels');
            $table->date('tanggal_pelaksanaan');
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('nilai_siswas');
    }
};
