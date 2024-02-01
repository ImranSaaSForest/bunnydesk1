<?php

namespace App\Filament\Resources\Settings\PayrollPolicyResource\RelationManagers;

use App\Models\Payroll\UserPayrollPolicy;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserPayrollPolicyRelationManager extends RelationManager
{
    protected static string $relationship = 'UserPayrollPolicy';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Select::make('user_id')
->required()
               //    ->relationship('user','name')
            ->options(User::whereDoesntHave('UserPayrollPolicy', function ($query) {
                $query->where('payroll_policy_id', $this->ownerRecord->id);
            })->pluck('name', 'id'))
            ->label('Employee')
            // ->afterStateUpdated(function (?string $state){
            //  $isPolicyExist=UserPayrollPolicy::where('user_id',$state)->get()->count();
            //  if($isPolicyExist>0){
            //     UserPayrollPolicy::where('user_id',$state)->update([
            //         'payroll_policy_id'=> $this->ownerRecord->id
            //     ]);
            //  }

            //    })
            ]);

    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payroll_policy_id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
            ->label('Employee')
            ,
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {

                   $userId= $data['user_id'];
                    $isPolicyExist=UserPayrollPolicy::where('user_id',$userId)->get();
                    if($isPolicyExist->count()>0){
                        UserPayrollPolicy::where('user_id',$userId)->delete();
                        $data['payroll_policy_id']=$this->ownerRecord->id;
                    }

                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->modalHeading('Edit User')

                ->mutateFormDataUsing(function (array $data): array {

                    $userId= $data['user_id'];
                     $isPolicyExist=UserPayrollPolicy::where('user_id',$userId)->get();
                     if($isPolicyExist->count()>0){
                         UserPayrollPolicy::where('user_id',$userId)->delete();
                         $data['payroll_policy_id']=$this->ownerRecord->id;
                     }

                     return $data;
                 })
                 ,
                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete User')
                ,
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make()
                // ->mutateFormDataUsing(function (array $data): array {

                //     $userId= $data['user_id'];
                //      $isPolicyExist=UserPayrollPolicy::where('user_id',$userId)->get();
                //      if($isPolicyExist->count()>0){
                //          UserPayrollPolicy::where('user_id',$userId)->delete();
                //          $data['payroll_policy_id']=$this->ownerRecord->id;
                //      }

                //      return $data;
                //  }),
            ]);
    }
}
