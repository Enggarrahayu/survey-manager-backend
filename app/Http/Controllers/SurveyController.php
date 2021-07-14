<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Survey;

class SurveyController extends Controller
{
    public function runSurvey($slug)
    {
        $survey = Survey::where('slug', $slug)->firstOrFail();

        return view('survey-manager::survey', [
            'survey'    =>  $survey,
        ]);
    }
}
