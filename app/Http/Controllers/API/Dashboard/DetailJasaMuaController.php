<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\GaleriPenjual;
use App\Models\JasaMuaKategori;
use App\Models\Layanan;
use App\Models\PenyediaJasaMua;
use App\Models\Portofolio;
use Illuminate\Http\Request;

class DetailJasaMuaController extends Controller
{
    public function index($id){
        // galeri_penjual
        $mua = PenyediaJasaMua::where('id', $id)->first();
        $galeri = GaleriPenjual::where('penyedia_jasa_mua_id', $id)->get();

        foreach ($galeri as $key => $value) {
            $galeri[$key]->foto = url('file/' . $mua->user_id . "_" . $mua->nama . '/galeri_penjual/' . $value->foto);
        }

        // profil penyedia jasa mua
        $profil = PenyediaJasaMua::where('penyedia_jasa_mua.id', $id)
            ->leftJoin('kecamatan', 'penyedia_jasa_mua.lokasi_jasa_mua', '=', 'kecamatan.id')
            ->select('penyedia_jasa_mua.nama_jasa_mua','penyedia_jasa_mua.nama', 'kecamatan.nama_kecamatan as lokasi_jasa_mua')
            ->first();
            
        // $profil->lokasi_jasa_mua =  $profil->lokasi_jasa_mua. ', Surabaya';

        
        // get average rating for this penyedia jasa mua
        $rating = Ulasan::join('pemesanan', 'ulasan.pemesanan_id', '=', 'pemesanan.id')
        ->where('pemesanan.penyedia_jasa_mua_id', $id)
        ->where('pemesanan.status', 'selesai')
        ->avg('ulasan.rating');
        
        $rating = number_format($rating, 1);

        // get harga termurah dan termahal
        $harga = Layanan::join('jasa_mua_kategori', 'jasa_mua_kategori.id', '=', 'layanan.jasa_mua_kategori_id')
        ->where('layanan.penyedia_jasa_mua_id', $id)
        ->select('layanan.harga')
        ->get();
    
        $hargaSorted = $harga->pluck('harga')->map(function ($harga) {
            return preg_replace("/[^0-9]/", '', $harga);
        })->sort();
        
        $sortedHarga = $hargaSorted->map(function ($harga) {
            return 'Rp. ' . number_format($harga, 0, ',', '.');
        })->values();

// return $sortedHarga;
        // sorted harga take array index 0 and last index
        $hargatermurah = $sortedHarga[0];
        $hargatermahal = $sortedHarga[$sortedHarga->count() - 1];

        $harga = $hargatermurah. ' - ' . $hargatermahal;

        // get kategori jasa mua
        $kategori = JasaMuaKategori::join('kategori_layanan', 'kategori_layanan.id', '=', 'jasa_mua_kategori.kategori_layanan_id')
            ->where('jasa_mua_kategori.penyedia_jasa_mua_id', $id)
            ->select('kategori_layanan.nama')
            ->get();
        
        // get portofolio
        $portofolio = Portofolio::where('penyedia_jasa_mua_id', $id)->select('file')->get();
        foreach ($portofolio as $key => $value) {
            $portofolio[$key]->file = url('file/' . $mua->user_id . "_" . $mua->nama . '/portofolio/' . $value->file);
        }

        // get layanan with harga with kategori
        $layanan = Layanan::join('jasa_mua_kategori', 'jasa_mua_kategori.id', '=', 'layanan.jasa_mua_kategori_id')
            ->where('layanan.penyedia_jasa_mua_id', $id)
            ->select('layanan.*', 'jasa_mua_kategori.kategori_layanan_id')
            ->get();
        foreach ($layanan as $key => $value) {
                $layanan[$key]->durasi = $layanan[$key]->durasi.' menit';
                $layanan[$key]->harga = 'Rp. ' . number_format($value->harga, 0, ',', '.');
        }

        
        // get review terbaru
        $review = PenyediaJasaMua::join('layanan', 'layanan.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
            ->join('detail_pemesanan', 'detail_pemesanan.layanan_id', '=', 'layanan.id')
            ->join('pemesanan', 'pemesanan.id', '=', 'detail_pemesanan.pemesanan_id')
            ->join('ulasan', 'ulasan.pemesanan_id', '=', 'detail_pemesanan.pemesanan_id')
            ->join('pencari_jasa_mua', 'pencari_jasa_mua.id', '=', 'pemesanan.pencari_jasa_mua_id')
            ->join('kecamatan', 'kecamatan.id', '=', 'pencari_jasa_mua.alamat')
            ->join('jasa_mua_kategori', 'jasa_mua_kategori.id', '=', 'layanan.jasa_mua_kategori_id')
            ->join('kategori_layanan', 'kategori_layanan.id', '=', 'jasa_mua_kategori.kategori_layanan_id')
            ->where('penyedia_jasa_mua.id', $id)
            ->select('ulasan.*', 'pencari_jasa_mua.nama as nama_pencari', 'pencari_jasa_mua.foto as foto_pencari', 'kecamatan.nama_kecamatan', 'layanan.nama as nama_layanan', 'layanan.harga', 'layanan.deskripsi', 'kategori_layanan.nama as kategori', 'pencari_jasa_mua.nama', 'pencari_jasa_mua.user_id', 'pencari_jasa_mua.foto')
            ->orderBy('ulasan.tanggal', 'DESC')
            ->limit(3)
            ->get();
        
            foreach ($review as $key => $value) {
                $review[$key]->foto = $this->formatFotoUrl($value);
                $review[$key]->nama_kecamatan = $review[$key]->nama_kecamatan. ', Surabaya';
            }

        // get wa.me link
        $wa = PenyediaJasaMua::where('id', $id)->select('nomor_telepon')->first();
        $wa = 'https://wa.me/' . $wa->nomor_telepon;

        return response()->json([
            'status' => 'success',
            'data' => [
                'galeri' => $galeri,
                'profil' => $profil,
                'rating' => $rating,
                'harga' => $harga,
                'kategori' => $kategori,
                'portofolio' => $portofolio,
                'layanan' => $layanan,
                'review' => $review,
                'wa' => $wa
            ]
        ]);
    }
}
