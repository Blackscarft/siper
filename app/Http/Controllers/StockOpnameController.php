<?php

namespace App\Http\Controllers;

use App\Exports\StockOpnameTemplateExport;
use App\Models\Barang;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\DataTables\StockOpnameDataTable;
use Barryvdh\DomPDF\Facade\Pdf;

class StockOpnameController extends Controller
{
    public function index(StockOpnameDataTable $dataTable){
        return $dataTable->render('pages.stock_opname.index');
    }

    public function create(){
        return view('pages.stock_opname.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'no_opname' => 'required|unique:stock_opnames,no_opname',
            'tanggal'   => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'stok_fisik' => 'required|array',
            'stok_fisik.*' => 'integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 2. Simpan Header Opname
            $opname = StockOpname::create([
                'no_opname'  => $request->no_opname,
                'tanggal'    => $request->tanggal,
                'keterangan' => $request->keterangan,
                'user_id'    => Auth::id(), // Mencatat siapa yang melakukan opname
            ]);

            // 3. Loop Detail Barang
            foreach ($request->barang_id as $key => $barangId) {
                $stokSistem = $request->stok_sistem[$key];
                $stokFisik  = $request->stok_fisik[$key];
                $selisih    = $stokFisik - $stokSistem;
                $catatan    = $request->catatan[$key];

                // Simpan ke tabel detail untuk audit trail
                $opname->details()->create([
                    'barang_id'   => $barangId,
                    'stok_sistem' => $stokSistem,
                    'stok_fisik'  => $stokFisik,
                    'selisih'     => $selisih,
                    'catatan'     => $catatan,
                ]);

                // 4. Update Stok di Master Barang
                // Stok sistem harus dipaksa mengikuti Stok Fisik hasil temuan lapangan
                $barang = Barang::findOrFail($barangId);
                $barang->update([
                    'stock' => $stokFisik
                ]);
            }

            DB::commit();
            return redirect()->route('stock-opname.index')
                                ->with('success', 'Stock Opname berhasil difinalisasi. Stok barang telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(int $id)
    {
        $opname = StockOpname::with(['details.barang', 'admin'])->findOrFail($id);
        return view('pages.stock_opname.show', compact('opname'));
    }

    public function exportTemplate()
    {
        return Excel::download(new StockOpnameTemplateExport, 'template-opname-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(int $id)
    {
        $opname = StockOpname::with(['details.barang', 'admin'])->findOrFail($id);
        
        $data = [
            'opname' => $opname,
            'tgl_cetak' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('pages.stock_opname.pdf', $data)
                ->setPaper('a4', 'portrait');

        return $pdf->stream("Stock_Opname_{$opname->no_opname}.pdf");
    }

    
}
