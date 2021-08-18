<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamInvites;
use Illuminate\Support\Facades\Auth;
use Mpociot\Teamwork\TeamInvite;
use Validator;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth', ['except' => ['login', 'register']]);
    // }

    public $successStatus = 200;

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $name = $request->input('username');
        
        $team = new Team;
        $team->name = $name. '-team';
        $team->owner_id = $user->id;
        $team->team_default = 1;
        $team->save();

        TeamInvites::where('email', $user->email)
                        ->update(['user_id' => $user->id]);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['username'] =  $user->username;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
} 