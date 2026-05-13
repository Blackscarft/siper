<?php

namespace App\Http\Controllers;

use App\DataTables\BarangDataTable;
use App\Exports\BarangExport;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index(BarangDataTable $dataTable)
    {
        $satuan = SatuanBarang::all();
        $kategori = KategoriBarang::all();
        return $dataTable->render('pages.master.barang', [
            'satuans' => $satuan,
            'kategories' => $kategori,
        ]);
    }

    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'nama' => 'required',
            'satuan' => 'required',
            'kategori' => 'required',
        ], [
            'nama.required' => 'Nama barang wajib diisi',
            'satuan.required' => 'Satuan barang wajib diisi',
            'kategori.required' => 'Kategori barang wajib diisi',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors(),
            ], 422);
        }

        $countBarang = Barang::withTrashed()->count();
        $incrementedValue = $countBarang + 1;
        $kode = "BRG" . str_pad($incrementedValue, 5, '0', STR_PAD_LEFT);

        Barang::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'satuan_id' => $request->satuan,
            'kategori_id' => $request->kategori,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'message' => 'Berhasil menambahkan barang!!!'
        ]);
    }

    public function show(string $id)
    {
        $barang = Barang::findOrFail($id);
        return response()->json($barang, 200);
    }

    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(), [
            'nama' => 'required',
            'satuan' => 'required',
            'kategori' => 'required',
        ], [
            'nama.required' => 'Nama barang wajib diisi',
            'satuan.required' => 'Satuan wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors(),
            ], 422);
        }
        $barang = Barang::findOrFail($id);
        $barang->nama = $request->nama;
        $barang->satuan_id = $request->satuan;
        $barang->kategori_id = $request->kategori;
        $barang->save();

        return response()->json(['message' => 'Berhasil mengubah barang!!!'], 200);
    }

    public function destroy(string $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json(['message' => 'Berhasil menghapus barang!!!'], 200);
    }

    public function export()
    {
        return Excel::download(new BarangExport, 'barang.xlsx');
    }
}
