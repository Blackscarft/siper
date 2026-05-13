<!DOCTYPE html>
<html>
<head>
    <title>Transaksi {{ $barangMasuk->kode_transaksi }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table td { border: 1px solid #ddd; padding: 8px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; }
        .signature { float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN BARANG MASUK</h2>
        <p style="margin:5px 0;">No. Transaksi: {{ $barangMasuk->no_transaksi }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Tanggal</td>
            <td width="2%">:</td>
            <td width="33%">{{ $barangMasuk->created_at->format('d F Y') }}</td>
            <td width="15%">Admin</td>
            <td width="2%">:</td>
            <td width="33%">{{ $barangMasuk->admin->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>:</td>
            <td colspan="4">{{ $barangMasuk->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Kode</th>
                <th>Nama Barang</th>
                <th class="text-center" width="10%">Qty</th>
                <th width="10%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangMasuk->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->barang->kode }}</td>
                <td>{{ $detail->barang->nama }}</td>
                <td class="text-center">{{ $detail->jumlah }}</td>
                <td>{{ $detail->barang->satuan->satuan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="3" class="text-right">TOTAL ITEM</td>
                <td class="text-center">{{ $barangMasuk->details->sum('jumlah') }}</td>
                <td>Unit</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
            <br><br><br>
            <p><strong>( ________________ )</strong><br>Admin Gudang</p>
        </div>
    </div>
</body>
</html>