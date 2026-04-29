<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'      => 'Administrator',
                'email'     => 'admin@gmail.com',
                'password'  => Hash::make('admin'),
                'role'      => 'admin',
                'is_active' => true,
            ],
            // [
            //     'name'      => 'Petugas Lapangan',
            //     'email'     => 'evaluator@spk.test',
            //     'password'  => Hash::make('password'),
            //     'role'      => 'evaluator',
            //     'is_active' => true,
            // ],
            // [
            //     'name'      => 'Kepala Dinas',
            //     'email'     => 'pimpinan@spk.test',
            //     'password'  => Hash::make('password'),
            //     'role'      => 'pimpinan',
            //     'is_active' => true,
            // ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
