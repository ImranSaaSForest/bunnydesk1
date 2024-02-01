<?php

namespace App\Filament\Resources\Employee\EmployeeResource\Pages;

use Akaunting\Money\View\Components\Currency;
use App\Filament\Resources\Employee\EmployeeResource;
use App\Models\Role;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Squire\Models\Country;
use Squire\Models\Currency as ModelsCurrency;
use PragmaRX\Countries\Package\Countries;


class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    public  function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            //     ->schema([
            //         Section::make()
            // ->schema([
            //     // ...

            //         Infolists\Components\TextEntry::make('name'),
            //         Infolists\Components\TextEntry::make('email')
            //             ->columnSpanFull(),
            //             ])
            //     ]);
            ->schema([

                Infolists\Components\TextEntry::make('name')
                    ->label('First Name'),
                Infolists\Components\TextEntry::make('last_name')
                    ->label('Last Name'),
                Infolists\Components\TextEntry::make('email'),
                Section::make('Personal Details')
                    ->relationship('employee')
                    ->schema([
                        Infolists\Components\TextEntry::make('company.name'),
                        Infolists\Components\TextEntry::make('date_of_birth'),
                        Infolists\Components\TextEntry::make('gender.name'),
                        Infolists\Components\TextEntry::make('maritalStatus.name'),
                        Infolists\Components\TextEntry::make('social_security_number'),
                        Infolists\Components\TextEntry::make('timezone'),
                        ImageEntry::make('profile_picture_url')

                    ])
                    ->columns(2),


                Section::make('Roles')
                    ->schema([
                        Infolists\Components\TextEntry::make('roles.name'),
                    ]),


                // Section::make('Address')
                //     ->schema([
                //         Infolists\Components\TextEntry::make('currentAddress.country')->label('Country')
                //             ->formatStateUsing(
                //                 function ($state) {
                //                     $countries = new Countries();

                //                     $country = $countries->where('cca3', $state);
                //                     //   dd($country);
                //                     if ($country) {
                //                         return $country[$state]->name->common;
                //                     }
                //                 }
                //             ),
                Section::make('Address')
                    ->schema([
                        Infolists\Components\TextEntry::make('currentAddress.country')->label('Country')
                            ->formatStateUsing(function ($state) {
                                $countries = new \League\ISO3166\ISO3166();
                                $countryList = [];
                                foreach ($countries->all() as $country) {
                                    $countryList[$country['alpha2']] = $country['name'];
                                }
                                return $countryList[$state];
                            }),
                        Infolists\Components\TextEntry::make('currentAddress.street')->label('Street address'),
                        Infolists\Components\TextEntry::make('currentAddress.city')->label('City'),
                        Infolists\Components\TextEntry::make('currentAddress.state')->label('State / Province'),
                        Infolists\Components\TextEntry::make('currentAddress.zip')->label('Zip / Postal code'),


                    ])->columns(3),

                Section::make('Contact Info')
                    ->relationship('contact')
                    ->schema([

                        Infolists\Components\TextEntry::make('work_phone'),
                        Infolists\Components\TextEntry::make('mobile_phone'),
                        // ->inputNumberFormat(PhoneInputNumberType::NATIONAL),
                        // Infolists\Components\TextEntry::make('mobile_phone')
                        //     ->tel()
                        //     ->maxLength(20),
                        Infolists\Components\TextEntry::make('home_phone'),
                        Infolists\Components\TextEntry::make('home_email')

                    ])
                    ->columns(2),


                Section::make('Job Info')
                    ->relationship('jobInfo')
                    ->schema([

                        Infolists\Components\TextEntry::make('designation.name')
                            ->label('Designation'),
                        Infolists\Components\TextEntry::make('supervisor.name')
                            ->label('Report To'),
                        Infolists\Components\TextEntry::make('team.name'),
                        Infolists\Components\TextEntry::make('shift.name')
                    ])
                    ->columns(2),

                Section::make('Compensation')
                    ->relationship('salaryDetail')
                    ->schema([
                        Infolists\Components\TextEntry::make('paymentinterval.name')
                            ->label('payment interval'),

                        Infolists\Components\TextEntry::make('paymentMethod.name'),
                        Infolists\Components\TextEntry::make('amount'),
                        Infolists\Components\TextEntry::make('currency'),
                    ])
                    ->columns(2),

                Section::make('Policies')
                    ->relationship('UserPayrollPolicy')
                    ->schema([
                        Infolists\Components\TextEntry::make('payrollPolicy.name')
                    ]),
                Section::make('Bank Info')
                    ->relationship('bankInfo')
                    ->schema([
                        Infolists\Components\TextEntry::make('bank_name'),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('ifsc'),
                        Infolists\Components\TextEntry::make('micr'),
                        Infolists\Components\TextEntry::make('account_number'),
                        Infolists\Components\TextEntry::make('branch_code'),
                    ])
                    ->columns(2),


                // // ->visible(function(){
                // //                     if(auth()->user()->hasPermissionTo('Employee Profiles')){
                // //                         return true;
                // //                     }
                // //                 }),
                Section::make('Employment')
                    ->relationship('employment')
                    ->schema([
                        Infolists\Components\TextEntry::make('employeeType.name'),
                        Infolists\Components\TextEntry::make('employeeStatus.name'),
                        Infolists\Components\TextEntry::make('employment_id')->label('Employee ID'),
                        Infolists\Components\TextEntry::make('hired_on'),
                        Infolists\Components\TextEntry::make('effective_date')->columnSpan('full'),
                    ])
                    ->columns(2),

                // Section::make('Contract')
                //     ->relationship('contract')
                //     ->schema([
                //         Infolists\Components\TextEntry::make('start_date'),
                //         Infolists\Components\TextEntry::make('end_date'),
                //         Infolists\Components\TextEntry::make('terms')->columnSpan('full'),
                //     ])
                //     ->columns(2),



            ]);
    }
}
