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
        Schema::create('jadwal_mengajars', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matpel', 20);
            $table->string('kode_guru', 20);
            $table->foreign('kode_matpel')->references('kode_matpel')->on('matpels');
            $table->foreign('kode_guru')->references('kode_guru')->on('gurus');
            $table->foreignId('idkelas')->references('id')->on('kelas');
            $table->foreignId('idjampel')->references('id')->on('jam_pelajarans');
            $table->foreignId('idsistemblok')->references('id')->on('sistem_bloks');
            $table->integer('jumlah_jam');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_mengajars');
    }
};
