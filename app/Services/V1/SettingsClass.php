<?php
namespace App\Services\V1;
use App\Models\Settings;
class SettingsClass{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function getval($request){
        $sett = Settings::where('settings_context', $request->context)->first();

        $this->RESULT = ['getval', "Successfully get the settings context", $sett];
    }
    
    public function getResult(){
        return $this->RESULT;
    }
}