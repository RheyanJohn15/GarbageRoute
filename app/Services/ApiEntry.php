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
             'details' => ['params'=>['id'], 'logs'=> true],
             'list'=> ['params'=>['empty'], 'logs'=> true],
             'getdriverbyzone' => ['params'=>['empty'], 'logs'=> true],
             'driverassignedzone'=> ['params'=>['zone', 'maindriver', 'standbydriver', 'sched_days', 'collection_start', 'collection_end'], 'logs'=> true],
             'getschedule'=> ['params'=>['empty'], 'logs'=> true]
            ],
       'dumptruck' =>
             [
                'add' => ['params'=>['model', 'can_carry', 'driver', 'plate_num'], 'logs'=> true],
                'delete'=> ['params'=>['id'], 'logs'=> true],
                'update'=> ['params'=>['model', 'can_carry', 'driver', 'id', 'plate_num'], 'logs'=> true],
                'details'=> ['params'=>['id'], 'logs'=> true],
                'list' => ['params'=>['empty'], 'logs'=> true]
             ],
        'complaints' => [
            'submit' => ['params'=>['comp_name', 'email', 'contact', 'nature', 'zone'], 'logs'=> true],
            'list'=> ['params'=>['empty'], 'logs'=> true],
            'remove'=> ['params'=>['comp_id'], 'logs'=> true],
            'details'=> ['params'=>['comp_id'], 'logs'=> true],
            'update'=> ['params'=>['comp_id', 'status'], 'logs'=> true],
            'getzone'=>['params'=>['empty'], 'logs'=> true]
        ],
        'drivers' => [
            'getzoneinfo'=> ['params'=>['driverid'], 'logs'=> true],
            'update'=> ['params'=>['id', 'name', 'license', 'address', 'contact', 'username'], 'logs'=> true],
            'changepass'=>['params'=>['id', 'currentpass', 'newpass'], 'logs'=> true],
            'changeprofilepic'=>['params'=>['id', 'pic'], 'logs'=> true],
            'updatetruck'=> ['params'=>['id', 'model', 'capacity', 'plate_num'], 'logs'=> true],
            'changetruckimage'=> ['params'=>['id', 'pic'], 'logs'=> true],
            'inactive'=> ['params'=>['driver_id'], 'logs'=> true],
            'active'=> ['params'=>['driver_id'], 'logs'=> true],
            'updatelocation'=> ['params'=>['driver_id', 'longitude', 'latitude'], 'logs'=> true],
            'completecollection' => ['params'=>['driver_id', 'waypoint_id'], 'logs'=> true],
            'dumpsiteturnover'=> ['params'=>['td_id'], 'logs'=> true],
            'records'=> ['params'=>['empty'], 'logs'=> true]
        ],
        'adminaccount'=> [
            'update'=> ['params'=>['id', 'name', 'username'], 'logs'=> true],
            'changepass'=>[ 'params'=>['id', 'newPass', 'currentPass'], 'logs'=> true],
            'changeavatar'=> ['params'=>['id', 'avatar'], 'logs'=> true],
            'add'=> ['params'=>['name', 'username', 'password'], 'logs'=> true],
            'delete'=> ['params'=>['id'], 'logs'=> true],
            'changepassadmin'=> ['params'=>['id', 'newpass'], 'logs'=> true],
            'getalladmin'=>['params'=> ['type'], 'logs'=> true],
            'details'=> ['params'=>['id'], 'logs'=> true],
            'dashboard'=>['params'=>['empty'], 'logs'=> true],
            'garbageperzonefilter'=> ['params'=>['empty'], 'logs'=> true]
        ],
        'brgy'=> [
            'list'=> ['params'=>['empty'], 'logs'=> true],
            'filterbyzone' => [['zone_id'], 'logs'=> true],
        ],
        'zone'=> [
            'list'=> ['params'=>['empty'], 'logs'=> true],
            'addbrgy'=> ['params'=>['brgy', 'zone'], 'logs'=> true],
            'getgeodata'=>['params'=>['empty'], 'logs'=> true],
            'changedumpsitelocation'=> ['params'=>['context', 'longitude', 'latitude'], 'logs'=> true],
            'getdriverassignedzone'=> ['params'=>['driver_id'], 'logs'=> true],
            'addwaypoint'=> ['params'=>['brgy', 'waypoints', 'zone'], 'logs'=> true],
            'getallwaypoint'=>['params'=> ['empty'], 'logs'=> true],
            'getwaypointadmin'=> ['params'=>['empty'], 'logs'=> true],
            'saveschedule'=> ['params'=>['zone', 'day', 'waypoint'], 'logs'=> true],
            'getschedule' => ['params'=>['empty'], 'logs'=> true],
            'removeschedule'=> ['params'=>['id'], 'logs'=> true],
            'removeallwaypoints'=> ['params'=>['zone'], 'logs'=> true]
        ],
        'settings'=> [
            'getval'=> ['params'=>['context'], 'logs'=> true]
        ],
        'landing'=> [
            'dashboard'=> ['params'=>['empty'], 'logs'=> true]
        ]
    ];

}
