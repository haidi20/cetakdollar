<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = User::create([
            'name' => 'administrator',
            'email' => 'administrator@email.com',
            'password' => Hash::make('samarinda'),
            'role_id' => 1,
            'status' => true,
        ]);

        $administrator->assignRole('administrator');

        $operator = User::create([
            'name' => 'operator',
            'email' => 'operator@email.com',
            'password' => Hash::make('samarinda'),
            'role_id' => 2,
            'status' => true,
        ]);

        $operator->assignRole('operator');

        $user = User::create([
            'name' => 'arini',
            'email' => 'user@email.com',
            'password' => Hash::make('samarinda'),
            'role_id' => 3,
            'status' => true,
        ]);

        $user->assignRole('user');
    }
}
