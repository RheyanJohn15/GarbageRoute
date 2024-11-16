<?php
namespace App\Services\V1;
use App\Models\Zones;
use App\Models\BrgyList;
use App\Models\GeoData;
use App\Models\GeoDataCoordinates;
use App\Models\Settings;
use App\Services\ApiException;
use App\Models\Waypoints;
use App\Models\ZoneDrivers;
use Carbon\Carbon;
use App\Models\Schedules;
use App\Models\CollectionProgress;
use App\Models\ZoneSubSched;

class Zone{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function list($req){
        $zone = Zones::all();

        $this->RESULT = ['list', 'Successfully get all zones', $zone];
    }

    private function addbrgy($request){
        $brgy = explode(',', $request->brgy);

        $brgyZones = BrgyList::all();

        foreach($brgyZones as $zones){
            if(in_array($zones->brgy_id, $brgy)){
                if($zones->zone_id == null){
                    $zones->update([
                        'zone_id'=> $request->zone
                    ]);
                }
            }else{
                if($zones->zone_id == $request->zone){
                    $zones->update([
                        'zone_id'=> null
                    ]);
                }
            }
        }


        $this->RESULT = ['Add Baranggays', "Successfully Added the baranggay to a zone", "null"];
    }

    private function getgeodata($request){
        $geoData = GeoData::join('brgy_lists', 'geo_data.brgy_id', '=', 'brgy_lists.brgy_id')->get();
        foreach($geoData as $geo){
            $coords = GeoDataCoordinates::where('gd_id', $geo->gd_id)->get();
            $geo->coordinates = $coords;

            if($geo->zone_id != null){
                $zone = Zones::where('zone_id',$geo->zone_id)->first();
                $geo->zone = $zone;
            }
        }

        $this->RESULT = ['Get Geo Datas', "Successfully get all geodata", $geoData];
    }

    private function changedumpsitelocation($req){
        $sett = Settings::where('settings_context', $req->context)->first();

        $coordinates = "$req->longitude,$req->latitude";

        $sett->update([
            'settings_value'=> $coordinates
        ]);

        $this->RESULT = ['Change Dumpsite Location', "Dumpsite Location Successfully Changed", 'null'];
    }

    private function getdriverassignedzone($req){

        $schedule = Schedules::where('td_id', $req->driver_id)->first();
        $today = Carbon::now()->format('D');

        if($schedule->days != 'everyday'){
            $schedDays = explode(',', $schedule->days);
        }else{
            $schedDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun'];
        }

        $startTime = Carbon::createFromFormat('H:i', $schedule->collection_start, 'Asia/Manila')->setDate(1970, 1, 1);
        $endTime = Carbon::createFromFormat('H:i', $schedule->collection_end, 'Asia/Manila')->setDate(1970, 1, 1);
        $currentTime = Carbon::now('Asia/Manila')->setDate(1970, 1, 1);

        $isScheduleToday = false;
        if (in_array($today, $schedDays) && $currentTime->between($startTime, $endTime)) {
            $isScheduleToday = true;
        }

        $zoneDriver = ZoneDrivers::where('td_id', $req->driver_id)->first();

        if(!$zoneDriver){
          throw new ApiException(ApiException::NO_DATA_FOUND);
        }

        $brgy = BrgyList::where('zone_id', $zoneDriver->zone_id)->join('geo_data', 'geo_data.brgy_id', '=', 'brgy_lists.brgy_id')->get();

        foreach($brgy as $b){
            $coordinates = GeoDataCoordinates::where('gd_id', $b->gd_id)->get();

            $b->coordinates = $coordinates;
        }

        $zone = Zones::where('zone_id', $zoneDriver->zone_id)->first();

        $dumpsite = Settings::where('settings_context', 'dumpsite_location')->first();
        $data = [$zone, $brgy, $dumpsite, $isScheduleToday];

        $this->RESULT = ['Get Zone Coordinates', 'Fetch Zone Coordinates', $data];
    }

