<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HariKetersediaan extends Model
{
    use HasFactory;

    protected $table = 'hari_ketersediaan';

    protected $fillable = [
        'penyedia_jasa_mua_id',
        'hari',
    ];

    // many to one relationship dengan penyedia_jasa_mua
    public function penyedia_jasa_mua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }
}
