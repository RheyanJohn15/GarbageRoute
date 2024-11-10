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
use App\Models\DumpsiteTurnovers;

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

        $this->RESULT = ['changeprofilepic', "Profile Pic Successfully Updated", $fileName];
    }

    private function updatetruck($request){
        $truck = DumpTruckModel::where('dt_id', $request->id)->first();

        $truck->update([
            'model'=> $request->model,
            'can_carry'=> $request->capacity,
            'plate_num' => $request->plate_num
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

        $this->RESULT = ['changetruckimage', "Truck Image successfully uploaded", $fileName];
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


    private function completecollection($req){
        $collection = new CollectionProgress();
        $collection->td_id = $req->driver_id;
        $collection->wp_id = $req->waypoint_id;
        $collection->status = "Complete";
        $collection->save();
        $this->RESULT = ['completecollection', "Collection Complete in this Location", 'null'];
    }

    private function dumpsiteturnover($req){
        $turnover = new DumpsiteTurnovers();

        $truck = DumpTruckModel::where('td_id', $req->td_id)->first();
        $turnover->td_id = $req->td_id;
        $turnover->dt_id = $truck->dt_id;
        $turnover->save();

        $this->RESULT = ['dumpsiteturnover', "Garbage is successfully dump in the dumpsite", 'null'];
    }

    private function records($req){
        $collection = CollectionProgress::where('td_id', $req->driver_id)
        ->join('waypoints', 'waypoints.wp_id', '=', 'collection_progress.wp_id')
        ->select('collection_progress.*', 'waypoints.longitude', 'waypoints.latitude')
        ->get();
        $dumpsite = DumpsiteTurnovers::where('td_id', $req->driver_id)
        ->get();

        foreach($dumpsite as $d){
            $truck = DumpTruckModel::where('dt_id', $d->dt_id)->first();

            $d->capacity = $truck->can_carry;
        }

        $data  = [$collection, $dumpsite];

        $this->RESULT = ['records', "Get All Records", $data];
    }

    
    private function loadschedules($req){

    }

    public function getResult(){
        return $this->RESULT;
    }
}
