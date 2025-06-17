<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $table = 'tb_layanan';
    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'nama_layanan',
        'harga'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function tipeKamar(): HasMany
    {
        return $this->hasMany(TipeKamar::class, 'id_layanan', 'id_layanan');
    }
}
