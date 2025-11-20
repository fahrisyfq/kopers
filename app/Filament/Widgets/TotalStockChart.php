<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class TotalStockChart extends ChartWidget
{
    protected static ?string $heading = 'Total Stok Produk';

    protected function getData(): array
    {
        $labels = [];
        $values = [];

        $products = Product::with('sizes')->get();

        foreach ($products as $product) {
            $labels[] = $product->name;
            $values[] = $product->sizes->sum('stock') ?: $product->stock;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Stok per Produk',
                    'data' => $values,
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
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
