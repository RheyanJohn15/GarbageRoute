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
    
    public function MapNavigator(Request $req){
        return $this->redirectRoute('mapnavigator', $req);
    }

    public function Profile(Request $req){
        return $this->redirectRoute('profile', $req);
    }

    public function Settings(Request $req){
        return $this->redirectRoute('settings', $req);
    }

    public function ViewTruck(Request $req){
        return $this->redirectRoute('viewtruck', $req);
    }
    public function ViewDriver(Request $req){
        return $this->redirectRoute('viewDriver', $req);
    }
    
    private function redirectRoute($routes, $req){
        $auth = new Auth();
        return $auth->checkAuth($req) ? view('Dashboard.'. $routes) : redirect()->route('login');
    }
}
