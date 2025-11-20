<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\ProductStockChart;
use App\Filament\Widgets\ProductStockComparisonChart;
use App\Filament\Widgets\TotalStockChart;
use App\Filament\Widgets\StatsOverview;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            OrdersChart::class,
            ProductStockChart::class,
            ProductStockComparisonChart::class,
            TotalStockChart::class, // 🔹 widget baru
            StatsOverview::class,   // 🔹 letakkan di paling bawah
        ];
    }
}
