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
        Schema::create('tujuan_pembelajarans', function (Blueprint $table) {
            $table->id();
            $table->string('tujuan');
            $table->string('kode_cp', 20);
            $table->foreign('kode_cp')->references('kode_cp')->on('capaian_pembelajarans');
            $table->integer('bt1');
            $table->integer('bt2');
            $table->integer('t1');
            $table->integer('t2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_pembelajarans');
    }
};
