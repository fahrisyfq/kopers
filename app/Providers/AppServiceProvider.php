<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// 🔥 PASTIKAN BAGIAN USE INI ADA SEMUA 🔥
use App\Models\Product; 
use App\Models\ProductSize;
use App\Observers\ProductStockObserver;
use App\Observers\ProductSizeStockObserver;
use App\Models\OrderItem;
use App\Observers\OrderItemObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Di dalam method boot()
        Product::observe(ProductStockObserver::class);
        ProductSize::observe(ProductSizeStockObserver::class);
        OrderItem::observe(OrderItemObserver::class);
    }
}
