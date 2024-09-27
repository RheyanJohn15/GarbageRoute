<?php
namespace App\Services\V1;
use App\Models\RoutesModel;
use App\Models\RouteProgress;
use App\Events\GpsUpdate;

class Driver{

    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function getroute($request){
        $route = RoutesModel::where('r_assigned_driver', $request->driverid)->get();
        
        $this->RESULT = ['routes', 'Successfully Fetch All drivers route', $route];
    }

    private function routedetails($request){
        $route = RoutesModel::where('r_id', $request->routeid)->first();

        $this->RESULT = ['details', 'Succesffully Fetch Route Details', $route];
    }

    private function updatelocation($request){
        event(new GpsUpdate($request->coordinates));

        $this->RESULT = ['updatelocation', 'Succesfully updated the location', 'null'];
    }

    private function startcollection($request){
        $prog = new RouteProgress();
        $route = RoutesModel::where('r_id', $request->route_id)->first();
        
        $prog->r_id = $request->route_id;
        $progress = [];
        $coordinates = explode(',',$route->r_coordinates);
        foreach($coordinates as $coord){
          array_push($progress,  $coord. "**False");
        }
        $prog->rp_progress = implode(',', $progress);
        $prog->rp_status = 0;
        $prog->save();

        $this->RESULT = ['startcollection', "Start Garbage Collection", 'null'];

    }

    public function getResult(){
        return $this->RESULT;
    }
}