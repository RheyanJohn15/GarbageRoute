<?php
namespace App\Services\V1;
use App\Models\Complaints;
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

        $data = [
            $pending,
            $progress,
            $resolved,
        ];

        $this->RESULT = ['dashboard', 'Get All Dashboard', $data];
    }

    public function getResult(){
        return $this->RESULT;
    }
}