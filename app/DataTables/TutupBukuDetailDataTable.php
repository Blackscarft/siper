<?php
namespace App\DataTables;

use App\Models\SaldoBulanan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TutupBukuDetailDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('barang.nama', fn($row) => $row->barang->nama)
            ->editColumn('stok_awal', fn($row) => number_format($row->stok_awal))
            ->editColumn('stok_masuk', fn($row) => '<span class="text-success">+' . number_format($row->stok_masuk) . '</span>')
            ->editColumn('stok_keluar', fn($row) => '<span class="text-danger">-' . number_format($row->stok_keluar) . '</span>')
            ->editColumn('stok_akhir', fn($row) => '<strong>' . number_format($row->stok_akhir) . '</strong>')
            ->rawColumns(['stok_masuk', 'stok_keluar', 'stok_akhir']);
    }

    public function query(SaldoBulanan $model): QueryBuilder
    {
        // Ambil parameter dari request yang dikirim controller
        return $model->newQuery()
            ->with('barang')
            ->where('bulan', $this->bulan)
            ->where('tahun', $this->tahun)
            ->orderBy('barang_id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('tutupbukudetail-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->ordering(false)
                    ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false),
            Column::make('barang.kode')->title('Kode'),
            Column::make('barang.nama')->title('Nama Barang'),
            Column::make('stok_awal')->title('Awal')->addClass('text-center'),
            Column::make('stok_masuk')->title('Masuk')->addClass('text-center'),
            Column::make('stok_keluar')->title('Keluar')->addClass('text-center'),
            Column::make('stok_akhir')->title('Akhir')->addClass('text-center'),
        ];
    }
}