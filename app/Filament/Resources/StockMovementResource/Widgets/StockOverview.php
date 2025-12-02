<?php

namespace App\Filament\Resources\StockMovementResource\Widgets;

use App\Filament\Resources\StockMovementResource\Pages\ListStockMovements;
use App\Models\Product; 
use App\Models\StockMovement;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB; // 🔥 Wajib import ini untuk query grafik

class StockOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected $queryString = [
        'tableFilters',
        'tableSearchQuery',
    ];

    public ?array $tableFilters = null;
    public ?string $tableSearchQuery = null;
    public array $tableColumnSearches = [];

    protected function getTablePage(): string
    {
        return ListStockMovements::class;
    }

    // 🔥 Helper untuk mengambil data grafik (array angka)
    protected function getTrend($baseQuery, $type, $isPreorder)
    {
        return (clone $baseQuery)
            ->reorder() // 👈 PENTING: Hapus default sort (created_at desc) bawaan tabel
            ->where('movement_type', $type)
            ->where('is_preorder', $isPreorder)
            ->where('created_at', '>=', now()->subDays(30)) 
            // Gunakan Raw yang kompatibel dengan Postgres & MySQL
            ->selectRaw('DATE(created_at) as date, SUM(quantity) as total') 
            ->groupBy('date')
            ->orderBy('date') // Urutkan berdasarkan tanggal grafik
            ->pluck('total')
            ->toArray();
    }

    protected function getStats(): array
    {
        // Query ini sudah otomatis berisi filter dari tabel
        $query = $this->getPageTableQuery();

        // 1. Stok Fisik Masuk
        $fisikMasuk = (clone $query)
            ->where('is_preorder', false)
            ->where('movement_type', 'in')
            ->sum('quantity');
        
        // Ambil trend grafik
        $chartMasuk = $this->getTrend($query, 'in', false);

        // 2. Stok Fisik Keluar
        $fisikKeluar = (clone $query)
            ->where('is_preorder', false)
            ->where('movement_type', 'out')
            ->sum('quantity');

        // Ambil trend grafik
        $chartKeluar = $this->getTrend($query, 'out', false);

        // 3. Antrian PO
        $antrianPO = (clone $query)
            ->where('is_preorder', true)
            ->where('movement_type', 'in') 
            ->sum('quantity');

        // Ambil trend grafik PO
        $chartPO = $this->getTrend($query, 'in', true);

        // --- HITUNG BALANCE (SISA STOK) ---
        // Ini fitur tambahan biar admin tau "Netto" stok di gudang dari hasil filter
        $balance = $fisikMasuk - $fisikKeluar; 

        return [
            Stat::make('Total Stok Masuk', number_format($fisikMasuk))
                ->description('30 Hari Terakhir')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($chartMasuk) // 🔥 Pasang Grafik Disini
                ->color('success'),

            Stat::make('Total Stok Keluar', number_format($fisikKeluar))
                ->description('30 Hari Terakhir')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart($chartKeluar) // 🔥 Pasang Grafik Disini
                ->color('danger'),

            Stat::make('Balance / Sisa', number_format($balance)) // 🔥 Kartu Baru: Balance
                ->description('Masuk dikurangi Keluar')
                ->descriptionIcon('heroicon-m-scale')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'title' => 'Menunjukkan sisa stok fisik berdasarkan filter saat ini'
                ]),

            Stat::make('Antrian PO', number_format($antrianPO)) 
                ->description('Pesanan Aktif')
                ->descriptionIcon('heroicon-m-clock')
                ->chart($chartPO) // 🔥 Pasang Grafik Disini
                ->color('warning'),
        ];
    }
}