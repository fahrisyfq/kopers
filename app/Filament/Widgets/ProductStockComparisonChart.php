<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\StockMovement;
use Filament\Widgets\ChartWidget;

class ProductStockComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Analisa Stok: Supply, Penjualan & PO';
    
    protected static ?string $maxHeight = '400px';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Ambil semua produk
        $products = Product::all();

        $labels = [];
        $stockIn = [];
        $stockOut = [];
        $stockPO = [];

        foreach ($products as $product) {
            $labels[] = $product->title;

            // 1. Total Stok Fisik Masuk (Supply)
            // Menghitung semua barang yang pernah masuk ke gudang (Restock)
            $totalIn = StockMovement::where('product_id', $product->id)
                ->where('movement_type', 'in')
                ->where('is_preorder', false) // Abaikan antrian PO, hanya fisik
                ->sum('quantity');
            
            // 2. Total Stok Terjual (Sales)
            // Menghitung semua barang fisik yang keluar
            $totalOut = StockMovement::where('product_id', $product->id)
                ->where('movement_type', 'out')
                ->sum('quantity');

            // 3. Antrian Pre-Order Aktif
            // Mengambil data real-time dari tabel produk
            $currentPO = $product->preorder_quantity;

            $stockIn[] = $totalIn;
            $stockOut[] = $totalOut;
            $stockPO[] = $currentPO;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Supply (Masuk)',
                    'data' => $stockIn,
                    'backgroundColor' => '#34d399', // Hijau (Emerald 400)
                    'borderColor' => '#10b981',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Terjual (Keluar)',
                    'data' => $stockOut,
                    'backgroundColor' => '#f87171', // Merah (Red 400)
                    'borderColor' => '#ef4444',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Antrian PO (Waiting)',
                    'data' => $stockPO,
                    'backgroundColor' => '#facc15', // Kuning (Yellow 400)
                    'borderColor' => '#eab308',
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