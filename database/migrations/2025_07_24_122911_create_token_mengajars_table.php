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
        Schema::create('token_mengajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idjadwalmengajar')->references('id')->on('jadwal_mengajars');
            $table->string('token');
            $table->dateTime('expired_at');
            $table->enum('status', ['aktif', 'kadaluarsa']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_mengajars');
    }
};
