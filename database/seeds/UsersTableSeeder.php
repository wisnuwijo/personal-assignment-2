<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            "name" => "admin",
            "email" => "admin@mail.com",
            "password" => Hash::make("123123"),
        ]);
    }
}
