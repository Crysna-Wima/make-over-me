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
        Schema::create('jasa_mua_kategori', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyedia_jasa_mua_id')->nullable();
            $table->unsignedBigInteger('kategori_layanan_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('penyedia_jasa_mua_id')->references('id')->on('penyedia_jasa_mua');
            $table->foreign('kategori_layanan_id')->references('id')->on('kategori_layanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jasa_mua_kategori');
    }
};
