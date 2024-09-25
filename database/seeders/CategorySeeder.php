<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Electronics', 'description' => 'Devices and gadgets'],
            ['name' => 'Apparel', 'description' => 'Clothing and fashion accessories'],
            ['name' => 'Home & Kitchen', 'description' => 'Household appliances and kitchenware'],
            ['name' => 'Books', 'description' => 'Fiction, non-fiction, and educational materials'],
            ['name' => 'Toys', 'description' => 'Toys for children and babies'],
        ]);
    }
}
