<?php

namespace App\Filament\Resources\Attendance\AttendanceRecordResource\RelationManagers;

use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceListRelationManager extends RelationManager
{
    protected static string $relationship = 'attendanceRecord';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->disabledOn('edit')
                        ->dehydrated(),
                    Select::make('attendance_type_id')
                        ->relationship('attendanceType', 'name')
                        ->required()
                        ->disabledOn('edit')
                        ->dehydrated(),

                    TextInput::make('reason'),
                    DateTimePicker::make('in')
                        ->seconds(false)
                        ->rules([
                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                if ($get('out')) {
                                    $out = Carbon::create($get('out'));
                                    if ($value) {
                                        $in = Carbon::create($value);
                                    }
                                    if (!$in->lt($out)) {
                                        $fail("Please select a date that is less than out.");
                                    }
                                }
                            },
                        ])
                        ->required(),
                    DateTimePicker::make('out')
                        ->seconds(false)
                        ->suffix(function (Get $get) {
                            $timezone = $get('timezone');
                            return $timezone;
                            // return self::getOffset($timezone);
                        })
                        ->rules([
                            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                if ($get('in')) {
                                    $in = Carbon::create($get('in'));
                                    if ($value) {
                                        $out = Carbon::create($value);
                                    }
                                    if (!$in->lt($out)) {
                                        $fail("Please select a date that is greater than in.");
                                    }
                                }
                            },
                        ])


                ])
                    ->columns(2)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('attendanceType.name'),
                Tables\Columns\TextColumn::make('reason'),
                Tables\Columns\TextColumn::make('in'),
                Tables\Columns\TextColumn::make('out'),
                Tables\Columns\TextColumn::make('total_hours'),
              
            ])
            ->defaultSort('in', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    if ($data['in'] && $data['out']) {
                        $in = Carbon::parse($data['in']);
                        $out = Carbon::parse($data['out']);
                        $data['total_hours'] = $in->diffInHours($out);
            
                    }
            
                    return $data;
                })
                ->visible(function($record){

                    $inTime=$record->in;
                    if($inTime >= Carbon::today()){
                        return true;
                    }
                    
                   
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
