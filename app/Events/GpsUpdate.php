<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RouteProgress;
use App\Models\RoutesModel;
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
        $routes = RouteProgress::where('rp_status', 0)->get();
        foreach($routes as $route){
            $driverRoute = RoutesModel::where('r_id', $route->r_id)->first();
            $driver = TruckDriverModel::where('td_id', $driverRoute->r_assigned_driver)->first();

            $route->drivername = $driver->name;
        }

        return ['message'=> "success", "data"=> $routes];
     }
    public function broadcastOn(): array
    {
        return [
            new Channel('gps-update'),
        ];
    }
}
