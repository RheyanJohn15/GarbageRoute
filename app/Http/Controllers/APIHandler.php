<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Auth;

class APIHandler extends Controller
{
    public function APIEntryGet(Request $req, string $data, string $method){

    }

    public function APIEntryPost(Request $req, string $data, string $method){
        if($method === 'login' && $data === 'user'){
           $check = new Auth($method, $req);
           return response()->json($check->auth());
        }
        
    }
}
