<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class MineController extends Controller
{
    public function __invoke()
    {
        $status = request()->status;

        Validator::validate(
            ['status' => $status],
            ['status' => ['required', 'status' => 'in:draft,published,archived']]
        );

        $questions = user()
            ->questions()
            ->when(
                $status == 'archived',
                fn (Builder $q) => $q->onlyTrashed(), // @phpstan-ignore-line
                fn (Builder $q) => $q->whereStatus($status) // @phpstan-ignore-line
            )
            ->get();

        return QuestionResource::collection($questions);
    }

}
