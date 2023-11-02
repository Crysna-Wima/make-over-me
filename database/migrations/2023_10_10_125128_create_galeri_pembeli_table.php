<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaleriPembeliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galeri_pembeli', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pencari_jasa_mua_id')->nullable();
            $table->unsignedBigInteger('pemesanan_id')->nullable();
            $table->string('foto');
            $table->string('deskripsi');
            $table->timestamps();

            // Foreign keys
            $table->foreign('pencari_jasa_mua_id')->references('id')->on('pencari_jasa_mua');
            $table->foreign('pemesanan_id')->references('id')->on('pemesanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galeri_pembeli');
    }
}
