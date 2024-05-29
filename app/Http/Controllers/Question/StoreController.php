<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $question = Question::create([
           'user_id' => auth()->user()->id,
            'status' => 'draft',
            'question' => $request->question
        ]);

        return QuestionResource::make($question);
    }
}
