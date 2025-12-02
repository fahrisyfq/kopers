<?php

namespace App\Filament\Resources;

use App\Models\User;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Data Siswa';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nisn')
                ->label('NISN')
                ->required()
                ->maxLength(50),

            Forms\Components\TextInput::make('nis')
                ->label('NIS')
                ->required()
                ->maxLength(50),

            Forms\Components\TextInput::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('kelas')
                ->label('Kelas')
                ->options([
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    'Belum Ditentukan' => 'Belum Ditentukan',
                ])
                ->default('Belum Ditentukan'),

            Forms\Components\Select::make('jurusan')
                ->label('Jurusan')
                ->options([
                    'AKL 1' => 'AKL 1',
                    'AKL 2' => 'AKL 2',
                    'AKL 3' => 'AKL 3',
                    'MP 1' => 'MP 1',
                    'Manlog' => 'Manlog',
                    'BR 1' => 'BR 1',
                    'BR 2' => 'BR 2',
                    'BD' => 'BD',
                    'UPW' => 'UPW',
                    'RPL' => 'RPL',
                    'Belum Ditentukan' => 'Belum Ditentukan',
                ])
                ->default('Belum Ditentukan'),

            Forms\Components\TextInput::make('no_telp_siswa')
                ->label('No. Telp Siswa')
                ->tel()
                ->maxLength(20)
                ->helperText('Gunakan format +62xxxxxx'),

            Forms\Components\TextInput::make('no_telp_ortu')
                ->label('No. Telp Orang Tua')
                ->tel()
                ->maxLength(20)
                ->helperText('Gunakan format +62xxxxxx'),

            Forms\Components\Toggle::make('is_blocked')
                ->label('Blokir Siswa Ini?')
                ->onColor('danger')
                ->offColor('success')
                ->helperText('Jika aktif, siswa tidak bisa melakukan pemesanan.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nisn')->label('NISN')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nis')->label('NIS')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama Lengkap')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('kelas')->label('Kelas')->sortable(),
                Tables\Columns\TextColumn::make('jurusan')->label('Jurusan')->sortable(),
                Tables\Columns\TextColumn::make('no_telp_siswa')
                    ->label('No. Siswa')
                    ->formatStateUsing(fn($state) => $state ?: '-')
                    ->url(fn($record) => $record->no_telp_siswa 
                        ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $record->no_telp_siswa)
                        : null,
                        shouldOpenInNewTab: true)
                    ->color('success')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                Tables\Columns\TextColumn::make('no_telp_ortu')
                    ->label('No. Ortu')
                    ->formatStateUsing(fn($state) => $state ?: '-')
                    ->url(fn($record) => $record->no_telp_ortu
                        ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $record->no_telp_ortu)
                        : null,
                        shouldOpenInNewTab: true)
                    ->color('warning')
                    ->icon('heroicon-o-chat-bubble-left'),
                Tables\Columns\TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label('Jumlah Pesanan')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_blocked')
                    ->label('Status Blokir')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->tooltip(fn (User $record) => $record->is_blocked ? 'Siswa Diblokir' : 'Akun Aman'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->options([
                        '10' => 'Kelas 10',
                        '11' => 'Kelas 11',
                        '12' => 'Kelas 12',
                        'Belum Ditentukan' => 'Belum Ditentukan',
                    ]),
                Tables\Filters\SelectFilter::make('jurusan')
                    ->label('Filter Jurusan')
                    ->options([
                        'AKL 1' => 'AKL 1',
                        'AKL 2' => 'AKL 2',
                        'AKL 3' => 'AKL 3',
                        'MP 1' => 'MP 1',
                        'Manlog' => 'Manlog',
                        'BR 1' => 'BR 1',
                        'BR 2' => 'BR 2',
                        'BD' => 'BD',
                        'UPW' => 'UPW',
                        'RPL' => 'RPL',
                        'Belum Ditentukan' => 'Belum Ditentukan',
                    ]),

                // 🔥 FILTER SISWA BERMASALAH 🔥
                Tables\Filters\Filter::make('blocked')
                    ->label('Hanya Siswa Diblokir')
                    ->query(fn ($query) => $query->where('is_blocked', true)),
                    
                Tables\Filters\SelectFilter::make('kelas')->options(['10'=>'10','11'=>'11','12'=>'12']),
            ])  
            ->headerActions([
                // 💡 TOMBOL BARU DITAMBAHKAN DI SINI
                Action::make('lihatProdukPesanan')
                    ->label('Produk-Pesanan')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('warning') 
                    ->url(fn() => Pages\ViewProductOrders::getUrl()), // Arahkan ke Halaman baru

                // 🔹 Tombol baru: Lihat Pre Order
                Action::make('lihatPreOrder')
                    ->label('Lihat Pre Order')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->url(fn() => Pages\ViewPreOrders::getUrl()),

                // 🔹 Export ke Excel
                Action::make('exportExcel')
                    ->label('Export ke Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire) {
    $query = $livewire->getFilteredTableQuery();
    $filteredUsers = $query->with(['orders.items.product', 'orders.items.size'])->get();

    $filters = $livewire->tableFilters ?? [];
    $fileName = 'Data_Siswa';

    if (!empty($filters['kelas']['value'])) {
        $fileName .= '_' . $filters['kelas']['value'];
    }
    if (!empty($filters['jurusan']['value'])) {
        $fileName .= '_' . str_replace(' ', '', $filters['jurusan']['value']);
    }
    $fileName = preg_replace('/[^A-Za-z0-9_]/', '', $fileName) . '.xlsx';

    $data = collect();

    foreach ($filteredUsers as $user) {
        $produkList = collect();

        foreach ($user->orders ?? [] as $order) {
            foreach ($order->items ?? [] as $item) {
                $isPreOrder = $item->is_preorder;
                $statusLabel = $item->status_label ?? 'Ready Stock';

                $produkList->push(
                    "• " . ($item->product?->title ?? '-') .
                    " (" . ($item->size?->size ?? '-') . ")" .
                    " — " . $statusLabel .
                    "\n   ↳ Status: " . ucfirst($order->payment_status ?? '-')
                );

            }
        }


        $data->push([
            'NISN' => $user->nisn,
            'NIS' => $user->nis,
            'Nama Lengkap' => $user->nama_lengkap,
            'Kelas' => $user->kelas,
            'Jurusan' => $user->jurusan,
            'Produk yang Dipesan' => $produkList->implode("\n\n"),
        ]);
    }

    return Excel::download(
        new class($data) implements 
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithColumnWidths,
            \Maatwebsite\Excel\Concerns\WithDefaultStyles
        {
            public function __construct(public $data) {}
            public function collection() { return $this->data; }
            public function headings(): array {
                return ['NISN', 'NIS', 'Nama Lengkap', 'Kelas', 'Jurusan', 'Produk yang Dipesan'];
            }
            public function columnWidths(): array {
                return [
                    'A' => 12,
                    'B' => 10,
                    'C' => 25,
                    'D' => 10,
                    'E' => 15,
                    'F' => 80, // Lebarkan kolom produk
                ];
            }
            public function defaultStyles(\PhpOffice\PhpSpreadsheet\Style\Style $defaultStyle) {
                $defaultStyle->getFont()->setName('Calibri')->setSize(11);
            }
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ];

                $dataStyle = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ];

                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
                $sheet->getStyle("A2:F{$lastRow}")->applyFromArray($dataStyle);

                // Sesuaikan tinggi baris otomatis
                for ($i = 1; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(-1);
                }
            }
        },
        $fileName
    );
}),



                // 🔹 Export ke PDF
                Action::make('exportPdf')
                    ->label('Export ke PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function ($livewire) {
                        $query = $livewire->getFilteredTableQuery();
                        $users = $query->with(['orders.items.product', 'orders.items.size'])->get();

                        // Ambil filter aktif dari tabel
                        $filters = $livewire->tableFilters ?? [];

                        // Buat nama file dinamis
                        $fileName = 'Data_Siswa';
                        if (!empty($filters['kelas']['value'])) {
                            $fileName .= '_' . $filters['kelas']['value'];
                        }
                        if (!empty($filters['jurusan']['value'])) {
                            $fileName .= '_' . str_replace(' ', '', $filters['jurusan']['value']);
                        }

                        $fileName = preg_replace('/[^A-Za-z0-9_]/', '', $fileName) . '.pdf';

                        // Generate PDF
                        $pdf = Pdf::loadView('exports.users-pdf', ['users' => $users])
                            ->setPaper('a4', 'landscape');

                        return response()->streamDownload(fn() => print($pdf->output()), $fileName);
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('toggle_block')
                    ->label(fn (User $record) => $record->is_blocked ? 'Buka Blokir' : 'Blokir Siswa')
                    ->icon(fn (User $record) => $record->is_blocked ? 'heroicon-o-lock-open' : 'heroicon-o-no-symbol')
                    ->color(fn (User $record) => $record->is_blocked ? 'success' : 'danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record) => $record->is_blocked ? 'Buka Blokir Siswa?' : 'Blokir Siswa Ini?')
                    ->modalDescription(fn (User $record) => $record->is_blocked 
                        ? 'Siswa akan dapat melakukan pemesanan kembali.' 
                        : 'Siswa tidak akan bisa checkout atau menambah barang ke keranjang.')
                    ->action(function (User $record) {
                        $record->is_blocked = !$record->is_blocked;
                        $record->save();

                        Notification::make()
                            ->title($record->is_blocked ? 'Siswa Diblokir' : 'Blokir Dibuka')
                            ->body('Status akun siswa berhasil diperbarui.')
                            ->status($record->is_blocked ? 'warning' : 'success')
                            ->send();
                    }),
                Action::make('cetak_tagihan')
                    ->label('Cetak Tagihan')
                    ->icon('heroicon-o-printer')
                    ->color('warning') // Warna kuning biar beda
                    ->action(function (User $record) {
                        // 1. Ambil order yang BELUM dicetak
                        $orders = $record->orders()
                                    ->where('is_printed', false)
                                    ->with(['items.product', 'items.productSize'])
                                    ->get();

                        // 2. Validasi jika kosong
                        if ($orders->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada tagihan baru')
                                ->body('Semua pesanan siswa ini sudah dicetak sebelumnya.')
                                ->warning()
                                ->send();
                            return;
                        }

                        // 3. Tandai sebagai sudah dicetak (Update Database)
                        // Kita update dulu sebelum download agar tidak tercetak 2x
                        // Gunakan pluck id agar query efisien
                        \App\Models\Order::whereIn('id', $orders->pluck('id'))
                            ->update(['is_printed' => true]);

                        // 4. Generate PDF
                        $pdf = Pdf::loadView('pdf.tagihan_siswa', [
                            'user' => $record,
                            'orders' => $orders
                        ])->setPaper('a4', 'portrait');

                        // 5. Download
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, 'Tagihan-' . $record->nisn . '-' . now()->timestamp . '.pdf');
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cetak Tagihan Baru?')
                    ->modalDescription('Sistem hanya akan mencetak pesanan yang BELUM pernah dicetak sebelumnya. Setelah dicetak, pesanan akan ditandai sebagai "Sudah Dicetak".')
                    ->modalSubmitActionLabel('Ya, Cetak & Tandai'),
            ])
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [OrdersRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            // 💡 DAFTARKAN HALAMAN BARU ANDA DI SINI
            'product-orders' => Pages\ViewProductOrders::route('/product-orders'),
            'preorders' => Pages\ViewPreOrders::route('/preorders'),
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
