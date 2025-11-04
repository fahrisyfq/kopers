<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    // ubah jadi non-static supaya sesuai parent class
    protected ?string $heading = 'Statistik Data';

    protected function getCards(): array
    {
        return [
            Card::make('Total Pemesanan', Order::count())
                ->description('Jumlah seluruh pesanan')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Card::make('Total Produk', Product::count())
                ->description('Produk yang tersedia')
                ->icon('heroicon-o-rectangle-stack')
                ->color('success'),

            Card::make('Total Siswa', User::count())
                ->description('Jumlah siswa terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('info'),
        ];
    }
}
