<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $id)
    {
        $question = Question::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $question);

        $question->restore();

        return response()->noContent();
    }
}
