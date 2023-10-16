<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user.
     */
    public function hasRole(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * Get the penyediaJasaMua associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function penyediaJasaMua(): HasOne
    {
        return $this->hasOne(PenyediaJasaMua::class, 'user_id', 'id');
    }

    /**
     * Get the pencariJasaMua associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pencariJasaMua(): HasOne
    {
        return $this->hasOne(PencariJasaMua::class, 'user_id', 'id');
    }

}
