<?php

namespace App\Filament\Resources\StockMovementResource\Pages;

use App\Filament\Resources\StockMovementResource;
use App\Models\StockMovement;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder; // ✅ pakai Builder Eloquent

class ListStockMovements extends ListRecords
{
    protected static string $resource = StockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // ✅ filter berdasarkan product_id (kalau dikirim dari halaman produk)
    protected function getTableQuery(): ?Builder
    {
        $query = StockMovement::query();

        if (request()->has('product_id')) {
            $query->where('product_id', request('product_id'));
        }

        return $query;
    }
}
