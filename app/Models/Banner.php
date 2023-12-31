<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banner';
    protected $fillable = [
        'user_id',
        'gambar',
        'status',
        'link',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    // many to one relationship dengan penyedia_jasa_mua
    public function penyedia_jasa_mua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }
}
