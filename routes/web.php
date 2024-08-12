<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authenticate;

Route::get('/', [Authenticate::class, 'Dashboard'])->name('dashboard');
Route::get('/routes', function () {
    return view('Dashboard.routes');
})->name('routes');
Route::get('/complaints', function () {
    return view('Dashboard.complaints');
})->name('complaints');
Route::get('/mapnavigator', function () {
    return view('Dashboard.mapnavigator');
})->name('mapnavigator');
Route::get('/truckdriver', function () {
    return view('Dashboard.truckdriver');
})->name('truckdriver');
Route::get('/truckregister', function () {
    return view('Dashboard.truckregister');
})->name('truckregister');
Route::get('/useraccounts', function () {
    return view('Dashboard.useraccounts');
})->name('useraccounts');
Route::get('/waypoints', function () {
    return view('Dashboard.waypoints');
})->name('waypoints');






Route::get('/auth/login', function () {
    return view('Auth/login');
})->name('login');