<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'pemesanan_id',
        'penyedia_jasa_mua_id',
        'rating',
        'komentar',
        'tanggal',
    ];

    /**
     * Get the pemesanan that owns the Ulasan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    /**
     * Get the penyediaJasaMua that owns the Ulasan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penyediaJasaMua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }
}
