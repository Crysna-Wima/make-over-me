<?php

namespace App\Http\Controllers\API\Layanan;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function getLayananMua($id)
    {
        $layanan = Layanan::where('penyedia_jasa_mua_id', $id)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data layanan',
            'data' => $layanan,
        ]);
    }

    public function getAllLayananMua()
    {
        $layanan = Layanan::select('layanan.id', 'layanan.nama', 'layanan.foto', 'penyedia_jasa_mua.nama_jasa_mua', 'kecamatan.nama_kecamatan as lokasi', 'penyedia_jasa_mua.nama', 'users.id as user_id', 'penyedia_jasa_mua.id as id_mua')
        ->leftJoin('detail_pemesanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
        ->leftJoin('ulasan', 'detail_pemesanan.pemesanan_id', '=', 'ulasan.pemesanan_id')
        ->leftJoin('penyedia_jasa_mua', 'layanan.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
        ->leftJoin('kecamatan', 'penyedia_jasa_mua.lokasi_jasa_mua', '=', 'kecamatan.id')
        ->leftJoin('users', 'penyedia_jasa_mua.user_id', '=', 'users.id')
        ->get();

        foreach ($layanan as $key => $value) {
            $value->foto = $this->formatLayananUrl($value);
            $value->lokasi = $value->lokasi.', Surabaya';
        }

        $data = [];
        foreach ($layanan as $key => $value) {
            $data[$key]['id'] = $value->id_mua;
            $data[$key]['nama'] = $value->nama_jasa_mua;
            $data[$key]['foto'] = $value->foto;
            $data[$key]['lokasi'] = $value->lokasi;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data layanan',
            'data' => $data,
        ]);
    }
}
