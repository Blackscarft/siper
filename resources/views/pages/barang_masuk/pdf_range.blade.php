<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Masuk</title>

    <style>
        body{
            font-family:sans-serif;
            font-size:12px;
            color:#333;
            line-height:1.5;
        }

        .header{
            text-align:center;
            border-bottom:2px solid #444;
            padding-bottom:10px;
            margin-bottom:20px;
        }

        .header h2{
            margin:0;
        }

        .header p{
            margin:5px 0;
        }

        .info-table{
            width:100%;
            margin-bottom:20px;
        }

        .info-table td{
            padding:4px;
        }

        .items-table{
            width:100%;
            border-collapse:collapse;
        }

        .items-table th{
            border:1px solid #ccc;
            background:#f2f2f2;
            padding:7px;
        }

        .items-table td{
            border:1px solid #ccc;
            padding:6px;
        }

        .detail-table{
            width:100%;
            border-collapse:collapse;
            margin:5px 0 12px 0;
        }

        .detail-table th{
            background:#fafafa;
            border:1px solid #ddd;
            padding:5px;
            font-size:11px;
        }

        .detail-table td{
            border:1px solid #ddd;
            padding:5px;
            font-size:11px;
        }

        .text-center{
            text-align:center;
        }

        .text-right{
            text-align:right;
        }

        .footer{
            margin-top:50px;
        }

        .signature{
            width:220px;
            float:right;
            text-align:center;
        }

        .summary{
            background:#f8f8f8;
            font-weight:bold;
        }
    </style>

</head>

<body>

<div class="header">

    <h2>LAPORAN BARANG MASUK</h2>

    <p>
        Periode
        {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }}
        s/d
        {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
    </p>

</div>

<table class="info-table">

    <tr>

        <td width="18%">Jumlah Transaksi</td>
        <td width="2%">:</td>
        <td>{{ $totalTransaksi }}</td>

        <td width="18%">Jumlah Barang</td>
        <td width="2%">:</td>
        <td>{{ $totalBarang }}</td>

    </tr>

    <tr>

        <td>Jumlah Item</td>
        <td>:</td>
        <td>{{ $totalItem }}</td>

        <td></td>
        <td></td>
        <td></td>

    </tr>

</table>


<table class="items-table">

<thead>

<tr>

    <th width="5%">No</th>
    <th width="18%">No Transaksi</th>
    <th width="15%">Tanggal</th>
    <th>Admin</th>
    <th width="12%">Jenis Barang</th>
    <th width="12%">Qty</th>

</tr>

</thead>

<tbody>

@foreach($barangMasuk as $index => $transaksi)

<tr class="summary">

    <td class="text-center">
        {{ $index+1 }}
    </td>

    <td>
        {{ $transaksi->no_transaksi }}
    </td>

    <td class="text-center">
        {{ $transaksi->created_at->format('d-m-Y') }}
    </td>

    <td>
        {{ $transaksi->admin->name ?? '-' }}
    </td>

    <td class="text-center">
        {{ $transaksi->details->count() }}
    </td>

    <td class="text-center">
        {{ $transaksi->details->sum('jumlah') }}
    </td>

</tr>

<tr>

<td></td>

<td colspan="5" style="padding:0">

<table class="detail-table">

<thead>

<tr>

    <th width="5%">No</th>
    <th width="18%">Kode</th>
    <th>Nama Barang</th>
    <th width="12%">Qty</th>
    <th width="15%">Satuan</th>

</tr>

</thead>

<tbody>

@foreach($transaksi->details as $i => $detail)

<tr>

    <td class="text-center">
        {{ $i+1 }}
    </td>

    <td>
        {{ $detail->barang->kode }}
    </td>

    <td>
        {{ $detail->barang->nama }}
    </td>

    <td class="text-center">
        {{ $detail->jumlah }}
    </td>

    <td class="text-center">
        {{ $detail->barang->satuan->satuan ?? '-' }}
    </td>

</tr>

@endforeach

</tbody>

</table>

</td>

</tr>

@endforeach

</tbody>

<tfoot>

<tr style="font-weight:bold;background:#efefef;">

    <td colspan="4" class="text-right">
        GRAND TOTAL
    </td>

    <td class="text-center">
        {{ $totalBarang }}
    </td>

    <td class="text-center">
        {{ $totalItem }}
    </td>

</tr>

</tfoot>

</table>

<div class="footer">

<div class="signature">

    <p>
        Dicetak pada :
        {{ now()->format('d/m/Y H:i') }}
    </p>

    <br><br><br>

    <strong>( ____________________ )</strong>

    <br>

    Admin Gudang

</div>

</div>

</body>
</html>