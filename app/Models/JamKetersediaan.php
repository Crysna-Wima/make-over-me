<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JamKetersediaan extends Model
{
    use HasFactory;

    protected $table = 'hari_ketersediaan';

    protected $fillable = [
        'penyedia_jasa_mua_id',
        'hari',
    ];

    /**
     * Get the penyediaJasaMua that owns the JamKetersediaan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penyediaJasaMua(): BelongsTo
    {
        return $this->belongsTo(PenyediaJasaMua::class, 'penyedia_jasa_mua_id', 'id');
    }
}
