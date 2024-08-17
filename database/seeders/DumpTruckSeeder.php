<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DumpTruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $truckDriverIds = DB::table('truck_drivers')->pluck('td_id')->toArray();

        for ($i = 0; $i < 40; $i++) {
            DB::table('dump_truck')->insert([
                'model' => 'Model ' . ($i + 1),
                'can_carry' => rand(1000, 5000) . ' kg',
                'td_id' => $truckDriverIds[array_rand($truckDriverIds)], // Assign a random truck driver
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
