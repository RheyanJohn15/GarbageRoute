<?php
namespace App\Services\V1;
use App\Models\Complaints;
use App\Models\GeoData;
use App\Models\GeoDataCoordinates;
use App\Models\Zones;
use App\Models\Settings;
use App\Models\Waypoints;
use App\Models\CollectionProgress;
use Carbon\Carbon;

class Landing{
    
    private $RESULT = null;
    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function dashboard($req){
        $pending = Complaints::where('comp_status', 0)->get()->count();
        $progress = Complaints::where('comp_status', 1)->get()->count();
        $resolved = Complaints::where('comp_status', 2)->get()->count();
        $complaints = Complaints::where('comp_status', 2)->limit(15)->get();

        $geoData = GeoData::join('brgy_lists', 'geo_data.brgy_id', '=', 'brgy_lists.brgy_id')->get();
        foreach($geoData as $geo){
            $coords = GeoDataCoordinates::where('gd_id', $geo->gd_id)->get();
            $geo->coordinates = $coords;

            if($geo->zone_id != null){
                $zone = Zones::where('zone_id',$geo->zone_id)->first();
                $geo->zone = $zone;
            }
        }

        $dumpsiteLocation = Settings::where("settings_context", "dumpsite_location")->first();

        $todayCollection = CollectionProgress::whereDate('created_at', Carbon::today())->get();
        $wpId = [];

        foreach($todayCollection as $collect){
            $wpId[] = $collect->wp_id;
        }

        $cleanWaypoint_id = array_unique($wpId);
        
        $wp = Waypoints::whereNotIn('wp_id', $cleanWaypoint_id)->join('zones','zones.zone_id', '=', 'waypoints.zone_id')->get();

        $data = [
            $pending,
            $progress,
            $resolved,
            $complaints,
            $geoData,
            $dumpsiteLocation,
            $wp
        ];

        $this->RESULT = ['dashboard', 'Get All Dashboard', $data];
    }

    public function getResult(){
        return $this->RESULT;
    }
}