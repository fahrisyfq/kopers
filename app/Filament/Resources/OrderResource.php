<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
// 🟢 1. TAMBAHKAN IMPORT INI
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Bagian Informasi Pesanan
            Forms\Components\Section::make('Informasi Pesanan')
                ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->label('Pelanggan'),

                    // 🟢 2. UBAH OPSI METODE PEMBAYARAN
                    Select::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->options([
                            'cash' => 'Cash',
                            'kjp' => 'KJP (Bank DKI)',
                            'transfer_bank' => 'Transfer Bank',
                            'e_wallet' => 'E-Wallet / QRIS',
                        ])
                        ->required(),
                    
                    // 🟢 3. TAMBAHKAN FILE UPLOAD BUKTI BAYAR
                    FileUpload::make('proof_of_payment')
                        ->label('Bukti Pembayaran')
                        ->directory('payment_proofs') // Sesuaikan dengan path di Controller
                        ->visibility('public')        // Gunakan disk 'public'
                        ->downloadable()
                        ->openable()
                        ->imagePreviewHeight('150')
                        ->loadingIndicatorPosition('left')
                        ->panelAspectRatio('2:1')
                        ->panelLayout('integrated'),

                ]),

            // Bagian Item Pesanan (Tidak ada perubahan di sini)
            Forms\Components\Section::make('Item Pesanan')
                ->schema([
                    Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                                ->label('Produk')
                                ->options(fn() => Product::pluck('title', 'id'))
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $product = Product::find($state);
                                    if (!$product) return;

                                    $set('price', (float) $product->price);
                                    if ($product->category !== 'Seragam Sekolah') {
                                        $set('product_size_id', null);
                                    }
                                    $qty = (int) ($get('quantity') ?? 1);
                                    $set('subtotal', (float) $product->price * $qty);
                                }),

                            Select::make('product_size_id')
                                ->label('Ukuran')
                                ->options(function (callable $get) {
                                    $productId = $get('product_id');
                                    if (!$productId) return [];

                                    $product = Product::find($productId);
                                    if (!$product || $product->category !== 'Seragam Sekolah') {
                                        return [];
                                    }

                                    return ProductSize::where('product_id', $productId)
                                        ->pluck('size', 'id')
                                        ->toArray();
                                })
                                ->reactive()
                                ->visible(fn($get) => Product::find($get('product_id'))?->category === 'Seragam Sekolah')
                                ->required(fn($get) => Product::find($get('product_id'))?->category === 'Seragam Sekolah'),

                            TextInput::make('price')
                                ->numeric()
                                ->required()
                                ->label('Harga (Rp)'),

                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->label('Jumlah')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                    $product = Product::find($get('product_id'));
                                    if (!$product) return;

                                    if ($product->category === 'Seragam Sekolah') {
                                        $sizeId = $get('product_size_id');
                                        $stock = ProductSize::find($sizeId)?->stock ?? 0;
                                    } else {
                                        $stock = (int) $product->stock;
                                    }
                                    $qty = (int) $state;

                                    if ($qty > $stock && !$product->is_preorder) { // Cek preorder
                                        $set('quantity', $stock);
                                        Notification::make()
                                            ->title('Peringatan Stok')
                                            ->body("Stok tersedia hanya {$stock}")
                                            ->warning()
                                            ->send();
                                    }

                                    $price = (float) ($get('price') ?? 0);
                                    $set('subtotal', $price * (int) ($get('quantity') ?? 1));
                                }),

                            TextInput::make('subtotal')
                                ->numeric()
                                ->readOnly()
                                ->label('Subtotal (Rp)'),
                        ])
                        ->columns(5)
                        ->createItemButtonLabel('Tambah Produk'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('custom_id')
                    // ... (logika custom_id tetap sama) ...
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

                Tables\Columns\TextColumn::make('user.nama_lengkap')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.kelas')
                    ->label('Kelas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.jurusan')
                    ->label('Jurusan')
                    ->sortable(),

                // 🟢 4. UBAH KOLOM METODE PEMBAYARAN (WARNA & FORMAT)
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->colors([
                        'success' => 'cash',
                        'info' => 'kjp',
                        'warning' => 'transfer_bank',
                        'primary' => 'e_wallet',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'cash' => 'Cash',
                        'kjp' => 'KJP (Bank DKI)',
                        'transfer_bank' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet / QRIS',
                        default => $state ?? '-',
                    }),

                // 🟢 5. TAMBAHKAN KOLOM BUKTI BAYAR
                ImageColumn::make('proof_of_payment')
                    ->label('Bukti Bayar')
                    ->disk('public') // Ambil dari disk public
                    ->width(80)
                    ->height(80)
                    ->square(), // Tampilkan sebagai kotak

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}