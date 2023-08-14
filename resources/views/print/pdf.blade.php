<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales Report</title>
</head>
<style type="text/css">
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    th,
    td {
        border: 1px solid rgb(135, 135, 135);
        padding: 10px;
        text-align: left;
        font-size: 12px;
    }

    th {
        background-color: #f2f2f2;
    }

    h1 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 10px;
    }

    p {
        font-size: 12px;
        margin-bottom: 5px;
    }
</style>

<body>
    <center>
        <h1>LAPORAN PENJUALAN TOKO JAYA MAKMUR SENTOSA</h1>
        <p>Alamat: Kampung Sukasenang, Desa Banyuresmi, Kecamatan Sukahening, Kabupaten
            Tasikmalaya.</p>
        <p>Telp: 0822-4948-6237</p>
        ----------------------------------------------------------------------------------------------------------------------------
    </center>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Created at</th>
                <th>Cashier</th>
                <th>Invoice</th>
                <th>Total</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalSales = 0;
            $totalProfit = 0;
            @endphp
            @foreach ($sales as $sale)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ date_format($sale->created_at, 'd M Y, H:i:s') }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->invoice }}</td>
                <td>{{ 'Rp '. number_format($sale->total, 0, '.', ',') }}</td>
                <td>{{ 'Rp ' . number_format($sale->profit, 0, '.', ',') }}</td>
            </tr>
            @php
            $totalSales += $sale->total; // Menambahkan total penjualan
            $totalProfit += $sale->profit; // Menambahkan total profit
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" style="text-align: right;">Total Keseluruhan:</th>
                <th>{{ 'Rp '. number_format($totalSales, 0, '.', ',') }}</th>
                <th>{{ 'Rp '. number_format($totalProfit, 0, '.', ',') }}</th>
            </tr>
        </tfoot>
    </table>

    <center>
        ----------------------------------------------------------------------------------------------------------------------------
    </center>
</body>

</html>