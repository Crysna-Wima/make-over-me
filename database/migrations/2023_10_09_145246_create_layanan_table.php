<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLayananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyedia_jasa_mua_id')->nullable();
            $table->unsignedBigInteger('jasa_mua_kategori_id')->nullable();
            $table->string('nama');
            $table->string('harga');
            $table->string('deskripsi');
            $table->timestamps();

            // Foreign keys
            $table->foreign('penyedia_jasa_mua_id')->references('id')->on('penyedia_jasa_mua');
            $table->foreign('jasa_mua_kategori_id')->references('id')->on('jasa_mua_kategori');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('layanan');
    }
}
