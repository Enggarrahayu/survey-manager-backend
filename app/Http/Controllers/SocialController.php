<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class SocialController extends Controller
{
    public $successStatus = 200;
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
          if (Auth::check()) {
           return redirect()->away('http://localhost:8080/surveylist');
        }
 
        $oauthUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('google_id', $oauthUser->id)->first();
        // $user = User::query()->firstOrNew(['email' => $oauthUser->getEmail()]);
        // if (!$user->exists){
        //     $user->username = $oauthUser->name;
        //     $user->email = $oauthUser->email;
        //     $user->google_id = $oauthUser->id;
        //     $user->password = md5($oauthUser->token);
        //     $user->save();
        // }
        // if(Auth::loginUsingId($user->id)){
        //     $user = Auth::user();
        //     $success['token'] =  $user->createToken('nApp')->accessToken;
        //     return response()->json(['success' => $success], $this->successStatus);
        // }
      
        if ($user) {
            Auth::loginUsingId($user->id);
            return redirect()->away('http://localhost:8080/surveylist');
        } else {
            $newUser = User::create([
                'username' => $oauthUser->name,
                'email' => $oauthUser->email,
                'google_id'=> $oauthUser->id,
                'password' => md5($oauthUser->token),
            ]);
            Auth::login($user);
            $success['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
            return redirect()->away('http://localhost:8080/surveylist');
         }
    }
    
}
