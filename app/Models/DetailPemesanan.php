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

    // one to one relationship dengan layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    // many to one relationship dengan pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }
}
