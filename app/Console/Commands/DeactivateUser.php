<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActiveDrivers;
use Carbon\Carbon;

class DeactivateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate an idle users for 10 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $currentTime = Carbon::now();
        $inactiveDrivers = ActiveDrivers::where('status', 'active')
        ->where('updated_at', '<=', $currentTime->subMinutes(12))
        ->get();
        foreach($inactiveDrivers as $a){
            $a->update([
                'status'=> 'inactive'
            ]);

        }

        $this->info('DONE');
    }
}
