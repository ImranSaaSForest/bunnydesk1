<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Pages\Dashboard;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }


    protected function getRedirectUrl(): ?string
    {
        return filament()->getUrl();
    }
    public function hasLogo(): bool
    {
        return true;
    }
    public static function getLabel(): string
    {
        if(auth()->user()->last_name){
            return auth()->user()->name .' '.auth()->user()->last_name;
        }
        else{
            return auth()->user()->name;
        }
    }
}
