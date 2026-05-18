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
        .text-right { text-align: right; } /* Tambahan untuk merapikan angka */
        .header { text-align: center; margin-bottom: 30px; }
        .ttd-container { margin-top: 50px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body onload="window.print()"> <!-- Otomatis buka dialog print -->
    <div class="header">
        <h2>LAPORAN SALDO BARANG BULANAN</h2>
        <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
    }</div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th class="text-right">Awal</th>
                <th class="text-right">Masuk</th>
                <th class="text-right">Keluar</th>
                <th class="text-right">Selisih Opname</th> <!-- Kolom Baru -->
                <th class="text-right">Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $i => $d)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td>{{ $d->barang->kode }}</td>
                <td>{{ $d->barang->nama }}</td>
                <td class="text-right">{{ number_format($d->stok_awal, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($d->stok_masuk, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($d->stok_keluar, 0, ',', '.') }}</td>
                
                <!-- Menampilkan Selisih Opname (Memberi tanda + jika positif) -->
                <td class="text-right">
                    @if($d->selisih_opname > 0)
                        +{{ number_format($d->selisih_opname, 0, ',', '.') }}
                    @else
                        {{ number_format($d->selisih_opname, 0, ',', '.') }}
                    @endif
                </td>

                <td class="text-right"><strong>{{ number_format($d->stok_akhir, 0, ',', '.') }}</strong></td>
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