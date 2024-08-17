<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\V1\Auth;
use App\Services\ApiEntry;
use App\Services\ApiException;

class APIHandler extends Controller
{
    public function APIEntryGet(Request $req, string $data, string $method){
        $check = new Auth($method, $req);

        return response()->json($this->isAuthenticated($check, $req, $data, $method, 'get'));
    }

    public function APIEntryPost(Request $req, string $data, string $method){
        $check = new Auth($method, $req);
        $response = $check->auth();
        
        if($method === 'login' && $data === 'user'){
           if($response['status']== 'success'){
            $req->session()->put('api_token', $response['result']);
           }
           return response()->json($response);
        }
    
        return response()->json($this->isAuthenticated($check, $req, $data, $method, 'post'));
    
    }

    private function isAuthenticated($check, $req, $data, $method, $reqType){
        if($check->checkAuth($req)){
            $entry = new ApiEntry($data, $method, $req, $reqType);
            return $entry->getResponse();
        }else{
            throw new ApiException(ApiException::NOT_AUTHENTICATED);
        }
    }
}
