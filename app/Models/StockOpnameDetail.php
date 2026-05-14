<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $table = 'stock_opname_details';
    protected $fillable = [
        'stock_opname_id',
        'barang_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'catatan',
    ];

    public function header()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
