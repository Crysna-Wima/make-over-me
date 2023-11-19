<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriPembeli extends Model
{
    use HasFactory;

    protected $table = 'galeri_pembeli';

    protected $fillable = [
        'pencari_jasa_mua_id',
        'ulasan_id',
        'foto',
        'deskripsi',
    ];

    // many to one relationship dengan pencari_jasa_mua
    public function pencari_jasa_mua()
    {
        return $this->belongsTo(PencariJasaMua::class);
    }

    // many to one relationship dengan detail_pemesanan
    public function detail_pemesanan()
    {
        return $this->belongsTo(DetailPemesanan::class);
    }
}
