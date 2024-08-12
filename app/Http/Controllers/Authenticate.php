<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Auth;
class Authenticate extends Controller
{
    public function Dashboard(){
       return $this->redirectRoute('index');
    }

    public function Routes(){
        return $this->redirectRoute('routes');
    }

    public function TruckRegister(){
       return $this->redirectRoute('truckregister');
    }

    public function TruckDriver(){
        return $this->redirectRoute('truckdriver');
    }

    public function Complaints(){
        return $this->redirectRoute('complaints');
    }
    
    public function Waypoints(){
        return $this->redirectRoute('waypoints');
    }

    public function MapNavigator(){
        return $this->redirectRoute('mapnavigator');
    }

    /*
    *Author: Rheyan John Blanco
    *Date: August, 12, 2024
    *Description: Check if the use is authenticated and redirect to login page if not
    *Params: $routes - name of blade file to redirect if the use is authenticated
    */
    private function redirectRoute($routes){
        $auth = new Auth();
        if($auth->checkAuth()){
            return view('Dashboard.'. $routes);
        }
        return redirect()->route('login');
    }
}
