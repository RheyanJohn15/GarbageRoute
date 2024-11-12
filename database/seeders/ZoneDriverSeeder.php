<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zones;
use App\Models\ZoneDrivers;
use App\Models\TruckDriverModel;

class ZoneDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = Zones::all();
        $truckDrivers = TruckDriverModel::all();
        $td_id = [];
        foreach($truckDrivers as $driver){
            $td_id[] = $driver->td_id;
        }
        $index = 0;
        foreach ($zones as $zone) {
            $mainDriver = $td_id[$index];
            $standByDriver = $td_id[$index + 1];

            $zoneDriverMain = new ZoneDrivers();
            $zoneDriverMain->zone_id = $zone->zone_id;
            $zoneDriverMain->td_id = $mainDriver;
            $zoneDriverMain->type = "Main Driver";
            $zoneDriverMain->save();

            // Save the standby driver
            $zoneDriverStandby = new ZoneDrivers();
            $zoneDriverStandby->zone_id = $zone->zone_id;
            $zoneDriverStandby->td_id = $standByDriver;
            $zoneDriverStandby->type = "Standby Driver";
            $zoneDriverStandby->save();


            $index += 2;
        }
    }
}
