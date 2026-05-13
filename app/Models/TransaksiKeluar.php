<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKeluar extends Model
{
    protected $table = 'transaksi_keluars';

    protected $fillable = [
        'no_transaksi',
        'tanggal_keluar',
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
        return $this->hasMany(TransaksiKeluarDetail::class);
    }
}
