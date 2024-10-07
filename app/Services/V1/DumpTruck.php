<?php
namespace App\Services\V1;
use App\Models\DumpTruckModel;
use App\Models\TruckDriverModel;
use App\Services\ApiException;

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
        $dumpTruck->plate_num = $req->plate_num;
        $dumpTruck->save();

        $this->RESULT = ['add' ,'Dump Truck Successfully Added', $dumpTruck];
    }

    private function list($req){

      $dumpTruck = DumpTruckModel::all();

      foreach($dumpTruck as $truck){
        $driver = TruckDriverModel::where('td_id', $truck->td_id)->first();

       if($driver){
        $truck->driver = $driver->name == 'null' ? 'N/A' : $driver->name;
        $truck->driver_id = $driver->td_id;
       }
      }

      $this->RESULT = ['list', 'List of all dumptruck', $dumpTruck];

    }


    private function update($req){
      $dumpTruck = DumpTruckModel::where('dt_id', $req->id)->first();

      $dumpTruck->update([
         'model'=>$req->model,
         'can_carry' => $req->can_carry,
         'driver'=> $req->driver,
         'plate_num'=> $req->plate_num
      ]);

      $this->RESULT = ['update','Dump Truck Successfully Updated', $dumpTruck];
    }

    private function delete($req){
      $dumpTruck = DumpTruckModel::where('dt_id', $req->id)->first();

      $dumpTruck->delete();

      $this->RESULT = ['delete', 'Dump Truck Successfully Deleted', null];
    }

    private function details($req){
       $dumpTruck = DumpTruckModel::where('dt_id', $req->id)->first();

       if(!$dumpTruck){
        throw new ApiException(ApiException::NO_DATA_FOUND);
       }

       $driver  = TruckDriverModel::where('td_id', $dumpTruck->td_id)->first();

       $dumpTruck->driver = $driver;

       $this->RESULT = ['details', 'Detail of the dump truck', $dumpTruck];
    }

    public function getResult(){
        return $this->RESULT;
    }
}
