<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKeluarDetail extends Model
{
    protected $table = 'transaksi_keluar_details';

    protected $fillable = [
        'transaksi_keluar_id',
        'barang_id',
        'jumlah',
    ];

    // Relasi balik ke Header
    public function header()
    {
        return $this->belongsTo(TransaksiKeluar::class, 'transaksi_keluar_id');
    }

    // Relasi ke Master Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
