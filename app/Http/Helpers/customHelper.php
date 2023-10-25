<?php

use App\Models\JasaMuaKategori;
use Illuminate\Support\Str;
use App\Models\JamKetersediaan;
use App\Models\Kecamatan;

// parse Data
if (!function_exists('parseData')) {
    function parseData($data)
    {
        $data = str_replace(['[', ']', ' '], '', $data);
        return explode(',', $data);
    }
}

// uploadBase64Image
if (!function_exists('uploadBase64Foto')) {
    function uploadBase64Foto($base64Image, $userId, $nama)
    {
        // Define the directory path
        $directory = 'file/' . $userId . "_" . $nama . '/foto/';

        // Check if the directory exists; if not, create it.
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if ($base64Image) {
            // Extract the image data from the base64 string
            $imageData = base64_decode($base64Image);

            if ($imageData === false) {
                return 'default.jpg'; // Handle decoding error here
            }

            // Create an image resource from the decoded data
            $sourceImage = imagecreatefromstring($imageData);

            if ($sourceImage === false) {
                return 'default.jpg'; // Handle image creation error here
            }

            // Generate a unique filename with .jpg extension
            $filename = time() . Str::random(10) . '.jpg';

            // Save the image to the specified folder
            if (imagejpeg($sourceImage, $directory . $filename)) {
                return $filename;
            } else {
                return 'default.jpg'; // Handle image saving error here
            }
        } else {
            return 'default.jpg'; // Handle missing image data
        }
    }
}

// uploadBase64Pdf
if (!function_exists('uploadBase64Portofolio')) {
    function uploadBase64Portofolio($base64Pdf, $userId, $nama)
    {
        // Define the directory path
        $directory = 'file/' . $userId . "_" . $nama . '/portofolio/';

        // Check if the directory exists; if not, create it.
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if ($base64Pdf) {
            // Extract the PDF data from the base64 string
            $base64Pdf = str_replace('data:application/pdf;base64,', '', $base64Pdf);
            $pdfData = base64_decode($base64Pdf);

            if ($pdfData === false) {
                return null; // Handle decoding error here
            }

            // Generate a unique filename with a .pdf extension
            $filename = time() . Str::random(10) . '.pdf';

            // Save the PDF to the specified folder
            if (file_put_contents($directory . $filename, $pdfData)) {
                return $filename;
            } else {
                return null; // Handle file saving error here
            }
        } else {
            return null; // Handle missing PDF data
        }
    }
}

// formatFotoUrl
if (!function_exists('formatFotoUrl')) {
    function formatFotoUrl($data)
    {
        return url('/file/' . $data->user_id . "_" . $data->nama . '/foto/' . $data->foto);
    }
}

// formatPortofolioUrls
if (!function_exists('formatPortofolioUrls')) {
    function formatPortofolioUrls($portofolioFiles, $data)
    {
        $portofolioUrls = [];

        foreach ($portofolioFiles as $portofolioFile) {
            $portofolioUrls[] = url('/file/' . $data->user_id . "_" . $data->nama . '/portofolio/' . $portofolioFile);
        }

        return $portofolioUrls;
    }
}

// formatLayananUrl
if (!function_exists('formatLayananUrl')) {
    function formatLayananUrl($data)
    {
        return url('/file/' . $data->penyedia_jasa_mua_id . "_" . $data->nama . '/layanan/' . $data->foto);
    }
}

// getJasaMuaKategoriName
if (!function_exists('getJasaMuaKategoriName')) {
    function getJasaMuaKategoriName($penyediaJasaMua)
    {
        $jasaMuaKategoriNames = JasaMuaKategori::join('kategori_layanan', 'jasa_mua_kategori.kategori_layanan_id', '=', 'kategori_layanan.id')
            ->where('penyedia_jasa_mua_id', $penyediaJasaMua->id)
            ->pluck('kategori_layanan.nama');

        return $jasaMuaKategoriNames;
    }
}

// getHariKetersediaan
if (!function_exists('getHariKetersediaan')) {
    function getHariKetersediaan($penyediaJasaMua)
    {
        $hariKetersediaan = JamKetersediaan::where('penyedia_jasa_mua_id', $penyediaJasaMua->id)
            ->pluck('hari');

        return $hariKetersediaan;
    }
}

// cari kecamatan terdekat
if(!function_exists('getKecamatanTerdekat')){
    function getKecamatanTerdekat($kecamatan) {
        $kecamatanTerdekat = Kecamatan::where('nama_kecamatan', $kecamatan)->select('wilayah')->first();
        $kecamatanTerdekat = Kecamatan::where('wilayah', $kecamatanTerdekat->wilayah)->select('nama_kecamatan')->get();
        return $kecamatanTerdekat;
    }
}