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
        'file',
    ];

    // many to one relationship dengan penyedia_jasa_mua
    public function penyedia_jasa_mua()
    {
        return $this->belongsTo(PenyediaJasaMua::class);
    }
}

