<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ActiveDrivers;
use App\Models\ZoneDrivers;
use App\Models\TruckDriverModel;
use App\Models\DumpTruckModel;

class GpsUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

     public function broadcastWith():array{

        $activeDrivers = ActiveDrivers::where('status', 'active')->get();
        foreach($activeDrivers as $active){
            $driver = TruckDriverModel::where('td_id', $active->td_id)->first();
            $zone = ZoneDrivers::where('td_id', $active->td_id)->join('zones', 'zones.zone_id', '=', 'zone_drivers.zone_id')->first();
            $dumptruck = DumpTruckModel::where('td_id', $active->td_id)->first();
            $active->driver = $driver;
            $active->zone = $zone;
            $active->truck = $dumptruck;
            
        }
        return ['message'=> "success", "data" => $activeDrivers];
     }
    public function broadcastOn(): array
    {
        return [
            new Channel('gps-update'),
        ];
    }
}
