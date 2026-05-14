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

class StockOpnameTemplateExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
{
    public function collection()
    {
        // Ambil barang yang masih aktif saja
        return Barang::all();
    }

    public function headings(): array
    {
        return ['ID', 'Kode', 'Nama', 'Sistem', 'Fisik', 'Catatan'];
    }

    public function map($barang): array
    {
        return [
            $barang->id,
            $barang->kode,
            $barang->nama,
            strval($barang->stock ?? 0), // Stok saat ini di sistem
            '',            // Kosongkan untuk diisi petugas
            '',            // Kosongkan untuk diisi petugas
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