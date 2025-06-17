<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petugas extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_petugas';
    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'roles'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}
