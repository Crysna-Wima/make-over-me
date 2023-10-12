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
        'alamat',
        'nomor_telepon',
        'user_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user that owns the PenyediaJasaMua
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
