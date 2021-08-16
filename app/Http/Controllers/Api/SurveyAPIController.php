<?php

namespace App\Http\Controllers\API;
use Illuminate\Routing\Controller;
use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResource;
use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\CreateSurveyRequest;
use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\UpdateSurveyRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;

class SurveyAPIController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $surveys = Survey::where('user_id', Auth::user()->id)->latest()->paginate(config('survey-manager.pagination_perPage', 10));
        return SurveyResource::collection($surveys, 200);
    }

    public function show($id)
    {
        $survey = Survey::find($id);

        if (is_null($survey)) {
            return response()->json('Survey not found', 404);
        }

        return response()->json([
            'data'      =>  new SurveyResource($survey),
            'message'   =>  'Survey successfully retrieved',
        ]);
    }

    public function store(CreateSurveyRequest $request)
    {
        // $survey = Survey::create($request->all());
        $team_name = $request->input('team_name');
        $username = Auth::user()->username;
        $default_team = $username. '-team';
        if(is_null($team_name)){
            $team_id = Team::where('name', $default_team)->first()->id;
        } else{
            $team_id  =  Team::where('name', $team_name)->first()->id;
        }
        $survey = Survey::create([
            'name'          =>  $request->input('name'),
            'json'          =>  $request->input('json'),
            'user_id'       =>  Auth::id(),
            'survey_status' =>  0,
            'team_id'       => $team_id,
            
        ]);

        return response()->json([
            'data'      =>  new SurveyResource($survey),
            'message'   =>  'Survey successfully create',
        ], 201);
    }

    public function update($id, UpdateSurveyRequest $request)
    {
        $survey = Survey::find($id);

        if (is_null($survey)) {
            return response()->json('Survey not found', 404);
        }

        $survey->update($request->all());

        return response()->json([
            'data'      =>  new SurveyResource($survey),
            'message'   =>  'Survey successfully updated',
        ]);
    }

    public function destroy($id)
    {
        $survey = Survey::find($id);

        if (is_null($survey)) {
            return response()->json('Survey not found', 404);
        }
        $survey->delete();

        return response()->json([
            'data' => $id,
            'message' => 'Survey deleted successfully',
        ], 200);
    }
}
