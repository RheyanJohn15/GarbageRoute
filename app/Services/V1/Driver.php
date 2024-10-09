<?php
namespace App\Services\V1;
use App\Models\ZoneDrivers;
use App\Models\Zones;
use App\Models\BrgyList;
use App\Events\GpsUpdate;
use App\Models\ActiveDrivers;
use App\Models\TruckDriverModel;
use App\Models\CollectionProgress;
use App\Models\DumpTruckModel;
use Illuminate\Support\Facades\Hash;
use App\Services\ApiException;

class Driver{

    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function getzoneinfo($req){
        $getZone = ZoneDrivers::where('td_id', $req->driverid)->first();
        if(!$getZone){
            throw new ApiException(ApiException::NO_ZONE_ASSIGNED);
        }

        $zoneData = Zones::where('zone_id', $getZone->zone_id)->first();
        $dumpTruck = DumpTruckModel::where('td_id', $req->driverid)->first();
        $brgyList = BrgyList::where('zone_id', $zoneData->zone_id)->get();
        $getOtherAssigned = ZoneDrivers::where('zone_id', $getZone->zone_id)->where('td_id', '!=', $req->driverid)->first();
        $otherAssigned = TruckDriverModel::where('td_id', $getOtherAssigned->td_id)->first();
        $otherAssignedTruck = DumpTruckModel::where('td_id', $otherAssigned->td_id)->first();

        $data = [$zoneData, $dumpTruck, $otherAssigned, $otherAssignedTruck, $brgyList];

        $this->RESULT = ['getzoneinfo', 'All zone info are retrieved', $data];

    }


    private function update($request){
        $driver = TruckDriverModel::where('td_id', $request->id)->first();

        $driver->update([
            'username'=> $request->username,
            'name'=> $request->name,
            'license'=> $request->license,
            'address' => $request->address,
            'contact'=> $request->contact
        ]);

        $this->RESULT = ['update', "Account Successfully Updated", 'null'];
    }

    private function changepass($request){
        $driver = TruckDriverModel::where('td_id', $request->id)->first();

        if(Hash::check($request->currentpass, $driver->password)){
            $driver->update([
                'password' => Hash::make($request->newpass)
            ]);

            $this->RESULT = ['changepass', "Password is successfully updated", "null"];
        }else{
            throw new ApiException(ApiException::CURRENT_PASS_INVALID);
        }
    }

    private function changeprofilepic($request){
        $pic = $request->file('pic');

        $validType = ['jpg', 'jpeg', 'png'];
        $picType = $pic->getClientOriginalExtension();
        if(!in_array($picType, $validType)){
            throw new ApiException(ApiException::INVALID_PIC_TYPE);
        }

        if($pic->getSize() > 10485760){
            throw new ApiException(ApiException::LARGE_IMAGE);
        }

        $fileName =  "Driver". $request->id . ".". $picType;
        $pic->move(public_path('UserPics/Driver/'), $fileName);

        $driver = TruckDriverModel::where('td_id', $request->id)->first();

        $driver->update([
            'profile_pic'=> $fileName
        ]);

        $this->RESULT = ['changeprofilepic', "Profile Pic Successfully Updated", 'null'];
    }

    private function updatetruck($request){
        $truck = DumpTruckModel::where('dt_id', $request->id)->first();

        $truck->update([
            'model'=> $request->model,
            'can_carry'=> $request->capacity
        ]);

        $this->RESULT = ['updatetruck', "Truck Details successfully updated", "null"];
    }

    private function changetruckimage($request){
        $pic = $request->file('pic');

        $validType = ['jpg', 'jpeg', 'png'];
        $picType = $pic->getClientOriginalExtension();
        if(!in_array($picType, $validType)){
            throw new ApiException(ApiException::INVALID_PIC_TYPE);
        }

        if($pic->getSize() > 10485760){
            throw new ApiException(ApiException::LARGE_IMAGE);
        }

        $fileName =  "Truck". $request->id . ".". $picType;
        $pic->move(public_path('UserPics/Truck/'), $fileName);

        $truck = DumpTruckModel::where('dt_id', $request->id)->first();

        $truck->update([
            'profile_pic'=> $fileName
        ]);

        $this->RESULT = ['changetruckimage', "Truck Image successfully uploaded", "null"];
    }

    private function inactive($req){
        $deact = ActiveDrivers::where('td_id', $req->driver_id)->where('status', 'active')->first();

        $deact->update([
            'status'=> 'inactive'
        ]);

        $this->RESULT = ['inactive', "Driver is now inactive", 'null'];
    }

    private function active($req){
        $active = new ActiveDrivers();

        $check = ActiveDrivers::where('td_id', $req->driver_id)->where('status', 'active')->first();

        if(!$check){
            $active->td_id  = $req->driver_id;
            $active->status = 'active';
            $active->save();
        }

        $this->RESULT = ['active', "Driver is now Active", 'null'];
    }

    private function updatelocation($req){
        $active = ActiveDrivers::where('td_id', $req->driver_id)->where('status', 'active')->first();

        $active->update([
            'ad_coordinates' => "$req->longitude,$req->latitude"
        ]);

        event(new GpsUpdate());

        $this->RESULT = ['updatelocation', 'Updated driver Location', 'null'];
    }

    private function addcollection($req){
        $progress = new CollectionProgress();
        $progress->td_id = $req->driver_id;
        $progress->brgy_id = $req->brgy_id;
        $progress->status = "0";
        $progress->time_entered = $req->time_entered;
        $progress->save();

        $this->RESULT = ['addcollection', "Added Collection", $progress->cp_id];
    }

    private function completecollection($req){
        $collection = CollectionProgress::where('cp_id', $req->collection_id)->first();

        $collection->update([
            'status' => "1",
            'time_out' => $req->time_out
        ]);

        $this->RESULT = ['completecollection', "Collection Complete in this Location", 'null'];
    }
    public function getResult(){
        return $this->RESULT;
    }
}
