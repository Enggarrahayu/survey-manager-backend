<?php

namespace App\Http\Controllers\API;
use Illuminate\Routing\Controller;
use AidynMakhataev\LaravelSurveyJs\app\Models\Survey;
use AidynMakhataev\LaravelSurveyJs\app\Http\Resources\SurveyResource;
use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\CreateSurveyRequest;
use AidynMakhataev\LaravelSurveyJs\app\Http\Requests\UpdateSurveyRequest;
use Illuminate\Support\Facades\Auth;

class SurveyAPIController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $surveys = Survey::where('user_id', Auth::user()->id)->latest()->paginate(config('survey-manager.pagination_perPage', 10));
        return SurveyResource::collection($surveys);
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
        $survey = Survey::create([
            'name'          =>  $request->input('name'),
            'json'          =>  $request->input('json'),
            'user_id'       =>  Auth::id(),
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
