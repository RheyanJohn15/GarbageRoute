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
            "Bagtic",
            "Balaring",
            "BarangayI",
            "BarangayII",
            "BarangayIII",
            "BarangayIV",
            "BarangayV",
            "BarangayVIPob",
            "EustaquioLopez",
            "Guimbala-On",
            "Guinhalaran",
            "KapitanRamon",
            "Lantad",
            "Mambulac",
            "Patag",
            "Rizal"
        ];

        foreach($brgyList as $brgy){
            $b = new BrgyList();

            $b->brgy_name = $brgy;
            $b->save();
        }
    }
}
