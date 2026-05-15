<?php

namespace App\DataTables;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class BarangDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return $data->created_at->locale('id')->translatedFormat('d F Y');
            })
            ->editColumn('stock', fn($row) => '<span class="badge '. ($row->stock <= 5 ? 'bg-light-danger' : 'bg-light-success') .'">' . number_format($row->stock) . '</span>' )
            ->editColumn('catatan', fn($row) => $row->catatan == null ? '-' : $row->catatan)
            ->editColumn('deleted_at', function ($data) {
                $bedge = '';
                if ($data->deleted_at == null) {
                    $bedge = '<span class="badge bg-light-success">Active</span>';
                } else {
                    $bedge = '<span class="badge bg-light-warning">Inactive</span>';
                }

                return $bedge;
            })
            ->addColumn('action', function ($data) {
                if (!$data->deleted_at) {
                    $actionBtn = <<<EOL
                <div class="d-flex">
                    <button
                        class="btn btn-outline-primary btn-sm"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Edit data"
                        id="btn-edit"
                        onclick="getData($data->id)"
                    >
                    <i class="bi bi-pencil-square"></i>
                    </button>

                    <button
                        class="btn btn-outline-danger btn-sm ms-1"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Hapus data"
                        onclick="remove($data->id)"
                        id="btn-hapus"
                    >
                    <i class="bi bi-trash"></i></button>
                </div>
                EOL;
                } else {
                    $actionBtn = '<span class="badge bg-light-warning">Deleted</span>';
                }

                return $actionBtn;
            })
            ->rawColumns(['action', 'stock']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Barang $model): QueryBuilder
    {
        return $model->newQuery()->with(['satuan', 'kategori'])->withTrashed();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('barangs-table')
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
        $columns = [
            Column::make('kode')->title('Kode'),
            Column::make('nama'),
            Column::make('kategori.kategori')->title('Kategori'),
            Column::make('satuan.satuan')->title('Satuan'),
            Column::make('stock')->title('Stock'),
            Column::make('catatan')
        ];

        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $columns[] = Column::computed('action')
                            ->exportable(false)
                            ->printable(false)
                            ->width(60)
                            ->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Barang_' . date('YmdHis');
    }
}
