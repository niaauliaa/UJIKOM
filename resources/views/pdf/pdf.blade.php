<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pembelian</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            color: #333;
        }
        h2, h3 {
            color: #2c3e50;
        }
        p {
            font-size: 14px;
            margin: 5px 0;
        }
        .info-section {
            background-color: #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
        }
        th {
            background-color: #ddd;
            color: white;
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        .total-section {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>LiteMart</h2>

    <div class="info-section">
        <h3>Detail Pembelian</h3>
        <p><strong>Petugas:</strong> {{ $pembelian->user->name ?? '-' }}</p>
        <p><strong>Customer:</strong> {{ $pembelian->customer->name ?? '-' }}</p>
        <p><strong>Tanggal:</strong> {{ $pembelian->created_at->format('d-m-Y H:i') }}</p>
        <p><strong>Sisa Poin:</strong> {{ $pembelian->points }}</p>
    </div>

    <h3>Produk yang Dibeli</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian->details as $detail)
            <tr>
                <td>{{ $detail->produk->name_product ?? '-' }}</td>
                <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td>{{ $detail->qty }}</td>
                <td>Rp {{ number_format($detail->price * $detail->qty, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p><strong>Total Pembelian:</strong> Rp {{ number_format($pembelian->total_price, 0, ',', '.') }}</p>
        <p><strong>Total Pembayaran:</strong> Rp {{ number_format($pembelian->bayar, 0, ',', '.') }}</p>
        <p><strong>Total Pembayaran setelah di potong poin:</strong> Rp {{ number_format($pembelian->total_price - $pembelian->used_points, 0, ',', '.') }}</p>
        <p><strong>Poin yang digunakan:</strong> Rp {{ number_format($pembelian->used_points, 0, ',', '.') }}</p>
        <p><strong>Kembalian:</strong> Rp {{ number_format($pembelian->change, 0, ',', '.') ?? '-' }}</p>
    </div>
</body>
</html>

