<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'user_role' => 4, // Accountant / CFO
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password456'),
                'user_role' => 3, // Customer Care
            ],
            [
                'name' => 'Adam CEO',
                'email' => 'adam@example.com',
                'password' => Hash::make('password789'),
                'user_role' => 1, // CEO
            ],
            [
                'name' => 'Eve COO',
                'email' => 'eve@example.com',
                'password' => Hash::make('password000'),
                'user_role' => 2, // COO
            ],
        ];

        DB::table('users')->insert($users);
    }
}
