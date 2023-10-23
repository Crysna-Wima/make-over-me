<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pemesanan;
use App\Models\PenyediaJasaMua;
use Illuminate\Http\Request;

class DashboardMuaController extends Controller
{
    public function getProfileMua(){
        $data = PenyediaJasaMua::where('user_id', auth()->user()->id)->select('nama_jasa_mua', 'lokasi_jasa_mua', 'foto')->first();
        $data->foto = url('file/' . auth()->user()->id . "_" . $data->nama_jasa_mua . '/foto/' . $data->foto);
        $data->lokasi_jasa_mua = $data->lokasi_jasa_mua . ', Surabaya';
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }

    public function getLayananMua(){
        // get data layanan and rating from ulasan (note: average rating)
        $data = Layanan::join('detail_pemesanan', 'detail_pemesanan.layanan_id', '=', 'layanan.id')
            ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
            ->join('ulasan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
            ->where('layanan.penyedia_jasa_mua_id', auth()->user()->penyediaJasaMua->id)
            ->selectRaw('layanan.id, layanan.nama, layanan.harga, layanan.foto, layanan.deskripsi, AVG(ulasan.rating) as rating')
            // get top 4 rating and based on pemesanan count
            ->groupBy('layanan.id', 'layanan.nama', 'layanan.harga', 'layanan.foto', 'layanan.deskripsi')
            ->orderByRaw('rating DESC, COUNT(pemesanan.id) DESC')
            ->limit(4)
            ->get();

        foreach($data as $key => $value){
            $data[$key]->foto = url('/images/layanan/'.$value->foto);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }

    public function getPemesanan(){
        $data = Pemesanan::join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
            ->join('layanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
            ->join('kategori_layanan', 'kategori_layanan.id', '=', 'layanan.kategori_layanan_id')
            ->join('pencari_jasa_mua', 'pencari_jasa_mua.id', '=', 'pemesanan.pencari_jasa_mua_id')
            ->where('layanan.penyedia_jasa_mua_id', auth()->user()->penyediaJasaMua->id)
            ->where('pemesanan.status', '!=', 1)
            ->selectRaw('pemesanan.id, pemesanan.tanggal_pemesanan, kategori_layanan.nama as kategori, pencari_jasa_mua.nama as nama_pencari, pencari_jasa_mua.foto as foto_pencari, pemesanan.status')
            ->get();

        foreach($data as $key => $value){
            $data[$key]->tanggal_pemesanan = date('d-m-Y', strtotime($value->tanggal_pemesanan));
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }

    public function getUlasan(){
        
    }
}
