<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomLocalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locales')->where('id',"!=",1)->delete();
        DB::table('locales')->where('id',1)->update(
            [
                'code' => 'en',
                'name' => 'English',
                'locale_image' => 'velocity/locale_image/1/in.png'
            ]
        );
    }
}
