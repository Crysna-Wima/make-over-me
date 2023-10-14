<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'penyedia_jasa_mua_id',
        'jasa_mua_kategori_id',
        'nama',
        'harga',
        'deskripsi',
    ];

    /**
     * Get the penyediaJasaMua that owns the Layanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penyediaJasaMua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }

    /**
     * Get the jasaMuaKategori that owns the Layanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jasaMuaKategori()
    {
        return $this->belongsTo(JasaMuaKategori::class);
    }
    
}
