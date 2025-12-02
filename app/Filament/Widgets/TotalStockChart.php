<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class TotalStockChart extends ChartWidget
{
    protected static ?string $heading = 'Status Kesehatan Stok';
    
    protected static ?string $maxHeight = '300px';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        // Hitung jumlah produk berdasarkan status stok
        $stokAman = Product::where('stock', '>=', 5)->count();
        $stokMenipis = Product::where('stock', '>', 0)->where('stock', '<', 5)->count();
        $stokHabis = Product::where('stock', '<=', 0)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Produk',
                    'data' => [$stokAman, $stokMenipis, $stokHabis],
                    'backgroundColor' => [
                        '#3b82f6', // Biru (Aman)
                        '#fbbf24', // Kuning (Menipis)
                        '#ef4444', // Merah (Habis)
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Stok Aman (>= 5)', 'Stok Menipis (< 5)', 'Stok Habis (0)'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Menggunakan Doughnut Chart
    }
}