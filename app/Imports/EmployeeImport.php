<?php

namespace App\Imports;

use App\Filament\Resources\Employee\EmployeeResource\Pages\ImportEmployee;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;


class EmployeeImport implements ToCollection
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
            // session()->put('importData',$collection);
        return redirect()->route('filament.admin.resources.employees.import');

        // dd($collection);
    }

}
