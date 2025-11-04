<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductStockChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Produk Terjual vs Stok Tersisa';

    protected function getData(): array
    {
        // Hitung produk terjual dari relasi order_items (ubah sesuai model kamu)
        $products = Product::withCount(['orderItems as sold' => function ($query) {
            $query->select(\DB::raw('COALESCE(SUM(quantity), 0)'));
        }])->get();

        $labels = $products->pluck('title')->toArray();
        $sold = $products->pluck('sold')->toArray();
        $stock = $products->pluck('stock')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Terjual',
                    'data' => $sold,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.7)', // hijau
                ],
                [
                    'label' => 'Stok Tersisa',
                    'data' => $stock,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)', // biru
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
