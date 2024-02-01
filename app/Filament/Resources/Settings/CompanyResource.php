<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\CompanyResource\Pages;
use App\Filament\Resources\Settings\CompanyResource\RelationManagers;
use App\Models\Company\Company;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Cancel;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Squire\Models\Currency;
use Squire\Models\Country;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use PragmaRX\Countries\Package\Countries;


class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Company Settings';

    public static function form(Form $form): Form
    {

        $countries = new Countries();
        // get currency
        $currencies=$countries->currencies();
        // dd($countries->currencies()['INR']->units->major->symbol);
        $currencyList=[];
        foreach($currencies as $key=>$value){
            $currencyList[$key]=$value->name;
        }
        ksort($currencyList);
        // get country

        $all = $countries
        ->all()
        ->map(function ($country) {
            $commonName = $country->name->common;

            $languages = $country->languages ?? collect();

            $language = $languages->keys()->first() ?? null;

            $nativeNames = $country->name->native ?? null;

            if (
                filled($language) &&
                    filled($nativeNames) &&
                    filled($nativeNames[$language]) ?? null
            ) {
                $native = $nativeNames[$language]['common'] ?? null;
            }

            if (blank($native ?? null) && filled($nativeNames)) {
                $native = $nativeNames->first()['common'] ?? null;
            }

            $native = $native ?? $commonName;

            if ($native !== $commonName && filled($native)) {
                $native = "$native ($commonName)";
            }

            return [$country->cca2 => $native];
        })
        ->values()
        ->toArray();;
        $countryList=[];
        foreach($all as $alls){
           foreach($alls as $key=>$value){
            $countryList[$key]=$value;
           }
        }
        // dd($countryList);
        return $form
            ->schema([
                //Company Information
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Section::make('Company Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Company Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Section::make('Image')
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Image')
                                            ->image()
                                            ->required(),

                                    ])
                                    ->collapsible(),
                                Forms\Components\TextInput::make('company_type')
                                    ->label('Company Type')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('tax_id')
                                    ->label('Tax ID')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('registration_id')
                                    ->label('Registration ID')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2),

                        //Contact Details
                        Forms\Components\Section::make('Contact Details')
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            PhoneInput::make('mobile')
                            ->label('Mobile Number')
                            ,

                                // Forms\Components\TextInput::make('mobile')
                                //     ->label('Mobile Number')
                                //     ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('website')
                                    ->label('Website')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                        //Currency and Location
                        Forms\Components\Section::make('Currency & Location')
                            ->schema([
                    // Forms\Components\Select::make('currency')
                    // ->label('Currency')
                    // ->options(Currency::all()->pluck('name', 'id'))

                    //     ->searchable()
                    //     ->getSearchResultsUsing(fn (string $query) => Currency::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                    //     ->getOptionLabelUsing(fn ($value): ?string => Currency::find($value)?->getAttribute('name'))
                    //     ->required(),
                    Select::make('currency')
                            ->required()
                            ->options($currencyList)
                            ->searchable(),
                                Forms\Components\TextInput::make('latitude')
                                ->label('Latitude')
                                ->rules([
                                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {

                                        if ( $value <-90 || $value >90) {
                                            $fail("The latitude field is invalid.");
                                        }
                                    },
                                ])
                                    ->numeric(),
                                Forms\Components\TextInput::make('longitude')
                                ->rules([
                                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                        if ( $value <-180 ||  $value >180) {
                                            $fail("The longitude field is invalid.");
                                        }
                                    },
                                ])
                                ->label('Longitude')

                                    ->numeric(),
                                TimezoneSelect::make('timezone')
                                ->searchable()
                                ->label('Time Zone')
                                ->required()
                            ])
                            ->columns(2),

                        //Adrress
                        Forms\Components\Section::make('Address')
                            ->schema([
                                Forms\Components\TextInput::make('street')
                                ->label('Street')
                                ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('street2')
                                ->label('Street2')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('city')
                                ->label('City')
                                ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('state')
                                ->label('State')
                                ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('zip')
                                ->label('ZIP')
                                ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('country')
                                ->options($countryList)
                                ->label('Country')->required()
                                    ->searchable()
                                    // ->getSearchResultsUsing(fn (string $query) => Country::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                                    // ->getOptionLabelUsing(fn ($value): ?string => Country::find($value)?->getAttribute('name'))
                                    // ->required(),
                            ])
                            ->columns(2),
                        //Social Media Accounts
                        Forms\Components\Section::make('Social Media Accounts')
                            ->schema([
                                Forms\Components\TextInput::make('twitter')
                                ->label('Twitter')
                                ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('facebook')
                                ->label('Facebook')
                                ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('youtube')
                                ->label('Youtube')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('instagram')
                                ->label('Instagram')
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Company Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo')
                ->circular()
                    ->label('Logo')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_type')
                    ->label('Company Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_id')
                    ->sortable()
                    ->label('Tax ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration_id')
                    ->sortable()
                    ->label('Registration ID')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('currency')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('latitude')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('longitude')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('timezone')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Contact Details')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->label('Mobile Number')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('email')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('website')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('street')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('street2')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('city')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('state')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('zip')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('country')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('twitter')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('facebook'),
                // Tables\Columns\TextColumn::make('youtube'),
                // Tables\Columns\TextColumn::make('instagram'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->before(function($record){
                    $checkEmployee=Company::where('id',$record->id)->whereHas('employee')->get()->count();
                   if($checkEmployee){
                      Notification::make()
                      ->title("To delete a company with employees ,you must either delete their profile or switch them to other company")
                      ->danger()
                      ->duration(9000)
                      ->send();
                      throw new Cancel();
                   }
                  }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
