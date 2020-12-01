<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stat;

class StatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stat::insert([
            'name' => 'New',            
        ]);

        Stat::insert([
            'name' => 'Processing',            
        ]);

        Stat::insert([
            'name' => 'Shipped',            
        ]);
    }
}
