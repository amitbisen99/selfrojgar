<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@123.com',
                'password' => '123456',
                'type' => '1',
            ]
        ];

        foreach ($users as $user) {
            
            $find = User::where('email',$user['email'])->first();
            
            if (is_null($find)) {
                $user['password'] = Hash::make($user['password']);   
                User::create($user);
            }
        }
    }
}
