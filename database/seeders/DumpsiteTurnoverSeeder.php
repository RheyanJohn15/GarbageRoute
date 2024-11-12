<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DumpTruckModel;
use App\Models\DumpsiteTurnovers;
use Carbon\Carbon;

class DumpsiteTurnoverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dumpTruck = DumpTruckModel::all();
        $d_truck_id = [];

        foreach ($dumpTruck as $truck) {
            $d_truck_id[] = $truck->dt_id;
        }

        for ($i = 0; $i < 120; $i++) {
            $truck_id = $d_truck_id[array_rand($d_truck_id)]; // Get the actual dt_id value

            $dTruck = DumpTruckModel::where('dt_id', $truck_id)->first();

            if ($dTruck) {
                $randomDate = Carbon::now()
                    ->subMonths(rand(1, 6))
                    ->subDays(rand(0, 30))
                    ->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

                $turnover = new DumpsiteTurnovers();
                $turnover->td_id = $dTruck->td_id; // Assign `td_id` correctly
                $turnover->dt_id = $dTruck->dt_id; // Assign `dt_id`
                $turnover->created_at = $randomDate;
                $turnover->updated_at = $randomDate;
                $turnover->save();

                // Log a message to the console
                echo "Seeded DumpsiteTurnover with dt_id: {$dTruck->dt_id} and random date: {$randomDate}\n";
            } else {
                echo "No DumpTruck found with dt_id: {$truck_id}\n";
            }
        }
    }
}
