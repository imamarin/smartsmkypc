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
        Schema::create('fnc_detail_non_spps', function (Blueprint $table) {
            $table->id();
            $table->integer('bayar');
            $table->foreignId('idnonspp')->references('id')->on('fnc_non_spps');
            $table->foreignId('iduser')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_non_spps');
    }
};
