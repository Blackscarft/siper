<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tutup Buku {{ $namaBulan }} {{ $tahun }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .header { text-align: center; margin-bottom: 30px; }
        .ttd-container { margin-top: 50px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body onload="window.print()"> <!-- Otomatis buka dialog print -->
    <div class="header">
        <h2>LAPORAN SALDO BARANG BULANAN</h2>
        <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Awal</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $i => $d)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $d->barang->kode }}</td>
                <td>{{ $d->barang->nama }}</td>
                <td>{{ $d->stok_awal }}</td>
                <td>{{ $d->stok_masuk }}</td>
                <td>{{ $d->stok_keluar }}</td>
                <td>{{ $d->stok_akhir }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-container">
        <p>Dicetak pada: {{ date('d/m/Y') }}</p>
        <p>Petugas Gudang,</p>
        <br><br><br>
        <p><strong>( ________________ )</strong></p>
    </div>
</body>
</html>