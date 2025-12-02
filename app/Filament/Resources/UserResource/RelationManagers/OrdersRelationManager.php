<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';
    protected static ?string $title = 'Daftar Pesanan';

    public function table(Table $table): Table
    {
        return $table
            // 🔥 1. LOGIKA BARIS ABU-ABU (DIPERBAIKI) 🔥
            ->recordClasses(function (Order $record) {
                // Pastikan data item termuat
                $totalBeli = $record->items->sum('quantity');
                
                // Cari history retur dengan pencarian text yang lebih luas (%)
                // Agar mendeteksi "Retur Order #10" maupun "Retur Order #10 (PO Batal)"
                $totalRetur = StockMovement::where('note', 'like', "%Retur Order #{$record->id}%")
                    ->where('movement_type', 'in') // In = Barang Masuk (Retur)
                    ->sum('quantity');

                // Jika jumlah yang diretur sudah sama atau lebih dari yang dibeli
                if ($totalBeli > 0 && $totalRetur >= $totalBeli) {
                    return 'opacity-50 bg-gray-100 pointer-events-none'; // Efek mati/disable
                }
                
                return null;
            })
            ->columns([
                Tables\Columns\TextColumn::make('custom_id')
                    ->label('ID Pesanan')
                    ->getStateUsing(function ($record) {
                        if (!$record->user) return '-';
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

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->colors([
                        'success' => 'cash',
                        'info' => 'kjp',
                        'warning' => 'transfer_bank',
                        'primary' => 'e_wallet',
                    ]),

                ImageColumn::make('proof_of_payment')
                    ->label('Bukti Bayar')
                    ->disk('public')
                    ->width(80)
                    ->height(80)
                    ->square(),

                Tables\Columns\TextColumn::make('is_preorder')
                    ->label('Pre-Order?')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->items->contains(fn($item) => $item->is_preorder) ? 'Ya' : 'Tidak')
                    ->colors([
                        'warning' => 'Ya',
                        'success' => 'Tidak',
                    ]),

                Tables\Columns\SelectColumn::make('payment_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'cash' => 'Cash',
                        'paid' => 'Paid',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->timezone('Asia/Jakarta')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                // 🔥 FITUR RETUR BARANG (VALIDASI KETAT & FIX PENGURANGAN) 🔥
                Action::make('retur_barang')
                    ->label(fn (Order $record) => 
                        (StockMovement::where('note', 'like', "%Retur Order #{$record->id}%")->where('movement_type', 'in')->sum('quantity') >= $record->items->sum('quantity')) 
                        ? 'Sudah Diretur' 
                        : 'Retur'
                    )
                    ->icon(fn (Order $record) => 
                        (StockMovement::where('note', 'like', "%Retur Order #{$record->id}%")->where('movement_type', 'in')->sum('quantity') >= $record->items->sum('quantity'))
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-arrow-path'
                    )
                    ->color(fn (Order $record) => 
                        (StockMovement::where('note', 'like', "%Retur Order #{$record->id}%")->where('movement_type', 'in')->sum('quantity') >= $record->items->sum('quantity'))
                        ? 'gray'
                        : 'danger'
                    )
                    
                    // Disable tombol jika sudah full retur
                    ->disabled(function (Order $record) {
                        $totalBeli = $record->items->sum('quantity');
                        $totalRetur = StockMovement::where('note', 'like', "%Retur Order #{$record->id}%")
                            ->where('movement_type', 'in')
                            ->sum('quantity');
                        return $totalRetur >= $totalBeli;
                    })

                    ->modalHeading('Retur Barang')
                    ->modalDescription('Pilih produk yang dikembalikan. Produk yang sudah diretur sepenuhnya akan hilang dari daftar.')
                    ->form([
                        // 1. Pilih Produk (DIFILTER: Hanya yang punya sisa qty)
                        Select::make('order_item_id')
                            ->label('Pilih Produk')
                            ->options(function (Order $record) {
                                return $record->items->map(function ($item) use ($record) {
                                    // Hitung yg sudah diretur spesifik item ini
                                    $alreadyReturned = StockMovement::where('note', 'like', "%Retur Order #{$record->id}%")
                                        ->where('product_id', $item->product_id)
                                        ->where('product_size_id', $item->product_size_id)
                                        ->where('movement_type', 'in') 
                                        ->sum('quantity');

                                    $item->remaining_qty = max(0, $item->quantity - $alreadyReturned);
                                    return $item;
                                })
                                ->filter(fn($item) => $item->remaining_qty > 0) // HILANGKAN YANG SISA 0
                                ->mapWithKeys(function ($item) {
                                    $label = $item->product->title;
                                    if ($item->productSize) {
                                        $label .= ' (' . $item->productSize->size . ')';
                                    }
                                    $type = $item->is_preorder ? '[PO]' : '[Ready]';
                                    $label .= " $type - Sisa: " . $item->remaining_qty . ' pcs';
                                    
                                    return [$item->id => $label];
                                });
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('quantity', 1)),

                        // 2. Input Jumlah Retur
                        TextInput::make('quantity')
                            ->label('Jumlah Retur')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(function (callable $get, Order $record) {
                                $itemId = $get('order_item_id');
                                if (!$itemId) return 1;
                                
                                $item = $record->items->where('id', $itemId)->first();
                                if (!$item) return 1;

                                $alreadyReturned = StockMovement::where('note', 'like', "%Retur Order #{$record->id}%")
                                    ->where('product_id', $item->product_id)
                                    ->where('product_size_id', $item->product_size_id)
                                    ->where('movement_type', 'in')
                                    ->sum('quantity');

                                return max(1, $item->quantity - $alreadyReturned);
                            })
                            ->required()
                            ->suffix('pcs'),

                        // 3. Alasan Retur
                        Textarea::make('note')
                            ->label('Alasan Retur')
                            ->placeholder('Contoh: Salah ukuran, Batal PO')
                            ->required(),
                    ])
                    ->action(function (array $data, Order $record) {
                    $orderItem = OrderItem::find($data['order_item_id']);

                    if (!$orderItem) {
                        Notification::make()->title('Item tidak ditemukan')->danger()->send();
                        return;
                    }

                    // === 🔥 LOGIKA RETUR (VERSI FIXED) 🔥 ===

                    if ($orderItem->is_preorder) {
                        // [KASUS A] RETUR PRE-ORDER (BATAL PESAN)
                        $product = $orderItem->product;
                        if ($product && $product->preorder_quantity > 0) {
                            $qtyToReduce = min($data['quantity'], $product->preorder_quantity);
                            $product->decrement('preorder_quantity', $qtyToReduce);
                        }

                        $orderItem->update(['preorder_status' => 'cancelled']);

                        $movement = new StockMovement();
                        $movement->product_id = $orderItem->product_id;
                        $movement->product_size_id = $orderItem->product_size_id;
                        $movement->movement_type = 'in'; 
                        $movement->quantity = $data['quantity'];
                        $movement->note = "Retur Order #{$record->id} (PO Batal): " . $data['note'];
                        $movement->is_preorder = true;
                        $movement->skipProductUpdate = true;
                        $movement->save();

                        $pesan = "Pre-order produk {$orderItem->product->title} dibatalkan. Antrian berkurang.";

                    } else {
                        // [KASUS B] RETUR READY STOCK (BARANG FISIK KEMBALI)
                        
                        $currentStockBalance = 0;

                        // 1. Tambah Stok Fisik
                        
                        if ($orderItem->product_size_id) {
                            // === JIKA ADA UKURAN (SERAGAM) ===
                            $size = \App\Models\ProductSize::find($orderItem->product_size_id);
                            
                            // Tambah manual & simpan diam-diam
                            $size->stock += $data['quantity'];
                            $size->saveQuietly(); 
                            
                            $currentStockBalance = $size->stock;
                            
                            // Sinkronkan total ke produk induk
                            $total = \App\Models\ProductSize::where('product_id', $orderItem->product_id)->sum('stock');
                            
                            // 🔥 PERBAIKAN DISINI: Ganti updateQuietly jadi update biasa 🔥
                            // Karena 'where' itu Builder, update() via Builder otomatis tidak memicu observer
                            \App\Models\Product::where('id', $orderItem->product_id)->update(['stock' => $total]);

                        } else {
                            // === JIKA TANPA UKURAN ===
                            $product = \App\Models\Product::find($orderItem->product_id);
                            
                            // Tambah manual & simpan diam-diam
                            $product->stock += $data['quantity'];
                            $product->saveQuietly(); 
                            
                            $currentStockBalance = $product->stock;
                        }

                        // 2. Catat Log Retur
                        StockMovement::create([
                            'product_id'      => $orderItem->product_id,
                            'product_size_id' => $orderItem->product_size_id,
                            'movement_type'   => 'in', // Stok Masuk Kembali
                            'quantity'        => $data['quantity'],
                            'balance_before'  => $currentStockBalance - $data['quantity'], 
                            'balance_after'   => $currentStockBalance,
                            'note'            => "Retur Order #{$record->id}: " . $data['note'],
                            'is_preorder'     => false,
                            'skipProductUpdate' => true 
                        ]);

                        $pesan = "Stok produk {$orderItem->product->title} telah dikembalikan (+{$data['quantity']}).";
                    }

                    Notification::make()
                        ->title('Retur Berhasil')
                        ->body($pesan)
                        ->success()
                        ->send();
                }),
            ]);
    }
}