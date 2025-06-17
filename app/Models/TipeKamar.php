<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TipeKamar extends Model
{
    protected $table = 'tb_tipe_kamar';
    protected $primaryKey = 'id_tipe';

    protected $fillable = [
        'nama_tipe',
        'id_layanan'
    ];

    public function kamar(): HasMany
    {
        return $this->hasMany(Kamar::class, 'id_tipe_kamar', 'id_tipe');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'id_layanan', 'id_layanan');
    }
}
