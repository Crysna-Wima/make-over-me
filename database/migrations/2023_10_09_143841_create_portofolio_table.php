<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortofolioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portofolio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyedia_jasa_mua_id')->nullable();
            $table->string('judul');
            $table->string('deskripsi');
            $table->string('gambar');
            $table->timestamps();

            // Foreign keys
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
        Schema::dropIfExists('portofolio');
    }
}
