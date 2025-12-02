<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Serah Terima Barang</title>
    <style>
        /* --- RESET & BASE TYPOGRAPHY --- */
        body {
            /* Kembali ke font Arial/klasik yang tegas dan jelas saat dicetak */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 30px 40px; /* Margin kertas */
        }

        /* --- UTILITIES --- */
        .w-full { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        /* Warna Emerald Koperasi */
        .text-emerald { color: #047857; }
        .bg-emerald-light { background-color: #ecfdf5; } /* Latar belakang hijau sangat muda */
        .border-emerald { border-color: #047857; }
        
        /* --- HEADER SECTION --- */
        .header-container {
            border-bottom: 3px solid #047857; /* Garis tebal */
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 22px;
            font-weight: 900; /* Sangat tebal */
            color: #047857;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .company-info {
            font-size: 11px;
            margin-top: 5px;
            line-height: 1.5;
            color: #444;
        }
        
        /* Judul Dokumen di Kanan */
        .document-title-box {
            text-align: right;
            padding-left: 20px;
        }
        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #111;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .document-date {
            font-size: 11px;
            color: #555;
        }

        /* --- INFO BOXES (SISWA) --- */
        .info-container {
            margin-bottom: 25px;
        }
        .info-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #000;
        }
        .info-sub {
            font-size: 12px;
            color: #333;
            margin-top: 3px;
        }

        /* --- ITEMS TABLE --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            text-align: left;
            padding: 12px 8px;
            background-color: #047857; /* Header Solid Hijau */
            color: white;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }
        /* Zebra Striping agar mudah dibaca */
        .items-table tr:nth-child(even) {
            background-color: #f9fafb; 
        }
        .items-table tr:last-child td {
            border-bottom: 2px solid #047857;
        }

        /* --- NEW TOTAL SECTION DESIGN --- */
        .total-container-wrapper {
            width: 100%;
            margin-top: 10px;
            display: flex; /* Untuk PDF engine modern */
        }
        /* Kotak Total yang "Lebih Bagus" */
        .total-box-styled {
            width: 45%; /* Lebar kotak */
            margin-left: auto; /* Rata kanan */
            background-color: #ecfdf5; /* Latar hijau muda segar */
            border: 2px solid #047857; /* Border tegas */
            border-radius: 8px; /* Sudut membulat */
            padding: 15px;
        }
        .total-row-table {
            width: 100%;
            border-collapse: collapse;
        }
        .grand-total-label {
            font-size: 13px;
            font-weight: bold;
            color: #065f46; /* Hijau tua */
            text-align: right;
            padding-right: 15px;
            vertical-align: middle;
        }
        .grand-total-value {
            font-size: 22px; /* Ukuran besar */
            font-weight: 900; /* Sangat tebal */
            color: #047857;
            text-align: right;
            margin: 0;
            line-height: 1;
        }

        /* --- SIGNATURE --- */
        .signature-wrapper {
            margin-top: 60px;
            width: 100%;
        }
        .sig-box {
            text-align: center;
            width: 35%;
        }
        .sig-role {
            font-size: 11px;
            font-weight: bold;
            color: #444;
            margin-bottom: 70px; /* Ruang Tanda Tangan Luas */
        }
        .sig-name {
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid #ccc; /* Garis tanda tangan lebih tegas */
            padding-bottom: 5px;
        }

        /* --- FOOTER NOTES --- */
        .footer-notes {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
            font-size: 10px;
            color: #666;
            font-style: italic;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    {{-- 1. HEADER --}}
    <table class="w-full header-container">
        <tr>
            {{-- Kiri: Info Sekolah --}}
            <td width="60%" style="vertical-align: top;">
                <h1 class="company-name">KOPERASI SMKN 8 JAKARTA</h1>
                <div class="company-info">
                    Jl. Pejaten Raya, RT.7/RW.6, Pejaten Barat, Ps. Minggu<br>
                    Jakarta Selatan, DKI Jakarta 12510<br>
                    Telp: (021) 79xxxxxx | Email: info@smkn8jkt.sch.id
                </div>
            </td>
            
            {{-- Kanan: Judul & Tanggal --}}
            <td width="40%" class="document-title-box" style="vertical-align: top;">
                <div class="document-title">BUKTI SERAH TERIMA</div>
                <div class="document-date">
                    Tanggal: <strong>{{ now()->setTimezone('Asia/Jakarta')->format('d F Y') }}</strong><br>
                    Pukul: {{ now()->setTimezone('Asia/Jakarta')->format('H:i') }} WIB
                </div>
            </td>
        </tr>
    </table>

    {{-- 2. INFO SISWA --}}
    <table class="w-full info-container">
        <tr>
            {{-- Kiri: Nama & NISN/NIS --}}
            <td width="60%" style="vertical-align: top;">
                <div class="info-label">Siswa Penerima</div>
                <div class="info-value" style="font-size: 16px;">{{ strtoupper($user->nama_lengkap) }}</div>
                
                {{-- NISN dan NIS (Posisi sesuai request sebelumnya) --}}
                <div class="info-sub">
                    NISN: <strong>{{ $user->nisn }}</strong> &nbsp;|&nbsp; NIS: <strong>{{ $user->nis ?? '-' }}</strong>
                </div>
            </td>

            {{-- Kanan: Kelas & Jurusan --}}
            <td width="40%" class="text-right" style="vertical-align: top;">
                <div class="info-label">Kelas / Jurusan</div>
                <div class="info-value" style="font-size: 16px;">{{ $user->kelas }} - {{ $user->jurusan }}</div>
            </td>
        </tr>
    </table>

    {{-- 3. TABEL BARANG --}}
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="50%">Deskripsi Barang / Produk</th>
                <th width="15%" class="text-center">Ukuran</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; $no = 1; @endphp
            
            @foreach($orders as $order)
                @foreach($order->items as $item)
                    <tr>
                        <td class="text-center" style="font-weight: bold;">{{ $no++ }}</td>
                        <td>
                            <span style="font-weight: bold; font-size: 13px;">{{ $item->product->title }}</span>
                        </td>
                        <td class="text-center">{{ $item->productSize->size ?? '-' }}</td>
                        <td class="text-center" style="font-weight: bold;">{{ $item->quantity }}</td>
                        <td class="text-right" style="font-weight: bold;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @php $grandTotal += $item->subtotal; @endphp
                @endforeach
            @endforeach
            
            {{-- Filler Rows (Agar tabel terlihat proporsional jika item sedikit) --}}
            @if($no < 7)
                @for($i = 0; $i < (7 - $no); $i++)
                    <tr>
                        <td style="height: 25px;"></td><td></td><td></td><td></td><td></td>
                    </tr>
                @endfor
            @endif
        </tbody>
    </table>

    {{-- 4. TOTAL SECTION (DESAIN BARU YG LEBIH BAGUS) --}}
    {{-- Menggunakan tabel untuk layouting agar aman di semua PDF engine --}}
    <table class="w-full" style="margin-top: 15px;">
        <tr>
            <td width="50%"></td> {{-- Spasi Kiri Kosong --}}
            <td width="50%">
                <div class="total-box-styled">
                    <table class="total-row-table">
                        <tr>
                            <td class="grand-total-label">TOTAL NILAI BARANG</td>
                            <td class="text-right">
                                <div class="grand-total-value">
                                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- 5. TANDA TANGAN --}}
    <table class="signature-wrapper">
        <tr>
            {{-- Admin Koperasi --}}
            <td class="sig-box">
                <div class="sig-role">Diserahkan Oleh (Admin Koperasi),</div>
                {{-- Nama Admin Otomatis --}}
                <div class="sig-name">{{ auth()->user()->name ?? 'Petugas Koperasi' }}</div>
            </td>
            
            <td width="30%"></td> {{-- Spasi Tengah --}}

            {{-- Siswa --}}
            <td class="sig-box">
                <div class="sig-role">Diterima Oleh (Siswa Ybs),</div>
                <div class="sig-name">{{ $user->nama_lengkap }}</div>
            </td>
        </tr>
    </table>

    {{-- 6. FOOTER NOTES --}}
    <div class="footer-notes">
        <strong>Catatan Penting:</strong> <br>
        Barang yang sudah diterima dan dibawa keluar dari area koperasi <u>tidak dapat ditukar atau dikembalikan</u>, kecuali terdapat cacat produksi yang terlewat saat pemeriksaan bersama. Harap simpan dokumen ini sebagai bukti sah pengambilan barang.
    </div>

</body>
</html>