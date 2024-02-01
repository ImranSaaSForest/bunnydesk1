<?php

namespace App\Models\Asset;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'name', 
        'description', 
        'value', 
        'purchase_date',
        'company_id',
        
    ];

    public function employeeAsset()
    {
        return $this->hasMany(EmployeeAsset::class,'asset_id');
    }

    public function company()
    {
        return $this->belongsTo(Company:: class,'company_id');
    }
    
}
