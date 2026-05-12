<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class BarangExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $barang = Barang::withTrashed()
                            ->with(['satuan', 'kategori'])
                            ->get();
        return $barang;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Barang',
            'Nama Barang',
            'Stok',
            'Kategori',
            'Satuan',
            'Created At',
            'Updated At',
            'Status',
        ];
    }

    public function map($barang): array
    {
        return [
            $barang->id,
            $barang->kode,
            $barang->nama,
            $barang->stok ?? 0,
            $barang->kategori->kategori ?? '',
            $barang->satuan->satuan ?? '',
            $barang->created_at,
            $barang->updated_at,
            $barang->deleted_at ? 'Deleted' : 'Active',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
