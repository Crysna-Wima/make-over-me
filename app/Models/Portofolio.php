<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portofolio extends Model
{
    use HasFactory;

    protected $table = 'portofolio';

    protected $fillable = [
        'penyedia_jasa_mua_id',
        'foto',
        'deskripsi',
    ];

    /**
     * Get the penyediaJasaMua that owns the Portofolio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penyediaJasaMua(): BelongsTo
    {
        return $this->belongsTo(PenyediaJasaMua::class, 'penyedia_jasa_mua_id', 'id');
    }
}

