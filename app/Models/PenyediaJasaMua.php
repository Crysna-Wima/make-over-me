<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenyediaJasaMua extends Model
{
    use HasFactory;

    protected $table = 'penyedia_jasa_mua';

    protected $fillable = [
        'nama',
        'nomor_telepon',
        'tanggal_lahir',
        'gender',
        'nama_jasa_mua',
        'lokasi_jasa_mua',
        'foto',
        'kapasitas_pelanggan_per_hari',
        'status',
        'user_id',
        'created_by',
        'updated_by',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jasaMuaKategori(): BelongsTo
    {
        return $this->belongsTo(JasaMuaKategori::class);
    }

    public function jamKetersediaan(): BelongsTo
    {
        return $this->belongsTo(JamKetersediaan::class);
    }

    public function portofolio(): BelongsTo
    {
        return $this->belongsTo(Portofolio::class);
    }
}
