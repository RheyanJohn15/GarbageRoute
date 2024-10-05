<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zones;

class Zone extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $color = [
            '#F48C06',
            '#5C8D89',
            '#DA627D',
            '#345995',
            '#6DD47E'
        ];

        for($i = 1; $i <= 5; $i++){
            $zone = new Zones();
            $zone->zone_name = "Zone $i";
            $zone->zone_color = $color[$i - 1];
            $zone->save();
        }


    }
}
