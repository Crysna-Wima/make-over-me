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
        Schema::create('galeri_penjual', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyedia_jasa_mua_id');
            $table->string('foto');
            $table->string('deskripsi');
            $table->timestamps();

            $table->foreign('penyedia_jasa_mua_id')->references('id')->on('penyedia_jasa_mua')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri_penjual');
    }
};
