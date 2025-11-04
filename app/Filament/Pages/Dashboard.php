<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\StatsOverview;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            OrdersChart::class,
            ProductStockChart::class,
        ];
    }
    
}
