<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class FirstConfig extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::insert([
            ['id' => 1 , 'title' => 'created'],
            ['id' => 2 , 'title' => 'cancelled'],
            ['id' => 3 , 'title' => 'confirmed']
        ]);
        Warehouse::insert([
            ['id' => 1 , 'quantity' => 1000000 , 'price' => 25.5 , 'credit' => 20000]
        ]);
    }
}
