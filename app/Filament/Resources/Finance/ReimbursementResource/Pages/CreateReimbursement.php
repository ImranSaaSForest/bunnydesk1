<?php

namespace App\Filament\Resources\Finance\ReimbursementResource\Pages;

use App\Filament\Resources\Finance\ReimbursementResource;
use App\Models\Finance\BudgetExpense;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateReimbursement extends CreateRecord
{
    protected static string $resource = ReimbursementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public function afterCreate()
    {
        $record = $this->getRecord();
        $managers = BudgetExpense::find($record->budget_expense_id)->with('budget.budgetManager.manager')->get();
        if ($managers) {

            foreach ($managers[0]->budget->budgetManager as $manager) {
                $recipient = $manager->manager;

                if ($recipient) {

                    Notification::make()
                        ->title('New Reimbursement Request Received')
                        ->body('Please review and approve or deny the request.')
                        ->actions([
                            Action::make('view')
                                ->button()
                                ->url(fn (): string => route('filament.admin.resources.finance.pending-reimbursement-requests.index'))
                                ->close()
                              
                        ])
                        ->sendToDatabase($recipient);
                }
            }
        }
    }
}
