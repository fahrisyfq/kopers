<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat; // Perhatikan namespace ini, di v3 pakai Stat, bukan Card
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    // Mengatur urutan tampilan widget. Semakin besar angkanya, semakin bawah posisinya.
    // Pastikan widget lain (grafik) memiliki sort lebih kecil (misal 1, 2, 3).
    protected static ?int $sort = 10; 

    // Opsional: Mengatur polling interval (refresh otomatis)
    protected static ?string $pollingInterval = '15s';

    // Opsional: Mengatur jumlah kolom
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pemesanan', Order::count())
                ->description('Jumlah seluruh pesanan')
                ->descriptionIcon('heroicon-m-arrow-trending-up') // Ikon trending (opsional)
                ->icon('heroicon-o-shopping-cart')
                ->color('primary') // Warna tema
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Contoh chart sparkline (opsional)

            Stat::make('Total Produk', Product::count())
                ->description('Produk yang tersedia')
                ->icon('heroicon-o-archive-box')
                ->color('success'),

            Stat::make('Total Siswa', User::where('role', '!=', 'admin')->count()) 
                ->description('Jumlah siswa terdaftar')
                ->icon('heroicon-o-users')
                ->color('warning'),
        ];
    }
}