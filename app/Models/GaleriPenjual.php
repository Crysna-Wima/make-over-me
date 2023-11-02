<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriPenjual extends Model
{
    use HasFactory;

    protected $table = 'galeri_penjual';

    protected $fillable = [
        'penyedia_jasa_mua_id',
        'foto',
        'deskripsi',
    ];
}
