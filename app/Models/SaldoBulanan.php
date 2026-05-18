<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoBulanan extends Model
{
    protected $table = 'saldo_bulanans';
    
    protected $fillable = [
        'barang_id',
        'bulan',
        'tahun',
        'stok_awal',
        'stok_masuk',
        'stok_keluar',
        'stok_akhir',
        'selisih_opname',
    ];

    public function barang(){
        return $this->belongsTo(Barang::class);
    }
}
