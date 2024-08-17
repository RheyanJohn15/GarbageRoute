<?php
namespace App\Services;
use App\Services\V1\TruckDriver;
use App\Services\V1\DumpTruck;
use App\Services\ApiException;
class ApiRequest{

    private $RESPONSE;
    public function __construct(string $type, string $method, $request)
    {
       switch($type){
        case 'truckdriver':
           $result = new TruckDriver($method,$request);
           break;
        case 'dumptruck':
           $result = new DumpTruck($method,$request);
           break;
        default:
          throw new ApiException(ApiException::NOT_VALID_TYPE);
          break;
       }

       $this->RESPONSE = $result->getResult();
    }


    public function getResponse(){
        return $this->RESPONSE;
    }
}