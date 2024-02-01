<?php

namespace App\Filament\Resources\HelpDesk\TicketResource\Pages;

use App\Filament\Resources\HelpDesk\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use phpDocumentor\Reflection\PseudoTypes\False_;

class ManageTickets extends ManageRecords
{
    protected static string $resource = TicketResource::class;

    // ->visible(function(){
    //     if(auth()->user()->hasPermissionTo('Ticket Management Delete')){
    //         return true;
    //     }
    // }),
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(function(){
                if(auth()->user()->hasPermissionTo('Ticket Management Create')){
                            return true;
                        }
            })
            ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();
                return $data;
            })->createAnother(False),
        ];
    }
}
