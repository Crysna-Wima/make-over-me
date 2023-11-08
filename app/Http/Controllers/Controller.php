<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\JasaMuaKategori;
use Illuminate\Support\Str;
use App\Models\HariKetersediaan;
use App\Models\Kecamatan;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

function parseData($data)
{
    $data = str_replace(['[', ']', ' '], '', $data);
    return explode(',', $data);
}

function uploadBase64Foto($base64Image, $userId, $nama)
{
    $directory = 'file/' . $userId . "_" . $nama . '/foto/';

    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }

    if ($base64Image) {
        $imageData = base64_decode($base64Image);

        if ($imageData === false) {
            return 'default.jpg';
        }

        $sourceImage = imagecreatefromstring($imageData);

        if ($sourceImage === false) {
            return 'default.jpg';
        }

        $filename = time() . Str::random(10) . '.jpg';

        if (imagejpeg($sourceImage, $directory . $filename)) {
            return $filename;
        } else {
            return 'default.jpg';
        }
    } else {
        return 'default.jpg';
    }
}

function uploadBase64Portofolio($base64Pdf, $userId, $nama)
{
    $directory = 'file/' . $userId . "_" . $nama . '/portofolio/';

    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }

    if ($base64Pdf) {
        $base64Pdf = str_replace('data:application/pdf;base64,', '', $base64Pdf);
        $pdfData = base64_decode($base64Pdf);

        if ($pdfData === false) {
            return null;
        }

        $filename = time() . Str::random(10) . '.pdf';

        if (file_put_contents($directory . $filename, $pdfData)) {
            return $filename;
        } else {
            return null;
        }
    } else {
        return null;
    }
}

function formatFotoUrl($data)
{
    return url('/file/' . $data->user_id . "_" . $data->nama . '/foto/' . $data->foto);
}

function formatPortofolioUrls($portofolioFiles, $data)
{
    $portofolioUrls = [];

    foreach ($portofolioFiles as $portofolioFile) {
        $portofolioUrls[] = url('/file/' . $data->user_id . "_" . $data->nama . '/portofolio/' . $portofolioFile);
    }

    return $portofolioUrls;
}

function formatLayananUrl($data)
{
    return url('/file/' . $data->user_id . "_" . $data->nama . '/layanan/' . $data->foto);
}

function getJasaMuaKategoriName($penyediaJasaMua)
{
    $jasaMuaKategoriNames = JasaMuaKategori::join('kategori_layanan', 'jasa_mua_kategori.kategori_layanan_id', '=', 'kategori_layanan.id')
        ->where('penyedia_jasa_mua_id', $penyediaJasaMua->id)
        ->pluck('kategori_layanan.nama');

    return $jasaMuaKategoriNames;
}

function getHariKetersediaan($penyediaJasaMua)
{
    $hariKetersediaan = HariKetersediaan::where('penyedia_jasa_mua_id', $penyediaJasaMua->id)
        ->pluck('hari');

    return $hariKetersediaan;
}

function getKecamatanTerdekat($kecamatan)
{
    $kecamatanTerdekat = Kecamatan::where('id', $kecamatan)->select('wilayah')->first();
    $kecamatanTerdekat = Kecamatan::where('wilayah', $kecamatanTerdekat->wilayah)->select('id')->get();
    return $kecamatanTerdekat;
}

function getKecamatanByWilayah($wilayah)
{
    $nama_wilayah = '';
    if ($wilayah == 1){
        $nama_wilayah = 'Surabaya Utara';
    } else if ($wilayah == 2){
        $nama_wilayah = 'Surabaya Timur';
    } else if ($wilayah == 3){
        $nama_wilayah = 'Surabaya Selatan';
    } else if ($wilayah == 4){
        $nama_wilayah = 'Surabaya Barat';
    } else if ($wilayah == 5){
        $nama_wilayah = 'Surabaya Pusat';
    }
    $kecamatan = Kecamatan::where('wilayah', $nama_wilayah)->select('id')->get();
    return $kecamatan;
}

}
