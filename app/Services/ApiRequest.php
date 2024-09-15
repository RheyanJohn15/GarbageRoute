<?php
namespace App\Services;
use App\Services\V1\TruckDriver;
use App\Services\V1\DumpTruck;
use App\Services\V1\Routes;
use App\Services\V1\ComplaintsClass;
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
        case 'routes':
           $result = new Routes($method,$request);
           break;
         
         case 'complaints':
            $result = new ComplaintsClass($method, $request);
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