<?php

namespace Database\Seeders;

use App\Models\KategoriBarang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Bahan Makanan/Minuman',
            'Makanan/Minuman',
            'Perlengkapan Kebersihan',
            'Alat Ibadah',
            'Al-Quran'
        ];

        foreach ($kategoris as $kategori) {
            KategoriBarang::create([
                'kategori' => $kategori
            ]);
        }
    }
}
