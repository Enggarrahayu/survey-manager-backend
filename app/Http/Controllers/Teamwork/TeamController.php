<?php

namespace App\Http\Controllers\Teamwork;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Models\Survey;
use App\Models\TeamUser;
use App\Http\Resources\SurveyTeamResource;
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $teamModel = config('teamwork.team_model');

        $team = $teamModel::create([
            'name' => $request->name,
            'owner_id' => $request->user()->getKey(),
        ]);

        // $teamUser = new TeamUser;
        // $request->user()->attachTeam($team);

        return response()->json([
            'data'      =>  new TeamResource($team),
            'message'   =>  'Team successfully created',
        ]);
    }

    /**
     * Switch to the given team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showSurveyByTeam($id){
        $surveyModel = new Survey;
        $surveyTeam = $surveyModel
                        ->where('team_id', $id)
                        ->where('survey_status', 0)
                        ->get();
        
        return SurveyTeamResource::collection($surveyTeam);
    }
}
