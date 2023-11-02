<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencariJasaMua extends Model
{
    use HasFactory;

    protected $table = 'pencari_jasa_mua';

    protected $fillable = [
        'user_id',
        'nama',
        'tanggal_lahir',
        'gender',
        'alamat',
        'nomor_telepon',
        'foto',
        'created_by',
        'updated_by',
    ];

   // one to one relationship dengan user
    public function user()
    {
         return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // many to one relationship dengan kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'alamat', 'id');
    }

    // one to many relationship dengan pemesanan
    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class,);
    }

    // one to many relationship dengan galeri_pembeli
    public function galeri_pembeli()
    {
        return $this->hasMany(GaleriPembeli::class);
    }
}
