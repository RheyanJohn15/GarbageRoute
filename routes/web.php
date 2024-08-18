<?php

use App\Events\GpsUpdate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authenticate;

Route::get('/', [Authenticate::class, 'Dashboard'])->name('dashboard');
Route::get('/routes', [Authenticate::class, 'Routes'])->name('routes');
Route::get('/complaints', [Authenticate::class, 'Complaints'])->name('complaints');
Route::get('/mapnavigator', [Authenticate::class, 'MapNavigator'])->name('mapnavigator');
Route::get('/truckdriver', [Authenticate::class, 'TruckDriver'])->name('truckdriver');
Route::get('/truckregister', [Authenticate::class, 'TruckRegister'])->name('truckregister');
Route::get('/useraccounts', [Authenticate::class, 'UserAccount'])->name('useraccounts');
Route::get('/waypoints', [Authenticate::class, 'Waypoints'])->name('waypoints');

Route::get('/auth/login', function () {
    return view('Auth/login');
})->name('login');


//Get CSRF Token
Route::get('/csrf-token', function () {
    return csrf_token();
});

Route::get('/test', function(){
  event(new GpsUpdate('this is test'));

  return 'done';
});