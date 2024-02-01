<?php

namespace App\Livewire;

use App\Models\Company\Company;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use PragmaRX\Countries\Package\Countries;

class SetupPage extends Component implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    public function mount(): void
    {
        $this->form->fill();
        file_put_contents(storage_path('setup'), 'setup finished');
    }
    public function form(Form $form): Form
    {
        $countries = new Countries();
        // get currency
        $currencies = $countries->currencies();
        // dd($countries->currencies()['INR']->units->major->symbol);
        $currencyList = [];
        foreach ($currencies as $key => $value) {
            $currencyList[$key] = $value->name;
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
        $countryList = [];
        foreach ($all as $alls) {
            foreach ($alls as $key => $value) {
                $countryList[$key] = $value;
            }
        }
        return $form
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Company Setup')
                        ->schema([
                            ViewField::make('')
                                ->view('forms.components.company-set-up'),
                            TextInput::make('name')
                                ->label('Company Name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Select::make('country')
                                ->options($countryList)
                                ->label('Country')->required(),
                            Select::make('currency')
                                ->required()
                                ->options($currencyList)

                        ]),
                    Wizard\Step::make('Employee Setup')
                        ->schema([
                            ViewField::make('')
                                ->view('forms.components.employee-set-up')
                        ]),
                    Wizard\Step::make('Role Setup')
                        ->schema([
                            ViewField::make('')
                                ->view('forms.components.role-set-up')
                        ]),
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
                <x-filament::button
                type='submit'
                size="sm"
                >
                    Finish
                </x-filament::button>
            BLADE)))

            ])
            ->statePath('data');
    }

    public function finish()
    {
// $this->validate();
        Company::create(
            $this->data
        );
        return redirect()->to('/');
    }
    public function render()
    {
        return view('livewire.setup-page');
    }
}
