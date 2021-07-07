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
Route::post('login', 'App\Http\Controllers\API\UserController@login');
Route::post('register', 'App\Http\Controllers\API\UserController@register');
Route::post('survey/create', 'App\Http\Controllers\API\SurveyController@postSurvey');
Route::get('survey/{id}', 'App\Http\Controllers\API\SurveyController@detail');
Route::post('survey/{id}', 'App\Http\Controllers\API\SurveyController@addQuestion');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user/detail', 'App\Http\Controllers\API\UserController@details');
    Route::post('logout', 'App\Http\Controllers\API\UserController@logout');
}); 