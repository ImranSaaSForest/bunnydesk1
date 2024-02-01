<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class Required extends Field
{
    protected string $view = 'forms.components.required';
    public $optionsList = [];
    public $state=[];

    public function options(array $options): static
    {
        $this->optionsList = $options;
        session(["number" => $this->optionsList]);
        return $this;
    }
}
