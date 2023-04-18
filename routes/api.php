<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\GarageInfoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/garageinfo/get/byownerid/{ig_owner_id}', 'App\Http\Controllers\API\GarageInfoController@getInfoByIgOwnerId');

Route::get('/garageinfo/get/byownercountry/{ioc_country}', 'App\Http\Controllers\API\GarageInfoController@getInfoByIgOwnerCountry');

Route::get('/garageinfo/get/byclientlocation/{standard_of_distance}/{distance_range}', 'App\Http\Controllers\API\GarageInfoController@getGarageInfoByClientLocation')->where(['standard_of_distance' => '(m|km)', 'distance_range' => '[0-9]+']);

Route::get('/garageinfo/get/all/{ig_no}', 'App\Http\Controllers\API\GarageInfoController@getInfoGarage');

Route::get('/garageinfo/get/lowerthan/{country}/{cost}', 'App\Http\Controllers\API\GarageInfoController@findCheapest');

Route::post('/garageinfo/update/reserve/{ig_no}/{ig_rental_id}/{timestandard}/{number}', 'App\Http\Controllers\API\GarageInfoController@reserveGarage');

