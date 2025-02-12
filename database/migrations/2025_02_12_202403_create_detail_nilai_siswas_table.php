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
        Schema::create('detail_nilai_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20);
            $table->foreign('nisn')->references('nisn')->on('siswas');
            $table->integer('nilai');
            $table->foreignId('idnilaisiswa')->references('id')->on('detail_nilai_siswas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_nilai_siswas');
    }
};
