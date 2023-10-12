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
    ];

    /**
     * Get the pencariJasaMua that owns the Pemesanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pencariJasaMua()
    {
        return $this->belongsTo(PencariJasaMua::class);
    }

    /**
     * Get the penyediaJasaMua that owns the Pemesanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penyediaJasaMua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }

    /**
     * Get all of the detailPemesanan for the Pemesanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class);
    }
}
