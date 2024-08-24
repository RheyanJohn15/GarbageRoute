<?php
namespace App\Services\V1;
use App\Models\RoutesModel;
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
       $route->r_end_longitude = $req->end_longitude;
       $route->r_end_latitude = $req->end_latitude;
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

        $this->RESULT = ['list', 'Get All Routes', $route];
    }

    private function update($req){
        $route = RoutesModel::where('r_id', $req->id)->first();

        $route->update([
           'r_name'=>$req->name,
           'r_start_longitude'=> $req->start_longitude,
           'r_start_latitude' => $req->start_latitude,
           'r_end_longitude'=> $req->end_longitude,
           'r_end_latitude'=> $req->end_latitude,
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