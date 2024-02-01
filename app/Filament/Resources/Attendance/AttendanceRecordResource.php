<?php

namespace App\Filament\Resources\Attendance;

use App\Filament\Resources\Attendance\AttendanceRecordResource\Pages;
use App\Filament\Resources\Attendance\AttendanceRecordResource\RelationManagers;
use App\Forms\Components\UserTimezone;
use App\Models\Attendance\AttendanceRecord;
use App\Models\User;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Closure;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;

class AttendanceRecordResource extends Resource
{

    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Attendance Record';

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';




    public static function table(Table $table): Table


    {

        
        
        return $table

            ->columns([
                TextColumn::make('name'),
                TextColumn::make('attendanceType')->default(function ($record) {

                    $attendanceTypes = AttendanceRecord::where('user_id', $record->id)->with('attendanceType')
                        ->latest('in')
                        ->first();
                    if ($attendanceTypes) {
                        return $attendanceTypes->attendanceType->name;
                    }
                }),
              
                TextColumn::make('reason')->default(function ($record) {
                    $reason = AttendanceRecord::where('user_id', $record->id)
                        ->latest('in')
                        ->first();

                    if ($reason) {
                        return $reason->reason;
                    }
                }),
                TextColumn::make('in')->default(function ($record) {
                    $checkIn = AttendanceRecord::where('user_id', $record->id)
                        ->latest('in')
                        ->first();

                    if ($checkIn) {
                        return $checkIn->in;
                    }
                }),
                TextColumn::make('out')->default(function ($record) {
                    $checkOut = AttendanceRecord::where('user_id', $record->id)
                        ->latest('in')
                        ->first();
                    if ($checkOut) {
                        return $checkOut->out;
                    }
                }),
                TextColumn::make('total_hours')->default(function ($record) {
                    $checkOut = AttendanceRecord::where('user_id', $record->id)
                        ->latest('total_hours')
                        ->first();
                    if ($checkOut) {
                        return $checkOut->total_hours;
                    }
                }),

            ])
            ->defaultSort('name', 'asc')
            ->filters([
                //
            ])



            ->actions([
                Tables\Actions\EditAction::make()->visible(function ($record) {
                    $edit = AttendanceRecord::where('user_id', $record->id)->latest('in')
                        ->first();
                    // dd($a);
                    return !is_null($edit);

                    
                }),
               
            ])
           
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                // Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])

            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttendanceListRelationManager::class,
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     $query = parent::getEloquentQuery();
    //     if (auth()->user()->hasRole('HR') || auth()->user()->hasRole('Super Admin')) {

    //         return $query;
    //     } else {

    //         $query->where('user_id', auth()->user()->id);
    //         return $query;
    //     }
    // }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceRecords::route('/'),
            'create' => Pages\AttendanceRecord::route('/create'),
            // 'view' => Pages\AttendanceList::route('/{record}/view'),
            'edit' => Pages\EditAttendanceRecord::route('/{record}/edit'),
        ];
    }
}
