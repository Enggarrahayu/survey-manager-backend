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

Route::post('register', 'App\Http\Controllers\API\UserController@register');
Route::post('survey/create', 'App\Http\Controllers\API\SurveyController@postSurvey');
Route::post('survey/{id}', 'App\Http\Controllers\API\SurveyController@addQuestion');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user/detail', 'App\Http\Controllers\API\UserController@details');
    Route::post('logout', 'App\Http\Controllers\API\UserController@logout');
}); 

Route::group(
    [
        'middleware'    => 'auth:api',
        'namespace'     =>  'App\Http\Controllers\API',
        // 'middleware'    =>  config('survey-manager.api_middleware'),
        // 'prefix'        =>  config('survey-manager.api_prefix'),
    ],
    function (){
        Route::post('login', 'UserController@login');
        Route::resource('/survey', 'SurveyAPIController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show',
        ]]);
        Route::resource('/survey/{survey}/result', 'SurveyResultAPIController');
        
    }
);

