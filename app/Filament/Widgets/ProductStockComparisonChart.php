<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\StockMovement;
use Filament\Widgets\ChartWidget;

class ProductStockComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Stok Awal & Terjual per Produk';

    protected static ?int $sort = 4; // urutan tampil di dashboard

    protected function getData(): array
    {
        // Ambil semua produk
        $products = Product::with('sizes')->get();

        $labels = [];
        $initialStocks = [];
        $soldStocks = [];

        foreach ($products as $product) {
            $labels[] = $product->title;

            // Ambil stok awal (stok pertama kali dicatat dari pergerakan "in" pertama kali)
            $firstMovement = StockMovement::where('product_id', $product->id)
                ->where('movement_type', 'in')
                ->orderBy('created_at', 'asc')
                ->first();

            $initialStock = $firstMovement
                ? ($firstMovement->balance_before ?? 0) + $firstMovement->quantity
                : 0;

            // Hitung total stok keluar (penjualan)
            $sold = StockMovement::where('product_id', $product->id)
                ->where('movement_type', 'out')
                ->sum('quantity');

            $initialStocks[] = $initialStock;
            $soldStocks[] = $sold;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Stok Awal',
                    'data' => $initialStocks,
                    'backgroundColor' => '#4ade80', // hijau muda
                ],
                [
                    'label' => 'Stok Terjual',
                    'data' => $soldStocks,
                    'backgroundColor' => '#f87171', // merah muda
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
