<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';

    protected $fillable = [
        'pencari_jasa_mua_id',
        'penyedia_jasa_mua_id',
        'tanggal_pemesanan',
        'status',
        'nama_pemesan',
        'nomor_telepon_pemesan',
        'gender_pemesan',
        'keterangan',
    ];

    // many to one relationship dengan pencari_jasa_mua
    public function pencari_jasa_mua()
    {
        return $this->belongsTo(PencariJasaMua::class);
    }

    // many to one relationship dengan penyedia_jasa_mua
    public function penyedia_jasa_mua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }

    // one to one relationship dengan ulasan
    public function ulasan()
    {
        return $this->hasOne(Ulasan::class);
    }

    // one to one dengan detail_pemesanan
    public function detail_pemesanan()
    {
        return $this->hasOne(DetailPemesanan::class);
    }

    
}
