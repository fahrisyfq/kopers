<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationGroup = 'Manajemen Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('title')
                        ->label('Nama Produk')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->maxLength(65535),

                    TextInput::make('price')
                        ->label('Harga')
                        ->required()
                        ->numeric()
                        ->minValue(0),

                    FileUpload::make('image')
                        ->label('Gambar Utama')
                        ->image()
                        ->disk('public')
                        ->directory('products')
                        ->required(),

                    // Gambar tambahan
                    Repeater::make('images')
                        ->label('Gambar Tambahan')
                        ->relationship('images')
                        ->schema([
                            FileUpload::make('url')
                                ->label('Upload Gambar')
                                ->image()
                                ->disk('public')
                                ->directory('products/extra')
                                ->required(),
                        ])
                        ->columns(1)
                        ->collapsed()
                        ->createItemButtonLabel('Tambah Gambar Baru'),

                    // Kategori
                    Select::make('category')
                        ->label('Kategori')
                        ->options([
                            'Atribut Sekolah' => 'Atribut Sekolah',
                            'Seragam Sekolah' => 'Seragam Sekolah',
                        ])
                        ->default('Atribut Sekolah')
                        ->required()
                        ->reactive(),

                    // Total stok (untuk atribut sekolah)
                    TextInput::make('stock')
                        ->label('Total Stok')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required()
                        ->visible(fn ($get) => $get('category') === 'Atribut Sekolah'),

                    // Stok per ukuran (untuk seragam sekolah)
                    Repeater::make('sizes')
                        ->label('Ukuran & Stok')
                        ->relationship('sizes')
                        ->schema([
                            Select::make('size')
                                ->label('Ukuran')
                                ->options([
                                    'M' => 'M',
                                    'L' => 'L',
                                    'XL' => 'XL',
                                    'XXL' => 'XXL',
                                ])
                                ->required(),

                            TextInput::make('stock')
                                ->label('Stok')
                                ->numeric()
                                ->minValue(0)
                                ->default(0)
                                ->required(),
                        ])
                        ->columns(2)
                        ->visible(fn ($get) => $get('category') === 'Seragam Sekolah'),

                    // Toggle Pre Order
                    Toggle::make('is_preorder')
                        ->label('Aktifkan Pre Order?')
                        ->default(false)
                        ->helperText('Jika diaktifkan, produk tetap bisa dipesan meskipun stok habis.'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            ImageColumn::make('image')
                ->label('Gambar')
                ->disk('public')
                ->square(),

            TextColumn::make('title')
                ->label('Nama Produk')
                ->sortable()
                ->searchable(),

            TextColumn::make('category')
                ->label('Kategori'),

            TextColumn::make('price')
                ->label('Harga')
                ->sortable()
                ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),

            TextColumn::make('stock')
                ->label('Total Stok')
                ->sortable()
                ->getStateUsing(fn ($record) =>
                    $record->category === 'Seragam Sekolah'
                        ? $record->sizes->sum('stock')
                        : $record->stock
                ),

            // 🔹 Tambahkan kolom baru: jumlah preorder
            TextColumn::make('preorder_quantity')
                ->label('Total Pre Order')
                ->sortable()
                ->getStateUsing(fn ($record) => $record->preorder_quantity . ' pcs')
                ->badge()
                ->color(fn ($record) => $record->preorder_quantity > 0 ? 'warning' : 'gray'),


            BadgeColumn::make('is_preorder')
                ->label('Status')
                ->colors([
                    'success' => fn ($state, $record) => $record->stock > 0,
                    'warning' => fn ($state, $record) => $record->stock == 0 && $state === true,
                    'danger'  => fn ($state, $record) => $record->stock == 0 && !$state,
                ])
                ->formatStateUsing(fn ($state, $record) => $record->stockStatusLabel()),

            TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}


    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
