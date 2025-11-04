<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles, WithAutoSize
{
    protected ?string $kelas;
    protected ?string $jurusan;

    public function __construct(?string $kelas = null, ?string $jurusan = null)
    {
        $this->kelas = $kelas;
        $this->jurusan = $jurusan;
    }

    public function collection(): Collection
    {
        $data = collect();

        $query = User::with(['orders.items.product', 'orders.items.size']);

        if ($this->kelas) {
            $query->where('kelas', $this->kelas);
        }

        if ($this->jurusan) {
            $query->where('jurusan', $this->jurusan);
        }

        $query->chunk(50, function ($users) use (&$data) {
            foreach ($users as $user) {
                $produkList = collect();

                foreach ($user->orders as $order) {
                    foreach ($order->items as $item) {
                        $namaProduk = $item->product?->title ?? '-';
                        $ukuran = $item->size?->size ?? '-';
                        $status = $order->payment_status ?? '-';

                        $produkList->push("{$namaProduk} ({$ukuran}) - Status: {$status}");
                    }
                }

                $produkText = $produkList->implode(" | ");

                $data->push([
                    'NIS' => $user->nis,
                    'Nama Lengkap' => $user->nama_lengkap,
                    'Kelas' => $user->kelas,
                    'Jurusan' => $user->jurusan,
                    'Produk dan Status Pembayaran' => $produkText,
                ]);
            }
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Lengkap',
            'Kelas',
            'Jurusan',
            'Produk dan Status Pembayaran',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Judul tabel tebal & rata tengah
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');

        // Tambah border di seluruh tabel
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:E{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        return [];
    }
}
