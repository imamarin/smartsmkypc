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
        Schema::create('walikelas', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20);
            $table->foreign('nip')->references('nip')->on('stafs');
            $table->foreignId('idkelas')->references('id')->on('kelas');
            $table->foreignId('idtahunajaran')->references('id')->on('tahun_ajarans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walikelas');
    }
};
