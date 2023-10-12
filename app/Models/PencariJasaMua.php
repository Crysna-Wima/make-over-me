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
        'alamat',
        'nomor_telepon',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user that owns the PencariJasaMua
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
