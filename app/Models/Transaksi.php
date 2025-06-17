<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_tamu',
        'id_kamar',
        'sub_total',
        'tgl_transaksi',
        'tgl_checkin',
        'tgl_checkout',
        'is_paid',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_response'
    ];

    protected $casts = [
        'sub_total' => 'decimal:2',
        'tgl_transaksi' => 'date',
        'tgl_checkin' => 'date',
        'tgl_checkout' => 'date',
    ];

    public function tamu(): BelongsTo
    {
        return $this->belongsTo(Tamu::class, 'id_tamu', 'id_tamu');
    }

    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