    private function addwaypoint($req){
        $check = Waypoints::where('zone_id', $req->zone)->get();

        foreach($check as $data){
            $data->delete();
        }
        $coord = $req->waypoints;
        foreach($req->brgy as $brgy){
            if(array_key_exists($brgy[0], $coord)){
                $coordinates = $coord[$brgy[0]];
                foreach($coordinates as $c){
                    $waypoint = new Waypoints();
                    $waypoint->brgy_id = $brgy[2];
                    $waypoint->longitude = $c[0];
                    $waypoint->latitude = $c[1];
                    $waypoint->zone_id = $req->zone;
                    $waypoint->save();
                }
            }

        }

        $this->RESULT = ['Add Waypoints', "Waypoints are successfully saved", 'null'];
    }

    private function getallwaypoint($req){
        if($req->type == 'admin'){
            $waypoints = Waypoints::where('waypoints.zone_id', $req->zone)->join('brgy_lists','brgy_lists.brgy_id', '=', 'waypoints.brgy_id')->get();
        }else{
            $doneWaypoints = [];
            $getOtherDriverQuery = ZoneDrivers::where('zone_id', $req->zone)->get();
            foreach($getOtherDriverQuery as $otherDriver){
                $queryWaypoints = CollectionProgress::where('td_id', $otherDriver->td_id)->whereDate('created_at', Carbon::today())->get();

                foreach($queryWaypoints as $qw){
                    $doneWaypoints[] = $qw->wp_id;
                }
            }

            $waypoints = Waypoints::where('waypoints.zone_id', $req->zone)
            ->whereNotIn('waypoints.wp_id', $doneWaypoints)
            ->join('brgy_lists','brgy_lists.brgy_id', '=', 'waypoints.brgy_id')->get();

        }

        $this->RESULT = ['Get All Waypoints', "Fetch all waypoints", $waypoints];
    }

    private function getwaypointadmin($req){
        $wp = Waypoints::join('zones','zones.zone_id', '=', 'waypoints.zone_id')->get();

        $this->RESULT = ['Get Waypoints', 'Successfully get all waypoints', $wp];
    }

    private function saveschedule($req){

        $check = ZoneSubSched::where('zone_id', $req->zone)
                            ->where('days', $req->day)
                            ->where('wp_id', $req->waypoint)
                            ->first();

        if($check){
            throw new ApiException(ApiException::DATA_EXIST);
        }

        $sched = new ZoneSubSched();

        $sched->zone_id = $req->zone;
        $sched->days = $req->day;
        $sched->wp_id = $req->waypoint;
        $sched->save();

        $this->RESULT = ['Save Schedules', "Assigned driver to this schedule and waypoint ", 'null'];
    }

    private function getschedule($req){
        $sched = ZoneSubSched::where('zone_sub_scheds.zone_id', $req->zone)->join('waypoints', 'waypoints.wp_id', '=', 'zone_sub_scheds.wp_id')->get();

        $this->RESULT = ['Get Schedule', "Get all schedule to this zone", $sched];
    }

    private function removeschedule($req){
        $sched = ZoneSubSched::where('zss_id', $req->id)->first();

        $sched->delete();
        $this->RESULT = ['Remove Schedule', "Schedule is removed to this Zones", 'null'];
    }

    private function removeallwaypoints($req){
        $wp = Waypoints::where('zone_id', $req->zone)->get();

        foreach($wp as $w){
            $schedule = ZoneSubSched::where('wp_id', $w->wp_id)->get();

            foreach($schedule as $sched){
                $sched->delete();
            }

            $progress = CollectionProgress::where('wp_id', $w->wp_id)->get();
            foreach($progress as $prog){
                $prog->delete();
            }
        }

        foreach($wp as $w){

            $w->delete();
        }
        $this->RESULT = ['Remove All Waypoints', "Zone waypoint are successfully removed for redo", 'null'];
    }

    public function getResult(){
        return $this->RESULT;
    }


}
