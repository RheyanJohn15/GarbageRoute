<?php
namespace App\Services\V1;
use App\Models\DumpTruckModel;
class DumpTruck{
    private $RESULT = null;
    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function add($req){
        $dumpTruck = new DumpTruckModel();

        $dumpTruck->model = $req->model;
        $dumpTruck->can_carry = $req->can_carry;
        $dumpTruck->td_id = $req->driver;

        $dumpTruck->save();

        $this->RESULT = ['add' ,'Dump Truck Successfully Added', $dumpTruck];
    }

    private function list($req){
      
      $dumpTruck = DumpTruckModel::all();
      
      $this->RESULT = ['list', 'List of all dumptruck', $dumpTruck];
    
    }  

    public function getResult(){
        return $this->RESULT;
    }
}