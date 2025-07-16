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
        Schema::create('kasus_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn');
            $table->foreign('nisn')->references('nisn')->on('siswas');
            $table->string('jenis_kasus');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_kasus')->nullable();
            $table->enum('status', ['private', 'open', 'closed', 'sp1', 'sp2', 'sp3'])->default('private');
            $table->text('penanganan')->nullable(); // tindakan yang diambil
            $table->string('dokumentasi')->nullable(); // link atau path ke dokumentasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasus_siswas');
    }
};
