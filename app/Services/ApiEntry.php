<?php
namespace App\Services;
use App\Services\ApiException;
use App\Services\ApiRequest;

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
                $this->RESULT = $init->getResponse();
                };
            }
        }


    }

    private function checkParams($request){
        $this->PARAMS = [];
        foreach ($this->VALID_METHOD as $param) {
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
            ['add' => ['name','username', 'password', 'licensenum', 'contact', 'address'],
             'delete' => ['id'],
             'update' => ['name','username', 'id', 'licensenum', 'contact', 'address'],
             'changepass'=> ['currentpass', 'newpass', 'id'],
             'details' => ['id'],
             'list'=> ['empty'],
             'getdriverbyzone' => ['empty'],
             'driverassignedzone'=> ['zone', 'maindriver', 'standbydriver']
            ],
       'dumptruck' =>
             [
                'add' => ['model', 'can_carry', 'driver', 'plate_num'],
                'delete'=> ['id'],
                'update'=> ['model', 'can_carry', 'driver', 'id', 'plate_num'],
                'details'=> ['id'],
                'list' => ['empty']
             ],
        'complaints' => [
            'submit' => ['comp_name', 'email', 'contact', 'nature', 'remarks'],
            'list'=> ['empty'],
            'remove'=> ['comp_id'],
            'details'=> ['comp_id'],
            'update'=> ['comp_id', 'status']
        ],
        'drivers' => [
            'getzoneinfo'=> ['driverid'],
            'update'=> ['id', 'name', 'license', 'address', 'contact', 'username'],
            'changepass'=>['id', 'currentpass', 'newpass'],
            'changeprofilepic'=>['id', 'pic'],
            'updatetruck'=> ['id', 'model', 'capacity'],
            'changetruckimage'=> ['id', 'pic'],
            'inactive'=> ['driver_id'],
            'active'=> ['driver_id'],
            'updatelocation'=> ['driver_id', 'longitude', 'latitude'],
            'addcollection' => ['driver_id', 'brgy_id', 'time_entered'],
            'completecollection' => ['collection_id', 'time_out']
        ],
        'adminaccount'=> [
            'update'=> ['id', 'name', 'username'],
            'changepass'=> ['id', 'newPass', 'currentPass'],
            'changeavatar'=> ['id', 'avatar'],
            'add'=> ['name', 'username', 'password'],
            'delete'=> ['id'],
            'changepassadmin'=> ['id', 'newpass'],
            'getalladmin'=> ['type'],
            'details'=> ['id'],
            'dashboard'=>['empty']
        ],
        'brgy'=> [
            'list'=> ['empty'],
            'filterbyzone' => ['zone_id'],
        ],
        'zone'=> [
            'list'=> ['empty'],
            'addbrgy'=> ['brgy', 'zone'],
            'getgeodata'=>['empty'],
            'changedumpsitelocation'=> ['context', 'longitude', 'latitude'],
            'getdriverassignedzone'=> ['driver_id']
        ],
        'settings'=> [
            'getval'=> ['context']
        ]
    ];

}
