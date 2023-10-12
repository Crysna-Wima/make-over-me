<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemesanan';

    protected $fillable = [
        'pemesanan_id',
        'layanan_id',
        'jumlah',
        'total_harga',
    ];

    /**
     * Get the pemesanan that owns the DetailPemesanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    /**
     * Get the layanan that owns the DetailPemesanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
