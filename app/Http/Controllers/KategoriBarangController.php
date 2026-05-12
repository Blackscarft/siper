<?php

namespace App\Http\Controllers;

use App\DataTables\KategoriBarangDataTable;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriBarangController extends Controller
{
    public function index(KategoriBarangDataTable $dataTable)
    {
        return $dataTable->render('pages.master.kategori');
    }

    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'kategori' => 'required',
        ], [
            'kategori.required' => 'Kategori wajib diisi',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors(),
            ], 422);
        }

        KategoriBarang::create([
            'kategori' => $request->kategori
        ]);

        return response()->json(['message' => 'Berhasil manambahan kategori!!!'], 200);
    }

    public function show(string $id)
    {
        $kategoriBarang = KategoriBarang::findOrFail($id);
        return response()->json($kategoriBarang, 200);
    }

    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(), [
            'kategori' => 'required',
        ], [
            'kategori.required' => 'Kategori wajib diisi',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors(),
            ], 422);
        }
        $kategoriBarang = KategoriBarang::findOrFail($id);
        $kategoriBarang->kategori = $request->kategori;
        $kategoriBarang->save();

        return response()->json(['message' => 'Berhasil mengubah kategori!!!'], 200);
    }

    
    public function destroy(string $id)
    {
        $kategoriBarang = KategoriBarang::findOrFail($id);
        $kategoriBarang->delete();

        return response()->json(['message' => 'Berhasil menghapus kategori!!!'], 200);
    }
}
