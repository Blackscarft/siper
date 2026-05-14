<!DOCTYPE html>
<html>
<head>
    <title>Stock Opname {{ $opname->no_opname }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .info { width: 100%; margin-bottom: 20px; }
        .info td { padding: 3px 0; }
        
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th { background-color: #f2f2f2; border: 1px solid #999; padding: 8px; text-align: center; font-size: 10pt; }
        table.data td { border: 1px solid #999; padding: 6px; font-size: 10pt; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-danger { color: #d9534f; font-weight: bold; }
        .text-success { color: #5cb85c; font-weight: bold; }
        
        .footer { margin-top: 50px; width: 100%; }
        .footer td { width: 33%; text-align: center; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin: 0;">BERITA ACARA STOCK OPNAME</h2>
        <p style="margin: 5px 0;">No. Dokumen: {{ $opname->no_opname }}</p>
    </div>

    <table class="info">
        <tr>
            <td width="15%">Tanggal Opname</td>
            <td width="2%">:</td>
            <td width="33%">{{ \Carbon\Carbon::parse($opname->tanggal)->format('d F Y') }}</td>
            <td width="15%">Dicetak Oleh</td>
            <td width="2%">:</td>
            <td>{{ auth()->user()->name }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>:</td>
            <td colspan="4">{{ $opname->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th width="10%">Sistem</th>
                <th width="10%">Fisik</th>
                <th width="10%">Selisih</th>
                <th width="20%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($opname->details as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->barang->kode }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td class="text-center">{{ $item->stok_sistem }}</td>
                <td class="text-center">{{ $item->stok_fisik }}</td>
                <td class="text-center">
                    @if($item->selisih > 0)
                        <span class="text-success">+{{ $item->selisih }}</span>
                    @elseif($item->selisih < 0)
                        <span class="text-danger">{{ $item->selisih }}</span>
                    @else
                        0
                    @endif
                </td>
                <td>{{ $item->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="footer">
        <tr>
            <td>
                Petugas Gudang,
                <div class="signature-space"></div>
                ( {{ $opname->admin->name }} )
            </td>
            <td>
                Saksi,
                <div class="signature-space"></div>
                ( ________________ )
            </td>
            <td>
                Kepala Gudang,
                <div class="signature-space"></div>
                ( ________________ )
            </td>
        </tr>
    </table>

    <div style="position: fixed; bottom: 0; width: 100%; font-size: 8pt; color: #777;">
        Dicetak pada: {{ $tgl_cetak }} | Dokumen ini dihasilkan secara otomatis oleh sistem.
    </div>

</body>
</html>