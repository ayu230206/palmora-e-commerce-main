<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['username' => 'admin', 'role' => 'admin'],
            ['username' => 'seller', 'role' => 'produsen'],
        ];

        $users = array_map(function ($user) {
            $user['password'] = Hash::make($user['username']);
            return $user;
        }, $users);

        DB::table('users')->insert($users);
    }
}
