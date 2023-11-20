<?php

namespace App\Http\Controllers\API\Ulasan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pemesanan;
use App\Models\PenyediaJasaMua;
use Illuminate\Support\Facades\DB;

class UlasanController extends Controller
{
    public function getSeluruhUlasan()
    {
        $data = Pemesanan::join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
        ->join('layanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
        ->join('ulasan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
        ->join('pencari_jasa_mua', 'pencari_jasa_mua.id', '=', 'pemesanan.pencari_jasa_mua_id')
        ->join('penyedia_jasa_mua', 'penyedia_jasa_mua.id', '=', 'layanan.penyedia_jasa_mua_id')
        ->leftJoin('galeri_pembeli', 'galeri_pembeli.ulasan_id', '=', 'ulasan.id') // Left join to include cases where there's no galeri_pembeli entry
        ->where('layanan.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
        ->where('pemesanan.status', '=', 'done')
        ->select('pemesanan.id', 'pemesanan.tanggal_pemesanan', 'pencari_jasa_mua.nama as nama', 'pencari_jasa_mua.foto as foto', 'galeri_pembeli.foto as foto_ulasan', 'ulasan.rating', 'ulasan.komentar', 'layanan.nama as nama_layanan', 'pencari_jasa_mua.user_id', 'penyedia_jasa_mua.user_id as user_id_mua', 'penyedia_jasa_mua.nama as nama_mua')
        ->get();

    $groupedData = [];

    foreach ($data as $key => $value) {
        $id = $value->id;
        $tanggal_pemesanan = date('d-m-Y', strtotime($value->tanggal_pemesanan));
        $foto = $this->formatFotoUrl($value);
        $foto_ulasan = $value->foto_ulasan; // Assume this is an array of photo filenames

        // Check if the entry for this ID already exists in the grouped data
        if (!isset($groupedData[$id])) {
            $groupedData[$id] = [
                'id' => $id,
                'tanggal_pemesanan' => $tanggal_pemesanan,
                'nama' => $value->nama,
                'foto' => $foto,
                'foto_ulasan' => [],
                'rating' => $value->rating,
                'nama_layanan' => $value->nama_layanan,
                'komentar' => $value->komentar,
                'user_id' => $value->user_id,
            ];
        }

        // Add the review photo to the grouped data
        if (!empty($foto_ulasan)) {
            $groupedData[$id]['foto_ulasan'][] = url('/file/' . $value->user_id_mua . '_' . $value->nama_mua . '/review/' . $foto_ulasan);
        }
    }

    // Convert associative array to indexed array
    $groupedData = array_values($groupedData);

    return response()->json([
        'success' => true,
        'message' => 'Berhasil mendapatkan seluruh ulasan',
        'data' => $groupedData
    ]);
    }

    public function getDetailUlasan($id)
    {
        $data = Pemesanan::join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
        ->join('layanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
        ->join('ulasan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
        ->join('pencari_jasa_mua', 'pencari_jasa_mua.id', '=', 'pemesanan.pencari_jasa_mua_id')
        ->join('penyedia_jasa_mua', 'penyedia_jasa_mua.id', '=', 'layanan.penyedia_jasa_mua_id')
        ->leftJoin('galeri_pembeli', 'galeri_pembeli.ulasan_id', '=', 'ulasan.id') // Left join to include cases where there's no galeri_pembeli entry
        ->where('layanan.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
        ->where('pemesanan.status', '=', 'done')
        ->select('pemesanan.id', 'pencari_jasa_mua.user_id', 'pencari_jasa_mua.nama as nama', 'pemesanan.tanggal_pemesanan', 'layanan.nama as nama_layanan', 'ulasan.rating', 'ulasan.komentar', 'galeri_pembeli.foto as foto_ulasan', 'penyedia_jasa_mua.user_id as user_id_mua', 'penyedia_jasa_mua.nama as nama_mua')
        ->get();

        $groupedData = [];

        foreach ($data as $key => $value) {
            $id = $value->id;
            $tanggal_pemesanan = date('d-m-Y', strtotime($value->tanggal_pemesanan));
            $foto_ulasan = $value->foto_ulasan; // Assume this is an array of photo filenames
    
            // Check if the entry for this ID already exists in the grouped data
            if (!isset($groupedData[$id])) {
                $groupedData[$id] = [
                    'id' => $id,
                    'nama' => $value->nama,
                    'tanggal_pemesanan' => $tanggal_pemesanan,
                    'nama_layanan' => $value->nama_layanan,
                    'rating' => $value->rating,
                    'komentar' => $value->komentar,
                    'foto_ulasan' => [],
                    'user_id' => $value->user_id,
                ];
            }
    
            // Add the review photo to the grouped data
            if (!empty($foto_ulasan)) {
                $groupedData[$id]['foto_ulasan'][] = url('/file/' . $value->user_id_mua . '_' . $value->nama_mua . '/review/' . $foto_ulasan);
            }
        }
    
        // Convert associative array to indexed array
        $groupedData = array_values($groupedData);
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan detil ulasan',
            'data' => $groupedData
        ], 200);
    }
}