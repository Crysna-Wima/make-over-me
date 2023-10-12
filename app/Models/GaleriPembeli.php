<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriPembeli extends Model
{
    use HasFactory;

    protected $table = 'galeri_pembeli';

    protected $fillable = [
        'pencari_jasa_mua_id',
        'foto',
        'deskripsi',
    ];

    /**
     * Get the pencariJasaMua that owns the GaleriPembeli
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pencariJasaMua()
    {
        return $this->belongsTo(PencariJasaMua::class);
    }

    /**
     * Get all of the galeriPembeli for the GaleriPembeli
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galeriPembeli()
    {
        return $this->hasMany(GaleriPembeli::class);
    }
}
