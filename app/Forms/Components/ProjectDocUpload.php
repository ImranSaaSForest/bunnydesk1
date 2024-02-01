<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Concerns\HasAffixes;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Concerns\HasPlaceholder;

class ProjectDocUpload extends Field
{
    protected string $view = 'forms.components.project-doc-upload';

    use HasAffixes,
    HasExtraInputAttributes,
    HasPlaceholder;
}
