<?php

namespace App\Filament\Resources\Settings\RoleResource\Pages;

use App\Filament\Resources\Settings\RoleResource;
use App\Models\Module\RoleModule;
use App\Models\Role;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
class AddPermission extends Page
{
    protected static string $resource = RoleResource::class;

    protected static string $view = 'filament.resources.settings.role-resource.pages.add-permission';


    use InteractsWithRecord;
    public $allModules;
    
    public function mount(int | string $record): void
    {

        // dd($record);
        $this->allModules=RoleModule::with('role','module')->where('role_id',$record)->get();
        // dd($allModule->module->name);
        $this->record = $this->resolveRecord($record);
    }
}
