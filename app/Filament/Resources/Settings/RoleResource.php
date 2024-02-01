<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\RoleResource\Pages;
use App\Filament\Resources\Settings\RoleResource\Pages\ListRoles;
use App\Filament\Resources\Settings\RoleResource\RelationManagers;
use App\Models\Module\Module;
use App\Models\Module\RoleModule;
use App\Models\Role;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Card::make()
                //     ->schema([
                //         Forms\Components\TextInput::make('name')
                //             ->unique(ignoreRecord: true)
                //             ->required(),
                //         Forms\Components\Select::make('permissions')
                //             ->multiple()
                //             ->relationship('permissions', 'name')
                //             ->preload()
                //             ->required()
                //     ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([

                Action::make('Add Permission')->label('Add Permission')->icon('heroicon-o-plus-small')
                    ->url(fn ($record): string => route('filament.admin.resources.settings.roles.permission', ['record' => $record->id])),

                Action::make('Edit')
                    ->slideOver()
                    ->form([

                        TextInput::make('name')->unique()
                            ->required()->label('Title'),
                        Select::make('user')->relationship('users', 'name')->label('Assign To'),


                        CheckboxList::make('Modules')->required()
                            ->options(Module::all()->pluck('name', 'id'))->columns(4)
                    ])
                    ->modalHeading('Add New Employee')
                    ->icon('heroicon-m-pencil-square')
                    ->modalSubmitActionLabel('Save Change')

                    ->fillForm(function ($record) {   
                        
                        $user = User::whereHas('roles', function ($query) use ($record) {
                            $query->where('name', $record->name);
                        })->first();
                        
                        $module = RoleModule::with('module')->where('role_id', $record->id)->get()->pluck('module_id')->toArray();
                        $data = [
                            "name" => $record->name,
                            "user" => $user->id,
                            "Modules" => $module
                        ];

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),



            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            // RelationManagers\PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
            'permission' => Pages\AddPermission::route('/{record}/permission'),
        ];
    }
}
