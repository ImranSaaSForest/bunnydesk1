<?php

namespace App\Filament\Resources\Settings\RoleResource\Pages;

use App\Filament\Resources\Settings\RoleResource;
use App\Models\Module\Module;
use App\Models\Module\RoleModule;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('Add New Roles')->modalHeading('Add New Roles')
                ->form([

                    TextInput::make('name')->unique()
                        ->required()->label('Title'),
                    Select::make('user')->relationship('users', 'name')->label('Assign To'),


                    CheckboxList::make('Modules')->required()
                        ->options(Module::all()->pluck('name','id'))->columns(4)
                ])
                ->action(function (array $data) {
                    // dd($data);
                    $role = Role::create([
                        'name' => $data['name']
                    ]);
                    // dd($role);

                    $user = User::find($data['user']);
                    if ($user) {
                        $user->assignRole($role->name);
                    }

                    foreach ($data['Modules'] as $module) {
                        //    dd($module);
                        RoleModule::create([
                            'role_id' => $role->id,
                            'module_id' => $module
                        ]);
                    }
                })
                ->modalSubmitActionLabel('Create')



                ->slideOver()





        ];
    }
}
