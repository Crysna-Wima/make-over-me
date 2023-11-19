<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'kategori_layanan_id',
        'penyedia_jasa_mua_id',
        'jasa_mua_kategori_id',
        'nama',
        'foto',
        'harga',
        'deskripsi',
    ];

    // one to one relationship dengan jasa_mua_kategori
    public function jasa_mua_kategori()
    {
        return $this->belongsTo(JasaMuaKategori::class);
    }

    // one to one relationship dengan detail_pemesanan
    public function detail_pemesanan()
    {
        return $this->hasOne(DetailPemesanan::class);
    }

    public static function layananPopuler($limit)
    {
        return self::select('layanan.id', 'layanan.nama', 'layanan.foto', 'layanan.harga', 'layanan.deskripsi', 'penyedia_jasa_mua.nama_jasa_mua', 'kecamatan.nama_kecamatan', 'penyedia_jasa_mua.nama', 'users.id as user_id', 'penyedia_jasa_mua.id as id_mua')
            ->leftJoin('detail_pemesanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
            ->leftJoin('ulasan', 'detail_pemesanan.pemesanan_id', '=', 'ulasan.pemesanan_id')
            ->leftJoin('penyedia_jasa_mua', 'layanan.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
            ->leftJoin('kecamatan', 'penyedia_jasa_mua.lokasi_jasa_mua', '=', 'kecamatan.id')
            ->leftJoin('users', 'penyedia_jasa_mua.user_id', '=', 'users.id')
            ->groupBy('layanan.id', 'layanan.nama', 'layanan.foto', 'layanan.harga', 'layanan.deskripsi', 'penyedia_jasa_mua.nama_jasa_mua', 'kecamatan.nama_kecamatan', 'penyedia_jasa_mua.nama', 'users.id', 'penyedia_jasa_mua.id')
            ->orderByRaw('COUNT(detail_pemesanan.id) DESC, AVG(ulasan.rating) DESC')
            ->limit($limit)
            ->get();
    }

    public static function layananTerdekat($lokasi, $limit)
    {
        return self::select('layanan.id', 'layanan.nama', 'layanan.foto', 'layanan.harga', 'layanan.deskripsi', 'penyedia_jasa_mua.nama_jasa_mua', 'kecamatan.nama_kecamatan', 'penyedia_jasa_mua.nama', 'users.id as user_id','penyedia_jasa_mua.id as id_mua')
        ->leftJoin('detail_pemesanan', 'layanan.id', '=', 'detail_pemesanan.layanan_id')
        ->leftJoin('ulasan', 'detail_pemesanan.pemesanan_id', '=', 'ulasan.pemesanan_id')
        ->leftJoin('penyedia_jasa_mua', 'layanan.penyedia_jasa_mua_id', '=', 'penyedia_jasa_mua.id')
        ->leftJoin('kecamatan', 'penyedia_jasa_mua.lokasi_jasa_mua', '=', 'kecamatan.id')
        ->leftJoin('users', 'penyedia_jasa_mua.user_id', '=', 'users.id')
        ->whereIn('penyedia_jasa_mua.lokasi_jasa_mua', $lokasi)
        ->groupBy('layanan.id', 'layanan.nama', 'layanan.foto', 'layanan.harga', 'layanan.deskripsi', 'penyedia_jasa_mua.nama_jasa_mua', 'kecamatan.nama_kecamatan', 'penyedia_jasa_mua.nama', 'users.id', 'penyedia_jasa_mua.id')
        ->orderByRaw('COUNT(detail_pemesanan.id) DESC, AVG(ulasan.rating) DESC')
        ->limit($limit)
        ->get();

    }

    
}
