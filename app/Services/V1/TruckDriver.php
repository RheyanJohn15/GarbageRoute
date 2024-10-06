<?php
namespace App\Services\V1;
use App\Models\TruckDriverModel;
use Illuminate\Support\Facades\Hash;
use App\Services\ApiException;
use App\Models\DumpTruckModel;
use App\Models\ZoneDrivers;
class TruckDriver {
    
    private $RESULT = null;
    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function add($req){
       $td = new TruckDriverModel();
       $td->username = $req->username;
       $td->password = Hash::make($req->password);
       $td->name = $req->name;
       $td->license = $req->licensenum;
       $td->contact = $req->contact;
       $td->address = $req->address;
       $td->save();

       $this->RESULT = ['add' ,'Truck Driver Successfully Added', $td];
    }

    private function delete($req){
        
       $check = TruckDriverModel::where('td_id', $req->id)->first();

       if(!$check){
         throw new ApiException(ApiException::NO_DATA_FOUND);
       }

       $truck = DumpTruckModel::where('td_id', $req->id)->get();
       if($truck->count() > 0){
         foreach($truck as $t){
            $t->update(['td_id' => '0']);
         }
       }

       $check->delete();
        $this->RESULT = ['delete', 'Truck Driver is Successfully Deleted', null];
     }

    private function list($req){
        $truck = TruckDriverModel::all();
        foreach($truck as $tr){
          $dump = DumpTruckModel::where('td_id', $tr->td_id)->first();
          $tr->dumptruck = $dump ? $dump : null;
        }
        
        $this->RESULT = ['list', 'List of all truck drivers', $truck];
    }

    private function update($req){
        $update = TruckDriverModel::where('td_id', $req->id)->first();

        if(!$update){
            throw new ApiException(ApiException::NO_DATA_FOUND);
        }

        $update->update([
             'name'=> $req->name,
             'username'=>$req->username,
             'password'=>Hash::make($req->password),
             'license'=>$req->licensenum,
             'contact'=>$req->contact,
             'address'=>$req->address
        ]);

        $this->RESULT = ['update', 'Update Truck Driver', $update];
    }

    private function details($req){
      $check = TruckDriverModel::where('td_id', $req->id)->first();

      if(!$check){
        throw new ApiException(ApiException::NO_DATA_FOUND);
      }

      $truck = DumpTruckModel::where('td_id', $req->id)->first();
      
      $check->truck = $truck;

      $this->RESULT = ['details', 'Show truck details', $check];
    }

    private function getdriverbyzone($req){
      $drivers = TruckDriverModel::all();
      
      foreach($drivers as $drive){
        $zone = ZoneDrivers::where('td_id', $drive->td_id)->join('zones', 'zones.zone_id', '=', 'zone_drivers.zone_id')->first();
        if($zone){
          $drive->zone = $zone;
        }
      }

      $this->RESULT = ['getdriverbyzone', "Fetch Driver By Zone", $drivers];
    }

    private function driverassignedzone($req){
      $zone = ZoneDrivers::where('zone_id', $req->zone)->get();
      $mainDriver = explode('-', $req->maindriver);
      $standbyDriver = explode('-', $req->standbydriver);
      
      if(count($zone) == 2){
        foreach($zone as $z){
          switch($z->type){
            case "Main Driver":
              $updateMain = ZoneDrivers::where('zone_id', $req->zone)->where('type', 'Main Driver')->first();
              $updateMain->update([
                'td_id'=> $mainDriver[0]
              ]);
              break;
            default:
              $updateStandBy = ZoneDrivers::where('zone_id', $req->zone)->where('type', "Standby Driver")->first();
              $updateStandBy->update([
                'td_id'=> $standbyDriver[0]
              ]);
              break;
          }
        }
      }else{
        $addMain = new ZoneDrivers();
        $addMain->zone_id = $req->zone;
        $addMain->td_id = $mainDriver[0];
        $addMain->type = "Main Driver";
        $addMain->save();

        $addStandBy = new ZoneDrivers();
        $addStandBy->zone_id = $req->zone;
        $addStandBy->td_id = $standbyDriver[0];
        $addStandBy->type = "Standby Driver";
        $addStandBy->save();
      }

      $this->RESULT = ['driverassignedzone', "Driver Assigned to Zone $req->zone Successfully", "null"];
    }

    public function getResult(){
        return $this->RESULT;
    }
}