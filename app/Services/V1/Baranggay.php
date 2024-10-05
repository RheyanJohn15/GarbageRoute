<?php
namespace App\Services\V1;
use App\Models\BrgyList;
use App\Models\Zones;
class Baranggay{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function list($req){
        $brgy = BrgyList::all();

        foreach($brgy as $b){
            if($b->zone_id != null){
                $zone = Zones::where('zone_id', $b->zone_id)->first();
                $b->zone = $zone;
            }
        }
        $this->RESULT = ['list', 'Successfully get all brgy', $brgy];
    }

    private function filterbyzone($request){
        $brgy = $request->zone_id === "all" ? BrgyList::all() : BrgyList::where('zone_id', $request->zone_id)->get();

        $this->RESULT = ['filterbyzone', "Brgy Filter By Zone", $brgy];
    }

    public function getResult(){
        return $this->RESULT;
    }
}
