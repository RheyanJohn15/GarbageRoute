<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\V1\Auth;
use App\Services\ApiEntry;
use App\Services\ApiException;
use App\Services\V1\Logger;
use App\Models\ActiveDrivers;
use App\Models\TruckDriverModel;

class APIHandler extends Controller
{
    public function APIEntryGet(Request $req, string $data, string $method){

        $check = new Auth($method, $req);

        if($data== 'request' && $method == 'accesstoken'){
            return response()->json(['success'=> true, "access_token"=> $check->getAccessToken()]);
        }

        if($data == 'auth' && $method == 'info'){
            return response()->json(['success'=>true,"data"=> $check->getUserInfo()]);
        }

        return response()->json($this->isAuthenticated($check, $req, $data, $method, 'get'));
    }

    public function APIEntryPost(Request $req, string $data, string $method){
        $check = new Auth($method, $req);

        if($method === 'login' && $data === 'user' ){

            $response = $check->auth();
           if($response['status']== 'success'){
            $req->session()->put('access_token', $response['result']);
           }
           return response()->json($response);
        }

        if($method === 'login' && $data === 'userdriver' ){

            $response = $check->authdriver();
           if($response['status']== 'success'){
            $req->session()->put('driver_access_token', $response['result']);
           }
           return response()->json($response);
        }


        if($method === 'logout' && $data === 'user'){
            Logger::save(['Logout', 'Successfully Logged Out'], $req);
            $req->session()->flush();
            return response()->json(["status"=>"success", "method"=> "logout", "message"=> "Logout Successfully" ]);
        }

        if($method === 'logout' && $data === 'drivers'){

            $driver = TruckDriverModel::where('access_token', session('driver_access_token'))->first();

            $activeDriver = ActiveDrivers::where('td_id', $driver->td_id)->first();

            $activeDriver->delete();

            $req->session()->flush();
            return response()->json(["status"=>"success", "method"=> "logout", "message"=> "Logout Successfully" ]);
        }

        return response()->json($this->isAuthenticated($check, $req, $data, $method, 'post'));

    }

    private function isAuthenticated($check, $req, $data, $method, $reqType){
        if($check->checkAuth($req)
        || ($data == "complaints" && $method == "submit")
        || ($data == "complaints" && $method == 'getzone')
        || ($data == 'landing' && $method   == 'dashboard')
        || ($data == 'landing' && $method == 'loadschedule')
        ){
            $entry = new ApiEntry($data, $method, $req, $reqType);
            return $entry->getResponse();
        }else{
            throw new ApiException(ApiException::NOT_AUTHENTICATED);
        }
    }
}
