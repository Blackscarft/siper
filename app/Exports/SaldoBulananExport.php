<?php

namespace App\Exports;

use App\Models\SaldoBulanan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SaldoBulananExport implements FromView, ShouldAutoSize
{
    protected int $bulan;
    protected int $tahun;

    public function __construct(int $tahun, int $bulan) {
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function view(): View
    {
        $details = SaldoBulanan::with('barang')
            ->where('tahun', $this->tahun)
            ->where('bulan', $this->bulan)
            ->get();

        // Hitung rekapan
        $rekap = [
            'total_awal'     => $details->sum('stok_awal'),
            'total_masuk'    => $details->sum('stok_masuk'),
            'total_keluar'   => $details->sum('stok_keluar'),
            'total_opname'   => $details->sum('selisih_opname'),
            'total_akhir'    => $details->sum('stok_akhir'),
            'jumlah_item'    => $details->count(),
        ];

        // Mengubah angka bulan menjadi nama bulan (Bahasa Inggris bawaan code lama Anda)
        $namaBulan = date('F', mktime(0, 0, 0, $this->bulan, 1));

        return view('pages.tutup_buku.excel', [
            'details'   => $details,
            'rekap'     => $rekap,
            'namaBulan' => $namaBulan,
            'tahun'     => $this->tahun
        ]);
    }
}