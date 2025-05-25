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
        Schema::create('rpt_nilai_prakerins', function (Blueprint $table) {
            $table->id();
            $table->string('nisn');
            $table->foreign('nisn')->references('nisn')->on('siswas');
            $table->string('dudi');
            $table->string('alamat');
            $table->integer('waktu');
            $table->integer('nilai');
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
        Schema::dropIfExists('nilai_prakerins');
    }
};
