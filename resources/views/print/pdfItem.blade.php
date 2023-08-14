<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 30%;
            margin: 5px auto;
            padding: 5px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 0;
            left: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .address {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .divider {
            border-top: 1px solid #ccc;
            margin: 5px 0;
        }

        .left {
            text-align: left;
            font-size: 12px;
        }

        .right {
            text-align: right;
            font-size: 12px;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 12px;
        }

        h3 {
            font-size: 14px;
            margin-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            font-size: 12px;
            text-align: left;
        }

        /* Custom style for padding on certain elements */
        .padding {
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h3>TOKO JAYA MAKMUR SENTOSA</h3>
            <div class="address">Alamat: Kampung Sukasenang, Desa Banyuresmi, Kecamatan Sukahening, Kabupaten
                Tasikmalaya.</div>
            <div class="address">Telp: 0822-4948-6237</div>
        </div>
        <div class="divider"></div>

        <div class="left">
            <div class="padding">Tanggal: {{ date_format($record->created_at, 'd M Y, H:i:s') }}</div>
            <div class="padding">Invoice: {{ $record->invoice }}</div>
            <div class="padding">Kasir: {{ $record->user->name }}</div>
        </div>
        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th class="right">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($record->saleItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="right">{{ 'Rp ' . number_format($item->price, 0, '.', ',') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>
        <div class="right">
            <div class="padding">Total: {{ 'Rp ' . number_format($record->total, 0, '.', ',') }}</div>
            <div class="padding">Discount: {{ 'Rp ' . number_format($record->discount, 0, '.', ',') }}</div>
            <div class="divider"></div>
            <div class="padding">Payment: {{ 'Rp ' . number_format($record->payment, 0, '.', ',') }}</div>
            <div class="padding">Change: {{ 'Rp ' . number_format($record->change, 0, '.', ',') }}</div>
        </div>
        <div class="divider"></div>

        <div class="footer">
            Terima kasih atas kunjungan Anda.
        </div>
    </div>
</body>

</html>