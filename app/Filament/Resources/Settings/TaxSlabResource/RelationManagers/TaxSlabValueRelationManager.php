<?php

namespace App\Filament\Resources\Settings\TaxSlabResource\RelationManagers;

use App\Models\Payroll\TaxSlab;
use App\Models\Payroll\TaxSlabValue;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class TaxSlabValueRelationManager extends RelationManager
{
    protected static string $relationship = 'taxSlabValue';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('tax_slab_id')
                //     ->required()
                //     ->maxLength(255),
                // Repeater::make()


                        TextInput::make('start')
                        ->default(function () {
                            $TaxId=$this->ownerRecord->id;
                            $isStart=TaxSlabValue::where('tax_slab_id',$TaxId)->where('start',0)->get()->count();
                            if($isStart==0){
                                return 0;
                            }else{
                                $endValue=TaxSlabValue::where('tax_slab_id',$TaxId)->max('end');
                               return  $endValue+1;
                            }
                        })->numeric()
                        ->disabled(
                            function ($state) {
                                $TaxId=$this->ownerRecord->id;
                                $isStart=TaxSlabValue::where('tax_slab_id',$TaxId)->where('start',0)->get()->count();
                                if($isStart==0){
                                    return true;
                                }
                                elseif($state==0){
                                    return true;

                                }else{
                               return false;
                                }
                            }
                        )
                        ->dehydrated(
                        )

                        ->required(),
                        Select::make('cal_range')
                        ->reactive()
                        ->options([
                            'To'=>'To',
                            'And Above'=>'And Above'
                        ])
                        ->rules([
                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                if ($get('end') && $value === 'And Above') {
                                    $fail("You cannot use And Above if you have end value.");
                                }
                                if (!$get('end') && $value === 'To') {
                                    $fail("You cannot use To if you don't have end value.");
                                }
                            },
                        ])
                        ->default('To')
                        ->label('Condition')
                        ->required(),
                        TextInput::make('end')
                        ->numeric()
                        ->gt('start')
                        ->disabled(function(Get $get){
                            if($get('cal_range')=='And Above'){
                                return true;
                            }
                        }),
                        TextInput::make('fixed_amount')
                        ->numeric(),

                        TextInput::make('percentage')
                        ->numeric()
                        ->default(0)
                        ->suffixIcon('antdesign-percentage-o'),
                        TextInput::make('description')
                ])
                ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tax_slab_id')
            ->columns([
                TextColumn::make('start'),
                TextColumn::make('cal_range')
                ->label('Condition'),
                TextColumn::make('end'),
                TextColumn::make('fixed_amount')
                ->default(0),
                TextColumn::make('percentage'),

                TextColumn::make('description')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('New Value')
                ->disabled(function(){
                    $TaxId=$this->ownerRecord->id;
                            $isStart=TaxSlabValue::where('tax_slab_id',$TaxId)->where('cal_range','And Above')->get()->count();

                            if($isStart>0){
                                return true;
                            }else{
                                return false;
                            }
                }),
            ])
            ->actions([

                Tables\Actions\DeleteAction::make()
                ->modalHeading('Delete Tax Slab Value')

                ->action(function(TaxSlabValue $record){
                    $TaxId=$this->ownerRecord->id;
                    $taxSlabValue=TaxSlabValue::where('tax_slab_id',$TaxId);
                    $belowRow=$taxSlabValue->where('id','>=',$record->id)->get();
                    foreach($belowRow as $row){
                        TaxSlabValue::find($row->id)->delete();
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make()
               
            ]);
    }
}
