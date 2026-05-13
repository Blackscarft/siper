<?php

namespace App\Http\Controllers;

use App\DataTables\BarangMasukDataTable;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\SatuanBarang;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiMasukDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BarangMasukDataTable $dataTable)
    {
        return $dataTable->render('pages.barang_masuk.index');
    }

    /**
     * Tampilkan Halaman Form Barang Masuk
     */
    public function create()
    {    
        $satuans = SatuanBarang::all();
        $kategori = KategoriBarang::all();
        return view('pages.barang_masuk.create',[
            'satuans' => $satuans,
            'kategories' => $kategori
        ]);
    }

    /**
     * Lihat Detail Riwayat Transaksi
     */
    public function show(int $id)
    {
        // Eager Loading untuk efisiensi query
        $barangMasuk = TransaksiMasuk::with(['admin', 'details.barang'])
                                    ->withCount('details')
                                    ->findOrFail($id);
        return view('pages.barang_masuk.show', compact('barangMasuk'));
    }

    /**
     * API untuk Tambah Master Barang Baru (Quick Add via Modal)
     */
    public function quickStoreBarang(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required',
            'kategori' => 'required',
        ]);

        $countBarang = Barang::withTrashed()->count();
        $incrementedValue = $countBarang + 1;
        $kode = "BRG" . str_pad($incrementedValue, 5, '0', STR_PAD_LEFT);

        $barang = Barang::create([
            'kode' => $kode,
            'nama' => $request->nama_barang,
            'satuan_id' => $request->satuan,
            'kategori_id' => $request->kategori,
            'stok' => 0, // Stok awal selalu 0
        ]);

        // Load relasi agar nama satuan dan kategorinya muncul di JSON
        $barang->load(['satuan', 'kategori']);

        return response()->json([
            'id' => $barang->id,
            'nama' => $barang->nama,
            'kode' => $barang->kode,
            'stok' => $barang->stok,
            'satuan' => $barang->satuan->satuan, // Sesuaikan field 'satuan' di tabel satuan
            'kategori' => $barang->kategori->kategori, // Sesuaikan field 'kategori' di tabel kategori
        ]);
    }

    /**
     * Simpan Transaksi Barang Masuk (Header & Detail)
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $request->validate([
            'no_transaksi' => 'required|unique:transaksi_masuks,no_transaksi',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'required',
            'barang_id' => 'required|array', // Harus ada barang yang diinput
            'jumlah' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan ke Header
            $header = TransaksiMasuk::create([
                'no_transaksi' => $request->no_transaksi,
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
            ]);

            // 2. Looping Simpan Detail & Update Stok
            foreach ($request->barang_id as $key => $idBarang) {
                $jumlah = $request->jumlah[$key];

                // Simpan baris detail
                TransaksiMasukDetail::create([
                    'transaksi_masuk_id' => $header->id,
                    'barang_id' => $idBarang,
                    'jumlah' => $jumlah,
                ]);

                // Update saldo stok di master barang (Penambahan)
                Barang::query()->where('id', $idBarang)->increment('stock', $jumlah);
            }

            DB::commit();
            return redirect()->route('barang-masuk.index')->with('success', 'Transaksi berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function exportPdf(int $id)
    {
        $barangMasuk = TransaksiMasuk::with(['admin', 'details.barang.satuan', 'details.barang.kategori'])
            ->withCount('details')
            ->findOrFail($id);

        // Load view khusus untuk PDF
        $pdf = Pdf::loadView('pages.barang_masuk.pdf', compact('barangMasuk'))
                ->setPaper('a4', 'portrait');

        // Download atau Stream (tampil di browser)
        return $pdf->stream('Detail-Barang-Masuk-' . $barangMasuk->kode_transaksi . '.pdf');
    }
}
