<?php

use App\Events\GpsUpdate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authenticate;
use App\Http\Middleware\DriverAuth;

Route::get('/dashboard', [Authenticate::class, 'Dashboard'])->name('dashboard');
Route::get('/routes', [Authenticate::class, 'Routes'])->name('routes');
Route::get('/complaints', [Authenticate::class, 'Complaints'])->name('complaints');
Route::get('/mapnavigator', [Authenticate::class, 'MapNavigator'])->name('mapnavigator');
Route::get('/truckdriver', [Authenticate::class, 'TruckDriver'])->name('truckdriver');
Route::get('/truckregister', [Authenticate::class, 'TruckRegister'])->name('truckregister');
Route::get('/profile', [Authenticate::class, 'Profile']);
Route::get('/settings', [Authenticate::class, 'Settings']);
Route::get('/viewtruck', [Authenticate::class, 'ViewTruck']);
Route::get('/viewdriver', [Authenticate::class, 'ViewDriver']);
Route::get('/activity-logs', [Authenticate::class, 'ActivityLogs']);

Route::get('/', function () {return view('User.index');})->name('home');
Route::get('/user/complaint', function () {return view('User.complaint');})->name('usercomplaints');

Route::middleware([DriverAuth::class])->group( function () {
    Route::get('/user/driver/dashboard', function () {return view('User.dashboard');})->name('userDashboard');
    Route::get('/user/driver/profile', function () {return view('User.profile');});
    Route::get('/user/driver/settings', function () {return view('User.settings');});
});


Route::get('/auth/login', function () {
    return view('Auth.login');
})->name('login');

Route::get('/auth/driver/login', function () {
    return view('User/login');
})->name('userLogin');

//Get CSRF Token
Route::get('/csrf-token', function () {
    return csrf_token();
});


Route::get('/automate/geoson', function () {
    return view('Automate.geoson');
});
