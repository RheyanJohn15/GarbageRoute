<?php
namespace App\Services\V1;
use App\Models\Zones;
use App\Models\BrgyList;
use App\Models\GeoData;
use App\Models\GeoDataCoordinates;
use App\Models\Settings;
use App\Services\ApiException;
use App\Models\ZoneDrivers;

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


        $this->RESULT = ['addbrgy', "Successfully Added the baranggay to a zone", "null"];
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

        $this->RESULT = ['getgeodata', "Successfully get all geodata", $geoData];
    }

    private function changedumpsitelocation($req){
        $sett = Settings::where('settings_context', $req->context)->first();

        $coordinates = "$req->longitude,$req->latitude";
        
        $sett->update([
            'settings_value'=> $coordinates
        ]);

        $this->RESULT = ['changedumpsitelocation', "Dumpsite Location Successfully Changed", 'null'];
    }

    private function getdriverassignedzone($req){
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

        $data = [$zone, $brgy];
        
        $this->RESULT = ['getdriverassignedzone', 'Fetch Zone Coordinates', $data];
    }

    public function getResult(){
        return $this->RESULT;
    }
}
