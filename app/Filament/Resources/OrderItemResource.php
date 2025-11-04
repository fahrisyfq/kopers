<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderItemResource\Pages;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Order')
                    ->relationship('order', 'id')
                    ->required(),

                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $product = Product::find($state);
                        if ($product) {
                            $set('price', $product->price); // isi harga otomatis
                        }
                    }),

                Forms\Components\Select::make('product_size_id')
                        ->label('Ukuran')
                        ->options(function (callable $get) {
                            $productId = $get('product_id');
                            if (!$productId) return [];

                            $product = \App\Models\Product::find($productId);

                            // hanya muncul kalau kategori seragam
                            if (!$product || $product->category !== 'Seragam Sekolah') {
                                return [];
                            }

                            return \App\Models\ProductSize::where('product_id', $productId)
                                ->pluck('size', 'id') // id = value yang disimpan, size = label
                                ->toArray();
                        })
                        ->visible(fn ($get) => \App\Models\Product::find($get('product_id'))?->category === 'Seragam Sekolah')
                        ->required(fn ($get) => \App\Models\Product::find($get('product_id'))?->category === 'Seragam Sekolah')
                        ->reactive(),


                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $product = Product::find($get('product_id'));
                        if (!$product) return;

                        if ($product->category === 'Seragam Sekolah') {
                            $productSizeId = $get('product_size_id');
                            $stock = \App\Models\ProductSize::find($productSizeId)?->stock ?? 0;
                        } else {
                            $stock = (int) $product->stock;
                        }

                        if ($state > $stock) {
                            $set('quantity', $stock);
                            Notification::make()
                                ->title('Stok tidak cukup')
                                ->body("Stok tersedia hanya {$stock}")
                                ->warning()
                                ->send();
                        }

                        $set('subtotal', (float) ($get('price') ?? 0) * (int) ($get('quantity') ?? 1));
                    }),



                Forms\Components\TextInput::make('price')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $set('subtotal', (float) $state * (int) ($get('quantity') ?? 1))
                    ),

                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->readOnly(), // ✅ tetap tersimpan, tapi tidak bisa diubah manual
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('order.id')->label('Order ID')->sortable(),
                Tables\Columns\TextColumn::make('product.title')->label('Produk')->sortable(),
                Tables\Columns\TextColumn::make('productSize.size')->label('Ukuran')->default('-'),
                Tables\Columns\TextColumn::make('quantity')->label('Jumlah')->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Harga Satuan')->money('IDR', true),
                Tables\Columns\TextColumn::make('subtotal')->label('Subtotal')->money('IDR', true),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->dateTime('d M Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
