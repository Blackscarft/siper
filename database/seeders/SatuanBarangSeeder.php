<?php

namespace Database\Seeders;

use App\Models\SatuanBarang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatuanBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arrSatuan = ['Pcs', 'Box', 'Kg', 'Ltr', 'Meter'];

        foreach ($arrSatuan as $satuan) {
            SatuanBarang::create([
                'satuan' => $satuan
            ]);
        }
    }
}
