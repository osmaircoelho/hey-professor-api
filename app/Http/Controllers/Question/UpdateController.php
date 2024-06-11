<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\UpdateRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class UpdateController extends Controller
{

    public function __invoke(UpdateRequest $request, Question $question): QuestionResource
    {


        $question->question = $request->question;
        $question->save();
        return QuestionResource::make($question);
    }
}
