<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TryoutsResource\Pages;
use App\Filament\Resources\TryoutsResource\RelationManagers;
use App\Models\BatchTryouts;
use App\Models\Tryouts;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TryoutsResource extends Resource
{
    protected static ?string $model = Tryouts::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?string $navigationGroup = 'Tryouts';

    protected static ?string $navigationLabel = 'Tryouts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tryout')->columnSpanFull()->schema([
                    Tabs\Tab::make('Informasi')->schema([
                        Forms\Components\Select::make('batch_id')
                            ->relationship('batch', 'nama')
                            ->columnSpanFull()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama')
                                    ->placeholder('Masukkan nama batch')
                                    ->unique(BatchTryouts::class, 'nama')
                                    ->validationMessages([
                                        'unique' => 'Nama batch sudah digunakan',
                                    ])
                                    ->required(),
                                Forms\Components\DatePicker::make('start_date')->label('Tanggal Mulai')
                                    ->native(false)
                                    ->minDate(now())
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')->label('Tanggal Selesai')
                                    ->native(false)
                                    ->minDate(fn(?BatchTryouts $record): string => $record?->start_date ?? now())
                                    ->required(),
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('nama')
                            ->placeholder('Nama Tryout')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\TextInput::make('waktu')
                            ->numeric()
                            ->placeholder('Waktu Pengerjaan Tryout (menit)')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Dibuat')
                            ->default(now())
                            ->native(false)
                            ->displayFormat('l, d F Y')
                            ->locale('id')
                            ->columnSpanFull(),
                        Forms\Components\ToggleButtons::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Aktif',
                                'nonaktif' => 'Non-Aktif',
                            ])
                            ->hint('Aktifkan apabila tryout sudah siap di publish')
                            ->icons([
                                'active' => 'heroicon-o-check-circle',
                                'nonaktif' => 'heroicon-o-x-circle',
                            ])
                            ->colors([
                                'active' => 'success',
                                'nonaktif' => 'danger',
                            ])
                            ->required()
                            ->default('nonaktif')
                            ->inline(),
                        Forms\Components\ToggleButtons::make('is_need_confirm')
                            ->label('Perlu Konfirmasi?')
                            ->options([
                                1 => 'Ya',
                                0 => 'Tidak',
                            ])
                            ->colors([
                                1 => 'success',
                                0 => 'danger',
                            ])
                            ->inline()
                            ->required()
                            ->default(1)
                            ->icons([
                                1 => 'heroicon-o-check-circle',
                                0 => 'heroicon-o-x-circle',
                            ]),
                        Forms\Components\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->columnSpanFull()
                            ->maxSize(5120)

                            ->validationMessages(['maxSize' => 'Ukuran gambar terlalu besar'])
                            ->directory('asset/tryouts/image')
                            ->disk('public'),
                    ]),
                    Tabs\Tab::make('Detail')->schema([
                        Forms\Components\RichEditor::make('deskripsi')
                            ->label('Deskripsi')
                            ->required(),
                        Forms\Components\TextInput::make('harga')
                            ->label('Harga Produk')
                            ->hint('Kosongkan jika gratis (free)')
                            ->numeric()
                            ->placeholder('Masukkan harga produk apabila pembelian secara satuan'),
                        Forms\Components\TextInput::make('url')
                            ->label('Link Pembelian')
                            ->placeholder('https://shopee.co.id/.....')
                            ->hint('Anda dapat memasukkan link pembelian produk seperti link shopee, tokopedia, dll.')
                            ->url(),
                        Forms\Components\FileUpload::make('file_pembahasan')
                            ->label('Upload File')
                            ->disk('local')
                            ->downloadable()
                            ->directory('pembahasan')
                            ->acceptedFileTypes(['application/pdf'])
                            ->hint('Upload file pdf pembahasan')
                            ->validationMessages([
                                'maxSize' => 'The :attribute max 100mb',
                            ])
                            ->columnSpanFull(),
                    ]),
                    Tabs\Tab::make('Extras')->schema([
                        Forms\Components\Repeater::make('extra items')
                            ->relationship('extras')
                            ->schema([
                                Forms\Components\Select::make('type')->required()
                                    ->label('Tipe')
                                    ->options([
                                        'poster' => 'Poster',
                                        'button' => 'Tombol',
                                    ])
                                    ->live(),
                                Forms\Components\FileUpload::make('poster_data')
                                    ->label('Gambar Poster')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                                    ->visible(fn(Get $get): bool => $get('type') === 'poster')
                                    ->disabled(fn(Get $get): bool => $get('type') !== 'poster')
                                    ->required(),
                                Forms\Components\TextInput::make('button_data') // Berikan nama yang berbeda
                                    ->visible(fn(Get $get): bool => $get('type') === 'button')
                                    ->disabled(fn(Get $get): bool => $get('type') !== 'button')
                                    ->label('URL Tombol')
                                    ->url()
                                    ->hint('Link Join Grup WhatsApp')
                                    ->placeholder('https://chat.whatsapp.com/...')
                                    ->required(),
                                Forms\Components\TextInput::make('title')
                                    ->label('Text')
                                    ->visible(fn(Get $get): bool => $get('type') === 'button')
                                    ->placeholder('Join Grup WhatsApp')
                                    ->required(),
                                Forms\Components\Select::make('display_on')
                                    ->suffixAction( // Tambahkan tombol di samping input
                                        ActionsAction::make('bantuan')
                                            ->form([
                                                Forms\Components\Placeholder::make('Tampilkan Tryout
                                                '),
                                                Forms\Components\Placeholder::make('Halaman Hasil Tryout: Tampilkan Tryout di halaman hasil Tryout'),
                                                Forms\Components\Placeholder::make('Ketika Menyelesaikan Tryout: Tampilkan Tryout ketika User menyelesaikan Tryout'),
                                                Forms\Components\Placeholder::make('Halaman Detail Tryout: Tampilkan Tryout di halaman detail Tryout'),
                                            ])
                                            ->icon('heroicon-o-question-mark-circle')
                                            ->tooltip('Klik untuk informasi lebih lanjut')
                                    )
                                    ->multiple()
                                    ->required()
                                    ->options([
                                        'result' => 'Halaman Hasil Tryout',
                                        'finished_tryout' => 'Ketika Menyelesaikan Tryout',
                                        'detail_tryout' => 'Halaman Detail Tryout',
                                    ]),

                            ])
                            ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                                if ($data['type'] === 'poster') {
                                    $data['poster_data'] = $data['data'];
                                } else if ($data['type'] === 'button') {
                                    $data['button_data'] = $data['data'];
                                }


                                return $data;
                            })
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $data['data'] = $data['poster_data'] ?? $data['button_data'] ?? null;

                                return $data;
                            })
                            ->columns(1),

                        Forms\Components\Toggle::make('is_configurable')
                            ->label('Bersyarat?')
                            ->hint('Aktifkan jika perlu upload syarat pendaftaran, nonaktifkan jika perlu upload pembayaran')
                            ->default(false),
                    ]),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->size(50)
                    ->label('Gambar'),
                Tables\Columns\TextColumn::make(name: 'nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch.nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Jumlah Soal')
                    ->counts('questions'),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'nonaktif' => 'Non Aktif',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionRelationManager::class,
            RelationManagers\TestimonisRelationManager::class,
            RelationManagers\UserTryoutsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTryouts::route('/'),
            'create' => Pages\CreateTryouts::route('/create'),
            'view' => Pages\ViewTryouts::route('/{record}'),
            'edit' => Pages\EditTryouts::route('/{record}/edit'),
        ];
    }
}
