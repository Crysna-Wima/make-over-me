<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';

    // one to many relationship dengan penyedia_jasa_mua
    public function penyedia_jasa_mua()
    {
        return $this->hasMany(PenyediaJasaMua::class);
    }

    // one to many relationship dengan pencari_jasa_mua
    public function pencari_jasa_mua()
    {
        return $this->hasMany(PencariJasaMua::class);
    }
}
