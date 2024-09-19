<?php
namespace App\Services\V1;
use App\Models\RoutesModel;
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

    public function getResult(){
        return $this->RESULT;
    }
}