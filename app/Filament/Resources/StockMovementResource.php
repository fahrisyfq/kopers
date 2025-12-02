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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Manajemen Stok';
    protected static ?string $navigationLabel = 'Riwayat Stok';
    protected static ?string $pluralModelLabel = 'Riwayat Pergerakan Stok';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('product_size_id', null))
                            ->columnSpan(1),

                        Forms\Components\Select::make('product_size_id')
                            ->label('Ukuran (Opsional)')
                            ->options(function (callable $get) {
                                $productId = $get('product_id');
                                if (!$productId) return [];
                                $product = Product::find($productId);
                                return $product && $product->category === 'Seragam Sekolah' 
                                    ? $product->sizes()->pluck('size', 'id') 
                                    : [];
                            })
                            ->visible(fn (callable $get) => Product::find($get('product_id'))?->category === 'Seragam Sekolah')
                            ->columnSpan(1),
                    ]),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('movement_type')
                            ->label('Aksi')
                            ->options([
                                'in' => 'Stok Masuk (Restock)',
                                'out' => 'Stok Keluar (Koreksi)',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),

                    Forms\Components\Textarea::make('note')
                        ->label('Keterangan / Alasan')
                        ->placeholder('Contoh: Barang baru datang dari supplier A')
                        ->required()
                        ->columnSpanFull(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // 1. GAMBAR & PRODUK (Digabung biar rapi)
                Tables\Columns\ImageColumn::make('product.image')
                    ->label('Foto')
                    ->disk('public')
                    ->square()
                    ->size(50),

                Tables\Columns\TextColumn::make('product.title')
                    ->label('Produk')
                    ->searchable()
                    ->sortable()
                    ->description(fn (StockMovement $record) => 
                        ($record->productSize ? 'Ukuran: ' . $record->productSize->size : 'All Size') . 
                        ' • ' . $record->product->category
                    )
                    ->weight('bold'),

                // 2. TIPE & STATUS (Icon Visual)
                Tables\Columns\TextColumn::make('movement_type')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function ($state, StockMovement $record) {
                        if ($record->is_preorder) {
                            return match ($state) {
                                'in' => 'Antrian PO',
                                'out' => 'PO Ready',
                                default => $state,
                            };
                        }
                        return match ($state) {
                            'in' => 'Masuk',
                            'out' => 'Keluar',
                            default => $state,
                        };
                    })
                    ->icon(function ($state, StockMovement $record) {
                        if ($record->is_preorder) return 'heroicon-m-clock';
                        return match ($state) {
                            'in' => 'heroicon-m-arrow-trending-up',
                            'out' => 'heroicon-m-arrow-trending-down',
                        };
                    })
                    ->colors([
                        'success' => fn ($state, $record) => !$record->is_preorder && $state === 'in', // Hijau (Masuk)
                        'danger'  => fn ($state, $record) => !$record->is_preorder && $state === 'out', // Merah (Keluar)
                        'warning' => fn ($state, $record) => $record->is_preorder && $state === 'in',  // Kuning (PO Masuk)
                        'gray'    => fn ($state, $record) => $record->is_preorder && $state === 'out', // Abu (PO Ready)
                    ]),

                // 3. JUMLAH (Warna Angka)
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jml')
                    ->alignCenter()
                    ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                    ->weight('bold')
                    ->formatStateUsing(fn ($state, StockMovement $record) => 
                        ($record->movement_type === 'in' ? '+' : '-') . $state
                    )
                    ->color(fn (StockMovement $record) => 
                        $record->movement_type === 'in' ? 'success' : 'danger'
                    ),

                // 4. SISA STOK (Snapshot saat kejadian)
                Tables\Columns\TextColumn::make('balance_after')
                    ->label('Sisa')
                    ->alignCenter()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => $state . ' pcs'),

                // 5. KETERANGAN (Dibersihkan)
                Tables\Columns\TextColumn::make('note')
                    ->label('Keterangan')
                    ->wrap() // Agar text panjang turun ke bawah
                    ->limit(50)
                    ->tooltip(fn ($state) => $state)
                    ->description(fn (StockMovement $record) => $record->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i')),            ])
            ->filters([
                // Filter Produk
                SelectFilter::make('product_id')
                    ->label('Filter Produk')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->preload(),

                // Filter Tipe
                SelectFilter::make('movement_type')
                    ->label('Tipe Aksi')
                    ->options([
                        'in' => 'Barang Masuk / Restock',
                        'out' => 'Barang Keluar / Terjual',
                    ]),

                // Filter Tanggal (PERBAIKAN DISINI)
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null, // 🔥 Tambahkan '?? null' agar aman
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null, // 🔥 Tambahkan '?? null' agar aman
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                // Hanya Edit/Hapus jika itu inputan manual (bukan otomatis sistem)
                // Agar data akuntansi tidak rusak
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (StockMovement $record) => !$record->is_preorder && !str_contains($record->note, 'Otomatis') && !str_contains($record->note, 'Penjualan')),
            ])
            ->bulkActions([
                // Hindari bulk delete sembarangan
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
        ];
    }
}