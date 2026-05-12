<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'barangs';

    protected $fillable = [
        'kode',
        'nama',
        'satuan_id',
        'kategori_id',
        'catatan',
    ];

    public function satuan()
    {
        return $this->belongsTo(SatuanBarang::class, 'satuan_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }
}
