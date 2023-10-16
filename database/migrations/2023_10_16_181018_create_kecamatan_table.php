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
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan');
            $table->string('kode_kecamatan');
            $table->decimal('luas_wilayah_kecamatan', 8, 2);
            $table->string('keberadaan_kantor_administrasi_pemerintahan');
            $table->string('status_kepemilikan_tanah_kantor_administrasi_pemerintahan');
            $table->string('status_kepemilikan_bangunan_kantor_administrasi_pemerintahan');
            $table->string('tanggal_terakhir_dilakukan_pendataan_batas_wilayah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
