<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\V1\Auth;
class Authenticate extends Controller
{
    public function Dashboard(Request $req){
       return $this->redirectRoute('index', $req);
    }

    public function Routes(Request $req){
        return $this->redirectRoute('routes', $req);
    }

    public function TruckRegister(Request $req){
       return $this->redirectRoute('truckregister', $req);
    }

    public function TruckDriver(Request $req){
        return $this->redirectRoute('truckdriver', $req);
    }

    public function Complaints(Request $req){
        return $this->redirectRoute('complaints', $req);
    }
    
    public function Waypoints(Request $req){
        return $this->redirectRoute('waypoints', $req);
    }

    public function MapNavigator(Request $req){
        return $this->redirectRoute('mapnavigator', $req);
    }

    public function UserAccount(Request $req){
        return $this->redirectRoute('useraccounts', $req);
    }



    /*
    *Author: Rheyan John Blanco
    *Date: August, 12, 2024
    *Description: Check if the use is authenticated and redirect to login page if not
    *Params: $routes - name of blade file to redirect if the use is authenticated
    */
    private function redirectRoute($routes, $req){
        $auth = new Auth();
        return $auth->checkAuth($req) ? view('Dashboard.'. $routes) : redirect()->route('login');
    }
}
