<?php

use App\User;
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
        User::create([
            'name' => 'Michael Kent Kentotoy',
            'email'=> 'user@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
