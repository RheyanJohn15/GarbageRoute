<?php
namespace App\Services\V1;
use App\Models\TruckDriverModel;
use Illuminate\Support\Facades\Hash;
use App\Services\ApiException;
use App\Models\DumpTruckModel;
use App\Models\ZoneDrivers;
use App\Models\ActiveDrivers;
use App\Models\CollectionProgress;
use App\Models\DumpsiteTurnovers;
use App\Models\Schedules;

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

       $this->RESULT = ['Add Truck Driver' ,'Truck Driver Successfully Added', $td];
    }

    private function delete($req){

       $check = TruckDriverModel::where('td_id', $req->id)->first();

       if(!$check){
         throw new ApiException(ApiException::NO_DATA_FOUND);
       }

       $truck = DumpTruckModel::where('td_id', $req->id)->get();
       if($truck->count() > 0){
         foreach($truck as $t){
            $t->update(['td_id' => null]);
         }
       }

       $activeDrivers = ActiveDrivers::where('td_id', $req->id)->get();

       foreach($activeDrivers as $ad){
        $ad->delete();
       }

       $collectionProgress = CollectionProgress::where('td_id', $req->id)->get();
       foreach($collectionProgress as $cp){
        $cp->delete();
       }

       $dumpsiteTurnover = DumpsiteTurnovers::where('td_id', $req->id)->get();
       foreach($dumpsiteTurnover as $dt){
        $dt->delete();
       }

       $schedules = Schedules::where("td_id", $req->id)->first();

      if($schedules){
        $schedules->delete();
      }
       $zoneDrivers = ZoneDrivers::where("td_id", $req->id)->first();

        if($zoneDrivers){
            $zoneDrivers->delete();
        }

       $check->delete();

        $this->RESULT = ['Delete Truck Drivers', 'Truck Driver is Successfully Deleted', null];


     }

    private function list($req){
        $truck = TruckDriverModel::where('status', 'enable')->get();
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

        $this->RESULT = ['Update Truck Driver', 'Update Truck Driver', $update];
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
              $updateSchedMain = Schedules::where('td_id', $updateMain->td_id)->first();

              $updateSchedMain->update([
                'td_id' => $mainDriver[0],
                'days'=> $req->sched_days,
                'collection_start'=> $req->collection_start,
                'collection_end' => $req->collection_end,
              ]);

              $updateMain->update([
                'td_id'=> $mainDriver[0]
              ]);
              break;
            default:
              $updateStandBy = ZoneDrivers::where('zone_id', $req->zone)->where('type', "Standby Driver")->first();
              $updateSchedStandby = Schedules::where('td_id', $updateStandBy->td_id)->first();

              $updateSchedStandby -> update([
                'td_id' => $standbyDriver[0],
                'days'=> $req->sched_days,
                'collection_start'=> $req->collection_start,
                'collection_end'=> $req->collection_end
              ]);
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


        $scheduleMain = new Schedules();
        $scheduleMain->td_id = $mainDriver[0];
        $scheduleMain->days = $req->sched_days;
        $scheduleMain->collection_start = $req->collection_start;
        $scheduleMain->collection_end = $req->collection_end;
        $scheduleMain->save();

        $scheduleStandby = new Schedules();
        $scheduleStandby->td_id  = $standbyDriver[0];
        $scheduleStandby->days = $req->sched_days;
        $scheduleStandby->collection_start = $req->collection_start;
        $scheduleStandby->collection_end = $req->collection_end;
        $scheduleStandby->save();
      }

      $this->RESULT = ['Assigned Driver To Zone', "Driver Assigned to Zone $req->zone Successfully", "null"];
    }

    private function getschedule($req){
        $zone = ZoneDrivers::where('zone_id', $req->zone)->get();

        $data = [];
        foreach($zone as $z){
          $schedule = Schedules::where('td_id', $z->td_id)->first();
          $schedule->zone = $z;
          $data[] = $schedule;
        }

        $this->RESULT = ['getschedule', "Fetch all data", $data];
    }

    public function getResult(){
        return $this->RESULT;
    }
}
