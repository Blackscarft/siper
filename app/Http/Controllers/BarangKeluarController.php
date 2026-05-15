<?php

namespace App\Http\Controllers;

use App\DataTables\BarangKeluarDataTable;
use App\Models\Barang;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiKeluarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BarangKeluarController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // 1. Pastikan user setidaknya punya role 'admin' ATAU 'manager' untuk masuk ke halaman ini
            new Middleware('role_or_permission:admin|manager', only: ['index', 'show', 'exportPdf']),

             // 2. Batasi fungsi manipulasi data (create,store, update, destroy) HANYA untuk role 'admin'
            // Manager otomatis tertolak karena tidak ada di daftar ini
            new Middleware('role:admin', only: ['create', 'store']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BarangKeluarDataTable $dataTable)
    {
        return $dataTable->render('pages.barang_keluar.index');
    }

    /**
     * Tampilkan Halaman Form Barang Keluar
     */
    public function create()
    {    
        return view('pages.barang_keluar.create');
    }

    /**
     * Lihat Detail Riwayat Transaksi
     */
    public function show(int $id)
    {
        // Eager Loading untuk efisiensi query
        $barangKeluar = TransaksiKeluar::with(['admin', 'details.barang'])
                                    ->withCount('details')
                                    ->findOrFail($id);
        return view('pages.barang_keluar.show', compact('barangKeluar'));
    }

    /**
     * Simpan Transaksi Barang Masuk (Header & Detail)
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'no_transaksi' => 'required|unique:transaksi_masuks,no_transaksi',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'required',
            'barang_id' => 'required|array', // Harus ada barang yang diinput
            'jumlah' => [
                    'required',
                    'array',
                    function ($attribute, $value, $fail) use ($request) {
                        foreach ($value as $index => $qty) {
                            $idBarang = $request->barang_id[$index] ?? null;
                            if ($idBarang) {
                                $barang = \App\Models\Barang::findOrFail($idBarang);
                                
                                if ($barang && $qty > $barang->stock) {
                                    $fail("Jumlah untuk barang {$barang->nama} melebihi stok yang tersedia (Maksimal: {$barang->stok}).");
                                }
                            }
                        }
                    },
                ],
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan ke Header
            $header = TransaksiKeluar::create([
                'no_transaksi' => $request->no_transaksi,
                'tanggal_keluar' => $request->tanggal_keluar,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
            ]);

            // 2. Looping Simpan Detail & Update Stok
            foreach ($request->barang_id as $key => $idBarang) {
                $jumlah = $request->jumlah[$key];

                // Simpan baris detail
                TransaksiKeluarDetail::create([
                    'transaksi_keluar_id' => $header->id,
                    'barang_id' => $idBarang,
                    'jumlah' => $jumlah,
                ]);

                // Update saldo stok di master barang (Pengurangan)
                Barang::query()->where('id', $idBarang)->decrement('stock', $jumlah);
            }

            DB::commit();
            return redirect()->route('barang-keluar.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    
    public function exportPdf(int $id)
    {
        $barangKeluar = TransaksiKeluar::with(['admin', 'details.barang.satuan', 'details.barang.kategori'])
            ->withCount('details')
            ->findOrFail($id);

        // Load view khusus untuk PDF
        $pdf = Pdf::loadView('pages.barang_keluar.pdf', compact('barangKeluar'))
                ->setPaper('a4', 'portrait');

        // Download atau Stream (tampil di browser)
        return $pdf->stream('Detail-Barang-Keluar-' . $barangKeluar->kode_transaksi . '.pdf');
    }
}
