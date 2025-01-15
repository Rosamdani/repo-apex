<?php

namespace App\Filament\Resources\TryoutsResource\RelationManagers;

use App\Imports\ImportQuestions;
use App\Models\BidangTryouts;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $title = 'List Soal';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bidang_id')
                    ->native(false)
                    ->placeholder('Pilih salah satu')
                    ->relationship('bidang', 'nama')
                    ->columnSpanFull()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama')
                            ->placeholder('Masukkan nama bidang')
                            ->unique(BidangTryouts::class, 'nama')
                            ->validationMessages([
                                'unique' => 'Nama bidang sudah digunakan',
                            ])
                            ->required(),
                    ])
                    ->required(),
                Forms\Components\RichEditor::make('soal')
                    ->required()
                    ->fileAttachmentsDirectory('attachments')
                    ->fileAttachmentsVisibility('public')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pilihan_a')->label('Pilihan A')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pilihan_b')->label('Pilihan B')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pilihan_c')->label('Pilihan C')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pilihan_d')->label('Pilihan D')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pilihan_e')->label('Pilihan E')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('jawaban')
                    ->label('Pilih Jawaban Benar')
                    ->required()
                    ->options([
                        'a' => 'A',
                        'b' => 'B',
                        'c' => 'C',
                        'd' => 'D',
                        'e' => 'E',
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Tidak Ada Soal')
            ->emptyStateDescription('Tidak ada soal yang tersedia saat ini.')
            ->emptyStateIcon('heroicon-o-archive-box-x-mark')
            ->columns([
                Tables\Columns\TextColumn::make('bidang.nama'),
                Tables\Columns\TextColumn::make('soal')
                    ->searchable()
                    ->wrap(true)
                    ->formatStateUsing(fn(string $state): string => strip_tags($state)),
                Tables\Columns\TextColumn::make('pilihan_a'),
                Tables\Columns\TextColumn::make('pilihan_b'),
                Tables\Columns\TextColumn::make('pilihan_c'),
                Tables\Columns\TextColumn::make('pilihan_d'),
                Tables\Columns\TextColumn::make('pilihan_e'),
                Tables\Columns\TextColumn::make('jawaban')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bidang_id')
                    ->relationship('bidang', 'nama')
                    ->label('Bidang')
                    ->placeholder('Semua Bidang'),
            ])
            ->headerActions([
                \pxlrbt\FilamentExcel\Actions\Tables\ExportAction::make()
                    ->color('secondary')
                    ->exports([
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('table')->fromTable(),
                    ]),
                // \EightyNine\ExcelImport\Tables\ExcelImportRelationshipAction::make()
                //     ->label('Import Soal')
                //     ->slideOver()
                //     ->use(ImportQuestions::class)
                //     ->color('success')
                //     ->sampleExcel(
                //         sampleData: [
                //             [
                //                 'nomor' => 1,
                //                 'bidang' => '(Gunakan ID bidang di halaman Bidang)',
                //                 'kompetensi' => '(Gunakan ID kompetensi di halaman Kompetensi)',
                //                 'soal' => 'Apa ibu kota Indonesia?',
                //                 'pilihan_a' => 'Bandung',
                //                 'pilihan_b' => 'Jakarta',
                //                 'pilihan_c' => 'Surabaya',
                //                 'pilihan_d' => 'Medan',
                //                 'pilihan_e' => 'Yogyakarta',
                //                 'jawaban' => 'b',
                //             ],
                //             [
                //                 'nomor' => 2,
                //                 'bidang' => '(Gunakan ID bidang di halaman Bidang)',
                //                 'kompetensi' => '(Gunakan ID kompetensi di halaman Kompetensi)',
                //                 'soal' => 'Berapa hasil dari 2 + 2?',
                //                 'pilihan_a' => '3',
                //                 'pilihan_b' => '4',
                //                 'pilihan_c' => '5',
                //                 'pilihan_d' => '6',
                //                 'pilihan_e' => '7',
                //                 'jawaban' => 'b',
                //             ],
                //         ],
                //         fileName: 'sample.xlsx',
                //         exportClass: \App\Exports\SampleExport::class,
                //         sampleButtonLabel: 'Download Sample',
                //         customiseActionUsing: fn(Action $action) => $action->color('secondary')
                //             ->icon('heroicon-m-clipboard')
                //             ->requiresConfirmation(),
                //     ),
                Tables\Actions\CreateAction::make()->label('Tambah Soal')->icon('heroicon-o-plus'),
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
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->questions ? $ownerRecord->questions->count() : null;
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
