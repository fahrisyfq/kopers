<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Detail Produk Pesanan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Produk')
                    ->sortable(),

                Tables\Columns\TextColumn::make('productSize.size')
                    ->label('Ukuran')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->money('IDR', true)
                    ->label('Subtotal'),

                Tables\Columns\SelectColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'cash' => 'Tunai',
                        'paid' => 'Sudah Dibayar',
                    ])
                    ->sortable()
                    ->editable(), // ✅ bisa diubah langsung dari tabel
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make(), // optional
            ])
            ->bulkActions([]);
    }
}
