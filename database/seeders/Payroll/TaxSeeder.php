<?php

namespace Database\Seeders\Payroll;

use App\Models\Payroll\TaxSlab;
use App\Models\Payroll\TaxSlabValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $tax= TaxSlab::create([
        'name'=>'Liberian Tax Slab',
      ]);


        $values=[
            [
            'tax_slab_id'=>$tax->id,
            'start'=>0,
            'end'=>70000,
            'cal_range'=>'To',
            'fixed_amount'=>0,
            'percentage'=>0
            // 'description'=>,
            ],
            [
                'tax_slab_id'=>$tax->id,
                'start'=>70001,
                'end'=>200000,
                'cal_range'=>'To',
                'fixed_amount'=>0,
                'percentage'=>5
                // 'description'=>,
            ],
            [
                'tax_slab_id'=>$tax->id,
                'start'=>200001,
                'end'=>800000,
                'cal_range'=>'To',
                'fixed_amount'=>6500,
                'percentage'=>15
                // 'description'=>,
            ],
            [
                'tax_slab_id'=>$tax->id,
                'start'=>800001,
                'end'=>null,
                'cal_range'=>'And Above',
                'fixed_amount'=>96500,
                'percentage'=>25
                // 'description'=>,
            ]
            ];
            foreach($values as $value){
                TaxSlabValue::create($value);
            }
    }
}
