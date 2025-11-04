<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa & Pesanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .preorder-yes {
            color: #d97706;
            font-weight: bold;
        }
        .preorder-no {
            color: #16a34a;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Data Siswa & Pesanan</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 10%">NISN</th>
                <th style="width: 10%">NIS</th>
                <th style="width: 18%">Nama Lengkap</th>
                <th style="width: 8%">Kelas</th>
                <th style="width: 10%">Jurusan</th>
                <th style="width: 40%">Daftar Produk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user->nisn }}</td>
                    <td>{{ $user->nis }}</td>
                    <td>{{ $user->nama_lengkap }}</td>
                    <td class="text-center">{{ $user->kelas }}</td>
                    <td class="text-center">{{ $user->jurusan }}</td>
                    <td>
                        @forelse($user->orders as $order)
                            @foreach($order->items as $item)
                                <div>
                                    • <strong>{{ $item->product->title ?? '-' }}</strong>
                                    (Ukuran: {{ $item->size->size ?? '-' }})
                                    <br>
                                    Harga: Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}
                                    <br>
                                    Status Pembayaran: {{ ucfirst($order->payment_status ?? '-') }}
                                    <br>
                                    <span class="{{ $item->is_preorder ? 'preorder-yes' : 'preorder-no' }}">
                                        Pre-Order: {{ $item->is_preorder ? 'Ya' : 'Tidak' }}
                                    </span>
                                </div>
                                <hr style="border: 0.5px dashed #bbb;">
                            @endforeach
                        @empty
                            <em>Tidak ada pesanan</em>
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="text-align:right; font-size: 10px; color:#555;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </p>
</body>
</html>
