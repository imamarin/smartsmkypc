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
        Schema::create('rpt_kategori_sikaps', function (Blueprint $table) {
            $table->id();
            $table->string('sikap', 20);
            $table->enum('kategori', ['sosial', 'spiritual']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rpt_kategori_sikaps');
    }
};
