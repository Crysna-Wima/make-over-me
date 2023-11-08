<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Pemesanan;
use App\Models\PenyediaJasaMua;
use Illuminate\Support\Facades\DB;

class DashboardMuaController extends Controller
{
    public function getProfileMua()
    {
        $data = PenyediaJasaMua::where('user_id', auth()->user()->id)
            ->leftJoin('kecamatan', 'penyedia_jasa_mua.lokasi_jasa_mua', '=', 'kecamatan.id')
            ->select('penyedia_jasa_mua.nama_jasa_mua','penyedia_jasa_mua.nama', 'kecamatan.nama_kecamatan as lokasi_jasa_mua', 'penyedia_jasa_mua.foto')
            ->first();
    
        $data->lokasi_jasa_mua = $data->lokasi_jasa_mua. ', Surabaya';
        $data->foto = url('file/' . auth()->user()->id . "_" . $data->nama . '/foto/' . $data->foto);
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }

    public function getLayananMua()
    {
        $data = Layanan::join('detail_pemesanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
    ->join('ulasan', 'detail_pemesanan.pemesanan_id', '=', 'ulasan.pemesanan_id')
    ->join('penyedia_jasa_mua', 'layanan.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
    ->select('penyedia_jasa_mua.user_id', 'penyedia_jasa_mua.nama as nama_mua', 'layanan.id', 'layanan.nama', 'layanan.harga', 'layanan.foto', 'layanan.deskripsi', DB::raw('AVG(ulasan.rating) as rating'))
    ->groupBy('penyedia_jasa_mua.user_id', 'penyedia_jasa_mua.nama','layanan.id', 'layanan.nama', 'layanan.harga', 'layanan.foto', 'layanan.deskripsi')
    ->orderByRaw('rating DESC')
    ->limit(4)
    ->get();

    
        foreach ($data as $key => $value) {
            $data[$key]->foto = url('/file/' . $value->user_id.'_'. $value->nama_mua . '/layanan/' . $value->foto);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }
    
    public function getPemesananTerbaru()
    {
        $data = Pemesanan::join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
            ->join('layanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
            ->join('kategori_layanan', 'kategori_layanan.id', '=', 'layanan.kategori_layanan_id')
            ->join('pencari_jasa_mua', 'pencari_jasa_mua.id', '=', 'pemesanan.pencari_jasa_mua_id')
            ->where('pemesanan.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
            ->where('pemesanan.status', '=', 'pending')
            ->selectRaw('pemesanan.id, pemesanan.tanggal_pemesanan, kategori_layanan.nama as kategori, pencari_jasa_mua.nama as nama_pencari, pencari_jasa_mua.foto as foto, pemesanan.status, pencari_jasa_mua.user_id, pencari_jasa_mua.nama')
            ->get();
    
        foreach ($data as $key => $value) {
            $data[$key]->tanggal_pemesanan = date('d-m-Y', strtotime($value->tanggal_pemesanan));
            $data[$key]->foto = $this->formatFotoUrl($value);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }

    public function getSeluruhPemesanan()
    {
        $data = Pemesanan::join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
            ->join('layanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
            ->join('kategori_layanan', 'kategori_layanan.id', '=', 'layanan.kategori_layanan_id')
            ->join('pencari_jasa_mua', 'pencari_jasa_mua.id', '=', 'pemesanan.pencari_jasa_mua_id')
            ->where('pemesanan.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
            // ->where('pemesanan.status', '!=', 'pending') //not in use
            ->selectRaw('pemesanan.id, pemesanan.tanggal_pemesanan, kategori_layanan.nama as kategori, pencari_jasa_mua.nama as nama_pencari, pencari_jasa_mua.foto as foto, pemesanan.status, pencari_jasa_mua.user_id, pencari_jasa_mua.nama')
            ->get();
    
        foreach ($data as $key => $value) {
            $data[$key]->tanggal_pemesanan = date('d-m-Y', strtotime($value->tanggal_pemesanan));
            $data[$key]->foto = $this->formatFotoUrl($value);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }
    
    public function getUlasan()
    {
        $data = Pemesanan::join('detail_pemesanan', 'detail_pemesanan.pemesanan_id', '=', 'pemesanan.id')
            ->join('layanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
            ->join('ulasan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
            ->join('pencari_jasa_mua', 'pencari_jasa_mua.id', '=', 'pemesanan.pencari_jasa_mua_id')
            ->where('layanan.penyedia_jasa_mua_id', auth()->user()->penyedia_jasa_mua->id)
            ->selectRaw('pemesanan.id, pemesanan.tanggal_pemesanan, pencari_jasa_mua.nama as nama_pencari, pencari_jasa_mua.foto as foto, ulasan.rating, ulasan.komentar, pencari_jasa_mua.user_id, pencari_jasa_mua.nama')
            ->get();
    
        foreach ($data as $key => $value) {
            $data[$key]->tanggal_pemesanan = date('d-m-Y', strtotime($value->tanggal_pemesanan));
            $data[$key]->foto = $this->formatFotoUrl($value);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ]);
    }

    public function acceptPemesanan($id)
    {
        $pemesanan = Pemesanan::find($id);
        $pemesanan->status = 'accept';
        $pemesanan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pemesanan Telah Di Terima',
            'data' => $pemesanan,
        ], 200);
    }

    public function declinePemesanan($id)
    {
        $pemesanan = Pemesanan::find($id);
        $pemesanan->status = 'decline';
        $pemesanan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pemesanan Telah Di Tolak',
            'data' => $pemesanan,
        ], 200);
    }
    
}
