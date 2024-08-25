<?php
namespace App\Services\V1;
use App\Models\RoutesModel;
use App\Models\TruckDriverModel;
class Routes{

    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function add($req){
       $route = new RoutesModel();

       $route->r_name = $req->name;
       $route->r_start_longitude = $req->start_longitude;
       $route->r_start_latitude = $req->start_latitude;
       $route->r_start_location = $req->start_location;
       $route->r_end_longitude = $req->end_longitude;
       $route->r_end_latitude = $req->end_latitude;
       $route->r_end_location = $req->end_location;
       $route->r_assigned_truck = $req->assigned_truck;
       $route->save();

       $this->RESULT = ['add', 'Successfully Saved Route', $route];
    }

    private function delete($req){
        $route = RoutesModel::where('r_id', $req->id)->first();
        $route->delete();

        $this->RESULT = ['delete', 'Succesfully Deleted Route', null];
    }

    private function list($req){
        $route = RoutesModel::all();

        foreach($route as $r){
            $driver = TruckDriverModel::where('td_id', $r->r_assigned_truck)->first();
            $r->truck_driver = $driver->name;
        }

        $this->RESULT = ['list', 'Get All Routes', $route];
    }

    private function update($req){
        $route = RoutesModel::where('r_id', $req->id)->first();

        $route->update([
           'r_name'=>$req->name,
           'r_start_longitude'=> $req->start_longitude,
           'r_start_latitude' => $req->start_latitude,
           'r_start_location'=> $req->start_location,
           'r_end_longitude'=> $req->end_longitude,
           'r_end_latitude'=> $req->end_latitude,
           'r_end_location'=> $req->end_location,
           'r_assigned_truck'=> $req->assigned_truck
        ]);

        $this->RESULT = ['update', 'Succesfully Updated Route', $route];
    }

    private function details($req){
        $route = RoutesModel::where('r_id', $req->id)->first();
        $this->RESULT = ['details', 'Get Route Details', $route];
    }

    
    public function getResult(){
        return $this->RESULT;
    }

}