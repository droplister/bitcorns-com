<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name'     => env('ADMIN_NAME'),
            'email'    => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASS')),
        ]);
    }
}
