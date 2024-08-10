<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Dashboard.index');
});



Route::get('/auth/login', function () {
    return view('Auth/login');
});