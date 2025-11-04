<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items'; // relasi milik model Order
    protected static ?string $title = 'Detail Produk Pesanan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Produk')
                    ->sortable(),

                Tables\Columns\TextColumn::make('productSize.size')
                    ->label('Ukuran'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR', true),

                Tables\Columns\SelectColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'cash' => 'Tunai',
                        'paid' => 'Sudah Dibayar',
                    ])
                    ->editable(), // ✅ bisa edit langsung
            ]);
    }
}
