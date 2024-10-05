<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(AdminSeeder::class);
        $this->call(Brgy::class);
        $this->call(Zone::class);
        $this->call(TruckDriverSeeder::class);
        $this->call(DumpTruckSeeder::class);
        $this->call(GeoDataSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
