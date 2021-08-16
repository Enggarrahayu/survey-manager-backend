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
    [
    'middleware'    =>  'cors', 'json.response',
     'namespace'     =>  'App\Http\Controllers',
    ], 
    function(){
        Route::get('redirect', 'SocialController@redirect');
        Route::get('callback', 'SocialController@callback');
});


Route::group(
    ['middleware' => 'auth:api',
     'namespace'     =>  'App\Http\Controllers\Teamwork',
    ], 
    function(){
    Route::resource('/team', 'TeamController', ['only' => [
     'store', 'index', 'destroy', 'update'
    ]]);
    Route::resource('/member', 'TeamMemberController', ['only' => [
        'index', 'store', 'update', 'destroy', 'show',
       ]]);
    Route::get('team/acceptInvitation/{id}', ['App\Http\Controllers\Teamwork\TeamMemberController', 'acceptInvite'])->name('teams.members.accept');
    Route::post('member/{id}', 'TeamMemberController@invite');
    Route::get('team/pendingInvitations', 'TeamMemberController@pendingInvite');
    Route::get('team/pendingMember/{id}', 'TeamController@pendingMember');
    Route::get('team/surveyTeam/{id}', 'TeamController@showSurveyByTeam');
    Route::get('ownedTeam', 'TeamMemberController@ownedTeam');
    Route::delete('member/{team_id}/{user_id}', [
        'App\Http\Controllers\Teamwork\TeamMemberController', 'destroy'
        ])->name('teams.members.destroy');
});

Route::get('team/acceptInvitation/{id}', ['App\Http\Controllers\Teamwork\TeamMemberController', 'acceptInvite'])->name('teams.members.accept');

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

