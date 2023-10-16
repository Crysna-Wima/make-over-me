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

    /**
     * Get all of the layanan for the KategoriLayanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function layanan()
    {
        return $this->hasMany(Layanan::class);
    }

    /**
     * Get all of the jasaMuaKategori for the KategoriLayanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jasaMuaKategori()
    {
        return $this->hasMany(JasaMuaKategori::class);
    }
}
