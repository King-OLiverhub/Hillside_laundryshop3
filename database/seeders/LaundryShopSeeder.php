<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Product;
use App\Models\Addon;
use App\Models\Customer;

class LaundryShopSeeder extends Seeder
{
  public function run()
{
    $services = [
        [
            'id' => 1,
            'service_type' => 'Washing',
            'load_capacity_kg' => 8,
            'per_load' => 'Dropoff',
            'services' => 'Basic Washing',
            'price_per_load' => 150.00,
            'available' => true
        ],
        [
            'id' => 2, 
            'service_type' => 'Pocket',
            'load_capacity_kg' => 8,
            'per_load' => 'SetRocks',
            'services' => 'Pocket Service',
            'price_per_load' => 55.00,
            'available' => true
        ],
        [
            'id' => 3,
            'service_type' => 'Main',
            'load_capacity_kg' => 8,
            'per_load' => 'SetRocks',
            'services' => 'Main Service',
            'price_per_load' => 65.00,
            'available' => true
        ],
        [
            'id' => 4,
            'service_type' => 'Dry',
            'load_capacity_kg' => 8,
            'per_load' => 'Dropoff',
            'services' => 'Drying Service',
            'price_per_load' => 15.00,
            'available' => true
        ],
        [
            'id' => 5,
            'service_type' => 'Lightning',
            'load_capacity_kg' => 8,
            'per_load' => 'Dropoff',
            'services' => 'Express Service',
            'price_per_load' => 15.00,
            'available' => true
        ]
    ];

    foreach ($services as $service) {
        Service::create($service);
    }

    $products = [
        [
            'id' => 1,
            'name' => 'Liquid Detergent',
            'brand' => 'Ariel',
            'unit' => 'bottle',
            'quantity' => 50,
            'buy_price' => 48.25,
            'price' => 65.00,
            'critical_quantity' => 10,
            'available' => true
        ],
        [
            'id' => 2,
            'name' => 'Liquid Detergent',
            'brand' => 'Tide',
            'unit' => 'bottle',
            'quantity' => 50,
            'buy_price' => 52.30,
            'price' => 70.00,
            'critical_quantity' => 10,
            'available' => true
        ],
        [
            'id' => 3,
            'name' => 'Fabric Softener',
            'brand' => 'Downy',
            'unit' => 'bottle',
            'quantity' => 30,
            'buy_price' => 35.50,
            'price' => 50.00,
            'critical_quantity' => 5,
            'available' => true
        ],
        [
            'id' => 4,
            'name' => 'Bleach',
            'brand' => 'Clorox',
            'unit' => 'gallon',
            'quantity' => 20,
            'buy_price' => 85.00,
            'price' => 120.00,
            'critical_quantity' => 3,
            'available' => true
        ]
    ];

    foreach ($products as $product) {
        Product::create($product);
    }

    Customer::create([
        'first_name' => 'Juan',
        'last_name' => 'Dela Cruz',
        'contact' => '09123456789',
        'customer_type' => 'regular',
        'available' => true
    ]);

    Customer::create([
        'first_name' => 'Maria',
        'last_name' => 'Santos',
        'contact' => '09198765432',
        'customer_type' => 'walkin',
        'available' => true
    ]);
    
  }
}