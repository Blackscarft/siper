<table>
    <!-- REKAPAN DATA DI ATAS -->
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; font-size: 14px;">REKAPITULASI STOK BULANAN</th>
        </tr>
        <tr>
            <th colspan="4">Periode: {{ $namaBulan }} {{ $tahun }}</th>
        </tr>
        <tr></tr> <!-- Baris kosong -->
        <tr>
            <td style="background-color: #f2f2f2; font-weight: bold;">Total Barang</td>
            <td style="background-color: #f2f2f2;">{{ $rekap['jumlah_item'] }} Item</td>
        </tr>
        <tr>
            <td style="background-color: #f2f2f2; font-weight: bold;">Total Stok Awal</td>
            <td style="background-color: #f2f2f2;">{{ $rekap['total_awal'] }}</td>
        </tr>
        <tr>
            <td style="background-color: #f2f2f2; font-weight: bold;">Total Masuk (+)</td>
            <td style="background-color: #f2f2f2;">{{ $rekap['total_masuk'] }}</td>
        </tr>
        <tr>
            <td style="background-color: #f2f2f2; font-weight: bold;">Total Keluar (-)</td>
            <td style="background-color: #f2f2f2;">{{ $rekap['total_keluar'] }}</td>
        </tr>
        <!-- 1. Tambahan Rekap Selisih Opname -->
        <tr>
            <td style="background-color: #f2f2f2; font-weight: bold;">Total Selisih Opname (+/-)</td>
            <td style="background-color: #f2f2f2;">
                @if($rekap['total_opname'] > 0)
                    +{{ $rekap['total_opname'] }}
                @else
                    {{ $rekap['total_opname'] }}
                @endif
            </td>
        </tr>
        <tr>
            <td style="background-color: #f2f2f2; font-weight: bold;">Saldo Akhir Keseluruhan</td>
            <td style="background-color: #f2f2f2; font-weight: bold;">{{ $rekap['total_akhir'] }}</td>
        </tr>
    </thead>

    <tr></tr> <!-- Baris kosong pemisah -->

    <!-- LOOPING DATA BARANG -->
    <thead>
        <tr>
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">No</th>
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">Kode</th>
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">Nama Barang</th>
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">Awal</th>
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">Masuk</th>
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">Keluar</th>
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">Selisih Opname</th> <!-- 2. Header Kolom Baru -->
            <th style="background-color: #000000; color: #ffffff; font-weight: bold; border: 1px solid #000;">Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($details as $index => $item)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000;">{{ $item->barang->kode }}</td>
            <td style="border: 1px solid #000;">{{ $item->barang->nama }}</td>
            <td style="border: 1px solid #000;">{{ $item->stok_awal ?? 0 }}</td>
            <td style="border: 1px solid #000;">{{ $item->stok_masuk ?? 0 }}</td>
            <td style="border: 1px solid #000;">{{ $item->stok_keluar ?? 0 }}</td>
            
            <!-- 3. Data Kolom Selisih Opname Baru -->
            <td style="border: 1px solid #000;">
                @if(($item->selisih_opname ?? 0) > 0)
                    +{{ $item->selisih_opname }}
                @else
                    {{ $item->selisih_opname ?? 0 }}
                @endif
            </td>
            
            <td style="border: 1px solid #000; font-weight: bold;">{{ $item->stok_akhir ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>