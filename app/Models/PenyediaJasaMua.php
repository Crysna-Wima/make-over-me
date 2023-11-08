<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyediaJasaMua extends Model
{
    use HasFactory;

    protected $table = 'penyedia_jasa_mua';

    protected $fillable = [
        'nama',
        'nomor_telepon',
        'tanggal_lahir',
        'gender',
        'nama_jasa_mua',
        'lokasi_jasa_mua',
        'foto',
        'kapasitas_pelanggan_per_hari',
        'status',
        'user_id',
        'created_by',
        'updated_by',
    ];

    // one to one relationship dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // one to many relationship dengan banner
    public function banner()
    {
        return $this->hasMany(Banner::class);
    }

    // one to many relationship dengan portofolio
    public function portofolio()
    {
        return $this->hasMany(Portofolio::class);
    }

    // one to many relationship dengan hari_ketersediaan
    public function hari_ketersediaan()
    {
        return $this->hasMany(HariKetersediaan::class);
    }

    // many to one relationship dengan kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'lokasi_jasa_mua', 'id');
    }

    // one to many relationship dengan jasa_mua_kategori
    public function jasa_mua_kategori()
    {
        return $this->hasMany(JasaMuaKategori::class);
    }

    // one to many relationship dengan pemesanan
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function kategorilayanan()
    {
        return $this->belongsToMany(KategoriLayanan::class, 'jasa_mua_kategori', 'penyedia_jasa_mua_id', 'kategori_layanan_id');
    }

    public function layanan()
    {
        return $this->hasMany(Layanan::class);
    }
}
