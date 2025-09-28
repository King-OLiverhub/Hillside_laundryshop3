<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LaundryShopSeeder::class,
        ]);
        
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@laundryshop.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'), 
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Customer One',
            'email' => 'customer1@laundryshop.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'), 
            'role' => 'customer',
        ]);
    }
}