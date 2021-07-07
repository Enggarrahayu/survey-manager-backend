<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Validator;

class SurveyController extends Controller
{
    private $question;
    public $successStatus = 200;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function postSurvey(Request $request)
    {
        //validate data
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'category'   => 'required',
        ],
            [
                'name.required' => 'You Have to Input Survey Name !',
                'category.required' => 'You Have To Choose Survey Category !',
            ]
        );

        if($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Please Fill The Empty Fields',
                'data'    => $validator->errors()
            ],401);

        } else {

            $post = Survey::create([
                'name'     => $request->input('name'),
                'category'   => $request->input('category')
            ]);

            if ($post) {
                return response()->json([
                    'success' => true,
                    'message' => ' Survey Created Successfully!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed!',
                ], 400);
            }
        }
    }

    public function detail($id)
    {
        $survey = Survey::find($id);
        if($survey){
            return response()->json(['success' => $survey], $this->successStatus);
        }
            return response()->json(['error'=>'Survey ID not found'], 401);
    }

    public function addQuestion(Request $request , $id){
        $surveys = Survey::where('id', $id)->first();
        $survey = Survey::find($id);
            if($survey)
            {
                $this->question->question = $request->get('question');
                $this->question->type = $request->get('type');
                $this->question->survey_id = $surveys->id;

                $this->question->save();
                return response()->json(['success' => $survey], $this->successStatus);
            }
        }
    }


