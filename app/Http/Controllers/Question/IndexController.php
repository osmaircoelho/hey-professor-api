<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;

class IndexController extends Controller
{
    public function __invoke()
    {

        $search = request()->q;

        $questions = Question::query()
            ->published()
            ->search($search)
            ->withSum('votes', 'like')
            ->withSum('votes', 'unlike')
            ->paginate();

        return QuestionResource::collection($questions);
    }
}
