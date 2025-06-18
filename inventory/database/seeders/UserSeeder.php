<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = collect([
            [
                'name'=>'demo',
                'email'=>"demo@gmail.com",
                'password'=>Hash::make('12345')
            ],
            [
                "name"=>"demo1",
                "email" =>"demo1@gmail.com",
                "password"=>Hash::make('12345')
            ]
            ]);

            $array->each(function($user) {
                User::insert($user);
            });
    }
}
