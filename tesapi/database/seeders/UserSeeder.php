<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $staffUsers = [
            [
                'name' => 'Owner Toko',
                'email' => 'owner@tokobuku.test',
            ],
            [
                'name' => 'Kasir Utama',
                'email' => 'kasir@tokobuku.test',
            ],
            [
                'name' => 'Admin Gudang',
                'email' => 'gudang@tokobuku.test',
            ],
        ];

        foreach ($staffUsers as $staff) {
            User::updateOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'password' => Hash::make('password'),
                ]
            );
        }

        User::factory()->count(12)->create();
    }
}
