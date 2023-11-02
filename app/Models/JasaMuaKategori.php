<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JasaMuaKategori extends Model
{
    use HasFactory;

    protected $table = 'jasa_mua_kategori';

    protected $fillable = [
        'penyedia_jasa_mua_id',
        'kategori_layanan_id',
    ];

    // many to one relationship dengan penyedia_jasa_mua
    public function penyedia_jasa_mua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }

    // many to one relationship dengan kategori_layanan
    public function kategori_layanan()
    {
        return $this->belongsTo(KategoriLayanan::class);
    }

    // one to one relationship dengan layanan
    public function layanan()
    {
        return $this->hasOne(Layanan::class);
    }


}
