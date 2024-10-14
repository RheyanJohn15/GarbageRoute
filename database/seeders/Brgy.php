<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BrgyList;

class Brgy extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brgyList =   [
            ["Bagtic", 4],
            ["Balaring", 4],
            ["BarangayI", 2],
            ["BarangayII", 2],
            ["BarangayIII", 2],
            ["BarangayIV", 3],
            ["BarangayV", 4],
            ["BarangayVIPob", 2],
            ["EustaquioLopez", 6],
            ["Guimbala-On", 6],
            ["Guinhalaran", 4],
            ["KapitanRamon", 6],
            ["Lantad", 4],
            ["Mambulac", 3],
            ["Patag", 6],
            ["Rizal", 5]
        ];

        foreach($brgyList as $brgy){
            $b = new BrgyList();

            $b->brgy_name = $brgy[0];
            $b->max_waypoint = $brgy[1];
            $b->save();
        }
    }
}
