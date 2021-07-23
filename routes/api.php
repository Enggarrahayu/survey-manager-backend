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


Route::group(
    ['middleware' => 'cors', 'json.response',
     'namespace'     =>  'App\Http\Controllers\API',
    ], 
    function(){
        Route::post('login', 'UserController@login');
        Route::post('register', 'UserController@register');
}); 
Route::group(
    ['middleware' => 'auth:api',
     'namespace'     =>  'App\Http\Controllers\Teamwork',
    ], 
    function(){
    Route::resource('/team', 'TeamController', ['only' => [
     'store', 'index',
    ]]);
    Route::post('members/{id}', 'TeamMemberController@invite');

});
Route::group(
    ['middleware' => 'auth:api',
     'namespace'     =>  'App\Http\Controllers\API',
    ], 
    function(){
    Route::get('user/detail', 'UserController@details');
    Route::post('logout', 'UserController@logout');
    Route::resource('/survey', 'SurveyAPIController', ['only' => [
        'index', 'store', 'update', 'destroy', 'show',
    ]]);
    Route::resource('/team', TeamController::class, ['only' => [
        'index', 'store', 'update', 'destroy', 'show',
    ]]);
    Route::resource('/survey/{survey}/result', 'SurveyResultAPIController');
}); 

// Route::group(
//     [
//         'middleware'    => 'auth:api',
//         'namespace'     =>  'App\Http\Controllers\API',
//         // 'middleware'    =>  config('survey-manager.api_middleware'),
//         // 'prefix'        =>  config('survey-manager.api_prefix'),
//     ],
//     function (){
//         Route::resource('/survey', 'SurveyAPIController', ['only' => [
//             'index', 'store', 'update', 'destroy', 'show',
//         ]]);
//         Route::resource('/survey/{survey}/result', 'SurveyResultAPIController');
        
//     }
// );

