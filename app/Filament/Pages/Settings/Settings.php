<?php

namespace App\Filament\Pages\Settings;

use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;

class Settings extends BaseSettings
{

    public static function getNavigationGroup(): string
    {
        return 'Settings';
    }
    public static function getNavigationLabel(): string
    {
        return 'Website Settings';
    }

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->schema([
                            TextInput::make('general.app_name'),
                            TextInput::make('general.app_url')
                                ->url(),
                            FileUpload::make('general.logo')
                                ->label('Logo Dark')
                                ->hint('Harap menggunakan logo yang lebih gelap')
                                ->openable()
                                ->disk('public')
                                ->acceptedFileTypes(['image/*'])
                                ->directory('/assets/settings'),
                            FileUpload::make('general.logo_dark')
                                ->hint('Harap menggunakan logo yang lebih cerah')
                                ->label('Logo Light')
                                ->openable()
                                ->disk('public')
                                ->acceptedFileTypes(['image/*'])
                                ->directory('/assets/settings'),
                            FileUpload::make('general.favicon')
                                ->label('Favicon')
                                ->openable()
                                ->disk('public')
                                ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml'])
                                ->directory('/assets/settings'),
                        ]),
                    Tabs\Tab::make('Palette Warna')
                        ->schema([
                            Section::make('Warna Utama')->collapsed()->description('Warna yang digunakan untuk tombol, link, teks penting')->schema([
                                ColorPicker::make('color.primary-color-50')
                                    ->default('#e4f1ff')
                                    ->label('50'),
                                ColorPicker::make('color.primary-color-100')
                                    ->default('#bfdcff')
                                    ->label('100'),
                                ColorPicker::make('color.primary-color-200')
                                    ->default('#95c7ff')
                                    ->label('200'),
                                ColorPicker::make('color.primary-color-300')
                                    ->default('#6bb1ff')
                                    ->label('300'),
                                ColorPicker::make('color.primary-color-400')
                                    ->default('#519fff')
                                    ->label('400'),
                                ColorPicker::make('color.primary-color-500')
                                    ->default('#458eff')
                                    ->label('500'),
                                ColorPicker::make('color.primary-color-600')
                                    ->hint('Digunakan pada tombol navigasi dan text penting')
                                    ->default('#487fff')
                                    ->label('600'),
                                ColorPicker::make('color.primary-color-700')
                                    ->default('#486cea')
                                    ->label('700'),
                                ColorPicker::make('color.primary-color-800')
                                    ->default('#4759d6')
                                    ->label('800'),
                                ColorPicker::make('color.primary-color-900')
                                    ->default('#4536b6')
                                    ->label('900'),
                            ]),
                        ]),
                    Tabs\Tab::make('Halaman Autentikasi')
                        ->schema([
                            FileUpload::make('auth.login_image')
                                ->image()
                                ->disk('public')
                                ->hint('(Ukuran gambar 917x917)')
                                ->directory('/asset/image/auth'),
                        ]),
                ]),
        ];
    }
}
