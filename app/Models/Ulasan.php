<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';
    public $timestamps = false;

    protected $fillable = [
        'pemesanan_id',
        'penyedia_jasa_mua_id',
        'rating',
        'komentar',
        'tanggal',
    ];

    // one to one relationship dengan pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }
}
