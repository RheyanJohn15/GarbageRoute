<?php
namespace App\Services;
use App\Services\ApiException;
use App\Services\ApiRequest;
use App\Services\V1\Logger;

class ApiEntry{

    private $VALID_TYPE = NULL;
    private $VALID_METHOD = NULL;
    private $RESULT = NULL;
    private $PARAMS = [];
    private $MESSAGE;
    private $RES_DATA;

    private $TYPE;
    private $METHOD;
    private $MESSAGE_RESPONSE;

    private $isVALID = false;

    public function __construct(string $type, string $method, $request, $requestType)
    {

        if(array_key_exists($type, self::API_LIST)){
           $this->VALID_TYPE = self::API_LIST[$type];

        }else{

            throw new ApiException(ApiException::NOT_VALID_TYPE);
        }


        if(array_key_exists($method, $this->VALID_TYPE)){
            $this->VALID_METHOD = $this->VALID_TYPE[$method];
        }else{
           throw new ApiException(ApiException::NOT_VALID_METHOD);
        }


        $this->TYPE = $type;
        $this->METHOD = $method;

        if($this->VALID_METHOD != NULL && $this->VALID_TYPE != NULL){
          $this->isVALID = true;
        }


        if($this->isVALID){

            if($requestType == 'get'){
                $init = new ApiRequest($type, $method, $request);
                $this->RESULT = $init->getResponse();
            }else{
                if($this->checkParams($request)){

               
                $init = new ApiRequest($type, $method, $request);
                if($this->VALID_METHOD['logs']){
                    Logger::save($init->getResponse(), $request);
                }
                $this->RESULT = $init->getResponse();
                };
            }
        }


    }

    private function checkParams($request){
        $this->PARAMS = [];
        foreach ($this->VALID_METHOD['params'] as $param) {
            if (isset($request[$param])) {
                if($request[$param] != ''){
                    $this->PARAMS[$param] = $request[$param];
                }else{
                    throw new ApiException(ApiException::INVALID_BODY);
                }

            } else {
                throw new ApiException(ApiException::INVALID_PARAMS);
            }
        }


        return true;
    }

    public function getResponse(){

        return $this->parseResult($this->RESULT);
    }

    private function parseResult($result){
        $this->MESSAGE = $result[0];
        $this->MESSAGE_RESPONSE = $result[1];
        $this->RES_DATA = $result[2];

        return [
           'status'=> 'success',
           'type'=> $this->TYPE,
           'method'=> $this->METHOD,
           'result'=> ['message'=>$this->MESSAGE,'response'=> $this->MESSAGE_RESPONSE, 'data'=> $this->RES_DATA ]
        ];
    }


