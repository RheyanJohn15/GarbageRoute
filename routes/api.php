<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIHandler;
use App\Models\ActiveDrivers;
use App\Models\ZoneDrivers;
use App\Models\TruckDriverModel;
use App\Models\DumpTruckModel;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => ['web']], function () {
    Route::get('/getactivedriver', function(){
        $activeDrivers = ActiveDrivers::where('status', 'active')->get();

         foreach ($activeDrivers as $active) {
             $driver = TruckDriverModel::where('td_id', $active->td_id)->first();
             $zone = ZoneDrivers::where('td_id', $active->td_id)
                 ->join('zones', 'zones.zone_id', '=', 'zone_drivers.zone_id')
                 ->first();
             $dumptruck = DumpTruckModel::where('td_id', $active->td_id)->first();

             $active->driver = $driver;
             $active->zone = $zone;
             $active->truck = $dumptruck;
         }

         return [
             'message' => 'success',
             'data' => $activeDrivers,
         ];
    });
   Route::get('/get/{data}/{method}', [APIHandler::class, 'APIEntryGet'])->name('APIGet');
   Route::post('/post/{data}/{method}', [APIHandler::class, 'APIEntryPost'])->name('APIPost');
});

