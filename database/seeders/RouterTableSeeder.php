<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;
class RouterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch all truck driver IDs from the `truck_drivers` table
        $truckDriverIds = DB::table('truck_drivers')->pluck('td_id')->toArray();

        for ($i = 0; $i < 30; $i++) {
            DB::table('routes')->insert([
                'r_name' => $faker->regexify('[A-Z0-9]{8}'), // Random alphanumeric string of length 8
                'r_start_longitude' => $faker->longitude(),
                'r_start_latitude' => $faker->latitude(),
                'r_start_location' => $faker->address(),
                'r_end_longitude' => $faker->longitude(),
                'r_end_latitude' => $faker->latitude(),
                'r_end_location' => $faker->address(),
                'r_assigned_truck' => $faker->randomElement($truckDriverIds), // Randomly pick a truck driver ID
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
