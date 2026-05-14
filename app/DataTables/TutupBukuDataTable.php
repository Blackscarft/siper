<?php

namespace App\DataTables;

use App\Models\SaldoBulanan;
use App\Models\TutupBuku;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TutupBukuDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($data) {
                return $data->created_at->locale('id')->translatedFormat('d F Y');
            })
            ->addColumn('action', function ($data) {
                $btnDetail = '<a href="'.route('tutup-buku.show', [$data->tahun, $data->bulan]).'" class="btn btn-sm btn-info me-1" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>';
            
                $btnCetak = '<a href="'.route('tutup-buku.pdf', $data->id).'" target="_blank" class="btn btn-sm btn-danger" title="Cetak PDF">
                                <i class="bi bi-printer"></i>
                            </a>';
                
                $btnExcel = '<a href="'.route('tutup-buku.export', $data->id).'" class="btn btn-sm btn-success ms-1" title="Export Excel">
                                <i class="bi bi-file-earmark-excel"></i>
                            </a>';
                
                return '<div class="d-flex">' . $btnDetail . $btnCetak . $btnExcel . '</div>';
            })
            ->rawColumns(['action', 'deleted_at']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SaldoBulanan $model): QueryBuilder
    {
        return $model->newQuery()
            ->selectRaw(
                'MAX(id) as id, 
                bulan, 
                tahun, 
                SUM(stok_masuk) as total_masuk, 
                SUM(stok_keluar) as total_keluar, 
                COUNT(barang_id) as total_barang,
                MAX(created_at) as created_at',
                []
            )
            ->groupBy('bulan', 'tahun')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('tutupbuku-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->autoWidth(false)
                    ->ordering(false)
                    ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('bulan')->title('Bulan')->addClass('align-middle'),
            Column::make('tahun')->title('Tahun')->addClass('align-middle'),
            Column::make('total_masuk')->title('Barang Masuk')->addClass('align-middle'),
            Column::make('total_keluar')->title('Barang Keluar')->addClass('align-middle'),
            Column::make('total_barang')->title('Total Barang')->addClass('align-middle'),
            Column::make('created_at')->title('Tanggal Eksekusi')->addClass('align-middle'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TutupBuku_' . date('YmdHis');
    }
}
