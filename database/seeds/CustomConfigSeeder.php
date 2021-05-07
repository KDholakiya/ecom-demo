<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        DB::table('core_config')->insert([
            'code'         => 'general.general.locale_options.weight_unit',
            'value'        => 'kgs',
            'channel_code' => 'default',
            'locale_code'  => null,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        if(env('MAIL_FROM_NAME', false)){
            DB::table('core_config')->insert([
                'code'         => 'general.general.email_settings.sender_name',
                'value'        => env('MAIL_FROM_NAME'),
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
        if(env('SHOP_MAIL_FROM', false)){
            DB::table('core_config')->updateOrInsert(['code'=> 'general.general.email_settings.shop_email_from'],[
                'value'        => env('SHOP_MAIL_FROM'),
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
        if(env('MAIL_ADMIN_NAME', false)){
            DB::table('core_config')->updateOrInsert(['code' => 'general.general.email_settings.admin_name'],[
                'value'        => env('MAIL_ADMIN_NAME'),
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
        if(env('ADMIN_MAIL_TO', false)){
            DB::table('core_config')->updateOrInsert(['code' => 'general.general.email_settings.admin_email'],[
                'value'        => env('ADMIN_MAIL_TO'),
                'channel_code' => 'default',
                'locale_code'  => null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }
    }
}
