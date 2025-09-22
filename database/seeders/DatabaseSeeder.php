<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::create([
            'name' => 'Admin',
            'email' => 'admin@laundryshop.com',
            'password' => Hash::make('password'), 
            'role' => 'admin',
        ]);

       
        User::create([
            'name' => 'Customer One',
            'email' => 'customer1@laundryshop.com',
            'password' => Hash::make('password'), 
            'role' => 'customer',
        ]);
    }
}
