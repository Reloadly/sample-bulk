<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('countries', 'CountriesController@getAll');
Route::get('countries/{id}/operators', 'CountriesController@getOperatorsForCountry');
Route::get('operators/{id}', 'OperatorsController@get');
Route::get('countries/{id}/operators/detect/{number}', 'OperatorsController@detect');
Route::get('currencies', 'CurrenciesController@getAll');
Route::get('subscriptions', 'NumbersController@getAllSubscriptions');
