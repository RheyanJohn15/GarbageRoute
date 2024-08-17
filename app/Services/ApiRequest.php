<?php
namespace App\Services;
use App\Services\V1\TruckDriver;
use App\Services\ApiException;
class ApiRequest{

    private $RESPONSE;
    public function __construct(string $type, string $method, $request)
    {
       switch($type){
        case 'truckdriver':
           $result = new TruckDriver($method,$request);
           $this->RESPONSE = $result->getResult();
           break;
        default:
          throw new ApiException(ApiException::NOT_VALID_TYPE);
       }
    }


    public function getResponse(){
        return $this->RESPONSE;
    }
}