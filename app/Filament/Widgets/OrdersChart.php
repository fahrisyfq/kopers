<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pemesanan per Bulan';

    protected function getData(): array
    {
        // Deteksi database driver yang digunakan
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            // Untuk PostgreSQL
            $orders = Order::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
        } else {
            // Untuk MySQL / MariaDB
            $orders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
        }

        // Buat label dan data untuk 12 bulan
        $labels = [];
        $data = [];

        foreach (range(1, 12) as $month) {
            $labels[] = date('M', mktime(0, 0, 0, $month, 1)); // Jan, Feb, Mar, dst.
            $data[] = $orders[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $data,
                    'borderColor' => '#f59e0b', // warna oranye amber
                    'backgroundColor' => 'rgba(245, 158, 11, 0.3)',
                    'tension' => 0.3, // lengkungan garis
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Bisa diganti 'bar', 'pie', dll.
    }
}
