<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'admin',
            'status' => 0, // Active
        ]) ;

        $cashier = User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'cashier',
            'status' => 0, // Active
        ]) ;
    }
}
