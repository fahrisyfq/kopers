<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Models\StockMovement;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-up-down';
    protected static ?string $navigationGroup = 'Manajemen Stok';
    protected static ?string $navigationLabel = 'Pergerakan Stok';
    protected static ?string $pluralModelLabel = 'Pergerakan Stok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('product_size_id', null)),

                Forms\Components\Select::make('product_size_id')
                    ->label('Ukuran')
                    ->options(function (callable $get) {
                        $productId = $get('product_id');
                        if (!$productId) return [];

                        $product = Product::find($productId);
                        if ($product && $product->category === 'Seragam Sekolah') {
                            return $product->sizes()->pluck('size', 'id');
                        }

                        return [];
                    })
                    ->visible(function (callable $get) {
                        $productId = $get('product_id');
                        if (!$productId) return false;

                        $product = Product::find($productId);
                        return $product && $product->category === 'Seragam Sekolah';
                    })
                    ->reactive()
                    ->nullable(),

                Forms\Components\Select::make('movement_type')
                    ->label('Tipe Pergerakan')
                    ->options([
                        'in' => 'Stok Masuk',
                        'out' => 'Stok Keluar',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Forms\Components\Textarea::make('note')
                    ->label('Catatan')
                    ->placeholder('Misal: Penambahan stok dari supplier atau pengurangan karena penjualan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('product.title')
                ->label('Nama Produk')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('productSize.size')
                ->label('Ukuran')
                ->default('-'),

            Tables\Columns\TextColumn::make('movement_type')
                ->label('Tipe')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'in' => 'Stok Masuk',
                    'out' => 'Stok Keluar',
                    default => ucfirst($state),
                })
                ->badge()
                ->colors([
                    'success' => 'in',
                    'danger' => 'out',
                ]),

            Tables\Columns\TextColumn::make('balance_before')
                ->label('Stok Sebelum')
                ->sortable(),

            Tables\Columns\TextColumn::make('quantity')
                ->label('Jumlah')
                ->sortable(),

            Tables\Columns\TextColumn::make('balance_after')
                ->label('Stok Setelah')
                ->sortable(),

            // tambahan opsional: kolom selisih stok
            Tables\Columns\TextColumn::make('stock_difference')
                ->label('Perubahan')
                ->getStateUsing(fn ($record) => $record->balance_after - $record->balance_before)
                ->formatStateUsing(fn ($state) => $state > 0 ? "+{$state}" : (string) $state)
                ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray')),

            Tables\Columns\TextColumn::make('note')
                ->label('Catatan')
                ->limit(30)
                ->wrap(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime('d M Y H:i')
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('movement_type')
                ->label('Tipe Pergerakan')
                ->options([
                    'in' => 'Stok Masuk',
                    'out' => 'Stok Keluar',
                ]),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
            'edit' => Pages\EditStockMovement::route('/{record}/edit'),
        ];
    }
}
