<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $this->call(CustomCurrencySeeder::class);
        $this->call(CustomLocalesSeeder::class);
        $this->call(CustomUserSeeder::class);
        $this->call(CustomConfigSeeder::class);
        $this->call(CustomThemeSeeder::class);
    }
}
