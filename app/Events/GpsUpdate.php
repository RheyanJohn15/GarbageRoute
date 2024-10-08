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
use App\Models\TruckDriverModel;

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
            $active->driver = $driver;
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
