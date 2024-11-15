<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TruckDriverModel;
use App\Models\Schedules;

class ZoneSchedules extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $truckdriver = TruckDriverModel::all();

        foreach($truckdriver as $driver){
            $schedule = new Schedules();

            $schedule->td_id = $driver->td_id;
            $schedule->days = 'everyday';
            $schedule->collection_start = '05:00';
            $schedule->collection_end = '15:00';
            $schedule->save();
        }
    }
}
