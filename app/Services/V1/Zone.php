<?php
namespace App\Services\V1;
use App\Models\Zones;
use App\Models\BrgyList;
use Database\Seeders\Brgy;
use PDO;

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

    public function getResult(){
        return $this->RESULT;
    }
}
