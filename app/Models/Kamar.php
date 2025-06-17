<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    protected $table = 'tb_kamar';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'id_tipe_kamar',
        'nomer_kamar',
        'jumlah_bed',
        'harga',
        'fasilitas',
        'thumbnail_kamar',
        'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function tipeKamar(): BelongsTo
    {
        return $this->belongsTo(TipeKamar::class, 'id_tipe_kamar', 'id_tipe');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_kamar', 'id_kamar');
    }
}
