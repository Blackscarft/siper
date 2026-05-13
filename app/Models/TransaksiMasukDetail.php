<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMasukDetail extends Model
{
    protected $table = 'transaksi_masuk_details';

    protected $fillable = [
        'transaksi_masuk_id',
        'barang_id',
        'jumlah',
    ];

    // Relasi balik ke Header
    public function header()
    {
        return $this->belongsTo(TransaksiMasuk::class, 'transaksi_masuk_id');
    }

    // Relasi ke Master Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
