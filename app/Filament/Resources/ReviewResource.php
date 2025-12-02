<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationLabel = 'Ulasan Siswa';
    protected static ?string $modelLabel = 'Ulasan';
    protected static ?string $navigationGroup = 'Manajemen Konten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Ulasan')->schema([
                    // Menampilkan Nama Produk (Read Only)
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'title')
                        ->label('Produk')
                        ->disabled(),

                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'nama_lengkap') // Sesuaikan nama kolom user kamu
                        ->label('Pengulas')
                        ->disabled(),
                    
                    Forms\Components\TextInput::make('rating')
                        ->numeric()
                        ->label('Rating')
                        ->disabled(),
                    
                    Forms\Components\Textarea::make('body')
                        ->label('Isi Ulasan')
                        ->columnSpanFull()
                        ->rows(4)
                        ->disabled(),
                    
                    Forms\Components\Group::make([
                        Forms\Components\Toggle::make('is_anonymous')
                            ->label('Anonim')
                            ->disabled(),
                        
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Setujui & Tampilkan')
                            ->onColor('success'),
                    ])->columns(2),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Nama Produk
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Produk')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('user.nama_lengkap')
                    ->label('Pengulas')
                    ->searchable()
                    ->description(fn (Review $record): string => $record->is_anonymous ? '(Anonim)' : ''),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (string $state): string => str_repeat('⭐', (int) $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('body')
                    ->label('Ulasan')
                    ->limit(30),

                Tables\Columns\ToggleColumn::make('is_approved')
                    ->label('Tampil'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->label('Tanggal')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('pending')
                    ->label('Menunggu Persetujuan')
                    ->query(fn ($query) => $query->where('is_approved', false))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve_all')
                        ->label('Setujui Semua')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true])),
                ]),
            ]);
    }
    
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}