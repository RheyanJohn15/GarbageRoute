<?php
namespace App\Services\V1;
use App\Models\RoutesModel;
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

    public function getResult(){
        return $this->RESULT;
    }
}