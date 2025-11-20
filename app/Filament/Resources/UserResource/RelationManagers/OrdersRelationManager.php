<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn; // 🟢 1. TAMBAHKAN IMPORT INI

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';
    protected static ?string $title = 'Daftar Pesanan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('custom_id')
                    ->label('ID Pesanan')
                    ->getStateUsing(function ($record) {
                        if (!$record->user) return '-'; // Pengaman jika user null
                        $orders = $record->user->orders()->orderBy('created_at')->pluck('id')->toArray();
                        $index = array_search($record->id, $orders);
                        return $index !== false ? $index + 1 : '-';
                    })
                    ->alignCenter()
                    ->badge()
                    ->sortable(false),

                Tables\Columns\TextColumn::make('items.product.title')
                    ->label('Nama Produk')
                    ->listWithLineBreaks()
                    ->limit(50),

                Tables\Columns\TextColumn::make('items.size.size')
                    ->label('Ukuran')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('items.quantity')
                    ->label('Jumlah')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR'),

                // 🟢 2. UBAH KOLOM PAYMENT_METHOD
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->colors([
                        'success' => 'cash',
                        'info' => 'kjp',
                        'warning' => 'transfer_bank', // Tambahan
                        'primary' => 'e_wallet',      // Tambahan
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'cash' => 'Cash',
                        'kjp' => 'KJP (Bank DKI)',
                        'transfer_bank' => 'Transfer Bank', // Tambahan
                        'e_wallet' => 'E-Wallet / QRIS', // Tambahan
                        default => $state ?? '-',
                    }),
                
                // 🟢 3. TAMBAHKAN KOLOM BUKTI BAYAR
                ImageColumn::make('proof_of_payment')
                    ->label('Bukti Bayar')
                    ->disk('public') // Pastikan ini 'public'
                    ->width(80)
                    ->height(80)
                    ->square(),

                Tables\Columns\TextColumn::make('is_preorder')
                    ->label('Pre-Order?')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->items->contains(fn($item) => $item->is_preorder) ? 'Ya' : 'Tidak';
                    })
                    ->colors([
                        'warning' => fn($state) => $state === 'Ya',
                        'success' => fn($state) => $state === 'Tidak',
                    ]),

                Tables\Columns\SelectColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'cash' => 'Cash', // Status ini mungkin perlu ditinjau, tapi saya biarkan
                        'paid' => 'Paid',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->timezone('Asia/Jakarta')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->label('Ubah Status Pembayaran')
                    ->form([
                        Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'cash' => 'Cash',
                                'paid' => 'Paid',
                            ])
                            ->required(),
                    ]),
            ]);
    }
}