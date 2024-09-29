<?php
namespace App\Services\V1;
use App\Models\RoutesModel;
use App\Models\RouteProgress;
use App\Events\GpsUpdate;
use App\Models\TruckDriverModel;
use App\Models\DumpTruckModel;
use Illuminate\Support\Facades\Hash;
use App\Services\ApiException;

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
        
        $progress = RouteProgress::where('r_id', $request->routeid)->first();
        
        $route -> progress = "null";
        
        if($progress){
            $route->progress = $progress;
        }

        $this->RESULT = ['details', 'Succesffully Fetch Route Details', $route];
    }

    private function updatelocation($request){
        $routes = RouteProgress::where('r_id',$request->routeId)->first();

        $routes->update([
            'rp_current_location' => $request->coordinates
        ]);

        $time = explode('-', $request->waypointTime);
        $updateProgress = $routes->rp_progress;
        //if the truck stayed at the waypoint for more than 3 minutes
        $waypoints = explode(',', $routes->rp_progress);
        
        array_pop($waypoints);
        if($time[1] > 180000){
            $updateProgress = "";

            $selectedWaypoint = $waypoints[$time[0] - 1];
            $getAttribute = explode('**', $selectedWaypoint);
            $newAttribute = $getAttribute[0] . "**True,";

            for($i = 0; $i < count($waypoints); $i++){
                if($i != ($time[0] - 1)){
                    $updateProgress .= $waypoints[$i] . ",";
                }else{
                    $updateProgress .= $newAttribute;
                }
            }
            $updateProgress .= "**False";
            $routes->update([
                'rp_progress'=> $updateProgress
            ]);
        }

        $checkRoutesProgress = $routes->rp_progress;
        $listRouteProgress = explode(',', $checkRoutesProgress);
        array_pop($listRouteProgress);
        $statusCheck = 0;
        foreach($listRouteProgress as $list){
            $getStatus = explode('**', $list);
            if($getStatus[1]){
                $statusCheck++;
            }
        }  
        
        $statusResult = false;
        if($statusCheck == count($listRouteProgress)){
            $statusResult = true;
        }

        event(new GpsUpdate());
        $this->RESULT = ['updatelocation', 'Succesfully updated the location', $statusResult];
    }

    private function startcollection($request){
        $prog = new RouteProgress();
        $route = RoutesModel::where('r_id', $request->route_id)->first();
        
        $check = RouteProgress::where('r_id', $request->route_id)->first();

        if(!$check){
            $prog->r_id = $request->route_id;
            $progress = [];
            $coordinates = explode(',',$route->r_coordinates);
            foreach($coordinates as $coord){
              array_push($progress,  $coord. "**False");
            }
            $prog->rp_progress = implode(',', $progress);
            $prog->rp_status = 0;
            $prog->save();
        }else{

            if($check->rp_status == 1){
                $check->update([
                    'rp_status'=> 0,
                ]);
            }else{
                $check->update([
                    'rp_status'=> 1
                ]);
            }
          
        }
        

        $this->RESULT = ['startcollection', "Start Garbage Collection", $check];

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
    
    public function getResult(){
        return $this->RESULT;
    }
}