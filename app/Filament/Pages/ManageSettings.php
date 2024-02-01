<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use App\Settings\GeneralSettings;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = GeneralSettings::class;

    protected static ?string $title = 'General Setting';

    public $image;

    public function form(Form $form): Form
    {

        $generalSetting = app(GeneralSettings::class);
        // $this->image=$generalSetting->company_logo;
        // dd($this->image);

        return $form
            ->schema([


                Section::make('Company')
                ->schema([
                    FileUpload::make('company_logo')
                        ->image()->label('Company Logo')->required(),
                    // Toggle::make('company_logo'),
                    FileUpload::make('favicon')->label('Favicon')->required(),
                ]),
                Section::make('Modules')
                    ->schema([
                        Toggle::make('employee_directory'),
                        Toggle::make('time_off'),
                        Toggle::make('time_sheet'),
                        Toggle::make('hr_tool'),
                        Toggle::make('help_desk'),
                        Toggle::make('recruitment'),
                        Toggle::make('insights'),
                        Toggle::make('lms'),
                        Toggle::make('attendance'),
                        Toggle::make('forum'),
                        Toggle::make('finance'),
                        Toggle::make('settings'),

                    ])
                    ->columns(4),


                Section::make('Attendance')
                    ->schema([
                        Toggle::make('paid_break'),
                        Toggle::make('unpaid_break'),
                    ])
                    ->columns(2),

                Section::make('Notification')
                    ->schema([
                        Toggle::make('app_notification'),
                        Toggle::make('email_notification'),
                    ])
                    ->columns(2),


               
            ]);
    }
}
