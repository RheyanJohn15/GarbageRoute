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
Route::get('/profile', [Authenticate::class, 'Profile']);
Route::get('/settings', [Authenticate::class, 'Settings']);

Route::get('/home', function () {return view('User.index');})->name('home');
Route::get('/user/complaint', function () {return view('User.complaint');})->name('usercomplaints');
Route::get('/user/driver/dashboard', function () {return view('User.dashboard');})->name('userDashboard');
Route::get('/user/driver/routejourney', function () {return view('User.journey');})->name('userJourney');
Route::get('/user/driver/history', function () {return view('User.history');})->name('userHistory');

Route::get('/auth/login', function () {
    return view('Auth.login');
})->name('login');

Route::get('/auth/driver/login', function () {
    return view('User/login');
})->name('userL ogin');

//Get CSRF Token
Route::get('/csrf-token', function () {
    return csrf_token();
});

Route::get('/test', function(){
  event(new GpsUpdate('this is test'));

  return 'done';
});