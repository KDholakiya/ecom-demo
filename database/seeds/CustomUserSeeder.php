<?php

use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seed;

class CustomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->where('id',1)->update([
            'name'       => env('DEFAULT_USER_NAME','Example'),
            'email'      => env('DEFAULT_USER_EMAIL','admin@example.com'),
            'password'   => bcrypt(env('DEFAULT_USER_PASSWORD','admin123')),
            'api_token'  => Str::random(80),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'status'     => 1,
            'role_id'    => 1,
        ]);
    }
}
