<?php

namespace App\Http\Controllers\Teamwork;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamInvitationResource;
use App\Http\Resources\MemberResource;
use App\Http\Resources\AcceptInvitationResource;
use App\Models\User;
use App\Models\Team;
use App\Models\TeamInvites;
use App\Models\Team;
use App\Mail\SendMail;
use App\Models\TeamUser;
use Mpociot\Teamwork\TeamInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeamMemberController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function user()
    {
        return $this->app->auth->user();
    }

    /**
     * Show the members of the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teamUserModel = new TeamUser;
        $member = $teamUserModel
                    ->where('team_id', $id)
                    ->get();
        return MemberResource::collection($member);
    }
    public function destroy($team_id, $user_id)
    {
        $teamModel = new Team;
        $team = $teamModel::findOrFail($team_id);

        $userModel = new User;
        $user = $userModel::findOrFail($user_id);

        $team_user = $user->detachTeam($team);
        $team_user;
        return response()->json([
            'user_id' => $user_id,
            'team_id' => $team_id,
            'message' => 'Member deleted successfully',
        ], 200);
    }
    public function invite(Request $request, $team_id)
    {
        if(TeamInvites::where('email', '=', request('email'))->where('team_id', $team_id)->where('invitation_status', 1)->exists()){
            return response()->json([
                'message'   =>  'User already join as a team member',
            ], 404);
         }

        if (User::where('email', '=', request('email'))->exists()) {
            $request->validate([
                'email' => 'required|email',
            ]);
    
            $teamModel = config('teamwork.team_model');
            $team = $teamModel::findOrFail($team_id);
           
            $teamwork = app('App\Http\Teamwork\Teamwork');
            $teamwork->inviteToTeam($request->email, $team, function ($invite) {
                $team_name = $invite->team->name;
                $team_id = $invite->team->id;
                $owner_id = $invite->team->owner_id;
                $team_owner = User::where('id', $owner_id)->first()->username;
                $accept_id = TeamInvites::where('email', request('email'))
                                        ->where('team_id', $team_id)
                                        ->first()->invitation_key;
                $username = User::where('email', request('email'))->first()->username;
                Mail::to($invite->email)->send(new SendMail($username, $team_name, $accept_id, $team_owner));
            });
            return response()->json([
                'data'      =>  new TeamResource($team),
                'message'   =>  'Successfully sent invitation request to user ',
            ], 201);
         }
         else{
            return response()->json([
                'message'   =>  'There is no user registered with this email',
            ], 404);
         }
       
    }

    public function pendingInvite()
    {
        $pending  = DB::table('team_invites')
                    ->where('user_id', Auth::user()->id)
                    ->where('invitation_status', 0)
                    ->join('users', 'users.id', '=' ,'team_invites.user_id')
                    ->join('teams', 'teams.id', '=' , 'team_invites.team_id')
                    ->get();

		return TeamInvitationResource::collection($pending);
    }

    public function ownedTeam(){
        $owned = DB::table('teams')
                ->where('owner_id', Auth::user()->id)
                ->get();  
        return TeamResource::collection($owned);
    }

    public function acceptInvite($invitation_key){
        $team_user = new TeamUser;
        $team_invite = TeamInvites::where('invitation_key',$invitation_key)->first();
        $team_invite->invitation_status = 1;
        $team_invite->update();

        $team_id = $team_invite->team_id;
        $user_id = $team_invite->user_id;
        if(!TeamUser::where('user_id', $user_id)->where('team_id', $team_id)->exists()){
            $team_user->user_id = $user_id;
            $team_user->team_id = $team_id;
            $team_user->save();
        }
        return redirect()->away('http://localhost:8080');
    }

    public function acceptInvites($invitation_key){
        $team_user = new TeamUser;
        $team_invite = TeamInvites::where('invitation_key',$invitation_key)->first();
        $team_invite->invitation_status = 1;
        $team_invite->update();

        $team_user->user_id = $team_invite->user_id;
        $team_user->team_id = $team_invite->team_id;
        $team_user->save();


          return response()->json([
                'data'      =>  new AcceptInvitationResource($team_invite),
                'message'   =>  'Successfully joined to the team',
            ], 201);
    }
}
