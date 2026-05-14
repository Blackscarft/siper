<?php
namespace App\DataTables;

use App\Models\StockOpname;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StockOpnameDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('tanggal', fn($row) => \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y'))
            ->addColumn('total_item', fn($row) => $row->details_count . ' Item')
            ->addColumn('action', function($row) {
                return '
                    <div class="d-flex">
                        <a href="'.route('stock-opname.show', $row->id).'" class="btn btn-sm btn-info me-1">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="'.route('stock-opname.pdf', $row->id).'" target="_blank" class="btn btn-sm btn-danger">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>';
            })
            ->rawColumns(['action']);
    }

    public function query(StockOpname $model): QueryBuilder
    {
        // Menggunakan withCount untuk menghitung jumlah item di detail
        return $model->newQuery()->withCount('details');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('stokopname-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No')->searchable(false)->orderable(false),
            Column::make('no_opname')->title('No. Dokumen'),
            Column::make('tanggal')->title('Tanggal'),
            Column::make('total_item')->title('Jumlah Barang'),
            Column::make('keterangan')->title('Keterangan'),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width(100)->addClass('text-center'),
        ];
    }
}