<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIHandler;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => ['web']], function () {
   Route::get('/get/{data}/{method}', [APIHandler::class, 'APIEntryGet'])->name('APIGet');
   Route::post('/post/{data}/{method}', [APIHandler::class, 'APIEntryPost'])->name('APIPost');
});

