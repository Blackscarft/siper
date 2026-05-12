<?php

namespace App\Http\Controllers;

use App\DataTables\SatuanBarangDataTable;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanBarangController extends Controller
{
    public function index(SatuanBarangDataTable $dataTable)
    {
        return $dataTable->render('pages.master.satuan');
    }

    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'satuan' => 'required',
        ], [
            'satuan.required' => 'Satuan wajib diisi',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors(),
            ], 422);
        }

        SatuanBarang::create([
            'satuan' => $request->satuan
        ]);

        return response()->json(['message' => 'Berhasil manambahan satuan!!!'], 200);
    }

    public function show(string $id)
    {
        $satuanBarang = SatuanBarang::findOrFail($id);
        return response()->json($satuanBarang, 200);
    }

    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(), [
            'satuan' => 'required',
        ], [
            'satuan.required' => 'Satuan wajib diisi',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'errors' => $validateData->errors(),
            ], 422);
        }
        $satuanBarang = SatuanBarang::findOrFail($id);
        $satuanBarang->satuan = $request->satuan;
        $satuanBarang->save();

        return response()->json(['message' => 'Berhasil mengubah satuan!!!'], 200);
    }

    
    public function destroy(string $id)
    {
        $satuanBarang = SatuanBarang::findOrFail($id);
        $satuanBarang->delete();

        return response()->json(['message' => 'Berhasil menghapus satuan!!!'], 200);
    }
}
