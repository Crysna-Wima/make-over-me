<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pencari_jasa_mua_id')->nullable();
            $table->unsignedBigInteger('penyedia_jasa_mua_id')->nullable();
            $table->dateTime('tanggal_pemesanan');
            $table->string('status');
            $table->timestamps();   

            // Foreign keys
            $table->foreign('pencari_jasa_mua_id')->references('id')->on('pencari_jasa_mua');
            $table->foreign('penyedia_jasa_mua_id')->references('id')->on('penyedia_jasa_mua');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
}
