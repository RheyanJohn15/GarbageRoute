<?php
namespace App\Services\V1;
use App\Models\Accounts;
use App\Models\TruckDriverModel;
use App\Models\DumpTruckModel;
use Carbon\Carbon;
use App\Models\Complaints;
use App\Models\DumpsiteTurnovers;
use App\Models\ZoneDrivers;
use App\Models\Zones;
use Illuminate\Support\Facades\Hash;
use App\Services\ApiException;

class AdminAccount{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function update($request){
        $account = Accounts::where('acc_id', $request->id)->first();

        $check = Accounts::where('acc_username', $request->username)->first();

        if($check){
            throw new ApiException(ApiException::USERNAME_EXIST);
        }

        $account->update([
            'acc_name'=> $request->name,
            'acc_username'=> $request->username,
        ]);

        $this->RESULT = ['update', 'Account Profile is successfully updated', 'null'];
    }

    private function changepass($request){
        $account = Accounts::where('acc_id', $request->id)->first();

        if(Hash::check($request->currentPass, $account->acc_password)){
            $account->update([
                'acc_password'=> Hash::make($request->newPass),
            ]);

            $this->RESULT = ['changepass', 'Password is Successfully Updated', 'none'];
        }else{
            $this->RESULT = ['changepass', 'Fail: Entered Current Password is invalid', 'none'];
        }
    }

    private function changeavatar($request){
        $account = Accounts::where('acc_id', $request->id)->first();

        $account->update([
            'acc_profile' => $request->avatar,
        ]);

        $this->RESULT = ['changeavatar', 'Avatar is successfully updated', $account];
    }

    private function add($request){
        $check = Accounts::where('acc_username', $request->username)->first();
        if($check){
            throw new ApiException(ApiException::USERNAME_EXIST);
        }
        $admin= new Accounts();

        $admin->acc_type = 'Admin';
        $admin->acc_name = $request->name;
        $admin->acc_username = $request->username;
        $admin->acc_password = Hash::make($request->password);
        $admin->acc_status = 1;

        $admin->save();

        $this->RESULT = ['add', 'Admin Successfully added', 'null'];
    }
    private function delete($request){
        $admin = Accounts::where('acc_id', $request->id)->first();

        $admin->update([
            'acc_status'=> 0
        ]);
        $this->RESULT = ['delete', 'Admin Successfully deleted', 'null'];
    }
    private function changepassadmin($request){
        $admin = Accounts::where('acc_id', $request->id)->first();

        if(Hash::check($request->newpass, $admin->acc_pass)){
            throw new ApiException(ApiException::SAME_PASS);
        }

        $admin->update([
            'admin_password'=> Hash::make($request->newpass)
        ]);

        $this->RESULT = ['changepassadmin', 'Password Successfully Changed', 'null'];
    }

    private function getalladmin($request){
        $account = Accounts::where('acc_status', 1)->get();

        if($request->type != 'Super Admin'){
            $this->RESULT = ['getalladmin', 'Unauthorized', 'null'];
        }else{
            $this->RESULT = ['getalladmin', 'Successfully Fetch all admins', $account];
        }
    }

    private function details($request){
        $account = Accounts::where('acc_id', $request->id)->first();

        $this->RESULT = ['details', 'Succesfully fetch details', $account];
    }

    private function dashboard($request){
        $driver = TruckDriverModel::all();
        $truck = DumpTruckModel::all();
        $complaint = Complaints::all();

        $zoneArry = [];
        $garbagePerZone = [];
        $dumpsiteTurnovers = DumpsiteTurnovers::join('truck_drivers', 'truck_drivers.td_id', '=', 'dumpsite_turnovers.td_id')->get();
        $zones = Zones::all();
        foreach($zones as $zone){
            $zoneArry[] = $zone->zone_name;
            $garbagePerZone[] = 0;
        }

        foreach($dumpsiteTurnovers as $dt){
            $ZoneDrivers = ZoneDrivers::where('td_id', $dt->td_id)->first();
            $dumpTruck = DumpTruckModel::where('dt_id', $dt->dt_id)->first();
            $getZone = Zones::where('zone_id', $ZoneDrivers->zone_id)->first();

            $zoneIndex = array_search($getZone->zone_name, $zoneArry);

            if($zoneIndex){
                $garbagePerZone[$zoneIndex] += $dumpTruck->can_carry;
            }
        }

        $driversCollection = [];
        $currentYear = Carbon::now()->year;
        $driverList = TruckDriverModel::all();
        foreach($driverList as $driver){
           for($i = 0; $i < 12; $i++){
            $turnovers = DumpsiteTurnovers::where('td_id', $driver->td_id)
                ->whereMonth('created_at', $i+1)
                ->whereYear('created_at', $currentYear)
                ->get();

            $tons = 0;
            foreach($turnovers as $to){
                $truck = DumpTruckModel::where('dt_id', $to->dt_id)->first();
                $tons += $truck->can_carry;
            }
            $driversCollection[$driver->name][$i] = $tons;
           }
        }

        $resolvedComplaint = Complaints::where('comp_status', 2)->get();
        $comp = [
            "Missed Collection",
            "Late Irregular Service",
            "Improper Handling of Waste",
        ];

        $complaintData = [];
        foreach ($comp as $c){
            $count = Complaints::where('comp_nature', $c)->count();
            $percentage = 0;
            if($count > 0){
                $percentage = ($count / $complaint->count()) * 100;
            }
            array_push($complaintData, $percentage);
        }

        $complaintStatus = [];

        for($i = 0; $i <= 5; $i++){
            $count = Complaints::where('comp_status', $i)->count();

            $complaintStatus[] = $count;
        }

        $dashboard = [
            $driver,
            $truck,
            $complaint,
            $complaintData,
            $resolvedComplaint,
            $complaintStatus,
            $zoneArry,
            $garbagePerZone,
            $driversCollection
        ];

        $this->RESULT = ['dashboard', 'Succesfully fetch all dashboard data', $dashboard];
    }

    public function getResult(){
        return $this->RESULT;
    }
}
