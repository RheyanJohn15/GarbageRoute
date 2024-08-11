<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Dashboard.index');
})->name('dashboard');



Route::get('/auth/login', function () {
    return view('Auth/login');
});