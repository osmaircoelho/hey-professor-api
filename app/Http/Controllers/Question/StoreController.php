<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Question;
use Database\Seeders\QuestionSeeder;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request)
    {
       Question::create([
           'question' => $request->question,
           'user_id' => auth()->user()->id
       ]);
    }
}
