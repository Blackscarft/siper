<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockOpname;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiMasuk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $model = Barang::where('stock', '<=', 5)->with('satuan'); // Kriteria stock menipis
            return datatables()->of($model)
                ->addIndexColumn()
                ->editColumn('stock', function($row) {
                    return '<span class="badge bg-danger">' . $row->stock . '</span>';
                })
                ->rawColumns(['stock'])
                ->make(true);
        }
        $data = [
            'total_barang'   => Barang::count('*'),
            'stock_menipis'  => Barang::where('stock', '<=', 5)->count(),
            'opname_bulan'   => StockOpname::whereMonth('tanggal', date('m'))->count(),
            'barang_keluar'  => TransaksiKeluar::whereMonth('tanggal_keluar', date('m'))->count(),
            'barang_masuk'   => TransaksiMasuk::whereMonth('tanggal_masuk', date('m'))->count(),

            // Data untuk Chart (Contoh: 5 Barang Terlaris/Paling Sering Keluar)
            'chart_labels'   => Barang::take(5)->orderBy('stock', 'desc')->pluck('nama'),
            'chart_values'   => Barang::take(5)->orderBy('stock', 'desc')->pluck('stock'),
        ];

        return view('pages.home.index', $data);
    }
}
