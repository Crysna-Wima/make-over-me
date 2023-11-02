<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLayanan extends Model
{
    use HasFactory;

    protected $table = 'kategori_layanan';

    protected $fillable = [
        'nama',
    ];

    // one to many relationship dengan jasa_mua_kategori
    public function jasa_mua_kategori()
    {
        return $this->hasMany(JasaMuaKategori::class);
    }
}
