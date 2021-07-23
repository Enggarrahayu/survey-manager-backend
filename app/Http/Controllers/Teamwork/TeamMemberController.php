<?php

namespace App\Http\Controllers\Teamwork;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamInvitationResource;
use App\Http\Teamwork\Teamwork;
// use Mpociot\Teamwork\Facades\Teamwork;
use Mpociot\Teamwork\TeamInvite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeamMemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the members of the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function invite(Request $request, $team_id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($team_id);
        $teamwork = app('App\Http\Teamwork\Teamwork');
        $teamwork->inviteToTeam($request->email, $team, function ($invite) {
            
        });
        return response()->json([
            'message'   =>  'User already requested to join the team ',
        ], 201);
    
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


}
