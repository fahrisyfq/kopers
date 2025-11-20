<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Forms\Components\Card;
use Filament\Notifications\Notification;
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
use Filament\Tables\Actions\Action; 

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
                                    'XXXL' => 'XXXL',
                                    '4XL' => '4XL',
                                    '5XL' => '5XL',
                                    '6XL' => '6XL',
                                    '7XL' => '7XL',
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

                    // Toggle Status Aktif per Produk
                    Toggle::make('is_active')
                        ->label('Status Produk')
                        ->default(true)
                        ->onIcon('heroicon-o-eye')
                        ->offIcon('heroicon-o-eye-slash')
                        ->onColor('success')
                        ->offColor('danger')
                        ->helperText('Saat non-aktif, produk ini tidak akan muncul di website katalog siswa.')
                        ->columnSpanFull(),
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
                    
                // Kolom Status Aktif Produk
                BadgeColumn::make('is_active')
                    ->label('Aktif')
                    ->getStateUsing(fn (Product $record): string => $record->is_active ? 'Aktif' : 'Non-aktif')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ]),

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

                // Kolom jumlah preorder
                TextColumn::make('preorder_quantity')
                    ->label('Total Pre Order')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->preorder_quantity . ' pcs')
                    ->badge()
                    ->color(fn ($record) => $record->preorder_quantity > 0 ? 'warning' : 'gray'),


                BadgeColumn::make('is_preorder')
                    ->label('Status Stok')
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
    Action::make('toggle_active')
        ->label(fn (Product $record): string => $record->is_active ? 'Non-aktifkan' : 'Aktifkan')
        ->iconButton() 
        ->icon(fn (Product $record): string => $record->is_active ? 'heroicon-o-eye' : 'heroicon-o-eye-slash') 
        ->color(fn (Product $record): string => $record->is_active ? 'success' : 'danger')
        ->tooltip(fn (Product $record): string => $record->is_active ? 'Produk Akun (Klik untuk Non-aktifkan)' : 'Produk Non-aktif (Klik untuk Aktifkan)')
        
        ->requiresConfirmation()
        ->modalHeading(fn (Product $record): string => $record->is_active ? 'Non-aktifkan Produk?' : 'Aktifkan Produk?')
        ->modalSubheading(fn (Product $record): string => 
            $record->is_active 
            ? 'Anda yakin ingin menyembunyikan produk "' . $record->title . '" dari katalog?' 
            : 'Anda yakin ingin menampilkan kembali produk "' . $record->title . '" di katalog?'
        )
        ->modalButton('Ya, Lakukan')
        ->action(function (Product $record) {
            $record->is_active = !$record->is_active; 
            $record->save();
            
            // [TAMBAHAN] Kirim notifikasi sukses setelah aksi
            Notification::make()
                ->title('Status Produk Diperbarui')
                ->body('Produk "' . $record->title . '" telah ' . ($record->is_active ? 'diaktifkan' : 'dinon-aktifkan') . '.')
                ->success()
                ->send();
        }),

        Action::make('manage_stock')
            ->label('Manajemen Stok')
            ->icon('heroicon-o-adjustments-horizontal')
            ->color('info')
            ->url(fn (Product $record): string => route('filament.admin.resources.stock-movements.index', [
                'product_id' => $record->id,
            ]))
            ->tooltip('Lihat & kelola pergerakan stok produk ini')
            ->openUrlInNewTab(),
                    
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