<?php

namespace App\Http\Controllers\Teamwork;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\TeamResource;
use App\Http\Resources\PendingMemberResource;
use App\Models\Survey;
use App\Models\Team;
use App\Models\TeamInvites;
use App\Models\TeamUser;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\TeamInvitationResource;
use App\Http\Resources\SurveyTeamResource;
use App\Http\Resources\TeamUserResource;
use Illuminate\Support\Facades\Auth;

use Mpociot\Teamwork\Exceptions\UserNotInTeamException;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = DB::table('team_user')
            ->where('user_id', Auth::user()->id)
            ->get();
            
            return TeamUserResource::collection($teams, 200); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string',
        ]);

        $teamModel = new Team;

        $team = $teamModel::create([
            'name' => $request->team_name,
            'owner_id' => $request->user()->getKey(),
        ]);

        // $teamUser = new TeamUser;
        // $request->user()->attachTeam($team);

        return response()->json([
            'data'      =>  new TeamResource($team),
            'message'   =>  'Team successfully created',
        ], 201);
    public function update(Request $request, $id)
    {
        $request->validate([
            'team_name' => 'required|string',
        ]);

        $teamModel = new Team;

        $team = $teamModel::findOrFail($id);
        $team->name = $request->team_name;
        $team->save();

        return response()->json([
            'data'    =>  new TeamResource($team),
            'message' => 'Team edited successfully',
        ]);
    }

    /**
     * Switch to the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $teamUserModel = new TeamUser;
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);
        if($team->team_default != 1){
            $team->delete();

            $teamUser = $teamUserModel::where('team_id', $id);
            $teamUser->delete();

            return response()->json([
                'data'    =>  new TeamResource($team),
                'message' => 'Team deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Team default can not be deleted',
            ], 200);
        }
           

    }
    public function showSurveyByTeam($id){
        $surveyModel = new Survey;
        $surveyTeam = $surveyModel
                        ->where('team_id', $id)
                        ->where('survey_status', 0)
                        ->get();
        
        $collection = SurveyTeamResource::collection($surveyTeam);
        return response()->json([
            'data'    =>  $collection,
        ], 200);

    }
    public function pendingMember($id){
        $team_invites = new TeamInvites;
        $pendingMember = $team_invites
                ->where('team_id', $id)
                ->where('invitation_status', 0)
                ->get();

        $collection =  PendingMemberResource::collection($pendingMember);                
        return response()->json([
            'data'    =>  $collection,
        ], 200);

    }
    
}