    private const API_LIST =
    [
        'truckdriver' =>
            ['add' => ['params'=>['name','username', 'password', 'licensenum', 'contact', 'address'], 'logs'=> true],
             'delete' => ['params'=> ['id'], 'logs'=> true],
             'update' => ['params'=>['name','username', 'id', 'licensenum', 'contact', 'address'], 'logs'=> true],
             'changepass'=> ['params'=>['currentpass', 'newpass', 'id'], 'logs'=> true],
             'details' => ['params'=>['id'], 'logs'=> false],
             'list'=> ['params'=>['empty'], 'logs'=> false],
             'getdriverbyzone' => ['params'=>['empty'], 'logs'=> true],
             'driverassignedzone'=> ['params'=>['zone', 'maindriver', 'standbydriver', 'sched_days', 'collection_start', 'collection_end'], 'logs'=> false],
             'getschedule'=> ['params'=>['empty'], 'logs'=> false]
            ],
       'dumptruck' =>
             [
                'add' => ['params'=>['model', 'can_carry', 'driver', 'plate_num'], 'logs'=> true],
                'delete'=> ['params'=>['id'], 'logs'=> true],
                'update'=> ['params'=>['model', 'can_carry', 'driver', 'id', 'plate_num'], 'logs'=> true],
                'details'=> ['params'=>['id'], 'logs'=> false],
                'list' => ['params'=>['empty'], 'logs'=> false]
             ],
        'complaints' => [
            'submit' => ['params'=>['comp_name', 'contact', 'nature', 'zone'], 'logs'=> false],
            'list'=> ['params'=>['empty'], 'logs'=> false],
            'remove'=> ['params'=>['comp_id'], 'logs'=> true],
            'details'=> ['params'=>['comp_id'], 'logs'=> false],
            'update'=> ['params'=>['comp_id', 'status'], 'logs'=> true],
            'getzone'=>['params'=>['empty'], 'logs'=> false]
        ],
        'drivers' => [
            'getzoneinfo'=> ['params'=>['driverid'], 'logs'=> false],
            'update'=> ['params'=>['id', 'name', 'license', 'address', 'contact', 'username'], 'logs'=> false],
            'changepass'=>['params'=>['id', 'currentpass', 'newpass'], 'logs'=> false],
            'changeprofilepic'=>['params'=>['id', 'pic'], 'logs'=> false],
            'updatetruck'=> ['params'=>['id', 'model', 'capacity', 'plate_num'], 'logs'=> false],
            'changetruckimage'=> ['params'=>['id', 'pic'], 'logs'=> false],
            'inactive'=> ['params'=>['driver_id'], 'logs'=> false],
            'active'=> ['params'=>['driver_id'], 'logs'=> false],
            'updatelocation'=> ['params'=>['driver_id', 'longitude', 'latitude'], 'logs'=> false],
            'completecollection' => ['params'=>['driver_id', 'waypoint_id'], 'logs'=> false],
            'dumpsiteturnover'=> ['params'=>['td_id'], 'logs'=> false],
            'records'=> ['params'=>['empty'], 'logs'=> false],
            'loadschedules'=> ['params'=> ['empty'], 'logs'=> false]
        ],
        'adminaccount'=> [
            'update'=> ['params'=>['id', 'name', 'username'], 'logs'=> true],
            'changepass'=>[ 'params'=>['id', 'newPass', 'currentPass'], 'logs'=> true],
            'changeavatar'=> ['params'=>['id', 'avatar'], 'logs'=> true],
            'add'=> ['params'=>['name', 'username', 'password'], 'logs'=> true],
            'delete'=> ['params'=>['id'], 'logs'=> true],
            'changepassadmin'=> ['params'=>['id', 'newpass'], 'logs'=> true],
            'getalladmin'=>['params'=> ['type'], 'logs'=> false],
            'details'=> ['params'=>['id'], 'logs'=> false],
            'dashboard'=>['params'=>['empty'], 'logs'=> false],
            'garbageperzonefilter'=> ['params'=>['empty'], 'logs'=> false],
            'getallactivity'=> ['params'=>['empty'], 'logs'=> false],
            'loadschedules'=> ['params'=> ['empty'], 'logs'=> false]
        ],
        'brgy'=> [
            'list'=> ['params'=>['empty'], 'logs'=> false],
            'filterbyzone' => [['zone_id'], 'logs'=> false],
        ],
        'zone'=> [
            'list'=> ['params'=>['empty'], 'logs'=> false],
            'addbrgy'=> ['params'=>['brgy', 'zone'], 'logs'=> true],
            'getgeodata'=>['params'=>['empty'], 'logs'=> false],
            'changedumpsitelocation'=> ['params'=>['context', 'longitude', 'latitude'], 'logs'=> true],
            'getdriverassignedzone'=> ['params'=>['driver_id'], 'logs'=> false],
            'addwaypoint'=> ['params'=>['brgy', 'waypoints', 'zone'], 'logs'=> true],
            'getallwaypoint'=>['params'=> ['empty'], 'logs'=> false],
            'getwaypointadmin'=> ['params'=>['empty'], 'logs'=> false],
            'saveschedule'=> ['params'=>['zone', 'day', 'waypoint'], 'logs'=> true],
            'getschedule' => ['params'=>['empty'], 'logs'=> false],
            'removeschedule'=> ['params'=>['id'], 'logs'=> true],
            'removeallwaypoints'=> ['params'=>['zone'], 'logs'=> true]
        ],
        'settings'=> [
            'getval'=> ['params'=>['context'], 'logs'=> false]
        ],
        'landing'=> [
            'dashboard'=> ['params'=>['empty'], 'logs'=> false],
            'loadschedule' => ['params'=> ['empty'], 'logs'=> false]
        ]
    ];

}
