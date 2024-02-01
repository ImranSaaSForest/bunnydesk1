<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Hash;
use Shipu\WebInstaller\Concerns\StepContract;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;

class ApplicationFields implements StepContract
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.installer';

    public static function form(): array
    {
        $applicationFields = [];

        foreach (config('installer.applications', []) as $key => $value) {
            if ($key == 'admin.password') {
                $applicationFields[] = TextInput::make('applications.'.$key)
                    ->label($value['label'])
                    ->password()
                    ->maxLength(255)
                    ->required($value['required'])

                    ->default($value['default'])
                    ->dehydrateStateUsing(fn($state) => ! empty($state)
                        ? Hash::make($state) : "");
            } else {
                $applicationFields[] = TextInput::make('applications.'.$key)
                    ->label($value['label'])
                    ->required($value['required'])
                    ->rules($value['rules'])
                    ->default($value['default'] ?? '');
            }
        }

        return $applicationFields;
    }
    public static function make(): Step
    {
        return Step::make('application')
            ->label('Application Settings')
            ->schema(self::form())
    
            ;
    }
}
