<?php

namespace App\Http\Controllers;

use App\DataTables\TutupBukuDataTable;
use App\DataTables\TutupBukuDetailDataTable;
use App\Exports\SaldoBulananExport;
use App\Models\Barang;
use App\Models\SaldoBulanan;
use App\Models\TransaksiKeluarDetail;
use App\Models\TransaksiMasukDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TutupBukuController extends Controller
{
    public function index(TutupBukuDataTable $dataTable) {
        return $dataTable->render('pages.tutup_buku.index');
    }
    
    public function store(Request $request) {
        // Validasi input
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // 1. Cek apakah bulan ini sudah pernah ditutup sebelumnya
        $sudahTutup = SaldoBulanan::query()->where('bulan', $bulan)->where('tahun', $tahun)->exists();
        if ($sudahTutup) {
            return back()->with('error', "Periode " . date('F', mktime(0, 0, 0, $bulan, 1)) . " $tahun sudah ditutup sebelumnya.");
        }

        try {
            DB::transaction(function () use ($bulan, $tahun) {
                // Ambil semua barang
                $barangs = Barang::all();

                foreach ($barangs as $barang) {
                    // Cari saldo bulan lalu untuk stok awal
                    $lastMonth = ($bulan == 1) ? 12 : $bulan - 1;
                    $lastYear = ($bulan == 1) ? $tahun - 1 : $tahun;
                    
                    $saldoLalu = SaldoBulanan::query()->where('barang_id', $barang->id)
                        ->where('bulan', $lastMonth)
                        ->where('tahun', $lastYear)
                        ->first();

                    $stokAwal = $saldoLalu ? $saldoLalu->stok_akhir : 0;

                    // Hitung total masuk periode ini
                    $masuk = TransaksiMasukDetail::whereHas('header', function($q) use ($bulan, $tahun) {
                        $q->whereMonth('tanggal_masuk', $bulan)->whereYear('tanggal_masuk', $tahun);
                    })->where('barang_id', $barang->id)->sum('jumlah');

                    // Hitung total keluar periode ini
                    $keluar = TransaksiKeluarDetail::whereHas('header', function($q) use ($bulan, $tahun) {
                        $q->whereMonth('tanggal_keluar', $bulan)->whereYear('tanggal_keluar', $tahun);
                    })->where('barang_id', $barang->id)->sum('jumlah');

                    // HITUNG SELISIH OPNAME (Penting!)
                    // Jika ada selisih di stok opname bulan tersebut, harus dimasukkan ke kalkulasi
                    // $selisihOpname = StokOpnameDetail::whereHas('header', ...)->sum('selisih');

                    $stokAkhir = ($stokAwal + $masuk) - $keluar;

                    // 2. Gunakan updateOrCreate untuk mencegah duplikasi jika skema DB tidak unik
                    SaldoBulanan::updateOrCreate(
                        [
                            'barang_id' => $barang->id,
                            'bulan'     => $bulan,
                            'tahun'     => $tahun,
                        ],
                        [
                            'stok_awal'  => $stokAwal,
                            'stok_masuk' => $masuk,
                            'stok_keluar'=> $keluar,
                            'stok_akhir' => $stokAkhir,
                        ]
                    );
                }
            });

            return back()->with('success', 'Tutup buku periode ini berhasil diselesaikan.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(int $tahun, int $bulan, TutupBukuDetailDataTable $dataTable)
    {
        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

        // Masukkan parameter ke dalam class DataTable
        return $dataTable->with([
            'bulan' => $bulan,
            'tahun' => $tahun
        ])->render('pages.tutup_buku.show', [
            'tahun' => $tahun,
            'namaBulan' => $namaBulan,
            'pageTitle' => "Detail Tutup Buku $namaBulan $tahun"
        ]);
    }

    public function exportPdf(int $id){
        $saldo = SaldoBulanan::findOrFail($id);
        $tahun = $saldo->tahun;
        $bulan = $saldo->bulan;

        $details = SaldoBulanan::with('barang')
                    ->where('tahun', $tahun)
                    ->where('bulan', $bulan)
                    ->get();

        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

        // Jika ingin langsung print via Browser (tanpa library tambahan)
        return view('pages.tutup_buku.pdf', compact('details', 'tahun', 'bulan', 'namaBulan'));
    }

    public function export(int $id)
    {
        $saldo = SaldoBulanan::findOrFail($id);
        $tahun = $saldo->tahun;
        $bulan = $saldo->bulan;

        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));
        $fileName = "Laporan_Stok_{$namaBulan}_{$tahun}.xlsx";

        return Excel::download(new SaldoBulananExport($tahun, $bulan), $fileName);
    }
}