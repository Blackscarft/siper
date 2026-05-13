<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    protected $table = 'transaksi_masuks';

    protected $fillable = [
        'no_transaksi',
        'tanggal_masuk',
        'keterangan',
        'user_id',
    ];

    // Relasi ke User (Admin yang input)
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Detail (Isi Barang)
    public function details()
    {
        return $this->hasMany(TransaksiMasukDetail::class);
    }
}
