<?php

namespace App\DataTables;

use App\Models\BarangKeluar;
use App\Models\TransaksiKeluar;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BarangKeluarDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('details', function($data) {
                $detailUrl = route('barang-keluar.show', $data->id);
                return '<a href="'.$detailUrl.'" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail"><i class="bi bi-eye"></i></a>';
            })
            ->rawColumns(['details']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TransaksiKeluar $model): QueryBuilder
    {
        return $model->newQuery()->with(['admin', 'details'])->withCount('details as jumlah_barang')->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('barangkeluar-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->autoWidth(false)
                    //->dom('Bfrtip')
                    ->ordering(false)
                    ->selectStyleSingle();
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('no_transaksi')->title('Nomor')->searchable(false)->orderable(false),
            Column::make('tanggal_keluar')->title('Tanggal Keluar'),
            Column::make('keterangan'),
            Column::make('jumlah_barang')->title('Jumlah Barang'),
            Column::make('admin.name')->title('Admin'),
            Column::computed('details')->title('Detail'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'BarangKeluar_' . date('YmdHis');
    }
}
