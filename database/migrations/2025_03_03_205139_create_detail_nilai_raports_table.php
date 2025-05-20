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
        Schema::create('rpt_detail_nilai_raports', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20);
            $table->integer('nilai_1');
            $table->integer('nilai_2');
            $table->foreignId('idnilairaport')->references('id')->on('rpt_nilai_raports');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpt_detail_nilai_raports');
    }
};
