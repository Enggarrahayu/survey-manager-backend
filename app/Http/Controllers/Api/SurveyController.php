<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Support\Facades\Auth;
use Validator;

class SurveyController extends Controller
{

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
} 