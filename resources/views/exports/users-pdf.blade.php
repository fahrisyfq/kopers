<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pesanan Siswa</title>
    <style>
        /* Mengatur font dan margin halaman */
        @page {
            margin: 30px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            color: #333;
        }

        /* 1. Header (Logo & Judul) */
        header {
            width: 100%;
            margin-bottom: 20px;
            display: block;
        }
        header::after {
            content: "";
            display: table;
            clear: both;
        }
        .header-logo {
            width: 60px; 
            height: 60px;
            float: left;
            margin-right: 15px;
        }
        .header-title {
            float: left;
        }
        .header-title h1 {
            font-size: 22px;
            font-weight: bold;
            margin: 5px 0;
            color: #003366; 
        }
        .header-title h2 {
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            color: #555;
        }

        /* 2. Footer (Tanggal Cetak & Halaman) */
        footer {
            position: fixed;
            bottom: -20px; 
            left: 0;
            right: 0;
            height: 40px;
            text-align: right;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        /* 3. Tabel Utama */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .main-table th, .main-table td {
            border-bottom: 1px solid #ddd; 
            padding: 9px 8px; 
            text-align: left;
            vertical-align: top; /* [FIX] Ganti ke top agar rata atas */
        }
        .main-table th {
            background-color: #E8F0F7; 
            color: #003366; 
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 10px;
            border-top: 1px solid #ddd;
            border-bottom-width: 2px; 
        }
        .main-table thead {
            display: table-header-group;
        }
        .main-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }

        /* 4. Tabel Produk (di dalam sel) */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: -9px -8px; 
        }
        .product-table th {
            font-size: 9px;
            text-align: left;
            padding: 5px 8px;
            background-color: #f0f0f0; 
            color: #333;
            border-bottom: 1px solid #ccc;
        }
        .product-table td {
            padding: 6px 8px;
            font-size: 10px;
            border-bottom: 1px dashed #ccc; 
        }
        /* [FIX] Hapus border-bottom: none di <tr> terakhir agar tfoot konsisten */
        
        .product-table tfoot td {
            font-weight: bold;
            background-color: #f8f8f8;
            border-top: 1px solid #ccc;
            font-size: 10px;
            padding: 6px 8px; /* Pastikan padding sama */
            border-bottom: none; /* Footer tidak punya border bawah */
        }
        
        /* 5. Teks 'Tidak ada pesanan' */
        .no-order-text {
            padding: 10px 8px;
            font-size: 10px;
            color: #777;
            font-style: italic;
            text-align: center;
        }

        /* 6. Badge Status */
        .status-badge {
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        .preorder-yes {
            color: #b45309; /* Amber */
        }
        .preorder-no {
            color: #15803d; /* Green */
        }
        .payment-paid, .payment-cash {
            color: #15803d; /* Green */
        }
        .payment-pending {
            color: #b91c1c; /* Red */
        }
    </style>
</head>
<body>
    
    <header>
        <img src="{{ public_path('images/logo.jpg') }}" alt="Logo" class="header-logo">
        <div class="header-title">
            <h1>Laporan Pesanan Siswa</h1>
            <h2>Koperasi Sekolah SMKN 8</h2>
        </div>
    </header>

    <footer>
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </footer>

    <main>
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 4%">No</th>
                    <th style="width: 10%">NISN</th>
                    <th style="width: 10%">NIS</th>
                    <th style="width: 18%">Nama Lengkap</th>
                    <th style="width: 8%">Kelas</th>
                    <th style="width: 10%">Jurusan</th>
                    <th style="width: 40%">Daftar Produk Dipesan</th>
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
                        
                        <td style="padding: 0;">
                            @php
                                $allItems = $user->orders->pluck('items')->flatten();
                                $totalPesananSiswa = 0;
                            @endphp

                            <table class="product-table">
                                <thead>
                                    <tr>
                                        <th>Produk (Ukuran)</th>
                                        <th style="width: 25%; text-align:right;">Harga</th>
                                        <th style="width: 28%; text-align:right;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allItems as $item)
                                        @php
                                            $totalPesananSiswa += $item->price ?? 0;
                                            $orderStatus = $item->order->payment_status ?? 'pending';
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product->title ?? '-' }}</strong>
                                                (Ukuran: {{ $item->size->size ?? '-' }})
                                            </td>
                                            <td style="width: 25%; text-align:right;">
                                                Rp{{ number_format($item->price ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td style="width: 28%; text-align:right;">
                                                <span class="status-badge payment-{{ strtolower($orderStatus) }}">
                                                    {{ ucfirst($orderStatus) }}
                                                </span>
                                                <br>
                                                <span class="status-badge {{ $item->is_preorder ? 'preorder-yes' : 'preorder-no' }}">
                                                    Pre-Order: {{ $item->is_preorder ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="no-order-text"><em>Tidak ada pesanan</em></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                
                                {{-- [PERBAIKAN] Tfoot SELALU ditampilkan untuk menjaga tinggi baris --}}
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align: right;">Total Pesanan Siswa:</td>
                                        <td style="text-align: right;">Rp{{ number_format($totalPesananSiswa, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            // Hitung jumlah item pre order (is_preorder aktif & stok <= 0)
                                            $jumlahPreOrder = $allItems->filter(function($item) {
                                                return $item->product && $item->product->is_preorder && $item->product->stock <= 0;
                                            })->count();
                                        @endphp
                                        <td colspan="2" style="text-align: right;">Jumlah Produk Pre-Order:</td>
                                        <td style="text-align: right;">{{ $jumlahPreOrder }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>

</body>
</html>