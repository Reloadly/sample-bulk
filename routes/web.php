<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/','/login');

Route::view('/login','dashboard.login')->name('login');
Route::post('/login','AuthController@login');
Route::get('/logout','AuthController@logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', 'SettingsController@index');
    Route::post('/settings', 'SettingsController@save');
    Route::post('/settings/logo/upload', 'SettingsController@uploadLogo');
    Route::get('/dashboard','DashboardController@index');
    Route::get('dashboard/stats/topups/amounts','DashboardController@statsTopupAmount');
    Route::get('/countries', 'CountriesController@index');
    Route::get('/topups', 'TopupsController@index');
    Route::get('/topups/{id}/pin_detail', 'TopupsController@getPinDetail');
    Route::get('/topups/{id}/failed', 'TopupsController@getFailedDetail');
    Route::post('/topups/{id}/retry', 'TopupsController@retryTopup');
    Route::post('/file/upload','DropzoneController@upload');
    Route::get('/wizard', 'WizardController@index');
    Route::get('/wizard/template', 'WizardController@getTemplate');
    Route::get('/wizard/start/file/{id}', 'WizardController@start');
    Route::post('/wizard/start/file/{id}', 'WizardController@process');
    Route::post('/wizard/entry/delete/{id}', 'WizardController@deleteEntry');
    Route::get('/wizard/schedule/file/{id}','WizardController@schedule');
    Route::post('/wizard/schedule/file/{id}','WizardController@scheduleTopup');
});
