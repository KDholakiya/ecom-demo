<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->where('id',"!=",1)->delete();
        DB::table('currencies')->where('id' , 1)->update([
            'code'   => 'INR',
            'name'   => 'Indian Rupees',
            'symbol' => '₹',
        ]);
        
    }
}
