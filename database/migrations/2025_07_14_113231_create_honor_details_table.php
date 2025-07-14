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
        Schema::create('honor_details', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20);
            $table->foreign('nip')->references('nip')->on('stafs');
            $table->foreignId('idhonor')->references('id')->on('honors');
            $table->integer('jml_jam')->default(0);
            $table->integer('bonus_hdr')->default(0);
            $table->integer('yayasan')->default(0);
            $table->integer('tun_jab_bak')->default(0);
            $table->integer('tunjab')->default(0);
            $table->integer('honor')->default(0);
            $table->integer('sub_non_ser')->default(0);
            $table->integer('jml')->default(0);
            $table->integer('tabungan')->default(0);
            $table->integer('arisan')->default(0);
            $table->integer('qurban')->default(0);
            $table->integer('kas_1')->default(0);
            $table->integer('kas_2')->default(0);
            $table->integer('lainnya')->default(0);
            $table->integer('jum_tal')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('honor_details');
    }
};
