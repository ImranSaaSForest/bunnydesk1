<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Company\Company;
use App\Models\Payroll\PayrollVariable;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
class CustomVariable extends Component
{
    public $companies;

  // CUSTOM VARIABLES | | unique:payroll_variables,name
  #[Rule('required')]
  public $customVariableName;
  #[Rule('required')]
  public $customVariableCompanyId;
  #[Rule('required')]
  public $customVariableValue;
  public $currentAction;
  public $currentVariableId;
  public function mount()
  {

      $this->companies = Company::all();
  }
    public function createCustomVariable(){
        $this->validate();

        if($this->currentVariableId){
            PayrollVariable::find($this->currentVariableId)->update([
                'name' => $this->customVariableName,
                'company_id' => $this->customVariableCompanyId,
                'value' => $this->customVariableValue,
            ]);
        }
        else{
            PayrollVariable::create([
                'name' => $this->customVariableName,
                'company_id' => $this->customVariableCompanyId,
                'value' => $this->customVariableValue,
            ]);
        }
        $this->reset('customVariableName','customVariableValue');
        $this->dispatch('close-modal', id: 'edit-user');
        $this->dispatch('refreshCustomVariable');

    }
    #[On('editCustomVariable')]
    public function editCustomVariable($id){
        $this->currentAction='edit';
        $this->currentVariableId=$id;
        $variableData=PayrollVariable::find($id);
        $this->customVariableName=$variableData->name;
        $this->customVariableCompanyId=$variableData->company_id;
        $this->customVariableValue=$variableData->value;
        $this->dispatch('open-modal', id: 'edit-user');

    }
    public function render()
    {
        return view('livewire.custom-variable');
    }
}
