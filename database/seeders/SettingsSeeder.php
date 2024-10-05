<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Dump Site Location

        $dumpLocation = new Settings();
        $dumpLocation->settings_context = "dumpsite_location";
        $dumpLocation->settings_value = "10.775059,122.974123";
        $dumpLocation->save();
    }
}
