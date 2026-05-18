<?php

namespace App\DataTables;

use App\Models\SaldoBulanan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
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
            ->editColumn('bulan', function ($data) {
                // Mengubah angka bulan menjadi nama bulan Indonesia (Contoh: 1 -> Januari)
                return \Carbon\Carbon::createFromFormat('m', $data->bulan)->locale('id')->translatedFormat('F');
            })
            ->editColumn('total_selisih_opname', function ($data) {
                // Memberikan penanda warna atau simbol + / - supaya mudah dibaca
                if ($data->total_selisih_opname > 0) {
                    return '<span class="badge bg-success">+' . number_format($data->total_selisih_opname, 0, ',', '.') . '</span>';
                } elseif ($data->total_selisih_opname < 0) {
                    return '<span class="badge bg-danger">' . number_format($data->total_selisih_opname, 0, ',', '.') . '</span>';
                }
                return '<span class="text-muted">0</span>';
            })
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
            ->rawColumns(['action', 'total_selisih_opname']); // Tambahkan total_selisih_opname ke rawColumns agar badge HTML bisa dirender
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
                SUM(selisih_opname) as total_selisih_opname, -- Ambil sum total selisih opname
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
            Column::make('total_barang')->title('Total Jenis Barang')->addClass('align-middle'),
            Column::make('total_masuk')->title('Barang Masuk')->addClass('align-middle'),
            Column::make('total_keluar')->title('Barang Keluar')->addClass('align-middle'),
            Column::make('total_selisih_opname')->title('Total Selisih Opname')->addClass('align-middle text-center'), // Kolom Informasi Opname Baru
            Column::make('created_at')->title('Tanggal Eksekusi')->addClass('align-middle'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center align-middle'),
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