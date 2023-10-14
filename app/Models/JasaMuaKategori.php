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

    /**
     * Get the penyediaJasaMua that owns the JasaMuaKategori
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penyediaJasaMua(): BelongsTo
    {
        return $this->belongsTo(PenyediaJasaMua::class, 'penyedia_jasa_mua_id', 'id');
    }

    /**
     * Get the kategoriLayanan that owns the JasaMuaKategori
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategoriLayanan(): BelongsTo
    {
        return $this->belongsTo(KategoriLayanan::class, 'kategori_layanan_id', 'id');
    }


}
