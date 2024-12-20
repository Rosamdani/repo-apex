<?php

namespace App\Filament\Pages\Settings;

use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
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
                                ->label('Logo')
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
                            ColorPicker::make('general.primary_color')
                                ->default('#D6D025')
                                ->hint('Warna utama website')
                                ->label('Warna Utama'),
                            ColorPicker::make('general.secondary_color')
                                ->default('#FFFFFF')
                                ->hint('Warna background website')
                                ->label('Warna Sekunder'),
                            ColorPicker::make('general.third_color')
                                ->default('#000000')
                                ->hint('Warna button website')
                                ->label('Warna Tersier'),
                        ]),
                ]),
        ];
    }
}
