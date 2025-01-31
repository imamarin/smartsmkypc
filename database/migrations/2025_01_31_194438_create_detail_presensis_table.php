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
        Schema::create('detail_presensis', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20);
            $table->enum('keterangan', ['h', 's', 'i', 'a']);
            $table->foreign('nisn')->references('nisn')->on('siswas');
            $table->foreignId('idpresensi')->references('id')->on('presensis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_presensis');
    }
};
