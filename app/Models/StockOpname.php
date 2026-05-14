<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $table = 'stock_opnames';
    
    protected $fillable = [
        'no_opname',
        'tanggal',
        'keterangan',
        'user_id'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }
}
