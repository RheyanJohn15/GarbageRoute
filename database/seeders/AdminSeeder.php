<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accounts;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = new Accounts();

        $account->acc_type = "Super Admin";
        $account->acc_name = "Administrator";
        $account->acc_username = "admin";
        $account->acc_password = Hash::make("admin123");
        $account->acc_status = 1;

        $account->save();
    }
}
