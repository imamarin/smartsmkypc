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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20);
            $table->foreign('nip')->references('nip')->on('stafs');
            $table->string('kode_matpel', 20);
            $table->foreign('kode_matpel')->references('kode_matpel')->on('matpels');
            $table->foreignId('idkelas')->references('id')->on('kelas');
            $table->string('catatan_pembelajaran');
            $table->text('pokok_bahasan');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->foreignId('idtahunajaran')->references('id')->on('tahun_ajarans');
            $table->foreignId('idjadwalmengajar')->references('id')->on('jadwal_mengajars')->onDelete('no action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
