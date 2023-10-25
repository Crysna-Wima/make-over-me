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
}
