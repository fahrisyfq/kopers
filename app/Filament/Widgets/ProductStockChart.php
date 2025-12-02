<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductStockChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan vs Sisa Stok vs PO';
    
    protected static ?string $maxHeight = '400px';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Ambil produk beserta total quantity terjual
        // Kita filter agar order yang 'cancelled' tidak ikut terhitung
        $products = Product::withSum(['orderItems as sold_qty' => function ($query) {
            $query->whereHas('order', function ($q) {
                $q->where('payment_status', '!=', 'cancelled');
            });
        }], 'quantity')->get();

        $labels = [];
        $soldData = [];
        $stockData = [];
        $poData = [];

        foreach ($products as $product) {
            $labels[] = $product->title;
            
            // Data Terjual
            $soldData[] = (int) $product->sold_qty; 
            
            // Data Sisa Stok Fisik (Mengambil dari kolom stock parent yang sudah disinkronkan Observer)
            $stockData[] = (int) $product->stock;

            // Data Antrian PO (Hutang)
            $poData[] = (int) $product->preorder_quantity;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Terjual',
                    'data' => $soldData,
                    'backgroundColor' => '#3b82f6', // Biru (Blue 500)
                    'borderColor' => '#2563eb',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Sisa Stok Fisik',
                    'data' => $stockData,
                    'backgroundColor' => '#22c55e', // Hijau (Green 500)
                    'borderColor' => '#16a34a',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Antrian PO',
                    'data' => $poData,
                    'backgroundColor' => '#eab308', // Kuning (Yellow 500)
                    'borderColor' => '#ca8a04',
                    'borderWidth' => 1,
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