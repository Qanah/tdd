<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'code' => 'FR1',
            'name' => 'Fruit tea',
            'price' => 3.11,
        ]);

        Product::create([
            'code' => 'SR1',
            'name' => 'Strawberries',
            'price' => 5.00,
        ]);

        Product::create([
            'code' => 'CF1',
            'name' => 'Coffee',
            'price' => 11.23,
        ]);
    }
}
