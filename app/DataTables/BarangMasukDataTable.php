<?php

namespace App\DataTables;

use App\Models\BarangMasuk;
use App\Models\TransaksiMasuk;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class BarangMasukDataTable extends DataTable
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
                $detailUrl = route('barang-masuk.show', $data->id);
                return '<a href="'.$detailUrl.'" class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail"><i class="bi bi-eye"></i></a>';
            })
            ->editColumn('tanggal_masuk', function($data) {
                return Carbon::parse($data->tanggal_masuk)->format('d-m-Y');
            })
            ->rawColumns(['details']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TransaksiMasuk $model): QueryBuilder
    {
        $query = $model->newQuery()->with(['admin', 'details'])->withCount('details as jumlah_barang')->orderBy('created_at', 'desc');
        if (request()->filled('tanggal_awal') && request()->filled('tanggal_akhir')) {

            $query->whereBetween(
                'tanggal_masuk',
                [
                    request('tanggal_awal'),
                    request('tanggal_akhir')
                ]
            );
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('barangmasuk-table')
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
            Column::make('tanggal_masuk')->title('Tanggal Masuk'),
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
        return 'BarangMasuk_' . date('YmdHis');
    }
}
